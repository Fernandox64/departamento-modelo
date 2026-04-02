<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'DECOM Laravel CMS' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <style>
        body.software-bg {
            background:
                radial-gradient(circle at 15% 20%, rgba(45, 212, 191, 0.20), transparent 40%),
                radial-gradient(circle at 85% 25%, rgba(56, 189, 248, 0.18), transparent 35%),
                linear-gradient(135deg, #0b1220 0%, #0f1b2d 50%, #1e3a5f 100%);
            color: #eef2ff;
            min-height: 100vh;
        }
        .glass { background: rgba(255,255,255,.08); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,.12); }
        .card a { text-decoration: none; }
    </style>
</head>
<body class="{{ $bodyClass ?? '' }}">
{{ $slot }}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
