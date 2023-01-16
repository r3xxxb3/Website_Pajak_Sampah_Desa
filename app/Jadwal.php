<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id_jadwal';
    public $timestamps = true;
    protected $table = 'tb_jadwal';

    protected $fillable = ['mulai', 'selesai', 'hari'];
    //

    public function jenis(){
        return $this->belongsTo(JenisSampah::class, 'id_jenis_sampah', 'id');
    }
}
