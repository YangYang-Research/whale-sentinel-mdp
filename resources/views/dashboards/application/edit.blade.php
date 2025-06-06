@extends('layouts.layout_dashboard')
@push('css')
<style>
    .language-btn {
        margin-right: 12px !important;
        margin-bottom: 10px !important;
    }
</style>
@endpush
@section('dashboard')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Edit Application</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Your Application</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('application.update', ['application' => $application]) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="instance">Instance</label>
                <select class="form-control" id="instance" name="instance_id">
                    <option value="">-- Select Instance --</option>
                    @foreach($instances as $instance)
                        <option value="{{ $instance->id }}"
                            {{ old('instance_id', $application->instance_id) == $instance->id ? 'selected' : '' }}>
                            {{ $instance->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">App Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $application->name) }}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('description', $application->description) }}">
            </div>
            <div class="form-group">
                <label>Application Language</label>
                <div class="d-flex flex-wrap gap-2" id="language-selector">
                    @foreach($languages as $key => $lang)
                        <button type="button"
                            class="btn btn-outline-secondary me-2 d-flex align-items-center p-2 language-btn {{ $application->language === $key ? 'active btn-primary text-white' : '' }}"
                            data-value="{{ $key }}">
                            <img src="{{ asset($lang['icon']) }}" alt="{{ $lang['label'] }}" width="24" height="24">
                            <span class="ms-2">{{ $lang['label'] }}</span>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="language" id="selected-language"  value="{{ old('language', $application->language) }}" data-default="{{ old('language', $application->language) }}" required>
            </div>
            <div class="form-group">
                <label for="status">Select Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
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
        const buttons = document.querySelectorAll(".language-btn");
        const hiddenInput = document.getElementById("selected-language");

        const updateButtonStates = (selectedValue) => {
            buttons.forEach(button => {
                const value = button.getAttribute("data-value");

                // Reset tất cả button về mặc định
                button.classList.remove("btn-primary", "text-white", "active");
                button.classList.add("btn-outline-secondary");

                // Nếu là ngôn ngữ được chọn thì highlight
                if (value === selectedValue) {
                    button.classList.remove("btn-outline-secondary");
                    button.classList.add("btn-primary", "text-white", "active");
                }
            });
        };

        // 1. Khi trang load lần đầu
        const initialLang = hiddenInput.value;
        updateButtonStates(initialLang);

        // 2. Khi click chọn ngôn ngữ
        buttons.forEach(button => {
            button.addEventListener("click", function () {
                const selected = this.getAttribute("data-value");
                hiddenInput.value = selected;
                updateButtonStates(selected);
            });
        });

        // 3. Khi nhấn nút reset trong form
        const form = hiddenInput.closest("form");
        form.addEventListener("reset", function () {
            setTimeout(() => {
                const defaultValue = hiddenInput.getAttribute("data-default");
                hiddenInput.value = defaultValue;
                updateButtonStates(defaultValue);
            }, 0); // đợi DOM reset xong mới cập nhật lại
        });
    });
</script>

@endpush
