# Pimbastic Esports

Projeto PHP com modelagem de dominio para apostas em eSports e persistencia em MySQL.

## Estrutura

- `src/Domain/Enum`: enums do dominio (`TipoEscolhido`, `StatusAposta`)
- `src/Domain/Entity`: entidades (`Cliente`, `Aposta`, `Jogo`, etc.)
- `src/Infrastructure/Database`: conector PDO
- `database/schema.sql`: script de criacao do banco e tabelas
- `public/index.php`: endpoint simples de health check
- `scripts/init_schema.php`: aplicacao manual do schema via CLI

## Subir com Docker

```bash
docker compose up --build
```

A aplicacao fica em `http://localhost:8080`.

## Variaveis de ambiente da aplicacao

- `DB_HOST` (padrao `127.0.0.1`)
- `DB_PORT` (padrao `3306`)
- `DB_NAME` (padrao `pimbastic_esports`)
- `DB_USER` (padrao `root`)
- `DB_PASS` (padrao vazio)
- `DB_CHARSET` (padrao `utf8mb4`)

## Aplicar schema manualmente (opcional)

```bash
php scripts/init_schema.php
```
