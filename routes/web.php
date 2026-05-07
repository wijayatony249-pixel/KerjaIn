<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Public routes
Route::get('/test-vue', fn() => Inertia::render('Test'));
Route::get('/', fn() => Inertia::render('Landing'))->name('home');

// Public service routes
Route::get('/layanan', function () {
    return Inertia::render('Services/Index', [
        'services' => \App\Models\Service::with('freelancer')->where('is_active', 1)->latest()->paginate(12),
        'filters' => request()->all(['search', 'category'])
    ]);
});

Route::get('/layanan/{id}', function ($id) {
    return Inertia::render('Services/Show', [
        'service' => \App\Models\Service::with(['freelancer', 'bookings.review'])->findOrFail($id)
    ]);
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Services
    Route::get('/layanan/buat', fn() => Inertia::render('Services/Form'));
    Route::get('/layanan/{id}/edit', function ($id) {
        return Inertia::render('Services/Form', [
            'service' => \App\Models\Service::findOrFail($id)
        ]);
    });

    // Bookings
    Route::get('/booking', function () {
        $user = auth()->user();
        $bookings = \App\Models\Booking::with(['service', 'client', 'freelancer'])
            ->where('client_id', $user->id)
            ->orWhere('freelancer_id', $user->id)
            ->latest()
            ->get();
        return Inertia::render('Bookings/Index', ['bookings' => $bookings]);
    })->name('booking.index');

    Route::get('/booking/{id}', function ($id) {
        $booking = \App\Models\Booking::with(['service', 'client', 'freelancer', 'review'])->findOrFail($id);
        return Inertia::render('Bookings/Show', [
            'booking' => $booking,
            'initialMessages' => $booking->messages()->with('sender')->get()
        ]);
    });

    // Profil
    Route::get('/profil', fn() => Inertia::render('Profile/Index'))->name('profile.index');

    // Pesan & Ulasan (Placeholder for now)
    Route::get('/pesan', fn() => Inertia::render('Dashboard'))->name('messages.index');
    Route::get('/ulasan', fn() => Inertia::render('Dashboard'))->name('reviews.index');
});