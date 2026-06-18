<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action', 'description', 'previous_hash', 'current_hash'];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            throw new \Exception('Activity Log cannot be updated. It is immutable.');
        });

        static::deleting(function ($model) {
            throw new \Exception('Activity Log cannot be deleted. It is immutable.');
        });

        static::creating(function ($model) {
            $lastLog = self::latest('id')->first();
            $previousHash = $lastLog ? $lastLog->current_hash : null;

            $model->previous_hash = $previousHash;
            
            // Set created_at if it's not set, to ensure accurate hashing
            if (!$model->created_at) {
                $model->created_at = now();
            }

            // Generate current hash
            $dataString = $model->user_id . $model->action . $model->description . $model->created_at . $previousHash;
            $model->current_hash = hash('sha256', $dataString);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
