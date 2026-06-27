# Pimbastic Esports

Plataforma web de apostas ficticias desenvolvida com CodeIgniter 4, com backend em PHP 8.2, frontend estatico servido por Nginx e banco MySQL 8.0 via Docker Compose.

## Visao geral

O projeto separa a aplicacao em tres partes:

- `app/`: backend MVC do CodeIgniter 4, com controllers, models, filtros, seeds e configuracoes.
- `frontend/`: telas estaticas servidas pelo Nginx para a experiencia publica e de cliente.
- `database/schema.sql`: schema inicial do MySQL, com tabelas e dados base.

## Requisitos

- Docker Desktop ou Docker Engine com Docker Compose.
- Porta `8080` livre para o frontend.
- Porta `8082` livre para o backend.
- Porta `3306` livre para o MySQL.

## Configuracao

Crie um arquivo `.env` na raiz do projeto com as variaveis usadas pelo `compose.yaml`:

```env
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=pimbastic_esports
MYSQL_USER=root
MYSQL_PASSWORD=root
```

Se quiser alterar o acesso inicial do administrador, tambem pode definir:

```env
ADMIN_EMAIL=admin@pimbastic.local
ADMIN_PASSWORD=admin123
```

## Como executar

1. Suba a infraestrutura limpa:

```bash
docker compose down -v
docker compose up --build -d
```

2. Verifique os servicos:

```bash
docker compose ps
```

3. Acesse a aplicacao:

- Frontend publico: http://localhost:8080
- Backend CodeIgniter: http://localhost:8082
- MySQL: localhost:3306

## Banco de dados

O container do MySQL carrega automaticamente o schema em `database/schema.sql` na primeira inicializacao do volume. Esse script cria as tabelas principais e insere dados basicos para campeonato, times, jogo e cliente.

Se precisar recriar tudo do zero, use `docker compose down -v` antes de subir novamente.

## Seeder de administrador

Depois que os containers estiverem ativos, crie o primeiro usuario admin com:

```bash
php spark db:seed AdminSeeder
```

Se as variaveis `ADMIN_EMAIL` e `ADMIN_PASSWORD` nao estiverem definidas, o seeder usa os padroes:

- Email: `admin@pimbastic.local`
- Senha: `admin123`

## Rotas principais

As rotas do backend estao definidas em `app/Config/Routes.php` e incluem:

- `POST /auth/login`
- `POST /auth/register`
- `POST /auth/logout`
- `GET /cliente/dashboard`
- `POST /cliente/saldo`
- `POST /apostas`
- `PUT /apostas/{id}`
- `DELETE /apostas/{id}`
- `GET /admin/dashboard`
- `GET /admin/jogos-recentes`
- CRUD de `campeonatos`, `times`, `jogos` e `usuarios`
- rotinas de `liquidacao`

## Estrutura do projeto

```text
app/
frontend/
database/schema.sql
public/
compose.yaml
Dockerfile
README.md
```

## Notas de operacao

- O backend usa `public/` como document root.
- O Apache do container `app` tem `mod_rewrite` habilitado para as rotas do CodeIgniter.
- O frontend e o backend compartilham a rede `app-network` definida no Compose.
- O volume `db_data` persiste os dados do MySQL entre reinicios.

## Desenvolvimento local

Para acompanhar logs dos servicos:

```bash
docker compose logs -f app
docker compose logs -f frontend
docker compose logs -f db
```

Para testar o banco inicializado, consulte as tabelas criadas pelo schema ou rode um `SELECT` no MySQL depois da subida dos containers.


