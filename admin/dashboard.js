class Dashboard {
    constructor() {
        this.currentPage = 1;
        this.perPage = 10;
        this.init();
    }

    init() {
        this.initDarkMode();
        this.initMenu();
        this.loadData();
        this.initExport();
        this.initAutoRefresh();
    }

    initDarkMode() {
        this.toggleDarkMode = () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark);
            document.querySelector('.mode-icon').textContent = isDark ? '☀️' : '🌙';
        };

        if(localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
            document.querySelector('.mode-icon').textContent = '☀️';
        }

        document.getElementById('toggleDarkMode').addEventListener('click', this.toggleDarkMode);
    }

    initMenu() {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const sidebar = document.querySelector('.sidebar');

        mobileMenuButton.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        window.addEventListener('resize', () => {
            if(window.innerWidth > 768) {
                sidebar.classList.remove('active');
            }
        });
    }

    async loadData() {
        try {
            this.showLoading();
            
            const response = await fetch(`dashboard_data.php?page=${this.currentPage}&per_page=${this.perPage}`);
            if(!response.ok) throw new Error('Erro na rede');
            
            const data = await response.json();
            
            this.updateStats(data);
            this.createCharts(data);
            this.updateActivities(data.atividadesRecentes);
            this.updatePagination(data.capitulosPorManga.length);
        } catch (error) {
            this.showError(error);
        }
    }

    showLoading() {
        document.querySelectorAll('.stat-number').forEach(el => {
            el.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
        });
    }

    updateStats(data) {
        document.getElementById("totalMangas").textContent = data.totalMangas;
        document.getElementById("totalCapitulos").textContent = data.totalCapitulos;
        document.getElementById("totalUsuarios").textContent = data.totalUsuarios;
        document.getElementById("mangaPopular").textContent = data.mangaPopular;
    }

    createCharts(data) {
        this.createBarChart(data.capitulosPorManga);
        this.createDoughnutChart(data.generosPopulares);
    }

    createBarChart(data) {
        const ctx = document.getElementById("chartCapitulos").getContext("2d");
        if(this.barChart) this.barChart.destroy();
        
        this.barChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: data.map(m => m.titulo),
                datasets: [{
                    label: "Capítulos",
                    data: data.map(m => m.total),
                    backgroundColor: "#4e73df",
                    borderColor: "#2e59d9",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Capítulos por Mangá' },
                    zoom: {
                        zoom: {
                            wheel: { enabled: true },
                            pinch: { enabled: true },
                            mode: 'x'
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    createDoughnutChart(data) {
        const ctx = document.getElementById("chartGeneros").getContext("2d");
        if(this.doughnutChart) this.doughnutChart.destroy();
        
        this.doughnutChart = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: data.map(g => g.nome),
                datasets: [{
                    data: data.map(g => g.total),
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc',
                        '#f6c23e', '#e74a3b', '#5a5c69'
                    ],
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Distribuição de Gêneros' }
                }
            }
        });
    }

    updateActivities(activities) {
        const container = document.getElementById('recentActivities');
        container.innerHTML = activities.map(act => `
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="badge ${this.getActivityBadge(act.tipo)}">${act.tipo}</span>
                        ${act.descricao}
                    </div>
                    <small class="text-muted">${new Date(act.data).toLocaleDateString()}</small>
                </div>
                ${act.titulo ? `<small class="text-muted">Mangá: ${act.titulo}</small>` : ''}
            </div>
        `).join('');
    }

    getActivityBadge(type) {
        const badges = {
            upload: 'bg-primary',
            update: 'bg-warning',
            delete: 'bg-danger'
        };
        return badges[type.toLowerCase()] || 'bg-secondary';
    }

    updatePagination(totalItems) {
        const totalPages = Math.ceil(totalItems / this.perPage);
        const pagination = document.getElementById('pagination');
        
        let html = '';
        for(let i = 1; i <= totalPages; i++) {
            html += `
                <li class="page-item ${i === this.currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }
        
        pagination.innerHTML = html;
        pagination.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.currentPage = parseInt(link.dataset.page);
                this.loadData();
            });
        });
    }

    initExport() {
        document.getElementById('exportCSV').addEventListener('click', async () => {
            try {
                const response = await fetch('dashboard_data.php');
                const data = await response.json();
                
                let csvContent = "data:text/csv;charset=utf-8,";
                csvContent += "Tipo de Dados,Valores\n";
                
                // Exportar estatísticas
                csvContent += `Total de Mangás,${data.totalMangas}\n`;
                csvContent += `Total de Capítulos,${data.totalCapitulos}\n`;
                csvContent += `Total de Usuários,${data.totalUsuarios}\n`;
                csvContent += `Mangá Mais Popular,${data.mangaPopular}\n\n`;
                
                // Exportar dados dos gráficos
                csvContent += "Capítulos por Mangá\n";
                data.capitulosPorManga.forEach(m => {
                    csvContent += `${m.titulo},${m.total}\n`;
                });
                
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement('a');
                link.setAttribute('href', encodedUri);
                link.setAttribute('download', 'mangaflow_data.csv');
                document.body.appendChild(link);
                link.click();
            } catch (error) {
                this.showError(error);
            }
        });
    }

    initAutoRefresh() {
        setInterval(() => {
            this.loadData();
        }, 300000); // 5 minutos
    }

    showError(error) {
        console.error("Erro:", error);
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger mt-3';
        alert.innerHTML = `
            <strong>Erro!</strong> Não foi possível carregar os dados.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.content').prepend(alert);
    }
}

// Inicializar o dashboard
new Dashboard();