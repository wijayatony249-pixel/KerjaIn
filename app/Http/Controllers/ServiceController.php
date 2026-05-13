<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::with('freelancer:id,name')
            ->where('is_active', true)
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), function($query) use ($request) {
                if ($request->category !== 'all') {
                    $query->where('category', $request->category);
                }
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Services/Index', [
            'services' => $services,
            'filters' => $request->only(['search', 'category'])
        ]);
    }

    public function manage(Request $request)
    {
        if (auth()->user()->role !== 'freelancer') {
            return redirect()->route('dashboard');
        }

        $services = Service::where('freelancer_id', auth()->id())
            ->when($request->search, function($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return Inertia::render('Services/Manage', [
            'services' => $services
        ]);
    }

    public function show(Service $service)
    {
        return Inertia::render('Services/Show', [
            'service' => $service->load('freelancer:id,name'),
        ]);
    }

    public function create()
    {
        if (auth()->user()->role !== 'freelancer') {
            return redirect()->route('dashboard')->with('error', 'Hanya freelancer yang dapat menambah layanan.');
        }

        return Inertia::render('Services/Create');
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (auth()->user()->role !== 'freelancer') {
            return redirect()->route('dashboard')->with('error', 'Hanya freelancer yang dapat menambah layanan.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        try {
            $data = [
                'freelancer_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'price' => $request->price,
                'is_active' => true,
            ];

            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('services', 'public');
                $data['thumbnail'] = $path;
            }

            Service::create($data);

            return back()->with('success', 'Layanan berhasil dipublikasikan!');

        } catch (\Exception $e) {
            return back()->withErrors(['title' => 'Gagal menyimpan ke database: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(Service $service)
    {
        if ($service->freelancer_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Services/Edit', [
            'service' => $service
        ]);
    }

    public function update(Request $request, Service $service)
    {
        if ($service->freelancer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('services', 'public');
            $validated['thumbnail'] = $path;
        }

        $service->update($validated);

        return redirect()->route('dashboard')->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy(Service $service)
    {
        if ($service->freelancer_id !== auth()->id()) {
            abort(403);
        }

        $service->delete();

        return back()->with('success', 'Layanan berhasil dihapus!');
    }
}
