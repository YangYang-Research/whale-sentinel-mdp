<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WSInstance;

class InstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instances = WSInstance::all();
        return view ('dashboards.instance.index', [
            'instances' => $instances
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('dashboards.instance.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|max:255',
            'description' => 'required|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $instance = new WSInstance;
        $instance->name = $request->name;
        $instance->description = $request->description;
        $instance->status = $request->status;
        $instance->save();
        return redirect()->route('instance.index')->with(['message' => 'Instance created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $instance = WSInstance::find($id);
        return view ('dashboards.instance.edit', [
            'instance' => $instance
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'    => 'required|max:255',
            'description' => 'required|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        
        $instance = WSInstance::find($id);
        $instance->name = $request->name;
        $instance->description = $request->description;
        $instance->status = $request->status;
        $instance->save();
        return redirect()->route('instance.index')->with(['message' => 'Instance updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
