@extends('layouts.layout_dashboard')
@push('css')
<!-- Custom styles for this page -->
<link href="{{ asset ('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('dashboard')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Agent</h1>
    <a href="{{ route('agent.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Create New Agent</a>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Your Agent</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>App Name</th>
                        <th>Language</th>
                        <th>Agent Name</th>
                        <th>Type</th>
                        <th>IP</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>App Name</th>
                        <th>Language</th>
                        <th>Agent Name</th>
                        <th>Type</th>
                        <th>IP</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($agents as $agent)
                    <tr>
                        <td>{{ $agent->id }}</td>
                        <td>{{ $agent->application->name }}</td>
                        <td>
                            @php
                                $lang = $languages[$agent->application->language] ?? null;
                            @endphp

                            @if($lang)
                                <img src="{{ asset($lang['icon']) }}" alt="{{ $lang['label'] }}" width="24" height="24" class="me-1">
                                <span>{{ $lang['label'] }}</span>
                            @else
                                {{ $agent->application->language }}
                            @endif
                        </td>
                        <td>{{ $agent->name }}</td>
                        <td>
                            @php
                                $langKey = $agent->application->language;
                                $lang = $languages[$langKey] ?? null;
                                $agentType = $agent->type;
                                $agentInfo = null;

                                if ($lang && isset($lang['agents'])) {
                                    foreach ($lang['agents'] as $ag) {
                                        if ($ag['name'] === $agentType) {
                                            $agentInfo = $ag;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            @if($agentInfo)
                                <img src="{{ asset($agentInfo['icon']) }}" alt="{{ $agentInfo['name'] }}" width="24" height="24" class="me-1">
                                <span>{{ $agentInfo['name'] }}</span>
                            @else
                                {{ $agentType }}
                            @endif
                        </td>
                        <td>{{ $agent->ipaddress }}</td>
                        <td>@if($agent->status == 'connected')
                                Connected
                            @else
                                Disconnect
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-info btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </a>
                            <a href="{{ route('agent.edit', ['agent' => $agent]) }}" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-pen-square"></i>
                                </span>
                            </a>
                            <a href="#" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('script')
<!-- Page level plugins -->
<script src="{{ asset ('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset ('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset ('assets/js/demo/datatables-demo.js') }}"></script>
@endpush