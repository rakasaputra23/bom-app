<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    protected $table = 'jenis_dokumen';
    
    protected $fillable = [
        'kode_dokumen',
        'nama_dokumen', 
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Scope untuk dokumen yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor untuk display name
    public function getDisplayNameAttribute()
    {
        return $this->kode_dokumen . ' - ' . $this->nama_dokumen;
    }

    // Relasi ke BillOfMaterial
    public function billOfMaterials()
    {
        return $this->hasMany(BillOfMaterial::class, 'jenis_dokumen_id');
    }

    // Static method untuk mendapatkan data dropdown
    public static function getDropdownData()
    {
        return static::active()
            ->orderBy('kode_dokumen')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->display_name];
            });
    }
}