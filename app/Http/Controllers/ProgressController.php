<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function updateProgress(Request $request)
    {
        $validated = $request->validate([
            'current_material_id' => 'required|integer',
        ]);

        // Assuming you have a 'progress' table with user_id and current_material_id
        $progress = Progress::updateOrCreate(
            ['user_id' => currentUserId()], // Find by user
            ['current_material_id' => $validated['current_material_id']] // Update or create with new material ID
        );

        return response()->json(['success' => true]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Progress $progress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Progress $progress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Progress $progress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Progress $progress)
    {
        //
    }
}
