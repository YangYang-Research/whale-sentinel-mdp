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
<h1 class="h3 mb-4 text-gray-800">Update Service</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Update Your Service</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('service.update', ['service' => $service]) }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $service->name) }}" readonly>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('description', $service->description) }}" required>
            </div>

            
            <div class="form-group" id="partial-service-cad-profile-form">
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
                        @include('dashboards.partials.profile_form_visualizer.service.secure_redirect')
                    </div>
                    <div class="col-md-8">
                        @include('dashboards.partials.profile_form_visualizer.service.secure_file_upload')
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
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="beautify-json">
                        Beautify JSON
                    </button>
                </div>   
                <textarea rows="10" cols="50" id="profile" name="profile" class="form-control" required>{{ old('profile', $service->profile) }}</textarea>
                <small id="json-error" class="text-danger d-none">⚠️ Invalid JSON format</small>
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
    const profileTextarea = document.getElementById("profile");

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
            
            if (profile.detect_http_large_request) {
                document.getElementById("cad_detect_http_large_request").checked = !!profile.detect_http_large_request.enable;
                document.getElementById("http_request_max_size").value = profile.detect_http_large_request.pattern || 0;
            }

            if (profile.detect_http_verb_tampering) {
                document.getElementById("cad_detect_http_verb_tampering").checked = !!profile.detect_http_verb_tampering.enable;

                const pattern = profile.detect_http_verb_tampering.pattern.replace(/^\(\?i\)/, '');
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
            
            // XSS
            if (profile.detect_xss) {
                document.getElementById("cad_detect_cross_site_scripting").checked = !!profile.detect_xss.enable;
            }

            // SQLi
            if (profile.detect_sqli) {
                document.getElementById("cad_detect_sql_injection").checked = !!profile.detect_sqli.enable;
            }

            // Unknown
            if (profile.detect_unknown_attack) {
                document.getElementById("cad_detect_unknown_attack").checked = !!profile.detect_unknown_attack.enable;
            }

            // Secure Redirect
            if (profile.secure_redirect) {
                document.getElementById("cad_detect_insecure_redirect").checked = !!profile.secure_redirect.enable;
                document.getElementById("secure_redirect_self_domain").checked = !!profile.secure_redirect.self_domain;
            }

            // Secure File Upload
            if (profile.secure_file_upload) {
                document.getElementById("cad_detect_insecure_file_upload").checked = !!profile.secure_file_upload.enable;
                document.getElementById("secure_file_upload_name").checked = !!profile.secure_file_upload.secure_file_name;
                document.getElementById("secure_file_upload_content").checked = !!profile.secure_file_upload.secure_file_content;
                document.getElementById("secure_file_upload_max_size").value = profile.secure_file_upload.max_size_file || 0;
            }

            // Custom regex patterns
            const patternList = document.getElementById("custom-regex-list");
            patternList.innerHTML = "";

            // Hiển thị các pattern của redirect extend domain
            if (profile.secure_redirect && profile.secure_redirect.extend_domain) {
                const redirectExtendPatterns = profile.secure_redirect.extend_domain;
                for (const [key, value] of Object.entries(redirectExtendPatterns)) {
                    const item = document.createElement("li");
                    const encodedKey = customEncode(key);
                    const encodedValue = customEncode(value);

                    item.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "custom-pattern-item");
                    item.dataset.group = "secure_redirect";
                    item.dataset.key = key;
                    item.dataset.value = value;
                    item.innerHTML = `
                        <span><strong>${encodedKey}</strong>: <code>${encodedValue}</code></span>
                        <button class="btn btn-sm btn-danger btn-remove">Remove</button>`;
                    patternList.appendChild(item);
                }
            }

            // Danh sách các nhóm pattern cần xử lý
            const patternGroups = ["detect_xss", "detect_sqli", "detect_unknown_attack"];

            patternGroups.forEach(group => {
                if (profile[group] && profile[group].patterns) {
                    const patterns = profile[group].patterns;
                    for (const [key, value] of Object.entries(patterns)) {
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
                }
            });
        } catch (e) {
            console.warn("Cannot parse JSON profile:", e);
        }
    }

    function buildJsonProfile() {
        const profile = {};

        profile.detect_http_large_request = {
            enable: document.getElementById("cad_detect_http_large_request").checked,
            pattern: parseInt(document.getElementById("http_request_max_size").value) || 0,
        }

        // HTTP Verb Patterns
        const allMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD'];
        const enabledMethods = allMethods.filter(method => {
            const checkbox = document.getElementById("method_" + method.toLowerCase());
            return checkbox && checkbox.checked;
        });
        profile.detect_http_verb_tampering = {
            enable: document.getElementById("cad_detect_http_verb_tampering").checked,
            pattern: `(?i)(${enabledMethods.join('|')})`,
        } 

        // XSS
        profile.detect_xss = {
            enable: document.getElementById("cad_detect_cross_site_scripting").checked,
        }

        // SQLi
        profile.detect_sqli = {
            enable: document.getElementById("cad_detect_sql_injection").checked,
        }

        // Unknown
        profile.detect_unknown_attack = {
            enable: document.getElementById("cad_detect_unknown_attack").checked,
        }

        // Secure Redirect
        profile.secure_redirect = {
            enable: document.getElementById("cad_detect_insecure_redirect").checked,
            self_domain: document.getElementById("secure_redirect_self_domain").checked,
        }

        // Secure File Upload 
        profile.secure_file_upload = {
            enable: document.getElementById("cad_detect_insecure_file_upload").checked,
            secure_file_name: document.getElementById("secure_file_upload_name").checked,
            secure_file_content: document.getElementById("secure_file_upload_content").checked,
            max_size_file: parseInt(document.getElementById("secure_file_upload_max_size").value) || 0,
        }
        
        // Custom regex patterns
        profile.detect_xss.patterns = {};
        profile.detect_sqli.patterns = {};
        profile.detect_unknown_attack.patterns = {};
        profile.secure_redirect.extend_domain = {};

        document.querySelectorAll(".custom-pattern-item").forEach(item => {
            const group = item.dataset.group;
            const key = item.dataset.key;
            const value = item.dataset.value;

            if (group === 'secure_redirect') {
                // Ghi vào profile.secure_redirect.extend_domain
                if (!profile.secure_redirect.extend_domain) {
                    profile.secure_redirect.extend_domain = {};
                }
                profile.secure_redirect.extend_domain[key] = value;
            } else {
                // Ghi vào profile[group].patterns
                if (!profile[group].patterns) {
                    profile[group].patterns = {};
                }
                profile[group].patterns[key] = value;
            }
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

        const finalJson = JSON.stringify({ profile }, null, 2);
        if (textarea) textarea.value = finalJson;
        errorMsg.classList.add("d-none");

        // Validate JSON again
        try {
            JSON.parse(finalJson);
            errorMsg?.classList.add("d-none");
            textarea?.classList.remove("is-invalid");
        } catch {
            errorMsg?.classList.remove("d-none");
            textarea?.classList.add("is-invalid");
        }
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

    attachInputListeners("#partial-service-cad-profile-form", buildJsonProfile);


    // Initial load
    populateFormFromJson();
    buildJsonProfile();
});
</script>
@endpush