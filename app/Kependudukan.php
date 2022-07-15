<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kependudukan extends Model
{
    //
    protected $table = 'tb_penduduk';
    protected $primaryKey = 'id';

    public function pelanggan(){
        return $this->hasOne(Pengguna::class, 'id_penduduk', 'id');
    }

    public function pegawai(){
        return $this->hasOne(Pegawai::class, 'id_penduduk', 'id');
    }
}
