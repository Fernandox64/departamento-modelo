VERSÃO ESTÁVEL PARA DOCKER LOCAL

Esta versão evita a dependência das tabelas complexas que causaram falha.
Ela sobe com:
- páginas institucionais funcionando
- menu completo
- conteúdo demonstrativo estático
- teste real de conexão com MySQL em /health.php

Como subir:
docker compose down -v
docker compose up -d --build

Acessos:
- http://localhost:8080
- http://localhost:8080/health.php
- http://localhost:8080/admin/login.php
- http://localhost:8081

Credenciais:
- Admin: admin@example.com / Admin@123456
- MySQL: newsuser / newspass
- Root: rootpass
