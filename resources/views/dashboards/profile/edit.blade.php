@extends('layouts.layout_dashboard')
@push('css')
<style>
    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endpush
@section('dashboard')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Update Profile</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Update Your Profile</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('profile.update', ['profile' => $profile]) }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="type">Profile Type</label>
                <input type="text" id="type" name="type" class="form-control" value="{{ old('name', $profile->type) }}" readonly>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $profile->name) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('description', $profile->description) }}" required>
            </div>

            @if($profile->type == 'agent')
            <div class="form-group" id="partial-agent-profile-form">
                <label>Configure Profile</label>
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
            @elseif($profile->type == 'common-attack-detection-service')
            <div class="form-group" id="partial-service-cad-profile-form">
                <label>Configure Service Profile</label>
                <div class="row">
                    <div class="col-md-4">
                        @include('dashboards.partials.profile_form_visualizer.service.http_large_request')
                    </div>
                    <div class="col-md-8">
                        @include('dashboards.partials.profile_form_visualizer.service.http_verb_tampering')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('dashboards.partials.profile_form_visualizer.service.rule_patterns')
                    </div>
                </div>
            </div>
            @endif
            <div class="form-group">
                <label for="profile">Profile (JSON format)</label>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Paste valid JSON and click "Beautify" to format it.</small>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="beautify-json">
                        Beautify JSON
                    </button>
                </div>   
                <textarea rows="10" cols="50" id="profile" name="profile" class="form-control" required>{{ old('profile', $profile->profile) }}</textarea>
                <small id="json-error" class="text-danger d-none">⚠️ Invalid JSON format</small>
            </div>

            <div class="form-group">
                <label for="status">Select Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="">-- Select Profile Type --</option>
                    <option value="active" {{ $profile->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $profile->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <button class="btn btn-sm btn-warning" type="back" onclick="goBack()">Back</button>
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
    const textarea = document.getElementById("profile");
    const errorMsg = document.getElementById("json-error");
    const beautifyBtn = document.getElementById("beautify-json");
    const profileType = document.getElementById("type")?.value;

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

    function populateFormFromJson() {
        if (!textarea) return;

        try {
            const parsed = JSON.parse(textarea.value);
            const profile = parsed.profile || {};

            if (profileType === "agent") {
                // Running Mode
                document.getElementById("running_mode").value = profile.running_mode || "";
                document.getElementById("last_run_mode").value = profile.last_run_mode || "";
                document.getElementById("lite_mode_data_is_synchronized").checked = !!profile.lite_mode_data_is_synchronized;
                document.getElementById("lite_mode_data_synchronize_status").value = profile.lite_mode_data_synchronize_status || "";

                // Web Attack Detection
                if (profile.ws_module_web_attack_detection) {
                    document.getElementById("ws_module_web_attack_detection_enable").checked = !!profile.ws_module_web_attack_detection.enable;
                    document.getElementById("ws_module_web_attack_detection_header").checked = !!profile.ws_module_web_attack_detection.detect_header;
                    document.getElementById("ws_module_web_attack_detection_threshold").value = profile.ws_module_web_attack_detection.threshold || 0;
                }

                // DGA Detection
                if (profile.ws_module_dga_detection) {
                    document.getElementById("ws_module_dga_detection_enable").checked = !!profile.ws_module_dga_detection.enable;
                    document.getElementById("ws_module_dga_detection_threshold").value = profile.ws_module_dga_detection.threshold || 0;
                }

                // Common Attack Detection
                if (profile.ws_module_common_attack_detection) {
                    document.getElementById("ws_module_common_attack_detection_enable").checked = !!profile.ws_module_common_attack_detection.enable;
                    document.getElementById("detect_cross_site_scripting").checked = !!profile.ws_module_common_attack_detection.detect_cross_site_scripting;
                    document.getElementById("detect_http_large_request").checked = !!profile.ws_module_common_attack_detection.detect_http_large_request;
                    document.getElementById("detect_sql_injection").checked = !!profile.ws_module_common_attack_detection.detect_sql_injection;
                    document.getElementById("detect_http_verb_tampering").checked = !!profile.ws_module_common_attack_detection.detect_http_verb_tampering;
                    document.getElementById("detect_unknow_attack").checked = !!profile.ws_module_common_attack_detection.detect_unknow_attack;
                }

                // Secure Headers
                if (profile.secure_response_headers) {
                    document.getElementById("secure_response_headers_enable").checked = !!profile.secure_response_headers.enable;

                    const checkedKeys = Object.keys(profile.secure_response_headers.headers || {});
                    document.querySelectorAll(".secure-header").forEach(input => {
                        const key = input.dataset.key;
                        const value = input.dataset.value;
                        input.checked = checkedKeys.includes(key) && profile.secure_response_headers.headers[key] === value;
                    });

                    // Custom headers
                    const headerList = document.getElementById("custom-header-list");
                    headerList.innerHTML = "";
                    for (const [key, value] of Object.entries(profile.secure_response_headers.headers || {})) {
                        const isPredefined = [...document.querySelectorAll(".secure-header")].some(
                            input => input.dataset.key === key && input.dataset.value === value
                        );
                        if (!isPredefined) {
                            const item = document.createElement("li");
                            const encodedKey = customEncode(key);
                            const encodedValue = customEncode(value);
                            item.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "custom-header-item");
                            item.dataset.key = encodedKey;
                            item.dataset.value = encodedValue;
                            item.innerHTML = `
                                <span><strong>${encodedKey}</strong>: <code>${encodedValue}</code></span>
                                <button class="btn btn-sm btn-danger btn-remove">Remove</button>`;
                            headerList.appendChild(item);
                        }
                    }
                }
            } else if (profileType === "common-attack-detection-service") {
                if (profile.max_size_request) {
                    document.getElementById("http_request_max_size").value = profile.max_size_request || 0;
                }

                if (profile.http_verb_patterns !== undefined) {
                    const pattern = profile.http_verb_patterns.replace(/^\(\?i\)/, '');
                    const match = pattern.match(/\(([^)]+)\)/);

                    if (match && match[1]) {
                        const enabledMethods = match[1].split('|').map(m => m.trim().toUpperCase());

                        const allMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD'];

                        allMethods.forEach(method => {
                            const checkbox = document.getElementById('method_' + method.toLowerCase());
                            if (checkbox) {
                                checkbox.checked = enabledMethods.includes(method);
                            }
                        });
                    }
                }

                // Custom regex patterns
                const patternList = document.getElementById("custom-regex-list");
                patternList.innerHTML = "";

                // Danh sách các nhóm pattern bạn quan tâm
                const patternGroups = ["xss_patterns", "sql_patterns", "unknow_attack_patterns"];

                patternGroups.forEach(group => {
                    const patterns = profile[group] || {};
                    for (const [key, value] of Object.entries(patterns)) {
                        // Kiểm tra xem pattern có phải là mặc định không (nếu cần)
                        // Nếu không cần lọc predefined thì có thể bỏ bước này
                        const item = document.createElement("li");
                        const encodedKey = customEncode(key);
                        const encodedValue = customEncode(value);

                        item.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "custom-pattern-item");
                        item.dataset.group = group;
                        item.dataset.key = key;
                        item.dataset.value = value;
                        item.innerHTML = `
                            <span><strong>${encodedKey}</strong>: <code>${encodedValue}</code></span>
                            <button class="btn btn-sm btn-danger btn-remove">Remove</button>`;
                        patternList.appendChild(item);
                    }
                });
            }
        } catch (e) {
            console.warn("Cannot parse JSON profile:", e);
        }
    }

    function buildJsonProfile() {
        const profile = {};
        const isAgent = profileType === "agent";
        const isCADService = profileType === "common-attack-detection-service";

        if (isAgent) {
            profile.running_mode = document.getElementById("running_mode").value;
            profile.last_run_mode = document.getElementById("last_run_mode").value;
            profile.lite_mode_data_is_synchronized = document.getElementById("lite_mode_data_is_synchronized").checked;
            profile.lite_mode_data_synchronize_status = document.getElementById("lite_mode_data_synchronize_status").value;

            profile.ws_module_web_attack_detection = {
                enable: document.getElementById("ws_module_web_attack_detection_enable").checked,
                detect_header: document.getElementById("ws_module_web_attack_detection_header").checked,
                threshold: parseInt(document.getElementById("ws_module_web_attack_detection_threshold").value) || 0
            };

            profile.ws_module_dga_detection = {
                enable: document.getElementById("ws_module_dga_detection_enable").checked,
                threshold: parseInt(document.getElementById("ws_module_dga_detection_threshold").value) || 0
            };

            profile.ws_module_common_attack_detection = {
                enable: document.getElementById("ws_module_common_attack_detection_enable").checked,
                detect_cross_site_scripting: document.getElementById("detect_cross_site_scripting").checked,
                detect_http_large_request: document.getElementById("detect_http_large_request").checked,
                detect_sql_injection: document.getElementById("detect_sql_injection").checked,
                detect_http_verb_tampering: document.getElementById("detect_http_verb_tampering").checked,
                detect_unknow_attack: document.getElementById("detect_unknow_attack").checked
            };

            profile.secure_response_headers = {
                enable: document.getElementById("secure_response_headers_enable").checked,
                headers: {}
            };

            // Default headers
            document.querySelectorAll(".secure-header:checked").forEach(input => {
                profile.secure_response_headers.headers[input.dataset.key] = input.dataset.value;
            });

            // Custom headers
            document.querySelectorAll(".custom-header-item").forEach(item => {
                const key = item.dataset.key;
                const value = item.dataset.value;
                profile.secure_response_headers.headers[key] = value;
            });
        }

        if (isCADService) {
            profile.max_size_request = parseInt(document.getElementById("http_request_max_size").value) || 0;

            // HTTP Verb Patterns
            const allMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD'];
            const enabledMethods = allMethods.filter(method => {
                const checkbox = document.getElementById("method_" + method.toLowerCase());
                return checkbox && checkbox.checked;
            });
            profile.http_verb_patterns = `(?i)(${enabledMethods.join('|')})`;

            // Custom regex patterns
            profile.xss_patterns = {};
            profile.sql_patterns = {};
            profile.unknow_attack_patterns = {};

            document.querySelectorAll(".custom-pattern-item").forEach(item => {
                const group = item.dataset.group;
                const key = item.dataset.key;
                const value = item.dataset.value;

                if (!profile[group]) {
                    profile[group] = {};
                }

                profile[group][key] = value;
            });


            // Nếu có rule_patterns riêng thì giữ lại
            const rulePatterns = [];
            document.querySelectorAll(".rule-pattern").forEach(input => {
                const val = input.value.trim();
                if (val) rulePatterns.push(val);
            });
            if (rulePatterns.length > 0) {
                profile.rule_patterns = rulePatterns;
            }
        }

        const finalJson = JSON.stringify({ profile }, null, 2);
        textarea.value = finalJson;
        errorMsg.classList.add("d-none");
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
                item.dataset.key = key;
                item.dataset.value = value;
                item.innerHTML = `<span><strong>${encodedKey}</strong>: <code>${encodedValue}</code></span>
                                  <button class="btn btn-sm btn-danger btn-remove">Remove</button>`;
                headerList.appendChild(item);
                keyInput.value = '';
                valueInput.value = '';
                buildJsonProfile(); // update json
            }
        });

        headerList.addEventListener("click", function (e) {
            if (e.target.classList.contains("btn-remove")) {
                e.target.closest("li").remove();
                buildJsonProfile();
            }
        });
    }
    
    // Add custom patterns
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
                item.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "custom-pattern-item");
                item.dataset.key = encodedKey;
                item.dataset.value = encodedValue;
                item.dataset.group = type;
                item.innerHTML = `<span><strong>[${type}] ${encodedKey}</strong>: <code>${encodedValue}</code></span>
                                <button class="btn btn-sm btn-danger btn-remove">Remove</button>`;
                regexList.appendChild(item);

                regexKeyInput.value = '';
                regexValueInput.value = '';
                regexTypeSelect.selectedIndex = 0;

                buildJsonProfile(); // cập nhật JSON
            }
        });

        regexList.addEventListener("click", function (e) {
            if (e.target.classList.contains("btn-remove")) {
                e.target.closest("li").remove();
                buildJsonProfile(); // cập nhật JSON
            }
        });
    }
    // Beautify button
    if (beautifyBtn) {
        beautifyBtn.addEventListener("click", function () {
            try {
                const parsed = JSON.parse(textarea.value);
                textarea.value = JSON.stringify(parsed, null, 2);
                errorMsg.classList.add("d-none");
            } catch (e) {
                errorMsg.classList.remove("d-none");
            }
        });
    }

    function attachInputListeners(formId, callback) {
        const inputFields = document.querySelectorAll(`${formId} input, ${formId} select, ${formId} textarea`);
        inputFields.forEach(el => {
            el.addEventListener("input", callback);
            el.addEventListener("change", callback);
        });
    }

    // Gọi hàm cho cả 2 form
    attachInputListeners("#partial-agent-profile-form", buildJsonProfile);
    attachInputListeners("#partial-service-cad-profile-form", buildJsonProfile);


    // Initial load
    populateFormFromJson();
    buildJsonProfile();
});
</script>
@endpush