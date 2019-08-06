<?php

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

Route::get("/","main_controller@index");
Route::post("/simpan-huruf","main_controller@save_alphabet");
Route::get("/get-data-huruf","main_controller@get_alphabet_data");
Route::get("/more-data","main_controller@get_training_data");
Route::post("/hapus-huruf/{no}/{hal}","main_controller@delete_alphabet");
Route::post("/simpan-perubahan/{no}/{imgname}/{huruf}/{hal}","main_controller@save_changes");
Route::get("/training","main_controller@training");
Route::post("/send-testing-data","main_controller@testing");

// Route::get("/cek-ketersediaan-bobot","main_controller@cek_bobot");
// Route::post("/hapus-berkala/{r1}/{r2}","main_controller@hapus_berkala");
// Route::post("/simpan-hasil-CNN","main_controller@simpan_hasil_cnn");
