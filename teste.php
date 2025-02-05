<?php
// ==================================================
// Configuração do Banco de Dados
// ==================================================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mangaflow";

// Criar conexão
$conn = new mysqli($servername, $username, $password);

// Criar banco de dados se não existir
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Criar tabelas
$conn->query("
    CREATE TABLE IF NOT EXISTS mangas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        descricao TEXT,
        capa VARCHAR(255),
        data_publicacao DATE,
        adulto TINYINT(1) DEFAULT 0,
        status VARCHAR(50) DEFAULT 'Em andamento'
    )
");

// Inserir dados de exemplo
$result = $conn->query("SELECT COUNT(*) FROM mangas");
if ($result->fetch_row()[0] == 0) {
    $conn->query("
        INSERT INTO mangas (titulo, descricao, capa, data_publicacao, adulto) VALUES
        ('Akira', 'Clássico cyberpunk', 'https://m.media-amazon.com/images/I/81VhR07ZtmL._AC_UF1000,1000_QL80_.jpg', '2023-01-15', 0),
        ('Berserk', 'Fantasia sombria épica', 'https://m.media-amazon.com/images/I/81V7C+9s5kL._AC_UF1000,1000_QL80_.jpg', '2023-02-20', 1),
        ('Dragon Ball', 'Aventura shonen clássica', 'https://m.media-amazon.com/images/I/91Z5SJK15FL._AC_UF1000,1000_QL80_.jpg', '2023-03-10', 0)
    ");
}

// ==================================================
// Lógica da Página
// ==================================================
$adulto = isset($_GET['adulto']) ? (int)$_GET['adulto'] : 0; // Garantir que seja 0 ou 1
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$itensPorPagina = 6;

// Consulta com paginação
$offset = ($pagina - 1) * $itensPorPagina;

// Ajuste na consulta SQL
if ($adulto) {
    // Se adulto = 1, mostrar todos os mangás (incluindo +18)
    $sql = "SELECT * FROM mangas LIMIT $itensPorPagina OFFSET $offset";
} else {
    // Se adulto = 0, mostrar apenas mangás não +18
    $sql = "SELECT * FROM mangas WHERE adulto = 0 LIMIT $itensPorPagina OFFSET $offset";
}

$mangas = $conn->query($sql);

// Total de itens para paginação
if ($adulto) {
    $totalItens = $conn->query("SELECT COUNT(*) FROM mangas")->fetch_row()[0];
} else {
    $totalItens = $conn->query("SELECT COUNT(*) FROM mangas WHERE adulto = 0")->fetch_row()[0];
}
$totalPaginas = ceil($totalItens / $itensPorPagina);

// ==================================================
// Interface
// ==================================================
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangaFlow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos Globais */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0F0F0F;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Bangers', cursive;
            color: #FF4654;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        /* Header */
        header {
            background-color: #1A1A1A;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
            color: #FF4654;
            text-shadow: 0 0 10px rgba(255, 70, 84, 0.5);
        }

        header p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Filtros */
        .filtros {
            text-align: center;
            padding: 1rem;
            background-color: #1A1A1A;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .filtros label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
        }

        .filtros input {
            margin-right: 0.5rem;
        }

        /* Grid de Cards */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Card Individual */
        .card {
            background-color: #1A1A1A;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }

        .card-info {
            padding: 15px;
        }

        .card h3 {
            margin: 0;
            color: #FF4654;
        }

        .card p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9em;
        }

        .card small {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Paginação */
        .paginacao {
            text-align: center;
            padding: 20px;
        }

        .paginacao a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            background-color: #FF4654;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .paginacao a:hover {
            background-color: #e63946;
        }

        .paginacao a[style] {
            background-color: #e63946;
        }

        /* Footer */
        footer {
            background-color: #1A1A1A;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <header>
        <h1>MangaFlow</h1>
        <p>Sua plataforma de leitura de mangás</p>
    </header>

    <div class="filtros">
        <label>
            <input type="checkbox" id="filtroAdulto" <?= $adulto ? 'checked' : '' ?> onchange="filtrarAdulto()">
            Exibir conteúdo +18
        </label>
    </div>

    <div class="grid">
        <?php while($manga = $mangas->fetch_assoc()): ?>
            <div class="card">
                <img src="<?= $manga['capa'] ?>" alt="<?= $manga['titulo'] ?>">
                <div class="card-info">
                    <h3><?= $manga['titulo'] ?></h3>
                    <p><?= substr($manga['descricao'], 0, 100) ?>...</p>
                    <small>Publicado em: <?= date('d/m/Y', strtotime($manga['data_publicacao'])) ?></small>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="paginacao">
        <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?= $i ?>&adulto=<?= $adulto ?>" <?= $i == $pagina ? 'style="background:#e63946"' : '' ?>>
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <footer>
        <p>&copy; 2023 MangaFlow. Todos os direitos reservados.</p>
    </footer>

    <script>
        function filtrarAdulto() {
            const adulto = document.getElementById('filtroAdulto').checked ? 1 : 0;
            const url = new URL(window.location.href);
            url.searchParams.set('adulto', adulto);
            url.searchParams.set('pagina', 1); // Resetar para a primeira página
            window.location.href = url.toString();
        }
    </script>
</body>
</html>