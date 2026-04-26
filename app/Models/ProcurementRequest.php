<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'description',
        'quantity',
        'price',
        'total_price',
        'status',
        'budget_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budget()
{
    return $this->belongsTo(Budget::class, 'budget_id', 'id');
}
}