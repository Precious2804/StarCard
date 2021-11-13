<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// MainController starts here
Route::post('/register', [MainController::class, 'register'])->name('register');
Route::post('/login', [MainController::class, 'login']);

Route::middleware(['jwt'])->group(function () {
    Route::get('/organization_details', [MainController::class, 'organization_details']);
    Route::post('/create_employee', [MainController::class, 'create_employee']);
    Route::get('/all_employees', [MainController::class, 'all_employees']);
    Route::get('/all_cards', [MainController::class, 'all_cards']);
    Route::post('/logout', [MainController::class, 'logout']);
});
// MainController ends here


// EmployeeController starts here
Route::post('/employee_login', [EmployeeController::class, 'employee_login']);
Route::middleware(['jwt'])->group(function () {
    Route::post('/create_card', [EmployeeController::class, 'create_card']);
    Route::post('/logoutEmp', [EmployeeController::class, 'logoutEmp']);
});
// EmployeeController ends here
