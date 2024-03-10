-- -------------------------------------------------------------
-- TablePlus 5.9.0(538)
--
-- https://tableplus.com/
--
-- Database: fitness
-- Generation Time: 2024-03-10 19:47:04.5240
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE `app_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_copyright` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_option` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`language_option`)),
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `help_support_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `assign_diets`;
CREATE TABLE `assign_diets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `diet_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `assign_workouts`;
CREATE TABLE `assign_workouts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `workout_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `body_parts`;
CREATE TABLE `body_parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `category_diets`;
CREATE TABLE `category_diets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `diets`;
CREATE TABLE `diets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categorydiet_id` bigint(20) unsigned DEFAULT NULL,
  `calories` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carbs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `protein` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `servings` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `ingredients` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_premium` tinyint(1) DEFAULT 0 COMMENT '0-free, 1-premium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diets_categorydiet_id_foreign` (`categorydiet_id`),
  CONSTRAINT `diets_categorydiet_id_foreign` FOREIGN KEY (`categorydiet_id`) REFERENCES `category_diets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `equipment`;
CREATE TABLE `equipment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `exercises`;
CREATE TABLE `exercises` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instruction` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tips` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bodypart_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `based` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'reps, time',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'sets, duration',
  `equipment_id` bigint(20) unsigned DEFAULT NULL,
  `level_id` bigint(20) unsigned DEFAULT NULL,
  `sets` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sets`)),
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `is_premium` tinyint(1) DEFAULT 0 COMMENT '0-free, 1-premium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `levels`;
CREATE TABLE `levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` bigint(20) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` bigint(20) unsigned DEFAULT NULL,
  `price` double DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payment_gateways`;
CREATE TABLE `payment_gateways` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1 COMMENT '0- InActive, 1- Active',
  `is_test` tinyint(4) DEFAULT 1 COMMENT '0-  No, 1- Yes',
  `test_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`test_value`)),
  `live_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`live_value`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `is_featured` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affiliate_link` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double DEFAULT NULL,
  `productcategory_id` bigint(20) unsigned DEFAULT NULL,
  `featured` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_productcategory_id_foreign` (`productcategory_id`),
  CONSTRAINT `products_productcategory_id_foreign` FOREIGN KEY (`productcategory_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `push_notifications`;
CREATE TABLE `push_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `quotes`;
CREATE TABLE `quotes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `package_id` bigint(20) unsigned DEFAULT NULL,
  `total_amount` double DEFAULT 0,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'cash',
  `txn_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`transaction_detail`)),
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'pending' COMMENT 'pending, paid, failed',
  `subscription_start_date` datetime DEFAULT NULL,
  `subscription_end_date` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'active, inactive',
  `package_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`package_data`)),
  `callback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_user_id_foreign` (`user_id`),
  KEY `subscriptions_package_id_foreign` (`package_id`),
  CONSTRAINT `subscriptions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_favourite_diets`;
CREATE TABLE `user_favourite_diets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `diet_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_favourite_diets_user_id_foreign` (`user_id`),
  KEY `user_favourite_diets_diet_id_foreign` (`diet_id`),
  CONSTRAINT `user_favourite_diets_diet_id_foreign` FOREIGN KEY (`diet_id`) REFERENCES `diets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_favourite_diets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_favourite_workouts`;
CREATE TABLE `user_favourite_workouts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `workout_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_favourite_workouts_user_id_foreign` (`user_id`),
  KEY `user_favourite_workouts_workout_id_foreign` (`workout_id`),
  CONSTRAINT `user_favourite_workouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_favourite_workouts_workout_id_foreign` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_graphs`;
CREATE TABLE `user_graphs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE `user_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `age` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `login_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `player_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_subscribe` tinyint(4) DEFAULT 0,
  `last_notification_seen` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `workout_day_exercises`;
CREATE TABLE `workout_day_exercises` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `workout_id` bigint(20) unsigned DEFAULT NULL,
  `workout_day_id` bigint(20) unsigned DEFAULT NULL,
  `exercise_id` bigint(20) unsigned DEFAULT NULL,
  `sets` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sets`)),
  `sequence` bigint(20) unsigned DEFAULT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workout_day_exercises_workout_id_foreign` (`workout_id`),
  KEY `workout_day_exercises_workout_day_id_foreign` (`workout_day_id`),
  CONSTRAINT `workout_day_exercises_workout_day_id_foreign` FOREIGN KEY (`workout_day_id`) REFERENCES `workout_days` (`id`) ON DELETE CASCADE,
  CONSTRAINT `workout_day_exercises_workout_id_foreign` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `workout_days`;
CREATE TABLE `workout_days` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `workout_id` bigint(20) unsigned DEFAULT NULL,
  `sequence` bigint(20) DEFAULT NULL,
  `is_rest` tinyint(1) DEFAULT 0 COMMENT '0-no,1-yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workout_days_workout_id_foreign` (`workout_id`),
  CONSTRAINT `workout_days_workout_id_foreign` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `workout_types`;
CREATE TABLE `workout_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `workouts`;
CREATE TABLE `workouts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level_id` bigint(20) unsigned DEFAULT NULL,
  `workout_type_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `is_premium` tinyint(1) DEFAULT 0 COMMENT '0-free, 1-premium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workouts_level_id_foreign` (`level_id`),
  KEY `workouts_workout_type_id_foreign` (`workout_type_id`),
  CONSTRAINT `workouts_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `workouts_workout_type_id_foreign` FOREIGN KEY (`workout_type_id`) REFERENCES `workout_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `app_settings` (`id`, `site_name`, `site_email`, `site_description`, `site_copyright`, `facebook_url`, `instagram_url`, `twitter_url`, `linkedin_url`, `language_option`, `contact_email`, `contact_number`, `help_support_url`, `created_at`, `updated_at`) VALUES
(1, 'Fitness Goals', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"en\"]', NULL, NULL, NULL, NULL, NULL);

INSERT INTO `assign_workouts` (`id`, `user_id`, `workout_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2024-03-10 11:56:36', '2024-03-10 11:56:36');

INSERT INTO `body_parts` (`id`, `title`, `status`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Upper Body', 'active', '2024-03-09 23:53:29', '2024-03-09 23:53:29', NULL),
(2, 'Abs', 'active', '2024-03-09 23:53:38', '2024-03-09 23:53:38', NULL);

INSERT INTO `exercises` (`id`, `title`, `instruction`, `tips`, `video_type`, `video_url`, `bodypart_ids`, `duration`, `based`, `type`, `equipment_id`, `level_id`, `sets`, `status`, `is_premium`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Bar Pushups', NULL, NULL, 'url', NULL, '[\"2\"]', '00:05:00', NULL, 'duration', NULL, 1, NULL, 'active', 0, '2024-03-09 23:56:02', '2024-03-09 23:56:02', NULL),
(2, 'Sprint', NULL, NULL, 'url', NULL, '[\"2\"]', NULL, 'time', 'sets', NULL, 1, '[{\"reps\":\"10\",\"weight\":\"3\",\"rest\":\"33\",\"time\":\"20\"},{\"reps\":\"10\",\"weight\":\"3\",\"rest\":\"33\",\"time\":\"20\"}]', 'active', 0, '2024-03-09 23:57:45', '2024-03-09 23:57:45', NULL),
(3, 'Chest Up', NULL, NULL, 'url', NULL, '[\"1\"]', NULL, 'reps', 'sets', NULL, 1, '[{\"reps\":\"11\",\"weight\":\"33\",\"rest\":\"44\",\"time\":null}]', 'active', 0, '2024-03-09 23:59:27', '2024-03-10 00:03:18', NULL);

INSERT INTO `levels` (`id`, `title`, `rate`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Beginner', 4, 'active', '2024-03-09 23:51:52', '2024-03-09 23:51:52'),
(2, 'Intermediate', 6, 'active', '2024-03-09 23:52:09', '2024-03-09 23:52:09'),
(3, 'Advance', 8, 'active', '2024-03-09 23:52:23', '2024-03-09 23:52:23'),
(4, 'Challenge', 10, 'active', '2024-03-09 23:52:36', '2024-03-09 23:52:36');

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\AppSetting', 1, 'd62c7648-fce6-4ae8-887c-e070a4d012c0', 'site_logo', 'fitness_logo_text', 'fitness_logo_text.png', 'image/png', 'public', 'public', 9529, '[]', '[]', '[]', '[]', 1, '2024-03-10 14:57:33', '2024-03-10 14:57:33'),
(2, 'App\\Models\\AppSetting', 1, 'd48be3b6-a5a2-4e45-bfce-9c7834391020', 'site_dark_logo', 'fitness_text_for_dark', 'fitness_text_for_dark.png', 'image/png', 'public', 'public', 9278, '[]', '[]', '[]', '[]', 2, '2024-03-10 14:57:33', '2024-03-10 14:57:33'),
(3, 'App\\Models\\AppSetting', 1, 'd51cd6e3-f45b-4871-a76f-66c9c121d94d', 'site_mini_logo', 'fitness_center-220x140', 'fitness_center-220x140.png', 'image/png', 'public', 'public', 9097, '[]', '[]', '[]', '[]', 3, '2024-03-10 14:57:33', '2024-03-10 14:57:33'),
(4, 'App\\Models\\AppSetting', 1, 'febeb98a-4330-494d-8f11-bf643d633090', 'site_dark_mini_logo', 'fitness_center-220x140', 'fitness_center-220x140.png', 'image/png', 'public', 'public', 9097, '[]', '[]', '[]', '[]', 4, '2024-03-10 14:57:33', '2024-03-10 14:57:33'),
(5, 'App\\Models\\AppSetting', 1, '151ef605-86c0-49fe-a0b2-99ca04590f95', 'site_favicon', 'fitness_center-220x140', 'fitness_center-220x140.png', 'image/png', 'public', 'public', 9097, '[]', '[]', '[]', '[]', 5, '2024-03-10 14:57:33', '2024-03-10 14:57:33');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_11_09_064224_create_user_profiles_table', 1),
(6, '2021_11_11_110731_create_permission_tables', 1),
(7, '2021_11_16_114009_create_media_table', 1),
(8, '2023_04_04_122206_create_equipment_table', 1),
(9, '2023_04_07_094031_create_workout_types_table', 1),
(10, '2023_04_07_114407_create_category_diets_table', 1),
(11, '2023_04_08_065211_create_diets_table', 1),
(12, '2023_04_12_051628_create_categories_table', 1),
(13, '2023_04_12_104633_create_levels_table', 1),
(14, '2023_04_13_092859_create_tags_table', 1),
(15, '2023_04_13_101848_create_app_settings_table', 1),
(16, '2023_04_13_124827_create_settings_table', 1),
(17, '2023_04_17_104726_create_body_parts_table', 1),
(18, '2023_04_17_111217_create_exercises_table', 1),
(19, '2023_04_17_115034_create_workouts_table', 1),
(20, '2023_04_21_052358_create_workout_days_table', 1),
(21, '2023_04_21_071141_create_workout_day_exercises_table', 1),
(22, '2023_04_22_042750_create_posts_table', 1),
(23, '2023_04_22_082012_create_user_favourite_diets_table', 1),
(24, '2023_05_01_105045_create_user_favourite_workouts_table', 1),
(25, '2023_05_05_062357_create_product_categories_table', 1),
(26, '2023_05_05_062432_create_products_table', 1),
(27, '2023_05_09_042923_create_assign_diets_table', 1),
(28, '2023_05_12_065812_create_assign_workouts_table', 1),
(29, '2023_07_08_063653_create_user_graphs_table', 1),
(30, '2023_08_18_101137_create_payment_gateways_table', 1),
(31, '2023_08_19_090449_create_notifications_table', 1),
(32, '2023_08_19_090739_create_push_notifications_table', 1),
(33, '2023_08_23_110759_create_packages_table', 1),
(34, '2023_08_26_043829_create_subscriptions_table', 1),
(35, '2023_10_14_065617_create_quotes_table', 1),
(36, '2024_03_10_132717_add_user_id_column_to_multiple_tables', 2);

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

INSERT INTO `permissions` (`id`, `name`, `title`, `guard_name`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'role', 'Role', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(2, 'role-add', 'Role Add', 'web', 1, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(3, 'role-list', 'Role List', 'web', 1, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(4, 'permission', 'Permission', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(5, 'permission-add', 'Permission Add', 'web', 4, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(6, 'permission-list', 'Permission List', 'web', 4, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(7, 'user', 'User', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(8, 'user-list', 'User List', 'web', 7, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(9, 'user-add', 'User Add', 'web', 7, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(10, 'user-edit', 'User Edit', 'web', 7, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(11, 'user-delete', 'User Delete', 'web', 7, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(12, 'user-show', 'User Show', 'web', 7, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(13, 'equipment', 'Equipment', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(14, 'equipment-list', 'Equipment List', 'web', 13, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(15, 'equipment-add', 'Equipment Add', 'web', 13, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(16, 'equipment-edit', 'Equipment Edit', 'web', 13, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(17, 'equipment-delete', 'Equipment Delete', 'web', 13, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(18, 'categorydiet', 'Categorydiet', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(19, 'categorydiet-list', 'Categorydiet List', 'web', 18, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(20, 'categorydiet-add', 'Categorydiet Add', 'web', 18, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(21, 'categorydiet-edit', 'Categorydiet Edit', 'web', 18, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(22, 'categorydiet-delete', 'Categorydiet Delete', 'web', 18, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(23, 'workouttype', 'Workouttype', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(24, 'workouttype-list', 'Workouttype List', 'web', 23, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(25, 'workouttype-add', 'Workouttype Add', 'web', 23, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(26, 'workouttype-edit', 'Workouttype Edit', 'web', 23, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(27, 'workouttype-delete', 'Workouttype Delete', 'web', 23, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(28, 'diet', 'Diet', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(29, 'diet-list', 'Diet List', 'web', 28, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(30, 'diet-add', 'Diet Add', 'web', 28, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(31, 'diet-edit', 'Diet Edit', 'web', 28, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(32, 'diet-delete', 'Diet Delete', 'web', 28, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(33, 'level', 'Level', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(34, 'level-list', 'Level List', 'web', 33, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(35, 'level-add', 'Level Add', 'web', 33, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(36, 'level-edit', 'Level Edit', 'web', 33, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(37, 'level-delete', 'Level Delete', 'web', 33, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(38, 'bodyparts', 'Bodyparts', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(39, 'bodyparts-list', 'Bodyparts List', 'web', 38, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(40, 'bodyparts-add', 'Bodyparts Add', 'web', 38, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(41, 'bodyparts-edit', 'Bodyparts Edit', 'web', 38, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(42, 'bodyparts-delete', 'Bodyparts Delete', 'web', 38, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(43, 'category', 'Category', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(44, 'category-list', 'Category List', 'web', 43, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(45, 'category-add', 'Category Add', 'web', 43, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(46, 'category-edit', 'Category Edit', 'web', 43, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(47, 'category-delete', 'Category Delete', 'web', 43, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(48, 'tags', 'Tags', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(49, 'tags-list', 'Tags List', 'web', 48, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(50, 'tags-add', 'Tags Add', 'web', 48, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(51, 'tags-edit', 'Tags Edit', 'web', 48, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(52, 'tags-delete', 'Tags Delete', 'web', 48, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(53, 'exercise', 'Exercise', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(54, 'exercise-list', 'Exercise List', 'web', 53, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(55, 'exercise-add', 'Exercise Add', 'web', 53, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(56, 'exercise-edit', 'Exercise Edit', 'web', 53, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(57, 'exercise-delete', 'Exercise Delete', 'web', 53, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(58, 'workout', 'Workout', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(59, 'workout-list', 'Workout List', 'web', 58, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(60, 'workout-add', 'Workout Add', 'web', 58, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(61, 'workout-edit', 'Workout Edit', 'web', 58, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(62, 'workout-delete', 'Workout Delete', 'web', 58, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(63, 'pages', 'Pages', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(64, 'terms-condition', 'Terms Condition', 'web', 63, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(65, 'privacy-policy', 'Privacy Policy', 'web', 63, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(66, 'post', 'Post', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(67, 'post-list', 'Post List', 'web', 66, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(68, 'post-add', 'Post Add', 'web', 66, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(69, 'post-edit', 'Post Edit', 'web', 66, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(70, 'post-delete', 'Post Delete', 'web', 66, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(71, 'productcategory', 'Productcategory', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(72, 'productcategory-list', 'Productcategory List', 'web', 71, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(73, 'productcategory-add', 'Productcategory Add', 'web', 71, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(74, 'productcategory-edit', 'Productcategory Edit', 'web', 71, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(75, 'productcategory-delete', 'Productcategory Delete', 'web', 71, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(76, 'product', 'Product', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(77, 'product-list', 'Product List', 'web', 76, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(78, 'product-add', 'Product Add', 'web', 76, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(79, 'product-edit', 'Product Edit', 'web', 76, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(80, 'product-delete', 'Product Delete', 'web', 76, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(81, 'package', 'Package', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(82, 'package-list', 'Package List', 'web', 81, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(83, 'package-add', 'Package Add', 'web', 81, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(84, 'package-edit', 'Package Edit', 'web', 81, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(85, 'package-delete', 'Package Delete', 'web', 81, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(86, 'pushnotification', 'Pushnotification', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(87, 'pushnotification-list', 'Pushnotification List', 'web', 86, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(88, 'pushnotification-add', 'Pushnotification Add', 'web', 86, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(89, 'pushnotification-delete', 'Pushnotification Delete', 'web', 86, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(90, 'subscription', 'Subscription', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(91, 'subscription-list', 'Subscription List', 'web', 90, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(92, 'quotes', 'Quotes', 'web', NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(93, 'quotes-list', 'Quotes List', 'web', 92, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(94, 'quotes-add', 'Quotes Add', 'web', 92, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(95, 'quotes-edit', 'Quotes Edit', 'web', 92, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(96, 'quotes-delete', 'Quotes Delete', 'web', 92, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(97, 'subscription-add', 'Subscription Add', 'web', 90, '2024-03-09 21:54:55', '2024-03-09 21:54:55');

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(17, 1),
(17, 2),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(24, 2),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(39, 2),
(40, 1),
(40, 2),
(41, 1),
(41, 2),
(42, 1),
(42, 2),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(54, 2),
(55, 1),
(55, 2),
(56, 1),
(56, 2),
(57, 1),
(57, 2),
(58, 1),
(59, 1),
(59, 2),
(60, 1),
(60, 2),
(61, 1),
(61, 2),
(62, 1),
(62, 2),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1);

INSERT INTO `roles` (`id`, `name`, `title`, `guard_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', 'web', 1, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(2, 'user', 'User', 'web', 1, '2024-03-09 21:54:55', '2024-03-09 21:54:55');

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `email`, `phone_number`, `email_verified_at`, `user_type`, `password`, `status`, `login_type`, `gender`, `display_name`, `player_id`, `is_subscribe`, `last_notification_seen`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'systemadmin', 'System', 'Admin', 'admin@admin.com', NULL, '2024-03-09 21:54:55', 'admin', '$2y$10$b7AaU46xL2pbLDcY70FurOsXVvCEKnlUEbsBIxnlp6D7Fq.uxKn8i', 'active', NULL, NULL, 'System Admin', NULL, 0, NULL, NULL, '2024-03-09 21:54:55', '2024-03-09 21:54:55'),
(2, 'jawadashraf', 'Jawad', 'Ashraf', 'jawadashraf78@gmail.com', '07438356414', NULL, 'user', '$2y$10$gzrwsuMyt5rPxnFQ2UMiHeNx9Gf43ESJmBn3MEuMTd6WAqdeLgDnS', 'active', NULL, NULL, 'JA', NULL, 0, NULL, NULL, '2024-03-09 22:01:57', '2024-03-09 22:01:57');

INSERT INTO `workout_day_exercises` (`id`, `workout_id`, `workout_day_id`, `exercise_id`, `sets`, `sequence`, `duration`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 0, NULL, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(2, 1, 1, 2, NULL, 1, NULL, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(3, 1, 3, 3, NULL, 0, NULL, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(4, 1, 5, 1, NULL, 0, NULL, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(5, 1, 5, 2, NULL, 1, NULL, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(6, 1, 8, 1, NULL, 0, NULL, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(7, 2, 9, 1, NULL, 0, NULL, '2024-03-10 14:36:10', '2024-03-10 14:36:10'),
(8, 3, 10, 2, NULL, 0, NULL, '2024-03-10 16:36:06', '2024-03-10 16:36:06');

INSERT INTO `workout_days` (`id`, `workout_id`, `sequence`, `is_rest`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(2, 1, 1, 1, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(3, 1, 2, 0, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(4, 1, 3, 1, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(5, 1, 4, 0, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(6, 1, 5, 1, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(7, 1, 6, 1, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(8, 1, 7, 0, '2024-03-10 11:54:18', '2024-03-10 11:54:18'),
(9, 2, 0, 0, '2024-03-10 14:36:10', '2024-03-10 14:36:10'),
(10, 3, 0, 0, '2024-03-10 16:36:06', '2024-03-10 16:36:06'),
(11, 5, 0, 0, '2024-03-10 16:37:48', '2024-03-10 16:37:48'),
(12, 6, 0, 0, '2024-03-10 16:46:19', '2024-03-10 16:46:19');

INSERT INTO `workout_types` (`id`, `title`, `status`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Gym Workout', 'active', '2024-03-10 11:52:12', '2024-03-10 11:52:12', NULL),
(2, 'Home Workout', 'active', '2024-03-10 11:52:34', '2024-03-10 11:52:34', NULL),
(3, 'Cardio', 'active', '2024-03-10 16:53:00', '2024-03-10 16:53:00', 2);

INSERT INTO `workouts` (`id`, `title`, `description`, `level_id`, `workout_type_id`, `status`, `is_premium`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Upper Body 1', NULL, 2, 2, 'active', 0, '2024-03-10 11:54:18', '2024-03-10 11:54:18', NULL),
(2, 'My Workout', NULL, 1, 1, 'active', 0, '2024-03-10 14:36:10', '2024-03-10 14:36:10', 2),
(3, 'My Home Workout 1', NULL, 1, 2, 'active', 0, '2024-03-10 16:36:06', '2024-03-10 16:36:06', 2),
(4, 'My Home Workout 2', NULL, 2, 2, 'active', 0, '2024-03-10 16:36:52', '2024-03-10 16:36:52', 2),
(5, 'My Home Workout 3', NULL, 2, 2, 'active', 0, '2024-03-10 16:37:48', '2024-03-10 16:37:48', 2),
(6, 'My Home WO 4', NULL, 1, 2, 'active', 0, '2024-03-10 16:46:19', '2024-03-10 16:46:19', 2);



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;