INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectMilestones|object_custom_properties', 'Критическая точка', 'critical_date_value', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectMilestones|object_custom_properties', 'Дата начала', 'start_date_value', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));

INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ObjectSubtypeToGroup', 'Поставка', 'Отдел поставок', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));

INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('GroupToWorkspace', 'Отдел поставок', 'Отдел поставок', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));

INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('WorkflowToWorkspace', 'choose_assignee', 'Выбор исполнителя', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('WorkflowToWorkspace', 'in_progress', 'Задача на выполнении', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('WorkflowToWorkspace', 'need_state', 'Необходимо выставить состояние', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('WorkflowToWorkspace', 'need_percent', 'Необходимо выставить процент', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('WorkflowToWorkspace', 'need_confirm_state', 'Необходимо подтвердить состояние', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('WorkflowToWorkspace', 'need_coordination', 'Требует согласования', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));


#INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('Group', 'manager', 'Руководитель отдела', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));


INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectTasks|object_custom_properties', 'Критическая точка', 'task_critical_date', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectTasks|object_custom_properties', 'Основной исполнитель', 'main_assigned_to', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectTasks|object_custom_properties', 'Скорость поставки подтверждается руководителем', 'is_due_date_confirmed_by_manager', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));


INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectTasks|object_subtypes', 'Подписание договора о сотрудничестве', 'cooperation_agreement', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectTasks|object_subtypes', 'Авансовый платеж', 'advance_payment', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectTasks|object_subtypes', 'Мобилизация', 'mobilization', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectTasks|object_subtypes', 'Формирование графика производства', 'schedule_formation', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectTasks|object_subtypes', 'Поставка', 'supply', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));
INSERT INTO `mapping` (`prefix`, `mapping1`, `mapping2`, `hash1`, `hash2`) VALUES ('ProjectTasks|object_subtypes', 'Работа', 'job', sha1(CONCAT(`prefix`, `mapping1`)), sha1(CONCAT(`prefix`, `mapping2`)));