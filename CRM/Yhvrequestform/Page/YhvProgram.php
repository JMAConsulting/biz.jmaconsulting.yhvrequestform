<?php
use CRM_Yhvrequestform_ExtensionUtil as E;

class CRM_Yhvrequestform_Page_YhvProgram extends CRM_Core_Page {

  public function run() {
    $value = $_REQUEST['_value'];
    $isGrid = $_REQUEST['_isJsGrid'];
    $loc = $_REQUEST['_loc'];

    if ($isGrid) {
      $progs = CRM_Yhvrequestform_Utils::getChainedSelectValues('Program', $value, $loc);
      if (!empty($progs)) {
        foreach ($progs as $prog) {
          $returnVals[] = [
            "Name" => $prog['value'],
            "Id" => $prog['key'],
          ];
        }
      }
      CRM_Utils_JSON::output($returnVals);
    }
    else {
      // Check if the values belong to Location.
      $loc = CRM_Core_Session::singleton()->get('location');
      CRM_Utils_JSON::output(CRM_Yhvrequestform_Utils::getChainedSelectValues('Program', $value, $loc));
    }
  }
}
