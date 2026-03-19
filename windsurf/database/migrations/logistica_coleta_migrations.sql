-- =====================================================
-- MIGRATION 1: Adicionar slug em etapas_producao + novas etapas logísticas
-- =====================================================

-- 1.1 Adicionar coluna slug
ALTER TABLE `etapas_producao` ADD COLUMN `slug` VARCHAR(50) NULL AFTER `nome`;
ALTER TABLE `etapas_producao` ADD UNIQUE INDEX `etapas_producao_slug_unique` (`slug`);

-- 1.2 Atribuir slugs às etapas logísticas existentes
UPDATE `etapas_producao` SET `slug` = 'aguardando_retirada' WHERE `nome` = 'Aguardando Retirada';
UPDATE `etapas_producao` SET `slug` = 'coletado' WHERE `nome` = 'Coletado';

-- 1.3 Reordenar Coletado para ordem 11
UPDATE `etapas_producao` SET `ordem` = 11 WHERE `slug` = 'coletado';

-- 1.4 Criar etapa Aguardando Motorista (ordem 9)
INSERT INTO `etapas_producao` (`nome`, `slug`, `icone`, `cor`, `ordem`, `ativo`, `created_at`, `updated_at`)
VALUES ('Aguardando Motorista', 'aguardando_motorista', '🚛', 'yellow', 9, 1, NOW(), NOW());

-- 1.5 Criar etapa Em Trânsito (ordem 10)
INSERT INTO `etapas_producao` (`nome`, `slug`, `icone`, `cor`, `ordem`, `ativo`, `created_at`, `updated_at`)
VALUES ('Em Trânsito', 'em_transito', '🚚', 'orange', 10, 1, NOW(), NOW());

-- 1.6 Remover transição direta Aguardando Retirada → Coletado (se existir)
DELETE et FROM `etapas_transicoes` et
INNER JOIN `etapas_producao` ep_orig ON et.`etapa_origem_id` = ep_orig.`id`
INNER JOIN `etapas_producao` ep_dest ON et.`etapa_destino_id` = ep_dest.`id`
WHERE ep_orig.`slug` = 'aguardando_retirada' AND ep_dest.`slug` = 'coletado';

-- 1.7 Criar novas transições logísticas
-- Pegar IDs das etapas (use variáveis MySQL)
SET @aguardando_retirada_id = (SELECT `id` FROM `etapas_producao` WHERE `slug` = 'aguardando_retirada');
SET @aguardando_motorista_id = (SELECT `id` FROM `etapas_producao` WHERE `slug` = 'aguardando_motorista');
SET @em_transito_id = (SELECT `id` FROM `etapas_producao` WHERE `slug` = 'em_transito');
SET @coletado_id = (SELECT `id` FROM `etapas_producao` WHERE `slug` = 'coletado');
SET @max_ordem = (SELECT COALESCE(MAX(`ordem`), 0) FROM `etapas_transicoes`);

-- Aguardando Retirada → Aguardando Motorista
INSERT INTO `etapas_transicoes` (`etapa_origem_id`, `etapa_destino_id`, `label_botao`, `cor_botao`, `ativo`, `ordem`, `created_at`, `updated_at`)
VALUES (@aguardando_retirada_id, @aguardando_motorista_id, NULL, 'yellow', 1, @max_ordem + 1, NOW(), NOW());

-- Aguardando Motorista → Em Trânsito
INSERT INTO `etapas_transicoes` (`etapa_origem_id`, `etapa_destino_id`, `label_botao`, `cor_botao`, `ativo`, `ordem`, `created_at`, `updated_at`)
VALUES (@aguardando_motorista_id, @em_transito_id, NULL, 'orange', 1, @max_ordem + 2, NOW(), NOW());

-- Em Trânsito → Coletado
INSERT INTO `etapas_transicoes` (`etapa_origem_id`, `etapa_destino_id`, `label_botao`, `cor_botao`, `ativo`, `ordem`, `created_at`, `updated_at`)
VALUES (@em_transito_id, @coletado_id, NULL, 'green', 1, @max_ordem + 3, NOW(), NOW());


-- =====================================================
-- MIGRATION 2: Criar tabela veiculos
-- =====================================================

CREATE TABLE `veiculos` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `placa` VARCHAR(10) NOT NULL,
    `descricao` VARCHAR(255) NULL,
    `ativo` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE INDEX `veiculos_placa_unique` (`placa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- MIGRATION 3: Criar tabela coletas_logisticas
-- =====================================================

CREATE TABLE `coletas_logisticas` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `produto_localizacao_id` BIGINT NOT NULL,
    `motorista_user_id` BIGINT UNSIGNED NOT NULL,
    `veiculo_id` BIGINT UNSIGNED NOT NULL,
    `destino_localizacao_id` BIGINT UNSIGNED NOT NULL,
    `inicio_previsto_em` DATETIME NOT NULL,
    `retorno_previsto_em` DATETIME NOT NULL,
    `chegada_origem_em` DATETIME NULL,
    `recebido_destino_em` DATETIME NULL,
    `status` ENUM('agendado', 'em_transito', 'finalizado', 'cancelado') NOT NULL DEFAULT 'agendado',
    `observacao_motorista` TEXT NULL,
    `observacao_origem` TEXT NULL,
    `observacao_destino` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    -- Foreign Keys
    CONSTRAINT `coletas_logisticas_produto_localizacao_id_foreign`
        FOREIGN KEY (`produto_localizacao_id`) REFERENCES `produto_localizacao` (`id`) ON DELETE CASCADE,
    CONSTRAINT `coletas_logisticas_motorista_user_id_foreign`
        FOREIGN KEY (`motorista_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `coletas_logisticas_veiculo_id_foreign`
        FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`) ON DELETE CASCADE,
    CONSTRAINT `coletas_logisticas_destino_localizacao_id_foreign`
        FOREIGN KEY (`destino_localizacao_id`) REFERENCES `localizacoes` (`id`) ON DELETE CASCADE,

    -- Indexes
    INDEX `coletas_motorista_agenda_idx` (`motorista_user_id`, `inicio_previsto_em`, `retorno_previsto_em`),
    INDEX `coletas_veiculo_agenda_idx` (`veiculo_id`, `inicio_previsto_em`, `retorno_previsto_em`),
    INDEX `coletas_prodloc_status_idx` (`produto_localizacao_id`, `status`),
    INDEX `coletas_logisticas_destino_localizacao_id_index` (`destino_localizacao_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- Registrar migrations na tabela do Laravel (opcional)
-- =====================================================
INSERT INTO `migrations` (`migration`, `batch`) VALUES
    ('2026_03_19_000001_add_slug_to_etapas_producao_table', (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations` AS m)),
    ('2026_03_19_000002_create_veiculos_table', (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations` AS m)),
    ('2026_03_19_000003_create_coletas_logisticas_table', (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations` AS m));
