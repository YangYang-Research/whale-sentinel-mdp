<p><strong>Request Rate Limit</strong></p>
<div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="ws_request_rate_limit_enable">
    <label class="form-check-label">Enable Request Rate Limit</label>
</div>
<div class="form-group">
    <label>Rate Limit Threshold (RPM)</label>
    <input type="number" class="form-control" id="ws_request_rate_limit_threshold" value="100">
</div>
<p><strong>Common Attack Detection</strong></p>
<div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="ws_module_common_attack_detection_enable" checked>
    <label class="form-check-label">Enable Common Attack Detection</label>
</div>
<div class="ml-3">
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="detect_cross_site_scripting" checked>
        <label class="form-check-label">Detect Cross-Site Scripting</label>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="detect_http_large_request" checked>
        <label class="form-check-label">Detect HTTP Large Request</label>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="detect_sql_injection" checked>
        <label class="form-check-label">Detect SQL Injection</label>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="detect_http_verb_tampering" checked>
        <label class="form-check-label">Detect HTTP Verb Tampering</label>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="detect_unknown_attack" checked>
        <label class="form-check-label">Detect Unknown Attack</label>
    </div>
</div>
