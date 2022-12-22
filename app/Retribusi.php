<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retribusi extends Model
{
    //
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'tb_retribusi';
    protected $fillable = ['id_pelanggan', 'id_properti', 'status', 'nominal'];

    public function properti(){
        return $this->belongsTo(Properti::class, 'id_properti', 'id');
    }

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }

    public function pembayaran() {
        return $this->morphToMany(DetailPembayaran::class, 'model');
    }
}
