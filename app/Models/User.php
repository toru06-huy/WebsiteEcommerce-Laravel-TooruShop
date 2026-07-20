<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $primaryKey = 'userID';
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
     protected $fillable = ['email','phone','password','fullName','sex','birthday','role','IsActive'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *  
     * @return array<string, string>
     */
    protected $casts    = ['IsActive' => 'boolean', 'birthday' => 'date', 'password' => 'hashed'];

    public function orders()   { return $this->hasMany(Order::class, 'userID'); }
    public function employee() { return $this->hasOne(Employee::class, 'userID'); }
    public function addresses()   { return $this->hasMany(Address::class, 'userID', 'userID'); }
    public function membership()  { return $this->hasOne(MembershipTier::class, 'userID', 'userID'); }
    public function wishlists()   { return $this->hasMany(Wishlist::class, 'userID', 'userID'); }
}
