<?php

class CRM_Yhvrequestform_BAO_Query_VolunteerApplication extends CRM_Contact_BAO_Query_Interface {

  public static $timetable_fields = [];

  public function &getFields() {
    $timePeriods = CRM_Core_OptionGroup::values('yhv_time_period');
    $days = CRM_Core_OptionGroup::values('yhv_days');
    for ($i = 1; $i <= count($timePeriods); $i++) {
      for ($j = 1; $j <= count($days); $j++) {
        self::$timetable_fields[$j . '_' .$i] = [
          'name' => $j . '_' .$i,
          'title' => $days[$j] . ' ' . $timePeriods[$i],
          'type' => CRM_Utils_Type::T_STRING,
        ];
      }
    }

    $return = self::$timetable_fields;

    return $return;
  }

  public function from($name, $mode, $side) {

    if ($mode == CRM_Contact_BAO_Query::MODE_CONTACTS) {
      return "$side JOIN civicrm_volunteer_timetable v ON v.entity_id = contact_a.id AND entity_table = 'civicrm_contact'";
    }
  }

  public function where(&$query) {

    if (empty($query->_params)) return;

    foreach ($query->_params as $id => $param) {
      if (empty($param[0])) {
        continue;
      }

      $param[3] = $id;

      $this->whereClauseSingle($param, $query);
    }

  }

  public function whereClauseSingle(&$values, &$query) {

    list($name, $op, $value, $grouping, $wildcard) = $values;


    $fields = $this->getFields();

    if (!array_key_exists($name,$fields)) return;

    $field = $fields[$name];
    $qillValue = 'No';
    if ($value) {
      $qillValue = 'Yes';
    }

    $query->_qill[$grouping][] = ts($field['title'])." - '$qillValue'";
    $searchValue = explode('_', $field['name']);
    $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("v.day", $op, $searchValue[0], "Integer");
    $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("v.time", $op, $searchValue[1], "Integer");
    $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("v.number_of_volunteers", $op, 1, "Integer");
    $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("v.entity_table", $op, "civicrm_contact", "String");
  }

  public function getPanesMapper(&$panes) {

    $panes['Volunteer Availability'] = 'civicrm_contact';
  }

  public function registerAdvancedSearchPane(&$panes) {
    $panes['Volunteer Availability'] = 'volunteer_application';
  }

  public function buildAdvancedSearchPaneForm(&$form, $type) {
    if ($type=='volunteer_application') {

      $form->add('hidden', 'hidden_volunteer_application', 1);

      CRM_Yhvrequestform_Utils::renderGridElements($form, TRUE);
    }
  }

  public function setAdvancedSearchPaneTemplatePath(&$paneTemplatePathArray, $type) {
    if (in_array($type,[
      'volunteer_application',
    ])) {
      $paneTemplatePathArray[$type] = "CRM/Yhvrequestform/Form/Search/Criteria/VolunteerApplication.tpl";
    }
  }

}