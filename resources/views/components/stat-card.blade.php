@props(['title', 'value', 'color' => 'gray', 'action' => null])

@php
    $colorClasses = [
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'red' => 'bg-red-50 text-red-600',
        'yellow' => 'bg-yellow-50 text-yellow-500',
        'purple' => 'bg-purple-50 text-purple-600',
        'gray' => 'bg-gray-100 text-gray-700',
    ];

    $baseClasses = 'shadow rounded-2xl p-6 text-center transition duration-200';
    $hoverable = $action ? 'cursor-pointer hover:shadow-lg hover:bg-opacity-90' : '';
    $combinedClasses = "{$baseClasses} {$hoverable} " . ($colorClasses[$color] ?? $colorClasses['gray']);
@endphp

<div {{ $attributes->merge(['class' => $combinedClasses]) }}
    @if ($action) onclick="window.location.href='{{ $action }}'" @endif>
    <h3 class="text-gray-600">{{ $title }}</h3>
    <p class="text-2xl font-bold">{{ $value }}</p>
</div>
