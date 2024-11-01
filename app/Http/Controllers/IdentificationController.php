<?php

namespace App\Http\Controllers;

use App\Models\Identification;
use App\Models\Person;
use Illuminate\Validation\Rule;

class IdentificationController extends Controller
{
    public function update(Identification $identification)
    {
        $requiredIfDocument = Rule::requiredIf(fn () => str_contains(request('type'), 'document'));
        $updated = $identification->update(request()->validate([
            'type' => [
                'required',
                'string',
                'in:'.implode(',', array_keys(config('constants.identification.types'))),
            ],
            'document_name' => [
                'string',
                'nullable',
                $requiredIfDocument,
            ],
            'document_number' => [
                'string',
                'nullable',
                $requiredIfDocument,
            ],
            'issue_date' => [
                'string',
                'nullable',
            ],
            'expiration_date' => [
                'string',
                'nullable',
                $requiredIfDocument,
            ],
        ]));

        return response()->json([
            'updated' => $updated,
            'message' => $updated ? 'Identification updated successfully' : 'Failed to update identification',
            'type' => 'identification',
        ]);
    }

    public function store()
    {
        $jsonResponse = [
            'created' => false,
            'message' => 'Failed to create identification',
            'type' => 'identification',
        ];

        $person = Person::find(request('person_id'));
        $requiredIfDocument = Rule::requiredIf(fn () => str_contains(request('type'), 'document'));
        $identificationAttributes = request()->validate([
            'type' => [
                'required',
                'string',
                'in:'.implode(',', array_keys(config('constants.identification.types'))),
            ],
            'document_name' => [
                'string',
                'nullable',
                $requiredIfDocument,
            ],
            'document_number' => [
                'string',
                'nullable',
                $requiredIfDocument,
            ],
            'issue_date' => [
                'string',
                'nullable',
            ],
            'expiration_date' => [
                'string',
                'nullable',
                $requiredIfDocument,
            ],
            'person_id' => [
                'required',
                'integer',
                'exists:people,id',
            ],
        ]);

        // check if identification exists
        $exists = $person->identifications()->where($identificationAttributes)->exists();

        if ($exists) {
            $jsonResponse['message'] = 'Identification already exists';

            return response()->json($jsonResponse);
        }

        $jsonResponse['created'] = $person->identifications()->create($identificationAttributes)->exists();
        $jsonResponse['message'] = $jsonResponse['created'] ? 'Identification created successfully' : 'Failed to create identification';

        return response()->json($jsonResponse);
    }

    public function destroy(Identification $identification)
    {
        $destroyed = $identification->delete();

        return response()->json([
            'destroyed' => $destroyed,
            'message' => $destroyed ? 'Identification deleted successfully' : 'Failed to delete identification',
            'type' => 'identification',
        ]);
    }

    public function listNames()
    {
        $dbNames = collect();
        $configNames = collect();

        // select non-null document_name values from the db
        if (request()->has('q')) {
            $query = request('q');
            $dbNames = Identification::select('document_name')
                ->whereNotNull('document_name')
                ->where('document_name', 'like', '%'.$query.'%')
                ->distinct()
                ->pluck('document_name');
            $configNames = collect(array_values(config('constants.identification.document_types')))
                ->filter(fn ($name) => str_contains($name, $query));
        } else {
            $dbNames = Identification::select('document_name')
                ->whereNotNull('document_name')
                ->distinct()
                ->pluck('document_name');
            $configNames = collect(array_values(config('constants.identification.document_types')));
        }

        return response()->json(
            $dbNames->merge(
                $configNames
            )->unique()->sort()->values()
        );
    }
}
