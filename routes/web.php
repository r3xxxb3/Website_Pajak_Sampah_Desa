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
    
    //Master data role
    Route::get('/masterdata/role', 'Admin\MasterDataController@indexRole')->name('masterdata-role-index');
    Route::get('/masterdata/role/create', 'Admin\MasterDataController@createRole')->name('masterdata-role-create');
    Route::post('/masterdata/role/store', 'Admin\MasterDataController@storeRole')->name('masterdata-role-store');
    Route::get('/masterdata/role/edit/{id}', 'Admin\MasterDataController@editRole')->name('masterdata-role-edit');
    Route::post('/masterdata/role/update/{id}', 'Admin\MasterDataController@updateRole')->name('masterdata-role-update');
    Route::get('/masterdata/role/delete/{id}', 'Admin\MasterDataController@deleteRole')->name('masterdata-role-delete');

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
    // Route::post('/pegawai/store-new', 'Admin\PegawaiController@storePegawaiNew')->name('pegawai-store-new');
    Route::get('/pegawai/edit/{id}', 'Admin\PegawaiController@editPegawai')->name('pegawai-edit');
    Route::post('/pegawai/update/{id}', 'Admin\PegawaiController@updatePegawai')->name('pegawai-update');
    Route::get('/pegawai/delete/{id}', 'Admin\PegawaiController@deletePegawai')->name('pegawai-delete');


    //Manajemen Standar Retribusi
    Route::get('/masterdata/retribusi', 'Admin\MasterDataController@indexRetribusi')->name('masterdata-retribusi-index');
    Route::get('/masterdata/retribusi/create', 'Admin\MasterDataController@createRetribusi')->name('masterdata-retribusi-create');
    Route::post('/masterdata/retribusi/store', 'Admin\MasterDataController@storeRetribusi')->name('masterdata-retribusi-store');
    Route::get('/masterdata/retribusi/edit/{id}', 'Admin\MasterDataController@editRetribusi')->name('masterdata-retribusi-edit');
    Route::post('/masterdata/retribusi/update/{id}', 'Admin\MasterDataController@updateRetribusi')->name('masterdata-retribusi-update');
    Route::get('/masterdata/retribusi/delete/{id}', 'Admin\MasterDataController@deleteRetribusi')->name('masterdata-retribusi-delete');
    Route::get('/masterdata/retribusi/{id}/{status}', 'Admin\MasterDataController@statusRetribusi')->name('masterdata-retribusi-status');

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
    Route::get('/pengguna/store/{id}', 'Admin\PenggunaController@store')->name('pengguna-store');
    Route::get('/pengguna/edit/{id}', 'Admin\PenggunaController@edit')->name('pengguna-edit');
    Route::post('/pengguna/update/{id}', 'Admin\PenggunaController@update')->name('pengguna-update');
    Route::get('/pengguna/delete/{id}', 'Admin\PenggunaController@delete')->name('pengguna-delete');

    //Manajemen properti pengguna
    Route::post('/properti/store', 'Admin\PenggunaController@propertiStore' )->name('admin-properti-store');
    Route::post('/properti/update/{id}', 'Admin\PenggunaController@propertiUpdate')->name('admin-properti-update');
    Route::get('/properti/cancel/{id}', 'Admin\PenggunaController@propertiCancel')->name('admin-properti-cancel');
    Route::get('/properti/delete/{id}', 'Admin\PenggunaController@propertiDelete')->name('admin-properti-delete');


    //Manajemen Retribusi
    Route::get('/retribusi', 'Admin\RetribusiController@index')->name('admin-retribusi-index');
    Route::post('/retribusi/verif-many', 'Admin\RetribusiController@verifMany')->name('admin-retribusi-verif-many');
    Route::post('/retribusi/update/{id}', 'Admin\RetribusiController@update')->name('admin-retribusi-update');
    Route::get('/retribusi/verif/{id}', 'Admin\RetribusiController@verif')->name('admin-retribusi-verif');

    //Manajemen Request


    //Manajemen Pembayaran
    Route::get('/pembayaran', 'Admin\PembayaranController@index')->name('admin-pembayaran-index');
    Route::get('/pembayaran/create', 'Admin\PembayaranController@create')->name('admin-pembayaran-create');
    Route::get('/pembayaran/verif/{id}', 'Admin\PembayaranController@verif')->name('admin-pembayaran-verif');
    Route::post('/pembayaran/update/{id}', 'Admin\PembayaranController@update')->name('admin-pembayaran-update');
    Route::get('/retribusi/delete/{id}', 'Admin\PembayaranController@delete')->name('admin-pembayaran-delete');

});

Route::name('js.')->group(function() {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});

// Auth::routes();

//Authentication routes for user/pelanggan

Route::get('/loginPage', 'Auth\AuthController@loginPage')->name('login-page');
Route::post('/login', 'Auth\AuthController@login')->name('login');
Route::get('/registerPage', 'Auth\AuthController@registerPage')->name('register-page');
Route::post('/register', 'Auth\AuthController@register')->name('register');
Route::post('/register/search', 'Auth\AuthController@registerSearch')->name('register-search');

Route::get('/password', 'Auth\PasswordController@requestPass')->name('password.request');
Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

Route::group(['middleware' => ['auth'], 'prefix' => 'user'] , function(){
    Route::get('/dashboard', 'User\DashboardController@dashboard')->name('user-dashboard');

    //Data Diri
    Route::get('/data-diri/index', 'User\UserController@dataIndex')->name('data-index');
    Route::post('/data-diri/update', 'User\UserController@dataUpdate')->name('data-update');

    //properti
    Route::get('/properti', 'User\UserController@properti')->name('properti-index');
    Route::get('/properti/create', 'User\UserController@propertiCreate')->name('properti-create');
    Route::post('/properti/store', 'User\UserController@propertiStore')->name('properti-store');
    Route::get('/properti/edit/{id}', 'User\UserController@propertiEdit')->name('properti-edit');
    Route::post('/properti/update/{id}', 'User\UserController@propertiUpdate')->name('properti-update');
    Route::post('/properti/cancel/{id}', 'User\UserController@propertiCancel')->name('properti-cancel');
    Route::get('/properti/delete/{id}', 'User\UserController@propertiDelete')->name('properti-delete');

    //Retribusi
    Route::get('/retribusi', 'User\RetribusiController@index')->name('retribusi-index');

    //Pembayaran
    Route::post('/pembayaran/store', 'User\PembayaranController@store')->name('pembayaran-store');
});

Route::get('/home', 'HomeController@landing')->name('home');

route::get('/test', 'TestingController@test')->name('test');
