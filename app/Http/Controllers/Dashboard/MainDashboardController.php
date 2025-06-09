<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WSInstance;
use App\Models\WSAgent;
use App\Models\WSApplication;

class MainDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instances = WSInstance::all();

        $applications = WSApplication::all();

        $agents = WSAgent::all();

        $languageCounts = $applications->groupBy('language')->map->count();

        $languages = config('languages');

        return view ("dashboards.index",[
            'instances' => $instances,
            'applications' => $applications,
            'agents' => $agents,
            'languageCounts' => $languageCounts,
            'languages' => $languages,
        ]);
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
