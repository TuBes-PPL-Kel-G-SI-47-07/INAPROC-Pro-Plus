<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
