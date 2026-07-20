@extends('admin.layout')

@section('title', 'Nhân viên')
@section('page-title', 'Nhân viên')
@section('breadcrumb', 'VELOUR / Nhân viên')

@section('topbar-actions')
<button class="topbar-btn" onclick="openModal('modal-create')">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Thêm nhân viên
</button>
@endsection

@section('content')

<div class="table-card">
    <div class="table-head">
        <h2>Danh sách nhân viên ({{ $employees->total() }})</h2>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.employees.index') }}">
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" placeholder="Tìm nhân viên..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã NV</th>
                <th>Họ tên</th>
                <th>Email / SĐT</th>
                <th>Chức vụ</th>
                <th>Lương</th>
                <th>Ngày vào</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $emp)
            @php 
                $addr = $emp->user->addresses->first(); 
            @endphp
            <tr onclick="openEmployeeDetail({{ $emp->employeeID }})" style="cursor:pointer;">
                <td>
                    <span
                        style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:600;color:var(--gold);text-decoration:underline;text-underline-offset:3px;">
                        {{ $emp->employeeCode }}
                    </span>
                </td>
                <td>
                    <strong>{{ $emp->user->fullName }}</strong>
                    <div style="font-size:11px;color:var(--muted);">{{ $emp->user->sex ?? '' }}</div>
                </td>
                <td class="td-muted">
                    {{ $emp->user->email ?? '—' }}<br>
                    <span style="font-size:12px;">{{ $emp->user->phone ?? '—' }}</span>
                </td>
                <td>
                    <span class="badge employee">{{ $emp->position->positionName ?? '—' }}</span>
                </td>
                <td>{{ number_format($emp->salary, 0, ',', '.') }}đ</td>
                <td class="td-muted">{{ $emp->hireDate ? $emp->hireDate->format('d/m/Y') : '—' }}</td>
                <td>
                    <span class="badge {{ $emp->user->IsActive ? 'active' : 'inactive' }}">
                        {{ $emp->user->IsActive ? 'Đang làm' : 'Nghỉ việc' }}
                    </span>
                </td>
                <td onclick="event.stopPropagation()">
                    <div class="action-btns">
                        <button class="btn-icon"
                            onclick="openEditModal(
                                {{ $emp->employeeID }},
                                {{ $emp->userID }},
                                '{{ addslashes($emp->employeeCode) }}',
                                {{ $emp->positionID ?? 'null' }},
                                '{{ $emp->salary }}',
                                '{{ $emp->hireDate ? $emp->hireDate->format('Y-m-d') : '' }}',
                                '{{ addslashes($emp->user->fullName) }}',
                                '{{ addslashes($emp->user->email ?? '') }}',
                                '{{ addslashes($emp->user->phone ?? '') }}',
                                '{{ $emp->user->sex ?? '' }}',
                                '{{ $emp->user->birthday ? $emp->user->birthday->format('Y-m-d') : '' }}',
                                '{{ addslashes($addr?->city ?? 'Hồ Chí Minh') }}',
                                '{{ addslashes($addr?->district ?? '') }}',
                                '{{ addslashes($addr?->ward ?? '') }}',
                                '{{ addslashes($addr?->addressDetail ?? '') }}'
                            )" title="Sửa">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.employees.destroy', $emp->employeeID) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-icon danger" title="Xóa"
                                onclick="confirmDelete(this.closest('form'), '{{ addslashes($emp->user->fullName) }}')">
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
                <td colspan="8">
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        </svg>
                        <h3>Chưa có nhân viên</h3>
                        <p>Thêm nhân viên đầu tiên vào hệ thống.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($employees->hasPages())
    <div class="pagination-wrap">
        <span>Hiển thị {{ $employees->firstItem() }}–{{ $employees->lastItem() }} / {{ $employees->total() }}</span>
        <div class="pagination">
            @foreach($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $employees->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Modal Tạo NV --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal" style="width:640px;">
        <div class="modal-head">
            <h3>Thêm nhân viên mới</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.employees.store') }}">
            @csrf
            <div class="modal-body">
                <p style="font-size:12px;color:var(--muted);margin-bottom:20px;padding:10px 14px;background:rgba(184,149,90,.08);border-left:3px solid var(--gold);border-radius:2px;">
                    Nhân viên dùng chung tài khoản User. Điền thông tin tài khoản để tạo mới hoặc chọn user hiện có.
                </p>
                
                <div style="border-bottom:1px solid var(--border);margin-bottom:20px;padding-bottom:8px;">
                    <strong style="font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);">Thông tin tài khoản</strong>
                </div>
                <div class="form-grid" style="gap:16px;margin-bottom:20px;">
                    <div class="form-group">
                        <label>Họ tên <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="fullName" class="form-control" required placeholder="Nguyễn Văn A">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nhanvien@velour.vn">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" placeholder="0901234567">
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu <span style="color:var(--danger)">*</span></label>
                        <input type="password" name="password" class="form-control" required placeholder="Tối thiểu 8 ký tự">
                    </div>
                    <div class="form-group">
                        <label>Giới tính</label>
                        <select name="sex" class="form-control">
                            <option value="">Chọn</option>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" name="birthday" class="form-control">
                    </div>
                </div>

                <div style="border-bottom:1px solid var(--border);margin-bottom:20px;padding-bottom:8px;">
                    <strong style="font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);">Địa chỉ liên hệ</strong>
                </div>
                <div class="form-grid" style="gap:16px;margin-bottom:16px;grid-template-columns: repeat(3, 1fr);">
                    <div class="form-group">
                        <label>Tỉnh / Thành phố</label>
                        <input type="text" name="city" class="form-control" value="Hồ Chí Minh" readonly style="background:#f5f5f5; color:#555; cursor:not-allowed;">
                    </div>
                    <div class="form-group">
                        <label>Quận / Huyện</label>
                        <select name="district" id="create-district" class="form-control" onchange="updateWards('create')">
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phường / Xã</label>
                        <select name="ward" id="create-ward" class="form-control">
                            <option value="">Chọn Phường/Xã</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:20px;">
                    <label>Số nhà, tên đường cụ thể</label>
                    <input type="text" name="addressDetail" class="form-control" placeholder="Ví dụ: 123 Nguyễn Huệ">
                </div>

                <div style="border-bottom:1px solid var(--border);margin-bottom:20px;padding-bottom:8px;">
                    <strong style="font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);">Thông tin nhân viên</strong>
                </div>
                <div class="form-grid" style="gap:16px;">
                    <div class="form-group">
                        <label>Mã nhân viên <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="employeeCode" class="form-control" required placeholder="NV001">
                    </div>
                    <div class="form-group">
                        <label>Chức vụ <span style="color:var(--danger)">*</span></label>
                        <select name="positionID" class="form-control" required>
                            <option value="">Chọn chức vụ</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->positionID }}">{{ $pos->positionName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lương (VNĐ)</label>
                        <input type="number" name="salary" class="form-control" min="0" step="100000" placeholder="8000000">
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <input type="date" name="hireDate" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="topbar-btn secondary" onclick="closeModal('modal-create')">Hủy</button>
                <button type="submit" class="topbar-btn">Tạo nhân viên</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Sửa NV --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal" style="width:640px;">
        <div class="modal-head">
            <h3>Chỉnh sửa nhân viên</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <form method="POST" id="edit-form" action="">
            @csrf @method('PUT')
            <div class="modal-body">
                <div style="border-bottom:1px solid var(--border);margin-bottom:20px;padding-bottom:8px;">
                    <strong style="font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);">Thông tin tài khoản</strong>
                </div>
                <div class="form-grid" style="gap:16px;margin-bottom:20px;">
                    <div class="form-group">
                        <label>Họ tên <span style="color:var(--danger)">*</span></label>
                        <input type="text" name="fullName" id="edit-fullname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email (Không thể sửa)</label>
                        <input type="email" id="edit-email" class="form-control" readonly style="background:#f5f5f5; color:#888; cursor:not-allowed;">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" id="edit-phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Giới tính</label>
                        <select name="sex" id="edit-sex" class="form-control">
                            <option value="">Chọn</option>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" name="birthday" id="edit-birthday" class="form-control">
                    </div>
                </div>

                <div style="border-bottom:1px solid var(--border);margin-bottom:20px;padding-bottom:8px;">
                    <strong style="font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);">Thông tin nhân viên</strong>
                </div>
                <div class="form-grid" style="gap:16px;margin-bottom:20px;">
                    <div class="form-group">
                        <label>Mã nhân viên</label>
                        <input type="text" name="employeeCode" id="edit-code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Chức vụ</label>
                        <select name="positionID" id="edit-position" class="form-control">
                            <option value="">Chọn chức vụ</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->positionID }}">{{ $pos->positionName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lương (VNĐ)</label>
                        <input type="number" name="salary" id="edit-salary" class="form-control" min="0" step="100000">
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <input type="date" name="hireDate" id="edit-hire" class="form-control">
                    </div>
                </div>

                <div style="border-bottom:1px solid var(--border);margin-bottom:20px;padding-bottom:8px;">
                    <strong style="font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);">Địa chỉ liên hệ</strong>
                </div>
                <div class="form-grid" style="gap:16px;margin-bottom:16px;grid-template-columns: repeat(3, 1fr);">
                    <div class="form-group">
                        <label>Tỉnh / Thành phố</label>
                        <input type="text" name="city" id="edit-city" class="form-control" value="Hồ Chí Minh" readonly style="background:#f5f5f5; color:#555; cursor:not-allowed;">
                    </div>
                    <div class="form-group">
                        <label>Quận / Huyện</label>
                        <select name="district" id="edit-district" class="form-control" onchange="updateWards('edit')">
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phường / Xã</label>
                        <select name="ward" id="edit-ward" class="form-control">
                            <option value="">Chọn Phường/Xã</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Số nhà, tên đường cụ thể</label>
                    <input type="text" name="addressDetail" id="edit-address-detail" class="form-control">
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
// Bộ dữ liệu hành chính chuẩn TP. Hồ Chí Minh
const hcmData = {
    "Quận 1": ["Phường Bến Nghé", "Phường Bến Thành", "Phường Cầu Kho", "Phường Cầu Ông Lãnh", "Phường Cô Giang", "Phường Đa Kao", "Phường Nguyễn Cư Trinh", "Phường Nguyễn Thái Bình", "Phường Phạm Ngũ Lão", "Phường Tân Định"],
    "Quận 3": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường Võ Thị Sáu"],
    "Quận 4": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 6", "Phường 8", "Phường 9", "Phường 10", "Phường 13", "Phường 14", "Phường 15", "Phường 16"],
    "Quận 5": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14"],
    "Quận 6": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14"],
    "Quận 7": ["Phường Bình Thuận", "Phường Phú Mỹ", "Phường Phú Thuận", "Phường Tân Hưng", "Phường Tân Kiểng", "Phường Tân Phong", "Phường Tân Phú", "Phường Tân Quy", "Phường Tân Thuận Đông", "Phường Tân Thuận Tây"],
    "Quận 8": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15", "Phường 16"],
    "Quận 10": ["Phường 1", "Phường 2", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15"],
    "Quận 11": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15"],
    "Quận 12": ["Phường An Phú Đông", "Phường Đông Hưng Thuận", "Phường Hiệp Thành", "Phường Tân Chánh Hiệp", "Phường Tân Hưng Thuận", "Phường Tân Thới Hiệp", "Phường Tân Thới Nhất", "Phường Thạnh Lộc", "Phường Thạnh Xuân", "Phường Thới An", "Phường Trung Mỹ Tây"],
    "Thành phố Thủ Đức": ["Phường An Khánh", "Phường An Lợi Đông", "Phường An Phú", "Phường Bình Chiểu", "Phường Bình Thọ", "Phường Bình Trưng Đông", "Phường Bình Trưng Tây", "Phường Cát Lái", "Phường Hiệp Bình Chánh", "Phường Hiệp Bình Phước", "Phường Hiệp Phú", "Phường Linh Chiểu", "Phường Linh Đông", "Phường Linh Tây", "Phường Linh Trung", "Phường Linh Xuân", "Phường Long Bình", "Phường Long Phước", "Phường Long Thạnh Mỹ", "Phường Long Trường", "Phường Phú Hữu", "Phường Phước Bình", "Phường Phước Long A", "Phường Phước Long B", "Phường Tam Bình", "Phường Tam Phú", "Phường Tăng Nhơn Phú A", "Tăng Nhơn Phú B", "Phường Thạnh Mỹ Lợi", "Phường Thảo Điền", "Phường Thủ Thiêm", "Phường Trường Thạnh", "Phường Trường Thọ"],
    "Quận Bình Tân": ["Phường An Lạc", "Phường An Lạc A", "Phường Bình Hịat Đông", "Phường Bình Trị Đông A", "Phường Bình Trị Đông B", "Phường Bình Hưng Hòa", "Phường Bình Hưng Hòa A", "Phường Bình Hưng Hòa B", "Phường Tân Tạo", "Phường Tân Tạo A"],
    "Quận Bình Thạnh": ["Phường 1", "Phường 2", "Phường 3", "Phường 5", "Phường 6", "Phường 7", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15", "Phường 17", "Phường 19", "Phường 21", "Phường 22", "Phường 24", "Phường 25", "Phường 26", "Phường 27", "Phường 28"],
    "Quận Gò Vấp": ["Phường 1", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15", "Phường 16", "Phường 17"],
    "Quận Phú Nhuận": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 13", "Phường 15", "Phường 17"],
    "Quận Tân Bình": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15"],
    "Quận Tân Phú": ["Phường Hiệp Tân", "Phường Hòa Thạnh", "Phường Phú Thạnh", "Phường Phú Thọ Hòa", "Phường Phú Trung", "Phường Sơn Kỳ", "Phường Tân Quý", "Phường Tân Sơn Nhì", "Phường Tân Thành", "Phường Tân Thới Hòa", "Phường Tây Thạnh"],
    "Huyện Bình Chánh": ["Thị trấn Tân Túc", "Xã An Phú Tây", "Xã Bình Chánh", "Xã Bình Hưng", "Xã Bình Lợi", "Xã Đa Phước", "Xã Hưng Long", "Xã Lê Minh Xuân", "Xã Phạm Văn Hai", "Xã Phong Phú", "Xã Quy Đức", "Xã Tân Kiên", "Xã Tân Nhựt", "Xã Tân Quý Tây", "Xã Vĩnh Lộc A", "Xã Vĩnh Lộc B"],
    "Huyện Cần Giờ": ["Thị trấn Cần Thạnh", "Xã An Thới Đông", "Xã Bình Khánh", "Xã Long Hòa", "Xã Lý Nhơn", "Xã Tam Thôn Hiệp", "Xã Thạnh An"],
    "Huyện Củ Chi": ["Thị trấn Củ Chi", "Xã An Nhơn Tây", "Xã An Phú", "Xã Bình Mỹ", "Xã Hòa Phú", "Xã Nhuận Đức", "Xã Phạm Văn Cội", "Xã Phú Hòa Đông", "Xã Phú Mỹ Hưng", "Xã Phước Hiệp", "Xã Phước Thạnh", "Xã Phước Vĩnh An", "Xã Tân An Hội", "Xã Tân Định", "Xã Tân Phú Trung", "Xã Tân Thạnh Đông", "Xã Tân Thạnh Tây", "Xã Tân Thông Hội", "Xã Thái Mỹ", "Xã Trung An", "Xã Trung Lập Hạ", "Xã Trung Lập Thượng"],
    "Huyện Hóc Môn": ["Thị trấn Hóc Môn", "Xã Bà Điểm", "Xã Đông Thạnh", "Xã Nhị Bình", "Xã Tân Hiệp", "Xã Tân Thới Nhì", "Xã Tân Xuân", "Xã Thới Tam Thôn", "Xã Trung Chánh", "Xã Xuân Thới Đông", "Xã Xuân Thới Sơn", "Xã Xuân Thới Thượng"],
    "Huyện Nhà Bè": ["Thị trấn Nhà Bè", "Xã Hiệp Phước", "Xã Long Thới", "Xã Nhơn Đức", "Xã Phú Xuân", "Xã Phước Kiển", "Xã Phước Lộc"]
};

// Hàm khởi tạo danh sách Quận/Huyện cho các thẻ select khi trang được tải
function initDistrictSelects() {
    const createDist = document.getElementById('create-district');
    const editDist = document.getElementById('edit-district');
    
    let htmlOptions = '<option value="">Chọn Quận/Huyện</option>';
    for (let key in hcmData) {
        htmlOptions += `<option value="${key}">${key}</option>`;
    }
    
    if(createDist) createDist.innerHTML = htmlOptions;
    if(editDist) editDist.innerHTML = htmlOptions;
}

// Hàm cập nhật danh sách Phường tương ứng dựa theo Quận được chọn
function updateWards(prefix, selectedWard = '') {
    const distSelect = document.getElementById(`${prefix}-district`);
    const wardSelect = document.getElementById(`${prefix}-ward`);
    
    const selectedDist = distSelect.value;
    let htmlOptions = '<option value="">Chọn Phường/Xã</option>';
    
    if (selectedDist && hcmData[selectedDist]) {
        hcmData[selectedDist].forEach(ward => {
            const active = (ward === selectedWard) ? 'selected' : '';
            htmlOptions += `<option value="${ward}" ${active}>${ward}</option>`;
        });
    }
    
    wardSelect.innerHTML = htmlOptions;
}

// Gọi hàm khởi tạo ngay khi tài liệu tải xong
document.addEventListener("DOMContentLoaded", function() {
    initDistrictSelects();
});

function openEditModal(id, userId, code, posId, salary, hireDate, fullName, email, phone, sex, birthday, city, district, ward, detail) {
    document.getElementById('edit-form').action = '/admin/employees/' + id;
    document.getElementById('edit-code').value = code;
    document.getElementById('edit-salary').value = salary;
    document.getElementById('edit-hire').value = hireDate;
    document.getElementById('edit-position').value = posId || '';
    
    // Đổ dữ liệu thông tin tài khoản
    document.getElementById('edit-fullname').value = fullName;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-phone').value = phone;
    document.getElementById('edit-sex').value = sex || '';
    document.getElementById('edit-birthday').value = birthday;

    // Thiết lập giá trị cho ô Thành Phố
    document.getElementById('edit-city').value = city || 'Hồ Chí Minh';
    
    // Đồng bộ Quận/Huyện và Phường/Xã trong form sửa
    const editDistSelect = document.getElementById('edit-district');
    editDistSelect.value = district || '';
    updateWards('edit', ward || '');
    
    document.getElementById('edit-address-detail').value = detail;
    
    openModal('modal-edit');
}

async function openEmployeeDetail(id) {
    const modal = document.getElementById('employee-detail-modal');
    const body  = document.getElementById('employee-detail-body');
    const title = document.getElementById('employee-detail-title');

    title.textContent = 'Chi tiết nhân viên';
    body.innerHTML = '<div style="text-align:center;padding:40px;color:var(--muted);">Đang tải...</div>';
    modal.classList.add('open');

    try {
        const res  = await fetch(`/admin/employees/${id}/detail`);
        const data = await res.json();
        const e    = data.employee;
        const s    = data.orderStats;

        let fullAddress = [e.addressDetail, e.ward, e.district, e.city].filter(Boolean).join(', ') || '—';

        body.innerHTML = `
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;font-size:13px;">
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Họ tên</div>
                    <div style="font-weight:500;">${e.fullName}</div>
                    <div style="color:var(--muted);">${e.email || ''}</div>
                    <div style="color:var(--muted);">${e.phone || ''}</div>
                </div>
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Chức vụ</div>
                    <div style="font-weight:500;">${e.position || '—'}</div>
                    <div style="margin-top:4px;"><span class="badge ${e.IsActive ? 'active' : 'inactive'}">${e.IsActive ? 'Đang làm việc' : 'Nghỉ việc'}</span></div>
                </div>

                <div style="grid-column: span 2; border-top: 1px dashed var(--border); padding-top: 8px;">
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Địa chỉ cư trú</div>
                    <div style="font-weight:500; color:var(--ink);">${fullAddress}</div>
                </div>

                <div style="border-top: 1px dashed var(--border); padding-top: 8px;">
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Lương</div>
                    <div style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:600;color:var(--gold);">${Number(e.salary).toLocaleString('vi-VN')}đ</div>
                </div>
                <div style="border-top: 1px dashed var(--border); padding-top: 8px;">
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Ngày vào làm</div>
                    <div>${e.hireDate || '—'}</div>
                </div>
                ${e.sex || e.birthday ? `
                <div style="grid-column: span 2;">
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Cá nhân</div>
                    <div>${[e.sex, e.birthday].filter(Boolean).join(' · ')}</div>
                </div>` : ''}
            </div>

            <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;">
                <div style="padding:12px 16px;background:var(--cream);border-bottom:1px solid var(--border);font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">
                    Thống kê xử lý đơn hàng
                </div>
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0;">
                    ${[
                        ['Tổng đơn', s.total, 'var(--ink)'],
                        ['Hoàn thành', s.completed, '#27ae60'],
                        ['Đã hủy', s.cancelled, '#c0392b'],
                        ['Chờ xử lý', s.pending, '#c9a84c'],
                    ].map(([lbl, val, color]) => `
                        <div style="padding:16px;text-align:center;border-right:1px solid var(--border);">
                            <div style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:${color};">${val}</div>
                            <div style="font-size:11px;color:var(--muted);margin-top:4px;">${lbl}</div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    } catch(e) {
        body.innerHTML = '<div style="text-align:center;padding:40px;color:#c0392b;">Không thể tải dữ liệu.</div>';
    }
}
</script>
@endpush

{{-- Modal chi tiết nhân viên --}}
<div class="modal-overlay" id="employee-detail-modal">
    <div class="modal" style="max-width:620px;width:100%;">
        <div class="modal-head">
            <h3 id="employee-detail-title">Chi tiết nhân viên</h3>
            <button class="modal-close" onclick="closeModal('employee-detail-modal')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="modal-body" id="employee-detail-body" style="max-height:70vh;overflow-y:auto;"></div>
    </div>
</div>