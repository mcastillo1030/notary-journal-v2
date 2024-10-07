<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonController extends Controller
{
    public function index(Request $request): Response
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

    public function show(Person $person)
    {
        return Inertia::render('People/Show', [
            'person' => $person,
            'addresses' => $person->addresses,
        ]);
    }

    public function edit(Person $person)
    {
        // TODO: Implement edit() method.
        return Inertia::render('People/Index', [
            'items' => Person::all()->map->only(['id', 'first_name', 'last_name', 'email', 'phone']),
        ]);
    }

    public function update(Person $person)
    {
        // TODO: Implement update() method.
        return Inertia::render('People/Index', [
            'items' => Person::all()->map->only(['id', 'first_name', 'last_name', 'email', 'phone']),
        ]);
    }

    public function destroy(Person $person)
    {
        // TODO: Implement destroy() method.
        return Inertia::render('People/Index', [
            'items' => Person::all()->map->only(['id', 'first_name', 'last_name', 'email', 'phone']),
        ]);
    }
}
