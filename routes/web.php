<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\PassportOfficerController;

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
