<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BanjarAdat extends Model
{
    //
    protected $table = "tb_m_banjar_adat";

    protected $fillable = ['kode_banjar_adat', 'nnama_banjar_adat'];

    public function desaAdat(){
        return $this->belongsTo(DesaAdat::class, 'desa_adat_id', 'id');
    }

}
