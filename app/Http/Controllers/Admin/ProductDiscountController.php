<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDiscount;
use Illuminate\Http\Request;

class ProductDiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductDiscount::with('product');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('productName', 'like', '%' . $search . '%');
            });
        }

        $productDiscounts = $query->orderByDesc('productDiscountID')->paginate(10)->withQueryString();
        $products         = Product::orderBy('productName')->get();

        return view('admin.product-discounts.index', compact('productDiscounts', 'products'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        
        ProductDiscount::create($data);

        return redirect()->route('admin.product-discounts.index')
                         ->with('success', 'Đã thêm giảm giá cho sản phẩm.');
    }

    public function update(Request $request, $id)
    {
        $discount = ProductDiscount::findOrFail($id);
        $discount->update($this->validateData($request));

        return redirect()->route('admin.product-discounts.index')
                         ->with('success', 'Đã cập nhật giảm giá sản phẩm.');
    }

    public function destroy($id)
    {
        ProductDiscount::findOrFail($id)->delete();

        return redirect()->route('admin.product-discounts.index')
                         ->with('success', 'Đã xóa giảm giá sản phẩm.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'productID'     => 'required|exists:products,productID',
            'discountValue' => 'required|numeric|min:0|max:70',
            'startDate'     => 'required|date_format:Y-m-d\TH:i',
            'endDate'       => 'required|date_format:Y-m-d\TH:i|after:startDate',
            'isActive'      => 'nullable|boolean',
        ], [
            'productID.required'     => 'Vui lòng chọn sản phẩm.',
            'productID.exists'       => 'Sản phẩm không tồn tại.',
            'discountValue.required' => 'Vui lòng nhập % giảm giá.',
            'discountValue.numeric'  => '% giảm giá phải là số.',
            'discountValue.max'      => '% giảm giá không được vượt quá 70%.',
            'startDate.required'     => 'Vui lòng chọn ngày bắt đầu.',
            'startDate.date_format'  => 'Ngày bắt đầu không hợp lệ.',
            'endDate.required'       => 'Vui lòng chọn ngày kết thúc.',
            'endDate.date_format'    => 'Ngày kết thúc không hợp lệ.',
            'endDate.after'          => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        // Chuyển datetime-local "2025-06-14T10:30" → "2025-06-14 10:30:00"
        $data['startDate'] = str_replace('T', ' ', $data['startDate']) . ':00';
        $data['endDate']   = str_replace('T', ' ', $data['endDate'])   . ':00';
        $data['isActive']  = $request->boolean('isActive');

        return $data;
    }
}