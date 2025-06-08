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
<h1 class="h3 mb-4 text-gray-800">Agent Profile</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Your Agent Profile</h6>
            <h6 class="m-0 font-weight-bold text-primary download-agent">
                <form action="{{ route('agent.export.env') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" value="{{ $agent->id }}">
                    <button class="btn btn-primary btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-down"></i>
                        </span>
                        <span class="text">Download The Agent .env</span>
                    </button>
                </form> 
            </h6>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="application">Application: {{ $agent->application->name }}</label>
        </div>

        <div class="form-group mt-3" id="language-info">
            <label>Language: 
                @php
                    $lang = $languages[$agent->application->language] ?? null;
                @endphp

                @if($lang)
                    <img src="{{ asset($lang['icon']) }}" alt="{{ $lang['label'] }}" width="24" height="24" class="me-1">
                    <span>{{ $lang['label'] }}</span>
                @else
                    {{ $agent->application->language }}
                @endif
            </label>
        </div>

        <div class="form-group mt-3" id="agent-list">
            <label>Agent: 
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
            </label>
        </div>

        <div class="form-group">
            <label for="name">Name: {{ $agent->name }}</label>
        </div>

        <div class="form-group">
            <label for="description">Description: {{ $agent->description }}</label>
        </div>

        <div class="form-group">
            <label for="id">Agent ID: {{ $agent->agent_id }}</label>
        </div>

        <div class="form-group">
            <label for="profile">Agent Profile Detail</label>  
            <textarea rows="10" cols="50" id="profile" name="profile" class="form-control" disabled required>{{ $agent->profile }}</textarea>
        </div>

        <div class="form-group">
            <button class="btn btn-sm btn-warning" type="back" onclick="goBack()">Back</button>
        </div>
    </div>
</div>
@endsection