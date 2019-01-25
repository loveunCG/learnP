<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

///User Defined Constants
/* User Defined Constants*/
define('DS', '/');
define('DS_PATH', DIRECTORY_SEPARATOR);
define('FOLERNAME', ltrim(str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']), "/") );
define('RESOURCES', 'assets');
define('TBL_PREFIX', 'pre_');


$base = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base .= '://'.$_SERVER['HTTP_HOST'].DS . FOLERNAME;
//define('SITEURL2',$base . "index.php");
define('SITEURL2',rtrim($base, "/"));
define('SITEURL',$base);

define('URL_RESOURCES', SITEURL . RESOURCES . DS);
define('RESOURCES_ADMIN', SITEURL . RESOURCES . DS . 'admin' . DS);
define('RESOURCES_FRONT', SITEURL . RESOURCES . DS . 'front' . DS);
define('RESOURCES_GROCERY_CRUD', SITEURL . RESOURCES . DS . 'grocery_crud' . DS);
define('URL_PUBLIC_UPLOADS', $_SERVER["DOCUMENT_ROOT"] . DS . FOLERNAME . RESOURCES . DS . 'uploads' . DS);
define('URL_PUBLIC_UPLOADS2', SITEURL . RESOURCES . DS . 'uploads' . DS);

define('PATH_DOCROOT',$_SERVER["DOCUMENT_ROOT"] . DS);
define('PATH_DOCROOT_RESOURCES',$_SERVER["DOCUMENT_ROOT"] . DS . RESOURCES);


/* Common Frontend URLs */
define('URL_FRONT_CSS', RESOURCES_FRONT.'css' . DS);
define('URL_FRONT_FONT', RESOURCES_FRONT.'fonts' . DS);
define('URL_FRONT_IMAGES', RESOURCES_FRONT.'images' . DS);
define('URL_FRONT_JS', RESOURCES_FRONT.'js' . DS);

/*Gorcery Crud URLs */
define('URL_GROCERY_CRUD_IMAGES', RESOURCES_GROCERY_CRUD.'themes' . DS .'flexigrid'. DS . 'css' . DS . 'images' . DS);

/* Common Admin URLs */
define('URL_ADMIN_CSS', RESOURCES_ADMIN.'css' . DS);
define('URL_ADMIN_FONT', RESOURCES_ADMIN.'fonts' . DS);
define('URL_ADMIN_IMAGES', RESOURCES_ADMIN.'images' . DS);
define('URL_ADMIN_JS', RESOURCES_ADMIN.'js' . DS);
define('URL_ADMIN_DATATABLES', RESOURCES_ADMIN.'DataTables' . DS);
define('URL_ADMIN_LANF', RESOURCES_ADMIN.'lang' . DS);
define('URL_ADMIN_LCSS', RESOURCES_ADMIN.'less' . DS);
define('URL_ADMIN_PLUGINS', RESOURCES_ADMIN.'plugins' . DS);
define('URL_ADMIN_SCSS', RESOURCES_ADMIN.'scss' . DS);
define('URL_ADMIN_SKINS', RESOURCES_ADMIN.'skins' . DS);

define('PATH_PUBLIC_UPLOADS', URL_PUBLIC_UPLOADS);
define('URL_PUBLIC_UPLOADS_THUMBS', URL_RESOURCES . 'uploads' . DS . 'thumbs' . DS);
define('URL_PUBLIC_UPLOADS_PROFILES', URL_RESOURCES . 'uploads' . DS . 'profiles' . DS);
define('URL_PUBLIC_UPLOADS_TESTIMONIALS', URL_RESOURCES . 'uploads' . DS . 'testimonials' . DS);
define('URL_PUBLIC_UPLOADS_SETTINGS', URL_RESOURCES . 'uploads' . DS . 'settings' . DS);
define('URL_PUBLIC_UPLOADS_PROFILES_THUMBS', URL_RESOURCES . 'uploads' . DS . 'profiles' . DS . 'thumbs' . DS);
define('URL_PUBLIC_UPLOADS_PACKAGES', URL_RESOURCES . 'uploads' . DS . 'packages' . DS);
define('URL_PUBLIC_UPLOADS_PACKAGES_THUMBS', URL_RESOURCES . 'uploads' . DS . 'packages' . DS . 'thumbs' . DS);
define('URL_PUBLIC_UPLOADS_QUOTES', URL_RESOURCES . 'uploads' . DS . 'quotes' . DS);
define('URL_PUBLIC_UPLOADS_QUOTES_THUMBS', URL_RESOURCES . 'uploads' . DS . 'quotes' . DS . 'thumbs' . DS);
define('URL_PUBLIC_UPLOADS_AUTHOR_CATEGORY', URL_RESOURCES . 'uploads' . DS . 'authors_categories' . DS );
define('URL_PUBLIC_UPLOADS_AUTHOR_CATEGORY_THUMBS', URL_RESOURCES . 'uploads' . DS . 'authors_categories' . DS . 'thumbs' . DS);

define('URL_PUBLIC_UPLOADS_HOMEPAGE', URL_RESOURCES . 'uploads' . DS . 'homepage' . DS);
define('URL_PUBLIC_UPLOADS_HOMEPAGE_THUMBS', URL_RESOURCES . 'uploads' . DS . 'homepage' . DS . 'thumbs' . DS);
define('URL_PUBLIC_UPLOADS_CERTIFICATES', URL_RESOURCES . 'uploads' . DS . 'certificates' . DS );
//Auth Module
define('URL_AUTH_INDEX', SITEURL2. DS . 'auth'. DS .'index');
define('URL_AUTH_LOGIN', SITEURL2. DS .'auth'. DS .'login');
define('URL_AUTH_EDIT_USER', SITEURL2. DS .'auth'. DS .'edit_user');
define('URL_AUTH_CREATE_USER', SITEURL2. DS . 'auth'.DS.'create_user');
define('URL_AUTH_SIGNUP', SITEURL2.DS.'auth'.DS.'signup');
define('URL_AUTH_FACEBOOKLOGIN', SITEURL2.DS.'auth'.DS.'facebooklogin');
define('URL_AUTH_FACEBOOKLOGOUT', SITEURL2.DS.'auth'.DS.'facebooklogout');
define('URL_AUTH_TWITTERLOGIN', SITEURL2.DS.'auth'.DS.'twitterlogin');
define('URL_AUTH_TWITTERCALLBACK', SITEURL2.DS.'auth'.DS.'twittercallback');
define('URL_AUTH_TWITTERLOGOUT', SITEURL2.DS.'auth'.DS.'twitterlogout');
define('URL_AUTH_PROFILE', SITEURL2. DS .'auth'. DS .'profile');
define('URL_AUTH_CHANGE_PASSWORD', SITEURL2. DS .'auth'. DS .'change_password');

define('URL_AUTH_GOOGLE_OPENID', SITEURL2.DS.'auth'.DS.'google_openid');

define('URL_AUTH_LOGOUT', SITEURL2.DS.'auth'.DS.'logout');
define('URL_AUTH_FORGOT_PASSWORD', SITEURL2.DS.'auth'.DS.'forgot_password');
define('URL_AUTH_DELETE', SITEURL2.DS.'auth'.DS.'delete_record');
define('URL_AUTH_STATUSTOGGLE', SITEURL2.DS.'auth'.DS.'statustoggle');
define('URL_FORGOT_PASSWORD', SITEURL2.DS.'auth'.DS.'forgot_password');
define('URL_RESET_PASSWORD', SITEURL2.DS.'auth'.DS.'reset_password');


//User Module


//Admin Module
define('URL_ADMIN_INDEX', SITEURL2.DS.'admin'.DS.'index');
define('URL_ADMIN_CHANGEPASSWORD', SITEURL2.DS.'admin'.DS.'changepassword');
define('URL_ADMIN_PROFILE', SITEURL2.DS.'admin'.DS.'profile');
define('URL_ADMIN_ALL_LEADS', SITEURL2.DS.'admin'.DS.'all_leads');
define('URL_ADMIN_PREMIUM_LEADS', SITEURL2.DS.'admin'.DS.'premium_leads');
define('URL_ADMIN_OPENED_LEADS', SITEURL2.DS.'admin'.DS.'opened_leads');
define('URL_ADMIN_CLOSED_LEADS', SITEURL2.DS.'admin'.DS.'closed_leads');
define('URL_ADMIN_FAQS', SITEURL2.DS.'admin'.DS.'faqs');
define('URL_ADMIN_DYNAMIC_PAGES', SITEURL2.DS.'admin'.DS.'dynamic_pages');
define('URL_ADMIN_STUDENT_BOOKINGS', SITEURL2.DS.'admin'.DS.'student-bookings');
define('URL_ADMIN_INST_BATCHES', SITEURL2.DS.'admin'.DS.'inst-batches');
define('URL_ADMIN_INST_BATCH_ENROLLED_STUDENTS', SITEURL2.DS.'admin'.DS.'inst-batche-enrolled-students');
define('URL_ADMIN_VIEW_CERTIFICATES', SITEURL2.DS.'admin'.DS.'view-certificates');
define('URL_ADMIN_VIEW_INST_TUTORS', SITEURL2.DS.'admin'.DS.'view-inst-tutors');


define('URL_ADMIN_TUTOR_MONEY_CONVERSION_REQUESTS', SITEURL2.DS.'admin'.DS.'tutor-money-conversion-requests');
define('URL_ADMIN_INST_MONEY_CONVERSION_REQUESTS', SITEURL2.DS.'admin'.DS.'inst-money-conversion-requests');

define('URL_ADMIN_SCROLL_NEWS', SITEURL2.DS.'admin'.DS.'scroll-news');
 

// Home constants

define('URL_BLOG_PAGES', SITEURL2.DS.'blog'.DS.'pages');


// Admin Reports

define('URL_ADMIN_USERS_INFO', SITEURL2.DS.'reports'.DS.'users_info');
define('URL_ADMIN_PREMIUM_USERS', SITEURL2.DS.'reports'.DS.'premium_users');
define('URL_ADMIN_PACKAGAES', SITEURL2.DS.'reports'.DS.'packages');
define('URL_ADMIN_PAYMENTS', SITEURL2.DS.'admin'.DS.'payments');
define('URL_ADMIN_PAYMENT_STATUS', SITEURL2.DS.'admin'.DS.'manual_payment_status');

//Language Module
define('URL_LANGUAGE_INDEX', SITEURL2.DS.'language'.DS.'index');
define('URL_LANGUAGE_ADDLANGUEGE', SITEURL2.DS.'language'.DS.'addlanguage');
define('URL_LANGUAGE_DELETE', SITEURL2.DS.'language'.DS.'deletelanguage');
define('URL_LANGUAGE_PHRASES', SITEURL2.DS.'language'.DS.'phrases');
define('URL_LANGUAGE_ADDPHRASE', SITEURL2.DS.'language'.DS.'addphrase');
define('URL_LANGUAGE_DELETEPHRASE', SITEURL2.DS.'language'.DS.'deletephrase');
define('URL_LANGUAGE_AJAX_GET_DATA', SITEURL2.DS.'language'.DS.'ajax_get_data');
define('URL_LANGUAGE_ADDLANGUEGEPHRASES', SITEURL2.DS.'language'.DS.'addlanguagephrases');
define('URL_LANGUAGE_LANGUAGES', SITEURL2.DS.'language'.DS.'languages');

define('TBL_LANGUAGEWORDS', 'languagewords');
define('TBL_LANGUAGES', 'languages');

//Settings Module
define('URL_SETTINGS_INDEX', SITEURL2."/settings/index");
define('URL_SETTINGS_ADDEDIT', SITEURL2."/settings/addedit");
define('URL_SETTINGS_DELETE', SITEURL2."/settings/delete_record");
define('URL_SETTINGS_STATUSTOGGLE', SITEURL2."/settings/statustoggle");
define('URL_SETTINGS_AJAX_GET_DATA', SITEURL2."/settings/ajax_get_data");

define('URL_SETTINGS_TYPES', SITEURL2."/settings/types");
define('URL_SETTINGS_TYPEADDEDIT', SITEURL2."/settings/typeaddedit");
define('URL_SETTINGS_TYPEDELETE', SITEURL2."/settings/typedelete");

define('URL_SETTINGS_FIELDS', SITEURL2."/settings/fields");
define('URL_SETTINGS_FIELDADDEDIT', SITEURL2."/settings/fieldaddedit");
define('URL_SETTINGS_FIELDDELETE', SITEURL2."/settings/fielddelete");
define('URL_SETTINGS_FIELDSVALUES', SITEURL2."/settings/fieldsvalues");
define('URL_SETTINGS_SUBTYPES', SITEURL2."/settings/subtypes");
define('URL_SETTINGS_MAKEDEFAULT', SITEURL2."/settings/makedefault");
define('URL_SETTINGS_MAKEACTIVE', SITEURL2."/settings/makeactive");
define('URL_SETTINGS_MAKEINACTIVE', SITEURL2."/settings/makeinactive");

define('URL_QUOTE_SETTINGS', SITEURL2."/settings/quotesettings");
define('URL_GET_PACKAGES', SITEURL2."/settings/getpackages");
define('URL_GET_PACKAGE_DAYS', SITEURL2."/settings/getpackagedays");
/*
Packages Module
//URLS
*/
define('URL_PACKAGE_INDEX', SITEURL2."/packages/index");
define('URL_PACKAGE_VIEWPACKAGES', SITEURL2."/packages/viewpackages");
define('URL_PACKAGE_ADDEDIT', SITEURL2."/packages/addedit");
define('URL_PACKAGE_DELETE_RECORD', SITEURL2."/packages/delete_record");
define('URL_PACKAGE_STATUSTOGGLE', SITEURL2."/packages/statustoggle");
define('URL_PACKAGE_AJAX_GET_DATA', SITEURL2."/packages/ajax_get_data");
//Messages
define('MSG_PACKAGE_ACTIVATED', 	'Package Activated Successfully.');
define('MSG_PACKAGE_DEACTIVATED', 	'Package De-Activated Successfully.');
define('MSG_PACKAGE_DELETED', 	'Package Deleted Successfully.');

//System Settings Tables
define('TBL_SETTINGS_TYPES', 'system_settings_types');
define('TBL_SETTINGS_FIELDS', 'system_settings_fields');
define('TBL_SETTINGS_VALUES', 'system_settings_values');
define('TBL_PAGES', 'pages');
define('TBL_QUOTE_SETTINGS', 'quote_settings');
define('TBL_PACKAGES', 'packages');
define('TBL_SUBSCRIPTIONS', 'subscriptions');
define('TBL_PAYMENTS', 'payments');
define('TBL_SUBMIT_QUOTES', 'submit_quotes');
define('TBL_SAVED_QUOTES', 'saved_quotes');

//Messages
define('MSG_MEMBERSHIP_ACTIVATED', 	'Membership Activated Successfully.');
define('MSG_MEMBERSHIP_DEACTIVATED', 	'Membership De-Activated Successfully.');
define('MSG_MEMBERSHIP_DELETED', 	'Membership Deleted Successfully.');

define('GRP_USER', 2);
define('GRP_USER_NAME', 'user');
define('TBL_EMAIL_TEMPLATES', 'email_templates');
define('TBL_TERMS_DATA', 'terms_data');
define('TBL_USERS', TBL_PREFIX.'users');
define('TBL_GROUPS', TBL_PREFIX.'groups');
define('TBL_USERS_GROUPS', TBL_PREFIX.'users_groups');

define('URL_USER', SITEURL2. DS . 'user'. DS);
define('URL_USER_CHANGEPASSWORD', SITEURL2. DS . 'user'. DS . 'changepassword');
define('URL_USER_INDEX', URL_USER . 'index');
define('URL_USER_DASHBOARD', URL_USER . 'index');
define('URL_USER_PROFILE', URL_USER . 'profile');
define('URL_SUBSCRIPTIONS', URL_USER . 'subscription_reports');
define('URL_SUBSCRIPTION_DETAILS', URL_USER . 'subscription_details');
define('URL_GET_CATEGORIES', URL_USER . 'getcategories');

/*############PAYMENT#############*/
define('URL_PAYMENT', SITEURL2. DS . 'payment' . DS);
define('URL_PAYMENT_INDEX', URL_PAYMENT.'index');
define('URL_PAYMENT_PAYNOW', URL_PAYMENT.'dopay');
define('URL_PAYMENT_PAYU_SUCCESS', URL_PAYMENT.'success');
define('URL_PAYMENT_PAYU_FAILURE', URL_PAYMENT.'failure');
define('URL_PAYU_SANDBOX', 'https://test.payu.in');
define('URL_PAYU_PRODUCTION', 'https://secure.payu.in');


define('URL_PAYMENT_PAYPALPAYNOW', URL_PAYMENT.'paypaldopay');
define('URL_PAYMENT_PAYPAL_SUCCESS', URL_PAYMENT.'paypal_success');
define('URL_PAYMENT_PAYPAL_CANCEL', URL_PAYMENT.'payment_cancel');

/*
PAGES Module
*/
define('URL_PAGES', SITEURL2."/pages/viewpages");
define('URL_PAGE_AJAX_GET_DATA',SITEURL2."/pages/ajax_get_data");
define('URL_PAGE_EDIT', SITEURL2."/pages/edit");


define('MSG_KEY_ALREADY_EXISTS', 'Key already exists');


//////Page Limits//////////////
define('LIMIT', '2');
define('PER_PAGE', 4);


/*
Testimonials Module
//URLS
*/
define('URL_TESTIMONIALS_INDEX', SITEURL2."/testimonials/index");
define('URL_TESTIMONIALS_ADDEDIT', SITEURL2."/testimonials/addedit");
define('URL_TESTIMONIALS_DELETE_RECORD', SITEURL2."/testimonials/delete_record");
define('URL_TESTIMONIALS_STATUSTOGGLE', SITEURL2."/testimonials/statustoggle");
define('URL_TESTIMONIALS_AJAX_GET_DATA', SITEURL2."/testimonials/ajax_get_data");

//Constants
define('TESTIMONIALS', 'testimonial');

//Tables
define('TBL_TESTIMONIALS', 'testimonials');



define('URL_SUBJECTS_INDEX', SITEURL2."/subjects/index");
define('URL_LOCATIONS_INDEX', SITEURL2."/locations/index");
define('URL_CATEGORIES_INDEX', SITEURL2."/categories/index");
define('URL_CATEGORIES_COURSES', SITEURL2."/categories/courses");
define('URL_CERTIFICATES_INDEX', SITEURL2."/certificates/index");


//INSTITUTE MODULE CONSTANTS
define('URL_INSTITUTE_INDEX', SITEURL2."/institute/index");
define('URL_INSTITUTE_LIST_PACKAGES', SITEURL2."/institute/list-packages");
define('URL_INSTITUTE_SUBSCRIPTIONS', SITEURL2."/institute/mysubscriptions");
define('URL_INSTITUTE_PERSONAL_INFO', SITEURL2."/institute/personal-info");
define('URL_INSTITUTE_PROFILE_INFO', SITEURL2."/institute/profile-information");
define('URL_INSTITUTE_CONTACT_INFO', SITEURL2."/institute/contact-information");
define('URL_INSTITUTE_MY_GALLARY', SITEURL2."/institute/my-gallery");
define('URL_INSTITUTE_OFFERED_COURSES', SITEURL2."/institute/courses");
define('URL_INSTITUTE_LOCATIONS', SITEURL2."/institute/locations");
define('URL_INSTITUTE_TEACHING_TYPES', SITEURL2."/institute/teaching-types");
define('URL_INSTITUTE_BATCHES', SITEURL2."/institute/batches");
define('URL_INSTITUTE_CERTIFICATES', SITEURL2."/institute/certificates");
define('URL_INSTITUTE_MANAGE_PRIVACY', SITEURL2."/institute/manage-privacy");
define('URL_INSTITUTE_ENROLLED_STUDENTS', SITEURL2."/institute/enrolled_students");
define('URL_INSTITUTE_GET_BATCHES', SITEURL2."/institute/get_batches");
define('URL_INSTITUTE_CREDITS_TRANSACTION_HISTORY', SITEURL2."/institute/credits-transactions-history");
define('URL_INSTITUTE_MONEY_CONVERSION_REQUEST', SITEURL2."/institute/money-conversion-request");
define('URL_INSTITUTE_GET_BATCH_LIST', SITEURL2."/institute/get-batch-list");


//TUTOR MODULE CONSTANTS
define('URL_TUTOR_INDEX', SITEURL2."/tutor/index");
define('URL_TUTOR_MANAGE_SUBJECTS', SITEURL2."/tutor/manage-subjects");
define('URL_TUTOR_MANAGE_LOCATIONS', SITEURL2."/tutor/manage-locations");
define('URL_TUTOR_LIST_PACKAGES', SITEURL2."/tutor/list-packages");
define('URL_TUTOR_VIEW_STUDETNS', SITEURL2."/tutor/view_students");
define('URL_TUTOR_PROFILE_INFO', SITEURL2."/tutor/profile-information");
define('URL_TUTOR_CREDITS_TRANSACTION_HISTORY', SITEURL2."/tutor/credits-transactions-history");
define('URL_TUTOR_SELL_COURSES_ONLINE', SITEURL2."/tutor/sell-courses-online");
define('URL_TUTOR_LIST_SELLING_COURSES', SITEURL2."/tutor/list-selling-courses");
define('URL_TUTOR_VIEW_SELLING_COURSE_CURRICULUM', SITEURL2."/tutor/view-selling-course-curriculum");
define('URL_TUTOR_DELETE_COURSE_CURRICULUM_RECORD', SITEURL2."/tutor/delete-course-curriculum-record");


//STUDEN MODULE CONSTANTS

define('URL_STUDENT_INDEX', SITEURL2."/student/index");
define('URL_OPTIONS_INDEX', SITEURL2."/options/index");
define('URL_STUDENT_LIST_PACKAGES', SITEURL2."/student/list-packages");
define('URL_STUDENT_SUBSCRIPTIONS', SITEURL2."/student/mysubscriptions");
define('URL_STUDENT_MANAGE_LOCATIONS', SITEURL2."/student/manage-locations");
define('URL_STUDENT_PREFFERED_TEACHING_TYPES', SITEURL2."/student/manage-teaching-types");
define('URL_STUDENT_CERTIFICATES', SITEURL2."/student/certificates");
define('URL_STUDENT_MANAGE_PRIVACY', SITEURL2."/student/manage-privacy");
define('URL_STUDENT_PERSONAL_INFO', SITEURL2."/student/personal-info");
define('URL_STUDENT_PROFILE_INFO', SITEURL2."/student/profile-information");
define('URL_STUDENT_EDUCATION', SITEURL2."/student/education");
define('URL_STUDENT_CONTACT_INFO', SITEURL2."/student/contact-information");
define('URL_STUDENT_MY_GALLARY', SITEURL2."/student/my-gallery");
define('URL_STUDENT_LEADS', SITEURL2."/student/student_leads");
define('URL_STUDENT_REQUIREMENTS', SITEURL2."/student/post-requirement");
define('URL_STUDENT_MANAGE_COURSES', SITEURL2."/student/manage-courses");
define('URL_STUDENT_ENROLLED_COURSES', SITEURL2."/student/enrolled-courses");
define('URL_STUDENT_CREDITS_TRANSACTION_HISTORY', SITEURL2."/student/credits-transactions-history");
define('URL_STUDENT_MANUAL', SITEURL2.'/student/manual_payment_status');

// Home Module
define('URL_VIEW_STUDENT_PROFILE', SITEURL2."/home/student-profile");
define('URL_VIEW_TERMS_AND_CONDITIONS', SITEURL2."/home/terms-and-conditions");
define('URL_HOME_AJAX_GET_INSTITUTE_BATCHES', SITEURL2."/home/ajax_get_institute_batches");
define('URL_HOME_AJAX_GET_INSTITUTE_BATCHES_INFO', SITEURL2."/home/ajax_get_institute_batches_info");

// Payment Gateway Constants
define('PAYU_PAYMENT_GATEWAY', 27);
define('PAYPAL_PAYMENT_GATEWAY', 28);
define('STRIPE_PAYMENT_GATEWAY', 41);
define('TPAY_PAYMENT_GATEWAY', 42);
define('PAGSEGURO_PAYMENT_GATEWAY', 43);
define('WEBMONEY_PAYMENT_GATEWAY', 44);
define('YANDEX_PAYMENT_GATEWAY', 45);
define('PAYZA_PAYMENT_GATEWAY', 46);
define('MANUAL_TRANSFER', 47);
define('TWOCHECKOUT_PAYMENT_GATEWAY', 48);
define('RAZORPAY_PAYMENT_GATEWAY', 49);

include_once('constants-tutors.php');