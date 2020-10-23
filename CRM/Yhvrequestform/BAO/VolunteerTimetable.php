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
          'entity_id' => $instance->entity_id,
          'entity_table' => $instance->entity_table,
          'day' => $instance->day,
          'time' => $instance->time,
          'number_of_volunteers' => $instance->number_of_volunteers,
        ];
      }
    }
    return $returnValues;
  }

  public static function add($entityId, $params, $update = FALSE, $entityTable = 'civicrm_activity') {
    if ($update) {
      // Delete all values from database pertaining to activity id.
      self::deleteTimeTable($entityId, $entityTable);
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
            'entity_id' => (int) $entityId,
            'entity_table' => $entityTable,
            'day' => (int) $items[0],
            'time' => (int) $items[1],
            'number_of_volunteers' => $value,
        ];

        self::create($timeTableParams);
      }
    }
  }

  public static function deleteTimeTable($entityId, $entityTable) {
    CRM_Core_DAO::executeQuery("DELETE FROM civicrm_volunteer_timetable WHERE entity_id = %1 AND entity_table = %2", [1 => [$entityId, 'Integer'], 2 => [$entityTable, 'String']]);
  }
}
