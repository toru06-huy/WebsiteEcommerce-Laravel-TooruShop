<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $rootCategories = Category::with('children')->whereNull('parentID')->get();
        $cartItems      = $this->getCartItems();
        $total          = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);

        return view('client.cart', compact('rootCategories', 'cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variantID' => 'required|exists:product_variants,variantID',
            'quantity'  => 'required|integer|min:1',
        ]);

        $variant  = ProductVariant::findOrFail($request->variantID);
        $quantity = (int) $request->quantity;

        if ($variant->stockQuantity < $quantity) {
            return response()->json(['success' => false, 'message' => 'Không đủ hàng trong kho.'], 422);
        }

        if (Auth::guard('web')->check()) {
            $user    = Auth::guard('web')->user();
            $cartItem = Cart::where('userID', $user->userID)->where('variantID', $variant->variantID)->first();
            if ($cartItem) {
                $newQty = $cartItem->quantity + $quantity;
                if ($newQty > $variant->stockQuantity) {
                    return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho.'], 422);
                }
                $cartItem->update(['quantity' => $newQty, 'abandoned_notified' => false]);
            } else {
                Cart::create(['userID' => $user->userID, 'variantID' => $variant->variantID, 'quantity' => $quantity, 'abandoned_notified' => false]);
            }
        } else {
            $cart = session('cart', []);
            $key  = $variant->variantID;
            $currentQty = $cart[$key]['quantity'] ?? 0;
            $newQty = $currentQty + $quantity;
            if ($newQty > $variant->stockQuantity) {
                return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho.'], 422);
            }
            $cart[$key] = ['variantID' => $key, 'quantity' => $newQty];
            session(['cart' => $cart]);
        }

        return response()->json(['success' => true, 'message' => 'Đã thêm vào giỏ hàng!', 'count' => $this->getCartCount()]);
    }

    public function update(Request $request, $variantId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $variant  = ProductVariant::findOrFail($variantId);
        $quantity = (int) $request->quantity;

        if ($quantity > $variant->stockQuantity) {
            return response()->json(['success' => false, 'message' => 'Không đủ hàng trong kho.'], 422);
        }

        if (Auth::guard('web')->check()) {
            Cart::where('userID', Auth::id())->where('variantID', $variantId)->update(['quantity' => $quantity]);
        } else {
            $cart = session('cart', []);
            if (isset($cart[$variantId])) {
                $cart[$variantId]['quantity'] = $quantity;
                session(['cart' => $cart]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function remove($variantId)
    {
        if (Auth::guard('web')->check()) {
            Cart::where('userID', Auth::guard('web')->user()->userID)->where('variantID', $variantId)->delete();
        } else {
            $cart = session('cart', []);
            unset($cart[$variantId]);
            session(['cart' => $cart]);
        }
        return response()->json(['success' => true]);
    }

    public function count()
    {
        return response()->json(['count' => $this->getCartCount()]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------
    public static function getCartItemsStatic(): array
    {
        if (Auth::guard('web')->check()) {
            $dbItems = Cart::with(['variant.product.coverImage', 'variant.size', 'variant.color'])
                ->where('userID', Auth::guard('web')->user()->userID)
                ->get();

            return $dbItems->map(function ($item) {
                $variant = $item->variant;
                $product = $variant?->product;
                return [
                    'variantID'   => $item->variantID,
                    'quantity'    => $item->quantity,
                    'productName' => $product?->productName ?? 'Sản phẩm',
                    'sizeName'    => $variant?->size?->sizeName ?? '',
                    'colorName'   => $variant?->color?->colorName ?? '',
                    'price'       => (float) ($variant?->price ?? $product?->basePrice ?? 0),
                    'imageURL'    => $product?->coverImage?->imageURL ?? null,
                    'stock'       => $variant?->stockQuantity ?? 0,
                ];
            })->toArray();
        }

        // Session cart
        $cart    = session('cart', []);
        $result  = [];
        $variantIds = array_keys($cart);
        if (empty($variantIds)) return [];

        $variants = ProductVariant::with(['product.coverImage', 'size', 'color'])
            ->whereIn('variantID', $variantIds)
            ->get()
            ->keyBy('variantID');

        foreach ($cart as $variantId => $item) {
            $variant = $variants[$variantId] ?? null;
            if (!$variant) continue;
            $product = $variant->product;
            $result[] = [
                'variantID'   => $variantId,
                'quantity'    => $item['quantity'],
                'productName' => $product?->productName ?? 'Sản phẩm',
                'sizeName'    => $variant->size?->sizeName ?? '',
                'colorName'   => $variant->color?->colorName ?? '',
                'price'       => (float) ($product?->discounted_price ?? $product?->basePrice ?? 0),
                'imageURL'    => $product?->coverImage?->imageURL ?? null,
                'stock'       => $variant->stockQuantity ?? 0,
            ];
        }
        return $result;
    }

    private function getCartItems(): array
    {
        return self::getCartItemsStatic();
    }

    public static function getCartCountStatic(): int
    {
        if (Auth::guard('web')->check()) {
            return Cart::where('userID', Auth::guard('web')->user()->userID)->sum('quantity');
        }
        return (int) collect(session('cart', []))->sum('quantity');
    }

    private function getCartCount(): int
    {
        return self::getCartCountStatic();
    }
}
