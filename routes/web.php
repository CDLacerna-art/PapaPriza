<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerAuthController;

// Customer orders
Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('auth')
    ->name('orders.store');

Route::get('/my-orders', [CustomerOrderController::class, 'index'])
    ->middleware('auth')
    ->name('customer.orders');

Route::post('/customer/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])
    ->middleware('auth')
    ->name('customer.orders.cancel');

// Home, menu, and contact pages
Route::get('/', function () {
    return view('home');
});

Route::get('/menu', function () {
    return view('menu');
});

Route::get('/contact', function () {
    return view('contact');
})->name('contact.show');

// Submit contact form
Route::post('/contact', [ContactController::class, 'submit'])
    ->name('contact.submit');

// Admin login and logout
Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('admin.login.submit');

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->name('admin.logout');

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    
   // Admin-only routes
    Route::get('/admin/orders', [AdminController::class, 'index'])
        ->name('admin.orders');

    // Update order status
    Route::post('/admin/orders/{id}/status', [AdminController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');

    // Delete order
    Route::delete('/admin/orders/{id}', [AdminController::class, 'destroy'])
        ->name('admin.orders.destroy');

    // Admin activity logs
    Route::get('/admin/logs', [\App\Http\Controllers\AdminLogController::class, 'index'])
        ->name('admin.logs');

    // Admin user management (CRUD)
    Route::get('/admin/users', [\App\Http\Controllers\AdminUserController::class, 'index'])
        ->name('admin.users.index');
    Route::get('/admin/users/create', [\App\Http\Controllers\AdminUserController::class, 'create'])
        ->name('admin.users.create');
    Route::post('/admin/users', [\App\Http\Controllers\AdminUserController::class, 'store'])
        ->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [\App\Http\Controllers\AdminUserController::class, 'edit'])
        ->name('admin.users.edit');
    Route::put('/admin/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'update'])
        ->name('admin.users.update');
    Route::delete('/admin/users/{id}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])
        ->name('admin.users.destroy');
});

// Customer registration
Route::get('/customer/register', [CustomerAuthController::class, 'showRegister'])
    ->name('customer.register');

Route::post('/customer/register', [CustomerAuthController::class, 'register'])
    ->name('customer.register.submit');

// Customer login
Route::get('/customer/login', [CustomerAuthController::class, 'showLogin'])
    ->name('customer.login');

Route::post('/customer/login', [CustomerAuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('customer.login.submit');

// Customer logout
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])
    ->name('customer.logout');