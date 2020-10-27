<?php

use CRM_Yhvrequestform_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Yhvrequestform_Form_VolunteerRequest extends CRM_Core_Form {

  /**
   * @var array
   * @internal to keep track of chain-select fields
   */
  private $_chainSelectFields = [];

  /**
   * Render form and return contents.
   *
   * @return string
   */
  public function toSmarty() {
    self::preProcessChainSelectFields();
    $renderer = $this->getRenderer();
    $this->accept($renderer);
    $content = $renderer->toArray();
    $content['formName'] = $this->getName();
    $content['formClass'] = CRM_Utils_System::getClassName($this);
    return $content;
  }

  /**
   * Set options and attributes for chain select fields based on the controlling field's value
   */
  private function preProcessChainSelectFields() {
    foreach ($this->_chainSelectFields as $control => $target) {
      // The 'target' might get missing if extensions do removeElement() in a form hook.
      if ($this->elementExists($target)) {
        $targetField = $this->getElement($target);
        $targetType = $targetField->getAttribute('data-callback') == 'civicrm/getdept' ? 'division' : 'program';
        $options = [];
        // If the control field is on the form, setup chain-select and dynamically populate options
        if ($this->elementExists($control)) {
          $controlField = $this->getElement($control);
          $controlType = $targetType == 'division' ? 'division' : 'program';

          $targetField->setAttribute('class', $targetField->getAttribute('class') . ' crm-chain-select-target');

          $css = (string) $controlField->getAttribute('class');
          $controlField->updateAttributes([
            'class' => ($css ? "$css " : 'crm-select2 ') . 'crm-chain-select-control',
            'data-target' => $target,
          ]);
          $controlValue = $controlField->getValue()[0];
          if ($controlValue) {
            $options = CRM_Yhvrequestform_Utils::getChainedSelectValue($controlValue, $controlType);
            if (!$options) {
              $targetField->setAttribute('placeholder', $targetField->getAttribute('data-none-prompt'));
            }
          }
          else {
            $targetField->setAttribute('placeholder', $targetField->getAttribute('data-empty-prompt'));
            $targetField->setAttribute('disabled', 'disabled');
          }
        }
        // Control field not present - fall back to loading default options
        else {
          $options = [];
        }
        if (!$targetField->getAttribute('multiple')) {
          $options = ['' => $targetField->getAttribute('placeholder')] + $options;
          $targetField->removeAttribute('placeholder');
        }
        $targetField->_options = [];
        $targetField->loadArray($options);
      }
    }
  }

  /**
   * Create a chain-select target field. All settings are optional; the defaults usually work.
   *
   * @param string $elementName
   * @param array $settings
   *
   * @return HTML_QuickForm_Element
   */
  public function addChainSelect($elementName, $settings = []) {
    $props = $settings;
    CRM_Utils_Array::remove($props, 'label', 'required', 'control_field', 'context');
    $props['class'] = (empty($props['class']) ? '' : "{$props['class']} ") . 'crm-select2';
    $props['data-select-prompt'] = $props['placeholder'];
    $props['data-name'] = $elementName;

    $this->_chainSelectFields[$settings['control_field']] = $elementName;

    return $this->add('select', $elementName, $settings['label'], NULL, $settings['required'], $props);
  }

  public function buildQuickForm() {
    CRM_Utils_System::setTitle('Volunteer Request Form');
    foreach (CRM_Yhvrequestform_Utils::getCustomFields() as $customField) {
      if (in_array($customField, ['Job', 'Location', 'Division', 'Program', 'Type_Of_Request'])) {
        $$customField = CRM_Yhvrequestform_Utils::getCustomFieldDetails($customField);
      }
      else {
	    $$customField = CRM_Yhvrequestform_Utils::getCustomFieldDetails($customField, VOLUNTEER_REQUEST);
      }
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

    $this->add('text', 'liaison_staff', ts('Liaison Staff'), [],TRUE);
    $emailRegex = '/^([a-zA-Z0-9&_?\/`!|#*$^%=~{}+\'-]+|"([\x00-\x0C\x0E-\x21\x23-\x5B\x5D-\x7F]|\\[\x00-\x7F])*")(\.([a-zA-Z0-9&_?\/`!|#*$^%=~{}+\'-]+|"([\x00-\x0C\x0E-\x21\x23-\x5B\x5D-\x7F]|\\[\x00-\x7F])*"))*@yeehong.com$/';
    $this->addRule('liaison_staff', ts('Please provide a valid Yee Hong email address'), 'regex', $emailRegex);

    $this->add('select', 'job', ts($Job['label']), ['' => '- select -'] + CRM_Yhvrequestform_Utils::getCustomFieldOptions('Job'), TRUE);
    $this->assign('jobPreHelp', $Job['help_pre']);
    $this->assign('jobPostHelp', $Job['help_post']);

    $this->add('textarea', 'job_description', ts($Job_Description_Duties['label']), 'rows=5, cols=50');
    $this->assign('jobdescPreHelp', $Job_Description_Duties['help_pre']);
    $this->assign('jobdescPostHelp', $Job_Description_Duties['help_post']);

    $this->add('select', 'languages', ts($Languages['label']), CRM_Yhvrequestform_Utils::getCustomFieldOptions('Languages', VOLUNTEER_REQUEST), FALSE, ['class' => 'crm-select2', 'multiple' => TRUE, 'placeholder' => ts('- select -')]);
    $this->assign('languagesPreHelp', $Languages['help_pre']);
    $this->assign('languagesPostHelp', $Languages['help_post']);

    $this->addYesNo('computer_skills', ts($Computer_Skills['label']), ['allowClear' => TRUE], TRUE);
    $this->assign('computerPreHelp', $Computer_Skills['help_pre']);
    $this->assign('computerPostHelp', $Computer_Skills['help_post']);

    $this->addYesNo('tb_screening', ts($TB_Screening['label']), ['allowClear' => TRUE], TRUE);
    $this->assign('tbPreHelp', $TB_Screening['help_pre']);
    $this->assign('tbPostHelp', $TB_Screening['help_post']);

    $this->addYesNo('police_check', ts($Police_Check['label']), ['allowClear' => TRUE], TRUE);
    $this->assign('policePreHelp', $Police_Check['help_pre']);
    $this->assign('policePostHelp', $Police_Check['help_post']);

    $this->addYesNo('vehicle', ts($Vehicle['label']), ['allowClear' => TRUE], TRUE);
    $this->assign('vehiclePreHelp', $Vehicle['help_pre']);
    $this->assign('vehiclePostHelp', $Vehicle['help_post']);

    $this->add('textarea', 'other_skills', ts($Other_Skills['label']), 'rows=5, cols=50');
    $this->assign('otherPreHelp', $Other_Skills['help_pre']);
    $this->assign('otherPostHelp', $Other_Skills['help_post']);

    $this->addRadio('type_of_request', ts($Type_Of_Request['label']), CRM_Yhvrequestform_Utils::getCustomFieldOptions('Type_Of_Request', VOLUNTEERING_CUSTOM), [], NULL, 'TRUE');
    $this->assign('requestPreHelp', $Type_Of_Request['help_pre']);
    $this->assign('requestPostHelp', $Type_Of_Request['help_post']);

    $this->add('text', 'duration', ts('Duration Of Shift'));
    $this->assign('durationPreHelp', 'Example: 2 hours');
    $this->assign('durationPostHelp', '');

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

    //$this->assign('liaisonStaff', CRM_Core_Session::singleton()->getLoggedInContactDisplayName());

    $this->addButtons([
        [
            'type' => 'upload',
            'name' => ts('Submit Volunteer Request'),
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
      'computer_skills' => 0,
      'vehicle' => 0,
      'duration' => 2,
      'type_of_request' => 'recurring',
    ];
    return $defaults;
  }

  /**
   * Validate country / state / county match and suppress unwanted "required" errors
   */
  private function validateChainSelectFields() {
    foreach ($this->_chainSelectFields as $control => $target) {
      if ($this->elementExists($control) && $this->elementExists($target)) {
        $controlValue = (array) $this->getElementValue($control);
        $targetField = $this->getElement($target);
        $controlType = $targetField->getAttribute('data-callback') == 'civicrm/getdept' ? 'division' : 'program';
        $targetValue = array_filter((array) $targetField->getValue());
        if ($targetValue || $this->getElementError($target)) {
          $options = CRM_Yhvrequestform_Utils::getChainedSelectValue($controlValue, $controlType);
          // Suppress "required" error for field if it has no options
          if (!$options) {
            $this->setElementError($target, NULL);
          }
        }
      }
    }
  }

  /**
   * Performs the server side validation.
   * @since     1.0
   * @return bool
   *   true if no error found
   * @throws    HTML_QuickForm_Error
   */
  public function validate() {
    HTML_QuickForm_Page::validate();

    self::validateChainSelectFields();

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
      if (!empty($values[$field]) || $values[$field] == '0') {
        $customName = CRM_Yhvrequestform_Utils::getCustomFieldID($name);
        $customParams[$customName] = $values[$field];
      }
    }
    $sourceCid = CRM_Yhvrequestform_Utils::createLiaisonContact($values['liaison_staff']);
    // Create Activity.
    $activityParams = [
        'activity_type_id' => "Volunteer Request",
        'source_contact_id' => $sourceCid,
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

      CRM_Core_Session::setStatus(ts("Thank You!"), ts("Your volunteer request has been successfully submitted"), "success");
      CRM_Utils_System::redirect('http://yhv.jmaconsulting.biz/request-submitted-successfully/');
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
