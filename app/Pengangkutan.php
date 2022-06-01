<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengangkutan extends Model
{
    //
    protected $table = "tb_request_pengangkutan";

    public function pembayaran(){
        return $this->morphedToMany(Pembayaran::class, 'model', 'tb_detail_pembayaran', 'model_id', 'id_pembayaran');
    }

    
}
