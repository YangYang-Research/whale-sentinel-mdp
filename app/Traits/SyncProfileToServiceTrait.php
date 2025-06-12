<?php

namespace App\Traits;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

trait SyncProfileToServiceTrait
{
    public function makeRequestSyncProfileToService(string $type, string $id, string $name)
    {
        $baseUrl = rtrim(env('WS_MODULE_CONFIGURATION_SERVICE_URL'), '/');
        $endpoint = ltrim(env('WS_MODULE_CONFIGURATION_SERVICE_ENDPOINT'), '/');
        $url = "{$baseUrl}/{$endpoint}/mcp/synchronize";

        $verifyTls = filter_var(env('WHALE_SENTINEL_VERIFY_TLS', false), FILTER_VALIDATE_BOOLEAN);

        $body = [
            "event_info" => "ws_agent_1|WS_CONTROLLER_SERVICE|2d482faf4f3e33c47561687d88f920c7608aee4c4f18ad5610c9d97f209ac604",
            "payload" => [
                "data" => [
                    "type" => $type,
                    "name" => $name,
                    "id" => $id
                ]
            ],
            "request_created_at" => Carbon::now('UTC')->format('Y-m-d\TH:i:s\Z')
        ];

        try {
            $response = Http::post($url, $body);
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}