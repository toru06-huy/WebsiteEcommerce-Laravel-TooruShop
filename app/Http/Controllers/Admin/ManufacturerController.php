<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(Request $request)
    {
        $query = Manufacturer::with('products');

        if ($request->filled('search')) {
            $query->where('manufacturerName', 'like', '%' . $request->search . '%')
                  ->orWhere('manufacturerCode', 'like', '%' . $request->search . '%');
        }

        $manufacturers = $query->paginate(15)->withQueryString();

        return view('admin.manufacturers.index', compact('manufacturers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'manufacturerCode' => 'required|string|max:50|unique:manufacturers,manufacturerCode',
            'manufacturerName' => 'required|string|max:200',
            'country'          => 'nullable|string|max:100',
            'website'          => 'nullable|url|max:255',
            'email'            => 'nullable|email|max:255',  
        ], [
            'manufacturerCode.required' => 'Mã nhà cung cấp không được để trống.',
            'manufacturerCode.unique'   => 'Mã nhà cung cấp đã tồn tại.',
            'manufacturerName.required' => 'Tên nhà cung cấp không được để trống.',
            'website.url'               => 'Website không đúng định dạng URL.',
        ]);

        Manufacturer::create($data);

        return redirect()->route('admin.manufacturers.index')
                         ->with('success', 'Đã thêm nhà cung cấp "' . $data['manufacturerName'] . '".');
    }

    public function update(Request $request, $id)
    {
        $manufacturer = Manufacturer::findOrFail($id);

        $data = $request->validate([
            'manufacturerCode' => 'required|string|max:50|unique:manufacturers,manufacturerCode,' . $id . ',manufacturerID',
            'manufacturerName' => 'required|string|max:200',
            'country'          => 'nullable|string|max:100',
            'website'          => 'nullable|url|max:255',
            
        ]);

        $manufacturer->update($data);

        return redirect()->route('admin.manufacturers.index')
                         ->with('success', 'Đã cập nhật nhà cung cấp.');
    }

    public function destroy($id)
    {
        $manufacturer = Manufacturer::findOrFail($id);

        if ($manufacturer->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa nhà cung cấp đang có sản phẩm.');
        }

        $name = $manufacturer->manufacturerName;
        $manufacturer->delete();

        return redirect()->route('admin.manufacturers.index')
                         ->with('success', 'Đã xóa nhà cung cấp "' . $name . '".');
    }
}