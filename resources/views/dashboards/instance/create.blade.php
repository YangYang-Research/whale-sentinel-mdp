@extends('layouts.layout_dashboard')
@section('dashboard')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Create Instance</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create New Instance</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('instance.store') }}" method="post">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" value="{{ old('name') }}" required>
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