<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Novo Conteudo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <x-ui.admin-nav />
    <div class="container pb-4">
        <h1 class="h4 mb-3">Novo Conteudo</h1>
        <x-ui.card>
            <form method="POST" action="{{ route('admin.contents.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.contents._form')
            </form>
        </x-ui.card>
    </div>
</body>
</html>
