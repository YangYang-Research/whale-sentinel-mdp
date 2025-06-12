<p><strong>Secure Response Header</strong></p>
<div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="secure_response_headers_enable" checked>
    <label class="form-check-label">Enable Secure Response Headers</label>
</div>

<div class="ml-3" id="secure-headers-list">
    <label>Default Headers</label>

    @php
        $defaultHeaders = [
            "Server" => "Whale Sentinel",
            "X-Whale-Sentinel" => "1",
            "Referrer-Policy" => "no-referrer-when-downgrade",
            "X-Content-Type-Options" => "nosniff",
            "X-XSS-Protection" => "1; mode=block",
            "X-Frame-Options" => "SAMEORIGIN",
            "Strict-Transport-Security" => "max-age=31536000; includeSubDomains; preload",
            "X-Permitted-Cross-Domain-Policies" => "none",
            "Expect-CT" => "enforce; max-age=31536000",
            "Feature-Policy" => "fullscreen 'self'",
            "Cache-Control" => "no-cache, no-store, must-revalidate",
            "Pragma" => "no-cache",
            "Expires" => "0",
            "X-UA-Compatible" => "IE=Edge,chrome=1",
            "Access-Control-Allow-Origin" => "*",
            "Access-Control-Allow-Methods" => "GET, POST",
            "Access-Control-Allow-Credentials" => "true",
            "Access-Control-Allow-Headers" => "Content-Type, Authorization"
        ];

        $chunks = array_chunk($defaultHeaders, ceil(count($defaultHeaders) / 2), true);
    @endphp

    <div class="row">
        @foreach ($chunks as $headerGroup)
            <div class="col-md-6">
                @foreach ($headerGroup as $key => $value)
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input secure-header"
                               data-key="{{ $key }}" data-value="{{ $value }}"
                               id="header_{{ Str::slug($key, '_') }}">
                        <label class="form-check-label">
                            {{ $key }}: <code>{{ $value }}</code>
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<div class="form-group">
    <label>Add Custom Header</label>
    <div class="d-flex">
        <input type="text" class="form-control mr-2" id="custom-header-key" placeholder="Header name">
        <input type="text" class="form-control mr-2" id="custom-header-value" placeholder="Header value">
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-custom-header">Add</button>
    </div>
</div>

<ul class="list-group mt-2" id="custom-header-list">
    <!-- Custom headers will be listed here -->
</ul>