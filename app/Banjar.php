<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banjar extends Model
{
    //
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $table = 'tb_m_banjar_dinas';

    protected $fillable = ['kode_banjar_dinas', 'nama_banjar_dinas', 'jenis_banjar_dinas'];

    public function DesaDinas ()
    {
        return $this->belongsTo(Desa::class, 'id' ,'desa_dinas_id');
    }


}
