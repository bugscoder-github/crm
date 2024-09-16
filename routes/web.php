<?php

use App\Http\Controllers\ContactUsController;
// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadCommentController;
use App\Http\Controllers\MetadataController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TemplateServiceController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SharedController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductServiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductServiceInOutController;
use App\Http\Controllers\SupplierController;
// use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Middleware\BackendRedirect;
use Illuminate\Support\Facades\Cache;

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

Route::get('changelog', function() {
	return Inertia::render('Changelog', [
		'content' => file_get_contents(base_path('README.md')),
	]);
});

Route::get('/',  [ContactUsController::class, 'contactUsCreate'])->name('contactus.create');
Route::post('/', [ContactUsController::class, 'contactUsStore'])->name('contactus.store');
Route::prefix('_backend')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () { })->middleware(BackendRedirect::class);

    Route::get('dashboard', function () {
    	return Inertia::render('Dashboard');
    })->name('dashboard');
	
    Route::resources(['user' => UserController::class]);

    Route::get('quotation/{quotation}/pdf', [QuotationController::class, 'pdf'])->name('quotation.pdf');
	Route::resources(['team' => TeamController::class]);

	Route::get('customer/search', [CustomerController::class, 'search'])->name('customer.search');
    Route::resources(['customer' => CustomerController::class]);

    Route::resources(['lead' => LeadController::class]);
	Route::resources(['lead.comment' => LeadCommentController::class]);
	Route::resources(['leadComment'  => LeadCommentController::class]);
	Route::post('lead/{lead}/done', [LeadController::class, 'leadMarkDone'])->name('lead.done');
	Route::post('lead/{lead}/reopen', [LeadController::class, 'leadReopen'])->name('lead.reopen');

    Route::get('quotation/{quotation}/pdf', [QuotationController::class, 'pdf'])->name('quotation.pdf');

	Route::post('invoice/{invoice}/paid', [InvoiceController::class, 'invoiceMarkPaid'])->name('invoice.paid');
	Route::post('invoice/{invoice}/approved', [InvoiceController::class, 'invoiceMarkApproved'])->name('invoice.approved');
	Route::get('invoice/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoice.pdf');

	Route::resources(['metadata' => MetadataController::class]);

	Route::get('/notifications', function () {
		return response()->stream(function () {
			if (isOwner()) {
				$newLead = Cache::get('newLead_0');
				if ($newLead) {
					echo 'data: '.json_encode($newLead)."\n\n";
					flush();
				}
			}
		}, 200, [
			'Content-Type' => 'text/event-stream',
			'Cache-Control' => 'no-cache',
			'Connection' => 'keep-alive',
		]);
	});


	Route::get('tax/datatables', [TaxController::class, 'datatables'])->name('tax.datatables');
	Route::get('service/datatables', [ServiceController::class, 'datatables'])->name('service.datatables');
	Route::get('template/datatables', [TemplateServiceController::class, 'datatables'])->name('template.service.datatables');

    Route::resources(['service' => ServiceController::class]);
    Route::resources(['tax' => TaxController::class]);
    Route::resources(['template-services' => TemplateServiceController::class]);
    Route::resources(['quotation' => QuotationController::class]);
    Route::resources(['invoice' => InvoiceController::class]);

	Route::get('template/{id}/services', [TemplateServiceController::class, 'services'])->name('template.services.retrieve');

	Route::post('estimate', [SharedController::class, 'estimate'])->name('shared.estimate');

    Route::resources(['category' => CategoryController::class]);
    Route::resources(['location' => LocationController::class]);
    Route::resources(['productService' => ProductServiceController::class]);
    Route::resources(['productServiceInOut' => ProductServiceInOutController::class]);
    Route::resources(['supplier' => SupplierController::class]);
});



require __DIR__.'/auth.php';
