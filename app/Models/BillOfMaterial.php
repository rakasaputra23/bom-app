<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;

    protected $table = 'bill_of_material';

    protected $fillable = [
        'nomor_bom',
        'kategori',
        'proyek_id',
        'revisi_id',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public $timestamps = false;

    /**
     * Relasi dengan proyek
     */
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    /**
     * Relasi dengan revisi
     */
    public function revisi()
    {
        return $this->belongsTo(Revisi::class, 'revisi_id');
    }

    /**
     * Relasi dengan item BOM
     */
    public function itemBom()
    {
        return $this->hasMany(ItemBom::class, 'bill_of_material_id');
    }

    /**
     * Accessor untuk format nomor BOM
     */
    public function getNomorBomAttribute($value)
    {
        return $value;
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk filter berdasarkan proyek
     */
    public function scopeByProyek($query, $proyekId)
    {
        return $query->where('proyek_id', $proyekId);
    }
}