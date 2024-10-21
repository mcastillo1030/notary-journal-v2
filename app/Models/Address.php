<?php

namespace App\Models;

use App\HasNotes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    use HasNotes;

    protected $fillable = [
        'line_1',
        'line_2',
        'city',
        'state',
        'zip',
    ];

    /**
     * Get all of the people that are associated with this address.
     */
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'address_person');
    }
}
