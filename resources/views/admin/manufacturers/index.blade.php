@extends('admin.layout')

@section('title', 'Nhà cung cấp')
@section('page-title', 'Nhà cung cấp')
@section('breadcrumb', 'VELOUR / Nhà cung cấp')

@section('topbar-actions')
<button class="topbar-btn" onclick="openModal('modal-create')">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Thêm nhà cung cấp
</button>
@endsection

@section('content')

<div class="table-card">
    <div class="table-head">
        <h2>Danh sách nhà cung cấp ({{ $manufacturers->total() }})</h2>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.manufacturers.index') }}">
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" placeholder="Tìm nhà cung cấp..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã</th>
                <th>Tên nhà cung cấp</th>
                <th>Quốc gia</th>
                <th>Website</th>
                <th>Số sản phẩm</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($manufacturers as $mfr)
            <tr>
                <td>
                    <span style="font-family:'Cormorant Garamond',serif;font-size:14px;font-weight:600;color:var(--gold);">
                        {{ $mfr->manufacturerCode }}
                    </span>
                </td>
                <td><strong>{{ $mfr->manufacturerName }}</strong></td>
                <td class="td-muted">{{ $mfr->country ?? '—' }}</td>
                <td>
                    @if($mfr->website)
                        <a href="{{ $mfr->website }}" target="_blank" style="color:var(--gold);font-size:13px;text-decoration:none;">
                            {{ parse_url($mfr->website, PHP_URL_HOST) ?? $mfr->website }}
                        </a>
                    @else
                        <span class="td-muted">—</span>
                    @endif
                </td>
                <td>{{ $mfr->products->count() }}</td>
                <td>
                    <div class="action-btns">
                        <button class="btn-icon"
                            onclick="openEditModal({{ $mfr->manufacturerID }}, '{{ addslashes($mfr->manufacturerCode) }}', '{{ addslashes($mfr->manufacturerName) }}', '{{ addslashes($mfr->country ?? '') }}', '{{ addslashes($mfr->website ?? '') }}')"
                            title="Sửa">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.manufacturers.destroy', $mfr->manufacturerID) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-icon danger" title="Xóa"
                                onclick="confirmDelete(this.closest('form'), '{{ addslashes($mfr->manufacturerName) }}')">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
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
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        </svg>
                        <h3>Chưa có nhà cung cấp</h3>
                        <p>Thêm nhà cung cấp để gán cho sản phẩm.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($manufacturers->hasPages())
    <div class="pagination-wrap">
        <span>Hiển thị {{ $manufacturers->firstItem() }}–{{ $manufacturers->lastItem() }} / {{ $manufacturers->total() }}</span>
        <div class="pagination">
            @foreach($manufacturers->getUrlRange(1, $manufacturers->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $manufacturers->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Modal Tạo --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal">
        <div class="modal-head">
            <h3>Thêm nhà cung cấp</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.manufacturers.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid" style="gap:16px;">
                    <div class="form-group">
                        <label>Mã nhà cung cấp <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="manufacturerCode" class="form-control" required placeholder="NCC001">
                    </div>
                    <div class="form-group">
                        <label>Tên nhà cung cấp <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="manufacturerName" class="form-control" required placeholder="Công ty TNHH ABC">
                    </div>
                    <div class="form-group">
                        <label>Quốc gia</label>
                        <input type="text" name="country" class="form-control" placeholder="Việt Nam">
                    </div>
                    <div class="form-group">
                        <label>Website</label>
                        <input type="url" name="website" class="form-control" placeholder="https://example.com">
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="topbar-btn secondary" onclick="closeModal('modal-create')">Hủy</button>
                <button type="submit" class="topbar-btn">Lưu</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Sửa --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-head">
            <h3>Chỉnh sửa nhà cung cấp</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" id="edit-form" action="">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-grid" style="gap:16px;">
                    <div class="form-group">
                        <label>Mã nhà cung cấp</label>
                        <input type="text" name="manufacturerCode" id="edit-code" class="form-control" required readonly>
                    </div>
                    <div class="form-group">
                        <label>Tên nhà cung cấp</label>
                        <input type="text" name="manufacturerName" id="edit-name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Quốc gia</label>
                        <input type="text" name="country" id="edit-country" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Website</label>
                        <input type="url" name="website" id="edit-website" class="form-control">
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
function openEditModal(id, code, name, country, website) {
    document.getElementById('edit-form').action = '/admin/manufacturers/' + id;
    document.getElementById('edit-code').value    = code;
    document.getElementById('edit-name').value    = name;
    document.getElementById('edit-country').value = country;
    document.getElementById('edit-website').value = website;
    openModal('modal-edit');
}
</script>
@endpush