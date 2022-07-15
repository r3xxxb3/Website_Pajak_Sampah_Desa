<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'tb_pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $guard = 'admin';

    protected $fillable = ['id_desa', 'username'];
    //
    protected $hidden = ['password'];

    public function kependudukan(){
        return $this->belongsTo(Kependudukan::class, 'id_penduduk', 'id');
    }

    public function desa(){
        return $this->belongsTo(Desa::class, 'id_desa', 'id');
    }

    public function role(){
        return $this->belongsToMany(Role::class, 'tb_role_pegawai', 'id_pegawai', 'id_role');
    }
}
