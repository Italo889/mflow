<?php
session_start();

// Verificar autenticação
/*if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}*/

$baseUrl = "http://localhost/mangaflow/"; // Altere para o caminho correto do seu projeto
$uploadDir = "uploads/"; // Pasta onde as imagens são salvas

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'mangaflow';
$username = 'root';
$password = '';

// Configurações do Cloudinary
$cloudName = 'dpwpaaqeh'; // Substitua pelo seu Cloud Name
$apiKey = '995963559265995'; // Substitua pela sua API Key
$uploadPreset = 'ml_default'; // Substitua pelo seu Upload Preset

// Conexão com o banco de dados usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Gerar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Função para upload no Cloudinary
function uploadParaCloudinary($filePath, $cloudName, $apiKey, $uploadPreset) {
    $url = "https://api.cloudinary.com/v1_1/$cloudName/image/upload";
    $data = [
        'file' => new CURLFile($filePath),
        'upload_preset' => $uploadPreset,
        'api_key' => $apiKey,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['secure_url'])) {
        return $result['secure_url']; // Retorna a URL pública da imagem
    } else {
        die("Erro no upload para o Cloudinary: " . $response);
    }
}

// Funções do Sistema
function listarMangas($pdo, $ordenarPor = 'titulo', $ordem = 'ASC') {
    $ordenacoesPermitidas = ['titulo', 'data_publicacao', 'avaliacao'];
    $ordemPermitida = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';

    if (in_array($ordenarPor, $ordenacoesPermitidas)) {
        $sql = "SELECT * FROM mangas ORDER BY $ordenarPor $ordemPermitida";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return [];
}

function pesquisarMangas($pdo, $termo, $status = null, $tipo = null, $avaliacao = null, $adulto = null) {
    $sql = "SELECT * FROM mangas WHERE titulo LIKE :termo OR descricao LIKE :termo";
    $params = [':termo' => "%$termo%"];

    if ($status) {
        $sql .= " AND status = :status";
        $params[':status'] = $status;
    }
    if ($tipo) {
        $sql .= " AND tipo = :tipo";
        $params[':tipo'] = $tipo;
    }
    if ($avaliacao) {
        $sql .= " AND avaliacao = :avaliacao";
        $params[':avaliacao'] = $avaliacao;
    }
    if ($adulto !== null) {
        $sql .= " AND adulto = :adulto";
        $params[':adulto'] = $adulto;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obterManga($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM mangas WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function adicionarManga($pdo, $titulo, $descricao, $capa, $data_publicacao, $adulto, $status, $tipo) {
    $sql = "INSERT INTO mangas (titulo, descricao, capa, data_publicacao, adulto, status, tipo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $descricao, $capa, $data_publicacao, $adulto, $status, $tipo]);
}

function editarManga($pdo, $id, $titulo, $descricao, $capa, $data_publicacao, $adulto, $status, $tipo) {
    $sql = "UPDATE mangas SET titulo = ?, descricao = ?, capa = ?, data_publicacao = ?, adulto = ?, status = ?, tipo = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $descricao, $capa, $data_publicacao, $adulto, $status, $tipo, $id]);
}

function excluirManga($pdo, $id) {
    $sql = "DELETE FROM mangas WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

function registrarLog($pdo, $acao, $detalhes) {
    $sql = "INSERT INTO logs (acao, detalhes) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$acao, $detalhes]);
}

function totalMangas($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM mangas");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

function mangasRecentes($pdo, $limite = 5) {
    $stmt = $pdo->query("SELECT * FROM mangas ORDER BY data_publicacao DESC LIMIT $limite");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Processar ações
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        // Validação CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Token CSRF inválido.");
        }

        // Upload da capa para o Cloudinary
        $capa = '';
        if (isset($_FILES['capa']) && $_FILES['capa']['error'] == 0) {
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $_FILES['capa']['tmp_name']);
            finfo_close($fileInfo);

            if (strpos($mimeType, 'image/') === 0) {
                $capa = uploadParaCloudinary($_FILES['capa']['tmp_name'], $cloudName, $apiKey, $uploadPreset);
            } else {
                die("O arquivo enviado não é uma imagem válida.");
            }
        }

        adicionarManga($pdo, $_POST['titulo'], $_POST['descricao'], $capa, $_POST['data_publicacao'], $_POST['adulto'] ?? 0, $_POST['status'], $_POST['tipo']);
        registrarLog($pdo, 'Adicionar Mangá', "Mangá '{$_POST['titulo']}' adicionado.");
        header("Location: ?mensagem=Mangá adicionado com sucesso!");
        exit();
    } elseif ($action == 'edit' && isset($_GET['id'])) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validação CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("Token CSRF inválido.");
            }

            // Upload da capa para o Cloudinary
            $capa = $_POST['capa_existente'];
            if (isset($_FILES['capa']) && $_FILES['capa']['error'] == 0) {
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($fileInfo, $_FILES['capa']['tmp_name']);
                finfo_close($fileInfo);

                if (strpos($mimeType, 'image/') === 0) {
                    $capa = uploadParaCloudinary($_FILES['capa']['tmp_name'], $cloudName, $apiKey, $uploadPreset);
                } else {
                    die("O arquivo enviado não é uma imagem válida.");
                }
            }

            editarManga($pdo, $_GET['id'], $_POST['titulo'], $_POST['descricao'], $capa, $_POST['data_publicacao'], $_POST['adulto'] ?? 0, $_POST['status'], $_POST['tipo']);
            registrarLog($pdo, 'Editar Mangá', "Mangá ID {$_GET['id']} editado.");
            header("Location: ?mensagem=Mangá atualizado com sucesso!");
            exit();
        }
    } elseif ($action == 'delete' && isset($_GET['id'])) {
        excluirManga($pdo, $_GET['id']);
        registrarLog($pdo, 'Excluir Mangá', "Mangá ID {$_GET['id']} excluído.");
        header("Location: ?mensagem=Mangá excluído com sucesso!");
        exit();
    } elseif ($action == 'backup') {
        $backupFile = 'backup/mangas_' . date('Y-m-d_H-i-s') . '.sql';
        exec("mysqldump -u $username -p$password $dbname mangas > $backupFile");
        registrarLog($pdo, 'Backup', "Backup do banco de dados realizado.");
        header("Location: ?mensagem=Backup realizado com sucesso!");
        exit();
    }
}

// Processar pesquisa
$termoPesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';
$statusPesquisa = isset($_GET['status']) ? $_GET['status'] : null;
$tipoPesquisa = isset($_GET['tipo']) ? $_GET['tipo'] : null;
$avaliacaoPesquisa = isset($_GET['avaliacao']) ? $_GET['avaliacao'] : null;
$adultoPesquisa = isset($_GET['adulto']) ? $_GET['adulto'] : null;
$mangas = $termoPesquisa ? pesquisarMangas($pdo, $termoPesquisa, $statusPesquisa, $tipoPesquisa, $avaliacaoPesquisa, $adultoPesquisa) : listarMangas($pdo);

// Mensagem de feedback
$mensagem = isset($_GET['mensagem']) ? htmlspecialchars($_GET['mensagem']) : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Mangás</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .card {
            background: #1F2937;
            border-radius: 12px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: #3B82F6;
            color: white;
            transition: background 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background: #2563EB;
        }
        .btn-danger {
            background: #EF4444;
            color: white;
            transition: background 0.2s ease-in-out;
        }
        .btn-danger:hover {
            background: #DC2626;
        }
        .form-input {
            background: #374151;
            border: 1px solid #4B5563;
            color: white;
            border-radius: 8px;
            padding: 10px;
            transition: border-color 0.2s ease-in-out;
        }
        .form-input:focus {
            border-color: #3B82F6;
            outline: none;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <div class="container mx-auto p-6">
        <!-- Cabeçalho -->
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold text-blue-500">Gerenciador de Mangás</h1>
            <p class="text-gray-400 mt-2">Gerencie sua coleção de mangás de forma simples e eficiente.</p>
        </header>

        <!-- Mensagem de feedback -->
        <?php if ($mensagem): ?>
            <div class="bg-green-900 border border-green-400 text-green-300 px-4 py-3 rounded mb-4">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <!-- Barra de pesquisa e filtros -->
        <form method="get" action="" class="mb-6">
            <input type="text" name="pesquisa" placeholder="Pesquisar mangás..." value="<?= htmlspecialchars($termoPesquisa) ?>" class="form-input w-full">
            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <select name="status" class="form-input">
                    <option value="">Todos os status</option>
                    <option value="Em andamento" <?= $statusPesquisa == 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
                    <option value="Concluído" <?= $statusPesquisa == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                </select>
                <select name="tipo" class="form-input">
                    <option value="">Todos os tipos</option>
                    <option value="Mangá" <?= $tipoPesquisa == 'Mangá' ? 'selected' : '' ?>>Mangá</option>
                    <option value="Manhwa" <?= $tipoPesquisa == 'Manhwa' ? 'selected' : '' ?>>Manhwa</option>
                    <option value="Manhua" <?= $tipoPesquisa == 'Manhua' ? 'selected' : '' ?>>Manhua</option>
                </select>
                <select name="adulto" class="form-input">
                    <option value="">Todos os conteúdos</option>
                    <option value="1" <?= $adultoPesquisa == 1 ? 'selected' : '' ?>>Adulto</option>
                    <option value="0" <?= $adultoPesquisa === '0' ? 'selected' : '' ?>>Não Adulto</option>
                </select>
            </div>
            <button type="submit" class="btn-primary mt-2 px-4 py-2 rounded"><i class="fas fa-search"></i> Pesquisar</button>
        </form>

        <!-- Estatísticas -->
        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-400">Estatísticas</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card p-6">
                    <h3 class="text-xl font-bold text-blue-300">Total de Mangás</h3>
                    <p class="text-gray-400 mt-2"><?= totalMangas($pdo) ?></p>
                </div>
                <div class="card p-6">
                    <h3 class="text-xl font-bold text-blue-300">Mangás Recentes</h3>
                    <ul class="text-gray-400 mt-2">
                        <?php foreach (mangasRecentes($pdo) as $manga): ?>
                            <li><?= htmlspecialchars($manga['titulo']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="card p-6">
                    <h3 class="text-xl font-bold text-blue-300">Backup</h3>
                    <a href="?action=backup" class="btn-primary mt-2 px-4 py-2 rounded"><i class="fas fa-download"></i> Fazer Backup</a>
                </div>
            </div>
        </section>

        <!-- Lista de Mangás -->
        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-400">Lista de Mangás</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($mangas as $manga): ?>
                    <div class="card p-6">
                        <h3 class="text-xl font-bold text-blue-300"><?= htmlspecialchars($manga['titulo']) ?></h3>
                        <p class="text-gray-400 mt-2"><?= htmlspecialchars($manga['descricao']) ?></p>
                        <div class="mt-4 flex space-x-2">
                            <a href="?action=edit&id=<?= $manga['id'] ?>" class="btn-primary px-4 py-2 rounded" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="?action=delete&id=<?= $manga['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')" class="btn-danger px-4 py-2 rounded" title="Excluir"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Formulário para Adicionar Mangá -->
        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-400">Adicionar Mangá</h2>
            <form method="post" action="?action=add" class="card p-6" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="mb-4">
                    <label class="block text-gray-400">Título</label>
                    <input type="text" name="titulo" placeholder="Título" required class="form-input w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Descrição</label>
                    <textarea name="descricao" placeholder="Descrição" class="form-input w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Capa</label>
                    <input type="file" name="capa" accept="image/*" class="form-input w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Data de Publicação</label>
                    <input type="date" name="data_publicacao" class="form-input w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Adulto</label>
                    <input type="checkbox" name="adulto" value="1" class="mr-2"> Marque se for conteúdo adulto
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Status</label>
                    <input type="text" name="status" placeholder="Status" class="form-input w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Tipo</label>
                    <select name="tipo" class="form-input w-full">
                        <option value="Mangá">Mangá</option>
                        <option value="Manhwa">Manhwa</option>
                        <option value="Manhua">Manhua</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary px-4 py-2 rounded"><i class="fas fa-plus"></i> Adicionar Mangá</button>
            </form>
        </section>

        <!-- Formulário para Editar Mangá -->
        <?php if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])): 
            $manga = obterManga($pdo, $_GET['id']);
        ?>
        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-400">Editar Mangá</h2>
            <form method="post" action="?action=edit&id=<?= $manga['id'] ?>" class="card p-6" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="capa_existente" value="<?= $manga['capa'] ?>">
                <div class="mb-4">
                    <label class="block text-gray-400">Título</label>
                    <input type="text" name="titulo" value="<?= htmlspecialchars($manga['titulo']) ?>" required class="form-input w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Descrição</label>
                    <textarea name="descricao" class="form-input w-full"><?= htmlspecialchars($manga['descricao']) ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Capa</label>
                    <input type="file" name="capa" accept="image/*" class="form-input w-full">
                    <?php if ($manga['capa']): ?>
                        <img src="<?= $manga['capa'] ?>" alt="Capa do mangá" class="mt-2 w-32 h-32 object-cover rounded">
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Data de Publicação</label>
                    <input type="date" name="data_publicacao" value="<?= $manga['data_publicacao'] ?>" class="form-input w-full">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Adulto</label>
                    <input type="checkbox" name="adulto" value="1" <?= $manga['adulto'] ? 'checked' : '' ?> class="mr-2"> Marque se for conteúdo adulto
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Status</label>
                    <select name="status" class="form-input w-full">
                        <option value="Em andamento" <?= isset($manga['status']) && $manga['status'] == 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
                        <option value="Concluído" <?= isset($manga['status']) && $manga['status'] == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                        <option value="Pausado" <?= isset($manga['status']) && $manga['status'] == 'Pausado' ? 'selected' : '' ?>>Pausado</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400">Tipo</label>
                    <select name="tipo" class="form-input w-full">
                        <option value="Mangá" <?= $manga['tipo'] == 'Mangá' ? 'selected' : '' ?>>Mangá</option>
                        <option value="Manhwa" <?= $manga['tipo'] == 'Manhwa' ? 'selected' : '' ?>>Manhwa</option>
                        <option value="Manhua" <?= $manga['tipo'] == 'Manhua' ? 'selected' : '' ?>>Manhua</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary px-4 py-2 rounded"><i class="fas fa-save"></i> Salvar Alterações</button>
            </form>
        </section>
        <?php endif; ?>
    </div>
</body>
</html>