<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    
    //
    protected $table = 'tb_properti';

    protected $fillable = ['id_pengguna', 'id_jenis', 'id_banjar', 'alamat'];

    public function pengguna(){
        return $this->belongsTo(Pengguna::class, 'id', 'id_pengguna');
    }
}
