<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $table = "tb_role";
    protected $fillable = ["role"];

    public function pegawai(){
        return $this->belongsToMany(Pegawai::class, 'tb_role_pegawai', 'id_role', 'id_pegawai');
    }
}
