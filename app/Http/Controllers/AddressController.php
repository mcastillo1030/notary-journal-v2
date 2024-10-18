<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Person;

class AddressController extends Controller
{
    public function store()
    {
        $created = false;
        $address_attributes = request()->validate([
            'line_1' => 'required|string',
            'line_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|alpha',
            'zip' => 'required|regex:/^\d{5}(-\d{4})?$/',
        ]);

        if (request()->has('person_id')) {
            $person = Person::find(request('person_id'));

            if ($person) {
                $address = $person->addresses()->create($address_attributes);
                $created = $address->exists;
            }
        }

        // return json response
        return response()->json([
            'created' => $created,
            'message' => $created ? 'Address created successfully' : 'Failed to create address',
            'type' => 'address',
        ]);
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
}
