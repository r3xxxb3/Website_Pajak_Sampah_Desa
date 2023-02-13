<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KepuasanPengguna extends Model
{
    //
    protected $table = "tb_kepuasan_pengguna";
    protected $primaryKey = "id";
    public $timestamps = "True";

    protected $fillable = ['item_id', 'item_type', 'rate', 'comment'];

    public function item(){
        return $this->morphTo();
    }
    
}
