<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBom extends Model
{
    use HasFactory;

    protected $table = 'item_bom';

    protected $fillable = [
        'bill_of_material_id',
        'kode_material_id',
        'keterangan'
    ];

    public $timestamps = false;

    /**
     * Relasi dengan Bill of Material
     */
    public function billOfMaterial()
    {
        return $this->belongsTo(BillOfMaterial::class, 'bill_of_material_id');
    }

    /**
     * Relasi dengan Kode Material
     */
    public function kodeMaterial()
    {
        return $this->belongsTo(KodeMaterial::class, 'kode_material_id');
    }

    /**
     * Accessor untuk mendapatkan nama material
     */
    public function getNamaMaterialAttribute()
    {
        return $this->kodeMaterial ? $this->kodeMaterial->nama_material : '';
    }

    /**
     * Accessor untuk mendapatkan spesifikasi
     */
    public function getSpesifikasiAttribute()
    {
        return $this->kodeMaterial ? $this->kodeMaterial->spesifikasi : '';
    }

    /**
     * Accessor untuk mendapatkan kode material
     */
    public function getKodeMaterialCodeAttribute()
    {
        return $this->kodeMaterial ? $this->kodeMaterial->kode_material : '';
    }

    /**
     * Accessor untuk mendapatkan UOM
     */
    public function getUomAttribute()
    {
        return $this->kodeMaterial && $this->kodeMaterial->uom ? $this->kodeMaterial->uom : null;
    }

    /**
     * Accessor untuk mendapatkan satuan
     */
    public function getSatuanAttribute()
    {
        return $this->kodeMaterial && $this->kodeMaterial->uom ? $this->kodeMaterial->uom->satuan : '';
    }

    /**
     * Accessor untuk mendapatkan qty UOM
     */
    public function getQtyUomAttribute()
    {
        return $this->kodeMaterial && $this->kodeMaterial->uom ? $this->kodeMaterial->uom->qty : 0;
    }
}