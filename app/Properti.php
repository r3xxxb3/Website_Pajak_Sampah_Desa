<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Properti extends Model
{
    
    //
    use SoftDeletes;

    protected $table = 'tb_properti';

    protected $fillable = ['id_pengguna', 'id_jenis', 'id_banjar', 'alamat', 'status', 'nama_properti', 'file', 'jumlah_kamar'];

    public function pengguna(){
        return $this->belongsTo(Pengguna::class, 'id', 'id_pengguna');
    }

    public function jasa(){
        return $this->belongsTo(JenisJasa::class, 'id_jenis', 'id');
    }

    public function banjarAdat(){
        return $this->belongsTo(BanjarAdat::class, 'id_banjar_adat', 'id');
    }
}
