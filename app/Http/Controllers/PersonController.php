<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Person;
// use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonController extends Controller
{
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

    public function addNote(Person $person)
    {
        // get content from request
        $content = request()->validate([
            'content' => 'required|string',
        ]);

        // create note
        $note = $person->addNote($content['content']);

        // return json response
        return response()->json([
            'created' => $note->exists,
            'message' => $note->exists ? 'Note added' : 'Note could not be created',
            'type' => 'note',
        ]);
    }

    public function updateNote(Person $person, Note $note)
    {
        $updated = false;

        if ($note->noteable_id === $person->id && $note->noteable_type === Person::class) {
            // get content from request
            $content = request()->validate([
                'content' => 'required|string',
            ]);

            // update note
            $updated = $note->update($content);
        }

        // return json response
        return response()->json([
            'updated' => $updated,
            'message' => $updated ? 'Note updated' : 'Note could not be updated',
            'type' => 'note',
        ]);
    }

    public function removeNote(Person $person, Note $note)
    {
        $destroyed = true;
        // delete if note belongs to person
        if ($note->noteable_id === $person->id && $note->noteable_type === Person::class) {
            $destroyed = $note->delete();
        }

        // return json response
        return response()->json([
            'destroyed' => $destroyed,
            'message' => $destroyed ? 'Note destroyed' : 'Note could not be destroyed',
            'type' => 'note',
        ]);
    }
}
