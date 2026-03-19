-- =============================================
-- Permissões por módulo do sistema
-- Executar no servidor: mysql -u root -p criacao < arquivo.sql
-- =============================================

-- Inserir permissões de módulo (ignora se já existem)
INSERT IGNORE INTO permissions (name, display_name, description, created_at, updated_at) VALUES
('dashboard',     'Dashboard',      'Acesso ao painel principal',              NOW(), NOW()),
('produtos',      'Produtos',       'Gerenciamento de produtos',               NOW(), NOW()),
('tecidos',       'Tecidos',        'Gerenciamento de tecidos',                NOW(), NOW()),
('movimentacoes', 'Movimentações',  'Visualização de movimentações',           NOW(), NOW()),
('kanban',        'Kanban',         'Quadro Kanban de produção',               NOW(), NOW()),
('planejamento',  'Planejamento',   'Dashboard de planejamento e capacidade',  NOW(), NOW()),
('sugestoes',     'Sugestões',      'Módulo de sugestões',                     NOW(), NOW()),
('logistica',     'Logística',      'Coletas logísticas e veículos',           NOW(), NOW()),
('cadastros',     'Cadastros',      'Cadastros gerais (estilistas, marcas, etc)', NOW(), NOW()),
('consultas',     'Consultas',      'Consultas e relatórios',                  NOW(), NOW());

-- Criar grupo "Motorista" (se não existir)
INSERT IGNORE INTO `groups` (name, display_name, description, is_active, created_at, updated_at) VALUES
('motorista', 'Motorista', 'Acesso apenas ao módulo de logística e coletas', 1, NOW(), NOW());

-- Vincular grupo Motorista às permissões: dashboard + logística
INSERT IGNORE INTO group_permission (group_id, permission_id)
SELECT g.id, p.id
FROM `groups` g, permissions p
WHERE g.name = 'motorista' AND p.name IN ('dashboard', 'logistica');
