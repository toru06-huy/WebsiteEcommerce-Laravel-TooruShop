<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $primaryKey = 'addressID';
    protected $fillable   = ['userID', 'city', 'district', 'ward', 'addressDetail'];

    
    public function user() { 
        return $this->belongsTo(User::class, 'userID', 'userID'); 
    }
}