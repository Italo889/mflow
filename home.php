<?php
    $baseUrl = "http://localhost/mangaflow/"; // Altere para o caminho correto do seu projeto
    $uploadDir = "uploads/"; // Pasta onde as imagens são salvas
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangaFlow - Página Inicial</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Estilos globais */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0F0F0F;
            color: rgba(255, 255, 255, 0.8);
        }

        h1 {
            font-family: 'Bangers', cursive;
            color: #FF4654;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Cards de mangás */
        .manga-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background-color: #1A1A1A;
        }

        .manga-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 20px rgba(255, 70, 84, 0.25);
        }

        .manga-card img {
            height: 280px;
            object-fit: cover;
        }

        .manga-card .card-body {
            padding: 15px;
            text-align: center;
        }

        .manga-card .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: white;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .manga-card .card-genres {
            font-size: 0.85rem;
            color: #FF4654;
            text-align: center;
            margin-top: 10px;
        }

        /* Botões de navegação */
        .nav-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: white;
        }

        .nav-section a {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.2rem;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .nav-section a:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <!-- Título -->
        <h1>📚 Mangás Disponíveis</h1>

        <!-- Navegação -->
        <div class="nav-section">
            <span>Featured</span>
            <a href="#">Seasonal: Fall 2025 ➡</a>
        </div>

        <!-- Cards de Mangás -->
        <div class="row" id="mangaList">
            <!-- Template de um card -->
            <?php foreach ($mangas as $manga): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <a href="includes/detalhes.php?id=<?= $manga['id'] ?>" class="text-decoration-none">
                        <div class="card manga-card">
                            <?php if ($manga['capa']): ?>
                                <img src="<?= $baseUrl . $uploadDir . $manga['capa'] ?>" alt="Capa do mangá" class="card-img-top">
                            <?php else: ?>
                                <img src="<?= $baseUrl . $uploadDir . 'default.jpg' ?>" alt="Capa padrão" class="card-img-top">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= $manga['titulo'] ?></h5>
                                <div class="card-genres"><?= $manga['generos'] ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para popular cards -->
    <script>
        async function loadMangas() {
            const mangaList = document.getElementById("mangaList");

            try {
                const response = await fetch("db/get_mangas.php");
                if (!response.ok) throw new Error("Erro ao carregar mangás");

                const data = await response.json();
                mangaList.innerHTML = ""; // Limpa a lista atual

                data.forEach(manga => {
                    const card = `
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <a href="includes/detalhes.php?id=${manga.id}" class="text-decoration-none">
                                <div class="card manga-card">
                                    <img src="${manga.capa}" class="card-img-top" alt="${manga.titulo}">
                                    <div class="card-body">
                                        <h5 class="card-title">${manga.titulo}</h5>
                                        <div class="card-genres">${manga.generos}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    `;
                    mangaList.innerHTML += card;
                });

            } catch (error) {
                mangaList.innerHTML = '<div class="alert alert-danger text-center">Erro ao carregar mangás.</div>';
            }
        }

        loadMangas();
    </script>

</body>

</html>