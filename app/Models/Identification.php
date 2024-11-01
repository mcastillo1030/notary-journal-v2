<?php

namespace App\Models;

use App\HasNotes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Identification extends Model
{
    /** @use HasFactory<\Database\Factories\IdentificationFactory> */
    use HasFactory;

    use HasNotes;

    protected $fillable = [
        'type',
        'document_type',
        'document_name',
        'document_number',
        'issue_date',
        'expiration_date',
    ];

    protected $appends = [
        'type_label',
    ];

    protected function typeLabel(): Attribute
    {
        // return Attribute::make(
        //     get: fn (string $value) => config('constants.identification.types')[$value],
        // );

        return new Attribute(
            get: fn () => config('constants.identification.types')[$this->type],
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
