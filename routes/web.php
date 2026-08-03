<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductDiscountController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ShopController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Client\MinigameController;
use App\Http\Controllers\Client\UserController as ClientUserController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\SupplierRestockController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

     // Route::get('login',  [AuthController::class, 'showLogin'])->name('login');
     // Route::post('login', [AuthController::class, 'login'])->name('login.post');

     Route::middleware('admin')->group(function () {

          // Logout
          Route::post('logout', [AuthController::class, 'logout'])->name('logout');

          Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
          Route::get('dashboard/revenue', [DashboardController::class, 'revenue'])->name('dashboard.revenue');
          Route::get('dashboard/traffic', [DashboardController::class, 'traffic'])->name('dashboard.traffic');
          Route::get('/',         fn() => redirect()->route('admin.dashboard'));

          Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
          Route::get('orders/{id}/detail',    [OrderController::class, 'detail'])->name('orders.detail');
          Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus'])
               ->name('orders.updateStatus');
          Route::patch('orders/{id}/advance', [OrderController::class, 'advance'])
               ->name('orders.advance');
          Route::get('products', [ProductController::class, 'index'])->name('products.index');
          Route::middleware('owner')->group(function () {
               Route::patch('orders/{id}/cancel', [OrderController::class, 'cancel'])
                    ->name('orders.cancel');
               Route::resource('categories', CategoryController::class)
                    ->only(['index', 'store', 'update', 'destroy'])
                    ->parameters(['categories' => 'id']);

               Route::delete('products/images/{imageId}', [ProductController::class, 'deleteImage'])
                    ->name('products.deleteImage');
               Route::resource('products', ProductController::class)
                    ->only(['store', 'update', 'destroy', 'create', 'edit'])
                    ->parameters(['products' => 'id']);

               Route::patch('products/{id}/restock', [ProductController::class, 'restock'])
                    ->name('products.restock');
               Route::get('products/restock-requests', [ProductController::class, 'restockRequests'])
                    ->name('products.restockRequests');
               Route::patch('products/restock-requests/{id}/receive', [ProductController::class, 'receiveStock'])
                    ->name('products.restockRequests.receive');
               Route::patch('products/restock-requests/{id}/reject', [ProductController::class, 'rejectReceipt'])
                    ->name('products.restockRequests.reject');

               Route::resource('manufacturers', ManufacturerController::class)
                    ->only(['index', 'store', 'update', 'destroy'])
                    ->parameters(['manufacturers' => 'id']);

               Route::resource('discounts', DiscountController::class)
                    ->only(['index', 'store', 'update', 'destroy'])
                    ->parameters(['discounts' => 'id']);

               Route::resource('product-discounts', ProductDiscountController::class)
                    ->only(['index', 'store', 'update', 'destroy'])
                    ->parameters(['product-discounts' => 'id']);
               Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
               Route::get('employees/{id}/detail', [EmployeeController::class, 'detail'])->name('employees.detail');
          });

          Route::middleware('admin.only')->group(function () {

               Route::post('employees',       [EmployeeController::class, 'store'])->name('employees.store');
               Route::put('employees/{id}',   [EmployeeController::class, 'update'])->name('employees.update');
               Route::delete('employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

               Route::get('users', [UserController::class, 'index'])->name('users.index');
               Route::get('users/{id}/detail', [UserController::class, 'detail'])->name('users.detail');
               Route::patch('users/{id}/toggle-active', [UserController::class, 'toggleActive'])
                    ->name('users.toggleActive');
               Route::delete('users/{id}', [UserController::class, 'destroy'])
                    ->name('users.destroy');
          });
     });
}); // end prefix('admin')

Route::prefix('')->name('client.')->group(function () {

     Route::get('/', [HomeController::class, 'index'])->name('home');

     Route::get('dang-nhap',  [ClientAuthController::class, 'showLogin'])->name('login');
     Route::post('dang-nhap', [ClientAuthController::class, 'login'])->name('login.post');
     Route::get('/register/verify', [ClientAuthController::class, 'showVerifyForm'])->name('register.verify');
     Route::post('/forgot-password', [ClientAuthController::class, 'forgotPassword'])->name('forgot-password.post');
     // 2 API Xử lý AJAX gửi & nhận mã OTP (Mới)
     Route::post('/otp/send', [ClientAuthController::class, 'sendOtp'])->name('otp.send');
     Route::post('/otp/verify', [ClientAuthController::class, 'verifyOtp'])->name('otp.verify');

     Route::get('dang-ky',    [ClientAuthController::class, 'showRegister'])->name('register');
     Route::post('dang-ky',   [ClientAuthController::class, 'register'])->name('register.post');
     Route::post('dang-xuat', [ClientAuthController::class, 'logout'])->name('logout');

     Route::get('trang-ca-nhan/{userID}', [ClientUserController::class, 'show'])->name('profile');
     Route::put('trang-ca-nhan/{userID}', [ClientUserController::class, 'update'])->name('profile.update');
     Route::put('trang-ca-nhan/{userID}/mat-khau', [ClientUserController::class, 'updatePassword'])->name('profile.password');
     Route::get('trang-ca-nhan/{userID}/don-hang', [ClientUserController::class, 'orders'])->name('profile.orders');
     Route::get('trang-ca-nhan/don-hang/{orderID}', [ClientUserController::class, 'orderDetails'])->name('profile.orderDetails');
     Route::get('trang-ca-nhan/{userID}/ma-giam-gia', [ClientUserController::class, 'vouchers'])->name('profile.vouchers');

     Route::get('vong-quay-may-man',       [MinigameController::class, 'index'])->name('minigame.index');
     Route::post('vong-quay-may-man/quay', [MinigameController::class, 'spin'])->name('minigame.spin');
     Route::post('vong-quay-may-man/lay-ma', [MinigameController::class, 'claim'])->name('minigame.claim');

     Route::post('yeu-thich/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
     Route::get('yeu-thich',         [WishlistController::class, 'index'])->name('wishlist.index');

     Route::get('san-pham',               [ShopController::class, 'index'])->name('shop');
     Route::get('danh-muc/{categoryId}',  [ShopController::class, 'index'])->name('shop.category');

     Route::get('chi-tiet/{id}', [ClientProductController::class, 'show'])->name('product.show');

     Route::get('gio-hang',                [CartController::class, 'index'])->name('cart');
     Route::post('gio-hang/them',          [CartController::class, 'add'])->name('cart.add');
     Route::patch('gio-hang/{variantId}',  [CartController::class, 'update'])->name('cart.update');
     Route::delete('gio-hang/{variantId}', [CartController::class, 'remove'])->name('cart.remove');
     Route::get('gio-hang/so-luong',       [CartController::class, 'count'])->name('cart.count');

     Route::post('dat-hang',                    [ClientOrderController::class, 'proceedToShipping'])->name('checkout.proceed');
     Route::get('thanh-toan/thong-tin',         [ClientOrderController::class, 'showShipping'])->name('checkout.shipping');
     Route::post('thanh-toan/thong-tin',        [ClientOrderController::class, 'submitShipping'])->name('checkout.shipping.post');
     Route::get('thanh-toan/phuong-thuc',       [ClientOrderController::class, 'showPayment'])->name('checkout.payment');
     Route::post('thanh-toan/hoan-thanh',       [ClientOrderController::class, 'finalize'])->name('checkout.finalize');
     Route::post('thanh-toan/ma-giam-gia',      [ClientOrderController::class, 'applyDiscount'])->name('checkout.discount.apply');
     Route::delete('thanh-toan/ma-giam-gia',    [ClientOrderController::class, 'removeDiscount'])->name('checkout.discount.remove');
});
Route::get('nha-cung-cap/xac-nhan-nhap-hang/{token}', [SupplierRestockController::class, 'show'])
     ->name('supplier.restock.show');

Route::post('nha-cung-cap/xac-nhan-nhap-hang/{token}', [SupplierRestockController::class, 'confirm'])
     ->name('supplier.restock.confirm');

Route::post('nha-cung-cap/xac-nhan-nhap-hang/{token}/tu-choi', [SupplierRestockController::class, 'decline'])
     ->name('supplier.restock.decline');

