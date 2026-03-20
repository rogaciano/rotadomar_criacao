-- =============================================
-- Restaurar etapa AGUARDANDO MOTORISTA (deletada)
-- e criar transições do fluxo logístico completo:
-- aguardando_retirada(8) → aguardando_motorista(NOVO) → em_transito(18) → coletado(9)
-- Executar no servidor: mysql -u root -p criacao < arquivo.sql
-- =============================================

-- 1. Recriar etapa AGUARDANDO MOTORISTA (id 17 foi deletado)
INSERT INTO etapas_producao (nome, slug, descricao, cor, icone, ativo, ordem, localizacao_id, obriga_data_entrega_faccao, created_at, updated_at)
VALUES ('AGUARDANDO MOTORISTA', 'aguardando_motorista', 'Coleta agendada, aguardando motorista chegar', 'yellow', '🚛', 1, 14, 13369, 0, NOW(), NOW());

-- 2. Capturar IDs para as transições
SET @aguardando_retirada_id = (SELECT id FROM etapas_producao WHERE slug = 'aguardando_retirada' AND deleted_at IS NULL LIMIT 1);
SET @aguardando_motorista_id = (SELECT id FROM etapas_producao WHERE slug = 'aguardando_motorista' AND deleted_at IS NULL LIMIT 1);
SET @em_transito_id = (SELECT id FROM etapas_producao WHERE slug = 'em_transito' AND deleted_at IS NULL LIMIT 1);

-- 3. Verificar IDs (debug - pode remover)
SELECT @aguardando_retirada_id AS retirada, @aguardando_motorista_id AS motorista, @em_transito_id AS transito;

-- 4. Transição: Aguardando Retirada → Aguardando Motorista
INSERT INTO etapas_transicoes (etapa_origem_id, etapa_destino_id, label_botao, cor_botao, ativo, ordem, created_at, updated_at)
VALUES (@aguardando_retirada_id, @aguardando_motorista_id, NULL, 'yellow', 1, 
    (SELECT COALESCE(MAX(ordem), 0) + 1 FROM etapas_transicoes t), NOW(), NOW());

-- 5. Transição: Aguardando Motorista → Em Trânsito
INSERT INTO etapas_transicoes (etapa_origem_id, etapa_destino_id, label_botao, cor_botao, ativo, ordem, created_at, updated_at)
VALUES (@aguardando_motorista_id, @em_transito_id, NULL, 'orange', 1,
    (SELECT COALESCE(MAX(ordem), 0) + 1 FROM etapas_transicoes t), NOW(), NOW());

-- 6. Reordenar para manter sequência lógica
UPDATE etapas_producao SET ordem = 14 WHERE slug = 'aguardando_motorista';
UPDATE etapas_producao SET ordem = 15 WHERE slug = 'em_transito';
UPDATE etapas_producao SET ordem = 16 WHERE slug = 'coletado';
