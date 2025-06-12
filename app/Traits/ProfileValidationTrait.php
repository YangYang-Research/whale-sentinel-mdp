<?php

namespace App\Traits;

trait ProfileValidationTrait
{
    /**
     * Validate dot-notation keys in payload against hardcoded valid keys.
     *
     * @param array $payload
     * @return true|array
     */
    public function validatePayloadKeysExistHardcoded(array $payload)
    {
        $validKeys = [
            // 'running_mode',
            // 'last_run_mode',
            'lite_mode_data_is_synchronized',
            'lite_mode_data_synchronize_status',
            // 'ws_module_web_attack_detection.enable',
            // 'ws_module_web_attack_detection.detect_header',
            // 'ws_module_web_attack_detection.threshold',
            // 'ws_module_dga_detection.enable',
            // 'ws_module_dga_detection.threshold',
            // 'ws_module_common_attack_detection.enable',
            // 'ws_module_common_attack_detection.detect_cross_site_scripting',
            // 'ws_module_common_attack_detection.detect_http_large_request',
            // 'ws_module_common_attack_detection.detect_sql_injection',
            // 'ws_module_common_attack_detection.detect_http_verb_tampering',
            // 'secure_response_headers.enable',
            // 'secure_response_headers.headers.Server',
            // 'secure_response_headers.headers.X-Whale-Sentinel',
            // 'secure_response_headers.headers.Referrer-Policy',
            // 'secure_response_headers.headers.X-Content-Type-Options',
            // 'secure_response_headers.headers.X-XSS-Protection',
            // 'secure_response_headers.headers.X-Frame-Options',
            // 'secure_response_headers.headers.Strict-Transport-Security',
            // 'secure_response_headers.headers.X-Permitted-Cross-Domain-Policies',
            // 'secure_response_headers.headers.Expect-CT',
            // 'secure_response_headers.headers.Feature-Policy',
            // 'secure_response_headers.headers.Cache-Control',
            // 'secure_response_headers.headers.Pragma',
            // 'secure_response_headers.headers.Expires',
            // 'secure_response_headers.headers.X-UA-Compatible',
            // 'secure_response_headers.headers.Access-Control-Allow-Origin',
            // 'secure_response_headers.headers.Access-Control-Allow-Methods',
            // 'secure_response_headers.headers.Access-Control-Allow-Credentials',
            // 'secure_response_headers.headers.Access-Control-Allow-Headers'
        ];

        $flattenPayload = $this->flattenArray($payload);
        $invalidKeys = [];

        foreach (array_keys($flattenPayload) as $key) {
            if (!in_array($key, $validKeys, true)) {
                $invalidKeys[] = $key;
            }
        }

        return empty($invalidKeys) ? true : $invalidKeys;
    }

    /**
     * Flatten nested arrays using dot notation.
     */
    private function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : $prefix . '.' . $key;
            if (is_array($value)) {
                $result += $this->flattenArray($value, $newKey);
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
