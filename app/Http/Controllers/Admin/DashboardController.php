<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Manufacturer;
use App\Models\PageView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts      = Product::count();
        $totalOrders        = Order::count();
        $totalUsers         = User::where('role', 'Customer')->count();
        $totalManufacturers = Manufacturer::count();

        $recentOrders   = Order::with('user')->latest()->take(6)->get();
        $recentProducts = Product::with('category')->latest('created_at')->take(6)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalManufacturers',
            'recentOrders',
            'recentProducts'
        ));
    }
    // ── API: trả về dữ liệu biểu đồ lượt truy cập ───────────────────────────────
    public function traffic(Request $request)
    {
        $period = $request->input('period', 'month');
        $now    = Carbon::now();

        [$labels,, $from, $to, $groupFormat, $dateFormat] =
            $this->buildSeries($period, $request, $now);

        $baseQuery = fn() => PageView::whereBetween('created_at', [
            $from->toDateTimeString(),
            $to->copy()->endOfDay()->toDateTimeString(),
        ]);

        if ($period === 'quarter') {
            // Tổng lượt xem theo quý
            $rows = $baseQuery()
                ->select(
                    DB::raw('YEAR(created_at) as y'),
                    DB::raw('QUARTER(created_at) as q'),
                    DB::raw('COUNT(*) as total_views'),
                    DB::raw('COUNT(DISTINCT session_id) as unique_sessions'),
                    DB::raw('COUNT(DISTINCT IF(userID IS NOT NULL, userID, NULL)) as members'),
                    DB::raw('COUNT(DISTINCT IF(userID IS NULL, session_id, NULL)) as guests')
                )
                ->groupBy('y', 'q')
                ->get()
                ->keyBy(fn($r) => "Q{$r->q}/{$r->y}");

            $views = $uniqueSessions = $members = $guests = [];
            foreach ($labels as $label) {
                $r = $rows[$label] ?? null;
                $views[]          = (int) ($r->total_views      ?? 0);
                $uniqueSessions[] = (int) ($r->unique_sessions  ?? 0);
                $members[]        = (int) ($r->members          ?? 0);
                $guests[]         = (int) ($r->guests           ?? 0);
            }
        } else {
            $rows = $baseQuery()
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '$dateFormat') as period"),
                    DB::raw('COUNT(*) as total_views'),
                    DB::raw('COUNT(DISTINCT session_id) as unique_sessions'),
                    DB::raw('COUNT(DISTINCT IF(userID IS NOT NULL, userID, NULL)) as members'),
                    DB::raw('COUNT(DISTINCT IF(userID IS NULL, session_id, NULL)) as guests')
                )
                ->groupBy('period')
                ->get()
                ->keyBy('period');

            $views = $uniqueSessions = $members = $guests = [];
            foreach ($labels as $i => $label) {
                $key = $groupFormat[$i];
                $r   = $rows[$key] ?? null;
                $views[]          = (int) ($r->total_views      ?? 0);
                $uniqueSessions[] = (int) ($r->unique_sessions  ?? 0);
                $members[]        = (int) ($r->members          ?? 0);
                $guests[]         = (int) ($r->guests           ?? 0);
            }
        }

        // Thống kê tổng
        $summary = $baseQuery()->selectRaw(
            'COUNT(*) as total_views,
             COUNT(DISTINCT session_id) as unique_sessions,
             COUNT(DISTINCT IF(userID IS NOT NULL, userID, NULL)) as members,
             COUNT(DISTINCT IF(userID IS NULL, session_id, NULL)) as guests'
        )->first();

        // Top 5 trang được truy cập nhiều nhất
        $topPages = $baseQuery()
            ->select('path', DB::raw('COUNT(*) as cnt'))
            ->groupBy('path')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        $topProductView = $baseQuery()
            ->select('path', DB::raw('COUNT(*) as cnt'))
            ->where('path', 'LIKE', '/chi-tiet/%')
            ->groupBy('path')
            ->orderByDesc('cnt')
            ->first();
        $topProduct = null;
        if ($topProductView) {
            $productId  = (int) last(explode('/', $topProductView->path));
            $topProduct = \App\Models\Product::with('coverImage')
                ->find($productId);
            if ($topProduct) {
                $topProduct->view_count = $topProductView->cnt;
            }
        }
        return response()->json([
            'labels'         => $labels,
            'views'          => $views,
            'uniqueSessions' => $uniqueSessions,
            'members'        => $members,
            'guests'         => $guests,
            'summary'        => [
                'totalViews'     => (int) $summary->total_views,
                'uniqueSessions' => (int) $summary->unique_sessions,
                'members'        => (int) $summary->members,
                'guests'         => (int) $summary->guests,
            ],
            'topPages'       => $topPages,
            'topProduct' => $topProduct ? [
                'productID'   => $topProduct->productID,
                'productName' => $topProduct->productName,
                'basePrice'   => $topProduct->basePrice,
                'view_count'  => $topProduct->view_count,
                'imageURL'    => $topProduct->coverImage
                    ? asset('storage/' . $topProduct->coverImage->imageURL)
                    : null,
            ] : null,
        ]);
    }
    // ── API: trả về dữ liệu biểu đồ doanh thu ────────────────────────────────
    public function revenue(Request $request)
    {
        $period = $request->input('period', 'month');
        $now    = Carbon::now();

        [$labels, $data, $from, $to, $groupFormat, $dateFormat] =
            $this->buildSeries($period, $request, $now);

        if ($period === 'quarter') {
            $quarterRows = Order::where('status', 'Completed')
                ->whereBetween('orderDate', [$from->toDateString(), $to->toDateString()])
                ->select(
                    DB::raw('YEAR(orderDate) as y'),
                    DB::raw('QUARTER(orderDate) as q'),
                    DB::raw('SUM(finalAmount) as revenue'),
                    DB::raw('COUNT(*) as orders')
                )
                ->groupBy('y', 'q')
                ->get()
                ->keyBy(fn($r) => "Q{$r->q}/{$r->y}");

            $revenues = [];
            $orderCounts = [];
            foreach ($labels as $label) {
                $revenues[]    = (float) ($quarterRows[$label]->revenue ?? 0);
                $orderCounts[] = (int)   ($quarterRows[$label]->orders  ?? 0);
            }
        } else {
            $rows = Order::where('status', 'Completed')
                ->whereBetween('orderDate', [$from->toDateString(), $to->toDateString()])
                ->select(
                    DB::raw("DATE_FORMAT(orderDate, '$dateFormat') as period"),
                    DB::raw('SUM(finalAmount) as revenue'),
                    DB::raw('COUNT(*) as orders')
                )
                ->groupBy('period')
                ->get()
                ->keyBy('period');

            $revenues = [];
            $orderCounts = [];
            foreach ($labels as $i => $label) {
                $key = $groupFormat[$i];
                $revenues[]    = (float) ($rows[$key]->revenue ?? 0);
                $orderCounts[] = (int)   ($rows[$key]->orders  ?? 0);
            }
        }

        $total      = array_sum($revenues);
        $totalOrders = array_sum($orderCounts);
        $avg        = $totalOrders > 0 ? $total / $totalOrders : 0;

        return response()->json([
            'labels'      => $labels,
            'revenues'    => $revenues,
            'orders'      => $orderCounts,
            'total'       => $total,
            'totalOrders' => $totalOrders,
            'avg'         => $avg,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    private function buildSeries(string $period, Request $request, Carbon $now): array
    {
        switch ($period) {
            case 'day': // 30 ngày gần nhất
                $from  = $now->copy()->subDays(29)->startOfDay();
                $to    = $now->copy()->endOfDay();
                $labels = [];
                $groupFormat = [];
                for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                    $labels[]      = $d->format('d/m');
                    $groupFormat[] = $d->format('d/m/Y');
                }
                return [$labels, [], $from, $to, $groupFormat, '%d/%m/%Y'];

            case 'week': // 2 tuần gần nhất
                $from  = $now->copy()->subWeeks(1)->startOfWeek();
                $to    = $now->copy()->endOfWeek();
                $labels = [];
                $groupFormat = [];
                for ($w = $from->copy(); $w->lte($to); $w->addWeek()) {
                    $labels[]      = 'T' . $w->week . '/' . $w->year;
                    $groupFormat[] = $w->format('W/Y'); // ISO week
                }
                return [$labels, [], $from, $to, $groupFormat, '%V/%Y'];

            case 'month': // 12 tháng gần nhất
                $from  = $now->copy()->subMonths(11)->startOfMonth();
                $to    = $now->copy()->endOfMonth();
                $labels = [];
                $groupFormat = [];
                for ($m = $from->copy(); $m->lte($to); $m->addMonth()) {
                    $labels[]      = $m->format('m/Y');
                    $groupFormat[] = $m->format('m/Y');
                }
                return [$labels, [], $from, $to, $groupFormat, '%m/%Y'];

            case 'quarter': // 8 quý gần nhất
                $from  = $now->copy()->subQuarters(7)->startOfQuarter();
                $to    = $now->copy()->endOfQuarter();
                $labels = [];
                $groupFormat = [];
                for ($q = $from->copy(); $q->lte($to); $q->addQuarter()) {
                    $qNum = ceil($q->month / 3);
                    $labels[]      = "Q{$qNum}/{$q->year}";
                    $groupFormat[] = "Q{$qNum}/{$q->year}";
                }
                return [$labels, [], $from, $to, $groupFormat, null]; // xử lý đặc biệt bên dưới

            case 'year': // 5 năm gần nhất
                $from  = $now->copy()->subYears(4)->startOfYear();
                $to    = $now->copy()->endOfYear();
                $labels = [];
                $groupFormat = [];
                for ($y = $from->copy(); $y->lte($to); $y->addYear()) {
                    $labels[]      = (string) $y->year;
                    $groupFormat[] = (string) $y->year;
                }
                return [$labels, [], $from, $to, $groupFormat, '%Y'];

            case 'range':
            default:
                $from = Carbon::parse($request->input('from', $now->copy()->startOfMonth()->toDateString()));
                $to   = Carbon::parse($request->input('to',   $now->toDateString()));
                // Chọn granularity tự động theo khoảng
                $diff = $from->diffInDays($to);
                if ($diff <= 31) {
                    $labels = $groupFormat = [];
                    for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                        $labels[]      = $d->format('d/m');
                        $groupFormat[] = $d->format('d/m/Y');
                    }
                    return [$labels, [], $from, $to, $groupFormat, '%d/%m/%Y'];
                } elseif ($diff <= 92) {
                    $labels = $groupFormat = [];
                    for ($w = $from->copy()->startOfWeek(); $w->lte($to); $w->addWeek()) {
                        $labels[]      = 'T' . $w->week . '/' . $w->year;
                        $groupFormat[] = $w->format('W/Y');
                    }
                    return [$labels, [], $from, $to, $groupFormat, '%V/%Y'];
                } else {
                    $labels = $groupFormat = [];
                    for ($m = $from->copy()->startOfMonth(); $m->lte($to); $m->addMonth()) {
                        $labels[]      = $m->format('m/Y');
                        $groupFormat[] = $m->format('m/Y');
                    }
                    return [$labels, [], $from, $to, $groupFormat, '%m/%Y'];
                }
        }
    }

    // Quarter cần xử lý riêng vì MySQL không có format quý
    public function revenueQuarter(Carbon $from, Carbon $to): array
    {
        $rows = Order::where('status', 'Completed')
            ->whereBetween('orderDate', [$from->toDateString(), $to->toDateString()])
            ->select(
                DB::raw('YEAR(orderDate) as y'),
                DB::raw('QUARTER(orderDate) as q'),
                DB::raw('SUM(finalAmount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('y', 'q')
            ->get()
            ->keyBy(fn($r) => "Q{$r->q}/{$r->y}");

        return $rows->toArray();
    }
}
