<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

/**custom URLS**/
$route['pages/(:num)-(:any)'] = 'page/pages/$1/$2';
$route['aboutus'] 	 		= 'home/page/1';
$route['howitworks'] 	 	= 'home/page/2';
$route['termsconditions']   = 'home/page/3';
$route['privacypolicy'] 	= 'home/page/4';



$route['about-us']			= 'home/about_us';
$route['faqs']				= 'home/faqs';
$route['contact-us']		= 'home/contact_us';

$route['courses']			 		= 'home/all_courses';
$route['courses/(:any)']	 		= 'home/all_courses/$1';

$route['buy-courses']			 	= 'home/buy_courses';
$route['buy-courses/(:any)']	 	= 'home/buy_courses/$1';

$route['buy-course']			 	= 'home/buy_course';
$route['buy-course/(:any)']	 		= 'home/buy_course/$1';

$route['checkout']			 		= 'home/checkout';
$route['checkout/(:any)']	 		= 'home/checkout/$1';
$route['checkout/(:any)/(:any)']	= 'home/checkout/$1/$2';




$route['search-tutor']		 			= 'home/search_tutor';
$route['search-tutor/(:any)'] 			= 'home/search_tutor/$1';
$route['search-tutor/(:any)/(:any)']	= 'home/search_tutor/$1/$2';
$route['search-tutor/(:any)/(:any)/(:any)']	= 'home/search_tutor/$1/$2/$3';

$route['search-institute']		 			= 'home/search_institute';
$route['search-institute/(:any)'] 			= 'home/search_institute/$1';
$route['search-institute/(:any)/(:any)']	= 'home/search_institute/$1/$2';
$route['search-institute/(:any)/(:any)/(:any)']	= 'home/search_institute/$1/$2/$3';

$route['search-student-leads']				 = 'home/search_student_leads';
$route['search-student-leads/(:any)']		 = 'home/search_student_leads/$1';
$route['search-student-leads/(:any)/(:any)'] = 'home/search_student_leads/$1/$2';
$route['search-student-leads/(:any)/(:any)/(:any)']	= 'home/search_student_leads/$1/$2/$3';


$route['tutor-profile']				= 'home/tutor_profile';
$route['tutor-profile/(:any)']		= 'home/tutor_profile/$1';

$route['institute-profile']				= 'home/institute_profile';
$route['institute-profile/(:any)']		= 'home/institute_profile/$1';


$route['book-tutor']				= 'student/book_tutor';
$route['book-tutor/(:any)']			= 'student/book_tutor/$1';

$route['enroll-in-institute']				= 'student/enroll_in_institute';
$route['enroll-in-institute/(:any)']		= 'student/enroll_in_institute/$1';


//Student - Enquiries made over Tutors
$route['enquiries']					= 'student/enquiries';
$route['enquiries/(:any)']			= 'student/enquiries/$1';
$route['enquiries/(:any)/(:any)']	= 'student/enquiries/$1/$2';
$route['enquiries/(:any)/(:any)/(:any)']	= 'student/enquiries/$1/$2/$3';

$route['rate-tutor']				= 'student/rate_tutor';
$route['rate-tutor/(:any)']			= 'student/rate_tutor/$1';

$route['user-reviews']				= 'tutor/user_reviews';
$route['user-reviews/(:any)/(:any)']= 'tutor/user_reviews/$1/$2';

$route['send-credits-conversion-request']	= 'tutor/send_credits_conversion_request';
$route['send-credits-conversion-request/(:any)']= 'tutor/send_credits_conversion_request/$1';

$route['credit-conversion-requests']= 'tutor/credit_conversion_requests';
$route['credit-conversion-requests/(:any)']= 'tutor/credit_conversion_requests/$1';
$route['credit-conversion-requests/(:any)/(:any)']= 'tutor/credit_conversion_requests/$1/$2';
$route['credit-conversion-requests/(:any)/(:any)/(:any)']= 'tutor/credit_conversion_requests/$1/$2/$3';

//Tutor - View Student's Enquiries
$route['student-enquiries']			= 'tutor/student_enquiries';
$route['student-enquiries/(:any)']	= 'tutor/student_enquiries/$1';
$route['student-enquiries/(:any)/(:any)']	= 'tutor/student_enquiries/$1/$2';
$route['student-enquiries/(:any)/(:any)/(:any)']	= 'tutor/student_enquiries/$1/$2/$3';

$route['my-batches']				= 'tutor/my_batches';
$route['my-batches/(:any)']			= 'tutor/my_batches/$1';
$route['my-batches/(:any)/(:any)']	= 'tutor/my_batches/$1/$2';
$route['my-batches/(:any)/(:any)/(:any)']	= 'tutor/my_batches/$1/$2/$3';

$route['approve-batch-students']		= 'institute/approve_batch_students';
$route['approve-batch-students/(:any)']	= 'institute/approve_batch_students/$1';
$route['approve-batch-students/(:any)/(:any)']	= 'institute/approve_batch_students/$1/$2';

$route['initiate-batch-session']		= 'tutor/initiate_batch_session';
$route['initiate-batch-session/(:any)']	= 'tutor/initiate_batch_session/$1';
$route['initiate-batch-session/(:any)/(:any)']	= 'tutor/initiate_batch_session/$1/$2';

$route['complete-batch-session']		= 'tutor/complete_batch_session';
$route['complete-batch-session/(:any)']	= 'tutor/complete_batch_session/$1';
$route['complete-batch-session/(:any)/(:any)']	= 'tutor/complete_batch_session/$1/$2';


$route['send-message']				= 'home/send_message';




/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
//$route['example/users/(:num)'] = 'example/users/id/$1'; // Example 4
//$route['example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'example/users/id/$1/format/$3$4'; // Example 8
