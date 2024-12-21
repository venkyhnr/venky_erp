02-12-2024
----------------------
ALTER TABLE `user_permissions` ADD `add_perm` VARCHAR(255) NOT NULL AFTER `permission`;
ALTER TABLE `user_permissions` ADD `delete_perm` VARCHAR(255) NOT NULL AFTER `editperm`;

10-12-2024
-------------------------

DELETE FROM `agent_type` WHERE `agent_type`.`id` = 2
ALTER TABLE `agents` CHANGE `reference` `reference` INT(11) NULL DEFAULT NULL;


11-12-2024
---------------------------
ALTER TABLE `agents` ADD `trade_license_file` VARCHAR(255) NOT NULL AFTER `period_days`, ADD `trade_license_expiry_date` VARCHAR(255) NOT NULL AFTER `trade_license_file`, ADD `vat_certificate_file` VARCHAR(255) NOT NULL AFTER `trade_license_expiry_date`, ADD `eid_file` VARCHAR(255) NOT NULL AFTER `vat_certificate_file`, ADD `eid_expiry_date` VARCHAR(255) NOT NULL AFTER `eid_file`, ADD `passport_visa_copies_file` VARCHAR(255) NOT NULL AFTER `eid_expiry_date`;

ALTER TABLE `agents` CHANGE `trade_license_expiry_date` `trade_license_expiry_date` DATE NOT NULL, CHANGE `eid_expiry_date` `eid_expiry_date` DATE NOT NULL;

ALTER TABLE `agents` ADD `trade_license` INT(11) NOT NULL DEFAULT '0' AFTER `period_days`;

ALTER TABLE `agents` ADD `vat_certificate` INT(11) NOT NULL DEFAULT '0' AFTER `vat_certificate_file`;

ALTER TABLE `agents` ADD `eid` INT(11) NOT NULL DEFAULT '0' AFTER `eid_file`;

ALTER TABLE `agents` ADD `passport_visa_copies` INT(11) NOT NULL DEFAULT '0' AFTER `passport_visa_copies_file`;

ALTER TABLE `agents` CHANGE `trade_license` `trade_license` INT(11) NULL DEFAULT '0' COMMENT 'uncheck=1\r\ncheck=0', CHANGE `vat_certificate` `vat_certificate` INT(11) NULL DEFAULT '0' COMMENT 'uncheck=1\r\ncheck=0', CHANGE `eid` `eid` INT(11) NULL DEFAULT '0' COMMENT 'uncheck=1\r\ncheck=0', CHANGE `passport_visa_copies` `passport_visa_copies` INT(11) NULL DEFAULT '0' COMMENT 'uncheck=1\r\ncheck=0';


ALTER TABLE `agents` ADD `organization_id` VARCHAR(255) NOT NULL AFTER `id`;

ALTER TABLE `agents` ADD `added_date` DATE NOT NULL AFTER `is_approved`;

ALTER TABLE `agents` ADD `is_approved_by` INT(11) NOT NULL AFTER `is_approved`;

16-12-2024
----------------------------
ALTER TABLE `agents` ADD `approved_agent_id` INT(11) NOT NULL DEFAULT '0' AFTER `passport_visa_copies`;


18-12-2024
----------------------------
ALTER TABLE `agents` CHANGE `industry_type` `industry_type` INT(11) NULL DEFAULT NULL;

ALTER TABLE `agents` CHANGE `branch` `branch` INT(11) NULL DEFAULT NULL;

19-12-2024
------------------------
INSERT INTO `permissions` (`id`, `pname`, `set_order`, `created_at`, `updated_at`) VALUES (NULL, 'Frequency', '20', NULL, NULL);

INSERT INTO `permissions` (`id`, `pname`, `set_order`, `created_at`, `updated_at`) VALUES (NULL, 'Duration', '21', NULL, NULL);

ALTER TABLE `followups` CHANGE `frequency` `frequency` INT(11) NULL DEFAULT NULL, CHANGE `duration` `duration` INT(11) NULL DEFAULT NULL;
