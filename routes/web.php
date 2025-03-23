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
    return redirect('/login'); // Redirect the home page to login
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/income-data', [DashboardController::class, 'getIncomeData'])->name('dashboard.income-data');

    //categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');// List categories
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');// Store a new category
    Route::get('categories/search', [CategoryController::class, 'search'])->name('categories.search');// Search categories
    Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');// Edit category
    Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');// Update category
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');// Delete category
    Route::get('categories/{id}', [CategoryController::class, 'show'])->name('categories.show');// Show category


    //products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');// List product
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');// Create product
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');// Store a new product
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');// Search product
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');// Edit product
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');// Update product
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');// Delete product
    Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');// Show product

    //clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');// List clients
    Route::get('client/create',[ClientController::class,'create'])->name('clients.create');// Create client
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');// Store a new client
    Route::get('clients/search', [ClientController::class, 'search'])->name('clients.search');// Search client
    Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');// Edit client
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');// Update client
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');// Delete client
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');// Show client

    //invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');// List invoices
    Route::get('invoices/create',[InvoiceController::class,'create'])->name('invoices.create');// Create invoices
    Route::get('/product-price/{id}', [InvoiceController::class, 'getProductPrice']);// Take unit price
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');// Store a new invoices
    Route::get('invoices/search', [InvoiceController::class, 'search'])->name('invoices.search');// Search invoices
    Route::get('invoices/filter', [InvoiceController::class, 'filterInvoices'])->name('invoices.filter');// filter invoices
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');// Edit invoices
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');// Update invoices
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');// Delete invoices
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');// Show invoices
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');// print invoices
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');// download invoices

    //settings
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
