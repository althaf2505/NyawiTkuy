<?php

use App\Http\Controllers\Admin\AdminBuyerController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminQuotationController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminShipmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerProfileController;
use App\Http\Controllers\Buyer\BuyerQuotationController;
use App\Http\Controllers\Buyer\BuyerShipmentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/products', [PublicController::class, 'products'])->name('products.index');
Route::get('/products/{product:slug}', [PublicController::class, 'product'])->name('products.show');
Route::view('/about', 'public.about')->name('about');
Route::view('/export-process', 'public.export-process')->name('export-process');
Route::view('/contact', 'public.contact')->name('contact');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'createLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Buyer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->as('buyer.')->group(function (): void {
    Route::get('/dashboard', [WorkspaceController::class, 'buyerDashboard'])->name('dashboard');

    // Quotations / RFQs
    Route::get('/quotations', [BuyerQuotationController::class, 'index'])->name('quotations.index');
    Route::get('/quotations/create', [BuyerQuotationController::class, 'create'])->name('quotations.create');
    Route::post('/quotations', [BuyerQuotationController::class, 'store'])->name('quotations.store');
    Route::get('/quotations/{quotation}', [BuyerQuotationController::class, 'show'])->name('quotations.show');
    Route::post('/quotations/{quotation}/respond', [BuyerQuotationController::class, 'respond'])->name('quotations.respond');

    // Orders
    Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [BuyerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/payment', [BuyerOrderController::class, 'submitPayment'])->name('orders.payment');

    // Shipments
    Route::get('/shipments', [BuyerShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/{shipment}', [BuyerShipmentController::class, 'show'])->name('shipments.show');

    // Profile
    Route::get('/profile', [BuyerProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [BuyerProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [BuyerProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Admin / Seller Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function (): void {
    Route::get('/dashboard', [WorkspaceController::class, 'adminDashboard'])->name('dashboard');

    // Products Management
    Route::resource('products', AdminProductController::class)->except(['show']);

    // Quotations / RFQ Management
    Route::get('/quotations', [AdminQuotationController::class, 'index'])->name('quotations.index');
    Route::get('/quotations/{quotation}', [AdminQuotationController::class, 'show'])->name('quotations.show');
    Route::post('/quotations/{quotation}/offer', [AdminQuotationController::class, 'sendOffer'])->name('quotations.offer');
    Route::post('/quotations/{quotation}/respond-counter', [AdminQuotationController::class, 'respondCounter'])->name('quotations.respond-counter');

    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Payments Management
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');

    // Shipments Management
    Route::get('/shipments', [AdminShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [AdminShipmentController::class, 'create'])->name('shipments.create');
    Route::post('/shipments', [AdminShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/shipments/{shipment}', [AdminShipmentController::class, 'show'])->name('shipments.show');
    Route::put('/shipments/{shipment}/status', [AdminShipmentController::class, 'updateStatus'])->name('shipments.status');

    // Buyers Management
    Route::get('/buyers', [AdminBuyerController::class, 'index'])->name('buyers.index');
    Route::get('/buyers/{buyer}', [AdminBuyerController::class, 'show'])->name('buyers.show');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [AdminReportController::class, 'exportCsv'])->name('reports.export');
});
