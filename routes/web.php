<?php

use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SubscriptionPackageController;
use App\Http\Controllers\SubscriptionPackageBookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Models\Checkout;

Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/search', [UserController::class, 'search'])->name('search');
Route::get('/category/{id}', [UserController::class, 'categoryBooks'])->name('category.books');
Route::get('/show-subscription-package/{id}', [SubscriptionPackageBookController::class, 'show'])->name('subscription_package.show');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::post('payments/midtrans-notification', [CheckoutController::class, 'receive']);
// {id} -> patch dinamise
Route::middleware('IsGuest')->group(function () {
    Route::get('/register', function () {
        return view('register');
    })->name('register');

    Route::post('/register', [UserController::class, 'store'])->name('register.store');
    Route::get('/login', [UserController::class, 'index'])->name('login');
    Route::post('/login', [UserController::class, 'authentication'])->name('login.auth');
});

Route::middleware('IsLoggedIn')->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    // halaman profil / riwayat pembelian
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    // pembayaran paket langganan hanya untuk user yang sudah login
    Route::get('/subscription-packages/{id}/payment', [SubscriptionPackageController::class, 'payment'])->name('subscription-packages.payment');
    Route::post('/subscription-packages/{id}/payment', [SubscriptionPackageController::class, 'paymentSend'])->name('subscription-packages.payment.send');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('checkout/{id}/snap', [CheckoutController::class, 'show'])->name('checkout.show');

    Route::middleware('IsAdmin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/subscription-packages/export', [SubscriptionPackageController::class, 'export'])->name('subscription-packages.export');
        Route::get('/books/export', [BookController::class, 'export'])->name('books.export');
        Route::get('/book-categories/export', [BookCategoryController::class, 'export'])->name('book-categories.export');
        Route::resource('book-categories', BookCategoryController::class);
        Route::get(
            'subscription-package-books/{packageId}',
            [SubscriptionPackageBookController::class, 'index']
        )->name('subscription-package-books');
        Route::post(
            'subscription-package-books/{packageId}',
            [SubscriptionPackageBookController::class, 'store']
        )->name('subscription-package-books.store');
        Route::resource('subscription-packages', SubscriptionPackageController::class);
        Route::resource('books', BookController::class);
    });
});
