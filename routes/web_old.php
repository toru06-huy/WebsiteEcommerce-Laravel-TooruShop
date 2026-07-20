<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\{
    DashboardController,
    CategoryController,
    ProductController,
    ManufacturerController,
    EmployeeController,
    UserController,
    OrderController
};

Route::get(
    '/',
    function () {
        return view('auth.login');
    }
);
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::middleware('admin')->group(function () {

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories',   CategoryController::class);

        Route::resource('products',     ProductController::class);
        Route::resource('manufacturers', ManufacturerController::class);
        Route::resource('orders',       OrderController::class)->only(['index']);
        Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Admin only
        Route::middleware('admin.only')->group(function () {
            Route::resource('employees', EmployeeController::class);
            Route::resource('users',     UserController::class)->only(['index', 'destroy']);
            Route::patch('users/{id}/toggle', [UserController::class, 'toggleActive'])->name('users.toggleActive');
        });
    });
});
