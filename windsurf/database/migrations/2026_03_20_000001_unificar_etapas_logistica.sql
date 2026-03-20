-- =============================================
-- Unificar etapas: eliminar AGUARDANDO MOTORISTA
-- Manter apenas AGUARDANDO RETIRADA no fluxo logístico
-- Fluxo novo: aguardando_retirada → em_transito → coletado
-- Executar no servidor: mysql -u root -p criacao < arquivo.sql
-- =============================================

-- 1. Migrar produtos que estão em AGUARDANDO MOTORISTA de volta para AGUARDANDO RETIRADA
UPDATE produto_localizacao
SET etapa_atual_id = (SELECT id FROM etapas_producao WHERE slug = 'aguardando_retirada' LIMIT 1)
WHERE etapa_atual_id = (SELECT id FROM etapas_producao WHERE slug = 'aguardando_motorista' LIMIT 1);

-- 2. Remover transições que envolvem AGUARDANDO MOTORISTA
DELETE et FROM etapas_transicoes et
INNER JOIN etapas_producao ep ON et.etapa_origem_id = ep.id OR et.etapa_destino_id = ep.id
WHERE ep.slug = 'aguardando_motorista';

-- 3. Criar transição direta: AGUARDANDO RETIRADA → EM TRANSITO (se não existir)
SET @aguardando_retirada_id = (SELECT id FROM etapas_producao WHERE slug = 'aguardando_retirada');
SET @em_transito_id = (SELECT id FROM etapas_producao WHERE slug = 'em_transito');
SET @max_ordem = (SELECT COALESCE(MAX(ordem), 0) FROM etapas_transicoes);

INSERT INTO etapas_transicoes (etapa_origem_id, etapa_destino_id, label_botao, cor_botao, ativo, ordem, created_at, updated_at)
SELECT @aguardando_retirada_id, @em_transito_id, NULL, 'orange', 1, @max_ordem + 1, NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM etapas_transicoes
    WHERE etapa_origem_id = @aguardando_retirada_id AND etapa_destino_id = @em_transito_id
);

-- 4. Desativar a etapa AGUARDANDO MOTORISTA
UPDATE etapas_producao SET ativo = 0 WHERE slug = 'aguardando_motorista';
