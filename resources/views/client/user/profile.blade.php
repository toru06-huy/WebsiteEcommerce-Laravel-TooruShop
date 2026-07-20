{{-- resources/views/client/user/show.blade.php --}}
@extends('layouts.client')

@section('title', 'Hồ Sơ Cá Nhân')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --cream:   #faf8f5;
        --sand:    #ede9e2;
        --stone:   #c9c3b8;
        --charcoal:#2b2b2b;
        --ink:     #1a1a1a;
        --sage:    #7a9e87;
        --sage-light: #eef3f0;
        --error:   #c0392b;
        --radius:  10px;
    }

    body { background: var(--cream); color: var(--charcoal); font-family: 'DM Sans', sans-serif; }

    /* ── Page Layout ── */
    .profile-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 56px 24px 80px;
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 40px;
        align-items: start;
    }

    /* ── Sidebar ── */
    .sidebar {
        position: sticky;
        top: 80px;
        background: #fff;
        border: 1px solid var(--sand);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .sidebar-avatar {
        background: var(--sand);
        padding: 36px 24px;
        text-align: center;
        border-bottom: 1px solid var(--sand);
    }

    .avatar-circle {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: var(--sage);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        color: #fff;
        margin: 0 auto 12px;
        letter-spacing: 1px;
    }

    .sidebar-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--ink);
    }

    .sidebar-role {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--stone);
        margin-top: 4px;
    }

    .sidebar-nav { padding: 12px 0; }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        font-size: 0.875rem;
        color: var(--charcoal);
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
        font-weight: 400;
    }

    .sidebar-nav a:hover,
    .sidebar-nav a.active {
        background: var(--sage-light);
        color: var(--sage);
    }

    .sidebar-nav a svg { opacity: 0.6; flex-shrink: 0; }

    /* ── Main Content ── */
    .main-content { display: flex; flex-direction: column; gap: 28px; }

    /* ── Card ── */
    .card {
        background: #fff;
        border: 1px solid var(--sand);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .card-header {
        padding: 22px 28px 18px;
        border-bottom: 1px solid var(--sand);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--ink);
        letter-spacing: 0.3px;
    }

    .card-subtitle {
        font-size: 0.8rem;
        color: var(--stone);
        margin-top: 2px;
    }

    .card-body { padding: 28px; }

    /* ── Form ── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-grid .span-2 { grid-column: span 2; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }

    .form-label {
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: var(--stone);
    }

    .form-control {
        padding: 11px 14px;
        border: 1.5px solid var(--sand);
        border-radius: 7px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        color: var(--ink);
        background: var(--cream);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--sage);
        box-shadow: 0 0 0 3px rgba(122,158,135,0.12);
        background: #fff;
    }

    .form-control.is-invalid { border-color: var(--error); }

    .invalid-feedback {
        font-size: 0.78rem;
        color: var(--error);
        margin-top: 2px;
    }

    select.form-control { cursor: pointer; }

    /* ── Buttons ── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 11px 24px;
        border-radius: 7px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--sage);
        color: #fff;
    }

    .btn-primary:hover { background: #6a8e77; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(122,158,135,0.3); }

    .btn-outline {
        background: transparent;
        color: var(--charcoal);
        border: 1.5px solid var(--sand);
    }

    .btn-outline:hover { border-color: var(--stone); background: var(--cream); }

    .card-actions {
        padding: 20px 28px;
        border-top: 1px solid var(--sand);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    /* ── Alert ── */
    .alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 18px;
        border-radius: 8px;
        font-size: 0.875rem;
        margin-bottom: 20px;
    }

    .alert-success { background: var(--sage-light); color: #4a7a5a; border: 1px solid #b8d4c0; }
    .alert-error   { background: #fdf2f2; color: var(--error); border: 1px solid #f1b8b8; }

    /* ── Divider ── */
    .section-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 4px 0 20px;
    }

    .section-divider span {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--stone);
        white-space: nowrap;
    }

    .section-divider::before,
    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--sand);
    }

    /* ── Address Grid ── */
    .address-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
    }

    /* ── Membership Tier ── */
    .tier-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 8px;
    }
    .tier-bronze  { background: #f0e6d9; color: #8b5e3c; }
    .tier-silver  { background: #eaecef; color: #5a6472; }
    .tier-gold    { background: #fdf3d0; color: #a07c10; }
    .tier-platinum{ background: #e8f0f7; color: #2c5f8a; }

    .tier-card {
        background: #fff;
        border: 1px solid var(--sand);
        border-radius: var(--radius);
        overflow: hidden;
    }
    .tier-progress-wrap {
        padding: 24px 28px;
    }
    .tier-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .tier-spent {
        font-size: 0.85rem;
        color: var(--stone);
    }
    .tier-bar-bg {
        height: 6px;
        background: var(--sand);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 16px;
    }
    .tier-bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.6s ease;
    }
    .fill-bronze   { background: #cd7f32; }
    .fill-silver   { background: #9ba4af; }
    .fill-gold     { background: #d4a017; }
    .fill-platinum { background: #4a90c4; }

    .tier-milestones {
        display: flex;
        justify-content: space-between;
        font-size: 0.72rem;
        color: var(--stone);
        letter-spacing: 0.5px;
    }
    .tier-milestones span.reached { color: var(--sage); font-weight: 600; }
    @media (max-width: 768px) {
        .profile-page { grid-template-columns: 1fr; padding: 24px 16px; }
        .sidebar { position: static; }
        .form-grid { grid-template-columns: 1fr; }
        .form-grid .span-2 { grid-column: span 1; }
        .address-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 480px) {
        .address-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="profile-page">

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">
        <div class="sidebar-avatar">
            <div class="avatar-circle">
                {{ mb_strtoupper(mb_substr($user->fullName, 0, 1)) }}
            </div>
            <div class="sidebar-name">{{ $user->fullName }}</div>
            <div class="sidebar-role">{{ $user->role }}</div>
            @if($membership)
            @php $tier = $membership->tier; @endphp
            <div class="tier-badge tier-{{ strtolower($tier) }}">
                @if($tier === 'Bronze')   🥉
                @elseif($tier === 'Silver') 🥈
                @elseif($tier === 'Gold')   🥇
                @else 💎
                @endif
                {{ $tier }}
            </div>
            @endif
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('client.profile', $user->userID) }}" class="active">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Hồ sơ cá nhân
            </a>
            <a href="{{ route('client.profile.orders', $user->userID) }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Đơn hàng của tôi
            </a>
            <a href="{{ route('client.profile.vouchers', $user->userID) }}"
               class="{{ request()->routeIs('client.profile.vouchers') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.59 13.41L11 4H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
                Mã giảm giá của tôi
            </a>
            <a href="{{ route('client.wishlist.index') }}" class="{{ request()->routeIs('client.wishlist.index') ? 'active' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                Sản phẩm yêu thích
            </a>
        </nav>
    </aside>

    {{-- ── Main ── --}}
    <div class="main-content">

        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ── Hạng thành viên ── --}}
        @if($membership)
        @php
            $tier       = $membership->tier;
            $spent      = (float) $membership->totalSpent;
            $tierClass  = strtolower($tier);
            $milestones = [0, 1_000_000, 5_000_000, 20_000_000];
            $labels     = ['0đ', '1tr', '5tr', '20tr'];
            $tierNames  = ['Bronze', 'Silver', 'Gold', 'Platinum'];

            // Tìm khoảng tier hiện tại để tính %
            $currentIdx = array_search($tier, $tierNames);
            $nextIdx    = min($currentIdx + 1, 3);
            $low        = $milestones[$currentIdx];
            $high       = $milestones[$nextIdx];
            $pct        = $currentIdx === 3 ? 100 : min(100, ($spent - $low) / max(1, $high - $low) * 100);
            $remaining  = $currentIdx === 3 ? 0 : max(0, $high - $spent);
        @endphp
        <div class="tier-card">
            <div class="card-header">
                <div>
                    <div class="card-title">Hạng thành viên</div>
                    <div class="card-subtitle">Mua sắm nhiều hơn để nâng hạng và nhận ưu đãi</div>
                </div>
                <span class="tier-badge tier-{{ $tierClass }}">
                    @if($tier === 'Bronze') 🥉
                    @elseif($tier === 'Silver') 🥈
                    @elseif($tier === 'Gold') 🥇
                    @else 💎
                    @endif
                    {{ $tier }}
                </span>
            </div>

            <div class="tier-progress-wrap">
                <div class="tier-row">
                    <span style="font-size:0.85rem;color:var(--charcoal);font-weight:500;">
                        Tổng chi tiêu: <strong>{{ number_format($spent, 0, ',', '.') }}đ</strong>
                    </span>
                    @if($currentIdx < 3)
                    <span class="tier-spent">
                        Còn {{ number_format($remaining, 0, ',', '.') }}đ để lên <strong>{{ $tierNames[$nextIdx] }}</strong>
                    </span>
                    @else
                    <span class="tier-spent" style="color:var(--sage);font-weight:600;">Hạng cao nhất 🎉</span>
                    @endif
                </div>

                <div class="tier-bar-bg">
                    <div class="tier-bar-fill fill-{{ $tierClass }}" style="width: {{ $pct }}%"></div>
                </div>

                <div class="tier-milestones">
                    @foreach($labels as $i => $label)
                    <span class="{{ $spent >= $milestones[$i] ? 'reached' : '' }}">{{ $label }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Đặc quyền theo hạng --}}
            <div style="padding: 0 28px 24px; display:grid; grid-template-columns:repeat(4,1fr); gap:12px; text-align:center;">
                @foreach([
                    ['Bronze',   '🥉', 'Miễn phí ship đơn > 500k'],
                    ['Silver',   '🥈', 'Giảm thêm 3% mỗi đơn'],
                    ['Gold',     '🥇', 'Giảm thêm 5% + freeship trên mọi đơn hàng'],
                    ['Platinum', '💎', 'Giảm 10% + hỗ trợ ưu tiên'],
                ] as [$t, $icon, $perk])
                <div style="padding:14px 8px; border-radius:8px; background: {{ $tier === $t ? 'var(--sage-light)' : 'var(--cream)' }}; border: 1px solid {{ $tier === $t ? '#b8d4c0' : 'var(--sand)' }}; opacity: {{ array_search($t, $tierNames) > $currentIdx ? '0.45' : '1' }};">
                    <div style="font-size:1.4rem; margin-bottom:6px;">{{ $icon }}</div>
                    <div style="font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; color:var(--charcoal); margin-bottom:4px;">{{ $t }}</div>
                    <div style="font-size:0.72rem; color:var(--stone); line-height:1.4;">{{ $perk }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Thông tin cá nhân ── --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Thông tin cá nhân</div>
                    <div class="card-subtitle">Cập nhật thông tin cá nhân</div>
                </div>
            </div>

            <form action="{{ route('client.profile.update', $user->userID) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group span-2">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->fullName) }}" placeholder="Nguyễn Văn A">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" placeholder="example@email.com" readonly>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}" placeholder="0901 234 567">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Giới tính</label>
                            <select name="sex" class="form-control">
                                <option value="">-- Chọn --</option>
                                <option value="Nam"    {{ old('sex', $user->sex) == 'Nam'    ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ"     {{ old('sex', $user->sex) == 'Nữ'     ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác"   {{ old('sex', $user->sex) == 'Khác'   ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="birthday" class="form-control"
                                   value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}">
                        </div>
                    </div>

                    {{-- ── Địa chỉ ── --}}
                    <div class="section-divider" style="margin-top:28px;">
                        <span>Địa chỉ</span>
                    </div>

                    @php $addr = $addresses->first(); @endphp

                    <div class="address-grid">
                        <div class="form-group">
                            <label class="form-label">Tỉnh / Thành phố</label>
                            <input type="text" name="city" class="form-control"
                                   value="{{ old('city', $addr->city ?? '') }}" placeholder="TP. Hồ Chí Minh">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Quận / Huyện</label>
                            <input type="text" name="district" class="form-control"
                                   value="{{ old('district', $addr->district ?? '') }}" placeholder="Quận 1">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phường / Xã</label>
                            <input type="text" name="ward" class="form-control"
                                   value="{{ old('ward', $addr->ward ?? '') }}" placeholder="Phường Bến Nghé">
                        </div>

                        <div class="form-group" style="grid-column: span 3;">
                            <label class="form-label">Địa chỉ chi tiết</label>
                            <input type="text" name="addressDetail" class="form-control"
                                   value="{{ old('addressDetail', $addr->addressDetail ?? '') }}"
                                   placeholder="Số nhà, tên đường, tòa nhà...">
                        </div>
                    </div>
                </div>

                <div class="card-actions">
                    <button type="reset" class="btn btn-outline">Đặt lại</button>
                    <button type="submit" class="btn btn-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Đổi mật khẩu ── --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Đổi mật khẩu</div>
                    <div class="card-subtitle">Để trống nếu không muốn thay đổi</div>
                </div>
            </div>

            <form action="{{ route('client.profile.password', $user->userID) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group span-2">
                            <label class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="••••••••">
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="••••••••">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="card-actions">
                    <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection