<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use App\Traits\Auditable;

class Bid extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'tender_id', 
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

    // Relasi ke Tender
    public function tender()
    {
        return $this->belongsTo(Tender::class);
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

    // Relasi ke Contract
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}