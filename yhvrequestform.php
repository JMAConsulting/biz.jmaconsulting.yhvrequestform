<?php

require_once 'yhvrequestform.civix.php';
require_once 'yhvrequestform.constants.php';
use CRM_Yhvrequestform_ExtensionUtil as E;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/ 
 */
function yhvrequestform_civicrm_config(&$config) {
  _yhvrequestform_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function yhvrequestform_civicrm_xmlMenu(&$files) {
  _yhvrequestform_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function yhvrequestform_civicrm_install() {
  _yhvrequestform_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function yhvrequestform_civicrm_postInstall() {
  _yhvrequestform_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function yhvrequestform_civicrm_uninstall() {
  _yhvrequestform_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function yhvrequestform_civicrm_enable() {
  _yhvrequestform_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function yhvrequestform_civicrm_disable() {
  _yhvrequestform_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function yhvrequestform_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _yhvrequestform_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function yhvrequestform_civicrm_managed(&$entities) {
  _yhvrequestform_civix_civicrm_managed($entities);
}

function yhvrequestform_civicrm_container(ContainerBuilder $container) {
		$container->addCompilerPass(new Civi\Volunteertimetable\CompilerPass());
}


/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function yhvrequestform_civicrm_caseTypes(&$caseTypes) {
  _yhvrequestform_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function yhvrequestform_civicrm_angularModules(&$angularModules) {
  _yhvrequestform_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function yhvrequestform_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _yhvrequestform_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function yhvrequestform_civicrm_entityTypes(&$entityTypes) {
  _yhvrequestform_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function yhvrequestform_civicrm_themes(&$themes) {
  _yhvrequestform_civix_civicrm_themes($themes);
}

/**
	* Implements hook_civicrm_buildForm().
	*
	* Set a default value for an event price set field.
	*
	* @param string $formName
	* @param CRM_Core_Form $form
	*/
function yhvrequestform_civicrm_buildForm($formName, &$form) {
		if ($formName == "CRM_Activity_Form_Activity") {
				if (in_array($form->_action, [CRM_Core_Action::VIEW, CRM_Core_Action::UPDATE])) {
						// Render the timetable if this is a volunteer request activity.
						$activityTypes = CRM_Activity_BAO_Activity::buildOptions('activity_type_id');
						if (in_array($activityTypes[$form->_activityTypeId], ['Volunteer Request', 'Volunteer'])) {
								// Render the grid.
								if ($activityTypes[$form->_activityTypeId] == 'Volunteer Request') {
										CRM_Yhvrequestform_Utils::renderGridElements($form);
								}
								else {
										CRM_Yhvrequestform_Utils::renderGridElements($form, TRUE);
								}
								CRM_Core_Region::instance('page-body')->add(array(
										'template' => 'CRM/Yhvrequestform/Form/VolunteerTimetableView.tpl',
								));
								
								// Set defaults.
								$params = ['activity_id' => $form->_activityId];
								$timeTable = CRM_Yhvrequestform_BAO_VolunteerTimetable::getTimeTable($params);
								if (!empty($timeTable)) {
										$timeTableDefaults = CRM_Yhvrequestform_Utils::getFormattedValues($timeTable);
										$form->setDefaults($timeTableDefaults);
								}
						}
				}
		}
}

/**
	* Implements hook_civicrm_postProcess().
	*
	* @param string $formName
	* @param CRM_Core_Form $form
	*/
function yhvrequestform_civicrm_postProcess($formName, $form) {
		if ($formName == "CRM_Activity_Form_Activity") {
				$activityTypes = CRM_Activity_BAO_Activity::buildOptions('activity_type_id');
				if ($activityTypes[$form->_activityTypeId] == "Volunteer Request") {
						CRM_Yhvrequestform_BAO_VolunteerTimetable::add($form->_activityId, $form->_submitValues, TRUE);
				}
		}
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *
function yhvrequestform_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 *
function yhvrequestform_civicrm_navigationMenu(&$menu) {
  _yhvrequestform_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _yhvrequestform_civix_navigationMenu($menu);
} // */
