<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StandarRetribusi extends Model
{
    //
    protected $table = 'tb_standar_retribusi';
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $fillable = ['nominal_retribusi', 'durasi'];
}
