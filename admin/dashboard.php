<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MangaFlow</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Estilos Personalizados -->
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="mb-4">📖 MangaFlow</h4>
        <hr>
        <a href="#" class="sidebar-link"><i class="bi bi-house-door"></i> Início</a>
        <a href="#" class="sidebar-link mt-2"><i class="bi bi-bar-chart"></i> Estatísticas</a>
        <a href="#" class="sidebar-link mt-2"><i class="bi bi-book"></i> Gerenciar Mangás</a>
        <hr>
        <div class="mt-3">
            <button id="toggleDarkMode" class="btn btn-warning w-100">
                <span class="mode-icon">🌙</span> Modo Escuro
            </button>
        </div>
    </div>

    <!-- Conteúdo -->
    <div class="content">
        <h2 class="mb-4">📊 Dashboard</h2>

        <!-- Cards de Estatísticas -->
        <div class="row gy-4">
            <div class="col-md-3 col-6">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-book"></i> Total de Mangás</h5>
                        <p class="card-text stat-number" id="totalMangas">
                            <span class="placeholder col-4"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-list-ol"></i> Total de Capítulos</h5>
                        <p class="card-text stat-number" id="totalCapitulos">
                            <span class="placeholder col-4"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-people"></i> Total de Usuários</h5>
                        <p class="card-text stat-number" id="totalUsuarios">
                            <span class="placeholder col-4"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-fire"></i> Mangá Mais Popular</h5>
                        <p class="card-text stat-number" id="mangaPopular">
                            <span class="placeholder col-8"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row mt-5 gy-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="mb-3">Capítulos por Mangá</h5>
                    <div class="chart-container">
                        <canvas id="chartCapitulos"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="mb-3">Distribuição de Gêneros</h5>
                    <div class="chart-container">
                        <canvas id="chartGeneros"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atividades Recentes -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card p-3">
                    <h5 class="mb-3">Últimas Atividades</h5>
                    <div id="recentActivities" class="list-group">
                        <!-- Atividades serão carregadas via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div class="mobile-menu d-md-none">
        <button class="btn btn-dark" id="mobileMenuButton">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <script>
        // Dark Mode
        const toggleDarkMode = () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark);
            document.querySelector('.mode-icon').textContent = isDark ? '☀️' : '🌙';
        };

        // Verificar preferência salva
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
            document.querySelector('.mode-icon').textContent = '☀️';
        }

        document.getElementById('toggleDarkMode').addEventListener('click', toggleDarkMode);

        // Menu Mobile
        document.getElementById('mobileMenuButton').addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Carregar dados
        document.addEventListener("DOMContentLoaded", function() {
            fetch("dashboard_data.php")
                .then(response => response.json())
                .then(data => {
                    document.getElementById("totalMangas").textContent = data.totalMangas;
                    document.getElementById("totalCapitulos").textContent = data.totalCapitulos;
                    document.getElementById("totalUsuarios").textContent = data.totalUsuarios;
                    document.getElementById("mangaPopular").textContent = data.mangaPopular;

                    // Gráficos (mesmo código anterior)
                })
                .catch(error => {
                    console.error("Erro:", error);
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger mt-3';
                    alert.textContent = 'Erro ao carregar dados. Tente recarregar a página.';
                    document.querySelector('.content').prepend(alert);
                });
        });
    </script>
</body>
</html>