<?php

namespace App\Http\Controllers;

use App\Models\Person;
// use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonController extends Controller
{
    private function pluralizeAttachableName(string $name): string
    {
        return $name === 'address' ? 'addresses' : $name.'s';
    }

    public function index(): Response
    {
        // Below is old implementation. The new implementation
        // will just return all people, and limit the fields returned
        // to id, first_name, last_name, email, and phone.

        // $page = $request->query('page', 1);
        // $items = Person::paginate(10, ['*'], 'page', $page)->through(fn ($item) => [
        //     'id' => $item->id,
        //     'firstName' => $item->first_name,
        //     'lastName' => $item->last_name,
        //     'email' => $item->email,
        //     'phone' => $item->phone,
        // ]);

        return Inertia::render('People/Index', [
            'people' => Person::all()->map->only(['id', 'first_name', 'last_name', 'email', 'phone']),
        ]);
    }

    public function create()
    {
        // TODO: Implement create() method.
        return Inertia::render('People/Index', [
            'items' => Person::all()->map->only(['id', 'first_name', 'last_name', 'email', 'phone']),
        ]);
    }

    public function store()
    {
        // TODO: Implement store() method.
        return Inertia::render('People/Index', [
            'items' => Person::all()->map->only(['id', 'first_name', 'last_name', 'email', 'phone']),
        ]);
    }

    public function storeAjax()
    {
        // get content from request
        $content = request()->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            // at least one contact method required
            'email' => ['required_without:phone', 'nullable', 'email'],
            'phone' => ['required_without:email', 'nullable', 'string'],
        ]);

        // ensure a person does not already exist and track creation for response
        $isNew = ! Person::where('first_name', $content['first_name'])
            ->where('last_name', $content['last_name'])
            ->where('email', $content['email'])
            ->where('phone', $content['phone'])
            ->exists();
        Person::firstOrCreate($content);

        // return json response
        return response()->json([
            'created' => $isNew,
            'message' => $isNew ? 'Person created' : 'Person already exists',
            'type' => 'person',
        ]);
    }

    public function show(Person $person)
    {
        return Inertia::render('People/Show', [
            'person' => fn () => $person,
            'addresses' => fn () => $person->addresses,
            'notes' => fn () => $person->notes()->latest()->get(),
            'identifications' => fn () => $person->identifications,
        ]);
    }

    public function update(Person $person)
    {
        // TODO: Implement update() method.
        return Inertia::render('People/Index', [
            'items' => Person::all()->map->only(['id', 'first_name', 'last_name', 'email', 'phone']),
        ]);
    }

    public function updateAjax(Person $person)
    {
        // get content from request
        $content = request()->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            // at least one contact method required
            'email' => ['required_without:phone', 'nullable', 'email'],
            'phone' => ['required_without:email', 'nullable', 'string'],
        ]);

        // update person
        $updated = $person->update($content);

        // return json response
        return response()->json([
            'updated' => $updated,
            'message' => $updated ? 'Person updated' : 'Person could not be updated',
            'type' => 'person',
        ]);
    }

    public function destroy(Person $person)
    {
        // TODO: Implement destroy() method.
        return Inertia::render('People/Index', [
            'items' => Person::all()->map->only(['id', 'first_name', 'last_name', 'email', 'phone']),
        ]);
    }

    public function destroyAjax(Person $person)
    {
        $destroyed = $person->delete();

        // return json response
        return response()->json([
            'destroyed' => $destroyed,
            'message' => $destroyed ? 'Person destroyed' : 'Person could not be destroyed',
            'type' => 'person',
        ]);
    }

    public function list()
    {
        // Check potential request data for:
        // not_assigned_to: address_id
        // assigned_to: address_id
        // limit: integer

        // All addressess
        $people = Person::query();

        // Check if the request has a not_assigned_to key
        if (request()->has('not_assigned_to')) {
            $people->whereDoesntHave('addresses', function ($query) {
                $query->where('address_id', request('not_assigned_to'));
            });
        }

        // Check if the request has an assigned_to key
        if (request()->has('assigned_to')) {
            $people->whereHas('addresses', function ($query) {
                $query->where('address_id', request('assigned_to'));
            });
        }

        // Check if the request has a limit key
        if (request()->has('limit')) {
            $people->limit(request('limit'));
        }

        return response()->json([
            'people' => $people->get()->map->only(['id', 'first_name', 'last_name', 'email', 'phone']),
        ]);

    }

    public function attach(Person $person)
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

        // attach the provided attachable IDs
        $pluralizedAttachableName = $this->pluralizeAttachableName($attachableType);
        $attached = $person->{$pluralizedAttachableName}()->attach(request('attachable_ids'));

        return response()->json([
            'updated' => $attached,
            'message' => ucfirst($pluralizedAttachableName).' attached successfully',
            'type' => $attachableType,
        ]);
    }

    public function detach(Person $person)
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
        $detached = $person->{$pluralizedAttachableName}()->detach(request('attachable_ids'));

        return response()->json([
            'updated' => $detached,
            'message' => ucfirst($pluralizedAttachableName).' detached successfully',
            'type' => $attachableType,
        ]);
    }
}
