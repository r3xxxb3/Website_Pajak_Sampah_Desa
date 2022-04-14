<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'tb_m_kabupaten';

    protected $fillable = ['name'];

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'id', 'kabupaten_id');
    }

    public function desa() 
    {
        return $this->hasMany(Desa::class, 'id', 'kecamatan_id');
    }
    //
}
