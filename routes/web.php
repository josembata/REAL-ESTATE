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
use App\Http\Controllers\UserPermissionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [RegisteredUserController::class, 'create'])->name('register');// Home page shows registration form

Route::middleware(['auth'])->group(function () {

Route::get('/email/verify', function () { return view('auth.verify-email'); })->name('verification.notice');// Email verification notice
Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {// Email verification handler
        if ($request->user()->hasVerifiedEmail()) {// If already verified
            return redirect()->route('complete-profile');// Redirect to profile completion if already verified
        }

        if ($request->user()->markEmailAsVerified()) {// Mark email as verified
            event(new Illuminate\Auth\Events\Verified($request->user()));// Fire verified event
        }

        return redirect()->route('complete-profile')// Redirect to profile completion
            ->with('status', 'Email verified successfully! Please complete your profile.');// Success message
    })->middleware('signed')->name('verification.verify');// Signed URL middleware

    Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {// Resend verification email
        $request->user()->sendEmailVerificationNotification();// Send verification email
        return back()->with('status', 'verification-link-sent');// Success message
    })->middleware('throttle:6,1')->name('verification.send');// Throttle to 6 requests per minute
});



// Routes for users to complete their profile after email verification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/complete-profile', [ProfileController::class, 'showCompleteForm'])->name('complete-profile');// Show profile completion form
    Route::post('/complete-profile', [ProfileController::class, 'completeProfile'])->name('complete-profile.submit');// Submit profile completion
});


// Protected routes require both verification and profile completion
Route::middleware(['auth', 'verified', 'profile.complete'])->group(function () {
    // Dynamic dashboard based on role
    Route::get('/dashboard', function () {
    $user = Auth::user();

    return match(true) {
        $user->hasRole('Admin') => redirect()->route('admin.dashboard'),// Redirect to admin dashboard
        $user->hasRole('Agent') => redirect()->route('agent.dashboard'),// Redirect to agent dashboard
        $user->hasRole('Landlord') => redirect()->route('landlord.dashboard'),// Redirect to landlord dashboard
        $user->hasRole('Tenant') => redirect()->route('tenant.dashboard'),// Redirect to tenant dashboard
        $user->hasRole('Staff') => redirect()->route('staff.dashboard'),// Redirect to staff dashboard
        default => redirect()->route('profile.edit'),// Redirect to profile completion if no role matched
    };
})->name('dashboard');// Dynamic dashboard route

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');// Edit profile
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');// Update profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//Role specific dashboards

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

// Role  Management routes
Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('can:view-roles');// List roles
Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('can:create-roles');// Create role
Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('can:edit-roles');// Edit role
Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('can:edit-roles');// Update role
Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('can:delete-roles');// Delete role
Route::get('roles/assign', [RoleController::class, 'assignForm'])->name('roles.assign.form')->middleware('can:assign-roles');// Assign role form
Route::post('roles/assign', [RoleController::class, 'assignRole'])->name('roles.assign')->middleware('can:assign-roles');// Handle role assignment

// Permission Management routes
Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('can:view-permissions');// List permissions
Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('can:create-permissions');// Create permission
Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('can:edit-permissions');// Edit permission
Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('can:edit-permissions');// Update permission
Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('can:delete-permissions');// Delete permission
Route::post('permissions/categories', [PermissionController::class, 'storeCategory'])->name('permissions.categories.store')->middleware('can:create-permissions');// Store permission category

    //assign permissions to roles
    Route::get('/roles/assign-permissions', [RoleController::class, 'showAssignPermissionsForm'])
    ->name('roles.assign.permissions.form');

Route::post('/roles/assign-permissions', [RoleController::class, 'assignPermissions'])
    ->name('roles.assign.permissions');

    Route::post('/roles/toggle-permission', [RoleController::class, 'togglePermission'])
    ->name('roles.toggle.permission');
  //assign permissions to users
    Route::post('/users/assign-permissions', [UserPermissionController::class, 'assign'])->name('users.assign.permissions');


// User Management routes 
Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index')->middleware('can:manage-users');// List users
Route::get('admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit')->middleware('can:manage-users');// Show the form for editing a specific user
Route::put('admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update')->middleware('can:manage-users');// Update a specific user


    //property routes
     Route::get('/properties', [PropertyController::class, 'index']) ->name('properties.index') ->middleware('can:view-properties');
    Route::get('/properties/create', [PropertyController::class, 'create']) ->name('properties.create') ->middleware('can:create-properties');
   Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store') ->middleware('can:create-properties');
     Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit')->middleware('can:edit-properties');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update')->middleware('can:edit-properties');
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show')->middleware('can:show-properties');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy')->middleware('can:delete-properties');


//Amenities routes 

Route::get('/amenities', [AmenityController::class, 'index'])->name('amenities.index')->middleware('can:view-amenities');
Route::post('/amenities', [AmenityController::class, 'store'])->name('amenities.store')->middleware('can:create-amenities');
Route::get('/amenities/{amenity}/edit', [AmenityController::class, 'edit'])->name('amenities.edit')->middleware('can:edit-amenities');
Route::put('/amenities/{amenity}', [AmenityController::class, 'update'])->name('amenities.update')->middleware('can:edit-amenities');
Route::delete('/amenities/{amenity}', [AmenityController::class, 'destroy'])->name('amenities.destroy')->middleware('can:delete-amenities');



// amenities category

Route::get('/amenity-categories', [AmenityCategoryController::class, 'index'])->name('amenity-categories.index')->middleware('can:view-amenity-categories');
Route::post('/amenity-categories', [AmenityCategoryController::class, 'store'])->name('amenity-categories.store')->middleware('can:create-amenity-categories');
Route::get('/amenity-categories/{category}/edit', [AmenityCategoryController::class, 'edit'])->name('amenity-categories.edit')->middleware('can:edit-amenity-categories');
Route::put('/amenity-categories/{category}', [AmenityCategoryController::class, 'update'])->name('amenity-categories.update')->middleware('can:edit-amenity-categories');
Route::delete('/amenity-categories/{category}', [AmenityCategoryController::class, 'destroy'])->name('amenity-categories.destroy')->middleware('can:delete-amenity-categories');
// Show form to assign amenities to a property
Route::get('/properties/{property}/assign-amenities', [PropertyController::class, 'assignAmenitiesForm']) ->name('properties.assignamenitiesForm')->middleware('can:assign-amenities');
// Handle saving assigned amenities
Route::post('/properties/{property}/assign-amenities', [PropertyController::class, 'assignAmenities']) ->name('properties.assignamenities')->middleware('can:assign-amenities');







// Units Routes
Route::get('/units', [UnitController::class, 'index'])->name('units.index')->middleware('can:view-units');          // List all units
Route::get('/units/create', [UnitController::class, 'create'])->name('units.create')->middleware('can:create-units'); // Show create form
Route::post('/units', [UnitController::class, 'store'])->name('units.store')->middleware('can:create-units');         // Store new unit
Route::get('/units/{unit}', [UnitController::class, 'show'])->name('units.show')->middleware('can:show-units');     // Show single unit
Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit')->middleware('can:edit-units'); // Show edit form
Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update')->middleware('can:edit-units');  // Update unit
Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy')->middleware('can:delete-units'); // Delete unit
// View units of a specific property
Route::get('properties/{property}/units', [PropertyController::class, 'viewUnits']) ->name('properties.viewUnits')->middleware('can:view-units');



// Booking Routes

Route::get('/units/{unit}/book', [BookingController::class, 'create'])->name('bookings.create')->middleware('can:create-bookings'); // Show booking form
Route::post('/units/{unit}/book', [BookingController::class, 'store'])->name('bookings.store')->middleware('can:create-bookings'); // Store booking
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index')->middleware('can:view-bookings'); // Show all bookings
Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show')->middleware('can:view-bookings'); // Show a single booking
// Confirm / Cancel booking
Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm')->middleware('can:edit-bookings');// Confirm booking
Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel')->middleware('can:edit-bookings');// Cancel booking
Route::patch('/bookings/{booking}/restore', [BookingController::class, 'restore'])->name('bookings.restore')->middleware('can:edit-bookings');// Restore booking
// View user's own bookings
Route::get('/my-bookings', [BookingController::class, 'userBookings'])->name('bookings.user')->middleware('can:view-bookings');
// Auto-cancel booking if not confirmed in time
Route::post('/bookings/{booking}/auto-cancel', [BookingController::class, 'autoCancel'])->name('bookings.auto-cancel')->middleware('can:edit-bookings');



 //roomBooking Routes 

Route::get('units/{unit}/rooms/{room}/book', [RoomBookingController::class, 'create']) ->name('rooms.book.create')->middleware('can:create-bookings');// Show booking form
Route::post('units/{unit}/rooms/{room}/book', [RoomBookingController::class, 'store']) ->name('rooms.book.store')->middleware('can:create-bookings');// Store booking
Route::patch('/bookings/{booking}/cancel', [RoomBookingController::class, 'cancel'])->name('bookings.cancel')->middleware('can:edit-bookings');// Cancel booking
Route::patch('/bookings/{booking}/restore', [RoomBookingController::class, 'restore'])->name('bookings.restore')->middleware('can:edit-bookings');// Restore booking
Route::post('/bookings/{booking}/auto-cancel', [RoomBookingController::class, 'autoCancel']) ->name('bookings.auto-cancel')->middleware('can:edit-bookings');




// Room Routes

Route::get('/units/{unit}/rooms', [RoomController::class, 'index'])->name('units.rooms.index')->middleware('can:view-rooms'); // List all rooms in a unit
Route::get('/units/{unit}/rooms/create', [RoomController::class, 'create'])->name('units.rooms.create')->middleware('can:create-rooms'); // Show form to create a room in a unit
Route::post('/units/{unit}/rooms', [RoomController::class, 'store'])->name('units.rooms.store')->middleware('can:create-rooms'); // Store a new room in a unit
Route::get('/units/{unit}/rooms/{room}', [RoomController::class, 'show'])->name('units.rooms.show')->middleware('can:view-rooms');// Show a single room in a unit
Route::get('/units/{unit}/rooms/{room}/edit', [RoomController::class, 'edit'])->name('units.rooms.edit')->middleware('can:edit-rooms'); // Show form to edit a room
Route::put('/units/{unit}/rooms/{room}', [RoomController::class, 'update'])->name('units.rooms.update')->middleware('can:edit-rooms'); // Update a room
Route::patch('/units/{unit}/rooms/{room}', [RoomController::class, 'update'])->middleware('can:edit-rooms');// Update a room
Route::delete('/units/{unit}/rooms/{room}', [RoomController::class, 'destroy'])->name('units.rooms.destroy')->middleware('can:delete-rooms');// Delete a room


// Room Price Plans
Route::prefix('rooms/{room}')->group(function () {
    Route::get('price-plans', [RoomPricePlanController::class, 'index'])->name('rooms.price-plans.index')->middleware('can:view-room-price-plans');// List all price plans for a room
    Route::get('price-plans/create', [RoomPricePlanController::class, 'create'])->name('rooms.price-plans.create')->middleware('can:create-room-price-plans');// Show form to create a new price plan
    Route::post('price-plans', [RoomPricePlanController::class, 'store'])->name('rooms.price-plans.store')->middleware('can:create-room-price-plans');// Store a new price plan
    Route::get('price-plans/{pricePlan}/edit', [RoomPricePlanController::class, 'edit'])->name('rooms.price-plans.edit')->middleware('can:edit-room-price-plans');// Show form to edit a price plan
    Route::put('price-plans/{pricePlan}', [RoomPricePlanController::class, 'update'])->name('rooms.price-plans.update')->middleware('can:edit-room-price-plans');// Update a price plan
    Route::delete('price-plans/{pricePlan}', [RoomPricePlanController::class, 'destroy'])->name('rooms.price-plans.destroy')->middleware('can:delete-room-price-plans');// Delete a price plan
});


// Unit Price Plans Routes

Route::get('units/{unit}/price-plans', [UnitPricePlanController::class, 'index'])->name('price-plans.index')->middleware('can:view-unit-price-plans'); // List all price plans for a unit
Route::get('units/{unit}/price-plans/create', [UnitPricePlanController::class, 'create'])->name('price-plans.create')->middleware('can:create-unit-price-plans');// Show form to create a new price plan
Route::post('units/{unit}/price-plans', [UnitPricePlanController::class, 'store'])->name('price-plans.store')->middleware('can:create-unit-price-plans');// Store a new price plan
Route::get('units/{unit}/price-plans/{pricePlan}/edit', [UnitPricePlanController::class, 'edit'])->name('price-plans.edit')->middleware('can:edit-unit-price-plans');// Show form to edit a price plan
Route::put('units/{unit}/price-plans/{pricePlan}', [UnitPricePlanController::class, 'update'])->name('price-plans.update')->middleware('can:edit-unit-price-plans');// Update a price plan
Route::delete('units/{unit}/price-plans/{pricePlan}', [UnitPricePlanController::class, 'destroy'])->name('price-plans.destroy')->middleware('can:delete-unit-price-plans');// Delete a price plan



// Price Plan Categories
Route::prefix('price-plan-categories')->group(function () {
    Route::get('/', [PricePlanCategoryController::class, 'index'])->name('price_plan_categories.index')->middleware('can:view-price-plan-categories');// List all categories
    Route::get('/create', [PricePlanCategoryController::class, 'create'])->name('price_plan_categories.create')->middleware('can:create-price-plan-categories');// Show form to create a new category
    Route::post('/', [PricePlanCategoryController::class, 'store'])->name('price_plan_categories.store')->middleware('can:create-price-plan-categories');// Store a new category
    Route::get('/{category}/edit', [PricePlanCategoryController::class, 'edit'])->name('price_plan_categories.edit')->middleware('can:edit-price-plan-categories');// Show form to edit a category
    Route::put('/{category}', [PricePlanCategoryController::class, 'update'])->name('price_plan_categories.update')->middleware('can:edit-price-plan-categories');// Update a category
    Route::delete('/{category}', [PricePlanCategoryController::class, 'destroy'])->name('price_plan_categories.destroy')->middleware('can:delete-price-plan-categories');// Delete a category
});


// Inquiries and Messages Routes

    
    Route::get('/inquiries/create/{property}', [InquiryController::class, 'create'])->name('inquiries.create')->middleware('can:create-inquiries');// Show inquiry form for a property
    Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store')->middleware('can:create-inquiries');// Store a new inquiry
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index')->middleware('can:view-inquiries');// List all inquiries
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show')->middleware('can:view-inquiries');// Show a single inquiry
    Route::post('/inquiries/{inquiry}/close', [InquiryController::class, 'close'])->name('inquiries.close')->middleware('can:close-inquiries');// Close an inquiry
    Route::post('/inquiries/{inquiry}/messages', [MessageController::class, 'store'])->name('messages.store')->middleware('can:create-messages');// Store a new message in an inquiry thread



//payment routes 
Route::get('/bookings/{booking}/payment', [BookingController::class, 'payment'])->name('bookings.payment')->middleware('can:view-payments'); // Show payment options for a booking
Route::post('/bookings/{booking}/pay', [BookingController::class, 'processPayment'])->name('bookings.pay')->middleware('can:create-payments'); // Process payment for a booking
Route::get('/payments/{invoice}/choose', [PaymentController::class, 'chooseMethod'])->name('payments.choose')->middleware('can:view-payments');// Show payment method selection
Route::post('/payments/{invoice}/mobile', [PaymentController::class, 'mobile'])->name('payments.mobile')->middleware('can:create-payments');// Process mobile payment
Route::post('/payments/{invoice}/card', [PaymentController::class, 'card'])->name('payments.card')->middleware('can:create-payments');// Process card payment
//payment routes
// Route::prefix('payments')->group(function () {
//     Route::get('/{invoice}/choose', [PaymentController::class, 'chooseMethod'])->name('payments.choose');

//     Route::post('/{invoice}/mobile', [PaymentController::class, 'mobile'])->name('payments.mobile');
//     Route::post('/{invoice}/card', [PaymentController::class, 'card'])->name('payments.card');
// });

//invoices routes

    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show')->middleware('can:view-invoices'); // show a single invoice
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index')->middleware('can:view-invoices');// list all invoices
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print')->middleware('can:view-invoices');// print a single invoice
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download')->middleware('can:view-invoices');// download a single invoice



 // owners, ownerships routes
// Route::resource('owners', OwnerController::class);
// Route::resource('ownerships', OwnershipController::class);
// Display a listing of the owners
Route::get('/owners', [OwnerController::class, 'index'])->name('owners.index')->middleware('can:view-owners');// List all owners
Route::get('/owners/create', [OwnerController::class, 'create'])->name('owners.create')->middleware('can:create-owners');// Show form to create a new owner
Route::post('/owners', [OwnerController::class, 'store'])->name('owners.store')->middleware('can:create-owners');// Store a new owner
Route::get('/owners/{owner}', [OwnerController::class, 'show'])->name('owners.show')->middleware('can:view-owners');// Show a single owner
Route::get('/owners/{owner}/edit', [OwnerController::class, 'edit'])->name('owners.edit')->middleware('can:update-owners');// Show form to edit an owner
Route::put('/owners/{owner}', [OwnerController::class, 'update'])->name('owners.update')->middleware('can:update-owners');// Update an owner
Route::delete('/owners/{owner}', [OwnerController::class, 'destroy'])->name('owners.destroy')->middleware('can:delete-owners');// Delete an owner



//ownerships routes
Route::get('/ownerships', [OwnershipController::class, 'index'])->name('ownerships.index')->middleware('can:view-ownerships');// List all ownerships
Route::get('/ownerships/create', [OwnershipController::class, 'create'])->name('ownerships.create')->middleware('can:create-ownerships');// Show form to create a new ownership
Route::post('/ownerships', [OwnershipController::class, 'store'])->name('ownerships.store')->middleware('can:create-ownerships');// Store a new ownership
Route::get('/ownerships/{ownership}', [OwnershipController::class, 'show'])->name('ownerships.show')->middleware('can:view-ownerships');// Show a single ownership
Route::get('/ownerships/{ownership}/edit', [OwnershipController::class, 'edit'])->name('ownerships.edit')->middleware('can:update-ownerships');// Show form to edit an ownership
Route::put('/ownerships/{ownership}', [OwnershipController::class, 'update'])->name('ownerships.update')->middleware('can:update-ownerships');// Update an ownership
Route::delete('/ownerships/{ownership}', [OwnershipController::class, 'destroy'])->name('ownerships.destroy')->middleware('can:delete-ownerships');// Delete an ownership
//approve ownership documents
Route::get('ownership-documents', [OwnershipController::class, 'documentsPending'])->name('admin.documents.pending')->middleware('can:view-ownership-documents');// List all pending documents
Route::post('ownership-documents/{document}/verify', [OwnershipController::class, 'verifyDocument'])->name('admin.documents.verify')->middleware('can:verify-ownership-documents');// Verify a document



//leases routes
Route::get('leases/{lease}', [LeaseController::class, 'show'])->name('leases.show')->middleware('can:view-leases');// show a single lease
Route::get('leases/{lease}/download', [LeaseController::class, 'download'])->name('leases.download')->middleware('can:download-leases');// download a single lease
Route::get('/leases', [LeaseController::class, 'index'])->name('leases.index')->middleware('can:view-leases');// list all leases
Route::get('/leases/{lease}/renew', [LeaseController::class, 'showRenewForm'])->name('leases.renew.form')->middleware('can:update-leases');// show renew lease form
Route::post('/leases/{lease}/renew', [LeaseController::class, 'renewLease'])->name('leases.renew')->middleware('can:update-leases');// renew a lease



// Landlord Reports
 Route::get('/landlord/report', [LandlordReportController::class, 'index'])->name('landlord.report')->middleware('can:view-landlord-reports');// Landlord reports dashboard
 Route::get('/my/report', [LandlordReportController::class, 'myReport']) ->name('landlords.my_report')->middleware('can:view-landlord-reports');// Landlord specific report


    
});




// Breeze auth routes
require __DIR__.'/auth.php';