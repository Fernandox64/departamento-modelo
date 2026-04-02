# DECOM Laravel CMS

Base Laravel com os 10 recursos solicitados:

1. Blade Components: `x-ui.card`, `x-ui.admin-nav`, `x-ui.form-field`.
2. Eloquent + Migrations + Seeders: `Content`, `Role`, `ImportRun` + seed de admin.
3. Validation (Form Requests): `StoreContentRequest`, `UpdateContentRequest`.
4. Auth + Gates/Policies: Breeze + `ContentPolicy` + papel `admin/editor`.
5. Storage: upload de imagem em `public` disk.
6. Cache: home/listas de noticias e editais com `Cache::remember`.
7. Queues + Jobs: `ImportDecomNewsJob`.
8. Scheduler: `decom:import-news --pages=10` diariamente em `routes/console.php`.
9. Notifications: `NewEditalPublished` (database + mail).
10. Telescope: instalado e protegido por gate para admin.

## Credenciais iniciais

- Email: `admin@example.com`
- Senha: `Admin@123456`

## Comandos úteis

```bash
php artisan migrate --seed
php artisan storage:link
php artisan decom:import-news --pages=10 --sync
php artisan queue:work
php artisan schedule:work
```

## Fundo visual para software

A home (`resources/views/home.blade.php`) já usa fundo com estética tech:
- gradientes azul escuro
- brilho ciano
- estilo glass para cards
