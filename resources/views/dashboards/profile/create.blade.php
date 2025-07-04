@extends('layouts.layout_dashboard')

@push('css')
<style>
    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endpush

@section('dashboard')
<h1 class="h3 mb-4 text-gray-800">Create Profile</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create New Profile</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('profile.store') }}" method="post">
            @csrf
            @method('POST')

            <div class="form-group">
                <label for="type">Select Profile Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="">-- Select Profile Type --</option>
                    <option value="agent">Agent</option>
                    <option value="common-attack-detection-service">common-attack-detection-service</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('description') }}" required>
            </div>

            {{-- Agent profile form --}}
            <div class="form-group" id="partial-agent-profile-form" style="display: none;">
                <label>Configure Agent Profile</label>
                <div class="row">
                    <div class="col-md-4">
                        @include('dashboards.partials.profile_form_visualizer.agent.running_mode')
                    </div>
                    <div class="col-md-4">
                        @include('dashboards.partials.profile_form_visualizer.agent.ws_module_web_attack_detection')
                    </div>
                    <div class="col-md-4">
                        @include('dashboards.partials.profile_form_visualizer.agent.ws_module_dga_detection')
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        @include('dashboards.partials.profile_form_visualizer.agent.ws_module_common_attack_detection')
                    </div>
                    <div class="col-md-8">
                        @include('dashboards.partials.profile_form_visualizer.agent.secure_response_headers')
                    </div>
                </div>
            </div>
            
            {{-- Service profile form --}}
            <div class="form-group" id="partial-service-cad-profile-form" style="display: none;">
                <label>Configure Service Profile</label>
                <div class="row">
                    <div class="col-md-12">
                        @include('dashboards.partials.profile_form_visualizer.service.detect_attack')
                    </div>
                    <div class="col-md-4">
                        @include('dashboards.partials.profile_form_visualizer.service.http_large_request')
                    </div>
                    <div class="col-md-8">
                        @include('dashboards.partials.profile_form_visualizer.service.http_verb_tampering')
                    </div>
                    <div class="col-md-4">
                        @include('dashboards.partials.profile_form_visualizer.service.insecure_redirect')
                    </div>
                    <div class="col-md-8">
                        @include('dashboards.partials.profile_form_visualizer.service.insecure_file_upload')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('dashboards.partials.profile_form_visualizer.service.rule_patterns')
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="profile">Profile (JSON format)</label>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Paste valid JSON and click "Beautify" to format it.</small>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="beautify-json">Beautify JSON</button>
                </div>
                <textarea rows="20" id="profile" name="profile" class="form-control" required>{{ old('profile', '{}') }}</textarea>
                <small id="json-error" class="text-danger d-none">⚠️ Invalid JSON format</small>
            </div>
            
            <div class="form-group">
                <label for="status">Select Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="">-- Select Status --</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <button class="btn btn-sm btn-warning" type="button" onclick="goBack()">Back</button>
                <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                <button class="btn btn-sm btn-danger" type="reset">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const typeSelect = document.getElementById('type');
    const agentForm = document.getElementById('partial-agent-profile-form');
    const serviceForm = document.getElementById('partial-service-cad-profile-form');
    const textarea = document.getElementById("profile");
    const errorMsg = document.getElementById("json-error");

    if (textarea) {
        textarea.addEventListener("input", function () {
            try {
                JSON.parse(textarea.value);
                errorMsg.classList.add("d-none");
                textarea.classList.remove("is-invalid");
            } catch (e) {
                errorMsg.classList.remove("d-none");
                textarea.classList.add("is-invalid");
            }
        });
    }

    const beautifyBtn = document.getElementById("beautify-json");
    if (beautifyBtn) {
        beautifyBtn.addEventListener("click", function () {
            try {
                const json = JSON.parse(textarea.value);
                const pretty = JSON.stringify(json, null, 4);
                textarea.value = pretty;
            } catch (e) {
                alert("Invalid JSON format. Please check again.");
            }
        });
    }

    function buildJsonAgentProfile() {
        const profile = {
            profile: {}
        };

        // Running Mode
        const runningMode = document.getElementById("running_mode");
        const lastRunMode = document.getElementById("last_run_mode");
        const liteSync = document.getElementById("lite_mode_data_is_synchronized");
        const liteStatus = document.getElementById("lite_mode_data_synchronize_status");

        if (runningMode && lastRunMode && liteSync && liteStatus) {
            profile.profile.running_mode = runningMode.value;
            profile.profile.last_run_mode = lastRunMode.value;
            profile.profile.lite_mode_data_is_synchronized = liteSync.checked;
            profile.profile.lite_mode_data_synchronize_status = liteStatus.value;
        }

        // Web Attack Detection
        const webAttackEnable = document.getElementById("ws_module_web_attack_detection_enable");
        const webAttackHeader = document.getElementById("ws_module_web_attack_detection_header");
        const webAttackThreshold = document.getElementById("ws_module_web_attack_detection_threshold");

        if (webAttackEnable && webAttackHeader && webAttackThreshold) {
            profile.profile.ws_module_web_attack_detection = {
                enable: webAttackEnable.checked,
                detect_header: webAttackHeader.checked,
                threshold: parseInt(webAttackThreshold.value) || 0
            };
        }

        // DGA Detection
        const dgaEnable = document.getElementById("ws_module_dga_detection_enable");
        const dgaThreshold = document.getElementById("ws_module_dga_detection_threshold");

        if (dgaEnable && dgaThreshold) {
            profile.profile.ws_module_dga_detection = {
                enable: dgaEnable.checked,
                threshold: parseInt(dgaThreshold.value) || 0
            };
        }

        // Request Rate Limit
        const requestRLEnable =  document.getElementById("ws_request_rate_limit_enable");
        const requestRLThreshold = document.getElementById("ws_request_rate_limit_threshold");

        if (requestRLEnable && requestRLThreshold) {
            profile.profile.ws_request_rate_limit = {
                enable: requestRLEnable.checked,
                threshold: parseInt(requestRLThreshold.value) || 0
            };
        }

        // Common Attack Detection
        const commonEnable = document.getElementById("ws_module_common_attack_detection_enable");
        const xss = document.getElementById("detect_cross_site_scripting");
        const largeRequest = document.getElementById("detect_http_large_request");
        const sqlInjection = document.getElementById("detect_sql_injection");
        const verbTampering = document.getElementById("detect_http_verb_tampering");
        const unknowAttack = document.getElementById("detect_unknown_attack");
        const insecureRedirect = document.getElementById("detect_insecure_redirect");
        const insecureFileUpload = document.getElementById("detect_insecure_file_upload");

        if (commonEnable && xss && largeRequest && sqlInjection && verbTampering) {
            profile.profile.ws_module_common_attack_detection = {
                enable: commonEnable.checked,
                detect_cross_site_scripting: xss.checked,
                detect_http_large_request: largeRequest.checked,
                detect_sql_injection: sqlInjection.checked,
                detect_http_verb_tampering: verbTampering.checked,
                detect_unknown_attack: unknowAttack.checked,
                detect_insecure_redirect: insecureRedirect.checked,
                detect_insecure_file_upload: insecureFileUpload.checked
            };
        }

        // Secure Headers
        const secureEnable = document.getElementById("secure_response_headers_enable");
        const headers = {};

        if (secureEnable) {
            document.querySelectorAll(".secure-header").forEach(input => {
                if (input.checked) {
                    const key = input.dataset.key;
                    const value = input.dataset.value;
                    headers[key] = value;
                }
            });

            document.querySelectorAll(".custom-header-item").forEach(item => {
                const key = item.dataset.key;
                const value = item.dataset.value;
                headers[key] = value;
            });

            profile.profile.secure_response_headers = {
                enable: secureEnable.checked,
                headers: headers
            };
        }

        const jsonStr = JSON.stringify(profile, null, 4);
        if (textarea) textarea.value = jsonStr;

        // Validate JSON again
        try {
            JSON.parse(jsonStr);
            errorMsg?.classList.add("d-none");
            textarea?.classList.remove("is-invalid");
        } catch {
            errorMsg?.classList.remove("d-none");
            textarea?.classList.add("is-invalid");
        }
    }

    function buildJsonCADServiceProfile() {
        const textarea = document.getElementById("profile");
        const errorMsg = document.getElementById("json-error");

        const profile = {
            profile: {}
        };

        profile.profile.detect_http_large_request = {
            enable: document.getElementById("cad_detect_http_large_request").checked,
            pattern: parseInt(document.getElementById("http_request_max_size").value) || 0,
        }
        // HTTP request size & method
        const selectedHttpMethods = Array.from(document.querySelectorAll('input[name="http_methods[]"]:checked'))
            .map(el => el.value.toUpperCase());

        if (selectedHttpMethods.length > 0) {
            const httpVerbPattern = `(?i)(${selectedHttpMethods.join('|')})`;
            
            profile.profile.detect_http_verb_tampering = {
                enable: document.getElementById("cad_detect_http_verb_tampering").checked,
                pattern: httpVerbPattern,
            } 
        }

        // XSS
        profile.profile.detect_xss = {
            enable: document.getElementById("cad_detect_cross_site_scripting").checked,
        }

        // SQLi
        profile.profile.detect_sqli = {
            enable: document.getElementById("cad_detect_sql_injection").checked,
        }

        // Unknown
        profile.profile.detect_unknown_attack = {
            enable: document.getElementById("cad_detect_unknown_attack").checked,
        }

        // Insecure Redirect
        profile.profile.detect_insecure_redirect = {
            enable: document.getElementById("cad_detect_insecure_redirect").checked,
            extend_domain: document.getElementById("insecure_redirect_extend_domain").checked,
        }

        // Insecure File Upload 
        profile.profile.detect_insecure_file_upload = {
            enable: document.getElementById("cad_detect_insecure_file_upload").checked,
            file_name: document.getElementById("secure_file_upload_name").checked,
            file_content: document.getElementById("secure_file_upload_content").checked,
            file_size: parseInt(document.getElementById("secure_file_upload_max_size").value) || 0,
        }

        // Reset lại các patterns ban đầu
        profile.profile.detect_xss.patterns = {};

        profile.profile.detect_sqli.patterns = {};

        profile.profile.detect_unknown_attack.patterns = {};

        profile.profile.detect_insecure_redirect.patterns = {};

        // Duyệt qua các regex-item
        document.querySelectorAll(".custom-regex-item").forEach(item => {
            const group = item.dataset.group;
            const key = item.dataset.key;
            const value = item.dataset.value;

            // Đảm bảo profile.profile[group] tồn tại
            if (!profile.profile[group]) {
                profile.profile[group] = {};
            }
            // Đảm bảo patterns tồn tại
            if (!profile.profile[group].patterns) {
                profile.profile[group].patterns = {};
            }
            // Ghi vào patterns
            profile.profile[group].patterns[key] = value;
        });

        const jsonStr = JSON.stringify(profile, null, 4);
        if (textarea) textarea.value = jsonStr;

        // Validate JSON
        try {
            JSON.parse(jsonStr);
            errorMsg?.classList.add("d-none");
            textarea?.classList.remove("is-invalid");
        } catch {
            errorMsg?.classList.remove("d-none");
            textarea?.classList.add("is-invalid");
        }
    }


    function addFormListeners(formSelector, buildJsonFn) {
        const inputFields = document.querySelectorAll(`${formSelector} input, ${formSelector} select`);
        inputFields.forEach(el => {
            el.removeEventListener("input", buildJsonFn); // tránh trùng lặp
            el.addEventListener("input", buildJsonFn);
        });
        buildJsonFn();
    }

    function toggleForms() {
        const selectedType = typeSelect.value;
        if (selectedType === 'agent') {
            agentForm.style.display = 'block';
            serviceForm.style.display = 'none';
            addFormListeners("#partial-agent-profile-form", buildJsonAgentProfile);
        } else if (selectedType === 'common-attack-detection-service') {
            agentForm.style.display = 'none';
            serviceForm.style.display = 'block';
            addFormListeners("#partial-service-cad-profile-form", buildJsonCADServiceProfile);
        } else {
            agentForm.style.display = 'none';
            serviceForm.style.display = 'none';
            textarea.value = JSON.stringify({}, null, 4);
        }
    }

    typeSelect.addEventListener('change', toggleForms);

    // Initialize on page load in case of validation errors / old input
    toggleForms();

    function customEncode(str) {
        return str
            .replace(/"/g, '&quot;')
            .replace(/>/g, '&gt;')
            .replace(/\)/g, '&#41;')
            .replace(/</g, '&lt;')
            .replace(/\//g, '&#47;')
            .replace(/{/g, '&#123;')
            .replace(/}/g, '&#125;');
    }

    // Add custom header
    const addBtn = document.getElementById("add-custom-header");
    const keyInput = document.getElementById("custom-header-key");
    const valueInput = document.getElementById("custom-header-value");
    const headerList = document.getElementById("custom-header-list");

    if (addBtn && keyInput && valueInput && headerList) {
        addBtn.addEventListener("click", function () {
            const key = keyInput.value.trim();
            const value = valueInput.value.trim();

            if (key && value) {
                const encodedKey = customEncode(key);
                const encodedValue = customEncode(value);

                const item = document.createElement("li");
                item.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "custom-header-item");
                item.dataset.key = encodedKey;
                item.dataset.value = encodedValue;
                item.innerHTML = `<span><strong>${encodedKey}</strong>: <code>${encodedValue}</code></span>
                                  <button class="btn btn-sm btn-danger btn-remove">Remove</button>`;
                headerList.appendChild(item);
                keyInput.value = '';
                valueInput.value = '';
                buildJsonAgentProfile(); // update json
            }
        });

        headerList.addEventListener("click", function (e) {
            if (e.target.classList.contains("btn-remove")) {
                e.target.closest("li").remove();
                buildJsonAgentProfile();
            }
        });
    }

    const addRegexBtn = document.getElementById("add-custom-regex");
    const regexTypeSelect = document.getElementById("regex-type");
    const regexKeyInput = document.getElementById("custom-regex-key");
    const regexValueInput = document.getElementById("custom-regex-value");
    const regexList = document.getElementById("custom-regex-list");

    if (addRegexBtn && regexKeyInput && regexValueInput && regexList && regexTypeSelect) {
        addRegexBtn.addEventListener("click", function () {
            const key = regexKeyInput.value.trim();
            const value = regexValueInput.value.trim();
            const type = regexTypeSelect.value;

            if (key && value && type) {
                const encodedKey = customEncode(key);
                const encodedValue = customEncode(value);

                const item = document.createElement("li");
                item.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "custom-regex-item");
                item.dataset.key = encodedKey;
                item.dataset.value = encodedValue;
                item.dataset.group = type;
                item.innerHTML = `<span><strong>[${type}] ${encodedKey}</strong>: <code>${encodedValue}</code></span>
                                <button class="btn btn-sm btn-danger btn-remove">Remove</button>`;
                regexList.appendChild(item);

                regexKeyInput.value = '';
                regexValueInput.value = '';
                regexTypeSelect.selectedIndex = 0;

                buildJsonCADServiceProfile(); // cập nhật JSON
            }
        });

        regexList.addEventListener("click", function (e) {
            if (e.target.classList.contains("btn-remove")) {
                e.target.closest("li").remove();
                buildJsonCADServiceProfile(); // cập nhật JSON
            }
        });
    }

    // Go back function
    window.goBack = function () {
        window.history.back();
    };
});
</script>
@endpush
