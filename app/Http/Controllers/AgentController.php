<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WSAgent;
use App\Models\WSInstance;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents = WSAgent::all();
        return view ('dashboards.agent.index', [
            'agents' => $agents
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instances = WSInstance::all();
        $languages = [
            'python' => ['icon' => 'python.svg', 'agents' => ['FlaskAgent', 'FastAPIAgent']],
            'java' => ['icon' => 'java.svg', 'agents' => ['SpringAgent', 'VertxAgent']],
            'golang' => ['icon' => 'golang.svg', 'agents' => ['GoAgent']],
            'javascript' => ['icon' => 'javascript.svg', 'agents' => ['NodeAgent']],
            'php' => ['icon' => 'php.svg', 'agents' => ['LaravelAgent', 'SymfonyAgent']],
            'ruby' => ['icon' => 'ruby.svg', 'agents' => ['RailsAgent']],
        ];  
        return view ('dashboards.agent.create', [
            'instances' => $instances,
            'languages' => $languages,
        ]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
