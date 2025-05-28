<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WSProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ws_profiles')->insert([
            [
                'name' => 'Agent-Profile-Example',
                'description' => 'This is an example profile for agents.',
                'profile' => '{
  "profile": {
    "running_mode": "protection",
    "last_run_mode": "lite",
    "lite_mode_data_is_synchronized": False,
    "lite_mode_data_synchronize_status": "fail",
    "ws_module_web_attack_detection": {
      "enable": True,
      "detect_header": False,
      "threshold": 80,
    },
    "ws_module_dga_detection": {
      "enable": True,
      "threshold": 80,
    },
    "ws_module_common_attack_detection": {
      "enable": True,
      "detect_cross_site_scripting": True,
      "detect_http_large_request": True,
      "detect_sql_injection": True,
      "detect_http_verb_tampering": True
    },
    "secure_response_headers": {
      "enable": True,
      "headers": {
        "Server": "Whale Sentinel",
        "X-Whale-Sentinel": "1",
        "Referrer-Policy": "no-referrer-when-downgrade",
        "X-Content-Type-Options": "nosniff",
        "X-XSS-Protection": "1; mode=block",
        "X-Frame-Options": "SAMEORIGIN",
        "Strict-Transport-Security": "max-age=31536000; includeSubDomains; preload",
        "X-Permitted-Cross-Domain-Policies": "none",
        "Expect-CT": "enforce; max-age=31536000",
        "Feature-Policy": "fullscreen \'self\'",
        "Cache-Control": "no-cache, no-store, must-revalidate",
        "Pragma": "no-cache",
        "Expires": "0",
        "X-UA-Compatible": "IE=Edge,chrome=1",
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "GET, POST",
        "Access-Control-Allow-Credentials": "True",
        "Access-Control-Allow-Headers": "Content-Type, Authorization"
      }
    }
  }
}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Service-Profile-Example',
                'description' => 'This is an example profile for ws-common-attack-detection service.',
                'profile' => '{
  "profile": {
    "xss_patterns": [
      "(?:https?://|//)[^\\s/]+\\.js",
      "((%3C)|<)((%2F)|/)*[a-z0-9%]+((%3E)|>)",
      "((\\%3C)|<)((\\%69)|i|(\\%49))((\\%6D)|m|(\\%4D))((\\%67)|g|(\\%47))[^\n]+((\\%3E)|>)"
    ],
    "sql_patterns": [
      "(?:select\\s+.+\\s+from\\s+.+)",
      "(?:insert\\s+.+\\s+into\\s+.+)",
    ],
    "http_verb_patterns": "(?i)(HEAD|OPTIONS|TRACE|CONNECT|PROPFIND|PROPPATCH|MKCOL|COPY|MOVE|LOCK|UNLOCK)",
    "max_size_request" : "2048",
    "patching_patterns: [
      "^(?!.*(\\.\\.|\\/\|\\\\)).*$"
    ]
  }',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
