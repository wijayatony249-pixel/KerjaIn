<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->has('my')) {
            $query->where('freelancer_id', auth()->id());
        } else {
            $query->where('is_active', true)->with('freelancer:id,name,avatar');
        }

        if ($request->has('all')) {
            return response()->json(['data' => $query->latest()->get()]);
        }

        return $query->latest()->paginate(12);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('services', 'public');
            $validated['thumbnail'] = $path;
        }

        $validated['freelancer_id'] = auth()->id();
        $service = Service::create($validated);

        return response()->json($service, 201);
    }

    public function show(Service $service)
    {
        $service->load(['freelancer.portfolios', 'reviews.client']);
        $service->avg_rating = $service->reviews()->avg('rating');
        
        return response()->json($service);
    }

    public function update(Request $request, Service $service)
    {
        if ($service->freelancer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($service->thumbnail) {
                Storage::disk('public')->delete($service->thumbnail);
            }
            $path = $request->file('thumbnail')->store('services', 'public');
            $validated['thumbnail'] = $path;
        }

        $service->update($validated);

        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        if ($service->freelancer_id !== auth()->id()) {
            abort(403);
        }

        if ($service->thumbnail) {
            Storage::disk('public')->delete($service->thumbnail);
        }

        $service->delete();

        return response()->json(null, 204);
    }
}