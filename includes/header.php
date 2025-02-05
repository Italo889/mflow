<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>MangaFlow - Header Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="http://localhost/mangaflow/assets/css/header.css">
</head>
<body>
    <header>
        <!-- Hamburger Menu -->
        <div class="hamburger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>

        <!-- Logo -->
        <a href="http://localhost/mangaflow/index.php" class="logo">
            <div class="logo-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <span class="logo-text">MANGA<span>FLOW</span></span>
        </a>

        <!-- Navegação -->
        <div class="nav-container">
            <nav>
                <ul class="nav-center">
                    <li><a href="#" class="active">Mais Lidos</a></li>
                    <li><a href="#">Lançamentos</a></li>
                    <li><a href="#">Rankings</a></li>
                    <li><a href="#">Novidades</a></li>
                </ul>
            </nav>
        </div>

        <!-- Controles -->
        <div class="header-right">
            <div class="search-box">
                <input type="text" placeholder="Buscar mangá...">
                <i class="fas fa-search"></i>
            </div>
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
                <div class="notification-badge"></div>
            </div>
            <a href="http://localhost/mangaflow/login.php" class="login-btn">LOGIN</a>
        </div>

        <!-- Overlay Mobile -->
        <div class="mobile-overlay" onclick="toggleMenu()"></div>
    </header>

    <script>
        function toggleMenu() {
            const navContainer = document.querySelector('.nav-container');
            const overlay = document.querySelector('.mobile-overlay');
            navContainer.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        // Fechar menu ao redimensionar
        window.addEventListener('resize', () => {
            if (window.innerWidth > 480) {
                document.querySelector('.nav-container').classList.remove('active');
                document.querySelector('.mobile-overlay').classList.remove('active');
            }
        });

        document.querySelectorAll('.nav-center a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 1024) {
                    toggleMenu();
                }
            });
        });
    </script>
</body>
</html>