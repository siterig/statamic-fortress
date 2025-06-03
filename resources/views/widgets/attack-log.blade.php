<div class="card p-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium">Recent Activity</h3>
        <button type="button" class="btn-sm" data-refresh-logs>
            Refresh
        </button>
    </div>

    <div class="space-y-4">
        @foreach($logs as $log)
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium">{{ $log->type }}</div>
                    <div class="text-xs text-gray-500">{{ $log->ip_address }}</div>
                </div>
                <div class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                </div>
            </div>
        @endforeach
    </div>
</div> 
 