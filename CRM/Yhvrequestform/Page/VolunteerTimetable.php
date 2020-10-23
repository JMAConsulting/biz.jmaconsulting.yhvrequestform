<?php
use CRM_Yhvrequestform_ExtensionUtil as E;

class CRM_Yhvrequestform_Page_VolunteerTimetable extends CRM_Core_Page {

  public function run() {
    $cid = CRM_Utils_Request::retrieve('cid', 'Positive');
    $url = CRM_Utils_System::url('civicrm/volunteertimetableedit', "reset=1&cid=$cid");
    $this->assign('editUrl', $url);

    $params = ['entity_id' => $cid, 'entity_table' => 'civicrm_contact'];
    $timeTable = CRM_Yhvrequestform_BAO_VolunteerTimetable::getTimeTable($params);
    CRM_Yhvrequestform_Utils::renderGrid($this, $timeTable);
    parent::run();
  }

}
