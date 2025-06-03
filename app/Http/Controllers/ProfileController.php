<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WSService;
use App\Models\WSAgent;
use App\Traits\ProfileValidationTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    use ProfileValidationTrait;

    public function getProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'event_info' => 'required|string',
                'payload.data.type' => ['required', Rule::in(['agent', 'service'])],
                'payload.data.key'  => 'required|string|max:255',
                'request_created_at' => [
                    'required',
                    'date_format:Y-m-d\TH:i:sP',
                ],
            ]); 

            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'status' => 'Fail',
                    'message' => $validator->errors(),
                ]);
            }

            if ($request['payload']['data']['type'] === 'agent' && !preg_match('/^ws_agent_.*/', $request['payload']['data']['key'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The key must start with "ws_agent_" for type "agent".'
                ], 422);
            }

            $eventInfo = $request['event_info'];
            $type = $request['payload']['data']['type'];
            $key = $request['payload']['data']['key'];
            $requestCreatedAt = $request['request_created_at'];
            
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
                    'message' => 'Request processed successfully',
                    'data'  => [
                        'type'    => 'agent',
                        'name'    => $agent->name,
                        'profile' => $agent->profile,
                    ],
                    'event_info' => '',
                    'request_created_at' => $requestCreatedAt,
                    'request_processed_at' => now()->toIso8601String(),
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
                    'message' => 'Request processed successfully',
                    'data'  => [
                        'type'    => 'service',
                        'name'    => $service->name,
                        'profile' => $service->profile,
                    ],
                    'event_info' => '',
                    'request_created_at' => $requestCreatedAt,
                    'request_processed_at' => now()->toIso8601String(),
                ]);
            }

            return response()->json(['message' => 'Invalid type'], 400);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request.',
            ], 500);
        }
    }

    public function syncProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'event_info' => 'required|string',
                'payload.data.type' => ['required', Rule::in(['agent', 'service'])],
                'payload.data.key'  => 'required|string|max:255',
                'payload.data.profile' => 'required|array',
                'request_created_at' => [
                    'required',
                    'date_format:Y-m-d\TH:i:sP',
                ],
            ]); 

            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'status' => 'Fail',
                    'message' => $validator->errors(),
                ]);
            }

            if ($request['payload']['data']['type'] === 'agent' && !preg_match('/^ws_agent_.*/', $request['payload']['data']['key'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The key must start with "ws_agent_" for type "agent".'
                ], 422);
            }

            $eventInfo = $request['event_info'];
            $type = $request['payload']['data']['type'];
            $key = $request['payload']['data']['key'];
            $requestCreatedAt = $request['request_created_at'];
            $newProfileData = $request['payload']['data']['profile'];

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
                    'message' => 'Request processed successfully',
                    'data'  => [
                        'type'    => 'agent',
                        'name'    => $agent->name,
                        'profile' => $agent->profile,
                    ],
                    'event_info' => '',
                    'request_created_at' => $requestCreatedAt,
                    'request_processed_at' => now()->toIso8601String(),
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

                $service->profile = json_encode($currentProfile, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                $service->save();

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Request processed successfully',
                    'data'  => [
                        'type'    => 'service',
                        'name'    => $service->name,
                        'profile' => $service->profile,
                    ],
                    'event_info' => '',
                    'request_created_at' => $requestCreatedAt,
                    'request_processed_at' => now()->toIso8601String(),
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
