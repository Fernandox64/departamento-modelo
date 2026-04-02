@props(['title' => null])
<div {{ $attributes->merge(['class' => 'card shadow-sm border-0']) }}>
    @if($title)
        <div class="card-header bg-white"><h5 class="mb-0">{{ $title }}</h5></div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
