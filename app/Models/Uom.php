<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    use HasFactory;

    protected $table = 'uom';
    
    public $timestamps = false;

    protected $fillable = ['qty', 'satuan'];

    // Relationship with KodeMaterial
    public function kodeMaterials()
    {
        return $this->hasMany(KodeMaterial::class, 'uom_id');
    }

    // Accessor for full format
    public function getFullFormatAttribute()
    {
        return $this->qty == 1 ? $this->satuan : $this->qty.' - '.$this->satuan;
    }
}