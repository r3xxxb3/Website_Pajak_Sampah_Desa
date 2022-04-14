<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $table = 'tb_m_desa_dinas';

    protected $fillable = ['name', 'kecamatan_id'];

    public function kecamatan ()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    // public function kota ()
    // {
    //     return $this->belongsTo(Kota::class, 'kabkot_id');
    // }
    //
}
