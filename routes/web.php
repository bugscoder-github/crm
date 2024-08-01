<?php

use App\Http\Controllers\ContactUsController;
// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadCommentController;
use App\Http\Controllers\QuotationController;
// use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Middleware\BackendRedirect;

Route::get('/debug', function() {
	return Inertia::render('debug', [
		// 'config' => config('custom'),
		// 'roles' => getRoles(),
		// 'userlist' => User::getUserWithRole()
	]);
});

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::prefix('_backend')->get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/',  [ContactUsController::class, 'contactUsCreate'])->name('contactus.create');
Route::post('/', [ContactUsController::class, 'contactUsStore'])->name('contactus.store');
Route::prefix('_backend')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () { })->middleware(BackendRedirect::class);

    Route::get('dashboard', function () {
    	return Inertia::render('Dashboard');
    })->name('dashboard');
	
    Route::resources(['user' => UserController::class]);
    Route::resources(['quotation' => QuotationController::class]);

	Route::get('customer/search', [CustomerController::class, 'search'])->name('customer.search');
    Route::resources(['customer' => CustomerController::class]);

    Route::resources(['lead' => LeadController::class]);
	Route::resources(['lead.comment' => LeadCommentController::class]);
	Route::resources(['leadComment'  => LeadCommentController::class]);
	Route::post('lead/{id}/done', [LeadController::class, 'leadMarkDone'])->name('lead.done');
	Route::post('lead/{id}/reopen', [LeadController::class, 'leadReopen'])->name('lead.reopen');
});



require __DIR__.'/auth.php';
