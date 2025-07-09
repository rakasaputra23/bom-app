<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revisi extends Model
{
    use HasFactory;

    protected $table = 'revisi';

    public $timestamps = false;

    protected $fillable = [
        'jenis_revisi',
        'keterangan'
    ];

    protected $casts = [
        'jenis_revisi' => 'string',
        'keterangan' => 'string'
    ];
}