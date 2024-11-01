<?php
/**
 * Get the current user's role
 */
function nlws_wu_get_user_role() {
	global $wp_roles;
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	$role = array_shift($roles);
	return isset($wp_roles->role_names[$role]) ? translate_user_role($wp_roles->role_names[$role] ) : false;
}

/**
 * Adds the necessary script to the header
 */
function nlws_wu_add_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tabs');
}

function nlws_wu_admin_header() {
	echo '<link type="text/css" rel="stylesheet" href="' . NLWS_WU_URL . 'css/jquery-ui-1.8.16.custom.css" />';
	echo '<script type="text/javascript" src="' . NLWS_WU_URL . 'js/wu.js"></script>';
}

/**
 * Outputs a checked="checked" if the value is checked
 */
function nlws_wu_checked( $value, $optionname ) {
	if ($optionname == $value)
		echo 'checked="checked"';	
}

/**
 * Plugin activation hook
 */
function nlws_wu_activate() {
	// Only send if the plugin hasn't been activated before
	$isinstalled = get_option('nlws_wu_version');
	
	if (!$isinstalled) {
	}
	
	// Update version
	update_option( 'nlws_wu_version', NLWS_WU_VERSION );
	
	// Check to see if there are any default settings
	$hasglobaloptions = get_option('nlws_wu_settings');
	
	if (!$hasglobaloptions) {
		global $wp_roles;
		$roles = $wp_roles->get_names();
		
		$defaultoptions = array(
			"non_logged_in" => 'Welcome Guest!  Please [LOGINOUT] to the site or [REGISTERADMIN].',
			"logged_in" => 'Greetings [USERNAME]! [REGISTERADMIN] or [LOGINOUT].',
			"login_link" => 'login',
			"register_link" => 'signup'
		);
		
		foreach ($roles as $role) {
			$defaultoptions[$role . '_' . "logged_in"] = "Hello [USERNAME]!  You have been granted " . $role . " site access.  Please [REGISTERADMIN] or [LOGINOUT].";
			$defaultoptions[$role . '_' . "display_name"] = 'user_login';
			$defaultoptions[$role . '_' . "siteadmin_link"] = 'use the backend of the site';
			$defaultoptions[$role . '_' . "logout_link"] = 'logout';
		}
		update_option('nlws_wu_settings', $defaultoptions);
	}
}


/**
 * Register settings
 */
function nlws_wu_register_settings() {
	// Register the settings
	register_setting( 'nlws_wu_settings', 'nlws_wu_settings' );
}

/**
 * Displays customizable user welcome message(s)
 */
function nlws_wu() {
	$options = get_option('nlws_wu_settings');
	
	if (is_user_logged_in()) {
		/**
		 * Get the user role and
		 * then replace the username
		 */
		
		$userrole = nlws_wu_get_user_role();
		$output = $options[$userrole . '_' . 'logged_in'];
		global $current_user;
		get_currentuserinfo();
		switch ($options[$userrole . '_' . 'display_name']) {
			case 'user_login' :
				$username = $current_user->user_login;
				break;
			case 'user_email' :
				$username = $current_user->user_email;
				break;
			case 'user_firstname' :
				$username = $current_user->user_firstname;
				break;
			case 'user_lastname' :
				$username = $current_user->user_lastname;
				break;
			case 'user_fullname' :
				$username = $current_user->user_firstname . ' ' . $current_user->user_lastname;
				break;
			case 'display_name' :
				$username = $current_user->display_name;
				break;
		}
		
		
		/**
		 * Get the admin link text
		 */
		$adminlink = wp_register('', '', 0);
		$adminlink = str_replace('Site Admin', $options[$userrole . '_' . 'siteadmin_link'], $adminlink);
		
		
		/**
		 * Get the logout text
		 */
		$logoutlink = wp_loginout('', 0);
		$logoutlink = str_replace('Log out', $options[$userrole . '_' . 'logout_link'], $logoutlink);
		
		
		/**
		 * Create our string and output everything
		 */
		$output = str_replace('[USERNAME]', $username, $output);
		$output = str_replace('[REGISTERADMIN]', $adminlink, $output);
		$output = str_replace('[LOGINOUT]', $logoutlink, $output);
		echo $output;
	}
	else {
		$output = $options['non_logged_in'];
		
		/**
		 * Get the register link text
		 */
		$registerlink = wp_register('', '', 0);
		$registerlink = str_replace('Register', $options['register_link'], $registerlink);


		/**
		 * Get the login text
		 */
		$loginlink = wp_loginout('', 0);
		$loginlink = str_replace('Log in', $options['login_link'], $loginlink);
		
		
		/**
		 * Create our string and output everything
		 */
		if ($registerlink == '') {
			// We didn't find a match, so replace it with a warning
			$warningmessage = '<span style="display: inline-block; margin-left: 3px; border: 1px solid #ccc; background: #ddd; color: #ff0000; font-size: 11px; padding: 0px 5px;">Registrations are currently disabled on your site.&nbsp;<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php" title="Fix your registrations settings" style="font-weight: bold;">Adjust your settings here...</a></span>';
			$output = str_replace('[REGISTERADMIN]', $warningmessage, $output);
		} else
				$output = str_replace('[REGISTERADMIN]', $registerlink, $output);
		$output = str_replace('[LOGINOUT]', $loginlink, $output);
		echo $output;
	}
}