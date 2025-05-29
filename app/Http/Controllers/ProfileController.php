<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WSService;
use App\Models\WSAgent;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:agent,service',
                'key'  => ['required','string', 'max:255']]);
            
            if ($validated['type'] === 'agent' && !preg_match('/^ws_agent_.*/', $validated['key'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The key must start with "ws_agent_" for type "agent".'
                ], 422);
            }

            $type = $validated['type'];
            $key = $validated['key'];

            if ($type === 'agent') {
                $agent = WsAgent::where('name', $key)->first();
                if (!$agent) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Agent not found'
                    ], 404);
                }
                return response()->json([
                    'status'  => 'success',
                    'type'    => 'agent',
                    'name'    => $agent->name,
                    'data'  => [
                        'profile'     => $agent->profile,
                    ],
                ]);
            }

            if ($type === 'service') {
                $service = WsService::where('name', $key)->first();
                if (!$service) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Service not found'
                    ], 404);
                }
                return response()->json([
                    'status'  => 'success',
                    'type'    => 'service',
                    'name'    => $service->name,
                    'data'  => [
                        'profile'     => $service->profile,
                    ],
                ]);
            }

            return response()->json(['message' => 'Invalid type'], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request.',
            ], 500);
        }
    }
}
