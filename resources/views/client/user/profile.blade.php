{{-- resources/views/client/user/show.blade.php --}}
@extends('layouts.client')

@section('title', 'Hồ Sơ Cá Nhân')

@push('styles')
    @vite(['resources/css/client/user/profile.css'])
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