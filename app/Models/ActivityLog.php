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

protected $fillable = [
        'user_id', 
        'action', 
        'description', 
        'ip_address', 
        'table_affected', 
        'previous_hash', 
        'current_hash'
    ];

    protected static function boot()
    {
        parent::boot();

        // Mencegah log diubah (Immutable)
        static::updating(function ($model) {
            throw new \Exception('Activity Log cannot be updated. It is immutable.');
        });

        // Mencegah log dihapus (Immutable)
        static::deleting(function ($model) {
            throw new \Exception('Activity Log cannot be deleted. It is immutable.');
        });

        static::creating(function ($model) {
            // 1. Ambil IP Address otomatis jika kosong
            if (empty($model->ip_address) && request()) {
                $model->ip_address = request()->ip();
            }

            // 2. Ambil hash dari log terakhir di database
            $lastLog = self::latest('id')->first();
            $previousHash = $lastLog ? $lastLog->current_hash : null;

            $model->previous_hash = $previousHash;
            
            // Set created_at untuk akurasi hashing
            if (!$model->created_at) {
                $model->created_at = now();
            }

            // 3. Generate current hash (Gabungkan semua data termasuk IP agar makin aman)
            $dataString = $model->user_id . $model->action . $model->description . $model->ip_address . $model->table_affected . $model->created_at . $previousHash;
            $model->current_hash = hash('sha256', $dataString);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
