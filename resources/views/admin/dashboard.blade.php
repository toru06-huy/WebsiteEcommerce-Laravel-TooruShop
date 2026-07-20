@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'VELOUR / Tổng quan')

@section('content')

<div class="stats-grid">
    <div class="stat-card gold">
        <div class="stat-label">Tổng sản phẩm</div>
        <div class="stat-value">{{ $totalProducts }}</div>
        <div class="stat-sub">Đang kinh doanh</div>
        <div class="stat-icon">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
            </svg>
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-label">Đơn hàng</div>
        <div class="stat-value">{{ $totalOrders }}</div>
        <div class="stat-sub">Tất cả trạng thái</div>
        <div class="stat-icon">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
            </svg>
        </div>
    </div>

    <div class="stat-card blue">
        <div class="stat-label">Người dùng</div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-sub">Đã đăng ký</div>
        <div class="stat-icon">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
        </div>
    </div>

    <div class="stat-card red">
        <div class="stat-label">Nhà cung cấp</div>
        <div class="stat-value">{{ $totalManufacturers }}</div>
        <div class="stat-sub">Đối tác</div>
        <div class="stat-icon">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            </svg>
        </div>
    </div>
</div>

{{-- ── Biểu đồ lưu lượng truy cập ─────────────────────────────────────────── --}}
<div class="table-card" style="margin-bottom:32px;">
    <div class="table-head" style="flex-wrap:wrap;gap:12px;">
        <h2>Lưu lượng truy cập</h2>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <div style="display:flex;gap:4px;" id="traffic-period-btns">
                <button class="period-btn active" data-period="day"    >30 Ngày</button>
                <button class="period-btn"         data-period="week"   >12 Tuần</button>
                <button class="period-btn"         data-period="month"  >12 Tháng</button>
                <button class="period-btn"         data-period="quarter">8 Quý</button>
                <button class="period-btn"         data-period="year"   >5 Năm</button>
            </div>
            <div style="display:flex;align-items:center;gap:6px;margin-left:4px;">
                <input type="date" id="traffic-from" class="form-control" style="padding:5px 10px;font-size:12px;width:138px;">
                <span style="color:var(--muted);font-size:12px;">—</span>
                <input type="date" id="traffic-to" class="form-control" style="padding:5px 10px;font-size:12px;width:138px;">
                <button id="traffic-range-btn" class="topbar-btn" style="padding:6px 14px;font-size:12px;">Xem</button>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:32px;padding:14px 0 20px;border-bottom:1px solid var(--border);margin-bottom:20px;flex-wrap:wrap;margin-left:20px;margin-right:20px;">
        <div><div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:4px;">Tổng lượt xem</div><div id="tv-total" style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:var(--ink);">—</div></div>
        <div><div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:4px;">Phiên duy nhất</div><div id="tv-unique" style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:var(--ink);">—</div></div>
        <div><div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:4px;">Thành viên</div><div id="tv-members" style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:#7a9e87;">—</div></div>
        <div><div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:4px;">Khách vãng lai</div><div id="tv-guests" style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:#c9a84c;">—</div></div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 260px;gap:24px;align-items:start;">
        <div style="position:relative;height:280px;">
            <canvas id="traffic-chart"></canvas>
            <div id="traffic-loading" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.75);font-size:13px;color:var(--muted);">Đang tải...</div>
        </div>
        <div class="top-pages-card" style="margin-bottom:20px;margin-right:20px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:12px;">Top trang xem nhiều nhất</div>
            <div id="top-pages" style="display:flex;flex-direction:column;gap:10px;">
                <div style="color:var(--muted);font-size:13px;">—</div>
            </div>
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin:20px 0 10px;">Sản phẩm được xem nhiều nhất</div>
            <div id="top-product-card">
                <div style="color:var(--muted);font-size:13px;">—</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Biểu đồ doanh thu ─────────────────────────────────────────── --}}
<div class="table-card" style="margin-bottom:32px;">
    <div class="table-head" style="flex-wrap:wrap;gap:12px;">
        <h2>Biểu đồ doanh thu</h2>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <div style="display:flex;gap:4px;" id="period-btns">
                <button class="period-btn active" data-period="day"     >30 Ngày</button>
                <button class="period-btn"         data-period="week"    >12 Tuần</button>
                <button class="period-btn"         data-period="month"   >12 Tháng</button>
                <button class="period-btn"         data-period="quarter" >8 Quý</button>
                <button class="period-btn"         data-period="year"    >5 Năm</button>
            </div>
            <div style="display:flex;align-items:center;gap:6px;margin-left:4px;">
                <input type="date" id="range-from" class="form-control" style="padding:5px 10px;font-size:12px;width:138px;">
                <span style="color:var(--muted);font-size:12px;">—</span>
                <input type="date" id="range-to"   class="form-control" style="padding:5px 10px;font-size:12px;width:138px;">
                <button id="range-btn" class="topbar-btn" style="padding:6px 14px;font-size:12px;">Xem</button>
            </div>
        </div>
    </div>

    <div id="chart-summary" style="display:flex;gap:32px;padding:14px 0 20px;border-bottom:1px solid var(--border);margin-bottom:20px;flex-wrap:wrap;margin-left:20px;margin-right:20px;">
        <div><div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:4px;">Tổng doanh thu</div><div id="sum-revenue" style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:var(--ink);">—</div></div>
        <div><div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:4px;">Số đơn thành công</div><div id="sum-orders" style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:var(--ink);">—</div></div>
        <div><div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:4px;">Giá trị TB / đơn</div><div id="sum-avg" style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:600;color:var(--ink);">—</div></div>
    </div>

    <div style="position:relative;height:320px;">
        <canvas id="revenue-chart"></canvas>
        <div id="chart-loading" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.75);font-size:13px;color:var(--muted);">
            Đang tải...
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

    {{-- Recent Orders --}}
    <div class="table-card">
        <div class="table-head">
            <h2>Đơn hàng gần đây</h2>
            <a href="{{ route('admin.orders.index') }}" class="topbar-btn secondary" style="font-size:11px;padding:6px 12px;">Xem tất cả</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td><span style="font-family:'Cormorant Garamond',serif;font-size:15px;">#{{ $order->orderID }}</span></td>
                    <td class="td-muted">{{ $order->user->fullName ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $order->status === 'Completed' ? 'active' : ($order->status === 'Cancelled' ? 'inactive' : 'customer') }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>{{ number_format($order->totalAmount, 0, ',', '.') }}đ</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:32px;">Chưa có đơn hàng</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Top Products --}}
    <div class="table-card">
        <div class="table-head">
            <h2>Sản phẩm mới nhất</h2>
            <a href="{{ route('admin.products.index') }}" class="topbar-btn secondary" style="font-size:11px;padding:6px 12px;">Xem tất cả</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentProducts as $product)
                <tr>
                    <td>{{ $product->productName }}</td>
                    <td class="td-muted">{{ $product->category->categoryName ?? '—' }}</td>
                    <td>{{ number_format($product->basePrice, 0, ',', '.') }}đ</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--muted);padding:32px;">Chưa có sản phẩm</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

@push('styles')
<style>
.period-btn {
    padding: 6px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    background: #fff;
    color: var(--muted);
    font-family: 'DM Sans', sans-serif;
    transition: all .15s;
}
.period-btn:hover { background: var(--hover); }
.period-btn.active {
    background: var(--ink);
    color: #fff;
    border-color: var(--ink);
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const REVENUE_URL = '{{ route("admin.dashboard.revenue") }}';
const TRAFFIC_URL = '{{ route("admin.dashboard.traffic") }}';

// ── Traffic chart ─────────────────────────────────────────────────────────────
let trafficChart = null;

function initTrafficChart(labels, views, uniqueSessions, members, guests) {
    const ctx = document.getElementById('traffic-chart').getContext('2d');
    if (trafficChart) trafficChart.destroy();

    trafficChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Tổng lượt xem',
                    data: views,
                    borderColor: '#2b2b2b',
                    backgroundColor: 'rgba(43,43,43,.06)',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.35,
                    fill: true,
                    order: 1,
                },
                {
                    label: 'Phiên duy nhất',
                    data: uniqueSessions,
                    borderColor: '#c9a84c',
                    backgroundColor: 'rgba(201,168,76,.08)',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.35,
                    fill: true,
                    order: 2,
                },
                {
                    label: 'Thành viên',
                    data: members,
                    borderColor: '#7a9e87',
                    borderWidth: 1.5,
                    pointRadius: 2,
                    borderDash: [4, 3],
                    tension: 0.35,
                    fill: false,
                    order: 3,
                },
                {
                    label: 'Khách vãng lai',
                    data: guests,
                    borderColor: '#c0392b',
                    borderWidth: 1.5,
                    pointRadius: 2,
                    borderDash: [4, 3],
                    tension: 0.35,
                    fill: false,
                    order: 4,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { font: { family: 'DM Sans', size: 11 }, boxWidth: 12 } },
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, maxRotation: 45 } },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,.05)' },
                    ticks: { font: { size: 11 }, stepSize: 1 },
                },
            }
        }
    });
}

async function loadTrafficChart(params = {}) {
    document.getElementById('traffic-loading').style.display = 'flex';
    const qs  = new URLSearchParams(params).toString();
    const res = await fetch(TRAFFIC_URL + (qs ? '?' + qs : ''));
    const d   = await res.json();

    document.getElementById('tv-total').textContent   = d.summary.totalViews.toLocaleString('vi-VN');
    document.getElementById('tv-unique').textContent  = d.summary.uniqueSessions.toLocaleString('vi-VN');
    document.getElementById('tv-members').textContent = d.summary.members.toLocaleString('vi-VN');
    document.getElementById('tv-guests').textContent  = d.summary.guests.toLocaleString('vi-VN');

    initTrafficChart(d.labels, d.views, d.uniqueSessions, d.members, d.guests);

    // Top pages
    const topPagesEl = document.getElementById('top-pages');
    if (d.topPages.length === 0) {
        topPagesEl.innerHTML = '<div style="color:var(--muted);font-size:13px;">Chưa có dữ liệu</div>';
    } else {
        const max = d.topPages[0].cnt;
        topPagesEl.innerHTML = d.topPages.map(p => `
            <div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                    <span style="color:var(--charcoal);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:170px;" title="${p.path}">${p.path}</span>
                    <span style="color:var(--muted);flex-shrink:0;margin-left:8px;">${p.cnt.toLocaleString('vi-VN')}</span>
                </div>
                <div style="height:4px;background:var(--border);border-radius:2px;">
                    <div style="height:100%;background:#c9a84c;border-radius:2px;width:${Math.round(p.cnt/max*100)}%;"></div>
                </div>
            </div>
        `).join('');
    }

    // Sản phẩm được xem nhiều nhất
    const topProductEl = document.getElementById('top-product-card');
    if (d.topProduct) {
        const p = d.topProduct;
        topProductEl.innerHTML = `
            <div style="display:flex;gap:12px;align-items:center;padding:12px;background:var(--cream);border-radius:8px;border:1px solid var(--border);">
                <div style="width:52px;height:64px;border-radius:6px;overflow:hidden;background:var(--sand);flex-shrink:0;">
                    ${p.imageURL
                        ? `<img src="${p.imageURL}" style="width:100%;height:100%;object-fit:cover;" alt="${p.productName}">`
                        : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:18px;">👗</div>`}
                </div>
                <div style="min-width:0;">
                    <div style="font-size:12px;font-weight:500;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;" title="${p.productName}">
                        ${p.productName}
                    </div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">
                        ${Number(p.basePrice).toLocaleString('vi-VN')}đ
                    </div>
                    <div style="font-size:11px;color:var(--gold);margin-top:4px;font-weight:600;">
                        👁 ${p.view_count.toLocaleString('vi-VN')} lượt xem
                    </div>
                </div>
            </div>
        `;
    } else {
        topProductEl.innerHTML = '<div style="font-size:12px;color:var(--muted);">Chưa có lượt xem sản phẩm</div>';
    }

    document.getElementById('traffic-loading').style.display = 'none';
}

// Traffic period buttons
document.querySelectorAll('#traffic-period-btns .period-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('#traffic-period-btns .period-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        loadTrafficChart({ period: btn.dataset.period });
    });
});

document.getElementById('traffic-range-btn').addEventListener('click', () => {
    document.querySelectorAll('#traffic-period-btns .period-btn').forEach(b => b.classList.remove('active'));
    const from = document.getElementById('traffic-from').value;
    const to   = document.getElementById('traffic-to').value;
    if (from && to) loadTrafficChart({ period: 'range', from, to });
});

// ── Format số ────────────────────────────────────────────────────────────────
function fmtMoney(v) {
    if (v >= 1_000_000_000) return (v / 1_000_000_000).toFixed(1).replace('.0','') + ' tỷ';
    if (v >= 1_000_000)     return (v / 1_000_000).toFixed(1).replace('.0','') + ' tr';
    if (v >= 1_000)         return (v / 1_000).toFixed(0) + 'k';
    return v.toLocaleString('vi-VN') + 'đ';
}
function fmtFull(v) { return Math.round(v).toLocaleString('vi-VN') + 'đ'; }

// ── Chart instance ────────────────────────────────────────────────────────────
let chartInstance = null;

function initChart(labels, revenues, orders) {
    const ctx = document.getElementById('revenue-chart').getContext('2d');
    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Doanh thu',
                    data: revenues,
                    backgroundColor: 'rgba(201,168,76,.75)',
                    borderColor: '#c9a84c',
                    borderWidth: 1.5,
                    borderRadius: 4,
                    yAxisID: 'yRevenue',
                    order: 2,
                },
                {
                    label: 'Số đơn',
                    data: orders,
                    type: 'line',
                    borderColor: '#7a9e87',
                    backgroundColor: 'rgba(122,158,135,.12)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#7a9e87',
                    tension: 0.35,
                    fill: true,
                    yAxisID: 'yOrders',
                    order: 1,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { font: { family: 'DM Sans', size: 12 }, boxWidth: 12 } },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label === 'Doanh thu'
                            ? ' ' + fmtFull(ctx.raw)
                            : ' ' + ctx.raw + ' đơn'
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, maxRotation: 45 } },
                yRevenue: {
                    position: 'left',
                    grid: { color: 'rgba(0,0,0,.05)' },
                    ticks: { font: { size: 11 }, callback: v => fmtMoney(v) },
                    beginAtZero: true,
                },
                yOrders: {
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { font: { size: 11 }, stepSize: 1 },
                    beginAtZero: true,
                }
            }
        }
    });
}

// ── Fetch & render ────────────────────────────────────────────────────────────
async function loadChart(params = {}) {
    document.getElementById('chart-loading').style.display = 'flex';

    const qs = new URLSearchParams(params).toString();
    const res = await fetch(REVENUE_URL + (qs ? '?' + qs : ''));
    const d   = await res.json();

    document.getElementById('sum-revenue').textContent = fmtFull(d.total);
    document.getElementById('sum-orders').textContent  = d.totalOrders + ' đơn';
    document.getElementById('sum-avg').textContent     = d.totalOrders > 0 ? fmtFull(d.avg) : '—';

    initChart(d.labels, d.revenues, d.orders);
    document.getElementById('chart-loading').style.display = 'none';
}

// ── Sự kiện ───────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Set mặc định range inputs = tháng này
    const today = new Date().toISOString().split('T')[0];
    const firstDay = today.slice(0,8) + '01';
    document.getElementById('range-from').value = firstDay;
    document.getElementById('range-to').value   = today;

    // Period buttons
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            loadChart({ period: btn.dataset.period });
        });
    });

    // Range button
    document.getElementById('range-btn').addEventListener('click', () => {
        document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
        const from = document.getElementById('range-from').value;
        const to   = document.getElementById('range-to').value;
        if (from && to) loadChart({ period: 'range', from, to });
    });

    // Load mặc định
    loadChart({ period: 'day' });
    loadTrafficChart({ period: 'day' });
});
</script>
@endpush