<?php
		
		use CRM_Yhvrequestform_ExtensionUtil as E;
		
		class CRM_Yhvrequestform_Utils {

				public static function getCustomFields() {
						return [
										'location' => 'Location',
										'division' => 'Division',
										'program' => 'Program',
										'funder' => 'Funder',
										'job' => 'Job',
										'languages' => 'Languages',
										'computer_skills' => 'Computer_Skills',
										'tb_screening' => 'TB_Screening',
										'police_check' => 'Police_Check',
										'vehicle' => 'Vehicle',
										'other_skills' => 'Other_Skills',
										'type_of_request' => 'Type_Of_Request',
										'duration' => 'Duration',
										'start_date' => 'Start_Date',
										'end_date' => 'End_Date',
										'other_remarks' => 'Other_Remarks',
								];
				}
				
				public static function getCustomFieldDetails($name, $group = VOLUNTEERING_CUSTOM) {
						$details = CRM_Core_DAO::executeQuery("SELECT label, help_pre, help_post FROM civicrm_custom_field WHERE name = %1 AND custom_group_id = %2", [1 => [$name, 'String'], 2 => [$group, 'Integer']])->fetchAll();
      if (empty($details)) {
      		return [
      				'label' => 'Label',
										'help_pre' => 'Help Pre',
										'help_post' => 'Help Post',
								];
						}
      foreach ($details as $detail) {
      		$returnValues = [
      				'label' => CRM_Utils_Array::value('label', $detail, ''),
										'help_pre' => CRM_Utils_Array::value('help_pre', $detail, ''),
										'help_post' => CRM_Utils_Array::value('help_post', $detail, ''),
								];
						}
						return $returnValues;
				}
				
				public static function getCustomFieldOptions($name) {
						$optionGroupName = CRM_Core_DAO::singleValueQuery("SELECT g.name
        FROM civicrm_custom_field c
        INNER JOIN civicrm_option_group g ON g.id = c.option_group_id
        WHERE c.name = %1", [1 => [$name, 'String']]);
						return CRM_Core_OptionGroup::values($optionGroupName);
				}
				
				public static function getChainedSelectValues($name, $selectedVal, $previousVal = NULL) {
						$validOptions = [];
						if ($name == "Division") {
								// Filter according to the location.
								$lookup = CRM_Core_DAO::executeQuery("SELECT Division FROM civicrm_volunteer_lookup WHERE Location = %1 GROUP BY Division", [1 => [$selectedVal, 'String']])->fetchAll();
								if (!empty($lookup)) {
										foreach ($lookup as $option) {
												$validOptions[$option['Division']] = $option['Division'];
										}
								}
						}
						if ($name == "Program") {
								// Filter according to the location and division.
								$lookup = CRM_Core_DAO::executeQuery("SELECT Program FROM civicrm_volunteer_lookup WHERE Location = %1 AND Division = %2 GROUP BY Program", [1 => [$previousVal, 'String'], 2 => [$selectedVal, 'String']])->fetchAll();
								if (!empty($lookup)) {
										foreach ($lookup as $option) {
												$validOptions[$option['Program']] = $option['Program'];
										}
								}
						}
						$options = self::getCustomFieldOptions($name);
						
						$options = array_intersect_assoc($validOptions, $options);
						foreach ($options as $key => $value) {
								$list[] = [
										'key' => $key,
										'value' => $value,
								];
						}
						
						return $list;
				}
				
				public static function getFunder($params) {
						if (!empty($params['location'])) {
								$clauses[] = "Location = '" . $params['location'] . "'";
						}
						
						if (!empty($params['division'])) {
								$clauses[] = "Division = '" . $params['division'] . "'";
						}
						
						if (!empty($params['program'])) {
								$clauses[] = "Program = '" . $params['program'] . "'";
						}
						
						$sql = "SELECT Funder FROM civicrm_volunteer_lookup WHERE " . implode(' AND ', $clauses);
						
						return CRM_Core_DAO::singleValueQuery($sql);
						
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