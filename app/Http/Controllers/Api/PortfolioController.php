<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->portfolios()->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:5120',
        ]);

        $path = $request->file('image')->store('portfolios', 'public');

        $portfolio = $request->user()->portfolios()->create([
            'title' => $validated['title'],
            'image_url' => Storage::url($path),
        ]);

        return response()->json($portfolio, 201);
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Extract path from URL to delete file
        $path = str_replace('/storage/', '', $portfolio->image_url);
        Storage::disk('public')->delete($path);

        $portfolio->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
