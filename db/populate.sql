-- Inserir Capítulos
INSERT INTO capitulos (manga_id, numero, titulo, arquivo) VALUES
(1, 1, 'O Recrutamento', 'cyber_shinobi/cap1'),
(1, 2, 'Primeira Missão', 'cyber_shinobi/cap2'),
(1, 3, 'Confronto nas Sombras', 'cyber_shinobi/cap3'),
(2, 1, 'O Início da Rebelião', 'tokyo_outlaws/cap1'),
(2, 2, 'O Preço da Liberdade', 'tokyo_outlaws/cap2'),
(3, 1, 'O Despertar do Samurai', 'neon_samurai/cap1');

-- Inserir Usuários
INSERT INTO usuarios (nome, email, senha) VALUES
('João Silva', 'joao.silva@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- senha: password
('Maria Oliveira', 'maria.oliveira@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- senha: password
('Carlos Souza', 'carlos.souza@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- senha: password

-- Inserir Comentários
INSERT INTO comentarios (usuario_id, manga_id, capitulo_id, comentario, data_comentario) VALUES
(1, 1, 1, 'Que início incrível! Mal posso esperar pelo próximo capítulo.', '2023-10-01 10:00:00'),
(2, 1, 1, 'A arte está simplesmente incrível!', '2023-10-01 11:30:00'),
(3, 2, 1, 'Tokyo Outlaws é uma obra-prima!', '2023-10-02 09:15:00');

-- Inserir Favoritos
INSERT INTO favoritos (usuario_id, manga_id) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 1);

-- Inserir Gêneros
INSERT INTO generos (nome) VALUES
('Ação'),
('Aventura'),
('Cyberpunk'),
('Fantasia'),
('Isekai'),
('Romance'),
('Seinen'),
('Shounen');

-- Inserir Mangás com Novas Colunas
INSERT INTO mangas (titulo, descricao, capa, data_publicacao, adulto, status, nota_media, visualizacoes, tipo) VALUES
('Cyber Shinobi', 'No ano de 2142, o ex-mercenário Kaito é recrutado por uma organização secreta para combater a ascensão das IAs rebeldes.', 'cyber_shinobi.jpg', '2023-01-15', 0, 'Em lançamento', 4.7, 12400, 'Mangá'),
('Tokyo Outlaws', 'Em uma Tokyo distópica, um grupo de jovens luta contra um sistema corrupto.', 'tokyo_outlaws.jpg', '2023-02-20', 0, 'Finalizado', 4.5, 9800, 'Mangá'),
('Neon Samurai', 'Um samurai cibernético busca vingança em um mundo dominado por megacorporações.', 'neon_samurai.jpg', '2023-03-10', 1, 'Em lançamento', 4.3, 7500, 'Manhwa');

-- Relacionar Mangás com Gêneros
INSERT INTO mangas_generos (manga_id, genero_id) VALUES
(1, 1), -- Cyber Shinobi: Ação
(1, 3), -- Cyber Shinobi: Cyberpunk
(1, 7), -- Cyber Shinobi: Seinen
(2, 1), -- Tokyo Outlaws: Ação
(2, 2), -- Tokyo Outlaws: Aventura
(2, 8), -- Tokyo Outlaws: Shounen
(3, 1), -- Neon Samurai: Ação
(3, 3), -- Neon Samurai: Cyberpunk
(3, 4); -- Neon Samurai: Fantasia