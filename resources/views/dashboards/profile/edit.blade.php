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
                <label for="type">Select Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="">-- Select Profile Type --</option>
                    <option value="agent" {{ $profile->type == 'agent' ? 'selected' : '' }}>Agent</option>
                    <option value="service" {{ $profile->type == 'service' ? 'selected' : '' }}>Service</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $profile->name) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('description', $profile->description) }}" required>
            </div>

            <div class="form-group" id="partial-profile-form">
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
        } catch (e) {
            console.warn("Cannot parse JSON profile:", e);
        }
    }

    function buildJsonProfile() {
        const profile = {
            running_mode: document.getElementById("running_mode").value,
            last_run_mode: document.getElementById("last_run_mode").value,
            lite_mode_data_is_synchronized: document.getElementById("lite_mode_data_is_synchronized").checked,
            lite_mode_data_synchronize_status: document.getElementById("lite_mode_data_synchronize_status").value,
            ws_module_web_attack_detection: {
                enable: document.getElementById("ws_module_web_attack_detection_enable").checked,
                detect_header: document.getElementById("ws_module_web_attack_detection_header").checked,
                threshold: parseInt(document.getElementById("ws_module_web_attack_detection_threshold").value) || 0
            },
            ws_module_dga_detection: {
                enable: document.getElementById("ws_module_dga_detection_enable").checked,
                threshold: parseInt(document.getElementById("ws_module_dga_detection_threshold").value) || 0
            },
            ws_module_common_attack_detection: {
                enable: document.getElementById("ws_module_common_attack_detection_enable").checked,
                detect_cross_site_scripting: document.getElementById("detect_cross_site_scripting").checked,
                detect_http_large_request: document.getElementById("detect_http_large_request").checked,
                detect_sql_injection: document.getElementById("detect_sql_injection").checked,
                detect_http_verb_tampering: document.getElementById("detect_http_verb_tampering").checked,
                detect_unknow_attack: document.getElementById("detect_unknow_attack").checked
            },
            secure_response_headers: {
                enable: document.getElementById("secure_response_headers_enable").checked,
                headers: {}
            }
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
                item.dataset.key = encodedKey;
                item.dataset.value = encodedValue;
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

    // Auto build JSON when inputs change
    const inputFields = document.querySelectorAll("#partial-profile-form input, #partial-profile-form select");
    if (inputFields.length > 0) {
        inputFields.forEach(el => {
            el.addEventListener("input", buildJsonProfile);
            el.addEventListener("change", buildJsonProfile);
        });
    }

    // Initial load
    populateFormFromJson();
    buildJsonProfile();
});
</script>
@endpush