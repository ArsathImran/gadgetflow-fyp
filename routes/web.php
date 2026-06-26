<?php

use App\Http\Controllers\GadgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerGadgetController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/browse-gadgets', [CustomerGadgetController::class, 'index'])->name('customer.gadgets.index');
    Route::get('/browse-gadgets/{gadget}', [CustomerGadgetController::class, 'show'])->name('customer.gadgets.show');
    Route::get('/rentals/create/{gadget}', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [RentalController::class, 'store'])->name('customer.rentals.store');
    Route::get('/my-rentals', [RentalController::class, 'index'])->name('customer.rentals.index');
    Route::get('/rentals/{rental}/payment', [RentalController::class, 'paymentCreate'])->name('customer.rentals.payment.create');
    Route::post('/rentals/{rental}/payment', [RentalController::class, 'paymentStore'])->name('customer.rentals.payment.store');
});

Route::resource('categories', CategoryController::class)->middleware(['auth']);
Route::resource('gadgets', GadgetController::class)->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/admin/rentals', [RentalController::class, 'adminIndex'])->name('admin.rentals.index');
    Route::patch('/admin/rentals/{rental}/approve', [RentalController::class, 'approve'])->name('admin.rentals.approve');
    Route::patch('/admin/rentals/{rental}/reject', [RentalController::class, 'reject'])->name('admin.rentals.reject');
    Route::patch('/admin/rentals/{rental}/payment/verify', [RentalController::class, 'verifyPayment'])->name('admin.rentals.payment.verify');
    Route::patch('/admin/rentals/{rental}/payment/reject', [RentalController::class, 'rejectPayment'])->name('admin.rentals.payment.reject');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
