ALTER TABLE `beer` CHANGE `idbiere` `id_beer` INT NOT NULL AUTO_INCREMENT; 

ALTER TABLE `beer` CHANGE `created_time` `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `update_time` `update_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP; 