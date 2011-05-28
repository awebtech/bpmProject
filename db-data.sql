INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectMilestones|object_custom_properties', 'Критическая точка', 'critical_date_value', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectMilestones|object_custom_properties', 'Дата начала', 'start_date_value', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));

INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ObjectSubtypeToGroup', 'Поставка', 'Отдел поставок', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));

INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('Group', 'manager', 'Руководитель отдела', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));