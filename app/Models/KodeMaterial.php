<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeMaterial extends Model
{
    use HasFactory;

    protected $table = 'kode_material';

    public $timestamps = false;
    
    protected $fillable = [
        'kode_material',
        'nama_material',
        'spesifikasi',
        'uom_id'
    ];

    // Relationship dengan UOM
    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    // Relationship dengan Item BOM
    public function itemBom()
    {
        return $this->hasMany(ItemBom::class, 'kode_material_id');
    }

    // Accessor untuk mendapatkan satuan dari UOM
    public function getSatuanAttribute()
    {
        return optional($this->uom)->full_format;
    }
}