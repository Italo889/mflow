<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberShinobi - Detalhes</title>
    <meta name="description" content="Detalhes do mangá no CyberShinobi. Leia sinopse, capítulos recentes e mais.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/detalhes.css">
    <style>
        /* Estilização do Spinner */
        .spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
        }

        .spinner div {
            width: 30px;
            height: 30px;
            margin: 5px;
            border-radius: 50%;
            background: #3498db;
            animation: bounce 1.5s infinite ease-in-out;
        }

        .spinner div:nth-child(2) {
            animation-delay: 0.3s;
        }

        .spinner div:nth-child(3) {
            animation-delay: 0.6s;
        }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        /* Placeholders de Carregamento */
        .placeholder {
            background: #2A2A2A;
            border-radius: 5px;
            animation: pulse 1.5s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        /* Botão de Voltar ao Topo */
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #FF4654;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .back-to-top.visible {
            opacity: 1;
        }

        /* Mensagem de erro */
        .error-message {
            color: #ff6b6b;
            text-align: center;
            margin: 20px 0;
            font-size: 1.2rem;
        }

        /* Ícone de Favorito */
        .favorite-icon {
            font-size: 24px;
            color: #FF4654;
            cursor: pointer;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .favorite-icon.favorited {
            color: #e63946;
        }

        .favorite-icon:hover {
            opacity: 0.8;
        }

        /* Avaliação com Estrelas */
        .rating-stars {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .rating-stars .star {
            font-size: 24px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .rating-stars .star.active {
            color: #FFD700; /* Cor das estrelas ativas */
        }

        .rating-stars .star:hover {
            color: #FFD700;
        }

        .rating-text {
            margin-left: 10px;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <!-- Header Section -->
        <section class="manga-header">
            <div class="placeholder" style="width: 200px; height: 300px;" id="placeholder-cover"></div>
            <img id="cover-art" src="" alt="Capa" class="cover-art" style="display: none;">
            <div class="basic-info">
                <h1 id="manga-title" class="title placeholder" style="width: 70%; height: 30px;"></h1>
                <div class="rating">
                    <div class="rating-stars" id="rating-stars">
                        <!-- Estrelas de avaliação -->
                        <i class="star" data-value="1"></i>
                        <i class="star" data-value="2"></i>
                        <i class="star" data-value="3"></i>
                        <i class="star" data-value="4"></i>
                        <i class="star" data-value="5"></i>
                    </div>
                    <span id="rating-text" class="rating-text">-/- (0 avaliações)</span>
                </div>
                <!-- Ícone de Favorito -->
                <i class="favorite-icon far fa-heart" id="favorite-icon" data-manga-id="1"></i>
                <div id="meta-info" class="meta-info"></div>
                <div class="status">
                    <h3>Último capítulo:</h3>
                    <p id="last-chapter" class="placeholder" style="width: 50%; height: 20px;"></p>
                </div>
            </div>
        </section>

        <!-- Sinopse -->
        <section class="synopsis">
            <h2>Sinopse</h2>
            <p id="synopsis" class="placeholder" style="width: 100%; height: 100px;"></p>
        </section>

        <!-- Capítulos Recentes -->
        <section class="chapters-section">
            <h2>Capítulos Recentes</h2>
            <div class="spinner" id="spinner-chapters">
                <div></div><div></div><div></div>
            </div>
            <div id="chapters-grid" class="chapters-grid" style="display: none;"></div>
            <div id="error-chapters" class="error-message" style="display: none;"></div>
        </section>

        <!-- Populares -->
        <section class="popular-section">
            <h2>Leitores também viram</h2>
            <div class="spinner" id="spinner-popular">
                <div></div><div></div><div></div>
            </div>
            <div id="popular-grid" class="popular-grid" style="display: none;"></div>
            <div id="error-popular" class="error-message" style="display: none;"></div>
        </section>
    </div>

    <!-- Botão de Voltar ao Topo -->
    <button class="back-to-top" aria-label="Voltar ao topo">↑</button>

    <?php include 'footer.php'; ?>

    <script>
        async function loadMangaDetails() {
            const urlParams = new URLSearchParams(window.location.search);
            const mangaId = urlParams.get('id'); // Pega o ID do manga da URL

            if (!mangaId) {
                document.getElementById('error-chapters').style.display = 'block';
                document.getElementById('error-chapters').textContent = 'ID do mangá não encontrado.';
                return;
            }

            try {
                const response = await fetch(`../db/get_mangas.php?id=${mangaId}`);
                if (!response.ok) throw new Error('Erro ao carregar os dados.');

                const manga = await response.json();
                console.log(manga);

                if (manga.error) {
                    document.getElementById('error-chapters').style.display = 'block';
                    document.getElementById('error-chapters').textContent = manga.error;
                    return;
                }

                // Preenchendo os elementos
                document.getElementById('placeholder-cover').style.display = 'none';
                document.getElementById('cover-art').src = manga.capa;
                document.getElementById('cover-art').style.display = 'block';

                document.getElementById('manga-title').classList.remove('placeholder');
                document.getElementById('manga-title').textContent = manga.titulo;

                document.getElementById('rating-text').textContent = `${manga.rating}/5 (${manga.reviews_count} avaliações)`;
                document.getElementById('last-chapter').classList.remove('placeholder');
                document.getElementById('last-chapter').textContent = `Capítulo ${manga.last_chapter} - ${manga.last_chapter_date}`;
                document.getElementById('synopsis').classList.remove('placeholder');
                document.getElementById('synopsis').textContent = manga.descricao;

                // Preenche os gêneros
                const metaInfo = document.getElementById('meta-info');
                metaInfo.innerHTML = manga.genres.split(',').map(genre => `
                    <span class="badge">${genre.trim()}</span>
                `).join('');

                // Preenche os capítulos recentes
                const chaptersGrid = document.getElementById('chapters-grid');
                chaptersGrid.innerHTML = manga.chapters.map(chapter => `
                    <div class="chapter-card">
                        <div class="chapter-number">Capítulo ${chapter.number}</div>
                        <div class="chapter-date">${chapter.date}</div>
                        <div class="chapter-status">${chapter.status}</div>
                    </div>
                `).join('');
                document.getElementById('spinner-chapters').style.display = 'none';
                chaptersGrid.style.display = 'block';

                // Preenche os itens populares
                const popularGrid = document.getElementById('popular-grid');
                popularGrid.innerHTML = manga.popular.map(item => `
                    <div class="popular-item">
                        <img src="${item.image}" alt="${item.title}" onerror="this.src='../assets/img/placeholder.jpg'">
                        <div class="popular-label">${item.title}</div>
                    </div>
                `).join('');
                document.getElementById('spinner-popular').style.display = 'none';
                popularGrid.style.display = 'block';

            } catch (error) {
                console.error(error);
                document.getElementById('error-chapters').style.display = 'block';
                document.getElementById('error-chapters').textContent = 'Erro ao carregar detalhes do mangá.';
            }
        }

        // Função para gerar estrelas de avaliação
        function generateStars(rating) {
            let stars = '';
            const fullStars = Math.floor(rating);
            const halfStar = rating - fullStars >= 0.5;

            for (let i = 0; i < fullStars; i++) {
                stars += '<i class="fas fa-star"></i>';
            }
            if (halfStar) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            }
            return stars;
        }

        // Botão de Voltar ao Topo
        const backToTopButton = document.querySelector('.back-to-top');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Lógica do Ícone de Favorito
        const favoriteIcon = document.getElementById('favorite-icon');
        favoriteIcon.addEventListener('click', function () {
            const mangaId = this.getAttribute('data-manga-id');

            // Alterna entre favoritado e não favoritado
            this.classList.toggle('favorited');
            this.classList.toggle('fas');
            this.classList.toggle('far');

            // Envia a requisição para favoritar/desfavoritar
            fetch('favoritos.php', {
                method: 'POST',
                body: new URLSearchParams({ manga_id: mangaId }),
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.message);
                }
            });
        });

        // Lógica de Avaliação com Estrelas
        const stars = document.querySelectorAll('.rating-stars .star');
        stars.forEach(star => {
            star.addEventListener('click', function () {
                const value = this.getAttribute('data-value');
                const mangaId = urlParams.get('id'); // Pega o ID do mangá da URL

                // Envia a avaliação para o backend
                fetch('avaliar.php', {
                    method: 'POST',
                    body: new URLSearchParams({ manga_id: mangaId, rating: value }),
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualiza a média e o número de avaliações
                        document.getElementById('rating-text').textContent = `${data.average_rating}/5 (${data.total_reviews} avaliações)`;
                        highlightStars(data.average_rating);
                    } else {
                        alert(data.message);
                    }
                });
            });
        });

        // Função para destacar as estrelas conforme a média
        function highlightStars(averageRating) {
            stars.forEach(star => {
                const value = star.getAttribute('data-value');
                if (value <= averageRating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        // Carrega os detalhes do manga ao carregar a página
        window.onload = loadMangaDetails;
    </script>
</body>
</html>