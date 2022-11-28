<?php

use App\Http\Controllers\AuthController;
use App\Http\Livewire\Client\Checkout;
use App\Http\Livewire\Client\CheckoutPayment;
use App\Http\Livewire\Client\HomeUser;
use App\Http\Livewire\Client\Panduan;
use App\Http\Livewire\Client\PanduanAdmin;
use App\Http\Livewire\Client\ProductDetail;
use App\Http\Livewire\Client\ShoppingCart;
use App\Http\Livewire\CrudGenerator;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Master\BannerController;
use App\Http\Livewire\Master\CategoryController;
use App\Http\Livewire\Master\OngkirController;
use App\Http\Livewire\Master\PaymentMethodController;
use App\Http\Livewire\Order\OrderController;
use App\Http\Livewire\ProductController;
use App\Http\Livewire\Settings\Menu;
use App\Http\Livewire\UpdateProfile;
use App\Http\Livewire\UserManagement\Permission;
use App\Http\Livewire\UserManagement\PermissionRole;
use App\Http\Livewire\UserManagement\Role;
use App\Http\Livewire\UserManagement\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', HomeUser::class)->name('home.user');
Route::get('/product-detail/{product_id}', ProductDetail::class)->name('product-detail');
Route::post('login', [AuthController::class, 'login'])->name('admin.login');
Route::get('logout', [AuthController::class, 'logout'])->name('user-logout');
Route::get('panduan-pengguna', Panduan::class)->name('user.panduan');

Route::group(['middleware' => ['auth:sanctum', 'verified', 'user.authorization']], function () {
    // Crud Generator Route
    Route::get('/crud-generator', CrudGenerator::class)->name('crud.generator');

    // user management
    Route::get('/permission', Permission::class)->name('permission');
    Route::get('/permission-role/{role_id}', PermissionRole::class)->name('permission.role');
    Route::get('/role', Role::class)->name('role');
    Route::get('/user', User::class)->name('user');
    Route::get('/menu', Menu::class)->name('menu');

    // App Route
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Master data
    Route::get('/category', CategoryController::class)->name('category');
    Route::get('/payment-method', PaymentMethodController::class)->name('payment-method');
    Route::get('/products', ProductController::class)->name('products');
    Route::get('/banners', BannerController::class)->name('banners');
    Route::get('/pengaturan-ongkir', OngkirController::class)->name('pengaturan-ongkir');

    // Order Data
    Route::get('/order', OrderController::class)->name('order');
    Route::get('/update-profile', UpdateProfile::class)->name('update-profile');

    // client
    Route::get('/keranjang-saya', ShoppingCart::class)->name('cart');
    Route::get('/checkout/{order_id?}', Checkout::class)->name('checkout');
    Route::get('/selesaikan-pesanan/{order_id}', CheckoutPayment::class)->name('checkout.payment');
    Route::get('panduan-admin', PanduanAdmin::class)->name('admin.panduan');
});
