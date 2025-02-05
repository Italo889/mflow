<?php
session_start();
include 'db/config.php';
include 'includes/functions.php';

$erro = ''; // Variável para armazenar mensagens de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT id, nome, senha, role FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        $_SESSION['user_role'] = $user['role'];

        echo json_encode(["success" => true, "role" => $user['role']]);
    } else {
        echo json_encode(["success" => false, "message" => "Credenciais inválidas"]);
    }
}

if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'postador'])) {
    header("Location: index.php"); // Redireciona usuários não autorizados
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $capitulo_id = $_POST['capitulo_id'];
    $comentario = $_POST['comentario'];
    $usuario_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO comentarios (usuario_id, capitulo_id, comentario) VALUES (:usuario_id, :capitulo_id, :comentario)");
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':capitulo_id', $capitulo_id);
    $stmt->bindParam(':comentario', $comentario);
    $stmt->execute();

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Acesso negado"]);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MangaFlow</title>
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

        /* Container de Login */
        .login-container {
            background-color: #111111;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-container h1 {
            font-family: 'Bangers', cursive;
            color: #e74c3c;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 12px rgba(231, 76, 60, 0.4);
        }

        .login-container input {
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

        .login-container input:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 8px rgba(231, 76, 60, 0.3);
        }

        .login-container button {
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

        .login-container button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(231, 76, 60, 0.3);
        }

        .login-container p {
            margin-top: 1rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }

        .login-container a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: 600;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .erro {
            color: #e74c3c;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .login-container {
                padding: 2rem;
                max-width: 90%;
            }

            .login-container h1 {
                font-size: 2.2rem;
            }

            .login-container input {
                font-size: 0.95rem;
                padding: 0.7rem;
            }

            .login-container button {
                font-size: 0.95rem;
                padding: 0.7rem;
            }

            .login-container p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                max-width: 95%;
            }

            .login-container h1 {
                font-size: 2rem;
            }

            .login-container input {
                font-size: 0.9rem;
                padding: 0.6rem;
            }

            .login-container button {
                font-size: 0.9rem;
                padding: 0.6rem;
            }

            .login-container p {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 320px) {
            .login-container {
                padding: 1rem;
                max-width: 100%;
            }

            .login-container h1 {
                font-size: 1.8rem;
            }

            .login-container input {
                font-size: 0.85rem;
                padding: 0.5rem;
            }

            .login-container button {
                font-size: 0.85rem;
                padding: 0.5rem;
            }

            .login-container p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (!empty($erro)): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>
        <form method="POST" onsubmit="return validarFormulario()">
            <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
            <input type="email" name="email" id="email" placeholder="E-mail" required>
            <input type="password" name="senha" id="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="register.php">Registre-se</a></p>
    </div>

    <script>
        function validarFormulario() {
            const email = document.getElementById('email').value;
            const senha = document.getElementById('senha').value;

            // Validação de e-mail
            if (!email || !email.includes('@')) {
                alert('Por favor, insira um e-mail válido.');
                return false;
            }

            // Validação de senha
            if (senha.length < 6) {
                alert('A senha deve ter pelo menos 6 caracteres.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>