<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;

Route::get('/',[ReportController::class, 'index'])->name('home');

Route::get('/login', function() {
    return view('login');
})->name('login');



Route::post('/store',[ReportController::class, 'store'])->name('store');
Route::post('/auth', [ReportController::class, 'auth'])->name('auth');
Route::middleware(['IsLogin', 'CekRole:petugas'])->group(function(){
    Route::get('/data/petugas', [ReportController::class, 'dataPetugas'])->name('data.petugas');
    //menampilkan from tambah atau ubah respon
    Route::get('/response/edit/{report_id}', [ResponseController::class, 'edit'])->name('response.edit');
    //kirim data response, menggunakan patch, karena dia bisa berupa tambah data atau update data
    Route::patch('/response/update/{report_id}', [ResponseController::class, 'update'])->name('response.update');


});

Route::middleware(['IsLogin', 'CekRole:admin,petugas'])->group(function (){
    Route::get('/logout', [ReportController::class, 'logout'])->name('logout');
});

Route::middleware('IsLogin', 'CekRole:admin')->group(function() {
 Route::get('/data',[ReportController::class, 'data'])->name('data');
 
 Route::delete('/delete/{id}', [ReportController::class, 'destroy'])->name('delete');
 Route::get('/export/pdf', [ReportController::class, 'createPDF'])->name('export-pdf');
 Route::get('/export/pdf/{id}', [ReportController::class, 'printPDF'])->name('print-pdf');
 Route::get('/export/excel',[ReportController::class, 'exportExcel'])->name('export-excel');
});