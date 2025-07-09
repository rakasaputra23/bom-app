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

    protected $appends = ['display_name']; // ← ini yang penting

    public function getDisplayNameAttribute()
    {
        return $this->kode_proyek . ' - ' . $this->nama_proyek;
    }
}