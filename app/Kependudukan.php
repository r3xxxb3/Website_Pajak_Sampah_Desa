<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kependudukan extends Model
{
    //
    protected $table = 'tb_penduduk';
    protected $primaryKey = 'id';

    public function pelanggan(){
        return $this->hasOne(Pelanggan::class, 'id_penduduk', 'id');
    }

    public function pegawai(){
        return $this->hasOne(Pegawai::class, 'id_penduduk', 'id');
    }

    public function mipil(){
        return $this->hasOne(KramaMipil::class, 'penduduk_id', 'id');
    }

    public function kTamiu(){
        return $this->hasOne(KramaTamiu::class, 'penduduk_id', 'id');
    }

    public function tamiu(){
        return $this->hasOne(Tamiu::class, 'penduduk_id', 'id');
    }

    
}
