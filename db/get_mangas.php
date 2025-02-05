<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include 'config.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter parâmetros da URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 10;
    $offset = ($page - 1) * $limit;
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

    if ($id > 0) {
        // Consulta para detalhes de um mangá específico
        $query = "
            SELECT 
                m.id, 
                m.titulo, 
                m.descricao, 
                m.capa, 
                m.status, 
                m.data_publicacao,
                (
                    SELECT MAX(c.data_publicacao) 
                    FROM capitulos c 
                    WHERE c.manga_id = m.id
                ) AS ultimo_capitulo,
                (
                    DATEDIFF(NOW(), (
                        SELECT MAX(c.data_publicacao) 
                        FROM capitulos c 
                        WHERE c.manga_id = m.id
                    ))
                ) AS dias_desde_ultimo,
                (
                    SELECT ROUND(AVG(a.nota), 1) 
                    FROM avaliacoes a 
                    WHERE a.manga_id = m.id
                ) AS nota_media
            FROM mangas m
            WHERE m.id = :id
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $manga = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($manga) {
            // Consulta para buscar capítulos do mangá
            $capitulosQuery = "
                SELECT 
                    id, titulo, numero, data_publicacao 
                FROM capitulos 
                WHERE manga_id = :manga_id
                ORDER BY data_publicacao $order
                LIMIT :offset, :limit
            ";

            $capitulosStmt = $pdo->prepare($capitulosQuery);
            $capitulosStmt->bindParam(':manga_id', $id, PDO::PARAM_INT);
            $capitulosStmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $capitulosStmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $capitulosStmt->execute();

            $capitulos = $capitulosStmt->fetchAll(PDO::FETCH_ASSOC);
            $manga['capitulos'] = $capitulos;
        }

        echo json_encode($manga ? $manga : ["erro" => "Mangá não encontrado"]);
    } else {
        // Consulta para listar todos os mangás com paginação
        $query = "
            SELECT 
                m.id, 
                m.titulo, 
                m.descricao, 
                m.capa, 
                m.status, 
                m.data_publicacao,
                (
                    SELECT ROUND(AVG(a.nota), 1) 
                    FROM avaliacoes a 
                    WHERE a.manga_id = m.id
                ) AS nota_media
            FROM mangas m
            LIMIT :offset, :limit
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $mangas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($mangas);
    }
} catch (PDOException $e) {
    echo json_encode(["erro" => "Erro ao conectar ao banco de dados", "detalhes" => $e->getMessage()]);
}
?>
