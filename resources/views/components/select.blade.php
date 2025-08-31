@props([
'name',
'label' => '',
'options' => [],
'selected' => null,
'required' => false,
])

@php
$selected = $selected ?? old($name);
@endphp

<div class="mb-3">
    @if($label)
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    <select name="{{ $name }}" id="{{ $name }}" class="form-select" {{ $required ? 'required' : '' }}>
        <option value="">اختر {{ $label }}</option>
        @foreach($options as $key => $value)
        <option value="{{ $key }}" {{ $selected==$key ? 'selected' : '' }}>
            {{ $value }}
        </option>
        @endforeach
    </select>
    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
