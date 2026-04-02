<x-layouts.site title="DECOM - Inicio" body-class="software-bg">
    <div class="container py-5">
        <div class="glass rounded-4 p-4 mb-4">
            <h1 class="display-6 fw-bold mb-2">Portal DECOM com Laravel</h1>
            <p class="mb-3">Blade Components, Eloquent, Policies, Queue, Scheduler, Notifications e Telescope.</p>
            <a class="btn btn-light btn-sm me-2" href="{{ route('news.index') }}">Noticias</a>
            <a class="btn btn-outline-light btn-sm me-2" href="{{ route('editais.index') }}">Editais</a>
            @auth
                <a class="btn btn-warning btn-sm" href="{{ route('dashboard') }}">Painel</a>
            @else
                <a class="btn btn-warning btn-sm" href="{{ route('login') }}">Entrar</a>
            @endauth
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <h2 class="h4 mb-3">Noticias</h2>
                <div class="row g-3">
                    @foreach($news as $item)
                        <div class="col-md-6">
                            <x-ui.card class="h-100">
                                <a href="{{ route('content.show', $item->slug) }}" class="text-dark">
                                    <h5>{{ $item->title }}</h5>
                                    <p class="mb-1 text-muted">{{ $item->summary }}</p>
                                </a>
                            </x-ui.card>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4">
                <h2 class="h4 mb-3">Editais</h2>
                <x-ui.card>
                    <ul class="list-group list-group-flush">
                        @foreach($editais as $item)
                            <li class="list-group-item px-0">
                                <a href="{{ route('content.show', $item->slug) }}">{{ $item->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-layouts.site>
