<?php
		
		use CRM_Yhvrequestform_ExtensionUtil as E;
		
		class CRM_Yhvrequestform_Utils {
				
				public static function getCustomFieldOptions($name) {
						$optionGroupName = CRM_Core_DAO::singleValueQuery("SELECT g.name
        FROM civicrm_custom_field c
        INNER JOIN civicrm_option_group g ON g.id = c.option_group_id
        WHERE c.name = %1", [1 => [$name, 'String']]);
						return CRM_Core_OptionGroup::values($optionGroupName);
				}
				
				public static function getChainedSelectValues($name) {
						$options = self::getCustomFieldOptions($name);
						
						foreach ($options as $key => $value) {
								$list[] = [
										'key' => $key,
										'value' => $value,
								];
						}
						
						return $list;
				}
				
				public static function renderGridElements($form) {
						$timePeriods = CRM_Core_OptionGroup::values('yhv_time_period');
						$days = CRM_Core_OptionGroup::values('yhv_days');
						for ($i = 1; $i <= count($timePeriods); $i++) {
								for ($j = 1; $j <= count($days); $j++) {
										$form->add('text', $j . '_' . $i, ts($j . '_' . $i), ['size' => 5]);
										$gridElements[$timePeriods[$i]][$j] = $j . '_' . $i;
								}
						}
						
						$form->assign('gridElements', $gridElements);
						$form->assign('yhvDays', $days);
						return [$days, $gridElements];
				}
				
				public static function getFormattedValues($timeTable) {
						foreach ($timeTable as $elements) {
								$gridValues[$elements['day'] . '_' . $elements['time']] = $elements['number_of_volunteers'];
						}
						return $gridValues;
				}
				
				public static function getCustomFieldID($name, $groupId = VOLUNTEERING_CUSTOM) {
						return 'custom_' . CRM_Core_DAO::singleValueQuery("SELECT id FROM civicrm_custom_field WHERE name = %1 AND custom_group_id = %2", [1 => [$name, 'String'], 2 => [$groupId, 'Integer']]);
				}
		}