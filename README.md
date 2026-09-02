# 🐾 Clínica Veterinária

Sistema web para gerenciamento de uma clínica veterinária, desenvolvido com **Laravel 12, PHP, Blade, Bootstrap e MySQL**.

## 📌 Funcionalidades

- Cadastro de tutores, animais, espécies e raças
- Agendamento e gerenciamento de consultas
- Cadastro de exames com anexos
- Registro de vacinações
- Emissão de receitas
- Histórico clínico dos animais
- Gerenciamento de usuários e permissões
- Dashboard com indicadores e gráficos
- Relatórios de consultas em PDF

## 🛠️ Tecnologias

- PHP 8.2+
- Laravel 12
- MySQL
- Blade
- Bootstrap 5
- Vite
- Chart.js
- DomPDF

## 🚀 Instalação

### 1. Instalar dependências

```bash
composer install
npm install
npm run build
```

### 2. Configurar o `.env`

Crie o arquivo `.env` na raiz do projeto e configure principalmente:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### 3. Gerar a chave da aplicação

```bash
php artisan key:generate
```

### 4. Criar as tabelas e dados iniciais

```bash
php artisan migrate:fresh --seed
```

> **Atenção:** `migrate:fresh` apaga as tabelas existentes e recria o banco.

### 5. Executar o projeto

```bash
php artisan serve
```

Acesse:

```text
http://localhost:8000
```

Para desenvolvimento do frontend:

```bash
npm run dev
```

## 📂 Estrutura

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/

database/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/

routes/
tests/
```

## 🧪 Testes

```bash
php artisan test
```

## 📋 Principais comandos

| Comando | Função |
|---|---|
| `composer install` | Instala dependências PHP |
| `npm install` | Instala dependências frontend |
| `npm run build` | Gera o build |
| `php artisan key:generate` | Gera a chave da aplicação |
| `php artisan migrate:fresh --seed` | Recria o banco e executa os seeders |
| `php artisan serve` | Inicia o servidor |
| `npm run dev` | Inicia o Vite |
| `php artisan test` | Executa os testes |