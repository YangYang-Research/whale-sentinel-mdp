<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WSProfileSeeder extends Seeder
{
    public function run(): void
    {
        $agentProfile = [
            'profile' => [
                'running_mode' => 'monitor',
                'last_run_mode' => 'lite',
                'lite_mode_data_is_synchronized' => false,
                'lite_mode_data_synchronize_status' => 'failure',
                'ws_request_rate_limit' => [
                    'enable' => true,
                    'threshold' => 100,
                ],
                'ws_module_web_attack_detection' => [
                    'enable' => true,
                    'detect_header' => false,
                    'threshold' => 80,
                ],
                'ws_module_dga_detection' => [
                    'enable' => true,
                    'threshold' => 80,
                ],
                'ws_module_common_attack_detection' => [
                    'enable' => true,
                    'detect_cross_site_scripting' => true,
                    'detect_http_large_request' => true,
                    'detect_sql_injection' => true,
                    'detect_http_verb_tampering' => true,
                    'detect_unknown_attack' => true,
                    'detect_insecure_redirect' => true,
                    'detect_insecure_file_upload' => true,
                ],
                'secure_response_headers' => [
                    'enable' => true,
                    'headers' => [
                        'Server' => 'Whale Sentinel',
                        'X-Whale-Sentinel' => '1',
                        'Referrer-Policy' => 'no-referrer-when-downgrade',
                        'X-Content-Type-Options' => 'nosniff',
                        'X-XSS-Protection' => '1; mode=block',
                        'X-Frame-Options' => 'SAMEORIGIN',
                        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
                        'X-Permitted-Cross-Domain-Policies' => 'none',
                        'Expect-CT' => 'enforce; max-age=31536000',
                        'Feature-Policy' => "fullscreen 'self'",
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        'X-UA-Compatible' => 'IE=Edge,chrome=1',
                        'Access-Control-Allow-Origin' => '*',
                        'Access-Control-Allow-Methods' => 'GET, POST',
                        'Access-Control-Allow-Credentials' => 'true',
                        'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                    ],
                ],
            ],
        ];

        $serviceProfile = [
            'profile' => [
                'detect_xss' => [
                    'enable' => true,
                    'patterns' => [
                        'xss_pattern_1' => '(?:https?://|//)[^\s/]+\.js',
                        'xss_pattern_2' => '((%3C)|<)((%2F)|/)*[a-z0-9%]+((%3E)|>)',
                        'xss_pattern_3' => '((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)',
                        'xss_pattern_4' => '((%3C)|<)[^\n]+((%3E)|>)',
                        'xss_pattern_5' => '(?i)<script[^>]*>.*?</script>',
                        'xss_pattern_6' => '(?i)on\w+\s*=\s*["\']?[^"\'>]+["\']?',
                        'xss_pattern_7' => '(?i)javascript\s*:\s*[^"\'>\s]+',
                        'xss_pattern_8' => '(?i)eval\s*\(',
                        'xss_pattern_9' => '(?i)document\.cookie',
                        'xss_pattern_10' => '(?i)alert\s*\(',
                        'xss_pattern_11' => '(?i)prompt\s*\(',
                        'xss_pattern_12' => '(?i)confirm\s*\(',
                        'xss_pattern_13' => '(?i)onload\s*=\s*[^"\'>]+',
                        'xss_pattern_14' => '(?i)onerror\s*=\s*[^"\'>]+',
                        'xss_pattern_15' => '(?i)onmouseover\s*=\s*[^"\'>]+',
                        'xss_pattern_16' => '(?i)onfocus\s*=\s*[^"\'>]+',
                        'xss_pattern_17' => '(?i)onblur\s*=\s*[^"\'>]+',
                        'xss_pattern_18' => '(?i)onchange\s*=\s*[^"\'>]+',
                        'xss_pattern_19' => '(?i)onsubmit\s*=\s*[^"\'>]+',
                        'xss_pattern_20' => '(?i)onreset\s*=\s*[^"\'>]+',
                        'xss_pattern_21' => '(?i)onselect\s*=\s*[^"\'>]+',
                        'xss_pattern_22' => '(?i)onkeydown\s*=\s*[^"\'>]+',
                        'xss_pattern_23' => '(?i)onkeypress\s*=\s*[^"\'>]+',
                        'xss_pattern_24' => '(?i)onmousedown\s*=\s*[^"\'>]+',
                        'xss_pattern_25' => '(?i)onmouseup\s*=\s*[^"\'>]+',
                        'xss_pattern_26' => '(?i)onmousemove\s*=\s*[^"\'>]+',
                        'xss_pattern_27' => '(?i)onmouseout\s*=\s*[^"\'>]+',
                        'xss_pattern_28' => '(?i)onmouseenter\s*=\s*[^"\'>]+',
                        'xss_pattern_29' => '(?i)onmouseleave\s*=\s*[^"\'>]+',
                        'xss_pattern_30' => '(?i)oncontextmenu\s*=\s*[^"\'>]+',
                        'xss_pattern_31' => '(?i)onresize\s*=\s*[^"\'>]+',
                        'xss_pattern_32' => '(?i)onscroll\s*=\s*[^"\'>]+',
                        'xss_pattern_33' => '(?i)onwheel\s*=\s*[^"\'>]+',
                        'xss_pattern_34' => '(?i)oncopy\s*=\s*[^"\'>]+',
                        'xss_pattern_35' => '(?i)oncut\s*=\s*[^"\'>]+',
                        'xss_pattern_36' => '(?i)onpaste\s*=\s*[^"\'>]+',
                        'xss_pattern_37' => '(?i)onbeforeunload\s*=\s*[^"\'>]+',
                        'xss_pattern_38' => '(?i)onhashchange\s*=\s*[^"\'>]+',
                        'xss_pattern_39' => '(?i)onmessage\s*=\s*[^"\'>]+',
                        'xss_pattern_40' => '(?i)onoffline\s*=\s*[^"\'>]+',
                        'xss_pattern_41' => '(?i)ononline\s*=\s*[^"\'>]+',
                        'xss_pattern_42' => '(?i)onpagehide\s*=\s*[^"\'>]+',
                        'xss_pattern_43' => '(?i)onpageshow\s*=\s*[^"\'>]+',
                        'xss_pattern_44' => '(?i)onpopstate\s*=\s*[^"\'>]+',
                        'xss_pattern_45' => '(?i)onstorage\s*=\s*[^"\'>]+',
                        'xss_pattern_46' => '(?i)onunload\s*=\s*[^"\'>]+',
                        'xss_pattern_47' => '(?i)oninput\s*=\s*[^"\'>]+',
                        'xss_pattern_48' => '(?i)oninvalid\s*=\s*[^"\'>]+',
                        'xss_pattern_49' => '(?i)onsearch\s*=\s*[^"\'>]+',
                        'xss_pattern_50' => '(?i)onkeyup\s*=\s*[^"\'>]+',
                        'xss_pattern_51' => '(?i)onabort\s*=\s*[^"\'>]+',
                        'xss_pattern_52' => '(?i)oncanplay\s*=\s*[^"\'>]+',
                        'xss_pattern_53' => '(?i)oncanplaythrough\s*=\s*[^"\'>]+',
                        'xss_pattern_54' => '(?i)ondurationchange\s*=\s*[^"\'>]+',
                        'xss_pattern_55' => '(?i)onemptied\s*=\s*[^"\'>]+',
                        'xss_pattern_56' => '(?i)onended\s*=\s*[^"\'>]+',
                        'xss_pattern_57' => '(?i)onloadeddata\s*=\s*[^"\'>]+',
                        'xss_pattern_58' => '(?i)onloadedmetadata\s*=\s*[^"\'>]+',
                        'xss_pattern_59' => '(?i)onloadstart\s*=\s*[^"\'>]+',
                        'xss_pattern_60' => '(?i)onpause\s*=\s*[^"\'>]+',
                        'xss_pattern_61' => '(?i)onplay\s*=\s*[^"\'>]+',
                        'xss_pattern_62' => '(?i)onplaying\s*=\s*[^"\'>]+',
                        'xss_pattern_63' => '(?i)onprogress\s*=\s*[^"\'>]+',
                        'xss_pattern_64' => '(?i)onratechange\s*=\s*[^"\'>]+',
                        'xss_pattern_65' => '(?i)onseeked\s*=\s*[^"\'>]+',
                        'xss_pattern_66' => '(?i)onseeking\s*=\s*[^"\'>]+',
                        'xss_pattern_67' => '(?i)onstalled\s*=\s*[^"\'>]+',
                        'xss_pattern_68' => '(?i)onsuspend\s*=\s*[^"\'>]+',
                        'xss_pattern_69' => '(?i)ontimeupdate\s*=\s*[^"\'>]+',
                        'xss_pattern_70' => '(?i)onvolumechange\s*=\s*[^"\'>]+',
                        'xss_pattern_71' => '(?i)onwaiting\s*=\s*[^"\'>]+',
                        'xss_pattern_72' => '(?i)onshow\s*=\s*[^"\'>]+',
                        'xss_pattern_73' => '(?i)onvisibilitychange\s*=\s*[^"\'>]+',
                        'xss_pattern_74' => '(?i)onanimationstart\s*=\s*[^"\'>]+',
                        'xss_pattern_75' => '(?i)onanimationend\s*=\s*[^"\'>]+',
                        'xss_pattern_76' => '(?i)onanimationiteration\s*=\s*[^"\'>]+',
                        'xss_pattern_77' => '(?i)ontransitionend\s*=\s*[^"\'>]+',
                    ]
                ],
                'detect_sqli' => [
                    'enable' => true,
                    'patterns' => [
                        'sqli_pattern_1' => '(?:select\s+.+\s+from\s+.+)',
                        'sqli_pattern_2' => '(?:insert\s+.+\s+into\s+.+)',
                        'sqli_pattern_3' => '(?:update\s+.+\s+set\s+.+)',
                        'sqli_pattern_4' => '(?:delete\s+.+\s+from\s+.+)',
                        'sqli_pattern_5' => '(?:drop\s+.+)',
                        'sqli_pattern_6' => '(?:truncate\s+.+)',
                        'sqli_pattern_7' => '(?:alter\s+.+)',
                        'sqli_pattern_8' => '(?:exec\s+.+)',
                        'sqli_pattern_9' => '(\s*(all|any|not|and|between|in|like|or|some|contains|containsall|containskey)\s+.+[\=\>\<=\!\~]+.+)',
                        'sqli_pattern_10' => '(?:let\s+.+[\=]\s+.*)',
                        'sqli_pattern_11' => '(?:begin\s*.+\s*end)',  
                        'sqli_pattern_12' => '(?:\s*[\/\*]+\s*.+\s*[\*\/]+)',                      
                        'sqli_pattern_13' => '(?:\s*(\-\-)\s*.+\s+)',                            
                        'sqli_pattern_14' => '(?:\s*(contains|containsall|containskey)\s+.+)',   
                        'sqli_pattern_15' => '\w*((%27)|(\'))((%6F)|o|(%4F))((%72)|r|(%52))',   
                        'sqli_pattern_16' => 'exec(\s|\+)+(s|x)p\w+',                            
                        'sqli_pattern_17' => '(?i)\b(select|insert|update|delete|drop|exec|union)\b', 
                        'sqli_pattern_18' => '(?i)(\bor\b|\band\b).*(=|>|<|!=)',
                        'sqli_pattern_19' => '(?i)\'\s*(or|and)\s*\'\s*=\s*\'',
                        'sqli_pattern_20' => '(?i)\'\s*(or|and)\s*\'[^=]*=\'',
                    ]
                ],
                'detect_http_verb_tampering' => [
                    'enable' => true,
                    'pattern' => '(?i)(GET|POST|PUT|DELETE)',
                ],
                'detect_http_large_request' => [
                    'enable' => true,
                    'pattern' => 2, // MB
                ],
                'detect_unknown_attack' => [
                    'enable' => true,
                    'patterns' => [
                        'unknown_pattern_1' => '(?:\\.\\./|\\.\\.\\\\)+',
                    ]
                ],
                'detect_insecure_redirect' => [
                    'enable' => true,
                    'extend_domain' => true,
                    'patterns' => [
                        'extend_domain_1' => '^example\.com$',
                    ],
                ],
                'detect_insecure_file_upload' => [
                    'enable' => true,
                    'file_size' => 2, // MB
                    'file_name' => true,
                    'file_content' => true,
                ],
            ],
        ];

        DB::table('ws_profiles')->insert([
            [
                'name' => 'Agent-Profile-Example',
                'description' => 'This is an example profile for agents.',
                'type' => 'agent',
                'profile' => json_encode($agentProfile, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Service-Profile-Example',
                'description' => 'This is an example profile for ws-common-attack-detection service.',
                'type' => 'common-attack-detection-service',
                'profile' => json_encode($serviceProfile, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
