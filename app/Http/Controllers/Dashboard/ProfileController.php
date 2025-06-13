<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WSProfile;

class ProfileController extends Controller
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
        $profiles = WSProfile::all();
        return view ('dashboards.profile.index', [
            'profiles' => $profiles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('dashboards.profile.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, WSProfile $profile)
    {
        $validated = $request->validate([
            'type'    => 'required|in:agent,common-attack-detection-service',
            'name'    => 'required|max:255',
            'description' => 'required|max:255',
            'profile' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $isValidJson = $this->isValidJson($request->profile);
        if (!$isValidJson) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['profile' => 'Profile must be a valid JSON string.']);
        }

        $profile = new WSProfile;
        $profile->type = $request->type;
        $profile->name = $request->name;
        $profile->description = $request->description;
        $profile->profile = $request->profile;
        $profile->status = $request->status;
        $profile->save();
        return redirect()->route('profile.index')->with(['message' => 'Profile created successfully.']);
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
        $profile = WSProfile::find($id);
        return view ('dashboards.profile.edit', [
            'profile' => $profile
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'type'    => 'required|in:agent,common-attack-detection-service',
            'name'    => 'required|max:255',
            'description' => 'required|max:255',
            'profile' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $isValidJson = $this->isValidJson($request->profile);
        if (!$isValidJson) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['profile' => 'Profile must be a valid JSON string.']);
        }

        $profile = WSProfile::find($id);
        $profile->type = $request->type;
        $profile->name = $request->name;
        $profile->description = $request->description;
        $profile->profile = $request->profile;
        $profile->status = $request->status;
        $profile->save();
        return redirect()->route('profile.index')->with(['message' => 'Profile updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
