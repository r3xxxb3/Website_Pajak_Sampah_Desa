<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    //
    protected $table = "tb_keranjang";
    protected $primaryKey = "id";
    public $timestamps = "True";

    protected $fillable = ['id_pelanggan', 'id_desa_adat', 'model_id', 'model_type'];

    public function model(){
        return $this->morphTo();
    }

}
