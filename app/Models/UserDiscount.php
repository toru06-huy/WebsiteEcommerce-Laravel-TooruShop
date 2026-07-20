<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDiscount extends Model
{
    protected $table = 'user_discounts';
    protected $primaryKey = 'userDiscountID';

    protected $fillable = ['userID', 'discountID', 'isUsed', 'usedAt'];

    protected $casts = [
        'isUsed' => 'boolean',
        'usedAt' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discountID', 'discountID');
    }
}
