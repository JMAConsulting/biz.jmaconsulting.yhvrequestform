<?php
use CRM_Yhvrequestform_ExtensionUtil as E;

class CRM_Yhvrequestform_Page_YhvProgram extends CRM_Core_Page {
		
		public function run() {
				$value = $_GET['_value'];
				
				// Check if the values belong to Location.
				$loc = CRM_Core_Session::singleton()->get('location');
				CRM_Utils_JSON::output(CRM_Yhvrequestform_Utils::getChainedSelectValues('Program', $value, $loc));
		}

}
