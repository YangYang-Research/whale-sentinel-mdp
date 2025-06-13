<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WSAgent;
use App\Models\WSService;
use App\Traits\ProfileValidationTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ProfileController extends Controller
{
    use ProfileValidationTrait;

    public function getProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'event_info' => 'required|string',
                'payload.data.type' => ['required', Rule::in(['agent', 'service'])],
                'payload.data.name'  => 'required|string|max:255',
                'payload.data.id'  => 'max:255',
                'request_created_at' => [
                    'required',
                    'date_format:Y-m-d\TH:i:s\Z',
                ],
            ]); 

            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'status' => 'Fail',
                    'message' => $validator->errors(),
                ]);
            }

            if ($request['payload']['data']['type'] === 'agent' && !preg_match('/^ws_agent_.*/', $request['payload']['data']['name'])) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'The name must start with "ws_agent_" for type "agent".'
                ], 422);
            }

            $eventInfo = $request['event_info'];
            $type = $request['payload']['data']['type'];
            $name = $request['payload']['data']['name'];
            $id = $request['payload']['data']['id'];
            $requestCreatedAt = $request['request_created_at'];
            
            if ($type === 'agent') {
                $agent = WsAgent::where('name', $name)->where('agent_id', $id)->first();
                if (!$agent) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Agent not found'
                    ], 404);
                }

                return response()->json([
                    'status'  => 'Success',
                    'message' => 'Request processed successfully',
                    'data'  => [
                        'type'    => 'agent',
                        'name'    => $agent->name,
                        'id'      => $agent->agent_id,
                        'profile' => $agent->profile,
                    ],
                    'event_info' => '',
                    'request_created_at' => $requestCreatedAt,
                    'request_processed_at' => Carbon::now('UTC')->format('Y-m-d\TH:i:s\Z')
                ]);
            }

            if ($type === 'service') {
                $service = WsService::where('name', $name)->first();
                if (!$service) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Service not found'
                    ], 404);
                }

                return response()->json([
                    'status'  => 'Success',
                    'message' => 'Request processed successfully',
                    'data'  => [
                        'type'    => 'common-attack-detection-service',
                        'name'    => $service->name,
                        'profile' => $service->profile,
                    ],
                    'event_info' => '',
                    'request_created_at' => $requestCreatedAt,
                    'request_processed_at' => Carbon::now('UTC')->format('Y-m-d\TH:i:s\Z'),
                ]);
            }

            return response()->json(['message' => 'Invalid type'], 400);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'status' => 'Error',
                'message' => 'An error occurred while processing your request.',
            ], 500);
        }
    }

    public function syncProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'event_info' => 'required|string',
                'payload.data.type' => ['required', Rule::in(['agent'])],
                'payload.data.name'  => 'required|string|max:255',
                'payload.data.id'  => 'required|string|max:255',
                'payload.data.profile' => 'required|array',
                'payload.data.ipaddress' => 'required|string',
                'request_created_at' => [
                    'required',
                    'date_format:Y-m-d\TH:i:s\Z',
                ],
            ]); 

            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'status' => 'Fail',
                    'message' => $validator->errors(),
                ]);
            }

            if ($request['payload']['data']['type'] === 'agent' && !preg_match('/^ws_agent_.*/', $request['payload']['data']['name'])) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'The name must start with "ws_agent_" for type "agent".'
                ], 422);
            }

            $eventInfo = $request['event_info'];
            $type = $request['payload']['data']['type'];
            $name = $request['payload']['data']['name'];
            $id = $request['payload']['data']['id'];
            $ipAddress = $request['payload']['data']['ipaddress'];
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
                $agent = WsAgent::where('name', $name)->where('agent_id', $id)->first();
                if (!$agent) {
                    return response()->json([
                        'status' => 'Error',
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
                $agent->ipaddress = $ipAddress;
                $agent->status = 'connected';
                $agent->save();

                return response()->json([
                    'status'  => 'Success',
                    'message' => 'Request processed successfully',
                    'data'  => [
                        'type'    => 'agent',
                        'name'    => $agent->name,
                        'id'      => $agent->agent_id,
                        'profile' => $agent->profile,
                    ],
                    'event_info' => '',
                    'request_created_at' => $requestCreatedAt,
                    'request_processed_at' => Carbon::now('UTC')->format('Y-m-d\TH:i:s\Z'),
                ]);
            }

            return response()->json(['message' => 'Invalid type'], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
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
