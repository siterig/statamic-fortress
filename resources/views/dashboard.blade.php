@extends('fortress::layout')

@section('title', 'Security Dashboard')

@section('actions')
    <button type="button" class="btn-primary" data-scan-vulnerabilities>
        Scan Now
    </button>
@endsection

@section('main')
    <div class="flex flex-wrap -mx-4">
        <!-- Security Stats -->
        <div class="w-full md:w-1/2 lg:w-1/4 px-4 mb-4">
            <div class="card p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Attacks Blocked</h3>
                <div class="text-2xl font-bold" data-stat="total_attacks">0</div>
            </div>
        </div>

        <div class="w-full md:w-1/2 lg:w-1/4 px-4 mb-4">
            <div class="card p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Brute Force Attempts</h3>
                <div class="text-2xl font-bold" data-stat="brute_force_attempts">0</div>
            </div>
        </div>

        <div class="w-full md:w-1/2 lg:w-1/4 px-4 mb-4">
            <div class="card p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Blocked Countries</h3>
                <div class="text-2xl font-bold" data-stat="blocked_countries">0</div>
            </div>
        </div>

        <div class="w-full md:w-1/2 lg:w-1/4 px-4 mb-4">
            <div class="card p-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Vulnerabilities Found</h3>
                <div class="text-2xl font-bold" data-stat="vulnerabilities">0</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8">
        <h2 class="text-lg font-medium mb-4">Recent Activity</h2>
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Type</th>
                        <th>IP Address</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivity as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <span class="fortress-badge fortress-badge--{{ $activity->type }}">
                                    {{ $activity->type }}
                                </span>
                            </td>
                            <td>{{ $activity->ip_address }}</td>
                            <td>
                                <button type="button" class="text-blue-500 hover:text-blue-700" 
                                        onclick="window.fortress.showDetails({{ json_encode($activity->details) }})">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Event Details</h3>
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
