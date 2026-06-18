<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Tender extends Model
{
    use Auditable;
    protected $fillable = [
        'procurement_request_id',
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
    ];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
