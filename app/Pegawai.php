<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pegawai extends Authenticatable
{
    use Notifiable;

    protected $table = 'tb_pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $guard = 'admin';

    protected $fillable = ['nama', 'username', 'password', 'jenis_kelamin', 'tgl_lahir', 'tmpt_lahir'];
    //
    protected $hidden = ['password'];

    public function pengguna(){
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }
}
