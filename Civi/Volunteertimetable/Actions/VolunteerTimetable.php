<?php

namespace Civi\Volunteertimetable\Actions;

use \Civi\ActionProvider\Action\AbstractAction;
use \Civi\ActionProvider\Parameter\ParameterBagInterface;
use \Civi\ActionProvider\Parameter\SpecificationBag;
use \Civi\ActionProvider\Parameter\Specification;

use CRM_Yhvrequestform_ExtensionUtil as E;

class VolunteerTimetable extends AbstractAction {

  /**
   * Returns the specification of the configuration options for the actual action.
   *
   * @return SpecificationBag
   */
  public function getConfigurationSpecification() {
    return new SpecificationBag();
  }

  /**
   * Returns the specification of the parameters of the actual action.
   *
   * @return SpecificationBag
   */
  public function getParameterSpecification() {
    $specs = [new Specification('id', 'Integer', E::ts('Contact ID'), true)];
    $timePeriods = \CRM_Core_OptionGroup::values('yhv_time_period');
    $days = \CRM_Core_OptionGroup::values('yhv_days');
    for ($i = 1; $i <= count($timePeriods); $i++) {
      for ($j = 1; $j <= count($days); $j++) {
        $label = $days[$j] . " - " . $timePeriods[$i];
        $specs[] = new Specification($j . '_' . $i, 'Integer', E::ts($label), false);
      }
    }

    $bag = new SpecificationBag($specs);
    return $bag;
  }

  /**
   * Returns the specification of the output parameters of this action.
   *
   * This function could be overriden by child classes.
   *
   * @return SpecificationBag
   */
  public function getOutputSpecification() {
    return new SpecificationBag(array());
  }

  /**
   * Run the action
   *
   * @param ParameterBagInterface $parameters
   *   The parameters to this action.
   * @param ParameterBagInterface $output
   *   The parameters this action can send back
   * @return void
   * @throws \Exception
   */
  protected function doAction(ParameterBagInterface $parameters, ParameterBagInterface $output) {
    // Get the contact and the event.
    $contactParams['entity_id'] = $parameters->getParameter('id');
    $timePeriods = \CRM_Core_OptionGroup::values('yhv_time_period');
    $days = \CRM_Core_OptionGroup::values('yhv_days');
    for ($i = 1; $i <= count($timePeriods); $i++) {
      for ($j = 1; $j <= count($days); $j++) {
        $name = $j . '_' . $i;
        $contactParams[$name] = $parameters->getParameter($name);
      }
    }
    try {
      // Save the timetable to the activity.
      \CRM_Yhvrequestform_BAO_VolunteerTimetable::add($contactParams['entity_id'], $contactParams, TRUE, 'civicrm_contact');
    } catch (\Exception $e) {
      // Do nothing.
    }
  }

}
