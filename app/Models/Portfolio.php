<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property string $file_path
 * @property string $file_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 */
#[Fillable(['user_id', 'title', 'description', 'file_path', 'file_type'])]
class Portfolio extends Model
{
    // Relasi: Satu portofolio dimiliki oleh satu User (Vendor) [cite: 189]
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}