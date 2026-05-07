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
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Services/Index', [
            'services' => $services,
            'filters' => $request->only(['search'])
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
        if (auth()->user()->role !== 'freelancer') {
            return redirect()->route('dashboard')->with('error', 'Hanya freelancer yang dapat menambah layanan.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|string', // Temporary string, can be file later
        ]);

        auth()->user()->services()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Layanan berhasil ditambahkan!');
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
        ]);

        $service->update($validated);

        return redirect()->route('dashboard')->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy(Service $service)
    {
        if ($service->freelancer_id !== auth()->id()) {
            abort(403);
        }

        $service->delete();

        return redirect()->route('dashboard')->with('success', 'Layanan berhasil dihapus!');
    }
}
