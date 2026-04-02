@props(['label', 'name', 'type' => 'text', 'value' => null])
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'form-control']) }}
    >
    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
