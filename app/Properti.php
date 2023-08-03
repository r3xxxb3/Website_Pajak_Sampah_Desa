<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Properti extends Model
{
    
    //
    use SoftDeletes;

    protected $table = 'tb_properti';

    protected $fillable = ['id_pelanggan', 'id_jenis', 'id_desa_adat' ,'id_banjar_adat', 'alamat', 'status', 'nama_properti', 'file', 'jumlah_kamar'];

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
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
    public function retribusi(){
        return $this->hasMany(Retribusi::class, 'id_properti', 'id')->withTrashed();
    }
}
