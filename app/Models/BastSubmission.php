<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProcurementRequest;

/**
 * @property int $id
 * @property int $procurement_request_id
 * @property int $vendor_id
 * @property string $file_path
 * @property string|null $description
 * @property string $status
 * @property string|null $auditor_notes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\ProcurementRequest $procurementRequest
 * @property-read \App\Models\User $vendor
 */
class BastSubmission extends Model
{
    use HasFactory;

    protected $table = 'bast_submissions';

    protected $fillable = [
        'procurement_request_id',
        'vendor_id',
        'file_path',
        'description',
        'status',
        'auditor_notes',
    ];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class, 'procurement_request_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
