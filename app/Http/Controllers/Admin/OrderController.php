<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Employee;
use App\Models\MembershipTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'processor.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee_search')) {
            $search = trim($request->employee_search);
            $query->whereHas('processor', function ($q) use ($search) {
                $q->where('employeeCode', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('fullName', 'like', "%{$search}%");
                  });
            });
        }
        $orders    = $query->orderBy('orderID', 'desc')->paginate(20)->withQueryString();
        $employees = Employee::with('user')->get();

        return view('admin.orders.index', compact('orders', 'employees'));
    }
    public function detail($id)
    {
        $order = Order::with([
            'details.variant.product.coverImage',
            'details.variant.size',
            'details.variant.color',
        ])->findOrFail($id);
 
        $details = $order->details->map(function ($d) {
            $variant = $d->variant;
            $product = $variant?->product;
 
            return [
                'productName' => $product?->productName ?? '—',
                'sizeName'    => $variant?->size?->sizeName ?? null,
                'colorName'   => $variant?->color?->colorName ?? null,
                'quantity'    => $d->quantity,
                'unitPrice'   => (float) $d->unitPrice,
                'imageURL'    => $product?->coverImage
                    ? asset('storage/' . $product->coverImage->imageURL)
                    : null,
            ];
        });
 
        return response()->json([
            'order'   => [
                'orderID'        => $order->orderID,
                'name'           => $order->name,
                'phone'          => $order->phone,
                'status'         => $order->status,
                'shippingAddress'=> $order->shippingAddress,
                'payment'        => $order->payment,
                'totalAmount'    => (float) $order->totalAmount,
                'discountAmount' => (float) ($order->discountAmount ?? 0),
                'finalAmount'    => (float) ($order->finalAmount ?? $order->totalAmount),
            ],
            'details' => $details,
        ]);
    }
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status'      => 'required|in:Pending,Confirmed,Shipping,Completed,Cancelled',
            'processedBy' => 'nullable|exists:employees,employeeID',
        ]);

        $currentStatus = $order->status;
        $newStatus = $request->status;

        $statusOrder = [
            'Pending'   => 1,
            'Confirmed' => 2,
            'Shipping'  => 3,
            'Completed' => 4,
            'Cancelled' => 99 
        ];

        if ($newStatus === 'Cancelled') {
            if (!in_array($currentStatus, ['Pending', 'Confirmed'])) {
                return back()->withErrors(['status' => "Không thể hủy đơn hàng khi đã vào giai đoạn: $currentStatus."]);
            }
        }

        else {
            if (in_array($currentStatus, ['Cancelled', 'Completed'])) {
                return back()->withErrors(['status' => "Đơn hàng đã $currentStatus, không thể thay đổi trạng thái."]);
            }

            if ($statusOrder[$newStatus] < $statusOrder[$currentStatus]) {
                return back()->withErrors(['status' => "Không thể chuyển ngược trạng thái từ $currentStatus về $newStatus."]);
            }
        }

        $order->update([
            'status'      => $newStatus,
            'processedBy' => $request->processedBy ?: $order->processedBy,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã cập nhật trạng thái đơn #' . $id . ' thành công.');
    }

    public function cancel(Request $request, $id)
    {
        $order = Order::with('details.variant')->findOrFail($id);

        if (!in_array($order->status, ['Pending', 'Confirmed', 'Shipping'])) {
            return back()->withErrors([
                'status' => "Không thể hủy đơn hàng khi đã vào giai đoạn: {$order->status}.",
            ]);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            foreach ($order->details as $detail) {
                if ($detail->variant) {
                    $detail->variant->increment('stockQuantity', $detail->quantity);
                }
            }

            $order->update(['status' => 'Cancelled']);
        });
        if ($order->userID) {
                $membership = MembershipTier::firstOrCreate(
                    ['userID' => $order->userID],
                    ['tier' => MembershipTier::calcTier(0), 'totalSpent' => 0]
                );

                $spentAmount = $order->finalAmount ?? $order->totalAmount;
                $newTotalSpent = max(0, $membership->totalSpent - $spentAmount);

                $membership->update([
                    'totalSpent' => $newTotalSpent,
                    'tier'       => MembershipTier::calcTier($newTotalSpent),
                ]);
            }
        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã hủy đơn hàng #' . $id . ' và hoàn lại số lượng sản phẩm vào kho.');
    }
    public function advance(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $statusFlow = [
            'Pending'   => 'Confirmed',
            'Confirmed' => 'Shipping',
            'Shipping'  => 'Completed',
        ];
        $newStatus = $statusFlow[$order->status] ?? null;
        if (!array_key_exists($order->status, $statusFlow)) {
            return back()->withErrors([
                'status' => "Đơn hàng đã {$order->status}, không thể chuyển tiếp trạng thái.",
            ]);
        }

        $currentEmployeeId = Employee::where('userID', Auth::id())->value('employeeID');

        $order->update([
            'status'      => $newStatus,
            'processedBy' => $order->processedBy ?: $currentEmployeeId,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã chuyển đơn #' . $id . ' sang trạng thái ' . $newStatus . '.');
    }
}
