<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Note;
use App\Models\Person;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $content = $request->validate([
            'content' => 'required|string',
            'noteable_id' => 'required|integer',
            'noteable_type' => 'required|string',
        ]);

        $type = $content['noteable_type'] === 'person' ? Person::class : Address::class;

        // store note
        $note = $type::find($content['noteable_id'])->addNote($content['content']);

        // return json response
        return response()->json([
            'created' => $note->exists,
            'message' => $note->exists ? 'Note created successfully' : 'Failed to create note',
            'type' => 'note',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $updated = $note->update(
            $request->validate([
                'content' => 'required|string',
            ])
        );

        // return json response
        return response()->json([
            'updated' => $updated,
            'message' => $updated ? 'Note updated successfully' : 'Failed to update note',
            'type' => 'note',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $destroyed = $note->delete();

        // return json response
        return response()->json([
            'destroyed' => $destroyed,
            'message' => $destroyed ? 'Note destroyed' : 'Failed to destroy note',
            'type' => 'note',
        ]);
    }
}
