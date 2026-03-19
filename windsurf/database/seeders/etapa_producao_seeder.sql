-- =====================================================
-- SEEDER: EtapaProducaoSeeder (SQL para MySQL)
-- =====================================================
-- ⚠️  CUIDADO: Este script APAGA todas as etapas e transições existentes!
-- ⚠️  Só use em banco novo/vazio ou se tiver certeza que pode resetar.
-- ⚠️  Se já rodou logistica_coleta_migrations.sql, NÃO precisa rodar este.
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;

-- Limpar dados existentes
DELETE FROM `etapas_transicoes`;
DELETE FROM `etapas_producao`;

SET FOREIGN_KEY_CHECKS = 1;

-- Criar etapas
INSERT INTO `etapas_producao` (`nome`, `slug`, `icone`, `cor`, `ordem`, `ativo`, `created_at`, `updated_at`) VALUES
('Recebimento',          NULL,                    '📦', 'blue',   1,  1, NOW(), NOW()),
('Separação',            NULL,                    '📋', 'indigo', 2,  1, NOW(), NOW()),
('Preparação',           NULL,                    '✂️', 'purple', 3,  1, NOW(), NOW()),
('Produção',             NULL,                    '⚙️', 'yellow', 4,  1, NOW(), NOW()),
('Aplicação DTF',        NULL,                    '🎨', 'pink',   5,  1, NOW(), NOW()),
('Estamparia',           NULL,                    '🖼️', 'orange', 6,  1, NOW(), NOW()),
('Acabamento',           NULL,                    '✨', 'green',  7,  1, NOW(), NOW()),
('Aguardando Retirada',  'aguardando_retirada',   '📍', 'gray',   8,  1, NOW(), NOW()),
('Aguardando Motorista', 'aguardando_motorista',  '🚛', 'yellow', 9,  1, NOW(), NOW()),
('Em Trânsito',          'em_transito',           '🚚', 'orange', 10, 1, NOW(), NOW()),
('Coletado',             'coletado',              '✅', 'green',  11, 1, NOW(), NOW());

-- Capturar IDs das etapas criadas
SET @recebimento_id          = (SELECT `id` FROM `etapas_producao` WHERE `nome` = 'Recebimento');
SET @separacao_id             = (SELECT `id` FROM `etapas_producao` WHERE `nome` = 'Separação');
SET @preparacao_id            = (SELECT `id` FROM `etapas_producao` WHERE `nome` = 'Preparação');
SET @producao_id              = (SELECT `id` FROM `etapas_producao` WHERE `nome` = 'Produção');
SET @aplicacao_dtf_id         = (SELECT `id` FROM `etapas_producao` WHERE `nome` = 'Aplicação DTF');
SET @estamparia_id            = (SELECT `id` FROM `etapas_producao` WHERE `nome` = 'Estamparia');
SET @acabamento_id            = (SELECT `id` FROM `etapas_producao` WHERE `nome` = 'Acabamento');
SET @aguardando_retirada_id   = (SELECT `id` FROM `etapas_producao` WHERE `slug` = 'aguardando_retirada');
SET @aguardando_motorista_id  = (SELECT `id` FROM `etapas_producao` WHERE `slug` = 'aguardando_motorista');
SET @em_transito_id           = (SELECT `id` FROM `etapas_producao` WHERE `slug` = 'em_transito');
SET @coletado_id              = (SELECT `id` FROM `etapas_producao` WHERE `slug` = 'coletado');

-- Criar transições
INSERT INTO `etapas_transicoes` (`etapa_origem_id`, `etapa_destino_id`, `label_botao`, `cor_botao`, `ativo`, `ordem`, `created_at`, `updated_at`) VALUES
-- Recebimento → Separação
(@recebimento_id,         @separacao_id,            NULL,              'blue',   1, 0,  NOW(), NOW()),
-- Separação → Preparação
(@separacao_id,           @preparacao_id,           NULL,              'blue',   1, 1,  NOW(), NOW()),
-- Preparação → Produção
(@preparacao_id,          @producao_id,             NULL,              'blue',   1, 2,  NOW(), NOW()),
-- Produção → Acabamento (sem DTF/Estampa)
(@producao_id,            @acabamento_id,           'Sem DTF/Estampa', 'blue',   1, 3,  NOW(), NOW()),
-- Produção → Aplicação DTF
(@producao_id,            @aplicacao_dtf_id,        'Aplicar DTF',     'pink',   1, 4,  NOW(), NOW()),
-- Produção → Estamparia
(@producao_id,            @estamparia_id,           'Enviar Estampa',  'orange', 1, 5,  NOW(), NOW()),
-- Aplicação DTF → Acabamento
(@aplicacao_dtf_id,       @acabamento_id,           NULL,              'blue',   1, 6,  NOW(), NOW()),
-- Estamparia → Acabamento
(@estamparia_id,          @acabamento_id,           NULL,              'blue',   1, 7,  NOW(), NOW()),
-- Acabamento → Aguardando Retirada
(@acabamento_id,          @aguardando_retirada_id,  NULL,              'blue',   1, 8,  NOW(), NOW()),
-- Aguardando Retirada → Aguardando Motorista
(@aguardando_retirada_id, @aguardando_motorista_id, NULL,              'yellow', 1, 9,  NOW(), NOW()),
-- Aguardando Motorista → Em Trânsito
(@aguardando_motorista_id,@em_transito_id,          NULL,              'orange', 1, 10, NOW(), NOW()),
-- Em Trânsito → Coletado
(@em_transito_id,         @coletado_id,             NULL,              'green',  1, 11, NOW(), NOW());
