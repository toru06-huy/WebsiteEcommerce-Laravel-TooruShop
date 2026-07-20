<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\UserDiscount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MinigameController extends Controller
{
    protected const CODE_PREFIX = 'MINIG';

    protected const VALID_DAYS = 7;

    protected const RANDOM_CHARSET = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected const RANDOM_LENGTH = 6;

    protected const SESSION_SPIN_KEY = 'minigame_spin_today';

    public static function segments(): array
    {
        return [
            ['type' => 'fixedAmount',   'value' => 5000,   'label' => 'Giảm 5k',   'color' => '#e7ffa5', 'weight' => 25],
            ['type' => 'fixedAmount',   'value' => 10000,  'label' => 'Giảm 10k',  'color' => '#f371fc', 'weight' => 20],
            ['type' => 'none',    'value' => 0,      'label' => 'Chúc bạn may mắn lần sau', 'color' => '#371dfd', 'weight' => 22],
            ['type' => 'fixedAmount',   'value' => 20000,  'label' => 'Giảm 20k',  'color' => '#01aa0c', 'weight' => 15],
            ['type' => 'percent', 'value' => 10,     'label' => 'Giảm 10%',      'color' => '#a08233', 'weight' => 10],
            ['type' => 'fixedAmount',   'value' => 50000,  'label' => 'Giảm 50k',  'color' => '#b5fcc1', 'weight' => 5],
            ['type' => 'fixedAmount',   'value' => 5000, 'label' => 'Giảm 5k', 'color' => '#f8a5ba', 'weight' => 2],
            ['type' => 'percent', 'value' => 5,      'label' => 'Giảm 5%',       'color' => '#ffc8bd', 'weight' => 1],
        ];
    }

    /** Trang chứa vòng quay */
    public function index()
    {
        $segments = self::segments();

        $alreadyClaimedToday = false;
        $pendingResult = null;   // kết quả trúng thưởng hôm nay, chưa claim
        $spunNoWinToday = false; // đã quay hôm nay nhưng không trúng

        if (Auth::check()) {
            $alreadyClaimedToday = $this->hasClaimedToday(Auth::id());

            if (!$alreadyClaimedToday) {
                $spinToday = session('minigame_spin_today');

                if ($spinToday && ($spinToday['date'] ?? null) === now()->toDateString()) {
                    if (!empty($spinToday['won']) && !empty($spinToday['code'])) {
                        $pendingResult = $spinToday;
                    } else {
                        $spunNoWinToday = true;
                    }
                }
            }
        }

        return view('client.minigame.index', compact(
            'segments',
            'alreadyClaimedToday',
            'pendingResult',
            'spunNoWinToday'
        ));
    }

    public function spin(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'requireLogin' => true,
                'message' => 'Bạn cần đăng nhập để tham gia vòng quay may mắn.',
                'loginUrl' => route('client.login'),
            ], 401);
        }

        if ($this->hasClaimedToday(Auth::id())) {
            return response()->json([
                'success' => false,
                'alreadyClaimed' => true,
                'message' => 'Bạn đã nhận mã giảm giá từ vòng quay hôm nay rồi. Hãy quay lại vào ngày mai nhé!',
            ]);
        }

        $today = now()->toDateString();
        $spinToday = session('minigame_spin_today');

        // Đã quay trong ngày hôm nay (kể cả trúng hay không) -> trả lại đúng kết quả cũ,
        // KHÔNG random lại. Chặn việc F5 để quay tiếp.
        if ($spinToday && ($spinToday['date'] ?? null) === $today) {
            return response()->json([
                'success' => true,
                'index' => $spinToday['index'],
                'won' => $spinToday['won'],
                'code' => $spinToday['code'] ?? null,
                'label' => $spinToday['label'],
                'resumed' => true,
            ]);
        }

        $segments = self::segments();
        $index = $this->pickWeightedIndex($segments);
        $segment = $segments[$index];

        if ($segment['type'] === 'none') {
            session(['minigame_spin_today' => [
                'date' => $today,
                'index' => $index,
                'won' => false,
                'code' => null,
                'type' => 'none',
                'value' => 0,
                'label' => $segment['label'],
            ]]);

            return response()->json([
                'success' => true,
                'index' => $index,
                'won' => false,
                'label' => $segment['label'],
            ]);
        }

        $code = $this->generateUniqueCode($segment);

        session(['minigame_spin_today' => [
            'date' => $today,
            'index' => $index,
            'won' => true,
            'code' => $code,
            'type' => $segment['type'],
            'value' => $segment['value'],
            'label' => $segment['label'],
            'generatedAt' => now()->toDateTimeString(),
        ]]);

        return response()->json([
            'success' => true,
            'index' => $index,
            'won' => true,
            'code' => $code,
            'label' => $segment['label'],
        ]);
    }

    /** AJAX: xử lý bấm nút "Lấy mã" — ghi mã đã quay trúng vào DB cho user hiện tại */
    public function claim(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'requireLogin' => true,
                'message' => 'Bạn cần đăng nhập để nhận mã giảm giá.',
                'loginUrl' => route('client.login'),
            ], 401);
        }

        $pending = session('minigame_spin_today');

        if (
            !$pending || empty($pending['won']) || empty($pending['code'])
            || ($pending['date'] ?? null) !== now()->toDateString()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Không có mã nào để nhận. Vui lòng quay lại vòng quay trước nhé!',
            ], 422);
        }

        $userID = Auth::id();

        if ($this->hasClaimedToday($userID)) {
            session()->forget('minigame_spin_today');

            return response()->json([
                'success' => false,
                'alreadyClaimed' => true,
                'message' => 'Bạn đã nhận mã giảm giá từ vòng quay hôm nay rồi. Hãy quay lại vào ngày mai nhé!',
            ]);
        }

        $discount = Discount::create([
            'discountCode' => $pending['code'],
            'discountName' => 'Quà tặng Vòng quay may mắn - ' . $pending['label'],
            'discountType' => $pending['type'] === 'percent' ? 'percentage' : 'fixedAmount',
            'discountValue' => $pending['value'],
            'discountLimit' => 1,
            'startDate' => now(),
            'endDate' => now()->addDays(self::VALID_DAYS),
            'minOrderValue' => 0,
            'isActive' => true,
            'isPersonal' => true,
        ]);

        UserDiscount::create([
            'userID' => $userID,
            'discountID' => $discount->discountID,
            'isUsed' => false,
        ]);

        session()->forget('minigame_spin_today');

        return response()->json([
            'success' => true,
            'message' => 'Chúc mừng! Bạn đã nhận mã "' . $pending['code'] . '" thành công.',
            'code' => $pending['code'],
            'redirect' => route('client.profile.vouchers', ['userID' => $userID]),
        ]);
    }
    
    protected function getTodaySpin(): ?array
    {
        $spinToday = session(self::SESSION_SPIN_KEY);
 
        if (!$spinToday) {
            return null;
        }
 
        $sameDay  = ($spinToday['date'] ?? null) === now()->toDateString();
        $sameUser = ($spinToday['userID'] ?? null) === Auth::id();
 
        if (!$sameDay || !$sameUser) {
            session()->forget(self::SESSION_SPIN_KEY);
            return null;
        }
 
        return $spinToday;
    }
 
    /** Lưu kết quả lượt quay hôm nay vào session, gắn kèm ngày hiện tại và userID */
    protected function saveTodaySpin(array $data): void
    {
        session([self::SESSION_SPIN_KEY => array_merge($data, [
            'date' => now()->toDateString(),
            'userID' => Auth::id(),
        ])]);
    }
    
    /** Chọn ngẫu nhiên 1 index của segments() theo trọng số (weight) */
    protected function pickWeightedIndex(array $segments): int
    {
        $totalWeight = array_sum(array_column($segments, 'weight'));
        $random = random_int(1, max(1, $totalWeight));

        $cumulative = 0;
        foreach ($segments as $index => $segment) {
            $cumulative += $segment['weight'];
            if ($random <= $cumulative) {
                return $index;
            }
        }

        return array_key_last($segments);
    }
    
    protected function hasClaimedToday(int $userID): bool
    {
        return UserDiscount::where('userID', $userID)
            ->whereHas('discount', function ($q) {
                $q->where('discountCode', 'like', self::CODE_PREFIX . '-%')
                    ->whereDate('startDate', now()->toDateString());
            })
            ->exists();
    }

    /** Định dạng giá trị hiển thị trong mã, ví dụ 5000 -> "5k", 100000 -> "100k" */
    protected function formatValueToken(array $segment): string
    {
        if ($segment['type'] === 'percent') {
            return $segment['value'] . 'PT'; // Percent Token, ví dụ 10% -> "10PT"
        }

        if ($segment['value'] > 0 && $segment['value'] % 1000 === 0) {
            return ($segment['value'] / 1000) . 'k';
        }

        return (string) $segment['value'];
    }

    /** Sinh chuỗi ngẫu nhiên độ dài RANDOM_LENGTH từ RANDOM_CHARSET */
    protected function randomToken(): string
    {
        $charset = self::RANDOM_CHARSET;
        $max = strlen($charset) - 1;
        $token = '';
        for ($i = 0; $i < self::RANDOM_LENGTH; $i++) {
            $token .= $charset[random_int(0, $max)];
        }
        return $token;
    }

    /** Sinh mã minigame duy nhất (đảm bảo không trùng trong bảng discounts) */
    protected function generateUniqueCode(array $segment): string
    {
        $valueToken = $this->formatValueToken($segment);

        do {
            $code = self::CODE_PREFIX . '-' . $valueToken . '-' . $this->randomToken();
        } while (Discount::where('discountCode', $code)->exists());

        return $code;
    }
}
