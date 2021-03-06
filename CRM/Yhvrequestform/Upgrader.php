<?php
use CRM_Yhvrequestform_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Yhvrequestform_Upgrader extends CRM_Yhvrequestform_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Example: Run an external SQL script when the module is installed.
   *
  public function install() {
  $this->executeSqlFile('sql/myinstall.sql');
  }

  /**
   * Example: Work with entities usually not available during the install step.
   *
   * This method can be used for any post-install tasks. For example, if a step
   * of your installation depends on accessing an entity that is itself
   * created during the installation (e.g., a setting or a managed entity), do
   * so here to avoid order of operation problems.
   */
  public function postInstall() {
    // Create Volunteer Request Activity Type.
    civicrm_api3('OptionValue', 'create', [
      'option_group_id' => "activity_type",
      'label' => "Volunteer Request",
    ]);

    // Install the timetable option group and values.
    $timePeriod = civicrm_api3('OptionGroup', 'create', [
      'name' => 'yhv_time_period',
      'title' => 'Volunteer Time Period',
    ]);
    $timePeriods = [
      1 => 'Breakfast',
      2 => 'Morning',
      3 => 'Lunch',
      4 => 'Afternoon',
      5 => 'Supper',
      6 => 'Evening',
    ];
    foreach ($timePeriods as $key => $value) {
      civicrm_api3('OptionValue', 'create', [
        'option_group_id' => $timePeriod['id'],
        'value' => $key,
        'name' => $value,
        'title' => $value,
      ]);
    }
    $day = civicrm_api3('OptionGroup', 'create', [
      'name' => 'yhv_days',
      'title' => 'Volunteer Days',
    ]);
    $days = [
      1 => 'Sunday',
      2 => 'Monday',
      3 => 'Tuesday',
      4 => 'Wednesday',
      5 => 'Thursday',
      6 => 'Friday',
      7 => 'Saturday',
    ];
    foreach ($days as $key => $value) {
      civicrm_api3('OptionValue', 'create', [
        'option_group_id' => $day['id'],
        'value' => $key,
        'name' => $value,
        'title' => $value,
      ]);
    }
  }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   *
  public function uninstall() {
  $this->executeSqlFile('sql/myuninstall.sql');
  }

  /**
   * Example: Run a simple query when a module is enabled.
   *
  public function enable() {
  CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
  }

  /**
   * Example: Run a simple query when a module is disabled.
   *
  public function disable() {
  CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  }

  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws Exception
   *
  public function upgrade_4200() {
  $this->ctx->log->info('Applying update 4200');
  CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
  CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
  return TRUE;
  } // */

  public function upgrade_1100() {
    $this->ctx->log->info('Applying update 1100');
    CRM_Core_DAO::executeQuery('ALTER TABLE civicrm_volunteer_timetable CHANGE `activity_id` `entity_id` int unsigned');
    CRM_Core_DAO::executeQuery('ALTER TABLE civicrm_volunteer_timetable ADD COLUMN `entity_table` VARCHAR(255) AFTER `entity_id`');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_volunteer_timetable SET `entity_table` = "civicrm_activity" WHERE `entity_id` IS NOT NULL');
    CRM_Core_DAO::executeQuery('ALTER TABLE civicrm_volunteer_timetable DROP FOREIGN KEY FK_civicrm_volunteer_timetable_activity_id');
    return TRUE;
  }

  /**
   * Example: Run an external SQL script.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4201() {
  $this->ctx->log->info('Applying update 4201');
  // this path is relative to the extension base dir
  $this->executeSqlFile('sql/upgrade_4201.sql');
  return TRUE;
  } // */


  /**
   * Example: Run a slow upgrade process by breaking it up into smaller chunk.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4202() {
  $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

  $this->addTask(E::ts('Process first step'), 'processPart1', $arg1, $arg2);
  $this->addTask(E::ts('Process second step'), 'processPart2', $arg3, $arg4);
  $this->addTask(E::ts('Process second step'), 'processPart3', $arg5);
  return TRUE;
  }
  public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
  public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
  public function processPart3($arg5) { sleep(10); return TRUE; }
  // */


  /**
   * Example: Run an upgrade with a query that touches many (potentially
   * millions) of records by breaking it up into smaller chunks.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4203() {
  $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

  $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM civicrm_contribution');
  $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM civicrm_contribution');
  for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
  $endId = $startId + self::BATCH_SIZE - 1;
  $title = E::ts('Upgrade Batch (%1 => %2)', array(
  1 => $startId,
  2 => $endId,
  ));
  $sql = '
  UPDATE civicrm_contribution SET foobar = whiz(wonky()+wanker)
  WHERE id BETWEEN %1 and %2
  ';
  $params = array(
  1 => array($startId, 'Integer'),
  2 => array($endId, 'Integer'),
  );
  $this->addTask($title, 'executeSql', $sql, $params);
  }
  return TRUE;
  } // */

}
