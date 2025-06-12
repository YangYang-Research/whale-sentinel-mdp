@extends('layouts.layout_dashboard')
@push('css')
<style>
    .agent-btn {
        margin-right: 12px !important;
        margin-bottom: 10px !important;
    }

    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endpush
@section('dashboard')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Create Agent Profile</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create New Agent Profile</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('agent.store') }}" method="post">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="application">Application</label>
                <select class="form-control" id="application" name="application_id">
                    <option value="">-- Select Application --</option>
                    @foreach($applications as $application)
                        <option value="{{ $application->id }}" data-language="{{ $application->language }}">
                            {{ $application->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3" id="language-info" style="display: none;">
                <label>Language</label>
                <div id="language-icon" class="d-flex align-items-center">
                    <img id="lang-img" src="" alt="" width="32" height="32">
                    <span id="lang-label" class="ms-2 fw-bold"></span>
                </div>
            </div>

            <div class="form-group mt-3" id="agent-list" style="display: none;">
                <label>Supported Agents</label>
                <div id="agent-buttons" class="d-flex flex-wrap gap-2 mt-2"></div>
                <input type="hidden" name="agent_type" id="selected-agent">
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                </br>
                <small class="text-muted">Prefix ws_agent_*</small>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('description') }}" required>
            </div>

            <div class="form-group">
                <label for="agent_profile">Template Agent Profile</label>
                <select class="form-control" id="agent_profile" name="agent_profile">
                    <option value="">-- Select Template Profile --</option>
                    @foreach($profiles as $profile)
                        <option value="{{ $profile->id }}" data-profile="{{ ($profile->profile) }}">
                            {{ $profile->name }}
                        </option>
                    @endforeach
                </select>
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
                <label for="profile">Agent Profile Detail</label>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Paste valid JSON and click "Beautify" to format it.</small>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="beautify-json">
                        Beautify JSON
                    </button>
                </div>   
                <textarea rows="10" cols="50" id="profile" name="profile" class="form-control" required>{{ old('profile', '{}') }}</textarea>
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
        const applicationSelect = document.getElementById("application");
        const languageInfo = document.getElementById("language-info");
        const langImg = document.getElementById("lang-img");
        const langLabel = document.getElementById("lang-label");
        const agentList = document.getElementById("agent-list");
        const agentButtons = document.getElementById("agent-buttons");
        const selectedAgentInput = document.getElementById("selected-agent");

        // Config từ PHP truyền sang JS
        const languages = @json(config('languages'));

        // Khi chọn Application
        applicationSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            const langKey = selectedOption.getAttribute("data-language");

            if (!langKey || !languages[langKey]) {
                languageInfo.style.display = "none";
                agentList.style.display = "none";
                agentButtons.innerHTML = '';
                selectedAgentInput.value = '';
                return;
            }

            const langData = languages[langKey];

            // Hiển thị icon + label ngôn ngữ
            languageInfo.style.display = "block";
            langImg.src = `{{ asset('') }}` + langData.icon;
            langImg.alt = langKey;
            langLabel.textContent = langData.label;

            // Hiển thị các agent tương ứng
            agentButtons.innerHTML = '';
            selectedAgentInput.value = '';
            agentList.style.display = "block";

            langData.agents.forEach(agent => {
                const button = document.createElement("button");
                button.type = "button";
                button.className = "btn btn-outline-secondary me-2 d-flex align-items-center agent-btn";
                button.setAttribute("data-agent", agent.name);

                const img = document.createElement("img");
                img.src = `{{ asset('') }}` + agent.icon;
                img.alt = agent.name;
                img.width = 24;
                img.height = 24;

                const span = document.createElement("span");
                span.className = "ms-2";
                span.textContent = agent.name;

                button.appendChild(img);
                button.appendChild(span);
                agentButtons.appendChild(button);

                // Gán sự kiện click
                button.addEventListener("click", function () {
                    document.querySelectorAll(".agent-btn").forEach(btn => {
                        btn.classList.remove("btn-primary", "text-white");
                        btn.classList.add("btn-outline-secondary");
                    });

                    this.classList.remove("btn-outline-secondary");
                    this.classList.add("btn-primary", "text-white");

                    selectedAgentInput.value = this.getAttribute("data-agent");
                });
            });
        });
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const profileSelect = document.getElementById("agent_profile");
    const profileTextarea = document.getElementById("profile");
    const errorMsg = document.getElementById("json-error");

    // 🔸 Uncheck all checkboxes on first load
    document.querySelectorAll("#partial-profile-form input[type='checkbox']").forEach(cb => cb.checked = false);

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

    function populateFormFromProfile(profile) {
        if (!profile || typeof profile !== "object") return;

        // Running Mode
        if (profile.running_mode !== undefined) document.getElementById("running_mode").value = profile.running_mode;
        if (profile.last_run_mode !== undefined) document.getElementById("last_run_mode").value = profile.last_run_mode;
        if (profile.lite_mode_data_is_synchronized !== undefined) document.getElementById("lite_mode_data_is_synchronized").checked = profile.lite_mode_data_is_synchronized;
        if (profile.lite_mode_data_synchronize_status !== undefined) document.getElementById("lite_mode_data_synchronize_status").value = profile.lite_mode_data_synchronize_status;

        // Web Attack Detection
        if (profile.ws_module_web_attack_detection) {
            const w = profile.ws_module_web_attack_detection;
            document.getElementById("ws_module_web_attack_detection_enable").checked = w.enable;
            document.getElementById("ws_module_web_attack_detection_header").checked = w.detect_header;
            document.getElementById("ws_module_web_attack_detection_threshold").value = w.threshold || 0;
        }

        // DGA Detection
        if (profile.ws_module_dga_detection) {
            const d = profile.ws_module_dga_detection;
            document.getElementById("ws_module_dga_detection_enable").checked = d.enable;
            document.getElementById("ws_module_dga_detection_threshold").value = d.threshold || 0;
        }

        // Common Attack Detection
        if (profile.ws_module_common_attack_detection) {
            const c = profile.ws_module_common_attack_detection;
            document.getElementById("ws_module_common_attack_detection_enable").checked = c.enable;
            document.getElementById("detect_cross_site_scripting").checked = c.detect_cross_site_scripting;
            document.getElementById("detect_http_large_request").checked = c.detect_http_large_request;
            document.getElementById("detect_sql_injection").checked = c.detect_sql_injection;
            document.getElementById("detect_http_verb_tampering").checked = c.detect_http_verb_tampering;
            document.getElementById("detect_unknow_attack").checked = c.detect_unknow_attack;
        }

        // Secure Headers
        if (profile.secure_response_headers) {
            const s = profile.secure_response_headers;
            document.getElementById("secure_response_headers_enable").checked = s.enable;

            // Clear all .secure-header checkboxes
            document.querySelectorAll(".secure-header").forEach(el => {
                const key = el.dataset.key;
                el.checked = s.headers && key in s.headers;
            });

            // Clear current custom headers
            const headerList = document.getElementById("custom-header-list");
            headerList.innerHTML = "";

            // Re-add custom headers
            for (const key in s.headers) {
                const isStandard = document.querySelector(`.secure-header[data-key="${key}"]`);
                if (!isStandard) {
                    const value = s.headers[key];
                    const item = document.createElement("li");
                    const encodedKey = customEncode(key);
                    const encodedValue = customEncode(value);
                    item.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center", "custom-header-item");
                    item.dataset.key = encodedKey;
                    item.dataset.value = encodedValue;
                    item.innerHTML = `<span><strong>${encodedKey}</strong>: <code>${encodedValue}</code></span>
                                      <button class="btn btn-sm btn-danger btn-remove">Remove</button>`;
                    headerList.appendChild(item);
                }
            }
        }
    }

    profileSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const profileData = selectedOption.getAttribute("data-profile");

        try {
            if (profileData) {
                const parsed = JSON.parse(profileData);
                const profile = parsed.profile || parsed;
                const pretty = JSON.stringify(parsed, null, 4);
                profileTextarea.value = pretty;
                populateFormFromProfile(profile);
                errorMsg.classList.add("d-none");
            } else {
                profileTextarea.value = "{}";

                // 🔸 Uncheck tất cả checkbox
                document.querySelectorAll("#partial-profile-form input[type='checkbox']").forEach(cb => cb.checked = false);

                const headerList = document.getElementById("custom-header-list");
                headerList.innerHTML = '';

            }

        } catch (e) {
            profileTextarea.value = profileData; // fallback if JSON.parse fails
            errorMsg.classList.remove("d-none");
        }
    });
    
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
});
</script>
@endpush