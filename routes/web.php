<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MenuController::class, 'index'])->name('menu.index');
Route::post('/bahasa', [MenuController::class, 'setLocale'])->name('locale.update');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/{menu}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{menu}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{menu}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/pembayaran/{order:public_id}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/pembayaran/{order:public_id}/berhasil', [PaymentController::class, 'succeed'])->name('payment.succeed');
Route::post('/pembayaran/{order:public_id}/batal', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::get('/pembayaran/{order:public_id}/gagal', [PaymentController::class, 'failed'])->name('payment.failed');
Route::get('/pesanan/{order:public_id}', [ReceiptController::class, 'show'])->name('receipt.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('dapur')->name('kitchen.')->group(function () {
    Route::get('/', [KitchenController::class, 'index'])->name('index');
    Route::patch('/{order}/status', [KitchenController::class, 'update'])->name('status');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('menus', AdminMenuController::class)->except('show');
});
