<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include '../db/config.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $usuario_id = $_POST['usuario_id'] ?? 0;
    $manga_id = $_POST['manga_id'] ?? 0;

    if ($usuario_id > 0 && $manga_id > 0) {
        $action = $_GET['action'] ?? '';

        if ($action === 'add') {
            $query = "INSERT INTO favoritos (usuario_id, manga_id) VALUES (:usuario_id, :manga_id)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':manga_id', $manga_id);
            $stmt->execute();

            echo json_encode(["sucesso" => "Mangá adicionado aos favoritos"]);
        } elseif ($action === 'remove') {
            $query = "DELETE FROM favoritos WHERE usuario_id = :usuario_id AND manga_id = :manga_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':manga_id', $manga_id);
            $stmt->execute();

            echo json_encode(["sucesso" => "Mangá removido dos favoritos"]);
        } elseif ($action === 'list') {
            $query = "
                SELECT m.id, m.titulo, m.descricao, m.capa
                FROM favoritos f
                JOIN mangas m ON f.manga_id = m.id
                WHERE f.usuario_id = :usuario_id
            ";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->execute();

            $favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($favoritos);
        } else {
            echo json_encode(["erro" => "Ação inválida"]);
        }
    } else {
        echo json_encode(["erro" => "Parâmetros inválidos"]);
    }
} catch (PDOException $e) {
    echo json_encode(["erro" => "Erro ao conectar ao banco de dados", "detalhes" => $e->getMessage()]);
}
?>
