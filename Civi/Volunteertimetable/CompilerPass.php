<?php

namespace Civi\Volunteertimetable;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use CRM_Yhvrequestform_ExtensionUtil as E;

class CompilerPass implements CompilerPassInterface {

  public function process(ContainerBuilder $container) {
    if ($container->hasDefinition('action_provider')) {
      $actionProviderDefinition = $container->getDefinition('action_provider');
      $actionProviderDefinition->addMethodCall('addAction', array('updateVolunteerTimetable', 'Civi\Volunteertimetable\Actions\VolunteerTimetable', E::ts('Activity: Update Volunteer Timetable'), array()));
      $actionProviderDefinition->addMethodCall('addAction', array('updateEmergencyContact', 'Civi\Volunteertimetable\Actions\EmergencyContact', E::ts('Contact: Update Emergency Contact'), array()));
      $actionProviderDefinition->addMethodCall('addAction', array('UpdateEmergencyRelationship', 'Civi\Volunteertimetable\Actions\UpdateEmergencyRelationship', E::ts('Relationship: Update Emergency Contact Relationship'), array()));
    }
  }
}
