<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProgress extends Model
{
    use HasFactory;

    protected $table = 'project_progresses';

    protected $fillable = [
        'procurement_request_id',
        'vendor_id',
        'percentage',
        'description',
        'photo_path',
        'latitude',
        'longitude',
        'taken_at',
        'status',
        'auditor_notes',
    ];

    protected $casts = [
        'taken_at' => 'datetime',
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
