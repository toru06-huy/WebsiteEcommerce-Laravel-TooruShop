<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = Discount::query();

        if ($request->filled('search')) {
            $query->where('discountCode', 'like', '%' . $request->search . '%')
                  ->orWhere('discountName', 'like', '%' . $request->search . '%');
        }

        $discounts = $query->orderByDesc('discountID')->paginate(10)->withQueryString();

        return view('admin.discounts.index', compact('discounts'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        
        if($data['discountType'] === 'percentage' && $data['discountValue'] > 70) {
            return redirect()->back()->withErrors(['discountValue' => 'Giá trị giảm giá không được vượt quá 70%.'])->withInput();
        }
        Discount::create($data);

        return redirect()->route('admin.discounts.index')
                         ->with('success', 'Đã thêm mã giảm giá "' . $data['discountCode'] . '".');
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $data = $this->validateData($request, $id);

        $discount->update($data);

        return redirect()->route('admin.discounts.index')
                         ->with('success', 'Đã cập nhật mã giảm giá "' . $discount->discountCode . '".');
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);

        if ($discount->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa mã giảm giá đã được sử dụng trong đơn hàng.');
        }

        $code = $discount->discountCode;
        $discount->delete();

        return redirect()->route('admin.discounts.index')
                         ->with('success', 'Đã xóa mã giảm giá "' . $code . '".');
    }

    private function validateData(Request $request, $id = null): array
    {
        $uniqueRule = $id
            ? 'unique:discounts,discountCode,' . $id . ',discountID'
            : 'unique:discounts,discountCode';

        $data = $request->validate([
            'discountCode'  => 'required|string|max:50|' . $uniqueRule,
            'discountName'  => 'required|string|max:150',
            'discountType'    => 'required|in:percentage,fixedAmount',
            'discountValue' => 'required|numeric|min:0',
            'discountLimit' => 'nullable|integer|min:0',
            'startDate'     => 'nullable|date',
            'endDate'       => 'nullable|date|after_or_equal:startDate',
            'minOrderValue' => 'nullable|numeric|min:0',
            'isActive'      => 'nullable|boolean',
            'isPersonal'    => 'nullable|boolean',
        ], [
            'discountCode.required'  => 'Vui lòng nhập mã giảm giá.',
            'discountCode.unique'    => 'Mã giảm giá đã tồn tại.',
            'discountName.required'  => 'Vui lòng nhập tên chương trình giảm giá.',
            'discountType.required'    => 'Vui lòng chọn loại giảm giá.',
            'discountType.in'          => 'Loại giảm giá không hợp lệ.',
            'discountValue.required' => 'Vui lòng nhập giá trị giảm.',
            'discountValue.numeric'  => 'Giá trị giảm phải là số.',
            'endDate.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        $data['discountCode']  = strtoupper(trim($data['discountCode']));
        $data['discountLimit'] = $data['discountLimit'] ?? 10;
        $data['minOrderValue'] = $data['minOrderValue'] ?? 0;
        $data['isActive']      = $request->boolean('isActive');
        $data['isPersonal']    = false;

        return $data;
    }
}