# Pimbastic Esports

Projeto PHP com modelagem de dominio para apostas em eSports e persistencia em MySQL.
Entrega 2 implementada com criacao de banco/tabelas e formularios de cadastro.

## Estrutura

- `src/Application/Forms`: servico de formularios e regras de cadastro
- `src/Domain/Enum`: enums do dominio (`TipoEscolhido`, `StatusAposta`)
- `src/Domain/Entity`: entidades (`Cliente`, `Aposta`, `Jogo`, etc.)
- `src/Infrastructure/Database`: conector PDO
- `database/schema.sql`: script de criacao do banco e tabelas
- `public/index.php`: dashboard com status do banco e formularios de cadastro
- `scripts/init_schema.php`: aplicacao manual do schema via CLI

## Funcionalidades da Entrega 2

- Cadastro de campeonato
- Cadastro de time
- Cadastro de jogo (com relacionamento campeonato/time)
- Cadastro de cliente
- Cadastro de aposta (com relacionamento cliente/jogo)
- Lista das ultimas apostas na tela principal

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

## Fluxo sugerido para demonstracao

1. Suba o ambiente com Docker.
2. Acesse `http://localhost:8080`.
3. Cadastre primeiro campeonatos, times e clientes.
4. Cadastre jogos usando os relacionamentos.
5. Cadastre apostas e mostre a lista de ultimas apostas.
