<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\UserDiscount;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AbandonedCartCommand extends Command
{
    protected $signature   = 'cart:abandoned';
    protected $description = 'Tạo voucher cho user có giỏ hàng bị bỏ quên > 24 giờ';

    public function handle(): void
    {
        $code = null;
        $threshold = Carbon::now()->subHours(24);

        $userIds = Cart::where('updated_at', '<=', $threshold)
            ->where('abandoned_notified', false)
            ->pluck('userID')
            ->unique()
            ->filter(); 

        $count = 0;

        foreach ($userIds as $userID) {
            $user = User::find($userID);
            if (!$user) continue;

            $hasRecentOrder = $user->orders()
                ->where('created_at', '>=', $threshold)
                ->exists();

            if ($hasRecentOrder) {

                Cart::where('userID', $userID)
                    ->where('abandoned_notified', false)
                    ->update(['abandoned_notified' => true]);
                continue;
            }
            // Tạo mã giảm giá riêng cho user này
            DB::transaction(function () use ($userID, &$code) {
                do {
                    $code = 'BACK-' . strtoupper(Str::random(6));
                } while (Discount::where('discountCode', $code)->exists());

                $discount = Discount::create([
                    'discountCode'  => $code,
                    'discountName'  => 'Quay lại và hoàn thành đơn hàng của bạn!',
                    'discountType'  => 'fixedAmount',
                    'discountValue' => 10000,
                    'discountLimit' => 1,
                    'startDate'     => Carbon::now(),
                    'endDate'       => Carbon::now()->addDays(3), // hiệu lực 3 ngày
                    'minOrderValue' => 0,
                    'isActive'      => true,
                    'isPersonal'    => true,
                ]);

                UserDiscount::create([
                    'userID'     => $userID,
                    'discountID' => $discount->discountID,
                    'isUsed'     => false,
                    'usedAt'     => null,
                ]);

                // Đánh dấu đã xử lý
                Cart::where('userID', $userID)
                    ->where('abandoned_notified', false)
                    ->update(['abandoned_notified' => true]);
            });

            $count++;
            $this->info("✓ Tạo voucher {$code} cho user #{$userID} ({$user->fullName})");
        }

        $this->info("Hoàn thành: đã xử lý {$count} giỏ hàng bỏ quên.");
    }
}
