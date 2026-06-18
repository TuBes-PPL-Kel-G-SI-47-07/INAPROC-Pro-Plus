<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone_number
 * @property string|null $address
 * @property string|null $profile_picture
 * @property string|null $position
 * @property string $status
 * @property-read \App\Models\SurveyReport|null $surveyReport
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method bool hasRole(string|array|\Spatie\Permission\Contracts\Role ...$roles)
 * @method $this assignRole(string|array|\Spatie\Permission\Contracts\Role ...$roles)
 */
#[Fillable([
    'name', 
    'email', 
    'password', 
    'phone_number', 
    'address', 
    'profile_picture', 
    'position'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function surveyReport()
    {
        return $this->hasOne(SurveyReport::class, 'user_id');
    }
}