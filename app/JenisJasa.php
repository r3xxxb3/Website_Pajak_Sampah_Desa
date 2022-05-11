<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisJasa extends Model
{
    //
    protected $table = 'tb_jenis_jasa';
    protected $fillable = ['jenis_jasa', 'deskripsi'];

    public function standar(){
        return $this->hasMany(StandarRetribusi::class, 'id_jenis_jasa', 'id');
    }
}
