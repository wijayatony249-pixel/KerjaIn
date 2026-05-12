<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar', 'bio', 'phone', 
        'latitude', 'longitude', 'fcm_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function services()
    {
        return $this->hasMany(Service::class, 'freelancer_id');
    }

    public function clientBookings()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function freelancerBookings()
    {
        return $this->hasMany(Booking::class, 'freelancer_id');
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'freelancer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'freelancer_id');
    }

    // Accessors
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    // Scopes
    public function scopeFreelancers($query)
    {
        return $query->where('role', 'freelancer');
    }

    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }
}
