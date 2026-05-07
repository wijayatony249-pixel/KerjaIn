<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class FreelancerController extends Controller
{
    public function index(Request $request)
    {
        $freelancers = User::where('role', 'freelancer')
            ->with(['services'])
            ->withCount(['bookings as completed_projects' => function ($query) {
                $query->where('status', 'done');
            }])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'bio' => $user->bio,
                    'category' => $user->category,
                    'rating' => 4.9, // Mock rating for now
                    'completed_projects' => $user->completed_projects,
                ];
            });

        return response()->json($freelancers);
    }

    public function show($id)
    {
        $freelancer = User::where('role', 'freelancer')
            ->with(['services', 'portfolios'])
            ->withCount(['bookings as completed_projects' => function ($query) {
                $query->where('status', 'done');
            }])
            ->findOrFail($id);

        return response()->json([
            'id' => $freelancer->id,
            'name' => $freelancer->name,
            'bio' => $freelancer->bio,
            'category' => $freelancer->category,
            'rating' => 4.9, // Mock rating for now
            'completed_projects' => $freelancer->completed_projects,
            'services' => $freelancer->services,
            'portfolios' => $freelancer->portfolios,
        ]);
    }
}
