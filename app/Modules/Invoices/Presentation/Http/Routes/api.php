<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Invoices\Presentation\Http\Controllers\InvoiceController;

Route::get('/invoices/{invoiceId}', [InvoiceController::class, 'show']);
Route::post('/invoices/{invoiceId}/approve', [InvoiceController::class, 'approve']);
Route::post('/invoices/{invoiceId}/reject', [InvoiceController::class, 'reject']);
