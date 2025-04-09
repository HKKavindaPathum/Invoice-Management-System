<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/income-data', [DashboardController::class, 'getIncomeData'])->name('dashboard.income-data');

    //Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('categories/{id}', [CategoryController::class, 'show'])->name('categories.show');


    //Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');

    //Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('client/create',[ClientController::class,'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');

    //Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create',[InvoiceController::class,'create'])->name('invoices.create');
    Route::get('/product-price/{id}', [InvoiceController::class, 'getProductPrice']);
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/search', [InvoiceController::class, 'search'])->name('invoices.search');
    Route::get('invoices/filter', [InvoiceController::class, 'filterInvoices'])->name('invoices.filter');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    //Settings
    Route::get('/settings',[SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/register-user', [RegisteredUserController::class, 'create'])->name('register.user');
    Route::post('/register-user', [RegisteredUserController::class, 'store'])->name('register.user.store');
});


require __DIR__.'/auth.php';
