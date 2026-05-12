<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = ['freelancer_id', 'title', 'image', 'description'];

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }
}
