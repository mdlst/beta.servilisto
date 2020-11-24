<?php
/**
 *
 * Check user is confirm or not
 * @param int|string $user_id int user id if null user_id = current_user
 * @return bool True on success, False on failure
 */
function et_is_user_active($user_id = ''){
	global $current_user;
	if($user_id == "") $user_id == $current_user->ID;
	$confirm = get_user_meta( $user_id, 'register_status', true );
	return $confirm == "" ? true : false;
}

/**
 * Log a user in in via user information.
 * @param $username
 * @param $password
 * @param bool $secure_cookie Whether to use secure cookie.
 * @return WP_User on success or WP_Error on failure
 *
 * @since 1.0
 */
function et_login( $username, $password, $secure_cookie = false ){
	global $current_user;

	// check users if he is member of this blog
	$user = get_user_by('login', $username);
	if ( !$user || !is_user_member_of_blog( $user->ID ) )
		return new WP_Error('login_failed', "Login failed");

	$creds['user_login'] = $username;
	$creds['user_password'] = $password;
	$creds['remember'] = true;
	
	//$result = &wp_signon( $creds, $secure_cookie );
	$result = wp_signon( $creds, $secure_cookie );
	
	if ( $result instanceof WP_User )
		$current_user = $result;
	
	return $result;
}

/**
 * Perform log user in via email
 * @param $email
 * @param $password
 * @param bool $secure_cookie
 * @return WP_Error|WP_User
 * @internal param $ @remember allow auto log for next time
 * @internal param $ @secure_cookie ...
 *
 * @since 1.0
 */
function et_login_by_email( $email, $password, $secure_cookie = false ){
	$user = get_user_by('email', $email);
	if ( $user != false )
		return et_login($user->user_login, $password, $secure_cookie);
	else 
		return new WP_Error(403, __('This email address was not found.', ET_DOMAIN));
}

/**
 * Register user by given user data
 * @param $userdata
 * @param string $role
 * @param bool $auto_login
 * @return array $result
 * @internal param array $user information of new user:
 *    - username : new user name
 *    - password : new password
 *    - email : email
 * @since 1.0
 */
function et_register( $userdata, $role = 'subscriber', $auto_login = false ){
	extract($userdata);
	
	if (!preg_match("/^[a-zA-Z0-9_]+$/", $userdata['user_login'])){
		return new WP_Error('username_invalid', __('Username is invalid', ET_DOMAIN));
	}

	$userdata['role']	= $role;
	$result = wp_insert_user( $userdata );

	// if creating user false
	if ( $result instanceof WP_Error ){
		return $result;
	}

	do_action('et_after_register', $result , $role );

	// auto login
	if ( $auto_login ) {
		et_login($user_login , $user_pass, true);
	}
	
	// then return user id
	return $result;
}
/**
 * Handles resetting the user's password.
 *
 * @param object $user The user
 * @param string $new_pass New password for the user in plaintext
 */
function et_reset_password($user, $new_pass) {
	do_action('et_password_reset', $user, $new_pass);

	wp_set_password($new_pass, $user->ID);

	wp_password_change_notification($user);
}
/**
 * Retrieves a user row based on password reset key and login
 *
 * @uses $wpdb WordPress Database object
 *
 * @param string $key Hash to validate sending user's password
 * @param string $login The user login
 * @return object|WP_Error User's database row on success, error object for invalid keys
 */
function et_check_password_reset_key($key, $login) {
	global $wpdb;

	$key = preg_replace('/[^a-z0-9]/i', '', $key);

	if ( empty( $key ) || !is_string( $key ) )
		return new WP_Error('invalid_key', __('Invalid key', ET_DOMAIN));

	if ( empty($login) || !is_string($login) )
		return new WP_Error('invalid_key', __('Invalid key', ET_DOMAIN));

	$user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login));


	if ( empty( $user ) )
		return new WP_Error('invalid_key', __('Invalid key', ET_DOMAIN));

	return $user;
}

/**
 * Handles sending password retrieval email to user.
 *
 * @uses $wpdb WordPress Database object
 *
 * @param $user_data
 * @param $errors
 * @return bool|WP_Error True: when finish. WP_Error on error
 */
function et_retrieve_password($user_data, $errors) {
	global $wpdb;

	do_action('lostpassword_post');

	if ( $errors->get_error_code() )
		return $errors;

	if ( !$user_data ) {
		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email address.', ET_DOMAIN));
		return $errors;
	}

	// redefining user_login ensures we return the right case in the email
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	do_action('retreive_password', $user_login);  // Misspelled and deprecated
	do_action('retrieve_password', $user_login);

	$allow = apply_filters('allow_password_reset', true, $user_data->ID);

	if ( ! $allow )
		return new WP_Error('no_password_reset', __('Password reset is not allowed for this user', ET_DOMAIN));
	else if ( is_wp_error($allow) )
		return $allow;

	$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
	if ( empty($key) ) {
		// Generate something random for a key...
		$key = wp_generate_password(20, false);
		do_action('retrieve_password_key', $user_login, $key);
		// Now insert the new md5 key into the db
		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
	}
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= "From: ".get_option('blogname')." < ".get_option('admin_email') ."> \r\n";
		
	$message = __('There is a request to reset the password for the following account:', ET_DOMAIN) . "\r\n\r\n";
	$message .= network_home_url( '/' ) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s', ET_DOMAIN), $user_login) . "\r\n\r\n";
	$message .= __('If this was a mistake, just ignore this email and nothing will happen.',ET_DOMAIN) . "\r\n\r\n";
	$message .= __('To reset your password, visit the following link:', ET_DOMAIN) . "\r\n\r\n";
	//$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
	$site = apply_filters('et_reset_password_link',  network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login'), $key, $user_login );
	$message .= '<' . $site . ">\r\n";

	if ( is_multisite() )
		$blogname = $GLOBALS['current_site']->site_name;
	else
		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf( __('[%s] Password Reset', ET_DOMAIN), $blogname );

	$title = apply_filters('et_retrieve_password_title', $title);
	$message = apply_filters('et_retrieve_password_message', $message, $key, $user_data);

	if ( $message && !wp_mail($user_email, $title, $message , $headers) )
		wp_die( __('The email could not be sent.', ET_DOMAIN) . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...', ET_DOMAIN) );

	return true;
}

/**
 * Get user role by user id 
 * author Tuandq
 * @param $user_id
 * @return user role
 */
function de_get_user_role( $user_id ){
  	$user_data = get_userdata( $user_id );
  	if(!empty( $user_data->roles ))
      	return $user_data->roles[0];
  	return false;
}