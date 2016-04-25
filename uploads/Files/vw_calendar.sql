DELIMITER $$

USE `customer_relation_management`$$

DROP VIEW IF EXISTS `vw_calendar`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_calendar` AS (
SELECT
  `ev`.`id`           AS `id`,
  `ev`.`subject`      AS `subjects`,
  `ev`.`assigned_to`  AS `assigned_to`,
  CONCAT(`users`.`first_name`,' ',`users`.`last_name`) AS `uname`,
  `ev`.`start_date`   AS `start_date`,
  `ev`.`end_date`     AS `end_date`,
  `ev`.`due_date`     AS `due_date`,
  `es`.`id`         AS `stat_id`,
  `es`.`name`         AS `event_stat`,
  `at`.`id`         AS `atype_id`,
  `at`.`name`         AS `activity_type`,
  `l`.`name`          AS `location`,
  `ev`.`priority`     AS `priority`,
  `ev`.`description`  AS `description`,
  `ev`.`is_deleted`   AS `is_deleted`,
  `opp`.`opp_name`   AS `is_related`,
  `ev`.`date_created` AS `date_created`,
  `ev`.`allDay`       AS `allDay`,
  `ev`.`org_id`       AS `org_id`,
  `org`.`org_name`    AS `org_name`,
  `ev`.`opp_id`       AS `opp_id`,
  `opp`.`opp_name`    AS `opp_name`
FROM (((((((`events` `ev`
         LEFT JOIN `users`
           ON ((`ev`.`assigned_to` = `users`.`id`)))
        LEFT JOIN `user_types` `utype`
          ON ((`users`.`user_type_id` = `utype`.`id`)))
       LEFT JOIN `act_types` `at`
         ON ((`ev`.`activity_type` = `at`.`id`)))
      LEFT JOIN `event_status` `es`
        ON ((`ev`.`event_stat` = `es`.`id`)))
     LEFT JOIN `locations` `l`
       ON ((`ev`.`location_id` = `l`.`id`)))
    LEFT JOIN `opportunities` `opp`
      ON ((`ev`.`opp_id` = `opp`.`id`)))
   LEFT JOIN `organizations` `org`
     ON ((`ev`.`org_id` = `org`.`id`)))
     WHERE (`ev`.`is_related` = `opp`.`id`))$$

DELIMITER ;