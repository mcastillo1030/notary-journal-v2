<?php

namespace App\Models;

use App\HasNotes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identification extends Model
{
    /** @use HasFactory<\Database\Factories\IdentificationFactory> */
    use HasFactory;

    use HasNotes;
}
