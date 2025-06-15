<?php

namespace App\Traits;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

trait SyncProfileToServiceTrait
{
    public function makeRequestSyncProfileToService(string $type, string $id, string $name)
    {
        $baseUrl = rtrim(config('services.whale_sentinel_services.module_configuration_service_url'), '/');
        $endpoint = ltrim(config('services.whale_sentinel_services.module_configuration_service_endpoint'), '/');
        $url = "{$baseUrl}/{$endpoint}/mcp/synchronize";
        
        $verifyTls = filter_var(config('services.whale_sentinel_services.verify_tls'), FILTER_VALIDATE_BOOLEAN);

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
            $response = Http::withOptions(['verify' => $verifyTls])
            ->withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->post($url, $body);
            return $response;
        } catch (\Exception $e) {
            return null;
        }
    }
}