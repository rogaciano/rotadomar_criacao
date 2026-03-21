-- =============================================
-- Índices para performance da logística
-- Executar no servidor: mysql -u root -p criacao < arquivo.sql
-- =============================================

-- Índice na coleta por status (filtro mais usado)
ALTER TABLE coletas_logisticas ADD INDEX idx_coletas_status (status);

-- Índice composto: motorista + status (para listar coletas do motorista)
ALTER TABLE coletas_logisticas ADD INDEX idx_coletas_motorista_status (motorista_user_id, status);

-- Índice na etapa atual do produto (filtro principal do dashboard)
ALTER TABLE produto_localizacao ADD INDEX idx_pl_etapa_atual (etapa_atual_id);

-- Índice na localização do produto (filtro por origem)
ALTER TABLE produto_localizacao ADD INDEX idx_pl_localizacao (localizacao_id);

-- Índice no slug das etapas (lookup frequente)
ALTER TABLE etapas_producao ADD INDEX idx_etapas_slug (slug);
