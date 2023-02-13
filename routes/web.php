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


    //Manajemen pelanggan
    Route::get('/pelanggan', 'Admin\pelangganController@index')->name('pelanggan-index');
    Route::get('/pelanggan/create', 'Admin\pelangganController@create')->name('pelanggan-create');
    Route::get('/pelanggan/store/{id}', 'Admin\pelangganController@store')->name('pelanggan-store');
    Route::get('/pelanggan/edit/{id}', 'Admin\pelangganController@edit')->name('pelanggan-edit');
    Route::post('/pelanggan/update/{id}', 'Admin\pelangganController@update')->name('pelanggan-update');
    Route::get('/pelanggan/delete/{id}', 'Admin\pelangganController@delete')->name('pelanggan-delete');

    //Manajemen properti pelanggan
    Route::post('/properti/store', 'Admin\pelangganController@propertiStore' )->name('admin-properti-store');
    Route::post('/properti/update/{id}', 'Admin\pelangganController@propertiUpdate')->name('admin-properti-update');
    Route::get('/properti/cancel/{id}', 'Admin\pelangganController@propertiCancel')->name('admin-properti-cancel');
    Route::get('/properti/delete/{id}', 'Admin\pelangganController@propertiDelete')->name('admin-properti-delete');
    Route::post('/banjar/search', 'Admin\pelangganController@banjarCheck')->name('banjar-search');

    //Manajemen Retribusi
    Route::get('/retribusi', 'Admin\RetribusiController@index')->name('admin-retribusi-index');
    Route::get('/retribusi/history', 'Admin\RetribusiController@history')->name('admin-retribusi-history');
    Route::post('/retribusi/verif-many', 'Admin\RetribusiController@verifMany')->name('admin-retribusi-verif-many');
    Route::post('/retribusi/update/{id}', 'Admin\RetribusiController@update')->name('admin-retribusi-update');
    Route::get('/retribusi/verif/{id}', 'Admin\RetribusiController@verif')->name('admin-retribusi-verif');

    //Manajemen Request
    Route::get('/request', 'Admin\RequestController@index')->name('admin-request-index');
    Route::get('/request/create', 'Admin\RequestController@create')->name('admin-request-create');
    Route::post('/request/store', 'Admin\RequestController@store')->name('admin-request-store');
    Route::get('/request/edit/{id}', 'Admin\RequestController@edit')->name('admin-request-store');
    Route::get('/request/update/{id}', 'Admin\RequestController@update')->name('admin-request-update');
    Route::get('/request/confirm/{id}', 'Admin\RequestController@confirm')->name('admin-request-confirm');
    Route::get('/request/cancel/{id}', 'Admin\RequestController@cancel')->name('admin-request-cancel');
    Route::post('/request/verif', 'Admin\RequestController@verif')->name('admin-request-verif');
    Route::get('/request/delete/{id}', 'Admin\RequestController@delete')->name('admin-request-delete');
    // Route::post('/request/verif-many', 'Admin\RequestController@verifMany')->name('admin-request-verif-many');

    //Manajemen Pembayaran
    Route::get('/pembayaran', 'Admin\PembayaranController@index')->name('admin-pembayaran-index');
    Route::get('/pembayaran/create', 'Admin\PembayaranController@create')->name('admin-pembayaran-create');
    Route::post('/pembayaran/store', 'Admin\PembayaranController@store')->name('admin-pembayaran-store');
    Route::get('/pembayaran/verif/{id}', 'Admin\PembayaranController@verif')->name('admin-pembayaran-verif');
    Route::get('/pembayaran/edit/{id}', 'Admin\PembayaranController@edit')->name('admin-pembayaran-edit');
    Route::post('/pembayaran/update/{id}', 'Admin\PembayaranController@update')->name('admin-pembayaran-update');
    Route::get('/pembayaran/delete/{id}', 'Admin\PembayaranController@delete')->name('admin-pembayaran-delete');
    Route::post('/pembayaran/search', 'Admin\PembayaranController@search')->name('admin-pembayaran-search');
    Route::post('/pembayaran/item/delete', 'Admin\PembayaranController@itemDelete')->name('admin-pembayaran-item-hapus');
    Route::post('/pembayaran/item/cari', 'Admin\PembayaranController@itemSearch')->name('admin-pembayaran-item-cari');
    Route::post('/pembayaran/item/tambah', 'Admin\PembayaranController@itemAdd')->name('admin-pembayaran-item-tambah');
    Route::post('/pembayaran/item/refresh', 'Admin\PembayaranController@itemRefresh')->name('admin-pembayaran-item-refresh');
    Route::post('/pembayaran/keranjang', 'Admin\PembayaranController@keranjang')->name('admin-pembayaran-keranjang');
    Route::post('/pembayaran/keranjang/view', 'Admin\PembayaranController@keranjangView')->name('admin-pembayaran-keranjang-view');
    Route::post('/pembayaran/keranjang/hapus', 'Admin\PembayaranController@keranjangHapus')->name('admin-pembayaran-keranjang-hapus');
    Route::post('/pembayaran/keranjang/cari', 'Admin\PembayaranController@keranjangSearch')->name('admin-pembayaran-keranjang-cari');
    Route::post('/pembayaran/pelanggan/cari', 'Admin\PembayaranController@pelangganSearch')->name('admin-pembayaran-pelanggan-cari');

    //Customer Service
    Route::get('/penilaian', 'Admin\CustomerServiceController@penilaianIndex')->name('admin-custom-penilaian-index');
    // Route::post('/penilaian/store', 'Admin\CustomerServiceController@penilaianStore')->name('admin-custom-penilaian-store');
    Route::get('/kritik', 'Admin\CustomerServiceController@kritikIndex')->name('admin-custom-kritik-index');
    Route::get('/kritik/delete/{id}', 'Admin\CustomerServiceController@kritikDelete')->name('admin-custom-kritik-delete');

    //Report
    Route::get('/laporan', 'Admin\ReportController@index')->name('admin-laporan-index');
    Route::post('laporan/keuangan', 'Admin\ReportCOntroller@keuanganSearch')->name('admin-report-keuangan-search');
    Route::post('laporan/penilaian', 'Admin\ReportCOntroller@penilaianSearch')->name('admin-report-penilaian-search');

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
    Route::post('/banjar/search', 'User\UserController@banjarCheck')->name('banjar-search');

    //Retribusi
    Route::get('/retribusi', 'User\RetribusiController@index')->name('retribusi-index');
    Route::post('/retribusi/update', 'User\RetribusiController@update')->name('retribusi-update');
    Route::post('/retribusi/keranjang', 'User\RetribusiController@keranjang')->name('retribusi-keranjang');
    Route::post('/retribusi/keranjang/view', 'User\RetribusiController@keranjangView')->name('retribusi-keranjang-view');
    Route::post('/retribusi/keranjang/hapus', 'User\RetribusiController@keranjangHapus')->name('retribusi-keranjang-hapus');
    Route::post('/retribusi/keranjang/bayar', 'User\RetribusiController@pay')->name('retribusi-keranjang-bayar');

    //Request
    Route::get('/request', 'User\RequestController@index')->name('request-index');
    Route::get('/request/create', 'User\RequestController@create')->name('request-create');
    Route::post('/request/store', 'User\RequestController@store')->name('request-store');
    Route::get('/request/edit/{id}', 'User\RequestController@edit')->name('request-edit');
    Route::post('/request/update/{id}', 'User\RequestController@update')->name('request-update');
    Route::get('/request/cancel/{id}', 'User\RequestController@cancel')->name('request-cancel');
    Route::get('/request/delete/{id}', 'User\RequestController@cancel')->name('request-delete');
    Route::post('/request/keranjang', 'User\RequestController@keranjang')->name('request-keranjang');
    Route::post('/request/keranjang/view', 'User\RequestController@keranjangView')->name('request-keranjang-view');
    Route::post('/request/keranjang/hapus', 'User\RequestController@keranjangHapus')->name('request-keranjang-hapus');
    Route::post('/request/keranjang/bayar', 'User\RequestController@pay')->name('request-keranjang-bayar');


    //Pembayaran
    Route::get('/pembayaran', 'User\PembayaranController@index')->name('pembayaran-index');
    Route::get('/pembayaran/create', 'User\PembayaranController@create')->name('pembayaran-create');
    Route::post('/pembayaran/store', 'User\PembayaranController@store')->name('pembayaran-store');
    Route::get('/pembayaran/edit/{id}', 'User\PembayaranController@edit')->name('pembayaran-edit');
    Route::post('/pembayaran/update/{id}', 'User\PembayaranController@update')->name('pembayaran-update');
    Route::get('/pembayaran/delete/{id}', 'User\PembayaranController@delete')->name('pembayaran-delete');
    Route::post('/Pembayaran/search', 'User\PembayaranController@search')->name('pembayaran-search');
    Route::post('/pembayaran/item/delete', 'User\PembayaranController@itemDelete')->name('pembayaran-item-hapus');
    Route::post('/pembayaran/item/cari', 'User\PembayaranController@itemSearch')->name('pembayaran-item-cari');
    Route::post('/pembayaran/item/tambah', 'User\PembayaranController@itemAdd')->name('pembayaran-item-tambah');
    Route::post('/pembayaran/item/refresh', 'User\PembayaranController@itemRefresh')->name('pembayaran-item-refresh');
    Route::post('/pembayaran/keranjang', 'User\PembayaranController@keranjang')->name('pembayaran-keranjang');
    Route::post('/pembayaran/keranjang/view', 'User\PembayaranController@keranjangView')->name('pembayaran-keranjang-view');
    Route::post('/pembayaran/keranjang/hapus', 'User\PembayaranController@keranjangHapus')->name('pembayaran-keranjang-hapus');
    Route::post('/pembayaran/keranjang/cari', 'User\PembayaranController@keranjangSearch')->name('pembayaran-keranjang-cari');

    //Customer Service
    Route::get('/penilaian', 'User\CustomerServiceController@penilaianIndex')->name('custom-penilaian-index');
    Route::post('/penilaian/store', 'User\CustomerServiceController@penilaianStore')->name('custom-penilaian-store');
    Route::post('/penilaian/update', 'User\CustomerServiceController@penilaianUpdate')->name('custom-penilaian-update');
    Route::get('/kritik', 'User\CustomerServiceController@kritikIndex')->name('custom-kritik-index');
    Route::post('/kritik/store', 'User\CustomerServiceController@kritikStore')->name('custom-kritik-store');
    Route::post('/kritik/update', 'User\CustomerServiceController@kritikUpdate')->name('custom-kritik-update');
    Route::get('/kritik/delete/{id}', 'User\CustomerServiceController@kritikDelete')->name('custom-kritik-delete');
});

Route::get('/home', 'HomeController@landing')->name('home');
Route::post('/jadwal', 'HomeController@searchJadwal')->name('jadwal-search');


route::get('/test/{id}', 'TestingController@test')->name('test');
