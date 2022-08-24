<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kota;
use App\Kecamatan;
use App\Desa;
use App\Jadwal;
use App\StandarRetribusi;
use App\JenisSampah;
use App\JenisJasa;
use App\Role;
use Auth;


class MasterDataController extends Controller
{
    public function indexKota ()
    {
        $index  =  Kota::all();
        return view('admin.master-data.kota.index', compact('index'));
    }

    public function createKota ()
    {
        return view('admin.master-data.kota.create');
    }

    public function storeKota (Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'kabkot' => 'required',
        ],$messages);

        $kota = new Kota;
        $kota->nama_kabkot = $request->kabkot;
        $kota->save();
        return redirect()->route('masterdata-kota-index')->with('success','Berhasil Menambah Data Kota!');
    
    }

    public function editKota ($id)
    {

    }

    public function updateKota (Request $request)
    {

    }

    public function deleteKota ()
    {

    }

    public function indexKecamatan ()
    {
        $index  =  Kecamatan::with('kota')->get();
        // dd($index);
        return view('admin.master-data.kecamatan.index', compact('index'));
    }

    public function createKecamatan ()
    {
        return view('admin.master-data.kecamatan.create');
    }

    public function storeKecamatan (Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'kabkot' => 'required',
            'kecamatan' => 'required',
        ],$messages);

        $kecamatan = new Kecamatan;
        $kecamatan->nama = $request->kecamatan;
        $kota = Kota::where('nama_kabkot','LIKE', $request->kabkot)->first();
        if(!is_null($kota)){
            // dd($kota);
            $kecamatan->kabkot_id = $kota->kabkot_id;
        }else{
            $kota2 = new Kota;
            $kota2->nama_kabkot=$request->kabkot;
            $kotaNew = Kota::where('nama_kabkot','LIKE', $request->kabkot)->first();
            $kecamatan->kabkot_id = $kotaNew->kabkot_id; 
        }
        $kecamatan->save();
        return redirect()->route('masterdata-kecamatan-index')->with('success','Berhasil Menambah Data Kecamatan !');
    }

    public function editKecamatan ($id)
    {

    }

    public function updateKecamatan (Request $request)
    {

    }

    public function deleteKecamatan ()
    {

    }

    public function indexDesa ()
    {
        $index  =  Desa::all();
        return view('admin.master-data.desa.index', compact('index'));
    }

    public function createDesa ()
    {
        return view('admin.master-data.desa.create');
    }

    public function storeDesa (Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'desadat_nama' => 'required',
            'kecamatan' => 'required',
        ],$messages);

        $desa = new Desa;
        $desa->desadat_nama = $request->desadat_nama;
        $kecamatan = Kecamatan::where('nama','LIKE', $request->kecamatan)->first();
        if(!is_null($kecamatan)){
            // dd($kecamatan);
            $desa->kabkot_id = $kecamatan->kabkot_id;
            $desa->kecamatan_id = $kecamatan->kecamatan_id;
        }else{
            // dd($kecamatan);
            return redirect()->route('masterdata-desa-create')->with('error','Data Kecamatan Tidak Ditemukan !');
        }
        $desa->save();
        return redirect()->route('masterdata-desa-index')->with('success','Berhasil Menambah Data Desa !');
    }

    public function editDesa ($id)
    {

    }

    public function updateDesa (Request $request)
    {

    }

    public function deleteDesa ()
    {

    }

    public function indexJadwal ()
    {
        $index  =  Jadwal::all();
        return view('admin.master-data.jadwal.index', compact('index'));
    }

    public function createJadwal ()
    {
        return view('admin.master-data.jadwal.create');
    }

    public function storeJadwal (Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'mulai' => 'required',
            'selesai' => 'required',
            'hari' => 'required',
        ],$messages);

        $jadwal = new Jadwal;
        $jadwal->mulai = $request->mulai;
        $jadwal->selesai = $request->selesai;
        $jadwal->hari = $request->hari;
        $jadwal->save();
        return redirect()->route('masterdata-jadwal-index')->with('success','Berhasil Menambah Data Jadwal Pengangkutan !');
    }
    
    public function editJadwal ($id)
    {
        $jadwal = Jadwal::where('id_jadwal', $id)->first();
        if($jadwal != [] ){
            return view('admin.master-data.jadwal.edit', compact('jadwal'));
        }else{
            return redirect()->route('masterdata-jadwal-index')->with('error', 'Data Jadwal Tidak Ditemukan !');
        }
    }

    public function updateJadwal ($id, Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'mulai' => 'required',
            'selesai' => 'required',
            'hari' => 'required',
        ],$messages);

        $jadwal = Jadwal::where('id_jadwal', $id)->first();
        if($jadwal != []){
            $jadwal->mulai = $request->mulai;
            $jadwal->selesai = $request->selesai;
            $jadwal->hari = $request->hari;
            $jadwal->save();
            return redirect()->route('masterdata-jadwal-index')->with('success','Berhasil Menambah Data Jadwal Pengangkutan !');
        }else{
            return redirect()->back()->with('error', 'Data Jadwal Tidak Ditemukan !');
        }
    }

    public function deleteJadwal ($id)
    {
        $jadwal = Jadwal::where('id_jadwal', $id)->first();
        if($jadwal != []){
            $jadwal->delete();
            return redirect()->route('masterdata-jadwal-index')->with('success', 'Berhasil Menghapus Data Jadwal Pengangkutan !');
        }else{
            return redirect()->route('masterdata-jadwal-index')->with('error', 'Berhasil Menghapus Data Jadwal Pengangkutan !');
        }   
    }

    public function indexRetribusi ()
    {
        $index  =  StandarRetribusi::all();
        return view('admin.master-data.standar-retribusi.index', compact('index'));
    }

    public function createRetribusi ()
    {
        return view('admin.master-data.standar-retribusi.create');
    }

    public function storeRetribusi (Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'standar' => 'required',
            'durasi' => 'required',
        ],$messages);

        $standar = new StandarRetribusi;
        $standar->nominal_retribusi = $request->standar;
        $standar->id_jenis_jasa = $request->id;
        $standar->tanggal_berlaku = $request->mulai;
        $standar->tanggal_selesai = $request->selesai;
        $standar->durasi = $request->durasi;
        $standar->save();
        return redirect()->back()->with('success','Berhasil Menambah Data Standar Retribusi !');
    }
    
    public function editRetribusi ($id)
    {
        $retribusi = StandarRetribusi::where('id', $id)->first();
        if($retribusi != null){
            return view('admin.master-data.standar-retribusi.edit', compact('retribusi'));
        }else{
            return back()->with('error', 'Data Tidak Ditemukan !');
        }

    }

    public function updateRetribusi ($id,Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'standar' => 'required',
            'durasi' => 'required',
        ],$messages);

        $standar = StandarRetribusi::where('id', $id)->first();
        // dd($standar);
        if($standar != []){
            $standar->nominal_retribusi = $request->standar;
            $standar->durasi = $request->durasi;
            $standar->tanggal_berlaku = $request->mulai;
            $standar->tanggal_selesai = $request->selesai;
            $standar->update();
            return redirect()->back()->with('success-1','Berhasil Mengubah Data Standar Retribusi !');
        }else{
            return redirect()->back()->with('error-1', 'Data Standar Retribusi Tidak Ditemukan !');
        }
        
    }

    public function deleteRetribusi ($id)
    {
        $retribusi = StandarRetribusi::where('id', $id)->first();
        if($retribusi != null){
            $retribusi->delete();
            return redirect()->back()->with('success-1', 'Berhasil Menghapus Data Standar Retribusi !');
        }else{
            return redirect()->back()->with('error-1', 'Berhasil Menghapus Data Standar Retribusi !');
        }
    }

    public function statusRetribusi ($id, $status){
        $statusRetribusi = StandarRetribusi::where('id', $id)->first();
        $stats = StandarRetribusi::where('id_jenis_jasa', $statusRetribusi->id_jenis_jasa)->get();
        // dd($stats);
        if(isset($stats)){
            foreach($stats as $s){
                $s->active = '0';
                $s->update();
            }
        }
        if($status == 'active'){
            $statusRetribusi->active = '1';
        }elseif($status = 'not-active'){
            $statusRetribusi->active = '0';
        }
        $statusRetribusi->update();
        return response()->json(['sukses' => 'Status Standar Retribusi berhasil diganti']);
    }


    public function indexJenisSampah ()
    {
        $index  =  JenisSampah::all();
        return view('admin.master-data.jenis-sampah.index', compact('index'));
    }

    public function createJenisSampah ()
    {
        return view('admin.master-data.jenis-sampah.create');
    }

    public function storeJenisSampah (Request $request)
    {
        // dd($request->jenis);
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'jenis' => 'required',
        ],$messages);

        $jenis = new JenisSampah;
        $jenis->jenis_sampah = $request->jenis;
        $jenis->deskripsi = $request->deskripsi;
        $jenis->save();
        return redirect()->route('masterdata-jenis-index')->with('success','Berhasil Menambah Data Jenis Sampah !');
    }
    
    public function editJenisSampah ($id)
    {
        $jenis = JenisSampah::where('id', $id)->first();
        if($jenis != []){
            return view('admin.master-data.jenis-sampah.edit', compact('jenis'));
        }else{
            return redirect()->back()->with('error','Data Jenis Sampah Tidak Ditemukan !');
        }
    }

    public function updateJenisSampah ($id, Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'jenis' => 'required',
        ],$messages);

        $jenis = JenisSampah::where('id', $id)->first();
        if($jenis != []){
            $jenis->jenis_sampah = $request->jenis;
            $jenis->deskripsi = $request->deskripsi;
            $jenis->save();
            return redirect()->route('masterdata-jenis-index')->with('success','Berhasil Menambah Data Jenis Sampah !');
        }else{
            return redirect()->back()->with('error', 'Data Jenis Sampah Tidak Ditemukan !');
        }
        
    }

    public function deleteJenisSampah ($id)
    {
        $jenis = JenisSampah::where('id', $id)->first();
        $jenis->delete();
        return redirect()->route('masterdata-jenis-index')->with('success', 'Berhasil Menghapus Data Jenis Sampah !');
    }

    public function indexJenisJasa(){
        $index = JenisJasa::with(['standar' => function($query){
            $query->where('active', '1')->get();
        }])->get();
        // dd($index);
        return view('admin.master-data.jenis-jasa.index', compact('index'));
    }

    public function createJenisJasa(){
        return view('admin.master-data.jenis-jasa.create');
    }

    public function storeJenisJasa(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'jenis' => 'required',
        ],$messages);

        $jenis = new JenisJasa;
        $jenis->jenis_jasa = $request->jenis;
        $jenis->deskripsi = $request->deskripsi;
        if($jenis->save()){
            return redirect()->route('masterdata-jenisjasa-index')->with('success','Berhasil Menambah Data Jenis Jasa !');    
        }else{
            return redirect()->route('masterdata-jenisjasa-index')->with('error','Proses Penambahan Data Jenis Jasa Tidak Berhasil !');
        }
        
        
    }

    public function editJenisJasa($id){
        $index = StandarRetribusi::where('id_jenis_jasa', $id)->get();
        $jenis = JenisJasa::where('id', $id)->first();
        return view('admin.master-data.jenis-jasa.edit', compact('jenis', 'index'));
    }

    public function updateJenisJasa($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'jenis' => 'required',
        ],$messages);

        $jenis = JenisJasa::where('id', $id)->first();
        if($jenis != []){
            $jenis->jenis_jasa = $request->jenis;
            $jenis->deskripsi = $request->deskripsi;
            $jenis->save();
            return redirect()->route('masterdata-jenisjasa-index')->with('success', 'Berhasil Mengubah Data Jenis Jasa !');
        }else{
            return redirect()->route('masterdata-jenisjasa-index')->with('error', 'Data Jenis Jasa Tidak Ditemukan !');
        }
        
    }

    public function deleteJenisJasa($id){
        $jenis = JenisJasa::where('id', $id)->first();
        $jenis->delete();
        return redirect()->route('masterdata-jenisjasa-index')->with('success', 'Berhasil Menghapus Data Jenis Jasa !');
    }
    
    public function indexRole(){
        $index = Role::all();
        return view('admin.master-data.role.index', compact('index'));
    }

    public function createRole(){
        return view('admin.master-data.role.create');
    }

    public function storeRole(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'role' => 'required',
        ],$messages);

        $role = new Role;
        $role->role = $request->role;
        if($role->save()){
            return redirect()->route('masterdata-role-index')->with('success','Berhasil Menambah Data Role !');    
        }else{
            return redirect()->route('masterdata-role-index')->with('error','Proses Penambahan Data Role Tidak Berhasil !');
        }
    }

    public function editRole($id){
        $role = Role::where('id', $id)->first();
        return view('admin.master-data.role.edit', compact('role'));
    }

    public function updateRole($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'role' => 'required',
        ],$messages);

        $role = Role::where('id', $id)->first();
        if($role != []){
            $role->role = $request->role;
            $role->save();
            return redirect()->route('masterdata-role-index')->with('success', 'Berhasil Mengubah Data Role !');
        }else{
            return redirect()->route('masterdata-role-index')->with('error', 'Data Role Tidak Ditemukan !');
        }
    }

    public function deleteRole($id){
        $role = Role::where('id', $id)->first();
        $role->delete();
        return redirect()->route('masterdata-role-index')->with('success', 'Berhasil Menghapus Data Role !');
    }
    //
}
