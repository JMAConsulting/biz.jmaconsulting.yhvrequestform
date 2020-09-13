<?php
use CRM_Yhvrequestform_ExtensionUtil as E;

class CRM_Yhvrequestform_BAO_VolunteerTimetable extends CRM_Yhvrequestform_DAO_VolunteerTimetable {

  /**
   * Create a new VolunteerTimetable based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Yhvrequestform_DAO_VolunteerTimetable|NULL
   */
  public static function create($params) {
    $className = 'CRM_Yhvrequestform_DAO_VolunteerTimetable';
    $entityName = 'VolunteerTimetable';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }
  
  public static function getTimeTable($params) {
				$className = 'CRM_Yhvrequestform_DAO_VolunteerTimetable';
				$returnValues = [];
				
				$instance = new $className();
				$instance->copyValues($params);
				if ($instance->find()) {
						while ($instance->fetch()) {
								$returnValues[$instance->id] = [
										'activity_id' => $instance->activity_id,
										'day' => $instance->day,
										'time' => $instance->time,
										'number_of_volunteers' => $instance->number_of_volunteers,
								];
						}
				}
				return $returnValues;
		}
  
  public static function add($activityId, $params, $update = FALSE) {
  		if ($update) {
  				// Delete all values from database pertaining to activity id.
						self::deleteTimeTable($activityId);
				}
  		$timePeriods = CRM_Core_OptionGroup::values('yhv_time_period');
  		$days = CRM_Core_OptionGroup::values('yhv_days');
				for ($i = 1; $i <= count($timePeriods); $i++) {
						for ($j = 1; $j <= count($days); $j++) {
								$elements[] = $j . '_' .$i;
						}
				}
  		
  		foreach ($params as $key => $value) {
  				if (in_array($key, $elements) && !empty($value)) {
  						if (empty($value)) {
  								continue;
								}
  						$items = explode('_', $key);
  						$timeTableParams = [
  								'activity_id' => (int) $activityId,
										'day' => (int) $items[0],
										'time' => (int) $items[1],
										'number_of_volunteers' => $value,
								];
						
								self::create($timeTableParams);
						}
				}
		}
		
		public static function deleteTimeTable($activityId) {
				CRM_Core_DAO::executeQuery("DELETE FROM civicrm_volunteer_timetable WHERE activity_id = %1", [1 => [$activityId, 'Integer']]);
		}
}
