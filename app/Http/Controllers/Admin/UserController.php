<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('employee');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('fullName', 'like', '%' . $request->search . '%')
                  ->orWhere('email',   'like', '%' . $request->search . '%')
                  ->orWhere('phone',   'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function detail($id)
    {
        $user = User::with(['membership', 'addresses'])->findOrFail($id);
 
        $totalOrders    = Order::where('userID', $id)->count();
        $completedOrders = Order::where('userID', $id)->where('status', 'Completed')->count();
        $totalSpent     = Order::where('userID', $id)->where('status', 'Completed')->sum('finalAmount');
 
        $recentOrders = Order::where('userID', $id)
            ->orderByDesc('orderDate')
            ->limit(5)
            ->get()
            ->map(fn($o) => [
                'orderID'     => $o->orderID,
                'status'      => $o->status,
                'finalAmount' => (float) ($o->finalAmount ?? $o->totalAmount),
                'orderDate'   => $o->orderDate?->format('d/m/Y'),
            ]);
 
        $address = $user->addresses->first();
 
        return response()->json([
            'user' => [
                'userID'    => $user->userID,
                'fullName'  => $user->fullName,
                'email'     => $user->email,
                'phone'     => $user->phone,
                'sex'       => $user->sex,
                'birthday'  => $user->birthday?->format('d/m/Y'),
                'IsActive'  => $user->IsActive,
                'createdAt' => $user->created_at?->format('d/m/Y'),
                'tier'      => $user->membership?->tier ?? 'Bronze',
                'totalSpent'=> (float) ($user->membership?->totalSpent ?? 0),
                'address'   => $address
                    ? trim(implode(', ', array_filter([
                        $address->addressDetail,
                        $address->ward,
                        $address->district,
                        $address->city,
                      ])))
                    : null,
            ],
            'stats' => [
                'totalOrders'     => $totalOrders,
                'completedOrders' => $completedOrders,
                'totalSpent'      => (float) $totalSpent,
            ],
            'recentOrders' => $recentOrders,
        ]);
    }
    public function toggleActive($id)
    {
        $user = User::findOrFail($id);

        $order = Order::where('userID', $user->userID)
            ->whereIn('status', ['Pending', 'Confirmed', 'Shipping'])
            ->first();

        if ($order) {
            return back()->with('error', 'Không thể khóa tài khoản vì có đơn hàng đang xử lý.');
        }
        if ($user->userID === Auth::id()) {
            return back()->with('error', 'Bạn không thể khóa tài khoản của chính mình.');
        }
        if ($user->role === 'Admin' || $user->role === 'Owner') {
            return back()->with('error', 'Không thể khóa tài khoản Admin hoặc Owner.');
        }
        $user->update(['IsActive' => !$user->IsActive]);

        $status = $user->IsActive ? 'mở khóa' : 'khóa';
        return redirect()->route('admin.users.index')
                         ->with('success', 'Đã ' . $status . ' tài khoản "' . $user->fullName . '".');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $order = Order::where('userID', $user->userID)
            ->whereIn('status', ['Pending', 'Confirmed', 'Shipping'])
            ->first();

        if ($order) {
            return back()->with('error', 'Không thể khóa tài khoản vì có đơn hàng đang xử lý.');
        }
        if ($user->userID === Auth::id()) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        if ($user->role !== "Customer") {
            return back()->with('error', 'Không thể xóa tài khoản nhân viên.');
        }

        $name = $user->fullName;
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Đã xóa người dùng "' . $name . '".');
    }
}