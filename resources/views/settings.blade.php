@extends('fortress::layout')

@section('title', 'Security Settings')

@section('main')
    <form action="{{ cp_route('fortress.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- WAF Settings -->
        <div class="mb-8">
            <h2 class="text-lg font-medium mb-4">Web Application Firewall</h2>
            <div class="card p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Enable WAF
                    </label>
                    <div class="flex items-center">
                        <input type="checkbox" name="waf_enabled" value="1" 
                               class="form-checkbox" {{ $settings->waf_enabled ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">
                            Protect against common web attacks
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Block Suspicious IPs
                    </label>
                    <div class="flex items-center">
                        <input type="checkbox" name="block_suspicious_ips" value="1" 
                               class="form-checkbox" {{ $settings->block_suspicious_ips ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-600">
                            Automatically block IPs that show suspicious behavior
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brute Force Protection -->
        <div class="mb-8">
            <h2 class="text-lg font-medium mb-4">Brute Force Protection</h2>
            <div class="card p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Max Login Attempts
                    </label>
                    <input type="number" name="max_login_attempts" 
                           value="{{ $settings->max_login_attempts }}"
                           class="form-control" min="1" max="10">
                    <p class="mt-1 text-sm text-gray-500">
                        Number of failed login attempts before temporary block
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Block Duration (minutes)
                    </label>
                    <input type="number" name="block_duration" 
                           value="{{ $settings->block_duration }}"
                           class="form-control" min="5" max="1440">
                    <p class="mt-1 text-sm text-gray-500">
                        How long to block IPs after exceeding max attempts
                    </p>
                </div>
            </div>
        </div>

        <!-- Country Blocking -->
        <div class="mb-8">
            <h2 class="text-lg font-medium mb-4">Country Blocking</h2>
            <div class="card p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Blocked Countries
                    </label>
                    <select name="blocked_countries[]" multiple class="form-control" size="5">
                        @foreach($countries as $code => $name)
                            <option value="{{ $code }}" 
                                    {{ in_array($code, $settings->blocked_countries) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        Select countries to block. Hold Ctrl/Cmd to select multiple.
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        GeoIP Database
                    </label>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">
                            Last updated: {{ $settings->geoip_last_update ? $settings->geoip_last_update->format('Y-m-d H:i:s') : 'Never' }}
                        </span>
                        <a href="{{ cp_route('fortress.settings.update-geoip') }}" 
                           class="btn-secondary text-sm">
                            Update Now
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary">
                Save Settings
            </button>
        </div>
    </form>
@endsection 
