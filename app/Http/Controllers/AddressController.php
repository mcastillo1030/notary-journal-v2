<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Person;
use Illuminate\Http\Client\Request;
use Inertia\Inertia;

class AddressController extends Controller
{
    private function pluralizeAttachableName(string $name): string
    {
        return $name === 'person' ? 'people' : $name.'s';
    }

    public function index()
    {
        return Inertia::render('Addresses/Index', [
            'addresses' => Address::all()->map->only(['id', 'line_1', 'line_2', 'city', 'state', 'zip']),
        ]);
    }

    public function show(Address $address)
    {
        return Inertia::render('Addresses/Show', [
            'address' => fn () => $address,
            'people' => fn () => $address->people,
            'notes' => fn () => $address->notes()->latest()->get(),
        ]);
    }

    public function store()
    {
        // $created = false;
        $jsonResponse = [
            'created' => false,
            'message' => 'Failed to create address',
            'type' => 'address',
        ];
        $addressAttributes = request()->validate([
            'line_1' => 'required|string',
            'line_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|alpha',
            'zip' => 'required|regex:/^\d{5}(-\d{4})?$/',
        ]);

        // Check if address exists
        $exists = Address::where($addressAttributes)->exists();
        if ($exists) {
            $jsonResponse['message'] = 'Address already exists';

            return response()->json($jsonResponse);
        }

        if (request()->has('person_id')) {
            $person = Person::find(request('person_id'));

            if ($person) {
                $address = $person->addresses()->create($addressAttributes);
                $jsonResponse['created'] = $address->exists;
            }
        } else {
            $address = Address::create($addressAttributes);
            $jsonResponse['created'] = $address->exists;
        }

        $jsonResponse['message'] = $jsonResponse['created'] ? 'Address created successfully' : 'Failed to create address';

        // return json response
        return response()->json($jsonResponse);
    }

    public function update(Address $address)
    {
        $updated = $address->update(
            request()
                ->validate([
                    'line_1' => 'required|string',
                    'line_2' => 'nullable|string',
                    'city' => 'required|string',
                    'state' => 'required|alpha',
                    'zip' => 'required|regex:/^\d{5}(-\d{4})?$/',
                ])
        );

        // return json response
        return response()->json([
            'updated' => $updated,
            'message' => $updated ? 'Address updated successfully' : 'Failed to update address',
            'type' => 'address',
        ]);
    }

    public function destroy(Address $address)
    {
        $destroyed = $address->delete();

        // return json response
        return response()->json([
            'destroyed' => $destroyed,
            'message' => $destroyed ? 'Address deleted successfully' : 'Failed to delete address',
            'type' => 'address',
        ]);
    }

    public function list()
    {
        // Check potential request data for:
        // not_assigned_to: person_id
        // assigned_to: person_id
        // limit: integer

        // All addressess
        $addresses = Address::query();

        // Check if the request has a not_assigned_to key
        if (request()->has('not_assigned_to')) {
            $addresses->whereDoesntHave('people', function ($query) {
                $query->where('person_id', request('not_assigned_to'));
            });
        }

        // Check if the request has an assigned_to key
        if (request()->has('assigned_to')) {
            $addresses->whereHas('people', function ($query) {
                $query->where('person_id', request('assigned_to'));
            });
        }

        // Check if the request has a limit key
        if (request()->has('limit')) {
            $addresses->limit(request('limit'));
        }

        return response()->json([
            'addresses' => $addresses->get()->map->only(['id', 'line_1', 'line_2', 'city', 'state', 'zip']),
        ]);

    }

    public function attach(Address $address)
    {
        // if no attachable_type or attachable_ids, return
        $attachableType = request()->get('attachable_type');
        if (! $attachableType || ! request()->has('attachable_ids')) {
            return response()->json([
                'attached' => false,
                'message' => 'No attachable type or IDs provided',
                'type' => 'attachment',
            ]);
        }

        // detach the provided attachable IDs
        $pluralizedAttachableName = $this->pluralizeAttachableName($attachableType);
        $attached = $address->{$pluralizedAttachableName}()->attach(request('attachable_ids'));

        // return success
        return response()->json([
            'updated' => $attached,
            'message' => ucfirst($pluralizedAttachableName).' attached successfully',
            'type' => $attachableType,
        ]);
    }

    public function detach(Address $address)
    {
        // if no attachable_type or attachable_ids, return
        $attachableType = request()->get('attachable_type');
        if (! $attachableType || ! request()->has('attachable_ids')) {
            return response()->json([
                'detached' => false,
                'message' => 'No attachable type or IDs provided',
                'type' => 'attachment',
            ]);
        }

        // detach the provided attachable IDs
        $pluralizedAttachableName = $this->pluralizeAttachableName($attachableType);
        $detached = $address->{$pluralizedAttachableName}()->detach(request('attachable_ids'));

        return response()->json([
            'updated' => $detached,
            'message' => ucfirst($pluralizedAttachableName).' detached successfully',
            'type' => $attachableType,
        ]);
    }
}
