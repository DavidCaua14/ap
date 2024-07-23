<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('auth/login'); 
})->name('login');

Route::middleware('guest')->group(function () {
    // Rota GET para exibir o formulário de login
    Route::get('login', [LoginController::class, 'mostrarFormularioDeLogin'])->name('login');

    // Rota POST para autenticar o usuário
    Route::post('login', [LoginController::class, 'autenticar'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home'); 
    
    Route::post('logout', [LoginController::class, 'destruir'])->name('logout');
});