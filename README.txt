VERSÃƒO ESTÃVEL PARA DOCKER LOCAL

Esta versÃ£o evita a dependÃªncia das tabelas complexas que causaram falha.
Ela sobe com:
- pÃ¡ginas institucionais funcionando
- menu completo
- conteÃºdo demonstrativo estÃ¡tico
- teste real de conexÃ£o com MySQL em /health.php

Como subir:
docker compose down -v
docker compose up -d --build

Acessos:
- http://localhost:8090
- http://localhost:8090/health.php
- http://localhost:8090/admin/login.php
- http://localhost:8081

Credenciais:
- Admin: admin@example.com / Admin@123456
- MySQL: newsuser / newspass
- Root: rootpass

