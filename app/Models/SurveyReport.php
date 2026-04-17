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
        'survey_photo'
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
}