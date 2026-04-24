<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SurveyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'surveyor_id',
        'office_condition',
        'infrastructure_score',
        'notes',
        'survey_photo',
        'status',         
        'auditor_notes'
    ];

    // Relasi ke Vendor yang disurvey
    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Petugas Survey
    public function surveyor()
    {
        return $this->belongsTo(User::class, 'surveyor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}