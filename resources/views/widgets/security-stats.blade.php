<div class="card p-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium">Security Overview</h3>
        <button type="button" class="btn-sm" data-refresh-stats>
            Refresh
        </button>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <div class="text-sm text-gray-500">Total Attacks</div>
            <div class="text-2xl font-bold" data-stat="total_attacks">{{ $stats['total_attacks'] }}</div>
        </div>

        <div>
            <div class="text-sm text-gray-500">Brute Force Attempts</div>
            <div class="text-2xl font-bold" data-stat="brute_force_attempts">{{ $stats['brute_force_attempts'] }}</div>
        </div>

        <div>
            <div class="text-sm text-gray-500">Blocked Countries</div>
            <div class="text-2xl font-bold" data-stat="blocked_countries">{{ $stats['blocked_countries'] }}</div>
        </div>

        <div>
            <div class="text-sm text-gray-500">Vulnerabilities</div>
            <div class="text-2xl font-bold" data-stat="vulnerabilities">{{ $stats['vulnerabilities'] }}</div>
        </div>
    </div>
</div> 
 