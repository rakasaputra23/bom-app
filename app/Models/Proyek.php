<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';

    public $timestamps = false;

    protected $fillable = [
        'kode_proyek',
        'nama_proyek'
    ];

    public function billOfMaterial()
    {
        return $this->hasMany(BillOfMaterial::class, 'proyek_id');
    }


    public function getDisplayNameAttribute()
    {
        return $this->kode_proyek . ' - ' . $this->nama_proyek;
    }
}