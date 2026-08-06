<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderSuccessMail;
use App\Mail\TierUpgradedMail;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Discount;
use App\Models\MembershipTier;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use App\Models\UserDiscount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // ─── Bước 1: Xem giỏ hàng và tiến hành đặt hàng ──────────────────────────
    public function proceedToShipping(Request $request)
    {
        $cartItems = CartController::getCartItemsStatic();
        if (empty($cartItems)) {
            return redirect()->route('client.cart')->with('error', 'Giỏ hàng trống.');
        }
        session(['checkout.cart' => $cartItems]);
        return redirect()->route('client.checkout.shipping');
    }

    // ─── Bước 2: Form thông tin nhận hàng ────────────────────────────────────
    public function showShipping()
    {
        $cartItems = session('checkout.cart');
        if (empty($cartItems)) return redirect()->route('client.cart');

        $user           = Auth::guard('web')->user();
        $address        = $user ? Address::where('userID', $user->userID)->latest()->first() : null;
        $rootCategories = Category::with('children')->whereNull('parentID')->get();
        $total          = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);
        $discountData   = session('checkout.discount');
        $publicDiscounts = $this->getAvailablePublicDiscounts($user, $total);

        return view('client.checkout.shipping', compact('rootCategories', 'cartItems', 'total', 'user', 'address', 'discountData', 'publicDiscounts'));
    }
    private function getAvailablePublicDiscounts($user, float $total)
    {
        $now = Carbon::now();

        $discounts = Discount::where('isActive', true)
            ->where('isPersonal', false)
            ->where(function ($q) use ($now) {
                $q->whereNull('startDate')->orWhere('startDate', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('endDate')->orWhere('endDate', '>=', $now);
            })
            ->orderByDesc('discountID')
            ->get();

        return $discounts->filter(function ($discount) use ($user, $total) {
            // Chưa đạt giá trị đơn hàng tối thiểu
            if ($total < $discount->minOrderValue) {
                return false;
            }

            // Đã hết lượt sử dụng
            if ($discount->discountLimit) {
                $usedTotal = UserDiscount::where('discountID', $discount->discountID)
                    ->where('isUsed', true)
                    ->count();
                if ($usedTotal >= $discount->discountLimit) {
                    return false;
                }
            }

            // Người dùng đã đăng nhập và đã sử dụng mã này rồi
            if ($user) {
                $alreadyUsed = UserDiscount::where('discountID', $discount->discountID)
                    ->where('userID', $user->userID)
                    ->where('isUsed', true)
                    ->exists();
                if ($alreadyUsed) {
                    return false;
                }
            }

            return true;
        })->values();
    }
    public function submitShipping(Request $request)
    {
        $cartItems = session('checkout.cart');
        if (empty($cartItems)) return redirect()->route('client.cart');

        $request->validate([
            'fullName'      => 'required|string|max:200',
            'phone'         => 'required|string|max:20',
            'city'          => 'required|string|max:200',
            'district'      => 'required|string|max:200',
            'ward'          => 'required|string|max:200',
            'addressDetail' => 'required|string|max:500',
        ], [
            'fullName.required'      => 'Vui lòng nhập họ tên.',
            'phone.required'         => 'Vui lòng nhập số điện thoại.',
            'city.required'          => 'Vui lòng nhập tỉnh/thành phố.',
            'district.required'      => 'Vui lòng nhập quận/huyện.',
            'ward.required'          => 'Vui lòng nhập phường/xã.',
            'addressDetail.required' => 'Vui lòng nhập địa chỉ chi tiết.',
        ]);

        $user = Auth::guard('web')->user();
        if ($user) {
            Address::updateOrCreate(
                ['userID' => $user->userID],
                [
                    'city'          => $request->city,
                    'district'      => $request->district,
                    'ward'          => $request->ward,
                    'addressDetail' => $request->addressDetail,
                ]
            );
        }

        session([
            'checkout.shipping' => [
                'fullName'      => $request->fullName,
                'phone'         => $request->phone,
                'city'          => $request->city,
                'district'      => $request->district,
                'ward'          => $request->ward,
                'addressDetail' => $request->addressDetail,
            ],
        ]);

        return redirect()->route('client.checkout.payment');
    }

    // ─── Bước 3: Phương thức thanh toán ─────────────────────────────────────
    public function showPayment()
    {
        $cartItems    = session('checkout.cart');
        $shippingInfo = session('checkout.shipping');
        if (empty($cartItems) || empty($shippingInfo)) return redirect()->route('client.cart');

        $rootCategories = Category::with('children')->whereNull('parentID')->get();
        $total          = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);
        $discountData   = session('checkout.discount');
        $finalAmount    = $discountData ? $discountData['finalAmount'] : $total;
        if ($finalAmount < 500000) {
            $finalAmount += 42000;
        }
        return view('client.checkout.payment', compact('rootCategories', 'cartItems', 'total', 'shippingInfo', 'discountData', 'finalAmount'));
    }

    public function finalize(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,bank',
        ]);

        $cartItems    = session('checkout.cart');
        $shippingInfo = session('checkout.shipping');
        if (empty($cartItems) || empty($shippingInfo)) return redirect()->route('client.cart');

        $user         = Auth::guard('web')->user();
        $total        = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);
        $discountData = session('checkout.discount');

        $discountID     = $discountData['discountID']     ?? null;
        $discountAmount = $discountData['discountAmount']  ?? 0;
        $finalAmount    = $discountData['finalAmount']     ?? $total;
        $shippingFee = $finalAmount < 500000 ? 42000 : 0;
        $finalAmount += $shippingFee;
        // Xây dựng chuỗi payment
        if ($request->payment_method === 'cod') {
            $paymentStr = 'Trả sau khi nhận';
        } else {
            $paymentStr = 'Chuyển khoản: BIDV - 0931462157' . "\n"
                . 'Số tiền: ' . number_format($finalAmount, 0, ',', '.') . 'đ' . "\n"
                . 'Địa chỉ: ' . $shippingInfo['addressDetail'] . ', ' . $shippingInfo['ward'] . ', ' . $shippingInfo['district'] . ', ' . $shippingInfo['city'] . "\n"
                . 'Nội dung chuyển khoản: ' . ($shippingInfo['fullName'] ?? '') . ' - ' . ($shippingInfo['phone'] ?? '');
        }

        // Gộp thông tin người nhận (tên, SĐT) vào địa chỉ giao hàng để admin xử lý đơn cho cả khách vãng lai
        $shippingAddress = implode(', ', [
            $shippingInfo['addressDetail'],
            $shippingInfo['ward'],
            $shippingInfo['district'],
            $shippingInfo['city'],
        ]);
        $order = null;
        try {
            DB::transaction(function () use ($user, $cartItems, $total, $discountID, $discountAmount, $finalAmount, $shippingAddress, $paymentStr, $shippingInfo, &$order) {

                $variantIds = collect($cartItems)->pluck('variantID')->toArray();

                $variants = ProductVariant::whereIn('variantID', $variantIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('variantID');

                $stockErrors = [];
                foreach ($cartItems as $item) {
                    $variant = $variants[$item['variantID']] ?? null;
                    if (!$variant || $variant->stockQuantity < $item['quantity']) {
                        $stockErrors[] = ($variant
                            ? $item['productName'] . ' (' . $item['sizeName'] . ' / ' . $item['colorName'] . ')'
                            : 'Sản phẩm #' . $item['variantID'])
                            . ' — chỉ còn ' . ($variant?->stockQuantity ?? 0) . ' sản phẩm.';
                    }
                }

                if (!empty($stockErrors)) {
                    throw new \Exception('OUT_OF_STOCK:' . implode('|', $stockErrors));
                }

                // ── Tạo đơn hàng ────────────────────────────────────────────────
                $order = Order::create([
                    'userID'          => $user?->userID,
                    'name'            => $user?->name ?? $shippingInfo['fullName'],
                    'phone'           => $user?->phone ?? $shippingInfo['phone'],
                    'orderDate'       => Carbon::now(),
                    'totalAmount'     => $total,
                    'discountID'      => $discountID,
                    'discountAmount'  => $discountAmount,
                    'finalAmount'     => $finalAmount,
                    'status'          => 'Pending',
                    'shippingAddress' => $shippingAddress,
                    'payment'         => $paymentStr,
                ]);

                foreach ($cartItems as $item) {
                    OrderDetail::create([
                        'orderID'   => $order->orderID,
                        'variantID' => $item['variantID'],
                        'quantity'  => $item['quantity'],
                        'unitPrice' => $item['price'],
                    ]);

                    ProductVariant::where('variantID', $item['variantID'])
                        ->decrement('stockQuantity', $item['quantity']);
                }

                // Ghi nhận user đã dùng mã giảm giá (sau khi đặt hàng thành công)
                if ($discountID && $user) {
                    UserDiscount::updateOrCreate(
                        ['userID' => $user->userID, 'discountID' => $discountID],
                        ['isUsed' => true, 'usedAt' => Carbon::now()]
                    );
                }
                if ($discountID) {
                    Discount::updateOrCreate(
                        ['discountID' => $discountID],
                        ['discountLimit' => DB::raw('discountLimit - 1')]
                    );
                }
                // Xóa cart trong DB (nếu là người dùng đã đăng nhập)
                if ($user) {
                    Cart::where('userID', $user->userID)->delete();

                    // ── Cập nhật hạng thành viên ─────────────────────────────────
                    $tier = MembershipTier::where('userID', $user->userID)->first();

                    if ($tier) {
                        $oldTier = $tier->tier;
                        $tier->increment('totalSpent', $finalAmount);
                        $tier->refresh();
                        $newTier = MembershipTier::calcTier((float) $tier->totalSpent);
                        $this->newDiscountForTier($user, $tier, $oldTier, $newTier);
                    }
                }
            });
            if (isset($order)) {
                try {
                    $targetEmail = $user?->email ?? ($shippingInfo['email'] ?? null);
                    if (!empty($targetEmail)) {
                        Mail::to($targetEmail)->send(new OrderSuccessMail($order, $cartItems, $shippingInfo, $shippingFee));
                    }
                } catch (\Exception $mailEx) {
                    logger('Không thể gửi email hóa đơn đơn hàng #' . $order->orderID . '. Lỗi: ' . $mailEx->getMessage());
                }
            }
        } catch (\Exception $e) {
            if (str_starts_with($e->getMessage(), 'OUT_OF_STOCK:')) {
                $items = explode('|', substr($e->getMessage(), 13));
                return redirect()->route('client.cart')
                    ->with('error', 'Có sự cố xảy ra trong quá trình đặt hàng. Vui lòng kiểm tra lại giỏ hàng.')
                    ->with('stock_errors', $items);
            }
            throw $e;
        }

        // Xóa session checkout (và session cart cho khách vãng lai)
        session()->forget(['checkout.cart', 'checkout.shipping', 'checkout.discount']);
        if (!$user) {
            session()->forget('cart');
        }

        return redirect()->route('client.home')->with('order_success', 'Đặt hàng thành công! Cảm ơn bạn đã mua sắm tại Velour.');
    }


    // ─── AJAX: Áp dụng mã giảm giá ───────────────────────────────────────────
    public function applyDiscount(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $user      = Auth::guard('web')->user();
        $cartItems = session('checkout.cart');
        $total     = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);

        $discount = Discount::where('discountCode', strtoupper(trim($request->code)))
            ->where('isActive', true)
            ->first();

        if (!$discount) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ.']);
        }

        $now = Carbon::now();
        if ($discount->startDate && $now->lt($discount->startDate)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá chưa có hiệu lực.']);
        }
        if ($discount->endDate && $now->gt($discount->endDate)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn.']);
        }
        if ($total < $discount->minOrderValue) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($discount->minOrderValue, 0, ',', '.') . 'đ để áp dụng mã này.',
            ]);
        }

        // ── Phân loại mã ────────────────────────────────────────────────────
        if ($discount->isPersonal) {
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ.']);
            }

            $myRecord = UserDiscount::where('discountID', $discount->discountID)
                ->where('userID', $user->userID)
                ->first();

            if (!$myRecord) {
                return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ.']);
            }
            if ($myRecord->isUsed) {
                return response()->json(['success' => false, 'message' => 'Bạn đã sử dụng mã giảm giá này rồi.']);
            }
        } else {
            $usedTotal = UserDiscount::where('discountID', $discount->discountID)
                ->where('isUsed', true)
                ->count();
            if ($discount->discountLimit && $usedTotal >= $discount->discountLimit) {
                return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
            }

            if ($user) {
                $alreadyUsed = UserDiscount::where('discountID', $discount->discountID)
                    ->where('userID', $user->userID)
                    ->where('isUsed', true)
                    ->exists();
                if ($alreadyUsed) {
                    return response()->json(['success' => false, 'message' => 'Bạn đã sử dụng mã giảm giá này rồi.']);
                }
            }
        }
        // Chỉ lưu session — UserDiscount ghi sau khi đặt hàng thành công trong finalize()

        $discountAmount = $discount->discountType === 'percentage'
            ? round($total * $discount->discountValue / 100)
            : min($discount->discountValue, $total);

        $finalAmount = max(0, $total - $discountAmount);

        session([
            'checkout.discount' => [
                'discountID'     => $discount->discountID,
                'discountCode'   => $discount->discountCode,
                'discountName'   => $discount->discountName,
                'discountAmount' => $discountAmount,
                'finalAmount'    => $finalAmount,
            ],
        ]);

        return response()->json([
            'success'        => true,
            'message'        => 'Áp dụng mã giảm giá thành công!',
            'discountAmount' => number_format($discountAmount, 0, ',', '.') . 'đ',
            'finalAmount'    => number_format($finalAmount, 0, ',', '.') . 'đ',
            'discountName'   => $discount->discountName,
        ]);
    }

    public function removeDiscount()
    {
        session()->forget('checkout.discount');
        return response()->json(['success' => true]);
    }

    public function newDiscountForTier($user, $tier, string $oldTier, string $newTier): void
    {
        if ($newTier === $oldTier) return;

        $tier->update(['tier' => $newTier]);

        $tierDiscount = [
            'Silver'   => 5,
            'Gold'     => 10,
            'Platinum' => 15,
        ];

        if (!isset($tierDiscount[$newTier])) return;

        $discountValue = $tierDiscount[$newTier];

        do {
            $code = strtoupper($newTier) . '-' . strtoupper(Str::random(6));
        } while (Discount::where('discountCode', $code)->exists());

        $endDate = Carbon::now()->addMonth();

        $newDiscount = Discount::create([
            'discountCode'  => $code,
            'discountName'  => 'Thưởng lên hạng ' . $newTier . ' — Giảm ' . $discountValue . '%',
            'discountType'  => 'percentage',
            'discountValue' => $discountValue,
            'discountLimit' => 1,
            'startDate'     => Carbon::now(),
            'endDate'       => $endDate,
            'minOrderValue' => 0,
            'isActive'      => true,
            'isPersonal'    => true,
        ]);

        UserDiscount::create([
            'userID'     => $user->userID,
            'discountID' => $newDiscount->discountID,
            'isUsed'     => false,
            'usedAt'     => null,
        ]);

        // --- ĐOẠN THÊM MỚI: TỰ ĐỘNG GỬI EMAIL THÔNG BÁO CHO KHÁCH HÀNG ---
        try {
            Mail::to($user->email)->send(new TierUpgradedMail(
                $user,
                $newTier,
                $code,
                $discountValue,
                $endDate
            ));
        } catch (\Exception $e) {
            logger('Không thể gửi mail thông báo thăng hạng cho User ID ' . $user->userID . '. Lỗi: ' . $e->getMessage());
        }
    }
}
