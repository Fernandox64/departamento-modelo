<x-layouts.site :title="$title">
    <div class="container py-4">
        <h1 class="h3 mb-3">{{ $title }}</h1>
        <div class="row g-3">
            @foreach($items as $item)
                <div class="col-md-6 col-lg-4">
                    <x-ui.card class="h-100">
                        <a href="{{ route('content.show', $item->slug) }}" class="text-dark">
                            <span class="badge text-bg-secondary">{{ $item->category }}</span>
                            <h5 class="mt-2">{{ $item->title }}</h5>
                            <p class="mb-0 text-muted">{{ $item->summary }}</p>
                        </a>
                    </x-ui.card>
                </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</x-layouts.site>
