<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - MangaFlow</title>
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

        h1 {
            font-size: 2.5rem;
            text-align: center;
            margin-top: 2rem;
        }

        h2 {
            font-size: 2rem;
            margin-top: 2rem;
            border-bottom: 2px solid #FF4654;
            padding-bottom: 0.5rem;
        }

        p {
            margin-bottom: 1.5rem;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
        }

        ul {
            list-style-type: disc;
            margin-left: 2rem;
            margin-bottom: 1.5rem;
        }

        li {
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
        }

        a {
            color: #FF4654;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Header */
        header {
            background-color: #1A1A1A;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
            color: #FF4654;
            text-shadow: 0 0 10px rgba(255, 70, 84, 0.5);
        }

        /* Main Content */
        main {
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        section {
            margin-bottom: 3rem;
        }

        /* Footer */
        footer {
            background-color: #1A1A1A;
            padding: 1rem 0;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 2rem;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Botões e Links */
        .email-link {
            color: #FF4654;
            font-weight: bold;
        }

        .email-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.75rem;
            }

            main {
                padding: 1rem;
            }
        }

        /* Menu de Navegação */
        nav {
            background-color: #1A1A1A;
            padding: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 0;
            padding: 0;
        }

        nav a {
            color: #FF4654;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* Botão de Voltar ao Topo */
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #FF4654;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .back-to-top.visible {
            opacity: 1;
        }

        /* Barra de Progresso */
        .progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 5px;
            background-color: #FF4654;
            width: 0%;
            z-index: 1000;
        }

        /* Formulário de Contato */
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 500px;
            margin: 0 auto;
        }

        label {
            font-weight: bold;
            color: #FF4654;
        }

        input, textarea {
            padding: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            background-color: #1A1A1A;
            color: white;
        }

        button {
            background-color: #FF4654;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e63946;
        }

        /* FAQ */
        details {
            margin-bottom: 1rem;
        }

        summary {
            font-weight: bold;
            color: #FF4654;
            cursor: pointer;
        }

        summary:hover {
            opacity: 0.8;
        }

        /* Banner de Cookies */
        .cookie-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #1A1A1A;
            padding: 1rem;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cookie-banner button {
            background-color: #FF4654;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <h1>POLÍTICA DE PRIVACIDADE</h1>
    </header>

    <!-- Menu de Navegação -->
    <nav>
        <ul>
            <li><a href="#coleta">1. Coleta de Informações</a></li>
            <li><a href="#uso">2. Uso das Informações</a></li>
            <li><a href="#compartilhamento">3. Compartilhamento de Dados</a></li>
            <li><a href="#seguranca">4. Segurança</a></li>
            <li><a href="#alteracoes">5. Alterações na Política</a></li>
        </ul>
    </nav>

    <!-- Barra de Progresso -->
    <div class="progress-bar"></div>

    <main>
        <section id="coleta">
            <h2><i class="fas fa-info-circle"></i> 1. Coleta de Informações</h2>
            <p>Podemos coletar dados pessoais fornecidos diretamente pelos usuários, como nome e e-mail, além de dados gerados automaticamente, como cookies e logs de navegação.</p>
        </section>
        <section id="uso">
            <h2><i class="fas fa-cogs"></i> 2. Uso das Informações</h2>
            <p>As informações coletadas são utilizadas para:</p>
            <ul>
                <li>Melhorar a experiência do usuário.</li>
                <li>Personalizar conteúdos.</li>
                <li>Analisar o tráfego do site.</li>
            </ul>
        </section>
        <section id="compartilhamento">
            <h2><i class="fas fa-share-alt"></i> 3. Compartilhamento de Dados</h2>
            <p>Não compartilhamos seus dados pessoais com terceiros, salvo em casos exigidos por lei.</p>
        </section>
        <section id="seguranca">
            <h2><i class="fas fa-shield-alt"></i> 4. Segurança</h2>
            <p>Adotamos medidas de segurança adequadas para proteger suas informações contra acessos não autorizados.</p>
        </section>
        <section id="alteracoes">
            <h2><i class="fas fa-exclamation-circle"></i> 5. Alterações na Política</h2>
            <p>Esta Política pode ser alterada a qualquer momento. As mudanças serão publicadas nesta página.</p>
        </section>
        <section>
            <h2><i class="fas fa-question-circle"></i> Perguntas Frequentes</h2>
            <details>
                <summary>Como meus dados são protegidos?</summary>
                <p>Adotamos medidas de segurança avançadas, como criptografia e firewalls, para proteger suas informações.</p>
            </details>
            <details>
                <summary>Posso solicitar a exclusão dos meus dados?</summary>
                <p>Sim, você pode entrar em contato conosco para solicitar a exclusão dos seus dados pessoais.</p>
            </details>
        </section>
        <section>
            <h2><i class="fas fa-envelope"></i> Dúvidas ou Preocupações?</h2>
            <form action="mailto:oxyzfake@gmail.com" method="post" enctype="text/plain">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
                
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="mensagem">Mensagem:</label>
                <textarea id="mensagem" name="mensagem" rows="5" required></textarea>
                
                <button type="submit">Enviar</button>
            </form>
        </section>
    </main>

    <!-- Botão de Voltar ao Topo -->
    <button class="back-to-top" aria-label="Voltar ao topo">↑</button>

    <!-- Rodapé -->
    <footer>
        <div class="footer-links">
            <a href="/termos-de-uso">Termos de Uso</a>
            <a href="/dmca">Projeto DMCA</a>
            <a href="/sobre">Sobre Nós</a>
        </div>
        <p>&copy; 2023 MangaFlow. Todos os direitos reservados.</p>
    </footer>

    <!-- Banner de Cookies -->
    <div class="cookie-banner">
        <p>Este site usa cookies para melhorar sua experiência. <a href="/politica-de-cookies">Saiba mais</a>.</p>
        <button onclick="acceptCookies()">Aceitar</button>
    </div>

    <script>
        // Lógica do Botão de Voltar ao Topo
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

        // Lógica da Barra de Progresso
        const progressBar = document.querySelector('.progress-bar');
        window.addEventListener('scroll', () => {
            const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (scrollTop / scrollHeight) * 100;
            progressBar.style.width = scrolled + '%';
        });

        // Lógica do Banner de Cookies
        function acceptCookies() {
            document.querySelector('.cookie-banner').style.display = 'none';
            // Lógica para salvar a preferência do usuário
        }
    </script>
</body>

</html>