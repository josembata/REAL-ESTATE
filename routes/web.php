<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [RegisteredUserController::class, 'create'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('complete-profile');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Illuminate\Auth\Events\Verified($request->user()));
        }

        return redirect()->route('complete-profile')
            ->with('status', 'Email verified successfully! Please complete your profile.');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});

// Profile completion routes no profile.complete middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/complete-profile', [ProfileController::class, 'showCompleteForm'])->name('complete-profile');
    Route::post('/complete-profile', [ProfileController::class, 'completeProfile'])->name('complete-profile.submit');
});

// Protected routes  require both verification and profile completion
Route::middleware(['auth', 'verified', 'profile.complete'])->group(function () {
    // Dynamic dashboard based on role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'agent' => redirect()->route('agent.dashboard'),
            'landlord' => redirect()->route('landlord.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    })->name('dashboard');

    // Separate dashboard routes for each role
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard')->middleware('admin');

    Route::get('/agent/dashboard', function () {
        return view('agent.dashboard');
    })->name('agent.dashboard');

    Route::get('/landlord/dashboard', function () {
        return view('landlord.dashboard');
    })->name('landlord.dashboard');

    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Admin routes
Route::middleware(['auth', 'verified', 'profile.complete', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
});

// Agent routes
Route::middleware(['auth', 'verified', 'profile.complete'])->prefix('agent')->name('agent.')->group(function () {
   
});

// Landlord routes
Route::middleware(['auth', 'verified', 'profile.complete'])->prefix('landlord')->name('landlord.')->group(function () {
   
});

// Customer routes
Route::middleware(['auth', 'verified', 'profile.complete'])->prefix('customer')->name('customer.')->group(function () {
  
});



//property routes
 
Route::middleware(['auth'])->group(function () {
    Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');//show list
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');//show form
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');//insert data

    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');//edit data
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');//update data
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');//show single


    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');//delete data


// Units Routes
Route::get('/units', [UnitController::class, 'index'])->name('units.index');          // List all units
Route::get('/units/create', [UnitController::class, 'create'])->name('units.create'); // Show create form
Route::post('/units', [UnitController::class, 'store'])->name('units.store');         // Store new unit
Route::get('/units/{unit}', [UnitController::class, 'show'])->name('units.show');     // Show single unit
Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit'); // Show edit form
Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update');  // Update unit
Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy'); // Delete unit

});
// Breeze auth routes
require __DIR__.'/auth.php';