<?php

namespace App;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * Trait HasNotes
 *
 * @author Marlon Castillo
 */
trait HasNotes
{
    /**
     * Get the current author's ID.
     *
     * @return int|null The current author's ID.
     */
    protected function getCurrentAuthorId(): ?int
    {
        return Auth::id();
    }

    /**
     * Prepare the note's attributes
     *
     * @param  string  $content  The content of the note.
     * @param  \Illuminate\Database\Eloquent\Model|null  $author  The author of the note.
     * @return array<string, mixed> The note's attributes.
     */
    protected function prepareNoteAttributes(string $content, ?Model $author = null): array
    {
        return [
            'content' => $content,
            'user_id' => is_null($author) ? $this->getCurrentAuthorId() : $author->getKey(),
        ];
    }

    /**
     * The Notes relationship.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Add a note to the model.
     *
     * @param  string  $content  The content of the note.
     * @param  \Illuminate\Database\Eloquent\Model|null  $author  The author of the note.
     * @param  bool  $reload  Indicates if the model should be reloaded. True by default.
     * @return \App\Models\Note The created note.
     */
    public function addNote(string $content, ?Model $author = null, bool $reload = true): Note
    {
        $note = $this->notes()->create(
            $this->prepareNoteAttributes($content, $author)
        );

        if ($reload) {
            $relationships = array_merge(
                ['notes'],
                method_exists($this, 'authoredNotes') ? ['authoredNotes'] : []
            );
            $this->load($relationships);
        }

        return $note;
    }
}
