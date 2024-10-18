<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
    ];

    protected $hidden = [
        'noteable_id',
        'noteable_type',
    ];

    /**
     * Get a human-readable date for the note.
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('M j, Y \a\t g:i a'),
        );
    }

    /**
     * The noteable relathionship.
     */
    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the Note
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
