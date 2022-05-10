<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', 'Admin\AuthController@Login')->name('admin-login');
Route::get('/admin/logout', 'Admin\AuthController@Logout')->name('admin-logout');
Route::post('/admin/auth', 'Admin\AuthController@Auth')->name('admin-authenticate');

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'] , function(){
    Route::get('/dashboard', 'Admin\DashboardController@index')->name('admin-dashboard');
    
    //master data kota
    Route::get('/masterdata/kota', 'Admin\MasterDataController@indexKota')->name('masterdata-kota-index');
    Route::get('/masterdata/kota/create', 'Admin\MasterDataController@createKota')->name('masterdata-kota-create');
    Route::post('/masterdata/kota/store', 'Admin\MasterDataController@storeKota')->name('masterdata-kota-store');
    Route::get('/masterdata/kota/edit/{id}', 'Admin\MasterDataController@editKota')->name('masterdata-kota-edit');
    Route::post('/masterdata/kota/update', 'Admin\MasterDataController@updatexKota')->name('masterdata-kota-update');
    Route::get('/masterdata/kota/delete/{id}', 'Admin\MasterDataController@deleteKota')->name('masterdata-kota-delete');

    //master data kecamatan
    Route::get('/masterdata/kecamatan', 'Admin\MasterDataController@indexKecamatan')->name('masterdata-kecamatan-index');
    Route::get('/masterdata/kecamatan/create', 'Admin\MasterDataController@createKecamatan')->name('masterdata-kecamatan-create');
    Route::post('/masterdata/kecamatan/store', 'Admin\MasterDataController@storeKecamatan')->name('masterdata-kecamatan-store');
    Route::get('/masterdata/kecamatan/edit/{id}', 'Admin\MasterDataController@editKecamatan')->name('masterdata-kecamatan-edit');
    Route::post('/masterdata/kecamatan/update', 'Admin\MasterDataController@updateKecamatan')->name('masterdata-kecamatan-update');
    Route::get('/masterdata/kecamatan/delete/{id}', 'Admin\MasterDataController@deleteKecamatan')->name('masterdata-kecamatan-delete');

    //master data desa
    Route::get('/masterdata/desa', 'Admin\MasterDataController@indexDesa')->name('masterdata-desa-index');
    Route::get('/masterdata/desa/create', 'Admin\MasterDataController@createDesa')->name('masterdata-desa-create');
    Route::post('/masterdata/desa/store', 'Admin\MasterDataController@storeDesa')->name('masterdata-desa-store');
    Route::get('/masterdata/desa/edit/{id}', 'Admin\MasterDataController@editDesa')->name('masterdata-desa-edit');
    Route::post('/masterdata/desa/update', 'Admin\MasterDataController@updateDesa')->name('masterdata-desa-update');
    Route::get('/masterdata/desa/delete/{id}', 'Admin\MasterDataController@deleteDesa')->name('masterdata-kota-delete');

    //master data jadwal
    Route::get('/masterdata/jadwal', 'Admin\MasterDataController@indexJadwal')->name('masterdata-jadwal-index');
    Route::get('/masterdata/jadwal/create', 'Admin\MasterDataController@createJadwal')->name('masterdata-jadwal-create');
    Route::post('/masterdata/jadwal/store', 'Admin\MasterDataController@storeJadwal')->name('masterdata-jadwal-store');
    Route::get('/masterdata/jadwal/edit/{id}', 'Admin\MasterDataController@editJadwal')->name('masterdata-jadwal-edit');
    Route::post('/masterdata/jadwal/update/{id}', 'Admin\MasterDataController@updatejadwal')->name('masterdata-jadwal-update');
    Route::get('/masterdata/jadwal/delete/{id}', 'Admin\MasterDataController@deletejadwal')->name('masterdata-jadwal-delete');

    //manajemen data pegawai
    Route::get('/pegawai', 'Admin\PegawaiController@indexPegawai')->name('pegawai-index');
    Route::get('/pegawai/create', 'Admin\PegawaiController@createPegawai')->name('pegawai-create');
    Route::post('/pegawai/store', 'Admin\PegawaiController@storePegawai')->name('pegawai-store');
    Route::post('/pegawai/store-new', 'Admin\PegawaiController@storePegawaiNew')->name('pegawai-store-new');
    Route::get('/pegawai/edit/{id}', 'Admin\PegawaiController@editPegawai')->name('pegawai-edit');
    Route::post('/pegawai/update', 'Admin\PegawaiController@updatePegawai')->name('pegawai-update');
    Route::get('/pegawai/delete/{id}', 'Admin\PegawaiController@deletePegawai')->name('pegawai-delete');


    //Manajemen Standar Retribusi
    Route::get('/masterdata/retribusi', 'Admin\MasterDataController@indexRetribusi')->name('masterdata-retribusi-index');
    Route::get('/masterdata/retribusi/create', 'Admin\MasterDataController@createRetribusi')->name('masterdata-retribusi-create');
    Route::post('/masterdata/retribusi/store', 'Admin\MasterDataController@storeRetribusi')->name('masterdata-retribusi-store');
    Route::get('/masterdata/retribusi/edit/{id}', 'Admin\MasterDataController@editRetribusi')->name('masterdata-retribusi-edit');
    Route::post('/masterdata/retribusi/update/{id}', 'Admin\MasterDataController@updateRetribusi')->name('masterdata-retribusi-update');
    Route::get('/masterdata/retribusi/delete/{id}', 'Admin\MasterDataController@deleteRetribusi')->name('masterdata-retribusi-delete');

    //Manajemen Jenis Jasa Retribusi
    Route::get('/masterdata/jenis-jasa', 'Admin\MasterDataController@indexJenisJasa')->name('masterdata-jenisjasa-index');
    Route::get('/masterdata/jenis-jasa/create', 'Admin\MasterDataController@createJenisJasa')->name('masterdata-jenisjasa-create');
    Route::post('/masterdata/jenis-jasa/store', 'Admin\MasterDataController@storeJenisJasa')->name('masterdata-jenisjasa-store');
    Route::get('/masterdata/jenis-jasa/edit/{id}', 'Admin\MasterDataController@editJenisJasa')->name('masterdata-jenisjasa-edit');
    Route::post('/masterdata/jenis-jasa/update/{id}', 'Admin\MasterDataController@updateJenisJasa')->name('masterdata-jenisjasa-update');
    Route::get('/masterdata/jenis-jasa/delete/{id}', 'Admin\MasterDataController@deleteJenisJasa')->name('masterdata-jenisjasa-delete');

    //Manajemen Jenis Sampah
    Route::get('/masterdata/jenis-sampah', 'Admin\MasterDataController@indexJenisSampah')->name('masterdata-jenis-index');
    Route::get('/masterdata/jenis-sampah/create', 'Admin\MasterDataController@createJenisSampah')->name('masterdata-jenis-create');
    Route::post('/masterdata/jenis-sampah/store', 'Admin\MasterDataController@storeJenisSampah')->name('masterdata-jenis-store');
    Route::get('/masterdata/jenis-sampah/edit/{id}', 'Admin\MasterDataController@editJenisSampah')->name('masterdata-jenis-edit');
    Route::post('/masterdata/jenis-sampah/update/{id}', 'Admin\MasterDataController@updateJenisSampah')->name('masterdata-jenis-update');
    Route::get('/masterdata/jenis-sampah/delete/{id}', 'Admin\MasterDataController@deleteJenisSampah')->name('masterdata-jenis-delete');

    //Manajemen Banjar


    //Manajemen Pengguna
    Route::get('/pengguna', 'Admin\PenggunaController@index')->name('pengguna-index');
    Route::get('/pengguna/create', 'Admin\PenggunaController@create')->name('pengguna-create');
    Route::post('/pengguna/store', 'Admin\PenggunaController@store')->name('pengguna-store');
    Route::get('/pengguna/edit/{id}', 'Admin\PenggunaController@edit')->name('pengguna-edit');
    Route::post('/pengguna/update/{id}', 'Admin\PenggunaController@update')->name('pengguna-update');
    Route::get('/pengguna/delete/{id}', 'Admin\PenggunaController@delete')->name('pengguna-delete');

    //Manajemen Pegawai


    //Manajemen Retribusi


    //Manajemen Request


    //Manajemen Pembayaran
    

});

Route::name('js.')->group(function() {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
