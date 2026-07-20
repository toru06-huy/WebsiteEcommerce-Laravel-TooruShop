<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Address;
use App\Models\UserDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $addresses = Address::where('userID', $id)->get();
        $membership = $user->membership;
        return view('client.user.profile', compact('user', 'addresses', 'membership'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id . ',userID',
            'phone'         => 'nullable|string|max:20|unique:users,phone,' . $id . ',userID',
            'sex'           => 'nullable|string|in:Nam,Nữ,Khác',
            'birthday'      => 'nullable|date',
            'city'          => 'nullable|string|max:200',
            'district'      => 'nullable|string|max:200',
            'ward'          => 'nullable|string|max:200',
            'addressDetail' => 'nullable|string',
        ], [
            'name.required'  => 'Vui lòng nhập họ tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.unique'   => 'Số điện thoại này đã được sử dụng.',
            'sex.in'         => 'Giới tính không hợp lệ.',
            'birthday.date'  => 'Ngày sinh không hợp lệ.',
            'birthday.before'=> 'Ngày sinh phải trước ngày hôm nay.',
        ]);
        $phoneValidation = preg_match('/^(0|\+84|84)(3|5|7|8|9)[0-9]{8}$/', $request->phone);
        if (!$phoneValidation) {
            return back()->withErrors(['phone' => 'Số điện thoại không đúng định dạng.'])->withInput();
        }
        $user->update([
            'fullName' => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone']    ?? $user->phone,
            'sex'      => $data['sex']      ?? $user->sex,
            'birthday' => $data['birthday'] ?? $user->birthday,
            'updated_at' => now(),
        ]);
        Address::updateOrCreate(
            ['userID' => $id],
            [
                'city'          => $data['city']          ?? null,
                'district'      => $data['district']      ?? null,
                'ward'          => $data['ward']          ?? null,
                'addressDetail' => $data['addressDetail'] ?? null,
                'updated_at'    => now(),
            ]
        );

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công.');
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'password.required'  => 'Vui lòng nhập mật khẩu mới.',
            'password.min'       => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->update([
            'password' => bcrypt($data['password']),
        ]);

        return redirect()->back()->with('success', 'Cập nhật mật khẩu thành công.');
    }

    public function vouchers($id)
    {
        $user    = User::findOrFail($id);
        $now     = now();

        $userDiscounts = UserDiscount::with('discount')
            ->where('userID', $id)
            ->orderBy('isUsed')
            ->orderByDesc('created_at')
            ->get();

        return view('client.user.vouchers', compact('user', 'userDiscounts', 'now'));
    }
    public function orders($id)
    {
        $orders = Order::where('userID', $id)
            ->with([
                'details',
                'details.variant.product.images',
                'details.variant.size',
                'details.variant.color'
            ])
            ->orderByDesc('orderDate');

        return view('client.user.orders', compact('orders'));
    }

    public function orderDetails($orderID)
    {
        $order = Order::with(
            'details.variant.product.images',
            'details.variant.size',
            'details.variant.color'
        )->findOrFail($orderID);
        $details = $order->details;
        return view('client.user.order_details', compact('order', 'details'));
    }

    public function requestCancel($orderID)
    {
        $order = Order::findOrFail($orderID);

        if ($order->status !== 'Pending') {
            return redirect()->back()->withErrors(['Không thể gửi yêu cầu hủy ở trạng thái hiện tại.']);
        }

        $order->update(['status' => 'CancelRequested']);

        return redirect()->back()->with('success', 'Yêu cầu hủy đã được gửi. Chúng tôi sẽ phản hồi sớm nhất.');
    }
}
