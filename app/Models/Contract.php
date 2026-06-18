<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_id',
        'spk_number',
        'contract_file_path',
    ];

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }
}
