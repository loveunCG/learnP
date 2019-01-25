<?php
defined('BASEPATH') OR exit('No direct script access allowed');


define('URL_AUTH_CHANGE_APPROVEMENT_STATUS', SITEURL2."/auth/change_approvement_status");

/*
Home Module
//URLS
*/

define('URL_HOME_ABOUT_US', SITEURL2."/about-us");
define('URL_HOME_FAQS', SITEURL2."/faqs");
define('URL_HOME_CONTACT_US', SITEURL2."/contact-us");
define('URL_HOME_ALL_COURSES', SITEURL2."/courses");
define('URL_HOME_LOAD_MORE_COURSES', SITEURL2."/home/load_more_courses");
define('URL_HOME_SEARCH_TUTOR', SITEURL2."/search-tutor");
define('URL_HOME_LOAD_MORE_TUTORS', SITEURL2."/home/load_more_tutors");
define('URL_HOME_SEARCH_INSTITUTE', SITEURL2."/search-institute");
define('URL_HOME_LOAD_MORE_INSTITUTES', SITEURL2."/home/load_more_institutes");
define('URL_HOME_SEARCH_STUDENT_LEADS', SITEURL2."/search-student-leads");
define('URL_HOME_LOAD_MORE_STUDENT_LEADS', SITEURL2."/home/load_more_student_leads");
define('URL_HOME_TUTOR_PROFILE', SITEURL2."/tutor-profile");
define('URL_HOME_AJAX_GET_TUTOR_COURSE_DETAILS', SITEURL2."/home/ajax_get_tutor_course_details");
define('URL_HOME_INSTITUTE_PROFILE', SITEURL2."/institute-profile");
define('URL_HOME_SEND_MESSAGE', SITEURL2."/send-message");

define('URL_HOME_BUY_COURSES', SITEURL2."/buy-courses");
define('URL_HOME_BUY_COURSE', SITEURL2."/buy-course");
define('URL_HOME_LOAD_MORE_SELLING_COURSES', SITEURL2."/home/load_more_selling_courses");
define('URL_HOME_CHECKOUT', SITEURL2."/checkout");


define('URL_PAY', SITEURL2."/pay");



define('URL_BLOG_INDEX', SITEURL2."/blog/index");
define('URL_BLOG_SINGLE', SITEURL2."/blog/single");


//Constants
define('OUR_POPULAR_COURSES', 'Our Popular <span>Courses</span>');
define('CHECK_ALL_COURSES', 'Check <strong>All Courses</strong>');
define('LIMIT_COURSE_LIST', 16);
define('LIMIT_PROFILES_LIST', 4);
define('MAX_DISPLAY_CATS_MENU', 6);


//Tables
define('TBL_CATEGORIES', TBL_PREFIX.'categories');
define('TBL_COURSE_CATEGORIES', TBL_PREFIX.'course_categories');
define('TBL_TUTOR_COURSES', TBL_PREFIX.'tutor_courses');
define('TBL_LOCATIONS', TBL_PREFIX.'locations');
define('TBL_TUTOR_LOCATIONS', TBL_PREFIX.'tutor_locations');
define('TBL_TEACHING_TYPES', TBL_PREFIX.'teaching_types');
define('TBL_TUTOR_TEACHING_TYPES', TBL_PREFIX.'tutor_teaching_types');
define('TBL_STUDENT_LEADS', TBL_PREFIX.'student_leads');
define('TBL_USER_CREDIT_TRANSACTIONS', TBL_PREFIX.'user_credit_transactions');
define('TBL_BOOKINGS', TBL_PREFIX.'bookings');
define('TBL_SUBSCRIPTIONS1', TBL_PREFIX.'subscriptions');
define('TBL_INST_OFFERED_COURSES', TBL_PREFIX.'inst_offered_courses');
define('TBL_INST_LOCATIONS', TBL_PREFIX.'inst_locations');
define('TBL_INST_TEACHING_TYPES', TBL_PREFIX.'inst_teaching_types');
define('TBL_INST_ENROLLED_STUDENTS', TBL_PREFIX.'inst_enrolled_students');



//Paths
define('URL_UPLOADS_CATEGORIES_PHYSICAL', RESOURCES . DS . 'uploads' . DS .'categories' . DS);
define('URL_UPLOADS_COURSES_PHYSICAL', RESOURCES . DS . 'uploads' . DS .'courses' . DS);

define('URL_UPLOADS_CATEGORIES', URL_PUBLIC_UPLOADS2.'categories' . DS);
define('URL_UPLOADS_COURSES', URL_PUBLIC_UPLOADS2.'courses' . DS);


define('URL_UPLOADS_COURSES_DEFAULT_IMAGE', URL_UPLOADS_COURSES.'default-course-img.jpg');


define('URL_UPLOADS_PROFILES_PHYSICAL', RESOURCES . DS . 'uploads' . DS .'profiles' . DS);
define('URL_UPLOADS_PROFILES', URL_PUBLIC_UPLOADS2.'profiles' . DS);

define('URL_UPLOADS_GALLERY_PHYSICAL', RESOURCES . DS . 'uploads' . DS .'gallery' . DS);
define('URL_UPLOADS_GALLERY', URL_PUBLIC_UPLOADS2.'gallery' . DS);

define('URL_UPLOADS_PROFILES_TUTOR_MALE_DEFAULT_IMAGE', URL_UPLOADS_PROFILES.'default-tutor-male.jpg');
define('URL_UPLOADS_PROFILES_TUTOR_FEMALE_DEFAULT_IMAGE', URL_UPLOADS_PROFILES.'default-tutor-female.jpg');
define('URL_UPLOADS_PROFILES_INSTITUTE_DEFAULT_IMAGE', URL_UPLOADS_PROFILES.'default-institute.jpg');

define('URL_UPLOADS_PROFILES_STUDENT_MALE_DEFAULT_IMAGE', URL_UPLOADS_PROFILES.'default-student-male.png');
define('URL_UPLOADS_PROFILES_STUDENT_FEMALE_DEFAULT_IMAGE', URL_UPLOADS_PROFILES.'default-student-female.png');





/*
Student Module
//URLS
*/

define('URL_STUDENT_BOOK_TUTOR', SITEURL2."/book-tutor");
define('URL_STUDENT_POST_REQUIREMENT', SITEURL2."/student/post-requirement");
define('URL_STUDENT_ENQUIRIES', SITEURL2."/enquiries");
define('URL_STUDENT_ENROLL_IN_INSTITUTE', SITEURL2."/enroll-in-institute");
define('URL_STUDENT_RATE_TUTOR', SITEURL2."/rate-tutor");
define('URL_STUDENT_COURSE_PURCHASES', SITEURL2."/student/course-purchases");
define('URL_STUDENT_DOWNLOAD_COURSE', SITEURL2."/student/download-course");
define('URL_STUDENT_COURSE_DOWNLOAD_HISTORY', SITEURL2."/student/course-download-history");






/*
Tutor Module
//URLS
*/

define('URL_TUTOR_MANAGE_COURSES', SITEURL2."/tutor/manage-courses");
define('URL_TUTOR_USER_REVIEWS', SITEURL2."/user-reviews");
define('URL_TUTOR_STUDENT_ENQUIRIES', SITEURL2."/student-enquiries");
define('URL_TUTOR_MY_BATCHES', SITEURL2."/my-batches");
define('URL_TUTOR_SEND_CREDITS_CONVERSION_REQUEST', SITEURL2."/send-credits-conversion-request");
define('URL_TUTOR_CREDIT_CONVERSION_REQUESTS', SITEURL2."/credit-conversion-requests");
define('URL_TUTOR_INITIATE_BATCH_SESSION', SITEURL2."/initiate-batch-session");
define('URL_TUTOR_COMPLETE_BATCH_SESSION', SITEURL2."/complete-batch-session");
define('URL_TUTOR_PURCHASED_COURSES', SITEURL2."/tutor/purchased-courses");





/*
Institute Module
//URLS
*/

define('URL_INSTITUTE_APPROVE_BATCH_STUDENTS', SITEURL2."/approve-batch-students");
define('URL_INSTITUTE_SEND_CREDITS_CONVERSION_REQUEST', SITEURL2."/institute/send-credits-conversion-request");




define('URL_ADMIN_VIEW_PURCHASED_COURSES', SITEURL2."/admin/view-purchased-courses");
define('URL_ADMIN_VIEW_COURSE_DOWNLOAD_HISTORY', SITEURL2."/admin/view-course-download-history");
define('URL_SECTIONS_INDEX', SITEURL2."/sections/index");




define("VIDEO_FORMATS", serialize (array ("mp4", "3gp", "webm", "wmv", "flv", "avi", "ogg")));
define("AUDIO_FORMATS", serialize (array ("mp2", "mp3", "aac", "wav")));
define("IMAGE_FORMATS", serialize (array ("jpg", "jpeg", "png", "gif", "svg", "bmp")));
define("OTHER_FILE_FORMATS", serialize (array ("pdf", "ppt", "pptx", "doc", "docx", "rtf", "rtx", "txt", "text")));











