# D20 Emporium - E-commerce de Jogos de Tabuleiro

## Visão Geral
Projeto desenvolvido como parte da disciplina de Programação II. O sistema é um e-commerce básico focado na venda de jogos de tabuleiro. O objetivo é aplicar os conceitos de desenvolvimento web com PHP e MySQL.

## Funcionalidades Implementadas
✔️ Cadastro e login de usuários com senha protegida por hash  
✔️ CRUD completo de usuários (cadastrar, listar, editar, excluir)  
✔️ CRUD completo de produtos (cadastrar, listar, editar, excluir)  
✔️ Validação de entrada de dados  
✔️ Sessões para controle de acesso  
✔️ Design com Bootstrap  

## Tecnologias Utilizadas
- PHP (versão 7+)
- MySQL / MariaDB
- HTML5 / CSS3
- Bootstrap 4
- XAMPP (ambiente local)

## Estrutura do Projeto
```
/d20Emporium/
├── conecta.php
├── login.php
├── logout.php
├── verificalogin.php
├── index.php
├── formusuario.php
├── salvar_usuario.php
├── lista_usuarios.php
├── formProduto.php
├── salvar_produto.php
├── lista_produtos.php
├── errosessao.php
├── errologin.php
├── estruturaBD.txt
├── excluir_produto.php
├── excluir_usuario.php
├── rodape.php
├── topo.php
├── README.md
├── css/
└── js/
```

## ⚙️ Como Executar Localmente

### 1. Instale um servidor local:
- [XAMPP](https://www.apachefriends.org/index.html)

### 2. Clone ou baixe este repositório:
```bash
git clone https://github.com/beatrizlauro/ecommerceJogosTabuleiro.git
```

### 3. Coloque os arquivos na pasta:
```
htdocs/d20-emporium
```

### 4. Inicie Apache e MySQL no painel do XAMPP/Wamp

---

## 🛠️ Criando o Banco de Dados no phpMyAdmin

### Acesse:
```
http://localhost/phpmyadmin
```

### Execute o seguinte SQL para criar o banco e as tabelas:

```sql
CREATE DATABASE d20_emporium;
USE d20_emporium;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL,
    imagem LONGBLOB
);
```

## Acesso ao Sistema
1. Acesse `http://localhost/d20Emporium/login.php`
2. Faça login com um usuário cadastrado
3. Navegue pelo sistema (painel de administração, cadastro de usuários e produtos)

## Desenvolvido por
- Ana Flávia Alves Rosa
- Beatriz da Costa Lauro  
- Brenda Bonaita de Oliveira  

Universidade do Estado de Minas Gerais – Sistemas de Informação, 5º período - Programação II  – 2025.

---
**D20 Emporium**
