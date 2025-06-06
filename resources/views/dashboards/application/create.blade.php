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
<h1 class="h3 mb-4 text-gray-800">Create Application</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create New Application</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('application.store') }}" method="post">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="instance">Instance</label>
                <select class="form-control" id="instance" name="instance_id">
                    <option value="">-- Select Instance --</option>
                    @foreach($instances as $instance)
                    <option value="{{ $instance->id }}">{{ $instance->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">App Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('description') }}" required>
            </div>
            <div class="form-group">
                <label>Application Language</label>
                <div class="d-flex flex-wrap gap-2" id="language-selector">
                    @foreach($languages as $key => $lang)
                        <button type="button"
                            class="btn btn-outline-secondary me-2 d-flex align-items-center p-2 language-btn"
                            data-value="{{ $key }}">
                            <img src="{{ asset($lang['icon']) }}" alt="{{ $lang['label'] }}" width="24" height="24">
                            <span class="ms-2">{{ $lang['label'] }}</span>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="language" id="selected-language" required>
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

        buttons.forEach(button => {
            button.addEventListener("click", function () {
                // Xóa class highlight khỏi tất cả
                buttons.forEach(btn => btn.classList.remove("btn-primary", "text-white"));
                buttons.forEach(btn => btn.classList.add("btn-outline-secondary"));

                // Highlight button được chọn
                this.classList.remove("btn-outline-secondary");
                this.classList.add("btn-primary", "text-white");

                // Gán giá trị cho input ẩn
                hiddenInput.value = this.getAttribute("data-value");
            });
        });
    });
</script>
@endpush
