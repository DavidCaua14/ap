<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ObjetoController;
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

// Rota para login
Route::get('login', [LoginController::class, 'index'])->name('login');

// Rota para autenticar o usuário
Route::post('login', [LoginController::class, 'autenticar'])->name('login.post');

// Rota para deslogar
Route::post('logout', [LoginController::class, 'deslogar'])->name('logout');

// Middleware para verificar se o usuário está autenticado
Route::middleware('auth')->group(function () {
    // Redireciona a rota / para /home
    Route::get('/', function() {
        return redirect()->route('home');
    })->name('root');

    // Página principal (home)
    Route::get('/home', [ObjetoController::class, 'indexHome'])->name('home');
});

// Apenas o administrador tem acesso
Route::middleware(['auth', 'can:admin-access'])->group(function () {
    // Página interna (dashboard)
    Route::get('/dashboard', [ObjetoController::class, 'indexDashboard'])->name('dashboard');

    // Objetos
    Route::get('/create-objeto', [ObjetoController::class, 'create'])->name('objeto.create');
    Route::post('/store-objeto', [ObjetoController::class, 'store'])->name('objeto.store');
    Route::get('/objetos/{id}/edit', [ObjetoController::class, 'edit'])->name('objeto.edit');
    Route::put('/objetos/{id}', [ObjetoController::class, 'update'])->name('objeto.update');
    Route::post('/objeto/{id}/entregue', [ObjetoController::class, 'entregar'])->name('objeto.entregar');
    Route::delete('/destroy-objeto/{id}', [ObjetoController::class, 'destroy'])->name('objeto.destroy');

    // Busca dentro da dashboard
    Route::get('/dashboard/search', [ObjetoController::class, 'search'])->name('admin.objeto.search');
});
