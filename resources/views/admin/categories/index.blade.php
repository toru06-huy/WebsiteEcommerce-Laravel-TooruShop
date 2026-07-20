@extends('admin.layout')

@section('title', 'Danh mục')
@section('page-title', 'Danh mục')
@section('breadcrumb', 'VELOUR / Danh mục')

@section('topbar-actions')
<button class="topbar-btn" onclick="openModal('modal-create')">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Thêm danh mục
</button>
@endsection

@section('content')

<div class="table-card">
    <div class="table-head">
        <h2>Danh sách danh mục ({{ $categories->total() }})</h2>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.categories.index') }}">
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" placeholder="Tìm danh mục..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tên danh mục</th>
                <th>Danh mục cha</th>
                <th>Mô tả</th>
                <th>Số sản phẩm</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td class="td-muted">{{ $category->categoryID }}</td>
                <td><strong>{{ $category->categoryName }}</strong></td>
                <td class="td-muted">{{ $category->parent->categoryName ?? '— Gốc —' }}</td>
                <td class="td-muted" style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $category->description ?: '—' }}
                </td>
                <td>{{ $category->products->count() }}</td>
                <td>
                    <div class="action-btns">
                        <button class="btn-icon" onclick="openEditModal({{ $category->categoryID }}, '{{ addslashes($category->categoryName) }}', {{ $category->parentID ?? 'null' }}, '{{ addslashes($category->description ?? '') }}')"
                            title="Sửa">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category->categoryID) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-icon danger" title="Xóa"
                                onclick="confirmDelete(this.closest('form'), '{{ addslashes($category->categoryName) }}')">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <h3>Chưa có danh mục</h3>
                        <p>Thêm danh mục đầu tiên để bắt đầu.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($categories->hasPages())
    <div class="pagination-wrap">
        <span>Hiển thị {{ $categories->firstItem() }}–{{ $categories->lastItem() }} / {{ $categories->total() }}</span>
        <div class="pagination">
            @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $categories->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Modal Tạo mới --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal">
        <div class="modal-head">
            <h3>Thêm danh mục</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid cols-1" style="gap:18px;">
                    <div class="form-group">
                        <label>Tên danh mục <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="categoryName" class="form-control @error('categoryName') error @enderror"
                               value="{{ old('categoryName') }}" placeholder="Ví dụ: Áo nam" required>
                        @error('categoryName')<p class="form-hint" style="color:var(--danger)">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label>Danh mục cha</label>
                        <select name="parentID" class="form-control">
                            <option value="">— Không có (danh mục gốc) —</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat->categoryID }}" {{ old('parentID') == $cat->categoryID ? 'selected' : '' }}>
                                    {{ $cat->categoryName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" placeholder="Mô tả ngắn về danh mục...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="topbar-btn secondary" onclick="closeModal('modal-create')">Hủy</button>
                <button type="submit" class="topbar-btn">Lưu danh mục</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Chỉnh sửa --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-head">
            <h3>Chỉnh sửa danh mục</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" id="edit-form" action="">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-grid cols-1" style="gap:18px;">
                    <div class="form-group">
                        <label>Tên danh mục <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="categoryName" id="edit-name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Danh mục cha</label>
                        <select name="parentID" id="edit-parent" class="form-control">
                            <option value="">— Không có (danh mục gốc) —</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat->categoryID }}">{{ $cat->categoryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" id="edit-desc" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="topbar-btn secondary" onclick="closeModal('modal-edit')">Hủy</button>
                <button type="submit" class="topbar-btn">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEditModal(id, name, parentId, desc) {
    document.getElementById('edit-form').action = '/admin/categories/' + id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-desc').value = desc;
    const sel = document.getElementById('edit-parent');
    sel.value = parentId || '';
    openModal('modal-edit');
}
</script>
@endpush