<?php

use CRM_Yhvrequestform_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Yhvrequestform_Form_VolunteerRequest extends CRM_Core_Form {
  public function buildQuickForm() {
  		CRM_Utils_System::setTitle('Volunteer Request Form');
  		foreach (CRM_Yhvrequestform_Utils::getCustomFields() as $customField) {
						$$customField = CRM_Yhvrequestform_Utils::getCustomFieldDetails($customField);
  		}
  		
				$this->add('select', 'location', ts($Location['label']), ['' => '- select -'] + CRM_Yhvrequestform_Utils::getLocationFieldOptions('Location'), TRUE);
				$this->assign('locationPreHelp', $Location['help_pre']);
				$this->assign('locationPostHelp', $Location['help_post']);
				
				$settings = [
						'control_field' => 'location',
						'data-callback' => 'civicrm/getdept',
						'label' => ts($Division['label']),
						'data-empty-prompt' => ts('Choose Location first'),
						'data-none-prompt' => ts('- N/A -'),
						'multiple' => FALSE,
						'required' => TRUE,
						'placeholder' => ts('- select -'),
				];
		
				$this->addChainSelect('division', $settings);
				$this->assign('divisionPreHelp', $Division['help_pre']);
				$this->assign('divisionPostHelp', $Division['help_post']);
				
				$settings = [
						'control_field' => 'division',
						'data-callback' => 'civicrm/getpro',
						'label' => ts($Program['label']),
						'data-empty-prompt' => ts('Choose Division first'),
						'data-none-prompt' => ts('- N/A -'),
						'multiple' => FALSE,
						'required' => TRUE,
						'placeholder' => ts('- select -'),
				];
		
				$this->addChainSelect('program', $settings);
		
				$this->assign('programPreHelp', $Program['help_pre']);
				$this->assign('programPostHelp', $Program['help_post']);
				
				$this->add('datepicker', 'request_date', ts('Date Of Request'), [], TRUE, ['time' => FALSE]);
  		$this->freeze('request_date');
		
  		$this->add('select', 'job', ts($Job['label']), ['' => '- select -'] + CRM_Yhvrequestform_Utils::getCustomFieldOptions('Job'), TRUE);
				$this->assign('jobPreHelp', $Job['help_pre']);
				$this->assign('jobPostHelp', $Job['help_post']);
		
				$this->add('select', 'languages', ts($Languages['label']), CRM_Yhvrequestform_Utils::getCustomFieldOptions('Languages'), FALSE, ['class' => 'crm-select2', 'multiple' => TRUE, 'placeholder' => ts('- select -')]);
				$this->assign('languagesPreHelp', $Languages['help_pre']);
				$this->assign('languagesPostHelp', $Languages['help_post']);
				
				$this->addYesNo('computer_skills', ts($Computer_Skills['label']), ['allowClear' => TRUE]);
				$this->assign('computerPreHelp', $Computer_Skills['help_pre']);
				$this->assign('computerPostHelp', $Computer_Skills['help_post']);
		
				$this->addYesNo('tb_screening', ts($TB_Screening['label']), ['allowClear' => TRUE]);
				$this->assign('tbPreHelp', $TB_Screening['help_pre']);
				$this->assign('tbPostHelp', $TB_Screening['help_post']);
		
				$this->addYesNo('police_check', ts($Police_Check['label']), ['allowClear' => TRUE]);
				$this->assign('policePreHelp', $Police_Check['help_pre']);
				$this->assign('policePostHelp', $Police_Check['help_post']);
				
				$this->addYesNo('vehicle', ts($Vehicle['label']), ['allowClear' => TRUE]);
				$this->assign('vehiclePreHelp', $Vehicle['help_pre']);
				$this->assign('vehiclePostHelp', $Vehicle['help_post']);
				
				$this->add('text', 'other_skills', ts($Other_Skills['label']));
				$this->assign('otherPreHelp', $Other_Skills['help_pre']);
				$this->assign('otherPostHelp', $Other_Skills['help_post']);
				
				$this->addRadio('type_of_request', ts($Type_Of_Request['label']), ['one_time' => ts('One Time'), 'recurring' => ts('Recurring')]);
				$this->assign('requestPreHelp', $Type_Of_Request['help_pre']);
				$this->assign('requestPostHelp', $Type_Of_Request['help_post']);
				
				$this->add('text', 'duration', ts($Duration['label']));
				$this->assign('durationPreHelp', $Duration['help_pre']);
				$this->assign('durationPostHelp', $Duration['help_post']);
				
				$this->add('datepicker', 'start_date', ts($Start_Date['label']), [], FALSE, ['time' => FALSE]);
				$this->assign('startPreHelp', $Start_Date['help_pre']);
				$this->assign('startPostHelp', $Start_Date['help_post']);
				
				$this->add('datepicker', 'end_date', ts($End_Date['label']), [], FALSE, ['time' => FALSE]);
				$this->assign('endPreHelp', $End_Date['help_pre']);
				$this->assign('endPostHelp', $End_Date['help_post']);
				
				// Time table.
				CRM_Yhvrequestform_Utils::renderGridElements($this);
  		
				$this->add('textarea', 'other_remarks', ts($Other_Remarks['label']), 'rows=5, cols=50');
				$this->assign('remarkPreHelp', $Other_Remarks['help_pre']);
				$this->assign('remarkPostHelp', $Other_Remarks['help_post']);
				
				$this->assign('liasonStaff', CRM_Core_Session::singleton()->getLoggedInContactDisplayName());

				$this->addButtons([
						[
								'type' => 'upload',
								'name' => ts('Submit'),
								'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
								'isDefault' => TRUE,
						],
						[
								'type' => 'cancel',
								'name' => ts('Cancel'),
						],
				]);
    parent::buildQuickForm();
  }
  
  public function setDefaultValues() {
  		$defaults = [
  				'request_date' => date('Y-m-d'),
				];
				return $defaults;
  }
		
		/**
			* Performs the server side validation.
			* @since     1.0
			* @return bool
			*   true if no error found
			* @throws    HTML_QuickForm_Error
			*/
		public function validate() {
				$hookErrors = [];
				
				CRM_Utils_Hook::validateForm(
						get_class($this),
						$this->_submitValues,
						$this->_submitFiles,
						$this,
						$hookErrors
				);
				
				if (!empty($hookErrors)) {
						$this->_errors += $hookErrors;
				}
				
				return (0 == count($this->_errors));
		}
		
  public function postProcess() {
    $values = $this->exportValues();
    // Key Value pair of field name from form and custom field name.
		
				// We also determine the Funder from the hierarchical select fields.
				$values['funder'] = CRM_Yhvrequestform_Utils::getFunder($values);
				$customFields = CRM_Yhvrequestform_Utils::getCustomFields();
    $customParams = [];
    foreach ($customFields as $field => $name) {
    		if (!empty($values[$field])) {
								$customName = CRM_Yhvrequestform_Utils::getCustomFieldID($name);
								$customParams[$customName] = $values[$field];
						}
				}
    // Create Activity.
				$activityParams = [
						'activity_type_id' => "Volunteer Request",
						'source_contact_id' => "user_contact_id",
						'assignee_id' => VOLUNTEER_REQUEST_FACILITATOR,
						'status_id' => "Scheduled",
				];
    if (!empty($customParams)) {
						$activityParams += $customParams;
				}
    try {
    		$activity = civicrm_api3('Activity', 'create', $activityParams);
    		
    		if (!empty($activity)) {
    				// We add to the timetable.
								CRM_Yhvrequestform_BAO_VolunteerTimetable::add($activity['id'], $values);
						}
				}
				catch (CiviCRM_API3_Exception $e) {
						// Handle error here.
						$errorMessage = $e->getMessage();
						$errorCode = $e->getErrorCode();
						$errorData = $e->getExtraParams();
						$error = [
								'error_message' => $errorMessage,
								'error_code' => $errorCode,
								'error_data' => $errorData,
						];
						CRM_Core_Error::debug_var('Error in processing volunteer request:', $error);
				}
    parent::postProcess();
  }

}
