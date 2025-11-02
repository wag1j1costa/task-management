# Instalação

### Requisitos:
xampp (baixar ultima versão para melhor compatibilidade), laragon ou implementação lamp
"php": "^8.2",
"laravel/framework": "^12.0"

## 1. Clonar
git clone https://github.com/wag1j1costa/task-management.git
cd task-management

## 2. Instalar dependências
composer install

## 3. Configurar .env
copy .env.example .env
## EDITAR .env com suas credenciais MySQL!

## 4. Gerar chave
php artisan key:generate

## 5. Criar banco
mysql -uroot -p -e "CREATE DATABASE task_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

## 6. Executar migrations (se você não criou o banco ainda, ele será criado aqui)
php artisan migrate

## 7. Link do storage
php artisan storage:link

## 8. Iniciar servidor
php artisan serve
