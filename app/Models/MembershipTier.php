<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipTier extends Model
{
    protected $primaryKey = 'membershipID';

    protected $fillable = ['userID', 'tier', 'totalSpent'];

    protected $casts = [
        'totalSpent' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    /**
     * Tính lại tier dựa trên tổng chi tiêu.
     * Bronze  < 1.000.000đ
     * Silver  >= 1.000.000đ
     * Gold    >= 5.000.000đ
     * Platinum>= 20.000.000đ
     */
    public static function calcTier(float $totalSpent): string
    {
        return match (true) {
            $totalSpent >= 20_000_000 => 'Platinum',
            $totalSpent >= 5_000_000  => 'Gold',
            $totalSpent >= 1_000_000  => 'Silver',
            default                   => 'Bronze',
        };
    }
}
