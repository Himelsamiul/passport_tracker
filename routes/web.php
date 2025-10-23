<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\PassportOfficerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PassportProcessingController;  
use App\Http\Controllers\PassportCollectionController;

Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED
Route::middleware(['auth.session'])->group(function () {
    // -------- Dashboard --------
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// -------- Agents CRUD --------
Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');       // list
Route::get('/agents/create', [AgentController::class, 'create'])->name('agents.create'); // add form
Route::post('/agents/store', [AgentController::class, 'store'])->name('agents.store');   // save new
Route::get('/agents/edit/{id}', [AgentController::class, 'edit'])->name('agents.edit');  // edit form
Route::post('/agents/update/{id}', [AgentController::class, 'update'])->name('agents.update'); // update
Route::get('/agents/delete/{id}', [AgentController::class, 'destroy'])->name('agents.delete'); // delete

// -------- Passports CRUD --------
Route::get('/passports', [PassportController::class, 'index'])->name('passports.index');       // list
Route::get('/passports/create', [PassportController::class, 'create'])->name('passports.create'); // add form
Route::post('/passports/store', [PassportController::class, 'store'])->name('passports.store');   // save new
Route::get('/passports/edit/{id}', [PassportController::class, 'edit'])->name('passports.edit');  // edit form
Route::post('/passports/update/{id}', [PassportController::class, 'update'])->name('passports.update'); // update
Route::get('/passports/delete/{id}', [PassportController::class, 'destroy'])->name('passports.delete'); // delete
Route::get('/passports/show/{id}', [PassportController::class, 'show'])->name('passports.show');


// -------- Employees CRUD --------
// -------- Employees CRUD --------
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::post('/employees/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
Route::get('/employees/delete/{id}', [EmployeeController::class, 'destroy'])->name('employees.delete');

// -------- Agencies CRUD --------
Route::get('/agencies', [AgencyController::class, 'index'])->name('agencies.index');
Route::get('/agencies/create', [AgencyController::class, 'create'])->name('agencies.create');
Route::post('/agencies/store', [AgencyController::class, 'store'])->name('agencies.store');
Route::get('/agencies/edit/{id}', [AgencyController::class, 'edit'])->name('agencies.edit');
Route::post('/agencies/update/{id}', [AgencyController::class, 'update'])->name('agencies.update');
Route::get('/agencies/delete/{id}', [AgencyController::class, 'destroy'])->name('agencies.delete');

// -------- Passport Officers CRUD --------
Route::get('/officers',               [PassportOfficerController::class, 'index'])->name('officers.index');
Route::get('/officers/create',        [PassportOfficerController::class, 'create'])->name('officers.create');
Route::post('/officers/store',        [PassportOfficerController::class, 'store'])->name('officers.store');
Route::get('/officers/edit/{id}',     [PassportOfficerController::class, 'edit'])->name('officers.edit');
Route::post('/officers/update/{id}',  [PassportOfficerController::class, 'update'])->name('officers.update');
Route::get('/officers/delete/{id}',   [PassportOfficerController::class, 'destroy'])->name('officers.delete');

// -------- Categories CRUD --------
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');

// -------- Passport Processings CRUD --------
Route::get('/processings',        [PassportProcessingController::class, 'index'])->name('processings.index');
Route::get('/processings/create', [PassportProcessingController::class, 'create'])->name('processings.create');
Route::post('/processings',        [PassportProcessingController::class, 'store'])->name('processings.store');
Route::get('/processings/{processing}', [PassportProcessingController::class, 'show'])->name('processings.show');
Route::get('/processings/{processing}/edit', [PassportProcessingController::class, 'edit'])->name('processings.edit');
Route::put('/processings/{processing}', [PassportProcessingController::class, 'update'])->name('processings.update');
Route::delete('/processings/{processing}', [PassportProcessingController::class, 'destroy'])->name('processings.destroy');

// AJAX to load full Add Passport content into the form
Route::get('/processings/passport-details/{passport}', [PassportProcessingController::class, 'passportDetails'])
    ->name('processings.passport.details');


Route::prefix('passport-collections')->name('collections.')->group(function () {
    Route::get('/', [PassportCollectionController::class, 'index'])->name('index');
    Route::get('/create', [PassportCollectionController::class, 'create'])->name('create');
    Route::post('/store', [PassportCollectionController::class, 'store'])->name('store');

    // ✅ Put AJAX before {id} OR constrain {id} to numbers
    Route::get('/passport/{passport}', [PassportCollectionController::class, 'passportInfo'])
        ->name('passport.info');

    Route::get('/{id}', [PassportCollectionController::class, 'show'])
        ->whereNumber('id')                           // ✅ numeric only
        ->name('show');
            // 👁️ View (details)

    // 🗑️ Delete (add it AFTER show)
    Route::delete('/{id}', [PassportCollectionController::class, 'destroy'])
        ->whereNumber('id') // optional but safer
        ->name('destroy');
});
});