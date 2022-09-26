<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StandarRetribusi extends Model
{
    //
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'tb_standar_retribusi';
    protected $primaryKey = 'id';

    protected $fillable = ['nominal_retribusi', 'durasi'];

    public function jenis(){
        return $this->belongsTo(JenisJasa::class, 'id_jenis_jasa', 'id');
    }
}
