<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $primaryKey = 'discountID';
    public $timestamps = false;
    protected $fillable = ['discountCode','discountName','discountType','discountValue','discountLimit','startDate','endDate','minOrderValue','isActive','isPersonal'];
    protected $casts = ['isActive' => 'boolean', 'isPersonal' => 'boolean', 'startDate' => 'datetime', 'endDate' => 'datetime'];

    public function orders() { return $this->hasMany(Order::class, 'discountID', 'discountID'); }

    public function userDiscounts() { return $this->hasMany(UserDiscount::class, 'discountID', 'discountID'); }
}