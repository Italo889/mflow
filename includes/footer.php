<footer class="manga-footer">
    <div class="footer-container">
        <div class="footer-grid">
            <!-- Coluna 1 - Brand -->
            <div class="footer-col">
                <h3 class="footer-title">MangaFlow</h3>
                <p class="footer-description">
                    Sua plataforma definitiva para ler mangás online. Descubra e desfrute de uma vasta coleção 
                    de obras literárias, incluindo mangás, manwhas, manhuas e muito mais.
                </p>
                <a href="#" class="cta-link">Quero postar minha obra →</a>
            </div>

            <!-- Coluna 2 - Links Úteis -->
            <div class="footer-col">
                <h3 class="footer-title">Links Úteis</h3>
                <ul class="footer-links">
                    <li><a href="http://localhost/mangaflow/pages/dmca.php">Projeto DMCA</a></li>
                    <li><a href="http://localhost/mangaflow/pages/termos_de_uso.php">Termos de Uso</a></li>
                    <li><a href="http://localhost/mangaflow/pages/sobre.php">Sobre Nós</a></li>
                    <li><a href="http://localhost/mangaflow/pages/politica_de_privacidade.php">Política de Privacidade</a></li>
                </ul>
            </div>

            <!-- Coluna 3 - Redes Sociais -->
            <div class="footer-col">
                <h3 class="footer-title">Conecte-se</h3>
                <div class="social-links">
                    <a href="https://discord.gg/W2zWNhMf" class="social-link"><i class="fab fa-discord"></i> Discord</a>
                    <a href="https://x.com/ItaloNexen" class="social-link"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="https://instagram.com/italo_dev" class="social-link"><i class="fab fa-instagram"></i> Instagram</a>
                </div>
            </div>

            <!-- Coluna 4 - Comunidade -->
            <div class="footer-col">
                <h3 class="footer-title">Comunidade</h3>
                <p class="community-text">Junte-se a nossa comunidade de fãs!</p>
                <a href="https://discord.gg/W2zWNhMf" class="discord-btn">
                    <i class="fab fa-discord"></i>
                    Acessar Servidor
                </a>
            </div>
        </div>

        <!-- Divisor -->
        <div class="footer-divider"></div>

        <!-- Rodapé Inferior -->
        <div class="footer-bottom">
            <div class="copyright">
                © 2025 MangaFlow. Todos os direitos reservados.
                <span class="login-notice">Algumas páginas requerem login para acesso</span>
            </div>
            <div class="legal-links">
                <a href="http://localhost/mangaflow/pages/dmca.php">DMCA</a>
                <a href="http://localhost/mangaflow/pages/termos_de_uso.php">Termos de Uso</a>
                <a href="http://localhost/mangaflow/pages/politica_de_privacidade.php">Política de Privacidade</a>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Estilo usando as variáveis do header */
    .manga-footer {
        background: #0F0F0F;
        border-top: 1px solid rgba(255, 70, 84, 0.1);
        padding: 3rem 0 1rem;
        font-family: 'Inter', sans-serif;
    }

    .footer-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .footer-title {
        font-family: 'Bangers', cursive;
        color: #FF4654;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        letter-spacing: 1px;
    }

    .footer-description {
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .cta-link {
        color: #FF4654;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .cta-link:hover {
        opacity: 0.8;
        text-decoration: underline;
    }

    .footer-links li {
        margin-bottom: 0.8rem;
    }

    .footer-links a {
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .footer-links a:hover {
        color: #FF4654;
        padding-left: 8px;
    }

    .social-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.7);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        color: #FF4654;
        transform: translateX(5px);
    }

    .discord-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #5865F2 0%, #4752C4 100%);
        color: white !important;
        padding: 12px 24px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .discord-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(88, 101, 242, 0.3);
    }

    .footer-divider {
        border-top: 1px solid rgba(255,255,255,0.1);
        margin: 2rem 0;
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: rgba(255,255,255,0.5);
        font-size: 0.85rem;
    }

    .legal-links a {
        color: rgba(255,255,255,0.5);
        margin-left: 1.5rem;
        transition: all 0.3s ease;
    }

    .legal-links a:hover {
        color: #FF4654;
    }

    .login-notice {
        margin-left: 1.5rem;
        font-size: 0.8rem;
    }

    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
        
        .footer-bottom {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        
        .legal-links a {
            margin: 0 0.5rem;
        }
    }
</style>