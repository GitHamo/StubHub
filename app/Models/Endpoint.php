<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Hit as EndpointHitModel;
use App\Models\User;
use App\Modules\Endpoints\Domain\Endpoint as EndpointEntity;
use Database\Factories\EndpointFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property int $user_id
 * @property string $path
 * @property string $name
 * @property string $inputs
 * @property \DateTimeImmutable $created_at
 * @property ?int $unique_hits
 * @property ?int $total_hits
 */
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
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'path',
        'name',
        'inputs',
    ];
    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): EndpointFactory
    {
        return EndpointFactory::new();
    }

    /**
     * Get the user that owns the endpoint.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<EndpointHitModel, $this>
     */
    public function hits(): HasMany
    {
        return $this->hasMany(EndpointHitModel::class);
    }

    public function toEntity(): EndpointEntity
    {
        return new EndpointEntity(
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
