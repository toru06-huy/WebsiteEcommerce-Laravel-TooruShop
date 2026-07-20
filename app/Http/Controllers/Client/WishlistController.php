<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /** Toggle: thêm hoặc xóa khỏi wishlist */
    public function toggle(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để lưu sản phẩm yêu thích.',
            ], 401);
        }

        $request->validate(['productID' => 'required|exists:products,productID']);

        $user = Auth::guard('web')->user();

        $existing = Wishlist::where('userID', $user->userID)
            ->where('productID', $request->productID)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['success' => true, 'wishlisted' => false, 'message' => 'Đã xóa khỏi danh sách yêu thích.']);
        }

        Wishlist::create(['userID' => $user->userID, 'productID' => $request->productID]);

        return response()->json(['success' => true, 'wishlisted' => true, 'message' => 'Đã thêm vào danh sách yêu thích.']);
    }

    /** Trang danh sách yêu thích của user */
    public function index()
    {
        $user = Auth::guard('web')->user();

        $wishlists = Wishlist::with(['product.coverImage', 'product.variants', 'product.productDiscounts'])
            ->where('userID', $user->userID)
            ->latest()
            ->paginate(12);

        return view('client.user.wishlist', compact('wishlists', 'user'));
    }
}
