<?php

declare(strict_types=1);

namespace App\Modules\Hits\Infrastructure\Persistence\Eloquent;

use Database\Factories\HitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hit extends Model
{
    /** @use HasFactory<\Database\Factories\HitFactory> */
    use HasFactory;
    public $timestamps = false; // disables both created_at and updated_at
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'endpoint_hits';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'endpoint_id',
        'signature',
        'created_at',
    ];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        // Laravel to auto-manage only `created_at`
        static::creating(fn (Model $model): \Illuminate\Support\Carbon => $model->created_at ??= now());
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return HitFactory::new();
    }
}
