<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Budget;
use App\Models\Tender;
use App\Models\ProjectProgress;
use App\Models\BastSubmission;

/**
 * @property int $id
 * @property int $user_id
 * @property int $budget_id
 * @property string $item_name
 * @property string|null $description
 * @property int $quantity
 * @property float $price
 * @property float $total_price
 * @property string $status
 * @property int|null $vendor_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Budget $budget
 * @property-read \App\Models\User|null $vendor
 * @property-read \App\Models\Tender|null $tender
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProjectProgress[] $progresses
 * @property-read \App\Models\BastSubmission|null $bastSubmission
 */
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