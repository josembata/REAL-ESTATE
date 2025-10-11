<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TanzaniaProxyController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UnitPricePlanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AmenityCategoryController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\PricePlanCategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\RoomPricePlanController;
use App\Http\Controllers\RoomBookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\OwnershipController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\LandlordReportController;
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

    return match(true) {
        $user->hasRole('Admin') => redirect()->route('admin.dashboard'),
        $user->hasRole('Agent') => redirect()->route('agent.dashboard'),
        $user->hasRole('Landlord') => redirect()->route('landlord.dashboard'),
        $user->hasRole('Tenant') => redirect()->route('tenant.dashboard'),
        $user->hasRole('Staff') => redirect()->route('staff.dashboard'),
        default => redirect()->route('profile.edit'),
    };
})->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




// Admin routes
Route::middleware(['auth', 'verified', 'profile.complete'])->prefix('admin') ->name('admin.')->group(function () {
        Route::get('/users', function () {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403); // Forbidden
            }
            return app(UserController::class)->index();
        })->name('users.index');

        Route::get('/users/{user}/edit', function ($user) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(UserController::class)->edit($user);
        })->name('users.edit');

        Route::put('/users/{user}', function ($user) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(UserController::class)->update(request(), $user);
        })->name('users.update');


        
        // Permissions routes
        Route::get('/permissions', function () {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->index();
        })->name('permissions.index');

        Route::post('/permissions', function () {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->store(request());
        })->name('permissions.store');

        Route::get('/permissions/{permission}/edit', function ($permission) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->edit($permission);
        })->name('permissions.edit');

        Route::put('/permissions/{permission}', function ($permission) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->update(request(), $permission);
        })->name('permissions.update');

        Route::delete('/permissions/{permission}', function ($permission) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->destroy($permission);
        })->name('permissions.destroy');


     

//         // Role assignment
// Route::get('/roles/assign', [RoleController::class, 'assignForm'])->name('roles.assign.form');
// Route::post('/roles/assign', [RoleController::class, 'assignRole'])->name('roles.assign');

    });

    //assign permissions to roles
    Route::get('/roles/assign-permissions', [RoleController::class, 'showAssignPermissionsForm'])
    ->name('roles.assign.permissions.form');

Route::post('/roles/assign-permissions', [RoleController::class, 'assignPermissions'])
    ->name('roles.assign.permissions');

    Route::post('/roles/toggle-permission', [RoleController::class, 'togglePermission'])
    ->name('roles.toggle.permission');


       // Roles routes
        Route::get('/roles', function () {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(RoleController::class)->index();
        })->name('roles.index');

        Route::post('/roles', function () {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(RoleController::class)->store(request());
        })->name('roles.store');

        Route::get('/roles/{role}/edit', function ($role) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(RoleController::class)->edit($role);
        })->name('roles.edit');

        Route::put('/roles/{role}', function ($role) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(RoleController::class)->update(request(), $role);
        })->name('roles.update');

        Route::delete('/roles/{role}', function ($role) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(RoleController::class)->destroy($role);
        })->name('roles.destroy');

           Route::get('/roles/assign', function () {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(RoleController::class)->assignForm();
        })->name('roles.assign.form');

       
Route::post('/roles/assign', [RoleController::class, 'assignRole'])->name('roles.assign');
          // Permissions routes
        Route::get('/permissions', function () {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->index();
        })->name('permissions.index');

        Route::post('/permissions', function () {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->store(request());
        })->name('permissions.store');

        Route::get('/permissions/{permission}/edit', function ($permission) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->edit($permission);
        })->name('permissions.edit');

        Route::put('/permissions/{permission}', function ($permission) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->update(request(), $permission);
        })->name('permissions.update');

        Route::delete('/permissions/{permission}', function ($permission) {
            if (!Auth::user()->hasRole('Admin')) {
                abort(403);
            }
            return app(PermissionController::class)->destroy($permission);
        })->name('permissions.destroy');


// Agent routes
Route::middleware(['auth', 'verified', 'profile.complete', 'role:Agent'])
    ->prefix('agent')->name('agent.')
    ->group(function () {
        Route::get('/properties', fn() => view('agent.properties'))->name('properties');
        Route::get('/appointments', fn() => view('agent.appointments'))->name('appointments');
    });

// Landlord routes
Route::middleware(['auth', 'verified', 'profile.complete', 'role:Landlord'])
    ->prefix('landlord')->name('landlord.')
    ->group(function () {
        Route::get('/properties', fn() => view('landlord.properties'))->name('properties');
        Route::get('/tenants', fn() => view('landlord.tenants'))->name('tenants');
    });

// Tenant/Customer routes
Route::middleware(['auth', 'verified', 'profile.complete', 'role:Tenant'])
    ->prefix('tenant')->name('tenant.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('tenant.dashboard'))->name('dashboard');
    });



// Admin
Route::get('/admin/dashboard', function () {
    if (!auth()->user()->hasRole('Admin')) {
        abort(403);
    }
    return view('admin.dashboard');
})->middleware(['auth'])->name('admin.dashboard');

// Agent
Route::get('/agent/dashboard', function () {
    if (!auth()->user()->hasRole('Agent')) {
        abort(403);
    }
    return view('agent.dashboard');

})->middleware(['auth'])->name('agent.dashboard');

// Landlord
Route::get('/landlord/dashboard', function () {
    if (!auth()->user()->hasRole('Landlord')) {
        abort(403);
    }
    return view('landlord.dashboard');
})->middleware(['auth'])->name('landlord.dashboard');

// Tenant
Route::get('/tenant/dashboard', function () {
    if (!auth()->user()->hasRole('Tenant')) {
        abort(403);
    }
    return view('tenant.dashboard');
})->middleware(['auth'])->name('tenant.dashboard');

//staff
Route::get('/staff/dashboard', function () {
    if (!auth()->user()->hasRole('Staff')) {
        abort(403);
    }
    return view('staff.dashboard');
})->middleware(['auth'])->name('staff.dashboard');



//property routes
 
Route::middleware(['auth'])->group(function () {
    //property routes
     Route::get('/properties', [PropertyController::class, 'index']) ->name('properties.index') ->middleware('can:view-properties');
    Route::get('/properties/create', [PropertyController::class, 'create']) ->name('properties.create') ->middleware('can:create-properties');
   Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store') ->middleware('can:create-properties');
     Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit')->middleware('can:edit-properties');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update')->middleware('can:edit-properties');
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show')->middleware('can:show-properties');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy')->middleware('can:delete-properties');


// amenities category

Route::get('/amenity-categories', [AmenityCategoryController::class, 'index'])->name('amenity-categories.index');
Route::post('/amenity-categories', [AmenityCategoryController::class, 'store'])->name('amenity-categories.store');
Route::get('/amenity-categories/{category}/edit', [AmenityCategoryController::class, 'edit'])->name('amenity-categories.edit');
Route::put('/amenity-categories/{category}', [AmenityCategoryController::class, 'update'])->name('amenity-categories.update');
Route::delete('/amenity-categories/{category}', [AmenityCategoryController::class, 'destroy'])->name('amenity-categories.destroy');

Route::get('/properties/{property}/assign-amenities', [PropertyController::class, 'assignAmenitiesForm'])
    ->name('properties.assignamenitiesForm');

// Handle saving assigned amenities
Route::post('/properties/{property}/assign-amenities', [PropertyController::class, 'assignAmenities'])
    ->name('properties.assignamenities');





//Amenities routes 

Route::get('/amenities', [AmenityController::class, 'index'])->name('amenities.index');
Route::post('/amenities', [AmenityController::class, 'store'])->name('amenities.store');
Route::get('/amenities/{amenity}/edit', [AmenityController::class, 'edit'])->name('amenities.edit');
Route::put('/amenities/{amenity}', [AmenityController::class, 'update'])->name('amenities.update');
Route::delete('/amenities/{amenity}', [AmenityController::class, 'destroy'])->name('amenities.destroy');




// Units Routes
Route::get('/units', [UnitController::class, 'index'])->name('units.index');          // List all units
Route::get('/units/create', [UnitController::class, 'create'])->name('units.create'); // Show create form
Route::post('/units', [UnitController::class, 'store'])->name('units.store');         // Store new unit
Route::get('/units/{unit}', [UnitController::class, 'show'])->name('units.show');     // Show single unit
Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit'); // Show edit form
Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update');  // Update unit
Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy'); // Delete unit






// Show booking form for a unit
Route::get('/units/{unit}/book', [BookingController::class, 'create'])->name('bookings.create');

// Store booking
Route::post('/units/{unit}/book', [BookingController::class, 'store'])->name('bookings.store');

// Show all bookings
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

// Show a single booking
Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

// Confirm / Cancel booking
Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');

Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::patch('/bookings/{booking}/restore', [BookingController::class, 'restore'])->name('bookings.restore');


Route::get('/my-bookings', [BookingController::class, 'userBookings'])->name('bookings.user');










// List all rooms in a unit
Route::get('/units/{unit}/rooms', [RoomController::class, 'index'])->name('units.rooms.index');

// Show form to create a room in a unit
Route::get('/units/{unit}/rooms/create', [RoomController::class, 'create'])->name('units.rooms.create');

// Store a new room in a unit
Route::post('/units/{unit}/rooms', [RoomController::class, 'store'])->name('units.rooms.store');

// Show a single room in a unit
Route::get('/units/{unit}/rooms/{room}', [RoomController::class, 'show'])->name('units.rooms.show');

// Show form to edit a room
Route::get('/units/{unit}/rooms/{room}/edit', [RoomController::class, 'edit'])->name('units.rooms.edit');

// Update a room
Route::put('/units/{unit}/rooms/{room}', [RoomController::class, 'update'])->name('units.rooms.update');
Route::patch('/units/{unit}/rooms/{room}', [RoomController::class, 'update']);

// Delete a room
Route::delete('/units/{unit}/rooms/{room}', [RoomController::class, 'destroy'])->name('units.rooms.destroy');


// Room Price Plans
Route::prefix('rooms/{room}')->group(function () {
    Route::get('price-plans', [RoomPricePlanController::class, 'index'])->name('rooms.price-plans.index');
    Route::get('price-plans/create', [RoomPricePlanController::class, 'create'])->name('rooms.price-plans.create');
    Route::post('price-plans', [RoomPricePlanController::class, 'store'])->name('rooms.price-plans.store');
    Route::get('price-plans/{pricePlan}/edit', [RoomPricePlanController::class, 'edit'])->name('rooms.price-plans.edit');
    Route::put('price-plans/{pricePlan}', [RoomPricePlanController::class, 'update'])->name('rooms.price-plans.update');
    Route::delete('price-plans/{pricePlan}', [RoomPricePlanController::class, 'destroy'])->name('rooms.price-plans.destroy');
});





// show room booking form
Route::get('units/{unit}/rooms/{room}/book', [RoomBookingController::class, 'create'])
    ->name('rooms.book.create');

// submit room booking
Route::post('units/{unit}/rooms/{room}/book', [RoomBookingController::class, 'store'])
    ->name('rooms.book.store');









// List all price plans for a unit
Route::get('units/{unit}/price-plans', [UnitPricePlanController::class, 'index'])->name('price-plans.index');

// Show form to create a new price plan
Route::get('units/{unit}/price-plans/create', [UnitPricePlanController::class, 'create'])->name('price-plans.create');

// Store a new price plan
Route::post('units/{unit}/price-plans', [UnitPricePlanController::class, 'store'])->name('price-plans.store');

// Show form to edit a price plan
Route::get('units/{unit}/price-plans/{pricePlan}/edit', [UnitPricePlanController::class, 'edit'])->name('price-plans.edit');

// Update a price plan
Route::put('units/{unit}/price-plans/{pricePlan}', [UnitPricePlanController::class, 'update'])->name('price-plans.update');

// Delete a price plan
Route::delete('units/{unit}/price-plans/{pricePlan}', [UnitPricePlanController::class, 'destroy'])->name('price-plans.destroy');



// Price Plan Categories
Route::prefix('price-plan-categories')->group(function () {
    Route::get('/', [PricePlanCategoryController::class, 'index'])->name('price_plan_categories.index');
    Route::get('/create', [PricePlanCategoryController::class, 'create'])->name('price_plan_categories.create');
    Route::post('/', [PricePlanCategoryController::class, 'store'])->name('price_plan_categories.store');
    Route::get('/{category}/edit', [PricePlanCategoryController::class, 'edit'])->name('price_plan_categories.edit');
    Route::put('/{category}', [PricePlanCategoryController::class, 'update'])->name('price_plan_categories.update');
    Route::delete('/{category}', [PricePlanCategoryController::class, 'destroy'])->name('price_plan_categories.destroy');
});


Route::middleware(['auth'])->group(function () {
    // Tenant/User
    Route::get('/inquiries/create/{property}', [InquiryController::class, 'create'])->name('inquiries.create');
    Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

    // Agent side
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::post('/inquiries/{inquiry}/close', [InquiryController::class, 'close'])->name('inquiries.close');
    
    // Messages
    Route::post('/inquiries/{inquiry}/messages', [MessageController::class, 'store'])->name('messages.store');
});



//billing routes 
Route::get('/billing/create/{unit}', [BillingController::class, 'create'])->name('billing.create');

Route::post('/billing', [BillingController::class, 'store'])->name('billing.store');


//payment routes 
Route::get('/bookings/{booking}/payment', [BookingController::class, 'payment'])->name('bookings.payment');
Route::post('/bookings/{booking}/pay', [BookingController::class, 'processPayment'])->name('bookings.pay');

Route::get('/payments/{invoice}/choose', [PaymentController::class, 'chooseMethod'])->name('payments.choose');


//payment routes

Route::prefix('payments')->group(function () {
    Route::get('/{invoice}/choose', [PaymentController::class, 'chooseMethod'])->name('payments.choose');

    Route::post('/{invoice}/mobile', [PaymentController::class, 'mobile'])->name('payments.mobile');
    Route::post('/{invoice}/card', [PaymentController::class, 'card'])->name('payments.card');
});

//invoices routes
    // Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index'); // list all invoices
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show'); // show a single invoice
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    
Route::resource('owners', OwnerController::class);
Route::resource('ownerships', OwnershipController::class);
Route::resource('transactions', TransactionController::class);


//approve ownership documents
    Route::get('ownership-documents', [OwnershipController::class, 'documentsPending'])->name('admin.documents.pending');
    Route::post('ownership-documents/{document}/verify', [OwnershipController::class, 'verifyDocument'])->name('admin.documents.verify');



//leases routes
Route::get('leases/{lease}', [LeaseController::class, 'show'])->name('leases.show');
Route::get('leases/{lease}/download', [LeaseController::class, 'download'])->name('leases.download');
 Route::get('/leases', [LeaseController::class, 'index'])->name('leases.index');


// Landlord Reports

 Route::get('/landlord/report', [LandlordReportController::class, 'index'])
        ->name('landlord.report');

        Route::get('/my/report', [LandlordReportController::class, 'myReport'])
        ->name('landlords.my_report');


    
});




// Breeze auth routes
require __DIR__.'/auth.php';