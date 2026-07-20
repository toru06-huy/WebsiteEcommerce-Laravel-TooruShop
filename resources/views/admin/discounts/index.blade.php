@extends('admin.layout')

@section('title', 'Mã giảm giá')
@section('page-title', 'Mã giảm giá')
@section('breadcrumb', 'VELOUR / Mã giảm giá')

@section('topbar-actions')
<button class="topbar-btn" onclick="openModal('modal-create')">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Thêm mã giảm giá
</button>
@endsection

@section('content')

<div class="table-card">
    <div class="table-head">
        <h2>Danh sách mã giảm giá ({{ $discounts->total() }})</h2>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.discounts.index') }}">
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" placeholder="Tìm mã giảm giá..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã</th>
                <th>Tên chương trình</th>
                <th>Loại</th>
                <th>Giá trị</th>
                <th>Đơn tối thiểu</th>
                <th>Giới hạn</th>
                <th>Thời gian hiệu lực</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($discounts as $discount)
            <tr>
                <td>
                    <span style="font-family:'Cormorant Garamond',serif;font-size:14px;font-weight:600;color:var(--gold);">
                        {{ $discount->discountCode }}
                    </span>
                </td>
                <td><strong>{{ $discount->discountName }}</strong></td>
                <td class="td-muted">
                    {{ $discount->discountType === 'percentage' ? 'Phần trăm (%)' : 'Số tiền (đ)' }}
                </td>
                <td>
                    @if($discount->discountType === 'percentage')
                        {{ number_format($discount->discountValue, 0, ',', '.') }}%
                    @else
                        {{ number_format($discount->discountValue, 0, ',', '.') }}đ
                    @endif
                </td>
                <td class="td-muted">{{ number_format($discount->minOrderValue, 0, ',', '.') }}đ</td>
                <td class="td-muted">{{ $discount->discountLimit }}</td>
                <td class="td-muted" style="font-size:12px;">
                    @if($discount->startDate || $discount->endDate)
                        {{ $discount->startDate ? $discount->startDate->format('d/m/Y H:i') : '—' }}
                        <br>đến
                        {{ $discount->endDate ? $discount->endDate->format('d/m/Y H:i') : '—' }}
                    @else
                        —
                    @endif
                </td>
                <td>
                    <span class="badge {{ $discount->isActive ? 'active' : 'inactive' }}">
                        {{ $discount->isActive ? 'Đang áp dụng' : 'Tạm dừng' }}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-icon"
                            onclick="openEditModal({{ $discount->discountID }}, {!! \Illuminate\Support\Js::from($discount) !!})"
                            title="Sửa">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.discounts.destroy', $discount->discountID) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-icon danger" title="Xóa"
                                onclick="confirmDelete(this.closest('form'), '{{ addslashes($discount->discountCode) }}')">
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
                <td colspan="9">
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path d="M20.59 13.41L11 4H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z"/>
                            <circle cx="7.5" cy="7.5" r="1.5"/>
                        </svg>
                        <h3>Chưa có mã giảm giá</h3>
                        <p>Thêm mã giảm giá để áp dụng cho đơn hàng của khách.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($discounts->hasPages())
    <div class="pagination-wrap">
        <span>Hiển thị {{ $discounts->firstItem() }}–{{ $discounts->lastItem() }} / {{ $discounts->total() }}</span>
        <div class="pagination">
            @foreach($discounts->getUrlRange(1, $discounts->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $discounts->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Modal Tạo --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal">
        <div class="modal-head">
            <h3>Thêm mã giảm giá</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.discounts.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid" style="gap:16px;">
                    <div class="form-group">
                        <label>Mã giảm giá <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="discountCode" class="form-control" required placeholder="VELOUR10" style="text-transform:uppercase;">
                    </div>
                    <div class="form-group">
                        <label>Tên chương trình <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="discountName" class="form-control" required placeholder="Giảm 10% cho đơn hàng đầu tiên">
                    </div>
                    <div class="form-group">
                        <label>Loại giảm giá <span style="color:var(--danger)">*</span></label>
                        <select name="discountType" class="form-control" required>
                            <option value="percentage">Phần trăm (%)</option>
                            <option value="fixedAmount">Số tiền cố định (đ)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giá trị giảm <span style="color:var(--danger)">*</span></label>
                        <input type="number" step="0.01" min="0" name="discountValue" class="form-control" required placeholder="10">
                    </div>
                    <div class="form-group">
                        <label>Đơn hàng tối thiểu (đ)</label>
                        <input type="number" step="0.01" min="0" name="minOrderValue" class="form-control" placeholder="200000">
                    </div>
                    <div class="form-group">
                        <label>Giới hạn số lượt sử dụng</label>
                        <input type="number" min="0" name="discountLimit" class="form-control" placeholder="10">
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <input type="datetime-local" name="startDate" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc</label>
                        <input type="datetime-local" name="endDate" class="form-control">
                    </div>
                    <div class="form-group" style="flex-direction:row;align-items:center;gap:8px;">
                        <input type="checkbox" name="isActive" id="create-isActive" value="1" checked style="width:16px;height:16px;">
                        <label for="create-isActive" style="margin:0;">Kích hoạt áp dụng ngay</label>
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
            <h3>Chỉnh sửa mã giảm giá</h3>
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
                        <label>Mã giảm giá</label>
                        <input type="text" name="discountCode" id="edit-code" class="form-control" required style="text-transform:uppercase;">
                    </div>
                    <div class="form-group">
                        <label>Tên chương trình</label>
                        <input type="text" name="discountName" id="edit-name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Loại giảm giá</label>
                        <select name="discountType" id="edit-type" class="form-control" required>
                            <option value="percentage">Phần trăm (%)</option>
                            <option value="fixedAmount">Số tiền cố định (đ)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giá trị giảm</label>
                        <input type="number" step="0.01" min="0" name="discountValue" id="edit-value" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Đơn hàng tối thiểu (đ)</label>
                        <input type="number" step="0.01" min="0" name="minOrderValue" id="edit-minOrder" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Giới hạn số lượt sử dụng</label>
                        <input type="number" min="0" name="discountLimit" id="edit-limit" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <input type="datetime-local" name="startDate" id="edit-start" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc</label>
                        <input type="datetime-local" name="endDate" id="edit-end" class="form-control">
                    </div>
                    <div class="form-group" style="flex-direction:row;align-items:center;gap:8px;">
                        <input type="checkbox" name="isActive" id="edit-isActive" value="1" style="width:16px;height:16px;">
                        <label for="edit-isActive" style="margin:0;">Kích hoạt áp dụng</label>
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
    // value dạng "2026-06-14T10:30:00.000000Z" hoặc "2026-06-14 10:30:00"
    const d = new Date(value);
    if (isNaN(d.getTime())) return '';
    const pad = (n) => String(n).padStart(2, '0');
    return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate())
        + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
}

function openEditModal(id, discount) {
    document.getElementById('edit-form').action = '/admin/discounts/' + id;
    document.getElementById('edit-code').value      = discount.discountCode;
    document.getElementById('edit-name').value      = discount.discountName;
    document.getElementById('edit-type').value      = discount.discountType;
    document.getElementById('edit-value').value     = discount.discountValue;
    document.getElementById('edit-minOrder').value  = discount.minOrderValue;
    document.getElementById('edit-limit').value     = discount.discountLimit;
    document.getElementById('edit-start').value     = toDatetimeLocal(discount.startDate);
    document.getElementById('edit-end').value       = toDatetimeLocal(discount.endDate);
    document.getElementById('edit-isActive').checked = !!discount.isActive;
    openModal('modal-edit');
}
</script>
@endpush