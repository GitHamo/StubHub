<?php

declare(strict_types=1);

namespace App\Modules\Endpoints\Infrastructure\Persistence\Eloquent;

use Database\Factories\EndpointFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return EndpointFactory::new();
    }
}
