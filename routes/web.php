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
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReportExportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminUserController;

Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED — every module is additionally guarded by the role system:
// 'perm:<module>.<action>' hides routes from roles without that ability.
Route::middleware(['auth.session'])->group(function () {
    // -------- Dashboard --------
    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('perm:dashboard.view')->name('dashboard');

    // -------- Agents CRUD --------
    Route::get('/agents', [AgentController::class, 'index'])->middleware('perm:agents.view')->name('agents.index');
    Route::get('/agents/create', [AgentController::class, 'create'])->middleware('perm:agents.create')->name('agents.create');
    Route::post('/agents/store', [AgentController::class, 'store'])->middleware('perm:agents.create')->name('agents.store');
    Route::get('/agents/edit/{id}', [AgentController::class, 'edit'])->middleware('perm:agents.edit')->name('agents.edit');
    Route::post('/agents/update/{id}', [AgentController::class, 'update'])->middleware('perm:agents.edit')->name('agents.update');
    Route::get('/agents/delete/{id}', [AgentController::class, 'destroy'])->middleware('perm:agents.delete')->name('agents.delete');

    // -------- Registrations --------
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->middleware('perm:registrations.create')->name('register.form');
    Route::post('/register', [AuthController::class, 'registerStore'])->middleware('perm:registrations.create')->name('register.store');
    Route::get('/register/list', [AuthController::class, 'registerList'])->middleware('perm:registrations.view')->name('register.list');
    Route::patch('/register/{id}/status', [AuthController::class, 'registerToggleStatus'])->middleware('perm:registrations.edit')->name('register.status');
    Route::delete('/register/{id}', [AuthController::class, 'registerDestroy'])->middleware('perm:registrations.delete')->name('register.destroy');

    // -------- Passports CRUD --------
    Route::get('/passports', [PassportController::class, 'index'])->middleware('perm:passports.view')->name('passports.index');
    Route::get('/passports/create', [PassportController::class, 'create'])->middleware('perm:passports.create')->name('passports.create');
    Route::post('/passports/store', [PassportController::class, 'store'])->middleware('perm:passports.create')->name('passports.store');
    Route::get('/passports/edit/{id}', [PassportController::class, 'edit'])->middleware('perm:passports.edit')->name('passports.edit');
    Route::post('/passports/update/{id}', [PassportController::class, 'update'])->middleware('perm:passports.edit')->name('passports.update');
    Route::get('/passports/delete/{id}', [PassportController::class, 'destroy'])->middleware('perm:passports.delete')->name('passports.delete');
    Route::get('/passports/show/{id}', [PassportController::class, 'show'])->middleware('perm:passports.view')->name('passports.show');

    // -------- Employees CRUD --------
    Route::get('/employees', [EmployeeController::class, 'index'])->middleware('perm:employees.view')->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->middleware('perm:employees.create')->name('employees.create');
    Route::post('/employees/store', [EmployeeController::class, 'store'])->middleware('perm:employees.create')->name('employees.store');
    Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit'])->middleware('perm:employees.edit')->name('employees.edit');
    Route::post('/employees/update/{id}', [EmployeeController::class, 'update'])->middleware('perm:employees.edit')->name('employees.update');
    Route::get('/employees/delete/{id}', [EmployeeController::class, 'destroy'])->middleware('perm:employees.delete')->name('employees.delete');

    // -------- Agencies CRUD --------
    Route::get('/agencies', [AgencyController::class, 'index'])->middleware('perm:agencies.view')->name('agencies.index');
    Route::get('/agencies/create', [AgencyController::class, 'create'])->middleware('perm:agencies.create')->name('agencies.create');
    Route::post('/agencies/store', [AgencyController::class, 'store'])->middleware('perm:agencies.create')->name('agencies.store');
    Route::get('/agencies/edit/{id}', [AgencyController::class, 'edit'])->middleware('perm:agencies.edit')->name('agencies.edit');
    Route::post('/agencies/update/{id}', [AgencyController::class, 'update'])->middleware('perm:agencies.edit')->name('agencies.update');
    Route::get('/agencies/delete/{id}', [AgencyController::class, 'destroy'])->middleware('perm:agencies.delete')->name('agencies.delete');

    // -------- Passport Officers CRUD --------
    Route::get('/officers', [PassportOfficerController::class, 'index'])->middleware('perm:officers.view')->name('officers.index');
    Route::get('/officers/create', [PassportOfficerController::class, 'create'])->middleware('perm:officers.create')->name('officers.create');
    Route::post('/officers/store', [PassportOfficerController::class, 'store'])->middleware('perm:officers.create')->name('officers.store');
    Route::get('/officers/edit/{id}', [PassportOfficerController::class, 'edit'])->middleware('perm:officers.edit')->name('officers.edit');
    Route::post('/officers/update/{id}', [PassportOfficerController::class, 'update'])->middleware('perm:officers.edit')->name('officers.update');
    Route::get('/officers/delete/{id}', [PassportOfficerController::class, 'destroy'])->middleware('perm:officers.delete')->name('officers.delete');

    // -------- Categories CRUD --------
    Route::get('/categories', [CategoryController::class, 'index'])->middleware('perm:categories.view')->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->middleware('perm:categories.create')->name('categories.create');
    Route::post('/categories/store', [CategoryController::class, 'store'])->middleware('perm:categories.create')->name('categories.store');
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->middleware('perm:categories.edit')->name('categories.edit');
    Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->middleware('perm:categories.edit')->name('categories.update');
    Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy'])->middleware('perm:categories.delete')->name('categories.delete');

    // -------- Passport Processings CRUD --------
    Route::get('/processings', [PassportProcessingController::class, 'index'])->middleware('perm:processings.view')->name('processings.index');
    Route::get('/processings/create', [PassportProcessingController::class, 'create'])->middleware('perm:processings.create')->name('processings.create');
    Route::post('/processings', [PassportProcessingController::class, 'store'])->middleware('perm:processings.create')->name('processings.store');
    Route::get('/processings/passport-details/{passport}', [PassportProcessingController::class, 'passportDetails'])
        ->middleware('perm:processings.view')->name('processings.passport.details');
    Route::get('/processings/{processing}', [PassportProcessingController::class, 'show'])->middleware('perm:processings.view')->name('processings.show');
    Route::get('/processings/{processing}/edit', [PassportProcessingController::class, 'edit'])->middleware('perm:processings.edit')->name('processings.edit');
    Route::put('/processings/{processing}', [PassportProcessingController::class, 'update'])->middleware('perm:processings.edit')->name('processings.update');
    Route::delete('/processings/{processing}', [PassportProcessingController::class, 'destroy'])->middleware('perm:processings.delete')->name('processings.destroy');

    // -------- Collections --------
    Route::prefix('passport-collections')->name('collections.')->group(function () {
        Route::get('/', [PassportCollectionController::class, 'index'])->middleware('perm:collections.view')->name('index');
        Route::get('/create', [PassportCollectionController::class, 'create'])->middleware('perm:collections.create')->name('create');
        Route::post('/store', [PassportCollectionController::class, 'store'])->middleware('perm:collections.create')->name('store');

        // AJAX before {id} — constrained below
        Route::get('/passport/{passport}', [PassportCollectionController::class, 'passportInfo'])
            ->middleware('perm:collections.view')->name('passport.info');

        Route::get('/{id}', [PassportCollectionController::class, 'show'])
            ->whereNumber('id')
            ->middleware('perm:collections.view')->name('show');

        Route::delete('/{id}', [PassportCollectionController::class, 'destroy'])
            ->whereNumber('id')
            ->middleware('perm:collections.delete')->name('destroy');
    });

    // -------- Reports --------
    Route::prefix('reports')->name('reports.')->middleware('perm:reports.view')->group(function () {
        Route::get('/processing', [ReportsController::class, 'processing'])->name('processing');
        Route::get('/collection', [ReportsController::class, 'collection'])->name('collection');
        Route::get('/summary',    [ReportsController::class, 'summary'])->name('summary');

        // Downloads (Excel + PDF) — respect the on-screen filters
        Route::get('/{type}/export/excel', [ReportExportController::class, 'excel'])
            ->where('type', 'processing|collection|summary')->name('export.excel');
        Route::get('/{type}/export/pdf', [ReportExportController::class, 'pdf'])
            ->where('type', 'processing|collection|summary')->name('export.pdf');
    });

    // -------- Administration: roles & admin users (super admins only;
    // Access::can denies everyone with a role) --------
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('roles', RoleController::class)->except('show');
        Route::resource('users', AdminUserController::class)->except('show');
    });
});
