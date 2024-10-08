# ![Logo](https://your-image-url-here) Clean Architecture - Esqueleto em php

![Clean Architecture](https://cesarmauri.com/wp-content/uploads/sites/2/mobile-development-900.jpg)

## Descrição do Projeto

Este projeto implementa uma **API REST** para execução de filtros de intimações, baseada nas regras definidas pelos clientes. Ele segue os princípios de **Clean Architecture**, o que facilita a substituição de frameworks, adaptadores e validações de forma independente.

O projeto foi desenvolvido utilizando o **Slim Framework**, mas sem dependência rígida. É possível trocar para outro framework criando um novo adaptador, garantindo flexibilidade na estrutura.

## Estrutura de Pastas

Abaixo está a estrutura de diretórios do projeto, organizada de acordo com os princípios da **Clean Architecture**:
```
src/
├── Application
│   ├── Adapters
│   │   └── Http
│   ├── Controllers
│   ├── DTOs
│   └── Services
├── Domain
│   ├── Entities
│   ├── Repositories
│   ├── Services
│   └── ValueObjects
├── Infrastructure
│   ├── Database
│   ├── Factories
│   └── Persistence
├── Interfaces
│   ├── Http
│   └── Routes
├── Middleware
└── tests/
```

Essa estrutura permite uma clara separação de responsabilidades, facilitando a manutenção e expansão do código.

### 🛠️ Tecnologias e Dependências

- **Slim Framework**: Utilizado para as rotas da API.
- **Respect/Validation**: Usado para validações no projeto (pode ser facilmente substituído).
- **PHP-DI**: Para injeção de dependências.
- **Pest**: Framework de testes para PHP.
- **Dotenv**: Gerenciamento de variáveis de ambiente.

## Instalação

### Pré-requisitos

- PHP 8.0 ou superior
- Composer

### Passos

1. Clone o repositório:

   ```bash
   git clone https://github.com/strolker/clean-architecture
   ```

2. Navegue até a pasta do projeto:

   ```bash
   cd clean-architecture
   ```

3. Instale as dependências do Composer:

   ```bash
   composer install
   ```

## Comandos do Composer
O projeto inclui alguns comandos úteis configurados no composer.json:

- **Rodar testes:**
   ```bash
   composer test
   ```

- **Gerar autoload:**
   ```bash
   composer autoload
   ```

- **Criar o banco de dados:**
   ```bash
   composer create-database
   ```

- **Executar as migrações:**
   ```bash
   composer up-database
   ```

- **Reverter as migrações:**
   ```bash
   composer down-database
   ```

- **Popular o banco de dados:**
   ```bash
   composer seed-database
   ```

## Arquitetura
O projeto segue os princípios da Clean Architecture, garantindo:

- **Independência de frameworks:** É possível alterar o framework sem grandes impactos na aplicação.
- **Facilidade de manutenção:** A clara separação de responsabilidades permite que o código seja facilmente mantido e atualizado.
- **Inversão de dependência:** Uso de injeção de dependência para facilitar a troca de implementações.

## Autor
Desenvolvido por Jean Carlo Schmitz.
