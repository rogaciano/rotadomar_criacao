-- =============================================
-- REVERTER unificação de etapas logísticas
-- Restaurar AGUARDANDO MOTORISTA no fluxo
-- Fluxo original: aguardando_retirada → aguardando_motorista → em_transito → coletado
-- Executar no servidor: mysql -u root -p criacao < arquivo.sql
-- =============================================

-- 1. Reativar etapa AGUARDANDO MOTORISTA
UPDATE etapas_producao SET ativo = 1 WHERE slug = 'aguardando_motorista';

-- 2. Remover transição direta aguardando_retirada → em_transito (criada pela unificação)
DELETE et FROM etapas_transicoes et
INNER JOIN etapas_producao ep_orig ON et.etapa_origem_id = ep_orig.id
INNER JOIN etapas_producao ep_dest ON et.etapa_destino_id = ep_dest.id
WHERE ep_orig.slug = 'aguardando_retirada' AND ep_dest.slug = 'em_transito';

-- 3. Restaurar transições originais (se não existirem)
SET @aguardando_retirada_id = (SELECT id FROM etapas_producao WHERE slug = 'aguardando_retirada');
SET @aguardando_motorista_id = (SELECT id FROM etapas_producao WHERE slug = 'aguardando_motorista');
SET @em_transito_id = (SELECT id FROM etapas_producao WHERE slug = 'em_transito');
SET @max_ordem = (SELECT COALESCE(MAX(ordem), 0) FROM etapas_transicoes);

-- Aguardando Retirada → Aguardando Motorista
INSERT INTO etapas_transicoes (etapa_origem_id, etapa_destino_id, label_botao, cor_botao, ativo, ordem, created_at, updated_at)
SELECT @aguardando_retirada_id, @aguardando_motorista_id, NULL, 'yellow', 1, @max_ordem + 1, NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM etapas_transicoes
    WHERE etapa_origem_id = @aguardando_retirada_id AND etapa_destino_id = @aguardando_motorista_id
);

-- Aguardando Motorista → Em Trânsito
INSERT INTO etapas_transicoes (etapa_origem_id, etapa_destino_id, label_botao, cor_botao, ativo, ordem, created_at, updated_at)
SELECT @aguardando_motorista_id, @em_transito_id, NULL, 'orange', 1, @max_ordem + 2, NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM etapas_transicoes
    WHERE etapa_origem_id = @aguardando_motorista_id AND etapa_destino_id = @em_transito_id
);
