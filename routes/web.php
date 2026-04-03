<?php
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\CustomerLoginController;
use App\Models\Conversation;
use App\Http\Controllers\EmployeeMessageController;
use App\Events\MessageSent;
Route::get('/', function () {return view('welcome');});
Route::get('/dashboard', function () {return view('dashboard');})
->middleware(['auth', 'verified'])
->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Messages
    Route::get('/messages', [EmployeeMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/start/{customer}', [EmployeeMessageController::class, 'start'])->name('messages.start');
    Route::get('/messages/{conversation}', [EmployeeMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [EmployeeMessageController::class, 'store'])->name('messages.store');
});
Route::middleware(['auth', 'manager'])->group(function () {
    Route::get('/manager', [ManagerController::class, 'index'])->name('manager.index');
    Route::get('/manager/users/{user}/edit', [ManagerController::class, 'edit'])->name('manager.users.edit');
    Route::put('/manager/users/{user}', [ManagerController::class,'update'])->name('manager.users.update');
});

    Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/monitoring', [AdminController::class, 'monitoring'])->name('admin.monitoring');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
});
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');


Route::get('/customer/login', [CustomerLoginController::class, 'create'])->name('customer.login');
Route::post('/customer/login', [CustomerLoginController::class, 'store'])->name('customer.login.store');
Route::post('/customer/logout', [CustomerLoginController::class, 'destroy'])->name('customer.logout');
use App\Http\Controllers\Customer\CustomerDashboardController;

Route::middleware('auth:customer')->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile/edit', [CustomerDashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/messages', [CustomerDashboardController::class, 'messages'])->name('messages');
    Route::post('/messages', [CustomerDashboardController::class, 'storeMessage'])->name('messages.store');
});




require __DIR__ . '/auth.php';