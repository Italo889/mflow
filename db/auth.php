<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include '../db/config.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $action = $_GET['action'] ?? '';

    if ($action === 'register') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($nome) || empty($email) || empty($senha)) {
            echo json_encode(["erro" => "Preencha todos os campos"]);
            exit;
        }

        $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $hashedPassword);
        $stmt->execute();

        echo json_encode(["sucesso" => "Usuário registrado com sucesso!"]);
    } elseif ($action === 'login') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $query = "SELECT id, senha FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            echo json_encode(["sucesso" => "Login bem-sucedido", "usuario_id" => $user['id']]);
        } else {
            echo json_encode(["erro" => "Email ou senha incorretos"]);
        }
    } else {
        echo json_encode(["erro" => "Ação inválida"]);
    }
} catch (PDOException $e) {
    echo json_encode(["erro" => "Erro ao conectar ao banco de dados", "detalhes" => $e->getMessage()]);
}
?>
