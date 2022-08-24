<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KramaMipil extends Model
{
    //
    protected $table = "tb_cacah_krama_mipil";

    public function banjarAdat(){
        return $this->belongsTo(BanjarAdat::class, 'banjar_adat_id', 'id');
    }

    public function desaAdat(){
        return $this->hasOneThrough(DesaAdat::class, BanjarAdat::Class );
    }
}
