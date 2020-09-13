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
		
				$options = CRM_Yhvrequestform_Utils::getCustomFieldOptions('Location');
				$this->add('select', 'location', ts('Location'), ['' => '- select -'] + $options, TRUE);
				$settings = [
						'control_field' => 'location',
						'data-callback' => 'civicrm/getdept',
						'label' => ts('Division'),
						'data-empty-prompt' => ts('Choose Location first'),
						'data-none-prompt' => ts('- N/A -'),
						'multiple' => FALSE,
						'required' => TRUE,
						'placeholder' => ts('- select -'),
				];
		
		//		$this->addChainSelect('division', $settings);
		
				$settings = [
						'control_field' => 'division',
						'data-callback' => 'civicrm/getdept',
						'label' => ts('Program'),
						'data-empty-prompt' => ts('Choose Division first'),
						'data-none-prompt' => ts('- N/A -'),
						'multiple' => FALSE,
						'required' => TRUE,
						'placeholder' => ts('- select -'),
				];
		
			//	$this->addChainSelect('program', $settings);
				
				$this->add('datepicker', 'request_date', ts('Date of Request'), [], TRUE, ['time' => FALSE]);
  		$this->freeze('request_date');
		
				$jobOptions = CRM_Yhvrequestform_Utils::getCustomFieldOptions('Job');
  		$this->add('select', 'job', ts('Job Description / Duties'), $jobOptions);
		
				$this->add('select', 'languages', ts('Language'), CRM_Yhvrequestform_Utils::getCustomFieldOptions('Language'), FALSE, ['class' => 'crm-select2', 'multiple' => TRUE, 'placeholder' => ts('- select -')]);
				
				$this->addYesNo('computer_skills', ts('Computer Skills'), ['allowClear' => TRUE]);
		
				$this->addYesNo('tb_screening', ts('TB Screening'), ['allowClear' => TRUE]);
		
				$this->addYesNo('police_check', ts('Police Check'), ['allowClear' => TRUE]);
				
				$this->addYesNo('vehicle', ts('Vehicle'), ['allowClear' => TRUE]);
				
				$this->add('text', 'other_skills', ts('Others'));
				
				$this->addRadio('type_of_request', ts('Type'), ['one_time' => ts('One Time'), 'recurring' => ts('Recurring')]);
				
				$this->add('text', 'duration', ts('Duration'));
				
				$this->add('datepicker', 'start_date', ts('Start Date'), [], FALSE, ['time' => FALSE]);
		
				$this->add('datepicker', 'end_date', ts('End Date (if any)'), [], FALSE, ['time' => FALSE]);
				
				// Time table.
				CRM_Yhvrequestform_Utils::renderGridElements($this);
  		
				$this->add('textarea', 'other_remarks', ts('Other Remarks'), 'rows=5, cols=50');
				
				$this->assign('liasonStaff', CRM_Core_Session::singleton()->getLoggedInContactDisplayName());

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
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
		
		/**
			* Validate division / location / program match and suppress unwanted "required" errors
			*/
		private function validateChainSelectFields() {
				return [];
				foreach ($this->_chainSelectFields as $control => $target) {
						if ($this->elementExists($control) && $this->elementExists($target)) {
								$controlValue = (array) $this->getElementValue($control);
								$targetField = $this->getElement($target);
								$controlType = $targetField->getAttribute('data-callback') == 'civicrm/ajax/jqCounty' ? 'stateProvince' : 'country';
								$targetValue = array_filter((array) $targetField->getValue());
								if ($targetValue || $this->getElementError($target)) {
										$options = CRM_Core_BAO_Location::getChainSelectValues($controlValue, $controlType, TRUE);
										if ($targetValue) {
												if (!array_intersect($targetValue, array_keys($options))) {
														$this->setElementError($target, $controlType == 'country' ? ts('State/Province does not match the selected Country') : ts('County does not match the selected State/Province'));
												}
										}
										// Suppress "required" error for field if it has no options
										elseif (!$options) {
												$this->setElementError($target, NULL);
										}
								}
						}
				}
		}
  
  public function setDefaultValues() {
  		$defaults = [
  				'request_date' => date('Y-m-d'),
				];
				return $defaults;
  }

  public function postProcess() {
    $values = $this->exportValues();
    // Key Value pair of field name from form and custom field name.
    $customFields = [
    		'location' => 'Location',
						'division' => 'Division',
						'program' => 'Program',
						'funder' => 'Funder',
						'job' => 'Job',
						'languages' => 'Language',
						'computer_skills' => 'Computer Skills',
						'tb_screening' => 'TB Screening',
						'police_check' => 'Police Check',
						'vehicle' => 'Vehicle',
						'other_skills' => 'Other Skills',
						'type_of_request' => 'Type Of Request',
						'duration' => 'Duration',
						'start_date' => 'Start Date',
						'end_date' => 'End Date',
				];
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

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
