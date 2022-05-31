<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retribusi extends Model
{
    //
    use SoftDeletes;
    protected $table = 'tb_retribusi';
    protected $fillable = ['id_pengguna', 'id_properti', 'status', 'nominal'];

    public function properti(){
        return $this->belongsTo(Properti::class, 'id_properti', 'id');
    }

    public function pengguna(){
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }

    public function pembayaran(){
        return $this->belongsToMany(Pembayaran::class,'tb_detail_pembayaran' , 'id', 'id_retribusi');
    }
}
