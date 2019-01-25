<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth
*
* Version: 2.5.2
*
* Author: Ben Edmunds
*		  ben.edmunds@gmail.com
*         @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

/*
| -------------------------------------------------------------------------
| Tables.
| -------------------------------------------------------------------------
| Database table names.
*/
$config['tables']['users']           = 'users';
$config['tables']['groups']          = 'groups';
$config['tables']['users_groups']    = 'users_groups';
$config['tables']['login_attempts']  = 'login_attempts';

/*
 | Users table column and Group table column you want to join WITH.
 |
 | Joins from users.id
 | Joins from groups.id
 */
$config['join']['users']  = 'user_id';
$config['join']['groups'] = 'group_id';

/*
 | -------------------------------------------------------------------------
 | Hash Method (sha1 or bcrypt)
 | -------------------------------------------------------------------------
 | Bcrypt is available in PHP 5.3+
 |
 | IMPORTANT: Based on the recommendation by many professionals, it is highly recommended to use
 | bcrypt instead of sha1.
 |
 | NOTE: If you use bcrypt you will need to increase your password column character limit to (80)
 |
 | Below there is "default_rounds" setting.  This defines how strong the encryption will be,
 | but remember the more rounds you set the longer it will take to hash (CPU usage) So adjust
 | this based on your server hardware.
 |
 | If you are using Bcrypt the Admin password field also needs to be changed in order to login as admin:
 | $2y$: $2y$08$200Z6ZZbp3RAEXoaWcMA6uJOFicwNZaqk4oDhqTUiFXFe63MG.Daa
 | $2a$: $2a$08$6TTcWD1CJ8pzDy.2U3mdi.tpl.nYOR1pwYXwblZdyQd9SL16B7Cqa
 |
 | Be careful how high you set max_rounds, I would do your own testing on how long it takes
 | to encrypt with x rounds.
 |
 | salt_prefix: Used for bcrypt. Versions of PHP before 5.3.7 only support "$2a$" as the salt prefix
 | Versions 5.3.7 or greater should use the default of "$2y$".
 */
$config['hash_method']    = 'bcrypt';	// sha1 or bcrypt, bcrypt is STRONGLY recommended
$config['default_rounds'] = 8;		// This does not apply if random_rounds is set to true
$config['random_rounds']  = FALSE;
$config['min_rounds']     = 5;
$config['max_rounds']     = 9;
$config['salt_prefix']    = version_compare(PHP_VERSION, '5.3.7', '<') ? '$2a$' : '$2y$';

/*
 | -------------------------------------------------------------------------
 | Authentication options.
 | -------------------------------------------------------------------------
 | maximum_login_attempts: This maximum is not enforced by the library, but is
 | used by $this->ion_auth->is_max_login_attempts_exceeded().
 | The controller should check this function and act
 | appropriately. If this variable set to 0, there is no maximum.
 */
$CI =& get_instance();

		$results = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_SETTINGS_FIELDS).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.type_id WHERE type_slug="SYSTEM_SETTINGS"')->result();

		
		$site_settings = array();
		foreach($results as $r) {
			$site_settings[strtolower($r->field_key)] =  $r->field_output_value;
		}
		// echo "<pre>"; print_r($site_settings); die();
		$CI->config->set_item('site_settings', (object)$site_settings);

		/****** Get Email Settings ******/
		$results = $CI->db->query('SELECT types.* FROM '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.parent_id WHERE sf.type_slug="EMAIL_SETTINGS"')->result();
		
		$email_settings = array();
		foreach($results as $r) {
			$fields = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_SETTINGS_FIELDS).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.type_id WHERE sf.type_id="'.$r->type_id.'"')->result();
			if(!empty($fields))
			{
				foreach($fields as $key)
				{
					$email_settings [strtolower($r->type_title)][$key->field_key] =  $key->field_output_value;
				}				
				$email_settings [strtolower($r->type_title)]['is_default'] = $r->is_default;
			}
			else
			{
			$email_settings [strtolower($r->type_title)] =  $r->is_default;
			}
		}
		$CI->config->set_item('email_settings',(object) $email_settings);
		
		/****** Get SMS Settings ******/
		$results = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_SETTINGS_FIELDS).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.type_id WHERE type_slug="SMS_SETTINGS"')->result();
		
		$sms_settings = array();
		foreach($results as $r) {
			$sms_settings [strtolower($r->field_key)] =  $r->field_output_value;
		}
		$CI->config->set_item('sms_settings',(object)$sms_settings);

		/****** Get SEO Settings ******/
		$results = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_SETTINGS_FIELDS).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.type_id WHERE type_slug="SEO_SETTINGS"')->result();
		
		$seo_settings = array();
		foreach($results as $r) {
			$seo_settings[strtolower($r->field_key)] =  $r->field_output_value;
		}
		$CI->config->set_item('seo_settings', (object)$seo_settings);
		
			/****** Get Registration Settings ******/
		$results = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_SETTINGS_FIELDS).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.type_id WHERE type_slug="REGISTRATION_SETTINGS"')->result();
		
		$registration_settings = array();
		foreach($results as $r) {
			$registration_settings [strtolower($r->field_key)] =  $r->field_output_value;
		}
		 
		$CI->config->set_item('registration_settings', (object)$registration_settings);
		
	
		/****** Get Social Settings ******/
		$results = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_SETTINGS_FIELDS).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.type_id WHERE type_slug="SOCIAL_SETTINGS"')->result();
		$social_settings = array();
		foreach($results as $r) {
			$social_settings [strtolower($r->field_key)] =  $r->field_output_value;
		}
		$CI->config->set_item('social_settings', (object)$social_settings);

		/****** Get Contact Details Settings ******/
		$results = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_SETTINGS_FIELDS).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.type_id WHERE type_slug="CONTACT_SETTINGS"')->result();
		$contact_settings = array();
		foreach($results as $r) {
			$contact_settings [strtolower($r->field_key)] =  $r->field_output_value;
		}
		$CI->config->set_item('contact_settings', (object)$contact_settings);


		/****** Get PayU Settings ******/
		$results = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_SETTINGS_FIELDS).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.type_id WHERE type_slug="PAYU_SETTINGS"')->result();
		$payu_settings = array();
		foreach($results as $r) {
			$payu_settings[strtolower($r->field_key)] =  $r->field_output_value;
		}
		$CI->config->set_item('payu_settings', (object)$payu_settings);
		
		/****** Get Paypal Settings ******/
		$results = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_SETTINGS_FIELDS).' sf INNER JOIN '.$CI->db->dbprefix(TBL_SETTINGS_TYPES).' types on sf.type_id = types.type_id WHERE type_slug="PAYPAL_SETTINGS"')->result();
		
		$paypal_settings = array();
		foreach($results as $r) {
			$paypal_settings[strtolower($r->field_key)] =  $r->field_output_value;
		}
		$CI->config->set_item('paypal_settings', (object)$paypal_settings);
		
		
		/******Static Pages******/
		$results = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix(TBL_PAGES).' ')->result();
		
		$pages = array();
		foreach($results as $r) {
			$pages[$r->id] =  $r->description;
		}
		$CI->config->set_item('pages',$pages);
		


		/******Sections******/
		$sections_recs = $CI->db->query('SELECT * FROM '.$CI->db->dbprefix('sections').' ')->result();
		
		$sections = array();
		foreach($sections_recs as $sec) {
			$sections[$sec->name] =  $sec->description;
		}
		$CI->config->set_item('sections',(object)$sections);


	
$siteSettings = $CI->config->item('site_settings');
$emailSettings = $CI->config->item('email_settings');
$regSettings = $CI->config->item('registration_settings');


$config['site_title']                 = $siteSettings->site_title;        // Site Title, example.com
$config['admin_email']                = $siteSettings->portal_email; // Admin Email, admin@example.com

$config['default_group']             = 'student';           // Default group, use name
$config['member_group']              = 'student';           // Default group, use name
$config['admin_group']               = 'admin';             // Default administrators group, use name
$config['tutor_group']           	 =	'tutor';          // tutor User Group
$config['institute_group']           =	'institute';          // institute User Group

$config['identity']                   = 'email';             // You can use any unique column in your table as identity column. The values in this column, alongside password, will be used for login purposes
$config['min_password_length']        = 6;                   // Minimum Required Length of Password
$config['max_password_length']        = 20;                  // Maximum Allowed Length of Password
$config['email_activation']           = (isset($regSettings->email_activation) && $regSettings->email_activation == "TRUE") ? TRUE : FALSE ; // Email Activation for registration
$config['manual_activation']          = (isset($regSettings->email_activation) && $regSettings->email_activation == "TRUE") ? TRUE : FALSE;               // Manual Activation for registration
$config['remember_users']             = TRUE;                // Allow users to be remembered and enable auto-login
$config['user_expire']                = 86500;               // How long to remember the user (seconds). Set to zero for no expiration
$config['user_extend_on_login']       = FALSE;               // Extend the users cookies every time they auto-login
$config['track_login_attempts']       = TRUE;               // Track the number of failed login attempts for each user or ip.
$config['track_login_ip_address']     = (isset($regSettings->track_login_ip_address) && $regSettings->track_login_ip_address == "TRUE") ? TRUE : FALSE ;            // Track login attempts by IP Address, if FALSE will track based on identity. (Default: TRUE)
$config['maximum_login_attempts']     = isset($regSettings->maximum_login_attempts) ? $regSettings->maximum_login_attempts: 3 ;                   // The maximum number of failed login attempts.
$config['lockout_time']               = isset($regSettings->lockout_time) ? $regSettings->lockout_time: FALSE ;                            // The number of seconds to lockout an account due to exceeded attempts
$config['forgot_password_expiration'] = 0;                   // The number of milliseconds after which a forgot password request will expire. If set to 0, forgot password requests will not expire.

/*
 | -------------------------------------------------------------------------
 | Cookie options.
 | -------------------------------------------------------------------------
 | remember_cookie_name Default: remember_code
 | identity_cookie_name Default: identity
 */
$config['remember_cookie_name'] = 'remember_code';
$config['identity_cookie_name'] = 'identity';

/*
 | -------------------------------------------------------------------------
 | Email options.
 | -------------------------------------------------------------------------
 | email_config:
 | 	  'file' = Use the default CI config or use from a config file
 | 	  array  = Manually set your email config settings
 */
$config['use_ci_email'] = TRUE; // Send Email using the builtin CI email class, if false it will return the code and the identity
$config['email_config'] = array(
	'mailtype' 		=> 'html',
	  'smtp_host' 	=> 'ssl://smtp.googlemail.com',
	  'smtp_user' 	=> '',
	  'smtp_pass' 	=> '',
	  'smtp_port' 	=> '465',
	  'charset' 	=> 'utf-8',
	  'newline' 	=> '\r\n',
	  'mailtype' 	=> 'html',		
	  'wordwrap' => TRUE			
);

/*
 | -------------------------------------------------------------------------
 | Email templates.
 | -------------------------------------------------------------------------
 | Folder where email templates are stored.
 | Default: auth/
 */
$config['email_templates'] = 'auth/email/';

/*
 | -------------------------------------------------------------------------
 | Activate Account Email Template
 | -------------------------------------------------------------------------
 | Default: activate.tpl.php
 */
$config['email_activate'] = 'activate.tpl.php';
/*
 | -------------------------------------------------------------------------
 | Forgot Password Email Template
 | -------------------------------------------------------------------------
 | Default: forgot_password.tpl.php
 */
$config['email_forgot_password'] = 'forgot_password.tpl.php';

/*
 | -------------------------------------------------------------------------
 | Forgot Password Complete Email Template
 | -------------------------------------------------------------------------
 | Default: new_password.tpl.php
 */
$config['email_forgot_password_complete'] = 'new_password.tpl.php';

/*
 | -------------------------------------------------------------------------
 | Salt options
 | -------------------------------------------------------------------------
 | salt_length Default: 22
 |
 | store_salt: Should the salt be stored in the database?
 | This will change your password encryption algorithm,
 | default password, 'password', changes to
 | fbaa5e216d163a02ae630ab1a43372635dd374c0 with default salt.
 */
$config['salt_length'] = 22;
$config['store_salt']  = FALSE;

/*
 | -------------------------------------------------------------------------
 | Message Delimiters.
 | -------------------------------------------------------------------------
 */
$config['delimiters_source']       = 'config'; 	// "config" = use the settings defined here, "form_validation" = use the settings defined in CI's form validation library
$config['message_start_delimiter'] = '<p>'; 	// Message start delimiter
$config['message_end_delimiter']   = '</p>'; 	// Message end delimiter
$config['error_start_delimiter']   = '<p class="error">';		// Error message start delimiter
$config['error_end_delimiter']     = '</p>';	// Error message end delimiter

/* End of file ion_auth.php */
/* Location: ./application/config/ion_auth.php */
