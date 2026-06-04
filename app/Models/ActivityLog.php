<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string|null $description
 * @property string|null $ip_address
 * @property string|null $table_affected
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 */
class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action', 'description', 'ip_address', 'table_affected'];

    protected static function booted()
    {
        static::creating(function (ActivityLog $log) {
            if (empty($log->ip_address) && request()) {
                $log->ip_address = request()->ip();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
