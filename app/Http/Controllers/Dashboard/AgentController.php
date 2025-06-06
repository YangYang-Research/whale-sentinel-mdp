<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WSAgent;
use App\Models\WSApplication;
use App\Models\WSProfile;
use Illuminate\Support\Str;

class AgentController extends Controller
{
    private function isValidJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents = WSAgent::all();

        $languages = config('languages');

        return view ('dashboards.agent.index', [
            'agents' => $agents,
            'languages' => $languages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $applications = WSApplication::whereDoesntHave('agent')->get();
        
        $languages = config('languages');

        $profiles = WSProfile::where('type', 'agent')->get();

        return view ('dashboards.agent.create', [
            'applications' => $applications,
            'languages' => $languages,
            'profiles' => $profiles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, WSAgent $agent)
    {
        $validated = $request->validate([
            'name'    => 'required|max:255',
            'description' => 'required|max:255',
            'agent_type' => 'required|in:FlaskAgent,DjangoAgent,FastAPIAgent,SpringAgent,GinAgent,NodeAgent,LaravelAgent,RailsAgent',
            'profile' => 'required|string',
        ]);



        $isValidJson = $this->isValidJson($request->profile);
        if (!$isValidJson) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['profile' => 'Profile must be a valid JSON string.']);
        }

        $rawKey = $request->name . '_' . Str::random(12) . now()->timestamp;

        $agent = new WSAgent;
        $agent->application_id = $request->application_id;
        $agent->name = 'ws_agent_'.$request->name;
        $agent->key = hash('sha256', $rawKey);
        $agent->description = $request->description;
        $agent->type = $request->agent_type;
        $agent->profile = $request->profile;
        $agent->save();
        return redirect()->route('agent.index')->with(['message' => 'Agent created successfully.']);
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
        $agent = WSAgent::find($id);

        $applications = WSApplication::all();
        
        $languages = config('languages');

        $profiles = WSProfile::where('type', 'agent')->get();

        return view('dashboards.agent.edit', [
            'agent' => $agent,
            'applications' => $applications,
            'languages' => $languages,
            'profiles' => $profiles
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
            'agent_type' => 'required|in:FlaskAgent,DjangoAgent,FastAPIAgent,SpringAgent,GinAgent,NodeAgent,LaravelAgent,RailsAgent',
            'profile' => 'required|string',
        ]);



        $isValidJson = $this->isValidJson($request->profile);
        if (!$isValidJson) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['profile' => 'Profile must be a valid JSON string.']);
        }

        $rawKey = $request->name . '_' . Str::random(12) . now()->timestamp;

        $agent = WSAgent::find($id);
        $agent->application_id = $request->application_id;
        $agent->name = 'ws_agent_'.$request->name;
        $agent->key = hash('sha256', $rawKey);
        $agent->description = $request->description;
        $agent->type = $request->agent_type;
        $agent->profile = $request->profile;
        $agent->save();
        return redirect()->route('agent.index')->with(['message' => 'Agent updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
