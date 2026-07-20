@extends('admin.layout')

@section('title', 'Giảm giá sản phẩm')
@section('page-title', 'Giảm giá sản phẩm')
@section('breadcrumb', 'VELOUR / Giảm giá sản phẩm')

@section('topbar-actions')
<button class="topbar-btn" onclick="openModal('modal-create')">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Thêm giảm giá sản phẩm
</button>
@endsection

@section('content')

<div class="table-card">
    <div class="table-head">
        <h2>Danh sách giảm giá sản phẩm ({{ $productDiscounts->total() }})</h2>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.product-discounts.index') }}">
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" placeholder="Tìm theo tên sản phẩm..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Giá gốc</th>
                <th>% Giảm</th>
                <th>Giá sau giảm</th>
                <th>Thời gian hiệu lực</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($productDiscounts as $pd)
            @php
                $basePrice  = (float) ($pd->product->basePrice ?? 0);
                $finalPrice = $pd->calcDiscountedPrice($basePrice);
                $now        = now();
                $isRunning  = $pd->isActive && $pd->startDate <= $now && $pd->endDate >= $now;
            @endphp
            <tr>
                <td><strong>{{ $pd->product->productName ?? 'Sản phẩm đã xóa' }}</strong></td>
                <td class="td-muted">{{ number_format($basePrice, 0, ',', '.') }}đ</td>
                <td>-{{ number_format($pd->discountValue, 0, ',', '.') }}%</td>
                <td>
                    <span style="font-family:'Cormorant Garamond',serif;font-size:14px;font-weight:600;color:var(--gold);">
                        {{ number_format($finalPrice, 0, ',', '.') }}đ
                    </span>
                </td>
                <td class="td-muted" style="font-size:12px;">
                    {{ $pd->startDate->format('d/m/Y H:i') }}<br>đến<br>{{ $pd->endDate->format('d/m/Y H:i') }}
                </td>
                <td>
                    @if(!$pd->isActive)
                        <span class="badge inactive">Tạm dừng</span>
                    @elseif($isRunning)
                        <span class="badge active">Đang diễn ra</span>
                    @elseif($pd->startDate > $now)
                        <span class="badge active">Sắp diễn ra</span>
                    @else
                        <span class="badge inactive">Đã kết thúc</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-icon"
                            onclick="openEditModal({{ $pd->productDiscountID }}, {!! \Illuminate\Support\Js::from($pd) !!})"
                            title="Sửa">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.product-discounts.destroy', $pd->productDiscountID) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-icon danger" title="Xóa"
                                onclick="confirmDelete(this.closest('form'), '{{ addslashes($pd->product->productName ?? '') }}')">
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
                <td colspan="7">
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path d="M20.59 13.41L11 4H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z"/>
                            <circle cx="7.5" cy="7.5" r="1.5"/>
                        </svg>
                        <h3>Chưa có giảm giá sản phẩm</h3>
                        <p>Thêm chương trình giảm giá theo từng sản phẩm.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($productDiscounts->hasPages())
    <div class="pagination-wrap">
        <span>Hiển thị {{ $productDiscounts->firstItem() }}–{{ $productDiscounts->lastItem() }} / {{ $productDiscounts->total() }}</span>
        <div class="pagination">
            @foreach($productDiscounts->getUrlRange(1, $productDiscounts->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $productDiscounts->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Modal Tạo --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal">
        <div class="modal-head">
            <h3>Thêm giảm giá sản phẩm</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.product-discounts.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid" style="gap:16px;">
                    <div class="form-group" style="grid-column:1 / -1;">
                        <label>Sản phẩm <span style="color:var(--danger)">*</span></label>
                        <select name="productID" class="form-control" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->productID }}">
                                    {{ $product->productName }} ({{ number_format($product->basePrice, 0, ',', '.') }}đ)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>% Giảm giá <span style="color:var(--danger)">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="discountValue"
                               class="form-control" required placeholder="10">
                        <small style="color:var(--text-muted);">Giá sau giảm = Giá gốc − (Giá gốc × %)</small>
                    </div>
                    <div class="form-group" style="flex-direction:row;align-items:center;gap:8px;margin-top:20px;">
                        <input type="checkbox" name="isActive" id="create-isActive" value="1" checked style="width:16px;height:16px;">
                        <label for="create-isActive" style="margin:0;">Kích hoạt áp dụng ngay</label>
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu <span style="color:var(--danger)">*</span></label>
                        <input type="datetime-local" name="startDate" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc <span style="color:var(--danger)">*</span></label>
                        <input type="datetime-local" name="endDate" class="form-control" required>
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
            <h3>Chỉnh sửa giảm giá sản phẩm</h3>
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
                    <div class="form-group" style="grid-column:1 / -1;">
                        <label>Sản phẩm</label>
                        <select name="productID" id="edit-product" class="form-control" required>
                            @foreach($products as $product)
                                <option value="{{ $product->productID }}">
                                    {{ $product->productName }} ({{ number_format($product->basePrice, 0, ',', '.') }}đ)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>% Giảm giá</label>
                        <input type="number" step="0.01" min="0" max="100" name="discountValue"
                               id="edit-value" class="form-control" required>
                        <small style="color:var(--text-muted);">Giá sau giảm = Giá gốc − (Giá gốc × %)</small>
                    </div>
                    <div class="form-group" style="flex-direction:row;align-items:center;gap:8px;margin-top:20px;">
                        <input type="checkbox" name="isActive" id="edit-isActive" value="1" style="width:16px;height:16px;">
                        <label for="edit-isActive" style="margin:0;">Kích hoạt áp dụng</label>
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <input type="datetime-local" name="startDate" id="edit-start" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc</label>
                        <input type="datetime-local" name="endDate" id="edit-end" class="form-control" required>
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
function toDatetimeLocal(value) {
    if (!value) return '';
    const d = new Date(value);
    if (isNaN(d.getTime())) return '';
    const pad = n => String(n).padStart(2, '0');
    return d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate())
        + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
}

function openEditModal(id, pd) {
    document.getElementById('edit-form').action     = '/admin/product-discounts/' + id;
    document.getElementById('edit-product').value   = pd.productID;
    document.getElementById('edit-value').value     = pd.discountValue;
    document.getElementById('edit-start').value     = toDatetimeLocal(pd.startDate);
    document.getElementById('edit-end').value       = toDatetimeLocal(pd.endDate);
    document.getElementById('edit-isActive').checked = !!pd.isActive;
    openModal('modal-edit');
}

// Tự mở lại modal tạo mới khi có lỗi validation (old input được giữ bởi Laravel)
@if($errors->any() && old('productID'))
document.addEventListener('DOMContentLoaded', function () {
    // Khôi phục giá trị cũ vào modal tạo
    const sel = document.querySelector('#modal-create select[name="productID"]');
    if (sel) sel.value = '{{ old('productID') }}';

    const val = document.querySelector('#modal-create input[name="discountValue"]');
    if (val) val.value = '{{ old('discountValue') }}';

    const start = document.querySelector('#modal-create input[name="startDate"]');
    if (start) start.value = '{{ old('startDate') }}';

    const end = document.querySelector('#modal-create input[name="endDate"]');
    if (end) end.value = '{{ old('endDate') }}';

    const active = document.querySelector('#modal-create input[name="isActive"]');
    if (active) active.checked = {{ old('isActive') ? 'true' : 'false' }};

    openModal('modal-create');
});
@endif
</script>
@endpush