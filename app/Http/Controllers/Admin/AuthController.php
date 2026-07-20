<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && in_array(Auth::user()->role, ['Admin', 'Employee'])) {
            return view('admin.dashboard'); //redirect()->route('admin.dashboard')
        }
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required'    => 'Vui lòng nhập email hoặc số điện thoại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $login    = $request->input('login');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $field     => $login,
            'password' => $password,
        ];

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->IsActive) {
                Auth::logout();
                return back()
                    ->withInput($request->only('login', 'remember'))
                    ->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
            }

            if (!in_array($user->role, ['Admin', 'Employee','Owner'])) {
                Auth::logout();
                return back()
                    ->withInput($request->only('login', 'remember'))
                    ->with('error', 'Bạn không có quyền truy cập vào trang quản trị.');
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')
                ->with('success', 'Xin chào, ' . $user->fullName . '!');
        }

        return back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors(['login' => 'Email/SĐT hoặc mật khẩu không đúng.']);
    }

    public function logout(Request $request)
    {
        //Kiểm tra đơn hàng đang xử lý thuộc về nhân viên trước khi logout
        $employee = Employee::where('userID', Auth::id())->first();
        $orders=Order::where('processedBy', $employee->employeeID)->whereIn('status', ['Pending', 'Confirmed'])->count();
        if($orders>0){
            return back()->with('error', 'Bạn không thể đăng xuất khi còn đơn hàng đang xử lý. Vui lòng hoàn tất hoặc hủy các đơn hàng trước khi đăng xuất.');
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('client.home')
            ->with('success', 'Đã đăng xuất thành công.');
    }
}