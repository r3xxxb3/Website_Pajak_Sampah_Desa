<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    //
    protected $primaryKey = 'id'; 
    protected $table = 'tb_m_provinsi';

    protected $fillable = ['name', 'kecamatan_id'];
    
}
