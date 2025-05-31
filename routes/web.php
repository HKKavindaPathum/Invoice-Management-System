<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login'); 
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/income-data', [DashboardController::class, 'getIncomeData'])->name('dashboard.income-data');

    //categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:category-list');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:category-create');
    Route::get('categories/search', [CategoryController::class, 'search'])->name('categories.search')->middleware('permission:category-list');
    Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:category-edit');
    Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:category-edit');
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:category-delete');
    Route::get('categories/{id}', [CategoryController::class, 'show'])->name('categories.show')->middleware('permission:category-list');


    //products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware('permission:product-list');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware('permission:product-create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware('permission:product-create');
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search')->middleware('permission:product-list');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('permission:product-edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:product-edit');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:product-delete');
    Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:product-list');

    //clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index')->middleware('permission:client-list');
    Route::get('client/create',[ClientController::class,'create'])->name('clients.create')->middleware('permission:client-create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store')->middleware('permission:client-create');
    Route::get('clients/search', [ClientController::class, 'search'])->name('clients.search')->middleware('permission:client-list');
    Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit')->middleware('permission:client-edit');
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update')->middleware('permission:client-edit');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy')->middleware('permission:client-edit');
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show')->middleware('permission:client-list');

    //invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index')->middleware('permission:invoice-list');
    Route::get('invoices/create',[InvoiceController::class,'create'])->name('invoices.create')->middleware('permission:invoice-create');
    Route::get('/product-price/{id}', [InvoiceController::class, 'getProductPrice']);
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store')->middleware('permission:invoice-create');
    Route::get('invoices/search', [InvoiceController::class, 'search'])->name('invoices.search')->middleware('permission:invoice-list');
    Route::get('invoices/filter', [InvoiceController::class, 'filterInvoices'])->name('invoices.filter')->middleware('permission:invoice-list');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit')->middleware('permission:invoice-edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update')->middleware('permission:invoice-edit');
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy')->middleware('permission:invoice-delete');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show')->middleware('permission:invoice-list');
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print')->middleware('permission:invoice-print');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download')->middleware('permission:invoice-download');

    //settings
    Route::get('/settings',[SettingsController::class, 'index'])->name('settings.index')->middleware('permission:settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('permission:settings');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:user-list');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:user-create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:user-create');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:user-list');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:user-edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:user-edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:user-delete');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:role-list');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:role-create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:role-create');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:role-list');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:role-edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:role-edit');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:role-delete');



});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/register-user', [RegisteredUserController::class, 'create'])->name('register.user');
    Route::post('/register-user', [RegisteredUserController::class, 'store'])->name('register.user.store');
});


require __DIR__.'/auth.php';
