<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyController;

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
    return view('pages/login');
});
Route::get('/login', [MyController::class, 'login'])->name('login');
Route::get('/loginadmin', [MyController::class, 'logAdmin'])->name('loginAdmin');
Route::get('/loginEquipes', [MyController::class, 'loginEquipes'])->name('loginEquipes');
Route::get('/logEquipes', [MyController::class, 'logEquipes'])->name('logEquipes');
Route::get('/equipe/{access_token}', [MyController::class, 'accessViaToken'])->name('equipeaccessViaToken');
Route::get('/', [MyController::class, 'dashboard'])->name('team.dashboard');
Route::get('/admis', [MyController::class, 'admin'])->name('admin');
Route::get('/logout', [MyController::class, 'logout'])->name('logout');
Route::get('/listeEtapes', [MyController::class, 'listeEtapes'])->name('listeEtapes');
Route::get('/listeEtape', [MyController::class, 'listeEtape'])->name('listeEtape');
Route::get('/listeCoureur', [MyController::class, 'listeCoureur'])->name('listeCoureur');
Route::get('/etape_assignment', [MyController::class, 'etape_assignment'])->name('etape_assignment');
Route::get('/insert', [MyController::class, 'insert'])->name('insert');
Route::get('/stockerChrono', [MyController::class, 'temps'])->name('temps');
