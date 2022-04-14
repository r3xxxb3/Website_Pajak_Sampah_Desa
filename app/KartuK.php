<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KartuK extends Model
{
    protected $primaryKey = 'no_kk';
    public $timestamps = true;
    protected $table = 'tb_kk';

    protected $fillable = ['id_desa', 'rt', 'rt'];

    // public function kepala ()
    // {
    //     return $this->belongsTo(Penduduk::class, 'kecamatan_id');
    // }

    public function desa ()
    {
        return $this->belongsTo(Desa::class, 'id_desa', 'id');
    }
    //
}
