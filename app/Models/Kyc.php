<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $uuid
 * @property bool $state
 * @property string $user_uuid
 * @property string $translation_uuid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Kyc extends Model
{
    const DOCUMENT_PATH = 'kyc';

    public $fillable = [
        'user_id',
        'dob',
        'document_type',
        'document_file',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
