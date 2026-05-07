<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //

    protected $fillable = ['freelancer_id','title','description','category','price','thumbnail','is_active'];
    public function freelancer() { return $this->belongsTo(User::class, 'freelancer_id'); }
    public function bookings()   { return $this->hasMany(Booking::class); }
}