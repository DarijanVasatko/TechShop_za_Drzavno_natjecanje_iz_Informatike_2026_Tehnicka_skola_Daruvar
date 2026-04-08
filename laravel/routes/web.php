<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProizvodController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\CountryTownController;
use App\Http\Controllers\FakePayController;
use App\Http\Controllers\PcBuilderController;
use App\Http\Controllers\PostCodeController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\PageController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PromoKodController;
use App\Http\Controllers\Admin\AdminRecenzijaController;
use App\Http\Controllers\RecenzijaController;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DriverOrderController;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::resource('users', AdminUserController::class)->only(['index', 'show']);
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);

        Route::put('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
        Route::put('/orders/{order}/close', [AdminOrderController::class, 'close'])->name('orders.close');
        Route::post('/promo-kodovi', [PromoKodController::class, 'store'])->name('promo-kodovi.store');
        Route::get('/promo-kodovi', [PromoKodController::class, 'index'])->name('promo-kodovi.index');

        Route::get('/recenzije', [AdminRecenzijaController::class, 'index'])->name('recenzije.index');
        Route::put('/recenzije/{recenzija}/approve', [AdminRecenzijaController::class, 'approve'])->name('recenzije.approve');
        Route::delete('/recenzije/{recenzija}/reject', [AdminRecenzijaController::class, 'reject'])->name('recenzije.reject');

        Route::patch('/admin/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
    });



Route::get('/admin-login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin-login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin-logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::get('/', [ProizvodController::class, 'home'])->name('index.index');
Route::get('/proizvodi', [ProizvodController::class, 'list'])->name('proizvodi.index');
Route::get('/proizvod/{id}', [ProizvodController::class, 'show'])->name('proizvod.show');
Route::get('/kategorija/{id}', [ProizvodController::class, 'kategorija'])->name('proizvodi.kategorija');

Route::get('/o-nama', [PageController::class, 'oNama'])->name('o-nama');

Route::get('/ajax/proizvodi', [ProizvodController::class, 'ajaxSearch'])->middleware('throttle:60,1')->name('proizvodi.search');
Route::post('/ajax/proizvodi/by-ids', [ProizvodController::class, 'getByIds'])->name('proizvodi.byIds');
Route::get('/countries/search', [CountryController::class, 'search'])->name('countries.search');
Route::get('/towns/search', [CountryTownController::class, 'search'])->name('towns.search');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware(['auth', 'user.only'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/address/{id}/default', [ProfileController::class, 'setDefaultAddress'])->name('profile.address.default');
    Route::post('/profile/address/add', [ProfileController::class, 'addAddress'])->name('profile.address.add');
    Route::delete('/profile/address/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/apply-promo', [PromoCodeController::class, 'apply'])->name('promo.apply');
    Route::post('/checkout/remove-promo', [PromoCodeController::class, 'remove'])->name('promo.remove');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    Route::post('/recenzija/{proizvod}', [RecenzijaController::class, 'store'])->name('recenzija.store');

    Route::get('/payments/fakepay/{payment}', [FakePayController::class, 'show'])->name('payments.fakepay');
    Route::post('/payments/fakepay/{payment}/process', [FakePayController::class, 'process'])->name('payments.fakepay.process');
    Route::get('/payments/fakepay/{payment}/callback', [FakePayController::class, 'callback'])->name('payments.fakepay.callback');

    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
});

Route::get('/post-codes/lookup', [PostCodeController::class, 'lookup'])->name('postcodes.lookup');
Route::get('/post-codes/lookup-by-postal', [PostCodeController::class, 'lookupByPostalCode'])->name('postcodes.lookupByPostal');

Route::prefix('pc-builder')->name('pc-builder.')->group(function () {
    Route::get('/', [PcBuilderController::class, 'index'])->name('index');
    Route::get('/new', [PcBuilderController::class, 'newConfiguration'])->name('new');
    Route::get('/step/{step}', [PcBuilderController::class, 'getStep'])->name('step');
    Route::post('/add-component', [PcBuilderController::class, 'addComponent'])->name('add-component');
    Route::patch('/update-quantity', [PcBuilderController::class, 'updateQuantity'])->name('update-quantity');
    Route::delete('/remove-component/{typeId}', [PcBuilderController::class, 'removeComponent'])->name('remove-component');
    Route::get('/configuration', [PcBuilderController::class, 'getConfiguration'])->name('configuration');
    Route::get('/compatible-products/{typeId}', [PcBuilderController::class, 'getCompatibleProducts'])->name('compatible');
    Route::post('/add-to-cart', [PcBuilderController::class, 'addAllToCart'])->name('add-to-cart');
    Route::post('/ai-recommendation', [PcBuilderController::class, 'aiRecommendation'])->name('ai-recommendation');
    Route::post('/apply-recommendation', [PcBuilderController::class, 'applyRecommendation'])->name('apply-recommendation');

    Route::middleware(['auth', 'user.only'])->group(function () {
        Route::post('/save', [PcBuilderController::class, 'saveConfiguration'])->name('save');
        Route::get('/saved', [PcBuilderController::class, 'savedConfigurations'])->name('saved');
        Route::get('/load/{id}', [PcBuilderController::class, 'loadConfiguration'])->name('load');
        Route::delete('/delete/{id}', [PcBuilderController::class, 'deleteConfiguration'])->name('delete');
    });
});

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::post('/api/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/driver/orders', [DriverOrderController::class, 'index']);
    Route::get('/api/driver/orders/{id}', [DriverOrderController::class, 'getOrderDetails']);
    Route::post('/api/driver/orders/{id}/delivered', [DriverOrderController::class, 'markDelivered']);
    Route::post('/api/driver/orders/{id}/not-delivered', [DriverOrderController::class, 'markNotDelivered']);
});

require __DIR__ . '/auth.php';