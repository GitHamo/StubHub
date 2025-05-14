<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\SubscriptionType;
use App\Enums\UserRole;
use App\Models\Eloquent\Endpoint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property UserRole $role
 * @property SubscriptionType $subscription_type
 * @property bool $is_active
 * @property string $name
 * @property string $email
 * @property ?\DateTime $email_verified_at
 */
class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role',
        'subscription_type',
        'is_active',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'subscription_type' => SubscriptionType::class,
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function getSubscriptionType(): SubscriptionType
    {
        return $this->subscription_type;
    }

    public function getEndpointsCount(): int
    {
        return $this->endpoints()->count();
    }

    /**
     * @return HasMany<Endpoint, $this>
     */
    public function endpoints(): HasMany
    {
        return $this->hasMany(Endpoint::class);
    }
}
