<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Mail\RegisterOtpMail;
use App\Models\Address;
use App\Models\Cart;
use App\Models\MembershipTier;
use App\Models\User;
use App\Services\MinigameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Các role được coi là nhân viên / quản trị viên.
     */
    private const STAFF_ROLES = ['Admin', 'Owner', 'Employee'];

    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return $this->redirectByRole(Auth::guard('web')->user());
        }
        return view('client.auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required',
        ], [
            'login.required'    => 'Vui lòng nhập email hoặc số điện thoại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $login = $request->input('login');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['login' => 'Email/SĐT hoặc mật khẩu không đúng.'])
                ->withInput($request->only('login', 'remember'));
        }

        if (!$user->IsActive) {
            return back()
                ->withErrors(['login' => 'Tài khoản của bạn đã bị khóa.'])
                ->withInput($request->only('login', 'remember'));
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        if (in_array($user->role, self::STAFF_ROLES)) {
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
        }

        // Merge session cart -> DB cart (chỉ cho khách hàng)
        $sessionCart = session('cart', []);
        foreach ($sessionCart as $variantId => $item) {
            $existing = Cart::where('userID', $user->userID)->where('variantID', $variantId)->first();
            if ($existing) {
                $existing->increment('quantity', $item['quantity']);
            } else {
                Cart::create(['userID' => $user->userID, 'variantID' => $variantId, 'quantity' => $item['quantity']]);
            }
        }
        session()->forget('cart');

        return redirect()->intended(route('client.home'))->with('success', 'Đăng nhập thành công!');
    }

    public function showRegister()
    {
        if (Auth::guard('web')->check()) {
            return $this->redirectByRole(Auth::guard('web')->user());
        }
        return view('client.auth.register');
    }
    public function showVerifyForm()
    {
        return view('client.auth.verify-email'); // Thay đổi đường dẫn view cho khớp thư mục của bạn
    }

    // API Gửi mã OTP
    public function sendOtp(Request $request)
    {
        $email = $request->input('email');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Email không hợp lệ.']);
        }

        // Check trùng tài khoản
        if (User::where('email', $email)->exists()) {
            return response()->json(['success' => false, 'message' => 'Email này đã được đăng ký tài khoản trước đó.']);
        }

        // Kiểm tra cooldown 30s chống spam liên tục
        $lastSent = Session::get('otp_last_sent_' . md5($email));
        if ($lastSent && (time() - $lastSent) < 30) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đợi 30 giây để gửi lại mã mới.']);
        }

        // Sinh mã ngẫu nhiên 6 chữ số
        $otpCode = rand(100000, 999999);

        // Lưu mã hiệu lực trong vòng 5 phút (300 giây)
        Session::put('reg_otp_' . md5($email), [
            'code' => $otpCode,
            'expires_at' => time() + 300
        ]);
        Session::put('otp_last_sent_' . md5($email), time());

        try {
            // Gọi Mailable và truyền mã OTP ($otpCode) đã sinh ngẫu nhiên vào
            Mail::to($email)->send(new RegisterOtpMail($otpCode));

            return response()->json(['success' => true, 'message' => 'Mã OTP đã được gửi thành công đến email của bạn.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Không thể gửi email. Lỗi: ' . $e->getMessage()]);
        }
    }

    // API Xác thực mã OTP
    public function verifyOtp(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');

        $otpData = Session::get('reg_otp_' . md5($email));

        if (!$otpData) {
            return response()->json(['success' => false, 'message' => 'Mã xác thực chưa được gửi hoặc yêu cầu không đúng.']);
        }

        if (time() > $otpData['expires_at']) {
            Session::forget('reg_otp_' . md5($email));
            return response()->json(['success' => false, 'message' => 'Mã xác thực đã hết hạn hiệu lực (5 phút). Vui lòng gửi lại.']);
        }

        if ($otpData['code'] == $otp) {
            // Đánh dấu Session xác thực thành công để bảo mật cho bước lưu database cuối cùng
            Session::put('email_is_verified', $email);
            Session::forget('reg_otp_' . md5($email)); // Xác thực xong thì xoá mã tạm

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Mã xác thực nhập vào không chính xác.']);
    }
    public function register(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:200',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'sex'      => 'nullable|in:Nam,Nữ,Khác',
            'birthday' => 'nullable|date|before:today',
            'password' => 'required|min:6|confirmed',
        ], [
            'fullName.required'  => 'Vui lòng nhập họ tên.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.unique'       => 'Email này đã được sử dụng.',
            'phone.required'     => 'Vui lòng nhập số điện thoại.',
            'phone.unique'       => 'Số điện thoại này đã được sử dụng.',
            'sex.in'             => 'Giới tính không hợp lệ.',
            'birthday.date'      => 'Ngày sinh không hợp lệ.',
            'birthday.before'    => 'Ngày sinh phải trước ngày hôm nay.',
            'password.required'  => 'Vui lòng nhập mật khẩu.',
            'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);
        $phoneValidation = preg_match('/^(0|\+84|84)(3|5|7|8|9)[0-9]{8}$/', $request->phone);
        if (!$phoneValidation) {
            return back()->withErrors(['phone' => 'Số điện thoại không đúng định dạng.'])->withInput();
        }
        $user = User::create([
            'fullName' => $request->fullName,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'sex'      => $request->sex,
            'birthday' => $request->birthday ?: null,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
            'IsActive' => true,
        ]);

        Address::create([
            'userID'        => $user->userID,
            'city'          => '',
            'district'      => '',
            'ward'          => '',
            'addressDetail' => '',
        ]);

        MembershipTier::create([
            'userID'     => $user->userID,
            'tier'       => 'Bronze',
            'totalSpent' => 0,
        ]);

        Auth::guard('web')->login($user);

        // Merge session cart
        $sessionCart = session('cart', []);
        foreach ($sessionCart as $variantId => $item) {
            Cart::create(['userID' => $user->userID, 'variantID' => $variantId, 'quantity' => $item['quantity']]);
        }
        session()->forget('cart');

        return redirect()->route('client.home')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với Velour.');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'forgot_email' => 'required|email',
        ], [
            'forgot_email.required' => 'Vui lòng nhập email.',
            'forgot_email.email'    => 'Email không đúng định dạng.',
        ]);

        $user = User::where('email', $request->forgot_email)->first();

        if (!$user) {
            return back()->withErrors(['forgot_email' => 'Email này không tồn tại trong hệ thống.'])->withInput();
        }

        if (!$user->IsActive) {
            return back()->withErrors(['forgot_email' => 'Tài khoản liên kết với email này đã bị khóa.'])->withInput();
        }

        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $newPassword = substr(str_shuffle(str_repeat($pool, 5)), 0, 8);

        $user->password = Hash::make($newPassword);
        $user->save();

        try {
            // 4. Tiến hành gửi Mail
            Mail::to($user->email)->send(new ForgotPasswordMail($newPassword));

            return redirect()->route('client.login')->with('success', 'Mật khẩu mới đã được gửi thành công! Vui lòng kiểm tra email của bạn.');
        } catch (\Exception $e) {
            return back()->withErrors(['forgot_email' => 'Không thể gửi email. Lỗi: ' . $e->getMessage()]);
        }
    }
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('client.home');
    }

    private function redirectByRole(User $user)
    {
        return in_array($user->role, self::STAFF_ROLES)
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.home');
    }
}
