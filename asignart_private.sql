ALTER TABLE `as_artwork_auctions`
    ADD `is_live`       BOOLEAN      NOT NULL DEFAULT FALSE AFTER `name`,
    ADD `is_online`     BOOLEAN      NOT NULL DEFAULT FALSE AFTER `is_live`,
    ADD `platform_name` VARCHAR(100) NULL AFTER `is_online`;
ALTER TABLE `as_artworks`
    ADD `location_id` INT NULL AFTER `location_details`;

CREATE TABLE `as_artwork_past_locations`
(
    `id`                  int(10) unsigned NOT NULL AUTO_INCREMENT,
    `artwork_id`          int(10) unsigned NOT NULL,
    `customer_address_id` int(11)          NOT NULL,
    `created_at`          timestamp        NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

update as_artworks
set location_id=JSON_UNQUOTE(json_extract(location_details, '$.customer_address_id'));


RENAME TABLE `as_artwork_view_requests` TO `as_artwork_private_view_requests`;

CREATE TABLE `as_settings`
(
    `id`     int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name`   varchar(100)     NOT NULL,
    `value`  longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`value`)),
    `status` tinyint(1)       NOT NULL                          DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

ALTER TABLE `as_artwork_publications`
    ADD `location` VARCHAR(200) NULL AFTER `author`;

ALTER TABLE `as_artworks`
    ADD `cart_status` ENUM ('auction','hold','offer','purchase','open','sold') NOT NULL DEFAULT 'open' AFTER `timeline_status`;

ALTER TABLE `as_customer_addresses`
    ADD `is_registered_address` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_default`;

update `as_customer_addresses`
set is_registered_address=1
where location_as = 'Registered Address';

ALTER TABLE `as_artwork_documents`
    ADD `name` VARCHAR(100) NULL AFTER `artwork_id`;

CREATE TABLE `as_artwork_extras`
(
    `id`                     int(11)          NOT NULL AUTO_INCREMENT,
    `artwork_id`             int(10) unsigned NOT NULL,
    `pricing_currency_id`    int(10) unsigned DEFAULT NULL,
    `cost_acquisition_price` decimal(20, 2)   DEFAULT NULL,
    `margin_addon`           decimal(10, 2)   DEFAULT NULL,
    `margin_addon_price`     decimal(20, 2)   DEFAULT NULL,
    `estimate_selling_price` decimal(20, 2)   DEFAULT NULL,
    `margin_offer`           decimal(10, 2)   DEFAULT NULL,
    `margin_offer_price`     decimal(20, 2)   DEFAULT NULL,
    `settlement_price`       decimal(20, 2)   DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `artwork_id` (`artwork_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;


ALTER TABLE `as_artworks`
    ADD `original_price` DECIMAL(20, 2) NULL AFTER `city`;


ALTER TABLE `as_artwork_insurances`
    ADD `customer_insurance_policy_id` INT UNSIGNED NULL AFTER `artwork_id`;

CREATE TABLE `as_customer_insurance_providers`
(
    `id`          int(11)          NOT NULL AUTO_INCREMENT,
    `customer_id` int(10) unsigned NOT NULL,
    `name`        varchar(200)     NOT NULL,
    `created_at`  timestamp        NOT NULL DEFAULT current_timestamp(),
    `deleted_at`  timestamp        NULL     DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

CREATE TABLE `as_customer_insurance_policies`
(
    `id`                             int(10) unsigned NOT NULL AUTO_INCREMENT,
    `customer_id`                    int(10) unsigned NOT NULL,
    `customer_insurance_provider_id` int(10) unsigned          DEFAULT NULL,
    `policy_document`                varchar(200)              DEFAULT NULL,
    `product_name`                   varchar(100)              DEFAULT NULL,
    `policy_no`                      varchar(30)               DEFAULT NULL,
    `insurance_type_id`              int(10) unsigned          DEFAULT NULL,
    `insurance_type_others`          varchar(200)              DEFAULT NULL,
    `coverage_type_id`               int(10) unsigned          DEFAULT NULL,
    `coverage_type_others`           varchar(200)              DEFAULT NULL,
    `holder_name`                    varchar(100)              DEFAULT NULL,
    `mobile_no`                      varchar(15)               DEFAULT NULL,
    `email`                          varchar(100)              DEFAULT NULL,
    `insurance_coverage_value`       decimal(20, 2)            DEFAULT NULL,
    `start_date`                     date                      DEFAULT NULL,
    `end_date`                       date                      DEFAULT NULL,
    `created_at`                     timestamp        NOT NULL DEFAULT current_timestamp(),
    `updated_at`                     timestamp        NULL     DEFAULT NULL ON UPDATE current_timestamp(),
    `deleted_at`                     timestamp        NULL     DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;


CREATE TABLE `as_customer_wishlists`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `customer_id` int(10) unsigned NOT NULL,
    `artwork_id`  int(10) unsigned NOT NULL,
    `created_at`  timestamp        NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

ALTER TABLE `as_customers`
    ADD `is_artwork_created` BOOLEAN NOT NULL DEFAULT FALSE AFTER `status`;

ALTER TABLE `as_object_types` CHANGE `gst_id` `gst_id` INT(11) NULL;


ALTER TABLE `as_customer_education`
    ADD `is_public` INT                                              NOT NULL AFTER `end_year`,
    ADD `status`    ENUM ('completed','in-progress','not-completed') NULL AFTER `is_public`;
ALTER TABLE `as_customer_education`
    CHANGE `is_public` `is_public` BOOLEAN NOT NULL DEFAULT TRUE;

ALTER TABLE `as_customers`
    ADD `establishment_year` VARCHAR(4) NULL AFTER `is_artwork_created`;
ALTER TABLE `as_customers`
    ADD `current_locations` VARCHAR(500) NULL AFTER `establishment_year`;
ALTER TABLE `as_customers`
    ADD `is_public` BOOLEAN NOT NULL DEFAULT FALSE AFTER `current_locations`;

ALTER TABLE `as_customer_media_mentions`
    ADD `is_public` BOOLEAN NOT NULL DEFAULT FALSE AFTER `date`;
ALTER TABLE `as_customer_awards`
    ADD `is_public` BOOLEAN NOT NULL DEFAULT FALSE AFTER `date`;
ALTER TABLE `as_artwork_insurances`
    ADD `loan_insured_value` DECIMAL(20, 2) NULL AFTER `loan_insurance_type_others`;

ALTER TABLE `as_customer_awards`
    ADD `day`   VARCHAR(2) NULL AFTER `is_public`,
    ADD `month` VARCHAR(2) NULL AFTER `day`,
    ADD `year`  VARCHAR(4) NULL AFTER `month`;
ALTER TABLE `as_customer_media_mentions`
    ADD `day`   VARCHAR(2) NULL AFTER `is_public`,
    ADD `month` VARCHAR(2) NULL AFTER `day`,
    ADD `year`  VARCHAR(4) NULL AFTER `month`;



ALTER TABLE `as_customer_publications`
    ADD `is_public` BOOLEAN NOT NULL DEFAULT FALSE AFTER `date`;
ALTER TABLE `as_customer_publications`
    ADD `day`   VARCHAR(2) NULL AFTER `is_public`,
    ADD `month` VARCHAR(2) NULL AFTER `day`,
    ADD `year`  VARCHAR(4) NULL AFTER `month`;
ALTER TABLE `as_customer_publications`
    ADD `location`       VARCHAR(300) NULL AFTER `image`,
    ADD `publisher_name` VARCHAR(300) NULL AFTER `location`,
    ADD `page_no`        VARCHAR(20)  NULL AFTER `publisher_name`;

ALTER TABLE `as_customer_media_mentions`
    ADD `image` VARCHAR(200) NULL AFTER `title`;
ALTER TABLE `as_customer_collections`
    ADD `is_public` BOOLEAN NULL DEFAULT FALSE AFTER `location`;

ALTER TABLE `as_customer_exhibitions`
    ADD `is_public` BOOLEAN NULL DEFAULT FALSE AFTER `exhibition_id`;
ALTER TABLE `as_exhibitions`
    ADD `start_day`   VARCHAR(2) NULL AFTER `status`,
    ADD `start_month` VARCHAR(2) NULL AFTER `start_day`,
    ADD `start_year`  VARCHAR(4) NULL AFTER `start_month`,
    ADD `end_day`     VARCHAR(2) NULL AFTER `start_year`,
    ADD `end_month`   VARCHAR(2) NULL AFTER `end_day`,
    ADD `end_year`    VARCHAR(4) NULL AFTER `end_month`;
ALTER TABLE `as_exhibitions`
    ADD `start_time` VARCHAR(10) NULL AFTER `end_year`,
    ADD `end_time`   VARCHAR(10) NULL AFTER `start_time`;
ALTER TABLE `as_exhibitions`
    ADD `time_zone_id` INT NULL AFTER `end_time`;

ALTER TABLE `as_customer_businesses`
    ADD `is_accept_terms` BOOLEAN NULL DEFAULT FALSE AFTER `is_verified`;
ALTER TABLE `as_customer_artists`
    ADD `is_accept_terms` BOOLEAN NULL DEFAULT FALSE AFTER `is_verified`;
ALTER TABLE `as_messages`
    ADD `is_accept_terms` BOOLEAN NULL AFTER `is_read`;

ALTER TABLE `as_messages`
    CHANGE `status` `status` ENUM ('new','accepted','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'new';
ALTER TABLE `as_users`
    ADD `hash_password` TEXT NULL AFTER `profile_image`;

CREATE TABLE `as_customer_notable_sales`
(
    `id`             int(10) unsigned NOT NULL AUTO_INCREMENT,
    `customer_id`    int(10) unsigned NOT NULL,
    `artist_id`      int(11)                   DEFAULT NULL,
    `image`          varchar(200)              DEFAULT NULL,
    `title`          varchar(300)              DEFAULT NULL,
    `year`           varchar(4)                DEFAULT NULL,
    `surface`        text                      DEFAULT NULL,
    `medium_other`   varchar(100)              DEFAULT NULL,
    `surface_other`  varchar(100)              DEFAULT NULL,
    `mediums`        text                      DEFAULT NULL,
    `dimension_size` enum ('in','cm')          DEFAULT NULL,
    `height`         varchar(12)               DEFAULT NULL,
    `width`          varchar(12)               DEFAULT NULL,
    `depth`          varchar(12)               DEFAULT NULL,
    `diameter`       varchar(12)               DEFAULT NULL,
    `weight_size`    enum ('kg','lbs')         DEFAULT NULL,
    `weight`         varchar(10)               DEFAULT NULL,
    `height_cm`      varchar(12)               DEFAULT NULL,
    `width_cm`       varchar(12)               DEFAULT NULL,
    `depth_cm`       varchar(12)               DEFAULT NULL,
    `diameter_cm`    varchar(12)               DEFAULT NULL,
    `is_public`      tinyint(1)       NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

ALTER TABLE `as_artwork_protect_requests`
    CHANGE `inspection_time` `inspection_time` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
ALTER TABLE `as_customer_artists`
    CHANGE `is_verified` `is_verified` TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE `as_customer_businesses`
    CHANGE `is_verified` `is_verified` TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE `as_customers`
    ADD `profile_completion` INT NOT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `as_customers`
    CHANGE `status` `status` ENUM ('pending','inactive','verified','unverified','moderation','paused') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending';

CREATE TABLE `as_customer_artwork_views`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `customer_id` int(10) unsigned NOT NULL,
    `artwork_id`  int(10) unsigned NOT NULL,
    `view_count`  int(11)          NOT NULL DEFAULT 1,
    `view_at`     timestamp        NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;


DROP TABLE IF EXISTS `as_surface_types`;
CREATE TABLE `as_surface_types` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(200) NOT NULL,
`type` varchar(100) NOT NULL,
`status` tinyint(1) NOT NULL DEFAULT 1,
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
`updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `as_surface_types` (`id`, `name`, `type`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Wood', 'Frame', 1, '2024-04-18 09:14:33', '2024-04-18 09:56:45'),
(4, 'Stainless Steel', 'Frame', 1, '2024-04-18 09:14:47', '2024-04-18 09:56:45'),
(5, 'Steel', 'Frame', 1, '2024-04-18 09:15:03', '2024-04-18 09:56:45'),
(6, 'Aluminum', 'Frame', 1, '2024-04-18 09:15:15', '2024-04-18 09:56:45'),
(7, 'Gold leaf or Gilded', 'Frame', 1, '2024-04-18 09:15:28', '2024-04-18 09:56:45'),
(8, 'Silver Leaf or Silvered', 'Frame', 1, '2024-04-18 09:15:42', '2024-04-18 09:56:45'),
(9, 'Plastic or Polystyrene', 'Frame', 1, '2024-04-18 09:15:56', '2024-04-18 09:56:45'),
(10, 'MDF (Medium Density Fiberboard)', 'Frame', 1, '2024-04-18 09:16:08', '2024-04-18 09:56:45'),
(11, 'Acrylic or Plexiglass', 'Frame', 1, '2024-04-18 09:16:24', '2024-04-18 09:56:45'),
(12, 'Other', 'Frame', 1, '2024-04-18 09:16:44', '2024-04-18 09:56:45'),
(13, 'Wood', 'Strecher', 1, '2024-04-18 09:23:42', '2024-04-18 09:57:04'),
(14, 'Other', 'Strecher', 1, '2024-04-18 09:24:09', '2024-04-18 09:57:04'),
(16, 'Wood', 'Object Stand', 1, '2024-04-18 09:25:23', '2024-04-18 09:57:21'),
(17, 'Stainless Steel', 'Object Stand', 1, '2024-04-18 09:25:35', '2024-04-18 09:57:21'),
(18, 'Steel', 'Object Stand', 1, '2024-04-18 09:25:52', '2024-04-18 09:57:21'),
(19, 'Aluminium', 'Object Stand', 1, '2024-04-18 09:26:02', '2024-04-18 09:57:21'),
(20, 'Gold leaf or Gilded', 'Object Stand', 1, '2024-04-18 09:26:15', '2024-04-18 09:57:21'),
(21, 'Silver Leaf or Silvered', 'Object Stand', 1, '2024-04-18 09:26:27', '2024-04-18 09:57:21'),
(22, 'Plastic or Polystyrene', 'Object Stand', 1, '2024-04-18 09:26:40', '2024-04-18 09:57:21'),
(23, 'MDF (Medium Density Fiberboard)', 'Object Stand', 1, '2024-04-18 09:26:52', '2024-04-18 09:57:21'),
(24, 'Acrylic or Plexiglass', 'Object Stand', 1, '2024-04-18 09:27:05', '2024-04-18 09:57:21'),
(25, 'Other', 'Object Stand', 1, '2024-04-18 09:27:27', '2024-04-18 09:57:21'),
(26, 'Frame Test', 'Frame', 1, '2024-04-30 07:29:47', '2024-04-30 07:29:47');


CREATE TABLE `as_artwork_protect_requests_inspections` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`artwork_protect_requests_id` int(11) NOT NULL,
`is_object_match_imageupload` tinyint(1) DEFAULT NULL,
`object_match_imageupload_reason` int(11) DEFAULT NULL,
`object_match_imageupload_reason_notes` longtext DEFAULT NULL,
`object_noticeable_damage_reason_images` longtext DEFAULT NULL,
`object_condition` int(11) DEFAULT NULL,
`is_object_noticeable_damages` tinyint(1) DEFAULT NULL,
`object_noticeable_damage_reason` int(11) DEFAULT NULL,
`object_noticeable_damage_reason_notes` longtext DEFAULT NULL,
`is_object_asignprotect_condition` tinyint(1) DEFAULT NULL,
`object_asignprotect_condition_reason` int(11) DEFAULT NULL,
`object_asignprotect_condition_reason_notes` longtext DEFAULT NULL,
`is_object_surface_suitable` tinyint(1) DEFAULT NULL,
`object_surface_type` varchar(100) DEFAULT NULL,
`object_material_frame` int(11) DEFAULT NULL,
`object_material_frame_notes` longtext DEFAULT NULL,
`object_material_objectstand` int(11) DEFAULT NULL,
`object_material_objectstand_notes` longtext DEFAULT NULL,
`object_material_stretcher` int(11) DEFAULT NULL,
`object_material_stretcher_notes` longtext DEFAULT NULL,
`object_label_images` longtext DEFAULT NULL,
`object_additional_notes` longtext DEFAULT NULL,
`object_surface_suitable_reason` int(11) DEFAULT NULL,
`object_surface_suitable_reason_notes` longtext DEFAULT NULL,
`object_surface_suitable_reason_images` longtext DEFAULT NULL,
`object_additional_reason_notes` longtext DEFAULT NULL,
`is_site_adequatephysical_taskcomplete` tinyint(1) DEFAULT NULL,
`site_adequatephysical_taskcomplete_reason` int(11) DEFAULT NULL,
`site_adequatephysical_taskcomplete_reason_notes` longtext DEFAULT NULL,
`is_site_adequatephysical_alternativespace` tinyint(1) DEFAULT NULL,
`site_adequatephysical_alternativespace_notes` longtext DEFAULT NULL,
`is_site_smoothworkflow` tinyint(1) DEFAULT NULL,
`site_smoothworkflow_reason` int(11) DEFAULT NULL,
`site_smoothworkflow_reason_notes` longtext DEFAULT NULL,
`site_entry_points` longtext DEFAULT NULL,
`site_exit_points` longtext DEFAULT NULL,
`is_site_lighting_adequate` tinyint(1) DEFAULT NULL,
`site_lighting_adequate_reason` int(11) DEFAULT NULL,
`site_lighting_adequate_reason_notes` longtext DEFAULT NULL,
`is_site_lighting_adequate_alternativespace` tinyint(1) DEFAULT NULL,
`site_lighting_adequate_alternativespace_notes` longtext DEFAULT NULL,
`is_site_surrounding_workspace` tinyint(1) DEFAULT NULL,
`site_surrounding_workspace_reason` int(11) DEFAULT NULL,
`site_surrounding_workspace_reason_notes` longtext DEFAULT NULL,
`is_site_surrounding_workspace_alternativespace` tinyint(1) DEFAULT NULL,
`site_surrounding_workspace_alternativespace_notes` longtext DEFAULT NULL,
`is_site_safety_protocols` tinyint(1) DEFAULT NULL,
`site_emergency_exit` longtext DEFAULT NULL,
`site_security_requirements` longtext DEFAULT NULL,
`site_observation_security` longtext DEFAULT NULL,
`is_site_washroom_available` tinyint(1) DEFAULT NULL,
`site_washroom_located_accessed` longtext DEFAULT NULL,
`site_washroom_located_nearest` longtext DEFAULT NULL,
`is_site_network_coverage` tinyint(1) DEFAULT NULL,
`site_alternate_available_network` longtext DEFAULT NULL,
`site_additional_notes` longtext DEFAULT NULL,
`is_site_condition_checked` tinyint(1) DEFAULT NULL,
`is_provenance_objective_verification` tinyint(1) DEFAULT NULL,
`is_provenance_art_upload` tinyint(1) DEFAULT NULL,
`provenance_object_number` varchar(20) DEFAULT NULL,
`is_provenance_confirm_object` tinyint(1) DEFAULT NULL,
`provenance_reason` varchar(100) DEFAULT NULL,
`provenance_additional_notes` longtext DEFAULT NULL,
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
`updated_at` timestamp NULL DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `artwork_protect_requests_id` (`artwork_protect_requests_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `as_artwork_protect_requests`
    ADD `approved_at` TIMESTAMP NULL AFTER `verify_status`;


ALTER TABLE `as_customer_activity_logs`
    ADD COLUMN `type`    ENUM ('register', 'artist', 'business') NOT NULL DEFAULT 'register',
    ADD COLUMN `type_id` INT;
ALTER TABLE as_customer_artists
    ADD COLUMN representation_accept_reject BOOLEAN DEFAULT NULL;
ALTER TABLE as_customer_artists
    ADD COLUMN representation_reject_id     INT          NULL,
    ADD COLUMN representation_reject_reason VARCHAR(255) NULL;


CREATE TABLE `as_represenation_rejected_reasons`
(
    `id`         bigint unsigned                         NOT NULL AUTO_INCREMENT,
    `name`       varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
    `status`     tinyint                                 NOT NULL DEFAULT '1',
    `created_at` timestamp                               NULL     DEFAULT NULL,
    `updated_at` timestamp                               NULL     DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

INSERT INTO `as_represenation_rejected_reasons` (`id`, `name`, `status`, `created_at`, `updated_at`)
VALUES (1, 'Gallery shut down', 1, NULL, NULL),
       (2, 'Information Incorrect', 1, NULL, NULL),
       (3, 'Other', 1, NULL, NULL);


ALTER TABLE as_customer_businesses
    ADD COLUMN representation_reject_id     INT          NULL,
    ADD COLUMN representation_reject_reason VARCHAR(255) NULL;

ALTER TABLE as_customer_businesses
    MODIFY COLUMN is_verified BOOLEAN NULL;


CREATE TABLE `as_authenticator_checklist_reasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `as_authenticator_checklist_reasons` (`id`, `name`, `status`, `created_at`, `updated_at`)
VALUES (3, 'Incomplete or missing information', 1, '2024-04-24 12:02:25', '2024-04-24 12:02:25'),
       (4, 'Unverifiable Ownership Chain', 1, '2024-04-24 12:02:40', '2024-04-24 12:02:40'),
       (5, 'Questionable Source', 1, '2024-04-24 12:02:56', '2024-04-24 12:02:56'),
       (6, 'Inconsistencies in Details', 1, '2024-04-24 12:03:13', '2024-04-24 12:03:13'),
       (7, 'Absence from Catalog Raisonne', 1, '2024-04-24 12:03:36', '2024-04-24 12:03:36'),
       (8, 'Use of Unaccepted Materials', 1, '2024-04-24 12:04:05', '2024-04-24 12:04:05'),
       (9, 'Unconfirmed Exhibition History', 1, '2024-04-24 12:04:21', '2024-04-24 12:04:21'),
       (10, 'Legal Ambiguities', 1, '2024-04-24 12:04:36', '2024-04-24 12:04:36'),
       (11, 'Other', 1, '2024-04-24 12:04:51', '2024-04-24 13:24:07');

ALTER TABLE `as_artwork_publications` ADD `day` VARCHAR(2) NULL AFTER `published_by`, ADD `month` VARCHAR(2) NULL AFTER `day`, ADD `year` VARCHAR(4) NULL AFTER `month`;

ALTER TABLE `as_customers` CHANGE `status` `status` ENUM('pending','inactive','verified','unverified','moderation','paused') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unverified';

UPDATE `as_customers` set STATUS='unverified' where status='pending';

ALTER TABLE `as_exhibitions` ADD `category_id` INT NULL AFTER `time_zone_id`;


CREATE TABLE `as_site_conditions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` enum('physical_space','site_accessible','lighting_adequate','surrounding_work_space') NOT NULL,
  `answer_type` enum('yes','no') NOT NULL,
  `name` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `as_site_conditions` (`id`, `question`, `answer_type`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'physical_space', 'no', 'Limited Space', 1, '2024-04-26 09:39:50', '2024-04-26 09:39:50', NULL),
(3, 'physical_space', 'no', 'Lack of Proper Lighting', 1, '2024-04-26 09:40:11', '2024-04-26 09:40:11', NULL),
(4, 'physical_space', 'no', 'Poor Ventilation', 1, '2024-04-26 09:40:26', '2024-04-26 09:40:26', NULL),
(5, 'physical_space', 'no', 'Safety Hazards', 1, '2024-04-26 09:40:46', '2024-04-26 09:40:46', NULL),
(6, 'physical_space', 'no', 'Limited Accessibility', 1, '2024-04-26 09:41:03', '2024-04-26 09:46:43', NULL),
(7, 'physical_space', 'no', 'Temperature Extremes', 1, '2024-04-26 09:41:20', '2024-04-26 09:41:20', NULL),
(8, 'physical_space', 'no', 'Other', 1, '2024-04-26 09:41:36', '2024-04-26 09:41:36', NULL),
(9, 'site_accessible', 'no', 'TEST', 1, '2024-04-26 09:42:05', '2024-04-30 07:44:51', NULL),
(10, 'lighting_adequate', 'no', 'Insufficient Brightness', 1, '2024-04-26 09:42:28', '2024-04-29 05:08:14', NULL),
(11, 'lighting_adequate', 'no', 'Inadequate lighting', 1, '2024-04-26 09:42:45', '2024-04-26 09:42:45', NULL),
(12, 'lighting_adequate', 'no', 'Flickering Lights', 1, '2024-04-26 09:43:02', '2024-04-26 09:43:02', NULL),
(13, 'lighting_adequate', 'no', 'Other', 1, '2024-04-26 09:43:21', '2024-04-29 05:08:13', NULL),
(14, 'surrounding_work_space', 'no', 'Humidity', 1, '2024-04-26 09:44:09', '2024-04-26 09:44:09', NULL),
(15, 'surrounding_work_space', 'no', 'Extreme temperatures', 1, '2024-04-26 09:44:25', '2024-04-26 09:44:25', NULL),
(16, 'surrounding_work_space', 'no', 'Pests and Vermin', 1, '2024-04-26 09:44:44', '2024-04-26 09:44:44', NULL),
(17, 'surrounding_work_space', 'no', 'Proximity to sources of water', 1, '2024-04-26 09:45:02', '2024-04-26 09:45:02', NULL),
(18, 'surrounding_work_space', 'no', 'Dust and Debris', 1, '2024-04-26 09:45:17', '2024-04-26 09:45:17', NULL),
(19, 'surrounding_work_space', 'no', 'Lighting Conditions', 1, '2024-04-26 09:45:45', '2024-04-26 09:45:45', NULL),
(20, 'surrounding_work_space', 'no', 'Security Concerns', 1, '2024-04-26 09:46:08', '2024-04-26 09:46:08', NULL),
(21, 'surrounding_work_space', 'no', 'Others', 1, '2024-04-26 09:46:25', '2024-04-26 09:46:25', NULL),
(22, 'site_accessible', 'no', 'Other', 1, '2024-04-26 09:42:05', '2024-04-28 12:17:01', NULL);


CREATE TABLE `as_object_conditions` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`question` enum('object_match','damage','asign_protect+','surface_condition') NOT NULL,
`answer_type` enum('yes','no') NOT NULL,
`name` varchar(200) NOT NULL,
`status` tinyint(1) NOT NULL DEFAULT 1,
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
`deleted_at` timestamp NULL DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `as_object_conditions` (`id`, `question`, `answer_type`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'object_match', 'yes', 'Mint', 1, '2024-04-18 09:09:42', '2024-04-18 09:09:42', NULL),
(4, 'object_match', 'yes', 'Excellent', 1, '2024-04-18 09:09:53', '2024-04-18 09:09:53', NULL),
(5, 'object_match', 'yes', 'Good', 1, '2024-04-18 09:10:05', '2024-04-18 09:10:05', NULL),
(6, 'object_match', 'yes', 'Fair', 1, '2024-04-18 09:10:16', '2024-04-18 09:10:16', NULL),
(7, 'object_match', 'yes', 'Poor', 1, '2024-04-18 09:10:29', '2024-04-18 09:10:29', NULL),
(8, 'object_match', 'yes', 'Needs restoration', 1, '2024-04-18 09:12:58', '2024-04-18 09:12:58', NULL),
(9, 'object_match', 'yes', 'Restored', 1, '2024-04-18 09:13:10', '2024-04-18 09:13:10', NULL),
(10, 'object_match', 'yes', 'Framed', 1, '2024-04-18 09:13:23', '2024-04-18 09:13:23', NULL),
(11, 'object_match', 'yes', 'Unframed', 1, '2024-04-18 09:13:38', '2024-04-18 09:13:38', NULL),
(12, 'object_match', 'no', 'A', 1, '2024-04-26 08:56:06', '2024-04-26 08:56:16', NULL),
(13, 'object_match', 'no', 'B', 1, '2024-04-26 08:56:35', '2024-04-26 08:56:35', NULL),
(14, 'object_match', 'no', 'C', 1, '2024-04-26 08:56:47', '2024-04-26 08:56:54', NULL),
(15, 'object_match', 'no', 'Other', 1, '2024-04-26 08:57:10', '2024-04-26 08:57:10', NULL),
(16, 'damage', 'yes', 'Surface scratches', 1, '2024-04-26 08:58:26', '2024-04-26 08:58:26', NULL),
(17, 'damage', 'yes', 'Scuffs', 1, '2024-04-26 08:58:47', '2024-04-26 08:58:47', NULL),
(18, 'damage', 'yes', 'Cracks', 1, '2024-04-26 08:59:05', '2024-04-26 08:59:05', NULL),
(19, 'damage', 'yes', 'Tears', 1, '2024-04-26 08:59:23', '2024-04-26 08:59:23', NULL),
(20, 'damage', 'yes', 'Warps or bends', 1, '2024-04-26 08:59:40', '2024-04-26 08:59:40', NULL),
(21, 'damage', 'yes', 'Stains or discoloration', 1, '2024-04-26 08:59:59', '2024-04-26 08:59:59', NULL),
(22, 'damage', 'yes', 'Insect damage (specify type if known)', 1, '2024-04-26 09:00:18', '2024-04-26 09:00:18', NULL),
(23, 'damage', 'yes', 'Mould or mildew', 1, '2024-04-26 09:00:42', '2024-04-26 09:00:42', NULL),
(24, 'damage', 'no', 'Loose or missing elements (frames, mounts, embellishments)', 1, '2024-04-26 09:01:10', '2024-04-26 09:01:10', NULL),
(25, 'damage', 'yes', 'Structural issues', 1, '2024-04-26 09:01:40', '2024-04-26 09:01:40', NULL),
(26, 'damage', 'yes', 'Previous repairs (describe)', 1, '2024-04-26 09:01:57', '2024-04-26 09:01:57', NULL),
(27, 'damage', 'no', 'Fading or changes in colour', 1, '2024-04-26 09:02:13', '2024-04-26 09:02:13', NULL),
(28, 'damage', 'yes', 'Yellowing (for works on paper)', 1, '2024-04-26 09:02:31', '2024-04-26 09:02:31', NULL),
(29, 'damage', 'yes', 'Other', 1, '2024-04-26 09:02:47', '2024-04-26 09:02:47', NULL),
(30, 'asign_protect+', 'no', 'Fragile or Sensitive Artwork Condition', 1, '2024-04-26 09:03:13', '2024-04-26 09:03:13', NULL),
(31, 'asign_protect+', 'no', 'Unsuitable Material', 1, '2024-04-26 09:03:31', '2024-04-26 09:03:31', NULL),
(32, 'asign_protect+', 'no', 'Unstable Material', 1, '2024-04-26 09:03:54', '2024-04-26 09:03:54', NULL),
(33, 'damage', 'no', 'No space to place labels', 1, '2024-04-26 09:04:11', '2024-04-26 09:04:11', NULL),
(34, 'asign_protect+', 'no', 'Conservation Concerns', 1, '2024-04-26 09:04:28', '2024-04-26 09:04:28', NULL),
(35, 'asign_protect+', 'no', 'Delicate Artwork', 1, '2024-04-26 09:04:43', '2024-04-26 09:04:43', NULL),
(36, 'asign_protect+', 'no', 'Artwork Size', 1, '2024-04-26 09:05:01', '2024-04-26 09:05:01', NULL),
(37, 'asign_protect+', 'no', 'Dust', 1, '2024-04-26 09:05:16', '2024-04-26 09:05:16', NULL),
(38, 'asign_protect+', 'no', 'Other', 1, '2024-04-26 09:05:30', '2024-04-26 09:05:30', NULL),
(39, 'surface_condition', 'no', 'Fragile or Sensitive Artwork Condition', 1, '2024-04-28 03:45:34', '2024-04-28 03:45:34', NULL),
(40, 'surface_condition', 'no', 'Unsuitable Material', 1, '2024-04-28 03:45:53', '2024-04-28 03:45:53', NULL),
(41, 'surface_condition', 'no', 'Unstable Material', 1, '2024-04-28 03:46:22', '2024-04-28 03:46:22', NULL),
(42, 'surface_condition', 'no', 'No space to place labels', 1, '2024-04-28 03:46:22', '2024-04-28 03:46:22', NULL),
(43, 'surface_condition', 'no', 'Conservation Concerns', 1, '2024-04-28 03:46:44', '2024-04-28 03:46:44', NULL),
(44, 'surface_condition', 'no', 'Delicate Artwork', 1, '2024-04-28 03:46:44', '2024-04-28 03:46:44', NULL),
(45, 'surface_condition', 'no', 'Artwork Size', 1, '2024-04-28 03:47:03', '2024-04-28 03:47:03', NULL),
(46, 'surface_condition', 'no', 'Dust', 1, '2024-04-28 03:47:03', '2024-04-28 03:47:03', NULL),
(47, 'surface_condition', 'no', 'Other', 1, '2024-04-28 03:47:15', '2024-04-28 03:47:15', NULL),
(48, 'object_match', 'no', 'D', 1, '2024-04-29 12:08:27', '2024-04-29 12:09:30', NULL),
(49, 'object_match', 'no', 'E', 1, '2024-04-30 07:13:42', '2024-04-30 07:13:42', NULL),
(50, 'object_match', 'yes', 'Test', 1, '2024-04-30 07:18:38', '2024-04-30 07:18:38', NULL),
(51, 'damage', 'yes', 'Test1', 1, '2024-04-30 07:20:28', '2024-04-30 07:20:28', NULL);


ALTER TABLE `as_artwork_valuations` ADD `valuation_value_type_others` VARCHAR(100) NULL AFTER `valuation_value_type_id`;


ALTER TABLE `as_artworks` CHANGE `subject_id` `subject_id` VARCHAR(200) NULL DEFAULT NULL;


ALTER TABLE `as_customers` CHANGE `status` `status` ENUM('pending','inactive','verified','unverified','moderation','paused','review') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unverified';

ALTER TABLE `as_customers` ADD `is_represent_contract` BOOLEAN NULL AFTER `accept_terms_date`;


ALTER TABLE `as_artwork_protect_requests` ADD `reference_img_url` LONGTEXT NULL AFTER `updated_at`, ADD `object_img_url` LONGTEXT NULL AFTER `reference_img_url`, ADD `matching_percentage` INT NULL AFTER `object_img_url`, ADD `current_step` ENUM('preview','object_match','inventory_label','auth_label','inventory_label_child','auth_label_child','edit_uploaded_image')  NULL DEFAULT 'preview' AFTER `matching_percentage`, ADD `inventory_label` JSON NULL AFTER `current_step`, ADD `auth_label` JSON NULL AFTER `inventory_label`;


CREATE TABLE `as_label_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transfer_no` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `source_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `reason_id` int(11) NOT NULL,
  `reason_others` varchar(500) DEFAULT NULL,
  `shipping_date` date DEFAULT NULL,
  `tracking_id` varchar(100) DEFAULT NULL,
  `status` enum('ordered','transit','fulfilled','packed') NOT NULL DEFAULT 'ordered',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transfer_no` (`transfer_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `as_label_transfer_details` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `label_transfer_id` int(11) NOT NULL,
     `product_id` int(11) NOT NULL,
     `qty` int(11) NOT NULL,
     PRIMARY KEY (`id`),
     KEY `label_transfer_id` (`label_transfer_id`),
     CONSTRAINT `as_label_transfer_details_ibfk_1` FOREIGN KEY (`label_transfer_id`) REFERENCES `as_label_transfers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `as_label_voids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `void_reason_id` int(11) NOT NULL,
  `envelope_code` varchar(100) DEFAULT NULL,
  `label_code` varchar(100) NOT NULL,
  `location_id` int(11) NOT NULL,
  `artwork_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `void_remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `as_void_reasons` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(200) NOT NULL,
   `status` tinyint(1) NOT NULL DEFAULT 1,
   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
   PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--  LabelProductDetailsAfterUpdate trigger update

ALTER TABLE `as_customer_notable_sales` ADD `unknown_artist` VARCHAR(200) NULL AFTER `artist_id`;
ALTER TABLE `as_customer_notable_sales` ADD `is_unknown_artist` BOOLEAN NULL AFTER `artist_id`;



UPDATE as_customers
set STATUS            ="verified",
    profile_completion=100
WHERE account_type = "collector"
  AND register_as = "individual"
  AND is_aadhaar_verify = 1
  AND is_pan_verify = 1
  AND is_accept_terms = 1
  AND is_mobile_verified IS NOT null
  AND is_email_verified IS NOT null;

UPDATE as_customers
set STATUS            ="unverified",
    profile_completion=0
WHERE account_type = "collector"
  AND register_as = "individual"
  AND (is_aadhaar_verify = 0 OR is_pan_verify = 0 OR is_accept_terms = 0 OR is_mobile_verified IS NULL OR
       is_email_verified IS NULL);

UPDATE as_customers
set STATUS            ="verified",
    profile_completion=100
WHERE account_type = "collector"
  AND register_as = "company"
  and company_type != "Private Limited"
  AND is_pan_verify = 1
  AND is_accept_terms = 1
  AND gst_no IS NOT null
  AND is_mobile_verified IS NOT null
  AND is_email_verified IS NOT null;

UPDATE as_customers
set STATUS            ="unverified",
    profile_completion=0
WHERE account_type = "collector"
  AND register_as = "company"
  and company_type != "Private Limited"
  AND (is_pan_verify = 0 OR is_accept_terms = 0 OR gst_no IS NULL OR is_mobile_verified IS NULL OR
       is_email_verified IS NULL);

UPDATE as_customers
set STATUS            ="verified",
    profile_completion=100
WHERE account_type = "collector"
  AND register_as = "company"
  and company_type = "Private Limited"
  AND is_pan_verify = 1
  AND is_accept_terms = 1
  AND cin_no IS NOT null
  AND gst_no IS NOT null
  AND is_mobile_verified IS NOT null
  AND is_email_verified IS NOT null;

UPDATE as_customers
set STATUS            ="unverified",
    profile_completion=0
WHERE account_type = "collector"
  AND register_as = "company"
  and company_type = "Private Limited"
  AND (is_pan_verify = 0 OR is_accept_terms = 0 OR cin_no IS NULL OR gst_no IS NULL OR is_mobile_verified IS NULL OR
       is_email_verified IS NULL);


CREATE TABLE `as_temp_images` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `request_id` bigint NOT NULL,
  `current_step` enum('inventory_label','auth_label','inventory_label_child','auth_label_child') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `img_url` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `child_no` int NOT NULL DEFAULT '0',
  `status` enum('temp','moved','current','marked') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `as_artwork_protect_requests` ADD `child_labels` JSON NULL AFTER `auth_label`, ADD `child_step` INT NULL AFTER `child_labels`, ADD `child_direction` ENUM('prev','next') NULL DEFAULT NULL AFTER `child_step`;
ALTER TABLE `as_exhibitions` ADD `category_others` VARCHAR(200) NULL AFTER `category_id`;

-- above queries added to live also


-- Need to add live

ALTER TABLE `as_customer_addresses` ADD `deleted_at` TIMESTAMP NULL AFTER `is_registered_address`;
ALTER TABLE `as_customer_education` ADD `deleted_at` TIMESTAMP NULL AFTER `status`;
ALTER TABLE `as_customer_exhibitions` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;
ALTER TABLE `as_customer_awards` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;
ALTER TABLE `as_customer_media_mentions` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;
ALTER TABLE `as_customer_collections` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;
ALTER TABLE `as_customer_notable_sales` ADD `deleted_at` TIMESTAMP NULL AFTER `is_public`;
ALTER TABLE `as_customer_publications` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;

ALTER TABLE `as_artwork_components` ADD `deleted_at` TIMESTAMP NULL AFTER `verifier_id`;
ALTER TABLE `as_artwork_condition_reports` ADD `deleted_at` TIMESTAMP NULL AFTER `document`;
ALTER TABLE `as_artwork_documents` ADD `deleted_at` TIMESTAMP NULL AFTER `document`;
ALTER TABLE `as_artwork_insurances` ADD `deleted_at` TIMESTAMP NULL AFTER `shipped_by`;
ALTER TABLE `as_artwork_measurements` ADD `deleted_at` TIMESTAMP NULL AFTER `weight`;
ALTER TABLE `as_artwork_media` ADD `deleted_at` TIMESTAMP NULL AFTER `detail`;
ALTER TABLE `as_artwork_provenances` ADD `deleted_at` TIMESTAMP NULL AFTER `provenance`;
ALTER TABLE `as_artwork_auctions` ADD `deleted_at` TIMESTAMP NULL AFTER `location`;
ALTER TABLE `as_artwork_exhibitions` ADD `deleted_at` TIMESTAMP NULL AFTER `exhibition_id`;
ALTER TABLE `as_artwork_publications` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;
ALTER TABLE `as_artwork_valuations` ADD `deleted_at` TIMESTAMP NULL AFTER `comparable_notes`;
ALTER TABLE `as_artwork_location_histories` ADD `deleted_at` TIMESTAMP NULL AFTER `pin_code`;

