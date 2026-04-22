<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'title', 'description', 'file_path', 'file_type'])]
class Portfolio extends Model
{
    // Relasi: Satu portofolio dimiliki oleh satu User (Vendor) [cite: 189]
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}