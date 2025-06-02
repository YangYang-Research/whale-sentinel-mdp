<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WSService;
use App\Models\WSAgent;
use App\Traits\ProfileValidationTrait;

class ProfileController extends Controller
{
    use ProfileValidationTrait;

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

    public function syncProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                    'type' => 'required|in:agent,service',
                    'key'  => ['required','string', 'max:255'],
                    'profile' => 'required|array'
                ]);

            if ($validated['type'] === 'agent' && !preg_match('/^ws_agent_.*/', $validated['key'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The key must start with "ws_agent_" for type "agent".'
                ], 422);
            }

            $type = $validated['type'];
            $key = $validated['key'];
            $newProfileData = $validated['profile'];

            $validation = $this->validatePayloadKeysExistHardcoded($newProfileData);
            if ($validation !== true) {
                return response()->json([
                    'message' => 'Invalid payload keys.',
                    'invalid_keys' => $validation
                ], 422);
            }

            if ($type === 'agent') {
                $agent = WsAgent::where('name', $key)->first();
                if (!$agent) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Agent not found'
                    ], 404);
                }
                $currentProfile = json_decode($agent->profile, true);
                if (!is_array($currentProfile)) {
                    $currentProfile = ['profile' => []];
                }

                if (!isset($currentProfile['profile']) || !is_array($currentProfile['profile'])) {
                    $currentProfile['profile'] = [];
                }

                if (isset($newProfileData['profile'])) {
                    unset($newProfileData['profile']);
                }
                $currentProfile['profile'] = $this->deepMerge($currentProfile['profile'], $newProfileData);

                $agent->profile = json_encode($currentProfile, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                $agent->save();

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
                $currentProfile = json_decode($service->profile, true);
                if (!is_array($currentProfile)) {
                    $currentProfile = ['profile' => []];
                }
                
                if (!isset($currentProfile['profile']) || !is_array($currentProfile['profile'])) {
                    $currentProfile['profile'] = [];
                }

                if (isset($newProfileData['profile'])) {
                    unset($newProfileData['profile']);
                }
                
                $currentProfile['profile'] = $this->deepMerge($currentProfile['profile'], $newProfileData);

                $agent->profile = json_encode($currentProfile, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                $agent->save();

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

    /**
     * Recursively merge nested arrays (overwrite right over left).
     */
    private function deepMerge(array $original, array $new): array
    {
        foreach ($new as $key => $value) {
            if (is_array($value) && isset($original[$key]) && is_array($original[$key])) {
                $original[$key] = $this->deepMerge($original[$key], $value);
            } else {
                $original[$key] = $value;
            }
        }

        return $original;
    }
}
