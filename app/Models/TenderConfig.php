<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderConfig extends Model
{
    protected $fillable = [
        'judul_tender',
        'weight_harga',
        'weight_teknis',
        'weight_integritas',
    ];
}
