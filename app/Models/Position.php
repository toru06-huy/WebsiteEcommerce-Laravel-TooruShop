<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $primaryKey = 'positionID';
    public $timestamps = false;
    protected $fillable = ['positionCode', 'positionName'];

    public function employees() { return $this->hasMany(Employee::class, 'positionID', 'positionID'); }
}
