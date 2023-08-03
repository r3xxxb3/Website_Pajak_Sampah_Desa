<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailJadwal extends Model
{
    //
    use softDeletes;
    public $timestamps = "true";
    protected $table = "tb_detail_jadwal";

    public function jadwal(){
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal')->withTrashed();
    }

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai')->withTrashed();
    }
}