<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementFile extends Model
{
    use HasFactory;

    protected $fillable = ['tender_config_id', 'file_name', 'file_path', 'file_type'];

    public function tenderConfig()
    {
        return $this->belongsTo(TenderConfig::class, 'tender_config_id');
    }
}
