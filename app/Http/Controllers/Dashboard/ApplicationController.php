<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WSApplication;
use App\Models\WSInstance;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = WSApplication::all();

        $languages = config('languages');

        return view ('dashboards.application.index', [
            'applications' => $applications,
            'languages' => $languages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $instances = WSInstance::all();

        $languages = config('languages');

        return view ('dashboards.application.create', [
            'instances' => $instances,
            'languages' => $languages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, WSApplication $application)
    {
        $validated = $request->validate([
            'name'    => 'required|max:255',
            'description' => 'required|max:255',
            'language' => 'required|in:python,java,golang,javascript,php,ruby',
            'status' => 'required|in:active,inactive',
        ]);

        $application = new WSApplication;
        $application->instance_id = $request->instance_id;
        $application->name = $request->name;
        $application->description = $request->description;
        $application->language = $request->language;
        $application->status = $request->status;
        $application->save();
        return redirect()->route('application.index')->with(['message' => 'Application created successfully.']);
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
        $instances = WSInstance::all();

        $application = WSApplication::find($id);

        $languages = config('languages');

        return view ('dashboards.application.edit', [
            'instances' => $instances,
            'application' => $application,
            'languages' => $languages
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
            'language' => 'required|in:python,java,golang,javascript,php,ruby',
            'status' => 'required|in:active,inactive',
        ]);

        $application = WSApplication::find($id);
        $application->instance_id = $request->instance_id;
        $application->name = $request->name;
        $application->description = $request->description;
        $application->language = $request->language;
        $application->status = $request->status;
        $application->save();
        return redirect()->route('application.index')->with(['message' => 'Application updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
