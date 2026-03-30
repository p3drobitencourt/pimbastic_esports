# Pimbastic Esports

Sistema PHP para cadastro de campeonatos, times, jogos, clientes e apostas em eSports, com persistencia em MySQL.

## Visao geral da Entrega 2

- Dashboard inicial com status do banco e resumo dos cadastros
- Formularios separados por pagina (navegacao por links)
- Validacao no backend (PHP) para os campos principais
- Relacionamentos entre entidades (campeonato/time/jogo/cliente/aposta)
- Preenchimento automatico de odd no formulario de aposta com base no jogo escolhido

## Estrutura principal

- `public/index.php`: dashboard principal
- `public/formularios/`: paginas de formulario
	- `campeonato.php`
	- `time.php`
	- `jogo.php`
	- `cliente.php`
	- `aposta.php`
- `src/Application/Forms/FormsService.php`: regras de negocio e persistencia dos formularios
- `src/Infrastructure/Database/DatabaseConnector.php`: conexao PDO
- `database/schema.sql`: criacao das tabelas
- `compose.yaml`: ambiente Docker (app + MySQL)

## Requisitos

- Docker Desktop (ou Docker Engine + Compose)
- Porta `8080` livre para a aplicacao
- Porta `3306` livre para o MySQL (opcional, pois esta exposta no compose)

## Como executar

1. Entre na pasta do projeto:

```bash
cd pimbastic_esports
```

2. Suba os containers:

```bash
docker compose up --build
```

3. Acesse no navegador:

```text
http://localhost:8080
```

## Reset do banco (quando precisar)

Se quiser limpar dados antigos e recriar tudo do zero:

```bash
docker compose down -v
docker compose up --build
```

## Fluxo de demonstracao (para apresentar)

1. Abrir o dashboard (`/`) e mostrar status ONLINE do banco.
2. Clicar em `Novo Campeonato` e cadastrar 1 item.
3. Clicar em `Novo Time` e cadastrar 2 times.
4. Clicar em `Novo Cliente` e cadastrar 1 cliente.
5. Clicar em `Novo Jogo` e vincular campeonato + dois times.
6. Clicar em `Nova Aposta`, escolher cliente/jogo/tipo e mostrar odd preenchida automaticamente.
7. Voltar ao dashboard e mostrar os dados atualizados.

## Roteiro rapido para o grupo (fala sugerida)

- Pessoa 1: contexto do problema e arquitetura (dashboard + service + banco)
- Pessoa 2: execucao com Docker e estrutura de pastas
- Pessoa 3: demonstracao dos cadastros encadeados
- Pessoa 4: validacoes no PHP e odd automatica na aposta

## Problemas comuns

- `docker: command not found`:
	- Docker nao esta instalado ou nao foi iniciado.
- Erro de porta ocupada:
	- altere a porta no `compose.yaml` (ex.: `8081:80`) e acesse `http://localhost:8081`.
- Banco com dados antigos:
	- rode `docker compose down -v` antes de subir novamente.
