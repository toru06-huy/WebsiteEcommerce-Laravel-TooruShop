<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
protected $primaryKey = 'employeeID';
    public $timestamps = false;
    protected $fillable = ['userID','employeeCode','positionID','salary','hireDate'];
    protected $casts = ['salary' => 'decimal:2', 'hireDate' => 'date'];

    public function user()            { return $this->belongsTo(User::class, 'userID', 'userID'); }
    public function position()        { return $this->belongsTo(Position::class, 'positionID', 'positionID'); }
    public function processedOrders() { return $this->hasMany(Order::class, 'processedBy', 'employeeID'); }
}
