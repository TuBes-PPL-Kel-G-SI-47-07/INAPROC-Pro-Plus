<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'budget_id',
        'item_name',
        'description',
        'quantity',
        'price',
        'total_price',
        'status',
        'vendor_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function tender()
    {
        return $this->hasOne(Tender::class);
    }

    public function progresses()
    {
        return $this->hasMany(ProjectProgress::class, 'procurement_request_id')->orderBy('percentage', 'asc');
    }

    public function bastSubmission()
    {
        return $this->hasOne(BastSubmission::class, 'procurement_request_id');
    }
}