<?php
use CRM_Yhvrequestform_ExtensionUtil as E;

class CRM_Yhvrequestform_Page_YhvDepartment extends CRM_Core_Page {

  public function run() {
  			$value = $_GET['_value'];
  			
  			// Check if the values belong to Location.
				 CRM_Core_Session::singleton()->set('location', $value);
				 CRM_Utils_JSON::output(CRM_Yhvrequestform_Utils::getChainedSelectValues('Division', $value));
  }
}
