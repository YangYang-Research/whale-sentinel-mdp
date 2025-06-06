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
<h1 class="h3 mb-4 text-gray-800">Update Agent</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Update Your Agent</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('agent.update', ['agent' => $agent]) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="application">Application</label>
                <select class="form-control" id="application" name="application_id">
                    <option value="">-- Select Application --</option>
                    @foreach($applications as $application)
                        <option value="{{ $application->id }}" {{ $application->id == $agent->application_id ? 'selected' : '' }} data-language="{{ $application->language }}">
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
                @php
                    $displayName = old('name', \Illuminate\Support\Str::after($agent->name, 'ws_agent_'));
                @endphp
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $displayName }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('description', $agent->description) }}" required>
            </div>

            <div class="form-group">
                <label for="agent_profile">Agent Profile</label>
                <select class="form-control" id="agent_profile" name="agent_profile">
                    <option value="">-- Select Profile --</option>
                    @foreach($profiles as $profile)
                        <option value="{{ $profile->id }}" data-profile="{{ ($profile->profile) }}">
                            {{ $profile->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="profile">Profile Detail</label>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Paste valid JSON and click "Beautify" to format it.</small>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="beautify-json">
                        Beautify JSON
                    </button>
                </div>   
                <textarea rows="10" cols="50" id="profile" name="profile" class="form-control" required>{{ old('profile', $agent->profile) }}</textarea>
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
        const languageConfig = @json($languages);
        const applicationSelect = document.getElementById("application");
        const langInfo = document.getElementById("language-info");
        const langImg = document.getElementById("lang-img");
        const langLabel = document.getElementById("lang-label");

        const agentList = document.getElementById("agent-list");
        const agentButtons = document.getElementById("agent-buttons");
        const selectedAgentInput = document.getElementById("selected-agent");

        const currentApplication = applicationSelect.value;
        const currentAgentType = "{{ $agent->type }}";

        function renderLanguageAndAgents(languageKey) {
            if (!languageConfig[languageKey]) {
                langInfo.style.display = "none";
                agentList.style.display = "none";
                return;
            }

            const lang = languageConfig[languageKey];
            langInfo.style.display = "block";
            langImg.src = '/' + lang.icon;
            langImg.alt = languageKey;
            langLabel.textContent = lang.label;

            // render supported agents
            agentButtons.innerHTML = '';
            if (lang.agents && Array.isArray(lang.agents)) {
                lang.agents.forEach(agent => {
                    const btn = document.createElement('button');
                    btn.type = "button";
                    btn.className = "btn btn-outline-secondary d-flex align-items-center agent-btn p-2";
                    btn.dataset.value = agent.name;

                    const icon = document.createElement('img');
                    icon.src = '/' + agent.icon;
                    icon.width = 24;
                    icon.height = 24;

                    const text = document.createElement('span');
                    text.className = "ms-2";
                    text.textContent = agent.name;

                    btn.appendChild(icon);
                    btn.appendChild(text);

                    // mark selected agent
                    if (agent.name === currentAgentType) {
                        btn.classList.remove('btn-outline-secondary');
                        btn.classList.add('btn-primary', 'text-white');
                        selectedAgentInput.value = agent.name;
                    }

                    btn.addEventListener("click", function () {
                        document.querySelectorAll(".agent-btn").forEach(b => {
                            b.classList.remove("btn-primary", "text-white");
                            b.classList.add("btn-outline-secondary");
                        });
                        this.classList.add("btn-primary", "text-white");
                        this.classList.remove("btn-outline-secondary");
                        selectedAgentInput.value = this.dataset.value;
                    });

                    agentButtons.appendChild(btn);
                });
                agentList.style.display = "block";
            } else {
                agentList.style.display = "none";
            }
        }

        // Initial render if currentApplication exists
        if (currentApplication) {
            const selectedOption = applicationSelect.querySelector(`option[value="${currentApplication}"]`);
            const languageKey = selectedOption ? selectedOption.dataset.language : null;
            if (languageKey) renderLanguageAndAgents(languageKey);
        }

        applicationSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            const languageKey = selectedOption.getAttribute("data-language");
            renderLanguageAndAgents(languageKey);
            selectedAgentInput.value = ''; // reset agent type
        });
    });
</script>

<!-- <script>
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
</script> -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const textarea = document.getElementById("profile");
    const errorMsg = document.getElementById("json-error");

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

    const beautifyBtn = document.getElementById("beautify-json");
    const profileTextarea = document.getElementById("profile");

    beautifyBtn.addEventListener("click", function () {
        try {
            const json = JSON.parse(profileTextarea.value);
            const pretty = JSON.stringify(json, null, 4);
            profileTextarea.value = pretty;
        } catch (e) {
            alert("Invalid JSON format. Please check again.");
        }
    });
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const profileSelect = document.getElementById("agent_profile");
        const profileTextarea = document.getElementById("profile");

        profileSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            const profileData = selectedOption.getAttribute("data-profile");

            try {
                if (profileData) {
                    const json = JSON.parse(profileData);
                    const pretty = JSON.stringify(json, null, 4);
                    profileTextarea.value = pretty;
                } else {
                    profileTextarea.value = "{}";
                }
            } catch (e) {
                profileTextarea.value = profileData; // fallback
            }
        });

        // Beautify button
        document.getElementById("beautify-json").addEventListener("click", function () {
            try {
                const json = JSON.parse(profileTextarea.value);
                const pretty = JSON.stringify(json, null, 4);
                profileTextarea.value = pretty;
                document.getElementById("json-error").classList.add("d-none");
            } catch (e) {
                document.getElementById("json-error").classList.remove("d-none");
            }
        });
    });
</script>
@endpush