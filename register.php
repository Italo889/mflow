<?php
session_start();
include 'db/config.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitizarEntrada($_POST['nome']);
    $email = sanitizarEntrada($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validar dados
    if ($senha !== $confirmar_senha) {
        redirecionar('register.php', 'As senhas não coincidem.');
    }

    // Verificar se o e-mail já está cadastrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        redirecionar('register.php', 'Este e-mail já está cadastrado.');
    }

    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // Inserir usuário no banco de dados
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha_hash);

    if ($stmt->execute()) {
        redirecionar('login.php', 'Registro realizado com sucesso!');
    } else {
        redirecionar('register.php', 'Erro ao registrar. Tente novamente.');
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - MangaFlow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilos Globais */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a;
            color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }

        /* Container de Registro */
        .register-container {
            background-color: #111111;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .register-container h1 {
            font-family: 'Bangers', cursive;
            color: #e74c3c;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 12px rgba(231, 76, 60, 0.4);
        }

        .register-container input {
            width: 100%;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.05);
            color: #f5f5f5;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .register-container input:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 8px rgba(231, 76, 60, 0.3);
        }

        .register-container button {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .register-container button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(231, 76, 60, 0.3);
        }

        .register-container p {
            margin-top: 1rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }

        .register-container a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: 600;
        }

        .register-container a:hover {
            text-decoration: underline;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .register-container {
                padding: 2rem;
                max-width: 90%;
            }

            .register-container h1 {
                font-size: 2.2rem;
            }

            .register-container input {
                font-size: 0.95rem;
                padding: 0.7rem;
            }

            .register-container button {
                font-size: 0.95rem;
                padding: 0.7rem;
            }

            .register-container p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 1.5rem;
                max-width: 95%;
            }

            .register-container h1 {
                font-size: 2rem;
            }

            .register-container input {
                font-size: 0.9rem;
                padding: 0.6rem;
            }

            .register-container button {
                font-size: 0.9rem;
                padding: 0.6rem;
            }

            .register-container p {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 320px) {
            .register-container {
                padding: 1rem;
                max-width: 100%;
            }

            .register-container h1 {
                font-size: 1.8rem;
            }

            .register-container input {
                font-size: 0.85rem;
                padding: 0.5rem;
            }

            .register-container button {
                font-size: 0.85rem;
                padding: 0.5rem;
            }

            .register-container p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Registro</h1>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required>
            <button type="submit">Registrar</button>
        </form>
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</body>
</html>