<?php

namespace App;

use App\Models\Note;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait AuthoredNotes
 *
 * @author Marlon Castillo
 */
trait AuthoredNotes
{
    /**
     * Get all of the Authored Notes for the User
     */
    public function authoredNotes(): HasMany
    {
        return $this->hasMany(Note::class, 'user_id');
    }
}
