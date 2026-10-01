<div>
    <div class="text-xs text-gray-500 mb-1">{{ $label }}</div>
    @if($value ?? null)
        <div class="text-sm font-medium text-gray-800 {{ ($mono ?? false) ? 'font-mono' : '' }}">
            {{ $value }}
            @if($suffix ?? null)
                <span class="text-xs text-gray-400 font-normal mr-1">{{ $suffix }}</span>
            @endif
        </div>
    @else
        <div class="text-sm text-gray-400">—</div>
    @endif
</div>
