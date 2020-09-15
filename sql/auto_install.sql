-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--


-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from drop.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the exisiting tables
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_volunteer_timetable`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_volunteer_timetable
-- *
-- * FIXME
-- *
-- *******************************************************/
CREATE TABLE `civicrm_volunteer_timetable` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique VolunteerTimetable ID',
     `activity_id` int unsigned    COMMENT 'FK to Activity',
     `day` int unsigned    COMMENT 'Number that denotes day of the week',
     `time` int unsigned    COMMENT 'Number that denotes time period',
     `number_of_volunteers` int unsigned    COMMENT 'Number of volunteers needed' 
,
        PRIMARY KEY (`id`)
 
 
,          CONSTRAINT FK_civicrm_volunteer_timetable_activity_id FOREIGN KEY (`activity_id`) REFERENCES `civicrm_activity`(`id`) ON DELETE CASCADE  
)    ;

 