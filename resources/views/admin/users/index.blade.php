@extends('admin.layout')

@section('title', 'Người dùng')
@section('page-title', 'Người dùng')
@section('breadcrumb', 'VELOUR / Người dùng')

@section('content')

<div class="table-card">
    <div class="table-head">
        <h2>Danh sách người dùng ({{ $users->total() }})</h2>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:8px;">
                <select name="role" class="form-control" style="padding:8px 12px;font-size:13px;">
                    <option value="">Tất cả vai trò</option>
                    <option value="Customer" {{ request('role') === 'Customer' ? 'selected' : '' }}>Khách hàng</option>
                    <option value="Employee" {{ request('role') === 'Employee' ? 'selected' : '' }}>Nhân viên</option>
                    <option value="Admin"    {{ request('role') === 'Admin'    ? 'selected' : '' }}>Admin</option>
                </select>
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" placeholder="Tìm theo tên, email..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="topbar-btn secondary" style="padding:8px 14px;">Lọc</button>
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr onclick="openUserDetail({{ $user->userID }})" style="cursor:pointer;">
                <td class="td-muted">
                    <span
                        style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:600;color:var(--gold);text-decoration:underline;text-underline-offset:3px;">
                        #{{ $user->userID }}
                    </span>
                </td>
                <td>
                    <strong>{{ $user->fullName }}</strong>
                    @if($user->employee)
                        <div style="font-size:11px;color:var(--gold);">{{ $user->employee->employeeCode }}</div>
                    @endif
                </td>
                <td class="td-muted">{{ $user->email ?? '—' }}</td>
                <td class="td-muted">{{ $user->phone ?? '—' }}</td>
                <td>
                    <span class="badge {{ strtolower($user->role) }}">{{ $user->role }}</span>
                </td>
                <td>
                    <span class="badge {{ $user->IsActive ? 'active' : 'inactive' }}">
                        {{ $user->IsActive ? 'Hoạt động' : 'Đã khóa' }}
                    </span>
                </td>
                <td class="td-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                <td onclick="event.stopPropagation()">
                    <div class="action-btns">
                        {{-- Toggle active --}}
                        <form method="POST" action="{{ route('admin.users.toggleActive', $user->userID) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-icon" title="{{ $user->IsActive ? 'Khóa tài khoản' : 'Mở khóa' }}">
                                @if($user->IsActive)
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                @else
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 9.9-1"/>
                                </svg>
                                @endif
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy', $user->userID) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-icon danger" title="Xóa"
                                onclick="confirmDelete(this.closest('form'), '{{ addslashes($user->fullName) }}')">
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
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <h3>Chưa có người dùng</h3>
                        <p>Người dùng sẽ xuất hiện tại đây khi đăng ký.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="pagination-wrap">
        <span>Hiển thị {{ $users->firstItem() }}–{{ $users->lastItem() }} / {{ $users->total() }}</span>
        <div class="pagination">
            @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $users->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
async function openUserDetail(id) {
    const modal = document.getElementById('user-detail-modal');
    const body  = document.getElementById('user-detail-body');
    const title = document.getElementById('user-detail-title');

    title.textContent = 'Chi tiết người dùng #' + id;
    body.innerHTML = '<div style="text-align:center;padding:40px;color:var(--muted);">Đang tải...</div>';
    modal.classList.add('open');

    try {
        const res  = await fetch(`/admin/users/${id}/detail`);
        const data = await res.json();
        const u    = data.user;
        const s    = data.stats;

        const tierColor = { Bronze:'#8b5e3c', Silver:'#5a6472', Gold:'#a07c10', Platinum:'#2c5f8a' };
        const tierBg    = { Bronze:'#f0e6d9', Silver:'#eaecef', Gold:'#fdf3d0', Platinum:'#e8f0f7' };
        const tier      = u.tier || 'Bronze';

        const statusLabel = {
            Pending:'Chờ xử lý', Confirmed:'Đã xác nhận',
            Shipping:'Đang giao', Completed:'Hoàn thành', Cancelled:'Đã hủy'
        };
        const statusClass = {
            Completed:'active', Cancelled:'inactive', Pending:'customer',
            Confirmed:'employee', Shipping:'employee'
        };

        body.innerHTML = `
            {{-- Thông tin cơ bản --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;font-size:13px;">
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Họ tên</div>
                    <div style="font-weight:500;font-size:14px;">${u.fullName}</div>
                    <div style="color:var(--muted);margin-top:2px;">${u.email || '—'}</div>
                    <div style="color:var(--muted);">${u.phone || '—'}</div>
                </div>
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Trạng thái</div>
                    <span class="badge ${u.IsActive ? 'active' : 'inactive'}">${u.IsActive ? 'Đang hoạt động' : 'Đã khóa'}</span>
                    <div style="margin-top:8px;">
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;letter-spacing:1px;background:${tierBg[tier]};color:${tierColor[tier]};">
                            ${{ Bronze:'🥉', Silver:'🥈', Gold:'🥇', Platinum:'💎' }[tier]} ${tier}
                        </span>
                    </div>
                </div>
                ${u.sex || u.birthday ? `
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Cá nhân</div>
                    <div>${[u.sex, u.birthday].filter(Boolean).join(' · ')}</div>
                </div>` : ''}
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Ngày đăng ký</div>
                    <div>${u.createdAt || '—'}</div>
                </div>
                ${u.address ? `
                <div style="grid-column:1/-1;">
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Địa chỉ</div>
                    <div>${u.address}</div>
                </div>` : ''}
            </div>

            {{-- Thống kê mua hàng --}}
            <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;margin-bottom:20px;">
                <div style="padding:12px 16px;background:var(--cream);border-bottom:1px solid var(--border);font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">
                    Thống kê mua hàng
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);">
                    ${[
                        ['Tổng đơn', s.totalOrders, 'var(--ink)'],
                        ['Đơn thành công', s.completedOrders, '#27ae60'],
                        ['Tổng chi tiêu', Number(s.totalSpent).toLocaleString('vi-VN') + 'đ', 'var(--gold)'],
                    ].map(([lbl, val, color]) => `
                        <div style="padding:16px;text-align:center;border-right:1px solid var(--border);">
                            <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:${color};">${val}</div>
                            <div style="font-size:11px;color:var(--muted);margin-top:4px;">${lbl}</div>
                        </div>
                    `).join('')}
                </div>
            </div>

            {{-- Đơn hàng gần đây --}}
            ${data.recentOrders.length > 0 ? `
            <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;">
                <div style="padding:12px 16px;background:var(--cream);border-bottom:1px solid var(--border);font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">
                    Đơn hàng gần đây
                </div>
                ${data.recentOrders.map(o => `
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid var(--sand);font-size:13px;">
                    <span style="font-family:'Cormorant Garamond',serif;font-weight:600;color:var(--gold);">#${o.orderID}</span>
                    <span class="badge ${statusClass[o.status] || 'employee'}">${statusLabel[o.status] || o.status}</span>
                    <span style="color:var(--muted);">${o.orderDate}</span>
                    <span style="font-weight:500;">${Number(o.finalAmount).toLocaleString('vi-VN')}đ</span>
                </div>`).join('')}
            </div>` : ''}
        `;
    } catch(e) {
        body.innerHTML = '<div style="text-align:center;padding:40px;color:#c0392b;">Không thể tải dữ liệu.</div>';
    }
}
</script>
@endpush

{{-- Modal chi tiết người dùng --}}
<div class="modal-overlay" id="user-detail-modal">
    <div class="modal" style="max-width:640px;width:100%;">
        <div class="modal-head">
            <h3 id="user-detail-title">Chi tiết người dùng</h3>
            <button class="modal-close" onclick="closeModal('user-detail-modal')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="modal-body" id="user-detail-body" style="max-height:72vh;overflow-y:auto;"></div>
    </div>
</div>