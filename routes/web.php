<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CancellationController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingManageController;

// ─── Public Routes ────────────────────────────────────────────────
Route::get('/', [BookingController::class, 'index'])->name('home');
Route::post('/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.check');
Route::get('/booking/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/checkout', [BookingController::class, 'storeSession'])->name('booking.store-session');
Route::get('/terms', fn() => view('terms'))->name('terms');
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::get('/booking/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('booking.confirmation');

// ─── PayPal ───────────────────────────────────────────────────────
Route::get('/payment/paypal', [PayPalController::class, 'redirect'])->name('paypal.redirect');
Route::get('/payment/success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('/payment/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

// ─── Cancellation ────────────────────────────────────────────────
Route::get('/cancel', [CancellationController::class, 'index'])->name('cancellation.index');
Route::post('/cancel', [CancellationController::class, 'lookup'])->name('cancellation.lookup');
Route::post('/cancel/confirm', [CancellationController::class, 'confirm'])->name('cancellation.confirm');
Route::get('/cancel/done', [CancellationController::class, 'done'])->name('cancellation.done');

// ─── Admin Auth ───────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Bookings table
        Route::get('/bookings', [BookingManageController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/past', [BookingManageController::class, 'past'])->name('bookings.past');
        Route::get('/bookings/{booking}', [BookingManageController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{booking}/edit', [BookingManageController::class, 'edit'])->name('bookings.edit');
        Route::put('/bookings/{booking}', [BookingManageController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{booking}', [BookingManageController::class, 'destroy'])->name('bookings.destroy');
        Route::post('/bookings/{booking}/parking-code', [BookingManageController::class, 'sendParkingCode'])->name('bookings.parking-code');
        Route::post('/bookings/{booking}/cancel', [BookingManageController::class, 'cancel'])->name('bookings.cancel');

        // Calendar
        Route::get('/calendar', [DashboardController::class, 'calendar'])->name('calendar');
        Route::get('/calendar/data', [DashboardController::class, 'calendarData'])->name('calendar.data');
    });
});
