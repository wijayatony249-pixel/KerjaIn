<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        return auth()->user()->portfolios()->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:3048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('portfolios', 'public');
            $validated['image'] = $path;
        }

        $validated['freelancer_id'] = auth()->id();
        $portfolio = Portfolio::create($validated);

        return response()->json($portfolio, 201);
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->freelancer_id !== auth()->id()) {
            abort(403);
        }

        if ($portfolio->image) {
            Storage::disk('public')->delete($portfolio->image);
        }

        $portfolio->delete();

        return response()->json(null, 204);
    }
}
