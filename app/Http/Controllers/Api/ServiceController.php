<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('freelancer:id,name,avatar')->where('is_active', 1);

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        return response()->json($query->latest()->paginate(12));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'thumbnail'   => 'nullable|image|max:5120',
        ]);

        $data = $request->only('title', 'description', 'category', 'price');
        $data['freelancer_id'] = auth()->id();
        $data['is_active'] = 1;

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('services', 'public');
            $data['thumbnail'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        $service = Service::create($data);

        return response()->json($service, 201);
    }

    public function show($id)
    {
        return response()->json(Service::with('freelancer:id,name,avatar,bio')->findOrFail($id));
    }

    public function update(Request $request, Service $service)
    {
        if ($service->freelancer_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'thumbnail'   => 'nullable|image|max:5120',
        ]);

        $data = $request->only('title', 'description', 'category', 'price', 'is_active');

        if ($request->hasFile('thumbnail')) {
            if ($service->thumbnail) {
                $oldPath = str_replace('/storage/', '', $service->thumbnail);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('thumbnail')->store('services', 'public');
            $data['thumbnail'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        $service->update($data);
        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        if ($service->freelancer_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $service->delete();
        return response()->json(['message' => 'Deleted']);
    }
}