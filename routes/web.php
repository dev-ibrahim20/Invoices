<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SectionsController;
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

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';


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

Route::get('/section/{id}', [InvoicesController::class, 'getproducts']);

Route::resource('InvoiceAttachments', InvoiceAttachmentsController::class);

Route::get('/InvoicesDetails/{id}', [InvoicesDetailsController::class, 'index']);
Route::get('/View_file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'View_file']);
Route::get('/download/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'download']);
Route::post('/delete_file', [InvoicesDetailsController::class,'destroy'])->name('delete_file');



Route::resource('sections', SectionsController::class);
Route::resource('products', ProductsController::class);

Route::get('/{page}', [AdminController::class, 'index']);
