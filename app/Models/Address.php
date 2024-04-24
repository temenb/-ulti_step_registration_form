<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property string $uuid
 * @property bool $state
 * @property string $user_uuid
 * @property string $translation_uuid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Address extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';

    public $fillable = [
        'state',
        'user_uuid',
        'translation_uuid',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): hasOne
    {
        return $this->hasOne(User::class);
    }
}
