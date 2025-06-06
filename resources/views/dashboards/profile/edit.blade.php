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
@endpush