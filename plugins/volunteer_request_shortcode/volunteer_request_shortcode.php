<?php
/*
Plugin Name: Volunteer Request Shortcode
Description: Allow volunteer request form to be used as shortcodes in Wordpress pages and posts
Version: 1.0
Author: JMA Consulting
Author URI: http://jmaconsulting.biz
License: AGPL3
*/
add_filter('civicrm_shortcode_preprocess_atts', array('CiviCRM_For_WordPress_Shortcodes_VolunteerRequest', 'civicrm_shortcode_preprocess_atts'), 10, 2);
// FIXME: Uncomment to allow support for multiple shortcodes on pages.
/**
 * Define CiviCRM_For_WordPress_Shortcodes Class
 */
class CiviCRM_For_WordPress_Shortcodes_VolunteerRequest {
  function civicrm_shortcode_preprocess_atts($args, $shortcode_atts) {
    if ($shortcode_atts['component'] == 'volunteer_request') {
      $args['q'] = 'civicrm/volunteer_request';
      return $args;
    }
  }
  // FIXME: Seems like multiple shortcodes don't work on a single page. Also,
  function civicrm_shortcode_get_data($data, $atts, $args) {
    if ($atts['component'] == 'volunteer_request') {
      $data = [
        'title' => ts('Volunteer Request'),
        'text' => 'Submit a Volunteer Request',
      ];
      return $data;
    }
  }
}