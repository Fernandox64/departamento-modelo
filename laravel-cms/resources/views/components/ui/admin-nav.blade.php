<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">DECOM CMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.contents.index') }}">Conteudos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('news.index') }}" target="_blank">Noticias</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('editais.index') }}" target="_blank">Editais</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/telescope') }}" target="_blank">Telescope</a></li>
            </ul>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm">Sair</button>
            </form>
        </div>
    </div>
</nav>
