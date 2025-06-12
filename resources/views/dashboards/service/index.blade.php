@extends('layouts.layout_dashboard')
@push('css')
<!-- Custom styles for this page -->
<link href="{{ asset ('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('dashboard')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Service</h1>
</div>

<div class="row">
    @foreach($services as $service)
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            {{$service->name}}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('service.edit', ['service' => $service]) }}" class="btn">
                            <i class="fas fa-pen-square fa-2x text-gray-300"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
@push('script')

@endpush