<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WSService;

class ServiceController extends Controller
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
        $services = WSService::all();

        return view("dashboards.service.index",[
            'services' => $services
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
        $service = WSService::find($id);

        return view("dashboards.service.show",[
            'service' => $service
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $service = WSService::find($id);

        return view("dashboards.service.edit",[
            'service' => $service
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'description' => 'required|max:255',
            'profile' => 'required|string',
        ]);

        $isValidJson = $this->isValidJson($request->profile);
        if (!$isValidJson) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['profile' => 'Profile must be a valid JSON string.']);
        }

        $service = WSService::find($id);
        $service->description = $request->description;
        $service->profile = $request->profile;
        $service->save();
        return redirect()->route('service.index')->with(['message' => 'Profile updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
