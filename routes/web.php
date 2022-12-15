<?php

use App\Http\Controllers\DocumentoController;
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

Route::prefix('/')->controller(DocumentoController::class)->group(function () {
    Route::get('', 'getFolderByCpf');
    Route::get('novo-documento', 'newDocument');
    Route::post('novo-documento', 'newDocumentAction')->name('send-file');
    Route::get('get-documento/{document_id}', 'getDocument');
});
// Route::prefix('documento/')->name('documento.')->controller(DocumentoController::class)->group(function () {
//     Route::get('cpf', 'getFolderByCpf')->name('cpf');
// });
