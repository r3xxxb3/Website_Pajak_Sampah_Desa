<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisSampah extends Model
{
    //
    use SoftDeletes;
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $table = 'tb_jenis_sampah';

    protected $fillable = ['jenis_sampah', 'deskripsi'];
}
