<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Bid extends Model
{
    protected $fillable = ['tender_config_id', 'user_id', 'encrypted_price', 'hash_key', 'status'];

    // Fungsi untuk mengunci harga (Sealed Bidding)
    public function setPriceAttribute($value)
    {
        $this->attributes['encrypted_price'] = Crypt::encryptString($value);
    }

    // Fungsi untuk membuka harga (saat pengumuman)
    public function getDecryptedPrice()
    {
        return Crypt::decryptString($this->encrypted_price);
    }
}