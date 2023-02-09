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

    public function Dpembayaran() {
        return $this->morphMany(DetailPembayaran::Class, 'model');
    }

    // public function pembayaran(){
    //     return $this->morphToMany(Pembayaran::class, 'DetailPembayaran')->using(DetailPembayaran::class);
    // }

    public function pembayaran() {
        return $this->morphToMany(Pembayaran::class, 'detail', 'tb_detail_pembayaran');
    }

    public function keranjang(){
        return $this->morphMany(Keranjang::Class, 'model');
    }

    public function kepuasan(){
        return $this->morphMany(KepuasanPengguna::Class, 'item');
    }
}
