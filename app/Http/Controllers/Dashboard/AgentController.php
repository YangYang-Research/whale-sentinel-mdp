<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WSAgent;
use App\Models\WSApplication;
use App\Models\WSProfile;
use Illuminate\Support\Str;
use App\Jobs\SyncProfileToServiceJob;

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
            'application_id' => 'required|integer',
            'name'    => 'required|max:255',
            'description' => 'required|max:255',
            'agent_type' => 'required|in:FlaskAgent,DjangoAgent,FastAPIAgent,SpringAgent,GinAgent,NodeAgent,NextAgent,LaravelAgent,CakeAgent,RailsAgent',
            'profile' => 'required|string',
        ]);



        $isValidJson = $this->isValidJson($request->profile);
        if (!$isValidJson) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['profile' => 'Profile must be a valid JSON string.']);
        }

        $rawAgentId = $request->name . '_' . Str::random(12) . now()->timestamp;

        $agent = new WSAgent;
        $agent->application_id = $request->application_id;
        $agent->name = 'ws_agent_'.preg_replace('/\s+/', '', $request->name);;
        $agent->agent_id = hash('sha256', $rawAgentId);
        $agent->description = $request->description;
        $agent->type = $request->agent_type;
        $agent->profile = $request->profile;
        $agent->save();

        SyncProfileToServiceJob::dispatch('agent', $agent->agent_id, $agent->name);
        
        return redirect()->route('agent.index')->with(['message' => 'Agent created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $agent = WSAgent::find($id);

        $applications = WSApplication::all();
        
        $languages = config('languages');


        return view('dashboards.agent.show', [
            'agent' => $agent,
            'applications' => $applications,
            'languages' => $languages,
        ]);
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
            'application_id' => 'required|integer',
            'name'    => 'required|max:255',
            'description' => 'required|max:255',
            'agent_type' => 'required|in:FlaskAgent,DjangoAgent,FastAPIAgent,SpringAgent,GinAgent,NodeAgent,NextAgent,LaravelAgent,CakeAgent,RailsAgent',
            'profile' => 'required|string',
        ]);



        $isValidJson = $this->isValidJson($request->profile);
        if (!$isValidJson) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['profile' => 'Profile must be a valid JSON string.']);
        }


        $agent = WSAgent::find($id);
        $agent->application_id = $request->application_id;
        $agent->name = 'ws_agent_'.preg_replace('/\s+/', '', $request->name);;
        $agent->description = $request->description;
        $agent->type = $request->agent_type;
        $agent->profile = $request->profile;
        $agent->save();

        SyncProfileToServiceJob::dispatch('agent', $agent->agent_id, $agent->name);

        return redirect()->route('agent.index')->with(['message' => 'Agent updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function exportEnv(Request $request)
    {
        $id = $request->id;
        
        $agent = WSAgent::find($id);

        $envContent = <<<ENV
            WS_AGENT_NAME="{$agent->name}"

            WS_AGENT_ID="{$agent->agent_id}"

            LOG_MAX_SIZE=1000000

            LOG_MAX_BACKUP=3

            WS_GATEWAY_API="your_whale_sentinel_gateway_api"

            WS_AGENT_AUTH_TOKEN="your_agent_authentication_token"
            ENV;
        
        $fileName = $agent->name.'.env';

        return response($envContent, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }
}
