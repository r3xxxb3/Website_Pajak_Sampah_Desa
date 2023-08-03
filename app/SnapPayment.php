<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SnapPayment extends Model
{
    //
    use SoftDeletes;
    protected $table='snap_payment';
    protected $primaryKey = 'id';
    
    public function pembayaran(){
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran')->withTrashed();
    }
       
}