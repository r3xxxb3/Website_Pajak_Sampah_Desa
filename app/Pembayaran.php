<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    //
    protected $table='tb_pembayaran';
    protected $primaryKey = 'id_pembayaran';

    public function retribusi(){
        return $this->morphedByMany(Retribusi::class, 'model', 'tb_detail_pembayaran', 'id_pembayaran', 'model_id')->withTimeStamps();
    }

    public function pengangkutan(){
        return $this->morphedByMany(Pengangkutan::class, 'model', 'tb_detail_pembayaran', 'id_pembayaran', 'model_id')->withTimeStamps();
    }
}
