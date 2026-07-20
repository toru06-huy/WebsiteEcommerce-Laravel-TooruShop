<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with(['parent', 'products']);

        if ($request->filled('search')) {
            $query->where('categoryName', 'like', '%' . $request->search . '%');
        }

        $categories    = $query->paginate(15)->withQueryString();
        $allCategories = Category::orderBy('categoryName')->get();

        return view('admin.categories.index', compact('categories', 'allCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoryName' => 'required|string|max:100|unique:categories,categoryName',
            'parentID'     => 'nullable|exists:categories,categoryID',
            'description'  => 'nullable|string|max:500',
        ], [
            'categoryName.required' => 'Tên danh mục không được để trống.',
            'categoryName.unique'   => 'Tên danh mục đã tồn tại.',
            'parentID.exists'       => 'Danh mục cha không hợp lệ.',
        ]);

        Category::create($data);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Đã thêm danh mục "' . $data['categoryName'] . '".');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'categoryName' => 'required|string|max:100|unique:categories,categoryName,' . $id . ',categoryID',
            'parentID'     => 'nullable|exists:categories,categoryID',
            'description'  => 'nullable|string|max:500',
        ]);

        // Tránh self-reference
        if ($data['parentID'] == $id) {
            return back()->with('error', 'Danh mục không thể là cha của chính nó.');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Đã cập nhật danh mục.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có sản phẩm.');
        }

        if ($category->children()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có danh mục con.');
        }

        $name = $category->categoryName;
        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Đã xóa danh mục "' . $name . '".');
    }
}