<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\GadgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerBundleController;
use App\Http\Controllers\CustomerGadgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RewardsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/combos', [CustomerBundleController::class, 'index'])->name('customer.bundles.index');
    Route::get('/combos/{bundle}', [CustomerBundleController::class, 'show'])->name('customer.bundles.show');
    Route::get('/browse-gadgets', [CustomerGadgetController::class, 'index'])->name('customer.gadgets.index');
    Route::get('/browse-gadgets/{gadget}', [CustomerGadgetController::class, 'show'])->name('customer.gadgets.show');
    Route::get('/rentals/create/{gadget}', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [RentalController::class, 'store'])->name('customer.rentals.store');
    Route::get('/my-rentals', [RentalController::class, 'index'])->name('customer.rentals.index');
    Route::get('/rewards', [RewardsController::class, 'index'])->name('customer.rewards.index');
    Route::get('/my-inquiries', [InquiryController::class, 'index'])->name('customer.inquiries.index');
    Route::get('/my-rentals/{rental}', [RentalController::class, 'show'])->name('customer.rentals.show');
    Route::get('/rentals/{rental}/qr', [RentalController::class, 'showQr'])->name('customer.rentals.qr');
    Route::patch('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('customer.rentals.cancel');
    Route::post('/rentals/{rental}/review', [ReviewController::class, 'store'])->name('customer.rentals.review.store');
    Route::get('/rentals/{rental}/payment', [RentalController::class, 'paymentCreate'])->name('customer.rentals.payment.create');
    Route::post('/rentals/{rental}/payment', [RentalController::class, 'paymentStore'])->name('customer.rentals.payment.store');
    Route::get('/chat/history', [ChatController::class, 'index'])->name('chat.history');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
});

Route::resource('categories', CategoryController::class)->middleware(['auth']);
Route::resource('gadgets', GadgetController::class)->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/admin/scan', [RentalController::class, 'scan'])->name('admin.scan');
    Route::post('/admin/scan/lookup', [RentalController::class, 'lookupByToken'])->name('admin.scan.lookup');
    Route::get('/admin/rentals', [RentalController::class, 'adminIndex'])->name('admin.rentals.index');
    Route::get('/admin/rentals/{rental}', [RentalController::class, 'show'])->name('admin.rentals.show');
    Route::get('/admin/bundles', [BundleController::class, 'index'])->name('admin.bundles.index');
    Route::get('/admin/bundles/create', [BundleController::class, 'create'])->name('admin.bundles.create');
    Route::post('/admin/bundles', [BundleController::class, 'store'])->name('admin.bundles.store');
    Route::get('/admin/bundles/{bundle}/edit', [BundleController::class, 'edit'])->name('admin.bundles.edit');
    Route::put('/admin/bundles/{bundle}', [BundleController::class, 'update'])->name('admin.bundles.update');
    Route::delete('/admin/bundles/{bundle}', [BundleController::class, 'destroy'])->name('admin.bundles.destroy');
    Route::post('/admin/rentals/{rental}/confirm-handover', [RentalController::class, 'confirmHandover'])->name('admin.scan.confirm-handover');
    Route::patch('/admin/rentals/{rental}/approve', [RentalController::class, 'approve'])->name('admin.rentals.approve');
    Route::patch('/admin/rentals/{rental}/reject', [RentalController::class, 'reject'])->name('admin.rentals.reject');
    Route::patch('/admin/rentals/{rental}/payment/collect', [RentalController::class, 'collectPayment'])->name('admin.rentals.payment.collect');
    Route::patch('/admin/rentals/{rental}/return', [RentalController::class, 'markReturned'])->name('admin.rentals.return');
    Route::patch('/admin/rentals/{rental}/shipping', [RentalController::class, 'updateShipping'])->name('admin.rentals.updateShipping');
    Route::patch('/admin/rentals/{rental}/payment/verify', [RentalController::class, 'verifyPayment'])->name('admin.rentals.payment.verify');
    Route::patch('/admin/rentals/{rental}/payment/reject', [RentalController::class, 'rejectPayment'])->name('admin.rentals.payment.reject');
    Route::get('/admin/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/admin/customers/{user}', [CustomerController::class, 'show'])->name('admin.customers.show');
    Route::patch('/admin/customers/{user}/toggle-block', [CustomerController::class, 'toggleBlock'])->name('admin.customers.toggleBlock');
    Route::get('/admin/reports/rentals-csv', [ReportController::class, 'rentalsCsv'])->name('admin.reports.rentals-csv');
    Route::get('/admin/reports/revenue-pdf', [ReportController::class, 'revenuePdf'])->name('admin.reports.revenue-pdf');
    Route::get('/admin/inquiries', [InquiryController::class, 'adminIndex'])->name('admin.inquiries.index');
    Route::get('/admin/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('admin.inquiries.show');
    Route::patch('/admin/inquiries/{inquiry}/reply', [InquiryController::class, 'reply'])->name('admin.inquiries.reply');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::post('/profile/id-document', [ProfileController::class, 'updateIdDocument'])->name('profile.id-document.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
