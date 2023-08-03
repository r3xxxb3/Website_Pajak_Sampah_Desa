<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPembayaran extends Model
{
    //
    use softDeletes;
    public $timestamps = "true";
    protected $table = "tb_detail_pembayaran";

    public function pembayaran(){
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran')->withTrashed();
    }

    public function model(){
        return $this->morphTo()->withTrashed();
    }
}
