# Pimbastic Esports

Sistema Web para cadastro de campeonatos, times, jogos, clientes e apostas em eSports. O projeto foi migrado e atualizado para utilizar o framework **CodeIgniter 4**, com um front-end completo, moderno e responsivo.

---

## 🚀 Novidades desta Versão (Entrega 3)
- **Framework CodeIgniter 4**: Migração completa da arquitetura do projeto para a estrutura padrão MVC (Model-View-Controller) do CI4.
- **Roteamento Estrito**: Rotas configuradas no arquivo `app/Config/Routes.php` agrupando as funcionalidades de clientes (`/cliente`) e administração (`/admin`).
- **Aparência Premium (eSports Theme)**: Layout escuro de alta fidelidade com fontes modernas (`Chakra Petch` para interface e `Bebas Neue` para títulos), efeitos neon (verde/ciano) e transparências estilo glassmorphism.
- **Interface Responsiva**: Todo o painel e formulários se adaptam para dispositivos móveis e desktops com navegação retrátil.
- **Consumo de Mocks**: Listagens, histórico e formulários dinâmicos consumindo dados pré-configurados nos controllers PHP (SSR).
- **Interatividade no Sportsbook**: Odds funcionais com seleção dinâmica via JavaScript no formulário de aposta e estimativa de retorno em tempo real.

---

## 📂 Estrutura do Projeto
- `app/Config/Routes.php`: Rotas centralizadas do framework.
- `app/Controllers/`: Controladores contendo regras de negócio e mocks de dados SSR (`AuthController`, `ClienteController`, `AdminController`, etc.).
- `app/Views/`:
  - `layouts/master.php`: Layout base com importações de fontes, Tailwind CSS e navegação do portal.
  - `auth/`: Telas de login e cadastro.
  - `cliente/`: Sportsbook e histórico de apostas.
  - `admin/`: Painel de controle e sub-telas de gerenciamento (campeonatos, times, usuários).
- `public/index.php`: Front Controller que inicializa o CodeIgniter 4.
- `backup_old_system/`: Cópia de segurança contendo os arquivos PHP avulsos da entrega anterior.

---

## 🛠️ Como Executar o Projeto

### Pré-requisitos
- Docker Desktop (ou Docker Engine + Docker Compose) instalado e rodando.
- Porta `8080` livre na máquina host para a aplicação.

### Passo a Passo

1. **Inicie os Containers do Docker (Limpando Volumes Antigos):**
   Para garantir que o banco de dados e os novos volumes temporários sejam mapeados do zero, no diretório raiz do projeto execute:
   ```bash
   docker compose down -v
   docker compose up --build
   ```

2. **Acesse no Navegador:**
   Abra o seu navegador de preferência e entre em:
   ```text
   http://localhost:8080
   ```

---

## 🎮 Roteiro de Teste (Como Avaliar)

O sistema conta com um redirecionamento inteligente na tela de login para facilitar a navegação rápida entre os dois perfis da aplicação:

1. **Acesso do Administrador (Dashboard & Cadastros):**
   - Acesse `http://localhost:8080` (será redirecionado para a tela de login).
   - Digite qualquer e-mail que contenha a palavra **`admin`** (ex: `admin@pimbastic.com`) e qualquer senha.
   - Clique em **Entrar no Sistema**.
   - Você será levado ao **Painel Administrativo** exibindo métricas do sistema e status online.
   - Navegue pelo menu lateral para acessar as listagens de **Campeonatos**, **Times** e **Usuários**.
   - Em cada listagem, clique no botão de **Novo Cadastro** para abrir os formulários com validações de campos. Preencha e salve (o sistema simula o salvamento e retorna feedback visual de sucesso).

2. **Acesso do Cliente (Sportsbook & Apostas):**
   - No cabeçalho, clique em **Sair** para retornar ao Login.
   - Digite qualquer e-mail comum (ex: `cliente@pimbastic.com` ou `player@gmail.com`) e qualquer senha.
   - Clique em **Entrar no Sistema**.
   - Você será levado ao **Mercado de Apostas (Sportsbook)** do cliente.
   - O sportsbook exibe o saldo do usuário (R$ 1.500,50) e cards interativos com jogos de eSports.
   - **Interatividade das Odds**: Clique em um dos botões de odd ("Casa", "Empate" ou "Fora") de um jogo. O botão correspondente ficará marcado com brilho ciano, e um painel de cálculo aparecerá mostrando o retorno estimado da aposta em tempo real ao digitar um valor.
   - Insira um valor no input e clique em **Apostar** para registrar a aposta (mensagem de sucesso de mock é exibida).
   - Abaixo dos jogos, visualize a tabela com o histórico de apostas realizadas.
