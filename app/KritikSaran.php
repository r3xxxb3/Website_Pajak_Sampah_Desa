<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KritikSaran extends Model
{
    //
    use SoftDeletes;
    protected $table = "tb_kritik";
    protected $primaryKey = "id";
    public $timestamps = "True";
    protected $fillable = ["id_pelanggan", "kritik", "subjek"];

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }
}
