<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_config_id', 
        'user_id', 
        'encrypted_price', 
        'hash_key', 
        'status',
        'score_harga',
        'score_teknis',
        'score_integritas',
        'final_score'
    ];

    // Relasi ke User (Vendor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Tender Config
    public function tenderConfig()
    {
        return $this->belongsTo(TenderConfig::class);
    }

    // Accessor untuk Enkripsi (PBI-10)
    public function setPriceAttribute($value)
    {
        $this->attributes['encrypted_price'] = Crypt::encryptString($value);
    }

    public function getDecryptedPrice()
    {
        return Crypt::decryptString($this->encrypted_price);
    }
}