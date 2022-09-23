<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Properti extends Model
{
    
    //
    use SoftDeletes;

    protected $table = 'tb_properti';

    protected $fillable = ['id_pengguna', 'id_jenis', 'id_desa_adat' ,'id_banjar_adat', 'alamat', 'status', 'nama_properti', 'file', 'jumlah_kamar'];

    public function pengguna(){
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }

    public function jasa(){
        return $this->belongsTo(JenisJasa::class, 'id_jenis', 'id');
    }

    public function desaAdat(){
        return $this->belongsTo(DesaAdat::class, 'id_desa_adat', 'id');
    }

    public function banjarAdat(){
        return $this->belongsTo(BanjarAdat::class, 'id_banjar_adat', 'id');
    }
}
