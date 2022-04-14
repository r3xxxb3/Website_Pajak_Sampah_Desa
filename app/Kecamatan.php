<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $primaryKey = 'kecamatan_id';
    public $timestamps = false;
    protected $table = 'tb_kecamatan';

    protected $fillable = ['nama'];

    public function kota ()
    {
        return $this->belongsTo(Kota::class, 'kabupaten_id', 'id');
    }

    public function desa ()
    {
        return $this->hasMany(Desa::class, 'id', 'kecamatan_id');
    }
    //
}
