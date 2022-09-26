<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    //
    use SoftDeletes;
    public $timestamps = true;
    protected $table = "tb_role";
    protected $fillable = ["role"];

    public function pegawai(){
        return $this->hasMany(Pegawai::class, 'id_role', 'id');
    }
}
