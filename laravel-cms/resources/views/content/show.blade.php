<x-layouts.site :title="$item->title">
    <div class="container py-4">
        <x-ui.card>
            <span class="badge text-bg-primary">{{ $item->category }}</span>
            <h1 class="h3 mt-2">{{ $item->title }}</h1>
            <p class="lead text-muted">{{ $item->summary }}</p>
            @if($item->image_path)
                <img src="{{ asset('storage/' . $item->image_path) }}" class="img-fluid rounded mb-3" alt="{{ $item->title }}">
            @endif
            <div>{!! $item->body !!}</div>
        </x-ui.card>
    </div>
</x-layouts.site>
