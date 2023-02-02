<?php

$wpcf7cf_settings = wpcf7cf_get_settings();

if (!isset($wpcf7cf_settings['license_key'])) {
    $wpcf7cf_settings['license_key'] = '';
}

// check for updates
require_once WPCF7CF_PLUGIN_DIR.'/pro/plugin-update-checker/plugin-update-checker.php';
$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://shop.bdwm.be/update_plugin/wpcf7cf/wpcf7cfpro.json.php?key='.$wpcf7cf_settings['license_key'],
	WPCF7CF_PLUGIN_DIR.'/contact-form-7-conditional-fields.php',
	WPCF7CF_PLUGIN_NAME
);


if( is_admin() ) {
	add_action('in_plugin_update_message-'.WPCF7CF_PLUGIN_NAME.'/contact-form-7-conditional-fields.php', 'wpcf7cf_modify_plugin_update_message', 99, 2 );
}

function wpcf7cf_modify_plugin_update_message( $plugin_data, $response ) {
	global $wpcf7cf_settings;

	if( !wpcf7cf_is_well_formed_license_key($wpcf7cf_settings['license_key']) ) {
		echo '</p><p>' . sprintf( __('To enable automatic updates, please enter your license key on the <a href="%s">Contact > Conditional Fields</a> page. If you don\'t have a licence key, please <a href="%s" target="_blank">purchase a copy of CF7CF Pro</a>.', 'wpcf7cf'), admin_url('/admin.php?page='.WPCF7CF_SLUG), 'https://conditional-fields-cf7.bdwm.be/contact-form-7-conditional-fields-pro/' );
		return;
	}

	if ($response->package === 'INVALID_KEY') {
		echo '</p><p>' . sprintf( __('<strong>Invalid license key</strong>. please enter your license key on the <a href="%s">Contact > Conditional Fields</a> page. If you don\'t have a licence key, please <a href="%s" target="_blank">purchase a copy of CF7CF Pro</a>.', 'wpcf7cf'), admin_url('/admin.php?page='.WPCF7CF_SLUG), 'https://conditional-fields-cf7.bdwm.be/contact-form-7-conditional-fields-pro/' );
		return;
	}

	if ($response->package === 'EXPIRED_KEY') {
		echo '</p><p>' . sprintf( __('<strong>License key expired</strong>. Please <a href="%s" target="_blank">renew your license</a>.', 'wpcf7cf'), 'https://shop.bdwm.be/contact-form-7-conditional-fields-pro/' );
		return;
	}
}

function wpcf7cf_is_well_formed_license_key($key) {

	if ($key && strlen($key) == 24) {
		return true;
	}
	return false;

}