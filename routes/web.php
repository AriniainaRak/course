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
Route::get('/loginutil', [MyController::class, 'loginUtil'])->name('loginUtil');
Route::get('/logUtil', [MyController::class, 'logUtil'])->name('logUtil');
// Route::get('/equipe', [MyController::class, 'equipe'])->name('equipe');
Route::get('/delete', [MyController::class, 'delete'])->name('delete');
// Route::get('/chrono', [MyController::class, 'chrono'])->name('chrono');
Route::get('/admis', [MyController::class, 'admin'])->name('admin');
Route::get('/logout', [MyController::class, 'logout'])->name('logout');
Route::get('/listeEtapes', [MyController::class, 'listeEtapes'])->name('listeEtapes');
Route::get('/listeEtape', [MyController::class, 'listeEtape'])->name('listeEtape');
Route::get('/listeCategorie', [MyController::class, 'listeCategorie'])->name('listeCategorie');
Route::get('/etape_assignment', [MyController::class, 'etape_assignment'])->name('etape_assignment');
Route::get('/insert', [MyController::class, 'insert'])->name('insert');
Route::get('/chrono', [MyController::class, 'showChrono'])->name('showChrono');
Route::get('/point', [MyController::class, 'point'])->name('point');
Route::get('/penalty', [MyController::class, 'penalty'])->name('penalty');
Route::get('/import', [MyController::class, 'import'])->name('import');
Route::get('/importresult', [MyController::class, 'importresult'])->name('importresult');
Route::post('/importPoint', [MyController::class, 'importPoint'])->name('importPoint');
Route::post('/importCSV1', [MyController::class, 'importCSV1'])->name('importCSV1');
Route::post('/importCSV2', [MyController::class, 'importCSV2'])->name('importCSV2');
Route::post('/importdouble', [MyController::class, 'doubleImport'])->name('importdouble');
Route::get('/DashEtape', [MyController::class, 'classement'])->name('DashEtape');
Route::get('/DashCategorie', [MyController::class, 'classementCategorie'])->name('DashCategorie');
// Route::get('/affectclassementetape}/{idcoureur}', [MyController::class, 'showAffecterTempsForm'])->name('showAffecterTempsForm');
// Route::post('/affecter-temps', [MyController::class, 'affecterTemps'])->name('affecterTemps');
Route::get('/resetdb', [MyController::class, 'resetDatabase'])->name('resetdb');
Route::get('/classement_general', [MyController::class, 'classement_generale'])->name('classement_general');
Route::get('/equipe/{id}', 'MyController@show')->name('equipe.details');
Route::get('/etapepdf', [MyController::class, 'generatePdf']);
Route::get('/classement/pdf/{idetape}', [MyController::class, 'generatePdf']);
Route::get('/lesCoureurs', [MyController::class, 'lesCoureurs']);
Route::get('/stat', [MyController::class, 'stat']);
Route::get('/classement_equipe', [MyController::class, 'classement_equipe']);
