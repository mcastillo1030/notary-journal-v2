<?php

namespace App\Models;

use App\HasNotes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    /** @use HasFactory<\Database\Factories\PersonFactory> */
    use HasFactory;

    use HasNotes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
    ];

    /**
     * Get a US-formatted phone number for the person.
     */
    protected function phone(): Attribute
    {
        return Attribute::make(
            // phone can be in +1-555-555-5555 or +1 (555) 555-5555 or 1-555-555-5555 format and we need it in (555) 555-5555 format
            get: function (?string $value) {
                // remove leading +1 or +1- or 1-
                $value = preg_replace('/^(\+1-|1-|\+1)/', '', $value);

                // may still have dashes, or parens, or spaces, or dots
                $value = preg_replace('/[-.() ]/', '', $value);

                // format as (555) 555-5555
                return preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '($1) $2-$3', $value);
            },
        );
    }

    /**
     * Get all of the addresses for the Person
     */
    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'address_person');
    }

    public function identifications(): HasMany
    {
        return $this->hasMany(Identification::class);
    }
}
