-- ================================================
-- DIÁRIO DE BORDO - DATABASE SETUP POSTGRESQL
-- Versão adaptada para Railway + PostgreSQL
-- ================================================

-- Dropar tabelas se existirem (ordem por dependências)
DROP TABLE IF EXISTS favoritos CASCADE;
DROP TABLE IF EXISTS sugestoes CASCADE;
DROP TABLE IF EXISTS viagem_status_logs CASCADE;
DROP TABLE IF EXISTS push_subscriptions CASCADE;
DROP TABLE IF EXISTS password_resets CASCADE;
DROP TABLE IF EXISTS failed_jobs CASCADE;
DROP TABLE IF EXISTS personal_access_tokens CASCADE;
DROP TABLE IF EXISTS viagens CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- ================================================
-- TABELA: users
-- ================================================
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NULL,
    cargo VARCHAR(100) NULL,
    data_nascimento DATE NULL,
    foto_perfil VARCHAR(255) NULL,
    cpf VARCHAR(14) UNIQUE NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    precisa_trocar_senha BOOLEAN DEFAULT FALSE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices para users
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_cpf ON users(cpf);
CREATE INDEX idx_users_is_admin ON users(is_admin);

-- ================================================
-- TABELA: password_resets
-- ================================================
CREATE TABLE password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_password_resets_email ON password_resets(email);

-- ================================================
-- TABELA: failed_jobs
-- ================================================
CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- TABELA: personal_access_tokens
-- ================================================
CREATE TABLE personal_access_tokens (
    id BIGSERIAL PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_personal_access_tokens_tokenable ON personal_access_tokens(tokenable_type, tokenable_id);

-- ================================================
-- TABELA: viagens
-- ================================================
CREATE TABLE viagens (
    id BIGSERIAL PRIMARY KEY,
    data DATE NOT NULL,
    hora_saida TIME NOT NULL,
    km_saida INTEGER NOT NULL,
    destino VARCHAR(255) NOT NULL,
    hora_chegada TIME NULL,
    km_chegada INTEGER NULL,
    condutor VARCHAR(255) NOT NULL,
    user_id BIGINT NOT NULL,
    num_registro_abastecimento VARCHAR(100) NULL,
    quantidade_abastecida DECIMAL(8,2) NULL,
    tipo_veiculo VARCHAR(100) NULL,
    placa VARCHAR(10) NULL,
    checklist JSONB NULL,
    anexos JSONB NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Índices para viagens
CREATE INDEX idx_viagens_user_id ON viagens(user_id);
CREATE INDEX idx_viagens_data ON viagens(data);
CREATE INDEX idx_viagens_destino ON viagens(destino);
CREATE INDEX idx_viagens_condutor ON viagens(condutor);
CREATE INDEX idx_viagens_tipo_veiculo ON viagens(tipo_veiculo);
CREATE INDEX idx_viagens_placa ON viagens(placa);

-- ================================================
-- TABELA: sugestoes
-- ================================================
CREATE TABLE sugestoes (
    id BIGSERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    tipo VARCHAR(20) CHECK (tipo IN ('sugestao', 'contato')) DEFAULT 'sugestao',
    mensagem TEXT NOT NULL,
    user_id BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Índices para sugestoes
CREATE INDEX idx_sugestoes_user_id ON sugestoes(user_id);
CREATE INDEX idx_sugestoes_tipo ON sugestoes(tipo);
CREATE INDEX idx_sugestoes_email ON sugestoes(email);

-- ================================================
-- TABELA: favoritos
-- ================================================
CREATE TABLE favoritos (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    viagem_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (viagem_id) REFERENCES viagens(id) ON DELETE CASCADE,
    UNIQUE(user_id, viagem_id)
);

-- Índices para favoritos
CREATE INDEX idx_favoritos_user_id ON favoritos(user_id);
CREATE INDEX idx_favoritos_viagem_id ON favoritos(viagem_id);

-- ================================================
-- TABELA: push_subscriptions
-- ================================================
CREATE TABLE push_subscriptions (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    endpoint TEXT NOT NULL,
    p256dh_key VARCHAR(255) NOT NULL,
    auth_token VARCHAR(255) NOT NULL,
    user_agent TEXT NULL,
    last_used_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Índices para push_subscriptions
CREATE INDEX idx_push_subscriptions_user_id ON push_subscriptions(user_id);

-- ================================================
-- TABELA: viagem_status_logs
-- ================================================
CREATE TABLE viagem_status_logs (
    id BIGSERIAL PRIMARY KEY,
    viagem_id BIGINT NOT NULL,
    status_anterior VARCHAR(50) NULL,
    status_novo VARCHAR(50) NOT NULL,
    observacoes TEXT NULL,
    user_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (viagem_id) REFERENCES viagens(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Índices para viagem_status_logs
CREATE INDEX idx_viagem_status_logs_viagem_id ON viagem_status_logs(viagem_id);
CREATE INDEX idx_viagem_status_logs_user_id ON viagem_status_logs(user_id);

-- ================================================
-- INSERIR DADOS DE EXEMPLO
-- ================================================

-- Usuário Administrador
INSERT INTO users (name, email, email_verified_at, password, telefone, cargo, is_admin, precisa_trocar_senha)
VALUES (
    'Administrador',
    'admin@diariobordo.local',
    CURRENT_TIMESTAMP,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    '(11) 99999-9999',
    'Administrador do Sistema',
    TRUE,
    FALSE
);

-- Usuário Regular 1
INSERT INTO users (name, email, email_verified_at, password, telefone, cargo, cpf, is_admin, precisa_trocar_senha)
VALUES (
    'João Silva',
    'joao.silva@empresa.com',
    CURRENT_TIMESTAMP,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    '(11) 98888-8888',
    'Vendedor Externo',
    '123.456.789-01',
    FALSE,
    TRUE
);

-- Usuário Regular 2
INSERT INTO users (name, email, email_verified_at, password, telefone, cargo, cpf, is_admin, precisa_trocar_senha)
VALUES (
    'Maria Santos',
    'maria.santos@empresa.com',
    CURRENT_TIMESTAMP,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    '(11) 97777-7777',
    'Consultora Comercial',
    '987.654.321-09',
    FALSE,
    FALSE
);

-- Usuário Regular 3
INSERT INTO users (name, email, email_verified_at, password, telefone, cargo, cpf, is_admin, precisa_trocar_senha)
VALUES (
    'Pedro Costa',
    'pedro.costa@empresa.com',
    CURRENT_TIMESTAMP,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    '(11) 96666-6666',
    'Supervisor de Vendas',
    '456.789.123-45',
    FALSE,
    FALSE
);

-- Viagens de Exemplo
INSERT INTO viagens (data, hora_saida, km_saida, destino, hora_chegada, km_chegada, condutor, user_id, tipo_veiculo, placa, num_registro_abastecimento, quantidade_abastecida) VALUES
('2024-01-15', '08:00:00', 15000, 'São Paulo - Cliente ABC Ltda', '17:30:00', 15250, 'João Silva', 2, 'Sedan', 'ABC-1234', 'REG001', 45.5),
('2024-01-16', '09:00:00', 15250, 'Campinas - Reunião Regional', '18:00:00', 15380, 'João Silva', 2, 'Sedan', 'ABC-1234', 'REG002', 32.0),
('2024-01-17', '07:30:00', 8500, 'Santos - Visita Técnica', '16:45:00', 8720, 'Maria Santos', 3, 'SUV', 'XYZ-5678', 'REG003', 52.3),
('2024-01-18', '08:15:00', 12000, 'Sorocaba - Apresentação', '17:15:00', 12180, 'Pedro Costa', 4, 'Hatch', 'DEF-9012', 'REG004', 38.7),
('2024-01-19', '09:30:00', 8720, 'Ribeirão Preto - Treinamento', '19:00:00', 9050, 'Maria Santos', 3, 'SUV', 'XYZ-5678', 'REG005', 48.2),
('2024-01-20', '08:00:00', 15380, 'Bauru - Cliente DEF Corp', NULL, NULL, 'João Silva', 2, 'Sedan', 'ABC-1234', NULL, NULL),
('2024-01-22', '10:00:00', 12180, 'Jundiaí - Prospecção', '15:30:00', 12280, 'Pedro Costa', 4, 'Hatch', 'DEF-9012', 'REG006', 28.5),
('2024-01-23', '07:45:00', 9050, 'Guarulhos - Entrega', '12:30:00', 9150, 'Maria Santos', 3, 'SUV', 'XYZ-5678', 'REG007', 25.8);

-- Sugestões de Exemplo
INSERT INTO sugestoes (nome, email, tipo, mensagem, user_id) VALUES
('João Silva', 'joao.silva@empresa.com', 'sugestao', 'Seria interessante ter uma funcionalidade de rota otimizada no sistema.', 2),
('Maria Santos', 'maria.santos@empresa.com', 'contato', 'Gostaria de reportar um pequeno bug na tela de relatórios.', 3),
('Cliente Externo', 'cliente@empresa.com', 'contato', 'Solicito informações sobre como acessar os relatórios da minha empresa.', NULL);

-- Favoritos de Exemplo
INSERT INTO favoritos (user_id, viagem_id) VALUES
(2, 1),
(2, 2),
(3, 3),
(3, 5),
(4, 4),
(4, 7);

-- ================================================
-- VIEWS PARA RELATÓRIOS
-- ================================================

-- View: Viagens Completas (com informações do usuário)
CREATE OR REPLACE VIEW vw_viagens_completas AS
SELECT 
    v.id,
    v.data,
    v.hora_saida,
    v.km_saida,
    v.destino,
    v.hora_chegada,
    v.km_chegada,
    v.condutor,
    v.tipo_veiculo,
    v.placa,
    v.num_registro_abastecimento,
    v.quantidade_abastecida,
    v.created_at,
    v.updated_at,
    u.name as usuario_nome,
    u.email as usuario_email,
    u.cargo as usuario_cargo,
    CASE 
        WHEN v.km_chegada IS NOT NULL THEN (v.km_chegada - v.km_saida)
        ELSE 0 
    END as distancia_percorrida,
    CASE 
        WHEN v.km_chegada IS NOT NULL THEN 'Concluída'
        WHEN v.data < CURRENT_DATE THEN 'Em Andamento'
        ELSE 'Agendada'
    END as status_viagem
FROM viagens v
JOIN users u ON v.user_id = u.id;

-- View: Estatísticas por Usuário
CREATE OR REPLACE VIEW vw_estatisticas_usuarios AS
SELECT 
    u.id,
    u.name,
    u.email,
    u.cargo,
    COUNT(v.id) as total_viagens,
    COALESCE(SUM(CASE WHEN v.km_chegada IS NOT NULL THEN (v.km_chegada - v.km_saida) ELSE 0 END), 0) as km_total,
    COALESCE(SUM(v.quantidade_abastecida), 0) as combustivel_total,
    COUNT(CASE WHEN v.km_chegada IS NOT NULL THEN 1 END) as viagens_concluidas,
    COUNT(CASE WHEN v.km_chegada IS NULL AND v.data <= CURRENT_DATE THEN 1 END) as viagens_em_andamento,
    COUNT(CASE WHEN v.data > CURRENT_DATE THEN 1 END) as viagens_agendadas
FROM users u
LEFT JOIN viagens v ON u.id = v.user_id
WHERE u.is_admin = FALSE
GROUP BY u.id, u.name, u.email, u.cargo;

-- View: Relatório Mensal
CREATE OR REPLACE VIEW vw_relatorio_mensal AS
SELECT 
    EXTRACT(YEAR FROM v.data) as ano,
    EXTRACT(MONTH FROM v.data) as mes,
    TO_CHAR(v.data, 'Month YYYY') as periodo,
    COUNT(v.id) as total_viagens,
    COUNT(DISTINCT v.user_id) as usuarios_ativos,
    COALESCE(SUM(CASE WHEN v.km_chegada IS NOT NULL THEN (v.km_chegada - v.km_saida) ELSE 0 END), 0) as km_total,
    COALESCE(SUM(v.quantidade_abastecida), 0) as combustivel_total,
    COALESCE(AVG(CASE WHEN v.km_chegada IS NOT NULL THEN (v.km_chegada - v.km_saida) END), 0) as km_medio_por_viagem
FROM viagens v
GROUP BY EXTRACT(YEAR FROM v.data), EXTRACT(MONTH FROM v.data), TO_CHAR(v.data, 'Month YYYY')
ORDER BY ano DESC, mes DESC;

-- ================================================
-- TRIGGERS E FUNCTIONS
-- ================================================

-- Function para atualizar updated_at automaticamente
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Triggers para updated_at
CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_viagens_updated_at BEFORE UPDATE ON viagens
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_sugestoes_updated_at BEFORE UPDATE ON sugestoes
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_favoritos_updated_at BEFORE UPDATE ON favoritos
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_push_subscriptions_updated_at BEFORE UPDATE ON push_subscriptions
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_viagem_status_logs_updated_at BEFORE UPDATE ON viagem_status_logs
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- ================================================
-- CONFIGURAÇÕES DE PERFORMANCE
-- ================================================

-- Configurar autovacuum para otimização
ALTER TABLE users SET (autovacuum_vacuum_scale_factor = 0.1);
ALTER TABLE viagens SET (autovacuum_vacuum_scale_factor = 0.1);
ALTER TABLE sugestoes SET (autovacuum_vacuum_scale_factor = 0.2);

-- ================================================
-- VERIFICAÇÃO FINAL
-- ================================================

-- Mostrar contagem de registros
SELECT 'users' as tabela, COUNT(*) as registros FROM users
UNION ALL
SELECT 'viagens' as tabela, COUNT(*) as registros FROM viagens
UNION ALL
SELECT 'sugestoes' as tabela, COUNT(*) as registros FROM sugestoes
UNION ALL
SELECT 'favoritos' as tabela, COUNT(*) as registros FROM favoritos;

-- ================================================
-- SETUP COMPLETO!
-- ================================================
-- ✅ Estrutura do banco criada
-- ✅ Dados de exemplo inseridos  
-- ✅ Views para relatórios criadas
-- ✅ Triggers configurados
-- ✅ Índices para performance
-- ✅ 4 usuários de exemplo
-- ✅ 8 viagens de exemplo
-- ✅ Dados para testes completos
-- 
-- PRÓXIMOS PASSOS:
-- 1. Configure as variáveis de ambiente no Railway
-- 2. Execute as migrations do Laravel
-- 3. Teste o sistema completo
-- ================================================
