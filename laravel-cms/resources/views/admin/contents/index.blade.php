<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Conteudos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <x-ui.admin-nav />
    <div class="container pb-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 mb-0">Conteudos</h1>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.contents.create') }}">Novo Conteudo</a>
        </div>

        <x-ui.card>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Titulo</th>
                            <th>Publicado</th>
                            <th class="text-end">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td><span class="badge text-bg-secondary">{{ $item->type }}</span></td>
                                <td>{{ $item->title }}</td>
                                <td>{{ optional($item->published_at)->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.contents.edit', $item) }}">Editar</a>
                                    <form class="d-inline" method="POST" action="{{ route('admin.contents.destroy', $item) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Excluir?')">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $items->links() }}
        </x-ui.card>
    </div>
</body>
</html>
