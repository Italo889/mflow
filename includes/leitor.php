<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitor de Mangá - Navegação Integrada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #e63946;
            --dark: #1a1a1a;
            --light: #e0e0e0;
            --secondary: #2d2d2d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: var(--dark);
            color: var(--light);
            line-height: 1.6;
        }

        .reader-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Navegação Superior */
        .chapter-navigation {
            position: sticky;
            top: 0;
            background: var(--dark);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            border-bottom: 2px solid var(--primary);
        }

        .chapter-selector {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .chapter-dropdown {
            position: relative;
        }

        .chapter-dropdown-btn {
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chapter-list {
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--secondary);
            border-radius: 8px;
            padding: 0.5rem;
            max-height: 300px;
            overflow-y: auto;
            width: 200px;
            display: none;
        }

        .chapter-list.show {
            display: block;
        }

        .chapter-item {
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .chapter-item:hover {
            background: var(--primary);
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .nav-button {
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .nav-button:hover {
            transform: translateY(-2px);
        }

        /* Conteúdo do Capítulo */
        .manga-content {
            margin: 2rem 0;
        }

        .manga-page {
            max-width: 800px;
            margin: 0 auto 2rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .manga-page img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Navegação Inferior */
        .bottom-navigation {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: var(--dark);
            padding: 1rem;
            display: flex;
            justify-content: center;
            gap: 2rem;
            border-top: 2px solid var(--primary);
            z-index: 100;
        }

        @media (max-width: 768px) {
            .chapter-navigation {
                flex-direction: column;
                gap: 1rem;
                padding: 0.5rem;
            }

            .nav-button {
                padding: 0.6rem 1rem;
            }

            .chapter-dropdown-btn {
                padding: 0.6rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="reader-container">
        <!-- Navegação Superior -->
        <div class="chapter-navigation">
            <div class="chapter-selector">
                <div class="chapter-dropdown">
                    <button class="chapter-dropdown-btn">
                        Capítulo 204
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="chapter-list">
                        <div class="chapter-item" data-chapter="203">Capítulo 203</div>
                        <div class="chapter-item" data-chapter="204" class="active">Capítulo 204</div>
                        <div class="chapter-item" data-chapter="205">Capítulo 205</div>
                        <!-- Adicionar mais capítulos -->
                    </div>
                </div>
                <span>de 350</span>
            </div>

            <div class="nav-buttons">
                <button class="nav-button" onclick="previousChapter()">
                    <i class="fas fa-chevron-left"></i> Anterior
                </button>
                <button class="nav-button" onclick="nextChapter()">
                    Próximo <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Conteúdo do Capítulo -->
        <div class="manga-content">
            <div class="manga-page">
                <img src="../academys_undercover_professor_0_2.jpg" alt="Página 1">
            </div>
            <!-- Adicionar mais páginas -->
        </div>

        <!-- Navegação Inferior -->
        <div class="bottom-navigation">
            <button class="nav-button" onclick="previousChapter()">
                <i class="fas fa-chevron-left"></i> Anterior
            </button>
            <button class="nav-button" onclick="nextChapter()">
                Próximo <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <script>
        // Dados dos Capítulos (Simulação)
        const chapters = [
            { id: 203, title: "O Confronto Final" },
            { id: 204, title: "Revelações" },
            { id: 205, title: "Novos Horizontes" }
        ];

        let currentChapter = 204;

        // Elementos do DOM
        const chapterDropdown = document.querySelector('.chapter-dropdown');
        const chapterList = document.querySelector('.chapter-list');
        const chapterBtn = document.querySelector('.chapter-dropdown-btn');

        // Mostrar/Esconder lista de capítulos
        chapterBtn.addEventListener('click', () => {
            chapterList.classList.toggle('show');
        });

        // Selecionar Capítulo
        document.querySelectorAll('.chapter-item').forEach(item => {
            item.addEventListener('click', () => {
                const chapterId = item.dataset.chapter;
                loadChapter(chapterId);
                chapterList.classList.remove('show');
            });
        });

        // Fechar lista ao clicar fora
        document.addEventListener('click', (e) => {
            if (!chapterDropdown.contains(e.target)) {
                chapterList.classList.remove('show');
            }
        });

        // Navegação entre Capítulos
        function previousChapter() {
            const prevChapter = chapters.find(ch => ch.id === currentChapter - 1);
            if (prevChapter) loadChapter(prevChapter.id);
        }

        function nextChapter() {
            const nextChapter = chapters.find(ch => ch.id === currentChapter + 1);
            if (nextChapter) loadChapter(nextChapter.id);
        }

        // Carregar Capítulo
        function loadChapter(chapterId) {
            currentChapter = chapterId;
            const chapter = chapters.find(ch => ch.id === chapterId);
            
            // Atualizar UI
            chapterBtn.innerHTML = `Capítulo ${chapterId} <i class="fas fa-chevron-down"></i>`;
            
            // Atualizar conteúdo (simulação)
            console.log(`Carregando capítulo ${chapterId}: ${chapter.title}`);
            // Implementar lógica de carregamento real aqui
        }

        // Inicialização
        loadChapter(currentChapter);
    </script>
</body>
</html>