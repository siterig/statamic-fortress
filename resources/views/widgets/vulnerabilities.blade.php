<div class="p-4 bg-white rounded-lg shadow">
    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $title }}</h3>
    
    @if(count($vulnerabilities) > 0)
        <div class="space-y-4">
            @foreach($vulnerabilities as $vulnerability)
                <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $vulnerability['package'] }}
                            </p>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $this->getSeverityClass($vulnerability['severity']) }}">
                                {{ ucfirst($vulnerability['severity']) }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $vulnerability['title'] }}
                        </p>
                        @if(isset($vulnerability['link']))
                            <a href="{{ $vulnerability['link'] }}" target="_blank" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                                View Details →
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4">
            <p class="text-sm text-gray-500">No vulnerabilities found</p>
        </div>
    @endif
</div> 
