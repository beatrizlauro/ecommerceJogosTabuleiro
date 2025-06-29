# 🧙‍♂️ D20 Emporium - E-commerce de Jogos de Tabuleiro

Este é um projeto acadêmico desenvolvido para a disciplina **Programação II**, com o objetivo de aplicar conceitos de programação web, banco de dados e desenvolvimento de sistemas em PHP.

---

## 💻 Tecnologias Utilizadas

- **PHP** (versão 7+)
- **MySQL** (via phpMyAdmin)
- **Bootstrap 4/5** (layout responsivo)
- HTML5 + CSS3

---

## 🧩 Funcionalidades

### 👥 Usuários
- Cadastro de novos usuários
- Edição de usuários existentes
- Exclusão de usuários
- Login (em desenvolvimento)

### 🎲 Produtos
- Cadastro de produtos
- Edição de produtos
- Exclusão de produtos
- Listagem com nome, preço, estoque e imagem

---

## ⚙️ Como Executar Localmente

### 1. Instale um servidor local:
- [XAMPP](https://www.apachefriends.org/index.html)
- ou [WampServer](https://www.wampserver.com/)

### 2. Clone ou baixe este repositório:
```bash
git clone https://github.com/seu-usuario/d20-emporium.git
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
    imagem VARCHAR(255)
);
```

---

## 📂 Estrutura de Pastas (resumo)

```
d20-emporium/
├── conecta.php
├── index.php
├── topo.php
├── rodape.php
├── lista_usuarios.php
├── formusuario.php
├── salvar.php
├── excluir_usuario.php
├── lista_produtos.php
├── formproduto.php
├── salvar_produto.php
├── excluir_produto.php
├── css/
│   └── estilo.css
├── js/
│   └── bootstrap.min.js
```

---

## 📌 Observações

- As senhas dos usuários são salvas com `password_hash()` por segurança.
- O projeto ainda pode ser expandido com:
  - Carrinho de compras
  - Login com sessões
  - Dashboard de administração
  - Filtros e pesquisa

---

## 👥 Créditos

Projeto desenvolvido por:

- Beatriz da Costa Lauro  
- Brenda Bonaita de Oliveira  
- José Rodrigues de França  
- Julia Alves de Brito  

Como parte da disciplina de **Inteligência Artificial** – Curso de **Sistemas de Informação**, 5º período – 2025.