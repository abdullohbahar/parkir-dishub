<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\MasterJenisPengajuanController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('auth', [AuthController::class, 'authenticate'])->name('authenticate');

// keycloak
Route::get('/login', [AuthController::class, 'redirectToKeycloak'])->name('login.keycloak');
Route::get('/callback', [AuthController::class, 'handleKeycloakCallback'])->name('keycloak.callback');
Route::get('/logout', [AuthController::class, 'logout'])->name('keycloak.logout');

// admin
Route::prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('parkir', MasterJenisPengajuanController::class);
});
