<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KramaTamiu extends Model
{
    //
    protected $table = "tb_cacah_krama_tamiu";
    

    public function banjarAdat(){
        return $this->belongsTo(BanjarAdat::class, 'banjar_adat_id', 'id');
    }

    public function desaAdat(){
        return $this->hasOneThrough(DesaAdat::class, BanjarAdat::Class );
    }
}
