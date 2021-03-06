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
      'job_description' => 'Job_Description_Duties',
      'languages' => 'Languages',
      'computer_skills' => 'Computer_Skills',
      'tb_screening' => 'TB_Screening',
      'police_check' => 'Police_Check',
      'vehicle' => 'Vehicle',
      'other_skills' => 'Other_Skills',
      'type_of_request' => 'Type_Of_Request',
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

  public static function getCustomFieldOptions($name, $group = VOLUNTEERING_CUSTOM) {
    $optionGroupName = CRM_Core_DAO::singleValueQuery("SELECT g.name
        FROM civicrm_custom_field c
        INNER JOIN civicrm_option_group g ON g.id = c.option_group_id
        WHERE c.name = %1 AND c.custom_group_id = %2", [1 => [$name, 'String'], 2 => [$group, 'Integer']]);
    if (empty($optionGroupName)) {
      return [];
    }
    return CRM_Core_OptionGroup::values($optionGroupName);
  }

  public static function getLocationFieldOptions($name) {
    $values = CRM_Core_DAO::executeQuery("SELECT v.value, CONCAT(v.label, '\n', v.description) AS description
        FROM civicrm_custom_field c
        INNER JOIN civicrm_option_group g ON g.id = c.option_group_id
        INNER JOIN civicrm_option_value v ON v.option_group_id = g.id
        WHERE c.name = %1 and c.custom_group_id = %2
        ORDER BY v.weight ASC", [1 => [$name, 'String'], 2 => [VOLUNTEERING_CUSTOM, 'Integer']])->fetchAll();
    foreach($values as $value) {
      $returnValues[$value['value']] = $value['description'];
    }
    return $returnValues;
  }

  public static function createLiaisonContact($email) {
    $contact = civicrm_api3('Email', 'get', ['sequential' => 1, 'email' => $email, 'return' => ['contact_id']]);
    if (!empty($contact['values'][0]['contact_id'])) {
      return $contact['values'][0]['contact_id'];
    }
    else {
      $contact = civicrm_api3('Contact', 'create', ['contact_type' => 'Individual', 'email' => $email]);
      return $contact['id'];
    }
  }

  public static function getChainedSelectValue($name, $selectedVal) {
    $validOptions = $list = [];
    if ($selectedVal == 'division') {
      CRM_Core_Session::singleton()->set('location', $name);
    }
    if (strtolower($selectedVal) == "division") {
      // Filter according to the location.
      $lookup = CRM_Core_DAO::executeQuery("SELECT Division FROM civicrm_volunteer_lookup WHERE Location = %1 GROUP BY Division", [1 => [$name, 'String']])->fetchAll();
      if (!empty($lookup)) {
        foreach ($lookup as $option) {
          $validOptions[$option['Division']] = $option['Division'];
        }
      }
    }
    if (strtolower($selectedVal) == "program") {
      $previousVal = CRM_Core_Session::singleton()->get('location');
      // Filter according to the location and division.
      $lookup = CRM_Core_DAO::executeQuery("SELECT Program FROM civicrm_volunteer_lookup WHERE Location = %1 AND Division = %2 GROUP BY Program", [1 => [$previousVal, 'String'], 2 => [$name, 'String']])->fetchAll();
      $isAny = FALSE;
      if (!empty($lookup)) {
        foreach ($lookup as $option) {
          if ($option['Program'] == 'Any') {
            $isAny = TRUE;
            break;
          }
          $validOptions[$option['Program']] = $option['Program'];
        }
      }
      if ($isAny) {
        $validOptions = [
          'Administration 行政部' => 'Administration 行政部',
          'Activation 活動組' => 'Activation 活動組',
          'Chaplain 院牧部' => 'Chaplain 院牧部',
          'Environmental Service 環境衛生部' => 'Environmental Service 環境衛生部',
          'Food Service 膳食' => 'Food Service 膳食',
          'Nursing 護理部' => 'Nursing 護理部',
          'Social Work 社工部' => 'Social Work 社工部',
        ];
      }
    }
    $options = self::getCustomFieldOptions($name);

    $options = array_intersect_assoc($validOptions, $options);
    foreach ($validOptions as $key => $value) {
      $list[$key] = $value;
    }

    return $list;
  }

  public static function getChainedSelectValues($name, $selectedVal, $previousVal = NULL) {
    $validOptions = [];
    if (strtolower($name) == "division") {
      // Filter according to the location.
      $lookup = CRM_Core_DAO::executeQuery("SELECT Division FROM civicrm_volunteer_lookup WHERE Location = %1 GROUP BY Division", [1 => [$selectedVal, 'String']])->fetchAll();
      if (!empty($lookup)) {
        foreach ($lookup as $option) {
          $validOptions[$option['Division']] = $option['Division'];
        }
      }
    }
    if (strtolower($name) == "program") {
      // Filter according to the location and division.
      $lookup = CRM_Core_DAO::executeQuery("SELECT Program FROM civicrm_volunteer_lookup WHERE Location = %1 AND Division = %2 GROUP BY Program", [1 => [$previousVal, 'String'], 2 => [$selectedVal, 'String']])->fetchAll();
      $isAny = FALSE;
      if (!empty($lookup)) {
        foreach ($lookup as $option) {
          if ($option['Program'] == 'Any') {
            $isAny = TRUE;
            break;
          }
          $validOptions[$option['Program']] = $option['Program'];
        }
      }
      if ($isAny) {
        $validOptions = [
          'Administration 行政部' => 'Administration 行政部',
          'Activation 活動組' => 'Activation 活動組',
          'Chaplain 院牧部' => 'Chaplain 院牧部',
          'Environmental Service 環境衛生部' => 'Environmental Service 環境衛生部',
          'Food Service 膳食' => 'Food Service 膳食',
          'Nursing 護理部' => 'Nursing 護理部',
          'Social Work 社工部' => 'Social Work 社工部',
        ];
      }
    }
    $options = self::getCustomFieldOptions($name);

    $options = array_intersect_assoc($validOptions, $options);
    foreach ($validOptions as $key => $value) {
      $list[] = [
        'key' => $key,
        'value' => $value,
      ];
    }

    return $list;
  }

  public static function getFunder($params) {
    $clauses[] = 1;
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

  public static function renderGridElements($form, $yesNo = FALSE) {
    $timePeriods = CRM_Core_OptionGroup::values('yhv_time_period');
    $days = CRM_Core_OptionGroup::values('yhv_days');
    for ($i = 1; $i <= count($timePeriods); $i++) {
      for ($j = 1; $j <= count($days); $j++) {
        if ($yesNo) {
          $form->add('checkbox', $j . '_' . $i, ts('Yes'));
        }
        else {
          $form->add('text', $j . '_' . $i, ts($j . '_' . $i), ['size' => 5]);
          $form->addRule($j . '_' . $i, ts('Please enter a number'), 'numeric');
        }
        $gridElements[$timePeriods[$i]][$j] = $j . '_' . $i;
      }
    }

    $form->assign('gridElements', $gridElements);
    $form->assign('yhvDays', $days);
    return [$days, $gridElements];
  }

  public static function renderGrid($page, $timeTable) {
    $timePeriods = CRM_Core_OptionGroup::values('yhv_time_period');
    $days = CRM_Core_OptionGroup::values('yhv_days');
    for ($i = 1; $i <= count($timePeriods); $i++) {
      for ($j = 1; $j <= count($days); $j++) {
        $gridElements[$timePeriods[$i]][$j] = " ";
        foreach ($timeTable as $row) {
          if ($row['day'] == $j && $row['time'] == $i) {
            $gridElements[$timePeriods[$i]][$j] = "Yes";
          }
        }
      }
    }

    $page->assign('gridElements', $gridElements);
    $page->assign('yhvDays', $days);
    return [$days, $gridElements];
  }

  function setStatus($oldStatus = NULL, $cid, $submitValues) {
    if (!empty($cid)) {
      $submitKeys = array_keys($submitValues);
      $key = preg_grep('/^' . STATUS . '_[\d]*/', $submitKeys);
      $newStatus = reset($key);
      if ($oldStatus != "Active" && CRM_Utils_Array::value($newStatus, $submitValues) == 'Active') {
        // Set WP user to active.
        $uf = civicrm_api3('UFMatch', 'get', [
          'sequential' => 1,
          'return' => ["uf_id"],
          'contact_id' => $cid,
        ]);
        if (!empty($uf['values'][0]['uf_id'])) {
          $u = new WP_User($uf['values'][0]['uf_id']);
          $u->remove_role('inactive');
          $u->add_role('subscriber');
        }
      }
      if ($oldStatus != "Inactive" && CRM_Utils_Array::value($newStatus, $submitValues) == 'Inactive') {
        // Set WP user to active.
        $uf = civicrm_api3('UFMatch', 'get', [
          'sequential' => 1,
          'return' => ["uf_id"],
          'contact_id' => $cid,
        ]);
        if (!empty($uf['values'][0]['uf_id'])) {
          $u = new WP_User($uf['values'][0]['uf_id']);
          $u->remove_role('subscriber');
          $u->add_role('inactive');
        }
      }
    }
  }

  public static function getFormattedValues($timeTable) {
    foreach ($timeTable as $elements) {
      $gridValues[$elements['day'] . '_' . $elements['time']] = $elements['number_of_volunteers'];
    }
    return $gridValues;
  }

  public static function getCustomFieldID($name) {
    return 'custom_' . CRM_Core_DAO::singleValueQuery("SELECT id FROM civicrm_custom_field WHERE name = %1 AND custom_group_id IN (%2,%3)", [1 => [$name, 'String'], 2 => [VOLUNTEERING_CUSTOM, 'Integer'], 3 => [VOLUNTEER_REQUEST, 'Integer']]);
  }
}
