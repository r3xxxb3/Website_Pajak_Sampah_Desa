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
    protected $fillable = ['id_pengguna', 'id_properti', 'status', 'nominal'];

    public function properti(){
        return $this->belongsTo(Properti::class, 'id_properti', 'id');
    }

    public function pengguna(){
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }

    public function pembayaran() {
        return $this->morphToMany(Pembayaran::class, 'model' ,'tb_detail_pembayaran' , 'model_id', 'id_pembayaran');
    }
}
