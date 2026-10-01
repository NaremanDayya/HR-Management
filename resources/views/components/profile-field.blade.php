<div>
    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">{{ $label }}</div>
    @if(($value ?? null) !== null && $value !== '')
        <div class="text-sm font-bold text-gray-900 {{ ($mono ?? false) ? 'font-mono' : '' }}">
            {{ $value }}
            @if($suffix ?? null)
                <span class="text-xs font-semibold text-gray-400 mr-1">{{ $suffix }}</span>
            @endif
        </div>
    @else
        <div class="text-sm font-semibold text-gray-400">—</div>
    @endif
</div>
