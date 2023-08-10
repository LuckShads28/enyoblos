<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPemilihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pemilih',
        'id_kandidat'
    ];
}
