<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include '../db/config.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $action = $_GET['action'] ?? '';

    if ($action === 'add') {
        // Adicionar avaliação
        $usuario_id = $_POST['usuario_id'] ?? 0;
        $manga_id = $_POST['manga_id'] ?? 0;
        $nota = $_POST['nota'] ?? 0;
        $comentario = $_POST['comentario'] ?? '';

        if ($usuario_id > 0 && $manga_id > 0 && $nota >= 1 && $nota <= 5) {
            $query = "INSERT INTO avaliacoes (usuario_id, manga_id, nota, comentario) VALUES (:usuario_id, :manga_id, :nota, :comentario)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':manga_id', $manga_id);
            $stmt->bindParam(':nota', $nota);
            $stmt->bindParam(':comentario', $comentario);
            $stmt->execute();

            echo json_encode(["sucesso" => "Avaliação adicionada com sucesso"]);
        } else {
            echo json_encode(["erro" => "Dados inválidos"]);
        }
    } elseif ($action === 'list') {
        // Listar avaliações de um mangá específico
        $manga_id = $_GET['manga_id'] ?? 0;
        if ($manga_id > 0) {
            $query = "SELECT u.nome, a.nota, a.comentario, a.criado_em FROM avaliacoes a 
                      JOIN usuarios u ON a.usuario_id = u.id 
                      WHERE a.manga_id = :manga_id
                      ORDER BY a.criado_em DESC";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':manga_id', $manga_id);
            $stmt->execute();
            $avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($avaliacoes);
        } else {
            echo json_encode(["erro" => "Mangá não encontrado"]);
        }
    } elseif ($action === 'media') {
        $cacheKey = "media_manga_" . $manga_id;
        $cacheData = getCache($cacheKey);

        if ($cacheData) {
            echo json_encode($cacheData);
        } else {
            $query = "SELECT ROUND(AVG(nota), 1) AS nota_media FROM avaliacoes WHERE manga_id = :manga_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':manga_id', $manga_id);
            $stmt->execute();
            $media = $stmt->fetch(PDO::FETCH_ASSOC);
    
            setCache($cacheKey, $media);
            echo json_encode($media);
        }

        // Obter a média de avaliações de um mangá
        $manga_id = $_GET['manga_id'] ?? 0;
        if ($manga_id > 0) {
            $query = "SELECT ROUND(AVG(nota), 1) AS nota_media FROM avaliacoes WHERE manga_id = :manga_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':manga_id', $manga_id);
            $stmt->execute();
            $media = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode($media);
        } else {
            echo json_encode(["erro" => "Mangá não encontrado"]);
        }
    } else {
        echo json_encode(["erro" => "Ação inválida"]);
    }
} catch (PDOException $e) {
    echo json_encode(["erro" => "Erro ao conectar ao banco de dados", "detalhes" => $e->getMessage()]);
}
?>
