<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $primaryKey = 'orderID';
    protected $fillable = ['userID','name','phone','orderDate','totalAmount','discountID','discountAmount','finalAmount','status','shippingAddress','payment','processedBy'];
    protected $casts = ['orderDate' => 'datetime'];

    public function user()      { return $this->belongsTo(User::class, 'userID', 'userID'); }
    public function discount()  { return $this->belongsTo(Discount::class, 'discountID', 'discountID'); }
    public function details()   { return $this->hasMany(OrderDetail::class, 'orderID', 'orderID'); }
    public function processor() { return $this->belongsTo(Employee::class, 'processedBy', 'employeeID'); }
}