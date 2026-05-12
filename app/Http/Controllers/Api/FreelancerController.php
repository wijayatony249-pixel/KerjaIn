<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FreelancerController extends Controller
{
    public function index()
    {
        $freelancers = User::where('role', 'freelancer')
            ->withCount('services')
            ->get();

        foreach ($freelancers as $freelancer) {
            $freelancer->avg_rating = $freelancer->reviews()->avg('rating') ?: 0;
        }

        return response()->json($freelancers);
    }

    public function show(User $user)
    {
        if ($user->role !== 'freelancer') {
            abort(404);
        }

        $user->load(['services' => function($query) {
            $query->where('is_active', true);
        }, 'portfolios', 'reviews.client']);

        $user->avg_rating = $user->reviews()->avg('rating') ?: 0;

        return response()->json($user);
    }
}
