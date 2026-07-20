<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $primaryKey = 'viewID';

    // Không dùng updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'userID',
        'session_id',
        'ip',
        'path',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
