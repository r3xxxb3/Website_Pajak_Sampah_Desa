<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesaAdat extends Model
{
    //
    protected $table = "tb_m_desa_adat";

    protected $fillable = ['desadat_nama', 'desadat_kode',  'kecamatan_id'];

    public function banjarAdat(){
        return $this->hasMany(banjarAdat::class, 'desa_adat_id', 'id');
    }


}
