-- =====================================================
-- MIGRATIONS PARA PWA MOTORISTA
-- Executar no servidor de produção
-- =====================================================

-- =====================================================
-- 1. Tabela personal_access_tokens (Laravel Sanctum)
-- =====================================================

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `tokenable_type` VARCHAR(255) NOT NULL,
    `tokenable_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `token` VARCHAR(64) NOT NULL,
    `abilities` TEXT NULL,
    `last_used_at` TIMESTAMP NULL,
    `expires_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE INDEX `personal_access_tokens_token_unique` (`token`),
    INDEX `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- 2. Tabela push_subscriptions (Web Push)
-- =====================================================

CREATE TABLE IF NOT EXISTS `push_subscriptions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `endpoint` TEXT NOT NULL,
    `p256dh_key` VARCHAR(255) NOT NULL,
    `auth_key` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    INDEX `push_subscriptions_user_id_index` (`user_id`),
    CONSTRAINT `push_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
