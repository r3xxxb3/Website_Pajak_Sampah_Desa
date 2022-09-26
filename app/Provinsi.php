<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    //
    protected $table = 'tb_m_provinsi';
    protected $primaryKey = 'id'; 

    protected $fillable = ['name', 'kecamatan_id'];
    
}
