<?php

declare(strict_types=1);

namespace App\Modules\Endpoints\Infrastructure\Persistence\Eloquent;

use App\Models\User;
use App\Modules\Hits\Infrastructure\Persistence\Eloquent\Hit as HitModel;
use Database\Factories\EndpointFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Endpoint extends Model
{
    use HasUuids;
    /** @use HasFactory<\Database\Factories\EndpointFactory> */
    use HasFactory;
    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'path',
        'name',
    ];
    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return EndpointFactory::new();
    }

    /**
     * Get the user that owns the endpoint.
     * @return BelongsTo<User, Endpoint>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<HitModel, Endpoint>
     */
    public function hits(): HasMany
    {
        return $this->hasMany(HitModel::class);
    }

    public function toEntity(): \App\Modules\Endpoints\Domain\Endpoint
    {
        return new \App\Modules\Endpoints\Domain\Endpoint(
            $this->id,
            $this->user_id,
            $this->path,
            $this->name,
            $this->unique_hits ?? 0,
            $this->total_hits ?? 0,
            $this->created_at,
        );
    }
}
