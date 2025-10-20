<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;

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
