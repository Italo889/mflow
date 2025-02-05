<?php
header('Content-Type: application/json');

class DashboardData {
    private $conn;
    private $cacheDir = __DIR__ . '/cache/';
    private $cacheTime = 300; // 5 minutos

    public function __construct() {
        // Conexão com o banco de dados
        $this->conn = new mysqli("localhost", "root", "", "mangaflow");
        
        if ($this->conn->connect_error) {
            $this->sendError("Erro na conexão com o banco de dados.");
        }
        
        // Criar diretório de cache, se não existir
        if (!file_exists($this->cacheDir)) {
            if (!mkdir($this->cacheDir, 0755, true)) {
                $this->sendError("Erro ao criar diretório de cache.");
            }
        }
    }

    public function getData() {
        try {
            // Validar parâmetros da requisição
            $params = $this->validateParams();

            // Verificar se os dados estão em cache
            $data = $this->getCachedData('main_data');
            if (!$data) {
                // Buscar dados do banco
                $data = [
                    'totalMangas' => $this->getTotalMangas(),
                    'totalCapitulos' => $this->getTotalCapitulos(),
                    'totalUsuarios' => $this->getTotalUsuarios(),
                    'mangaPopular' => $this->getMangaPopular(),
                    'capitulosPorManga' => $this->getCapitulosPorManga($params['page'], $params['per_page']),
                    'generosPopulares' => $this->getGenerosPopulares(),
                    'atividadesRecentes' => $this->getAtividadesRecentes()
                ];

                // Salvar dados em cache
                $this->saveCache('main_data', $data);
            }

            // Retornar dados em formato JSON
            echo json_encode($data);
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    private function getTotalMangas() {
        return $this->querySingle("SELECT COUNT(*) AS total FROM mangas");
    }

    private function getTotalCapitulos() {
        return $this->querySingle("SELECT COUNT(*) AS total FROM capitulos");
    }

    private function getTotalUsuarios() {
        return $this->querySingle("SELECT COUNT(*) AS total FROM usuarios");
    }

    private function getMangaPopular() {
        $sql = "SELECT titulo FROM mangas ORDER BY visualizacoes DESC LIMIT 1";
        $result = $this->querySingle($sql);
    
        // Debug: Verificar o que está sendo retornado
        if ($result === null) {
            error_log("Nenhum mangá encontrado ou coluna 'visualizacoes' não está populada.");
        } else {
            error_log("Mangá popular encontrado: " . print_r($result, true));
        }
    
        return $result ? $result['titulo'] : "Nenhum";
    }

    private function getCapitulosPorManga($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT mangas.titulo, COUNT(capitulos.id) AS total 
                FROM capitulos 
                JOIN mangas ON capitulos.manga_id = mangas.id 
                GROUP BY mangas.titulo
                LIMIT ? OFFSET ?";
        return $this->query($sql, [$perPage, $offset]);
    }

    private function getGenerosPopulares() {
        return $this->query("SELECT generos.nome, COUNT(mangas_generos.manga_id) AS total 
                           FROM mangas_generos 
                           JOIN generos ON mangas_generos.genero_id = generos.id 
                           GROUP BY generos.nome");
    }

    private function getAtividadesRecentes() {
        return $this->query("SELECT a.tipo, a.descricao, a.data, m.titulo 
                           FROM atividades a
                           LEFT JOIN mangas m ON a.manga_id = m.id
                           ORDER BY a.data DESC 
                           LIMIT 5");
    }

    private function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta: " . $this->conn->error);
        }

        if ($params) {
            $types = str_repeat('i', count($params));
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function querySingle($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            throw new Exception("Erro ao executar a consulta: " . $this->conn->error);
        }
        return $result->fetch_assoc()['total'] ?? null;
    }

    private function validateParams() {
        return [
            'page' => max(1, intval($_GET['page'] ?? 1)),
            'per_page' => max(5, min(50, intval($_GET['per_page'] ?? 10)))
        ];
    }

    private function getCachedData($key) {
        $cacheFile = $this->cacheDir . $key . '.json';
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $this->cacheTime) {
            return json_decode(file_get_contents($cacheFile), true);
        }
        return null;
    }

    private function saveCache($key, $data) {
        $cacheFile = $this->cacheDir . $key . '.json';
        if (!file_put_contents($cacheFile, json_encode($data))) {
            throw new Exception("Erro ao salvar cache.");
        }
    }

    private function sendError($message) {
        http_response_code(500);
        die(json_encode(['error' => $message]));
    }

    public function __destruct() {
        $this->conn->close();
    }
}

// Executar a classe
(new DashboardData())->getData();