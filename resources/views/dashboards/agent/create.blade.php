@extends('layouts.layout_dashboard')
@push('css')
<style>
    .agent-btn {
        margin-right: 12px !important;
        margin-bottom: 10px !important;
    }
</style>
@endpush
@section('dashboard')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Create Agent</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create New Agent</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('agent.store') }}" method="post">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="instance">Select Your Instance</label>
                <select class="form-control" id="instance" name="instance">
                    @foreach($instances as $instance)
                    <option value="{{ $instance->id }}">{{ $instance->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control">
            </div>
            <div class="form-group">
                <label for="language">Select Language</label>
                <div class="flex-wrap mt-2">
                    @foreach($languages as $lang => $info)
                        <button type="button"
                            class="btn btn-lg btn-outline-primary language-btn"
                            data-lang="{{ $lang }}">
                            <img src="{{ asset('assets/icons/lang/' . $info['icon']) }}"
                                alt="{{ $lang }}"
                                style="height: 40px;">
                            <div>{{ ucfirst($lang) }}</div>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="language" id="language">
            </div>
            <div class="form-group mt-3" id="agent-type-group" style="display: none;">
                <label>Select Agent Type</label>
                <div class="d-flex flex-wrap mt-2" id="agent-buttons-container"></div>
                <input type="hidden" name="agent_type" id="agent_type">
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
    const languageData = @json($languages);
    const agentTypeGroup = document.getElementById('agent-type-group');
    const agentButtonsContainer = document.getElementById('agent-buttons-container');
    const languageInput = document.getElementById('language');
    const agentTypeInput = document.getElementById('agent_type');

    // Xử lý chọn ngôn ngữ
    document.querySelectorAll('.language-btn').forEach(button => {
        button.addEventListener('click', function () {
            const selectedLang = this.dataset.lang;
            languageInput.value = selectedLang;

            // Highlight ngôn ngữ được chọn
            document.querySelectorAll('.language-btn').forEach(btn => btn.classList.remove('btn-primary'));
            this.classList.add('btn-primary');

            // Clear agent buttons
            agentButtonsContainer.innerHTML = '';
            agentTypeInput.value = '';

            // Tạo buttons cho agent type
            const agents = languageData[selectedLang]?.agents || [];
            if (agents.length > 0) {
                agents.forEach(agent => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-outline-secondary agent-btn me-3 mb-2';
                    btn.textContent = agent;
                    btn.dataset.agent = agent;
                    btn.addEventListener('click', function () {
                        // Xóa active cũ
                        document.querySelectorAll('.agent-btn').forEach(b => b.classList.remove('btn-secondary'));
                        this.classList.add('btn-secondary');
                        agentTypeInput.value = this.dataset.agent;
                    });
                    agentButtonsContainer.appendChild(btn);
                });
                agentTypeGroup.style.display = 'block';
            } else {
                agentTypeGroup.style.display = 'none';
            }
        });
    });
</script>
@endpush