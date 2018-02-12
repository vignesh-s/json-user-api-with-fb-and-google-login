<?php

/*

  Plugin Name: JSON User API with FB & Google Login

  Plugin URI: https://github.com/vignesh-s/json-user-api-with-fb-and-google-login

  Description: Extends the JSON API for RESTful user registration, authentication, password reset, Facebook & Google Login, user meta and BuddyPress Profile related functions.

  Version: 2.8.0.1

  Author: Ali Qureshi, Vignesh Sundar

  Author URI: http://www.parorrey.com/, https://github.com/vignesh-s

  License: GPLv3

 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


if (!is_plugin_active('json-api/json-api.php')) {

    add_action('admin_notices', 'pim_draw_notice_json_api');

    return;

}



add_filter('json_api_controllers', 'pimJsonApiController');

add_filter('json_api_user_controller_path', 'setUserControllerPath');

add_action('init', 'json_api_user_checkAuthCookie', 100);

load_plugin_textdomain('json-api-user', false, basename(dirname(__FILE__)) . '/languages');



function pim_draw_notice_json_api() {

    echo '<div id="message" class="error fade"><p style="line-height: 150%">';

    _e('<strong>JSON API User</strong></a> requires the JSON API plugin to be activated. Please <a href="wordpress.org/plugins/json-api/‎">install / activate JSON API</a> first.', 'json-api-user');

    echo '</p></div>';

}



function pimJsonApiController($aControllers) {

    $aControllers[] = 'User';

    return $aControllers;

}



function setUserControllerPath($sDefaultPath) {

    return dirname(__FILE__) . '/controllers/User.php';

}

function json_api_user_checkAuthCookie($sDefaultPath) {
    global $json_api;

    if ($json_api->query->cookie) {
      $user_id = wp_validate_auth_cookie($json_api->query->cookie, 'logged_in');
      if ($user_id) {
        $user = get_userdata($user_id);

        wp_set_current_user($user->ID, $user->user_login);
      }
    }
}