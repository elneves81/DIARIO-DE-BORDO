-- =====================================================
-- SCRIPT DE CRIAÇÃO DO BANCO DE DADOS - DIÁRIO DE BORDO
-- =====================================================
-- Versão: 1.0
-- Data: 22/07/2025
-- Descrição: Criação completa do banco com dados de exemplo

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS diario_bordo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE diario_bordo;

-- =====================================================
-- TABELAS PRINCIPAIS
-- =====================================================

-- Tabela de usuários
CREATE TABLE users (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    email_verified_at timestamp NULL DEFAULT NULL,
    password varchar(255) NOT NULL,
    telefone varchar(20) DEFAULT NULL,
    cargo varchar(100) DEFAULT NULL,
    data_nascimento date DEFAULT NULL,
    foto_perfil varchar(255) DEFAULT NULL,
    cpf varchar(14) DEFAULT NULL,
    is_admin tinyint(1) NOT NULL DEFAULT 0,
    precisa_trocar_senha tinyint(1) NOT NULL DEFAULT 0,
    remember_token varchar(100) DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY users_email_unique (email),
    UNIQUE KEY users_cpf_unique (cpf)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de viagens
CREATE TABLE viagems (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    data date NOT NULL,
    hora_saida time NOT NULL,
    km_saida int(11) NOT NULL,
    destino varchar(255) NOT NULL,
    hora_chegada time DEFAULT NULL,
    km_chegada int(11) DEFAULT NULL,
    condutor varchar(255) NOT NULL,
    user_id bigint(20) UNSIGNED NOT NULL,
    num_registro_abastecimento varchar(100) DEFAULT NULL,
    quantidade_abastecida decimal(8,2) DEFAULT NULL,
    tipo_veiculo varchar(100) DEFAULT NULL,
    placa varchar(10) DEFAULT NULL,
    checklist json DEFAULT NULL,
    anexos json DEFAULT NULL,
    origem varchar(255) DEFAULT 'Sede - Ponta Grossa',
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY viagems_user_id_foreign (user_id),
    KEY viagems_data_index (data),
    KEY viagems_destino_index (destino),
    KEY viagems_condutor_index (condutor),
    CONSTRAINT viagems_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de sugestões
CREATE TABLE sugestoes (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    nome varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    tipo enum('sugestao','contato') NOT NULL DEFAULT 'sugestao',
    mensagem text NOT NULL,
    user_id bigint(20) UNSIGNED DEFAULT NULL,
    resposta text DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY sugestoes_user_id_foreign (user_id),
    CONSTRAINT sugestoes_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de favoritos
CREATE TABLE favoritos (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    viagem_id bigint(20) UNSIGNED NOT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY favoritos_user_id_viagem_id_unique (user_id,viagem_id),
    KEY favoritos_viagem_id_foreign (viagem_id),
    CONSTRAINT favoritos_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT favoritos_viagem_id_foreign FOREIGN KEY (viagem_id) REFERENCES viagems (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de notificações push
CREATE TABLE push_subscriptions (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id bigint(20) UNSIGNED NOT NULL,
    endpoint text NOT NULL,
    p256dh_key varchar(255) NOT NULL,
    auth_token varchar(255) NOT NULL,
    user_agent text DEFAULT NULL,
    last_used_at timestamp NULL DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY push_subscriptions_user_id_foreign (user_id),
    CONSTRAINT push_subscriptions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de notificações
CREATE TABLE notifications (
    id char(36) NOT NULL,
    type varchar(255) NOT NULL,
    notifiable_type varchar(255) NOT NULL,
    notifiable_id bigint(20) UNSIGNED NOT NULL,
    data text NOT NULL,
    read_at timestamp NULL DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY notifications_notifiable_type_notifiable_id_index (notifiable_type,notifiable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de tokens de acesso pessoal
CREATE TABLE personal_access_tokens (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    tokenable_type varchar(255) NOT NULL,
    tokenable_id bigint(20) UNSIGNED NOT NULL,
    name varchar(255) NOT NULL,
    token varchar(64) NOT NULL,
    abilities text DEFAULT NULL,
    last_used_at timestamp NULL DEFAULT NULL,
    expires_at timestamp NULL DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY personal_access_tokens_token_unique (token),
    KEY personal_access_tokens_tokenable_type_tokenable_id_index (tokenable_type,tokenable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de redefinição de senhas
CREATE TABLE password_resets (
    email varchar(255) NOT NULL,
    token varchar(255) NOT NULL,
    created_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de jobs falhados
CREATE TABLE failed_jobs (
    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    uuid varchar(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload longtext NOT NULL,
    exception longtext NOT NULL,
    failed_at timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (id),
    UNIQUE KEY failed_jobs_uuid_unique (uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DADOS INICIAIS - USUÁRIOS
-- =====================================================

INSERT INTO users (id, name, email, email_verified_at, password, telefone, cargo, data_nascimento, cpf, is_admin, precisa_trocar_senha, created_at, updated_at) VALUES
(1, 'Administrador', 'admin@diariobordo.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(42) 99999-9999', 'Administrador do Sistema', '1980-01-01', '11111111111', 1, 0, NOW(), NOW()),
(2, 'João Silva', 'joao@diariobordo.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(42) 98888-8888', 'Motorista', '1985-05-15', '22222222222', 0, 0, NOW(), NOW()),
(3, 'Maria Santos', 'maria@diariobordo.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(42) 97777-7777', 'Coordenadora', '1990-08-20', '33333333333', 0, 0, NOW(), NOW()),
(4, 'Carlos Oliveira', 'carlos@diariobordo.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(42) 96666-6666', 'Supervisor', '1988-12-10', '44444444444', 0, 0, NOW(), NOW());

-- =====================================================
-- DADOS INICIAIS - VIAGENS DE EXEMPLO
-- =====================================================

INSERT INTO viagems (id, data, hora_saida, km_saida, destino, hora_chegada, km_chegada, condutor, user_id, tipo_veiculo, placa, origem, created_at, updated_at) VALUES
(1, '2025-07-20', '08:00:00', 45230, 'São Paulo - Santos', '18:30:00', 45308, 'João Silva', 2, 'Caminhão', 'ABC-1234', 'Sede - Ponta Grossa', '2025-07-20 08:00:00', '2025-07-20 18:30:00'),
(2, '2025-07-22', '07:30:00', 52100, 'Rio de Janeiro - Belo Horizonte', NULL, NULL, 'Maria Santos', 3, 'Caminhão', 'XYZ-5678', 'Sede - Ponta Grossa', '2025-07-22 07:30:00', '2025-07-22 07:30:00'),
(3, '2025-07-25', '09:00:00', 38450, 'Curitiba - Florianópolis', NULL, NULL, 'Carlos Oliveira', 4, 'Van', 'DEF-9012', 'Sede - Ponta Grossa', '2025-07-25 09:00:00', '2025-07-25 09:00:00'),
(4, '2025-07-18', '06:45:00', 41200, 'Salvador - Recife', '16:20:00', 42000, 'João Silva', 2, 'Caminhão', 'GHI-3456', 'Sede - Ponta Grossa', '2025-07-18 06:45:00', '2025-07-18 16:20:00'),
(5, '2025-07-15', '08:15:00', 33890, 'Brasília - Goiânia', '14:45:00', 34100, 'Maria Santos', 3, 'Ônibus', 'JKL-7890', 'Sede - Ponta Grossa', '2025-07-15 08:15:00', '2025-07-15 14:45:00'),
(6, '2025-07-12', '07:00:00', 29340, 'Porto Alegre - Pelotas', '19:30:00', 29620, 'Carlos Oliveira', 4, 'Caminhão', 'MNO-2468', 'Sede - Ponta Grossa', '2025-07-12 07:00:00', '2025-07-12 19:30:00'),
(7, '2025-07-10', '09:30:00', 48750, 'Fortaleza - Natal', '17:15:00', 49280, 'João Silva', 2, 'Van', 'PQR-1357', 'Sede - Ponta Grossa', '2025-07-10 09:30:00', '2025-07-10 17:15:00'),
(8, '2025-07-08', '06:00:00', 31200, 'Manaus - Belém', '20:45:00', 32050, 'Maria Santos', 3, 'Caminhão', 'STU-9876', 'Sede - Ponta Grossa', '2025-07-08 06:00:00', '2025-07-08 20:45:00');

-- =====================================================
-- DADOS INICIAIS - SUGESTÕES
-- =====================================================

INSERT INTO sugestoes (id, nome, email, tipo, mensagem, user_id, created_at, updated_at) VALUES
(1, 'João Silva', 'joao@diariobordo.com', 'sugestao', 'Seria interessante ter um campo para observações adicionais nas viagens.', 2, NOW(), NOW()),
(2, 'Maria Santos', 'maria@diariobordo.com', 'contato', 'Gostaria de saber sobre a possibilidade de integração com GPS.', 3, NOW(), NOW()),
(3, 'Carlos Oliveira', 'carlos@diariobordo.com', 'sugestao', 'O sistema poderia ter notificações automáticas para viagens em atraso.', 4, NOW(), NOW());

-- =====================================================
-- DADOS INICIAIS - FAVORITOS
-- =====================================================

INSERT INTO favoritos (user_id, viagem_id, created_at, updated_at) VALUES
(2, 1, NOW(), NOW()),
(2, 4, NOW(), NOW()),
(3, 2, NOW(), NOW()),
(3, 5, NOW(), NOW()),
(4, 3, NOW(), NOW()),
(4, 6, NOW(), NOW());

-- =====================================================
-- CONFIGURAÇÕES E ÍNDICES ADICIONAIS
-- =====================================================

-- Auto increment para as tabelas
ALTER TABLE users AUTO_INCREMENT = 5;
ALTER TABLE viagems AUTO_INCREMENT = 9;
ALTER TABLE sugestoes AUTO_INCREMENT = 4;
ALTER TABLE favoritos AUTO_INCREMENT = 7;
ALTER TABLE push_subscriptions AUTO_INCREMENT = 1;

-- Comentários das tabelas
ALTER TABLE users COMMENT = 'Tabela de usuários do sistema';
ALTER TABLE viagems COMMENT = 'Tabela de registro de viagens';
ALTER TABLE sugestoes COMMENT = 'Tabela de sugestões e contatos dos usuários';
ALTER TABLE favoritos COMMENT = 'Tabela de viagens favoritadas pelos usuários';
ALTER TABLE push_subscriptions COMMENT = 'Tabela de assinaturas para notificações push';

-- =====================================================
-- VIEWS ÚTEIS PARA RELATÓRIOS
-- =====================================================

-- View para estatísticas de viagens por usuário
CREATE VIEW vw_estatisticas_usuario AS
SELECT 
    u.id,
    u.name,
    u.email,
    COUNT(v.id) as total_viagens,
    SUM(CASE WHEN v.km_chegada IS NOT NULL THEN (v.km_chegada - v.km_saida) ELSE 0 END) as km_total,
    COUNT(CASE WHEN v.km_chegada IS NOT NULL THEN 1 END) as viagens_concluidas,
    COUNT(CASE WHEN v.km_chegada IS NULL THEN 1 END) as viagens_em_andamento
FROM users u
LEFT JOIN viagems v ON u.id = v.user_id
GROUP BY u.id, u.name, u.email;

-- View para destinos mais visitados
CREATE VIEW vw_destinos_populares AS
SELECT 
    destino,
    COUNT(*) as total_viagens,
    SUM(CASE WHEN km_chegada IS NOT NULL THEN (km_chegada - km_saida) ELSE 0 END) as km_total
FROM viagems
GROUP BY destino
ORDER BY total_viagens DESC;

-- =====================================================
-- INSERIR DADOS DE MIGRAÇÃO
-- =====================================================

-- Tabela de controle de migrações do Laravel
CREATE TABLE migrations (
    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    migration varchar(255) NOT NULL,
    batch int(11) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO migrations (migration, batch) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2019_08_19_000000_create_failed_jobs_table', 1),
('2019_12_14_000001_create_personal_access_tokens_table', 1),
('2025_06_23_134508_create_viagems_table', 1),
('2025_06_24_000002_add_campos_perfil_to_users_table', 1),
('2025_06_24_200000_add_user_id_to_viagems_table', 1),
('2025_06_25_000000_create_sugestoes_table', 1),
('2025_06_25_000001_create_favoritos_table', 1),
('2025_06_25_100000_add_is_admin_to_users_table', 1),
('2025_06_26_013628_create_notifications_table', 1),
('2025_06_29_022718_create_push_subscriptions_table', 1);

-- =====================================================
-- FINALIZAÇÃO
-- =====================================================

-- Exibir resumo da instalação
SELECT 'BANCO DE DADOS CRIADO COM SUCESSO!' as status;
SELECT 
    'Usuários criados' as tabela, 
    COUNT(*) as registros 
FROM users
UNION ALL
SELECT 
    'Viagens criadas' as tabela, 
    COUNT(*) as registros 
FROM viagems
UNION ALL
SELECT 
    'Sugestões criadas' as tabela, 
    COUNT(*) as registros 
FROM sugestoes;

-- Informações de login
SELECT 
    'CREDENCIAIS DE ACESSO' as info,
    '' as usuario,
    '' as senha
UNION ALL
SELECT 
    'Admin:' as info,
    'admin@diariobordo.com' as usuario,
    'password' as senha
UNION ALL
SELECT 
    'Usuário:' as info,
    'joao@diariobordo.com' as usuario,
    'password' as senha;

-- =====================================================
-- FIM DO SCRIPT
-- =====================================================
