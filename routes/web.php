<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomersReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
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


require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('invoices', InvoicesController::class);
    Route::get('/edit_invoices/{id}', [InvoicesController::class, 'edit']);
    Route::post('/edit_invoices/{id}', [InvoicesController::class, 'update']);

    Route::post('/archive', [InvoicesController::class, 'archive'])->name('invoices.archive');

    Route::get('/status_show/{id}', [InvoicesController::class, 'show'])->name('invoices.status_show');
    Route::post('/status_update/{id}', [InvoicesController::class, 'statusUpdate'])->name('invoices.status_update');
    // Invoices Status 
    Route::get('Invoices_Paid', [InvoicesController::class, 'PaidInvoices'])->name('invoices.PaidInvoices');
    Route::get('Invoices_Partial', [InvoicesController::class, 'PartialInvoices'])->name('invoices.PartialInvoices');
    Route::get('Invoices_Unpaid', [InvoicesController::class, 'UnpaidInvoices'])->name('invoices.UnpaidInvoices');
    // Invoices Archive
    Route::get('Invoices_Archive', [InvoicesController::class, 'ArchiveInvoices'])->name('invoices.ArchiveInvoices');
    // Invoices Restore
    Route::post('Invoices_Restore', [InvoicesController::class, 'RestoreInvoices'])->name('invoices.RestoreInvoices');
    // Invoices Archive Destory
    Route::delete('Invoices_Archive_Destory', [InvoicesController::class, 'ArchiveDestory'])->name('invoices.ArchiveDestory');
    // Print Invoices
    Route::get('Print_invoice/{id}', [InvoicesController::class, 'Print_invoices'])->name('invoices.Print_invoices');
    // Export Invoices
    Route::get('export', [InvoicesController::class, 'export'])->name('invoices.export');
    // Invoices Report
    Route::get('invoices_report', [InvoicesReportController::class, 'index'])->name('invoices.report');
    // Search Invoices
    Route::post('Search_invoices', [InvoicesReportController::class, 'search_invoices'])->name('Search_invoices');
    // Customers Report
    Route::get('customers_report', [CustomersReportController::class, 'index'])->name('customers.report');
    // Search Customers
    Route::post('search_customers', [CustomersReportController::class, 'search_customers'])->name('search_customers');



    Route::get('/section/{id}', [InvoicesController::class, 'getproducts']);

    Route::resource('InvoiceAttachments', InvoiceAttachmentsController::class);

    Route::get('/InvoicesDetails/{id}', [InvoicesDetailsController::class, 'index'])->name('InvoicesDetails');
    Route::get('/View_file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'View_file']);
    Route::get('/download/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'download']);
    Route::post('/delete_file', [InvoicesDetailsController::class,'destroy'])->name('delete_file');



    Route::resource('sections', SectionsController::class);
    Route::resource('products', ProductsController::class);


    
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);


    Route::get('/{page}', [AdminController::class, 'index']);


});
