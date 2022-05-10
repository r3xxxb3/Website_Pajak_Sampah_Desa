<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pengguna extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    //
    protected $table = 'tb_pengguna';
    protected $primaryKey = 'id';

    protected $fillable = ['no_kk', 'nik', 'nama_pengguna', 'tgl_lahir', 'tmpt_lahir', 'no_telp', 'jenis_kelamin', 'chat_id'];

    public function kk(){
        return $this->belongsTo(KartuK::class, 'no_kk', 'no_kk');
    }

    public function pegawai(){
        return $this->hasMany(Pegawai::class, 'id_pengguna', 'id');
    }

    public function banjar(){
        return $this->belongsTo(Banjar::class, 'id_banjar', 'id');
    }
}
