@extends('fortress::layout')

@section('title', 'Security Logs')

@section('actions')
    <div class="flex items-center space-x-2">
        <select class="form-control" name="type">
            <option value="">All Types</option>
            <option value="attack">Attacks</option>
            <option value="brute_force">Brute Force</option>
            <option value="country_block">Country Block</option>
            <option value="vulnerability">Vulnerability</option>
        </select>
        <button type="button" class="btn-primary" data-refresh-logs>
            Refresh
        </button>
    </div>
@endsection

@section('main')
    <div class="card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Type</th>
                    <th>IP Address</th>
                    <th>Country</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <span class="fortress-badge fortress-badge--{{ $log->type }}">
                                {{ $log->type }}
                            </span>
                        </td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->country ?? 'Unknown' }}</td>
                        <td>
                            <button type="button" class="text-blue-500 hover:text-blue-700" 
                                    onclick="window.fortress.showDetails({{ json_encode($log->details) }})">
                                View Details
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Log Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="window.fortress.hideDetails()">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <pre id="detailsContent" class="bg-gray-100 p-4 rounded-lg overflow-auto max-h-96"></pre>
        </div>
    </div>
@endsection 
 