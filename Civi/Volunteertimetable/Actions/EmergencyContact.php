<?php

namespace Civi\Volunteertimetable\Actions;

use \Civi\ActionProvider\Action\AbstractAction;
use \Civi\ActionProvider\Parameter\ParameterBagInterface;
use \Civi\ActionProvider\Parameter\SpecificationBag;
use \Civi\ActionProvider\Parameter\Specification;

use CRM_Yhvrequestform_ExtensionUtil as E;

class EmergencyContact extends AbstractAction {

  /**
   * Run the action
   *
   * @param ParameterInterface $parameters
   *   The parameters to this action.
   * @param ParameterBagInterface $output
   * 	 The parameters this action can send back
   * @return void
   */
  protected function doAction(ParameterBagInterface $parameters, ParameterBagInterface $output) {
    $sql = "
        SELECT contact.id  AS id, 10 as weight
        FROM civicrm_contact as contact
        INNER JOIN civicrm_phone phone ON contact.id = phone.contact_id
        INNER JOIN civicrm_relationship r ON r.contact_id_b = contact_id
        WHERE contact.is_deleted = 0 AND contact.is_deceased = 0 AND contact.contact_type = 'Individual'
        AND phone.phone = %1
        AND contact.first_name = %2 AND contact.last_name = %3
        AND r.relationship_type_id = %4
        AND r.contact_id_a = %5
        ORDER BY weight ASC
    ";
    $sqlParams[1] = array($parameters->getParameter('phone'), 'String');
    $sqlParams[2] = array($parameters->getParameter('first_name'), 'String');
    $sqlParams[3] = array($parameters->getParameter('last_name'), 'String');
    $relationshipTypeId = \CRM_Core_DAO::singleValueQuery("SELECT id FROM civicrm_relationship_type WHERE name_a_b = %1", [1 => [$parameters->getParameter('relationship_type'), 'String']]);
    $sqlParams[4] = array($relationshipTypeId, 'Integer');
    $sqlParams[5] = array($parameters->getParameter('contact_id_a'), 'Integer');

    $dao = \CRM_Core_DAO::executeQuery($sql, $sqlParams);
    if ($dao->fetch()) {
      $output->setParameter('contact_id', $dao->id);
    }
  }

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
    return new SpecificationBag(array(
      new Specification('first_name', 'String', E::ts('First name'), true),
      new Specification('last_name', 'String', E::ts('Last name'), true),
      new Specification('phone', 'String', E::ts('Phone'), true),
      new Specification('relationship_type', 'String', E::ts('Relationship Type'), true),
      new Specification('contact_id_a', 'Integer', E::ts('Contact ID A'), true),
    ));
  }

  /**
   * Returns the specification of the output parameters of this action.
   *
   * This function could be overriden by child classes.
   *
   * @return SpecificationBag
   */
  public function getOutputSpecification() {
    return new SpecificationBag(array(
      new Specification('contact_id', 'Integer', E::ts('Contact ID'), true)
    ));
  }

  public function getHelpText() {
    return E::ts('This action looks up a contact by its name, phone number and relationship type.');
  }

}
