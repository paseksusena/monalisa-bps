<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanTeknis extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function pemutakhiranRumahTangga()
    {
        return $this->hasMany(PemutakhiranRumahTangga::class, 'kegiatan_id');
    }

    public function pemutakhiranPetani()
    {
        return $this->hasMany(pemutakhiranPetani::class, 'kegiatan_id');
    }

    public function pemutakhiranPerusahaan()
    {
        return $this->hasMany(pemutakhiranPerusahaan::class, 'kegiatan_id');
    }


    //pencacahan
    public function pencacahanRumahTangga(){
        return $this->hasMany(pencacahanRumahTangga::class, 'kegiatan_id');
    }
    public function pencacahanPetani(){
        return $this->hasMany(pencacahanPetani::class, 'kegiatan_id');
    }
    public function pencacahanPerusahaan(){
        return $this->hasMany(pencacahanPerusahaan::class, 'kegiatan_id');
    }
}
