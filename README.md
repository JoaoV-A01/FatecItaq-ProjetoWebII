# Projeto Web - Sistema de Gerenciamento de Produtos e Usuários

Este repositório contém o código-fonte de um sistema de gerenciamento de produtos e usuários, desenvolvido como parte de um projeto educacional. O projeto é uma aplicação web que segue o padrão de arquitetura MVC e a arquitetura de comunicação REST. Ele oferece funcionalidades específicas para diferentes tipos de usuários, como visitantes não logados, usuários padrão e usuários com privilégios de vendedor ou administrador.

## Funcionalidades

### Para Usuários Não Logados:

- Exibe uma lista de produtos cadastrados.

### Para Usuários Cadastrados:

- É possível realizar o cadastro ao fornecer nome, email, senha e endereço.
  - O endereço é automaticamente preenchido ao fornecer o CEP.

### Para Usuários Logados (Perfil Vendedor):

- Autorizado a cadastrar, editar, excluir, criar venda e ver gráficos dos produtos vendidos.

### Para Usuários Logados (Perfil Admin):

- Autorizado a cadastrar, editar e excluir usuários, endereços e produtos.
- Pode ver gráficos das idades dos usuários cadastrados e das vendas.

### Observações:

- Todas as operações de exclusão, atualização ou inserção no banco de dados são gerenciadas por triggers que mantêm uma tabela espelho, garantindo a integridade dos registros.

## Tipo de Trabalho Realizado

O projeto foi desenvolvido de forma progressiva, incorporando funcionalidades a cada aula. Desde operações básicas CRUD até a implementação de um sistema de login e perfis, o código reflete as práticas modernas e reais de desenvolvimento de software. A arquitetura desacoplada do front-end e back-end segue as melhores práticas de desenvolvimento web.

## Tecnologias Utilizadas

- **Front-End:**
  - HTML, CSS, JavaScript

- **Back-End:**
  - PHP

- **Banco de Dados:**
  - MySQL

- **Ferramentas de Desenvolvimento:**
  - VSCode

- **Controle de Versão:**
  - GitHub e Git

- **API Externa:**
  - API Via CEP para obtenção de endereço completo dos usuários

- **Hospedagem do Banco de Dados:**
  - 000webhost.com
