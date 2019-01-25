<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update_db extends MY_Controller {

	function __construct()
	{
		parent::__construct();		
		$this->load->library(array('session'));
	}

	public function index()
	{
		//$this->load->dbforge();

		$this->load->database();

		$table1_query = "CREATE TABLE `pre_course_downloads` (
						  `cd_id` bigint(20) NOT NULL,
						  `purchase_id` bigint(20) NOT NULL,
						  `sc_id` int(11) NOT NULL,
						  `tutor_id` int(11) NOT NULL,
						  `user_id` int(11) NOT NULL,
						  `ip_address` varchar(25) NOT NULL,
						  `browser` varchar(50) NOT NULL,
						  `browser_version` varchar(25) NOT NULL,
						  `platform` varchar(50) NOT NULL,
						  `mobile_device` varchar(50) NOT NULL,
						  `robot` varchar(512) NOT NULL,
						  `is_download_success` enum('Yes','No') NOT NULL DEFAULT 'Yes',
						  `downloaded_date` datetime NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1";


		$table2_query = "CREATE TABLE `pre_course_purchases` (
						  `purchase_id` bigint(20) NOT NULL,
						  `sc_id` int(11) NOT NULL,
						  `tutor_id` int(11) NOT NULL,
						  `user_id` int(11) NOT NULL,
						  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
						  `admin_commission_percentage` decimal(10,2) NOT NULL DEFAULT '0.00',
						  `admin_commission_val` decimal(10,2) NOT NULL DEFAULT '0.00',
						  `max_downloads` int(11) NOT NULL,
						  `total_downloads` int(11) NOT NULL DEFAULT '0',
						  `payment_gateway_id` int(11) NOT NULL,
						  `paid_date` varchar(50) DEFAULT NULL,
						  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
						  `transaction_id` varchar(100) DEFAULT NULL,
						  `payer_id` varchar(50) DEFAULT NULL,
						  `payer_email` varchar(50) DEFAULT NULL,
						  `payer_name` varchar(50) DEFAULT NULL,
						  `payment_status` varchar(50) DEFAULT NULL,
						  `status_of_payment_to_tutor` enum('Pending','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
						  `tutor_payment_details` varchar(1000) DEFAULT NULL,
						  `last_modified` datetime DEFAULT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1";


		$table3_query = "CREATE TABLE `pre_payments_data` (
						  `id` bigint(20) NOT NULL,
						  `gateway` varchar(50) DEFAULT NULL,
						  `data` text,
						  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						  `ip_address` varchar(50) DEFAULT NULL,
						  `browser` varchar(50) DEFAULT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1";


		$table4_query = "CREATE TABLE `pre_sections` (
						  `section_id` int(11) NOT NULL,
						  `name` varchar(512) NOT NULL,
						  `description` text NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1";


		$table5_query = "CREATE TABLE `pre_seosettings` (
						  `id` int(11) NOT NULL,
						  `type` varchar(50) DEFAULT NULL,
						  `allowed_variables` text,
						  `seo_title` text,
						  `seo_description` text,
						  `seo_keywords` text,
						  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
						  `updated` datetime DEFAULT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1";


		$table6_query = "CREATE TABLE `pre_tpay_data` (
						  `id` int(11) NOT NULL,
						  `data` text,
						  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
						) ENGINE=InnoDB DEFAULT CHARSET=latin1";


		$table7_query = "CREATE TABLE `pre_tutor_selling_courses` (
						  `sc_id` int(11) NOT NULL,
						  `tutor_id` int(11) NOT NULL,
						  `course_name` varchar(512) NOT NULL,
						  `slug` varchar(512) NOT NULL,
						  `course_title` varchar(512) NOT NULL,
						  `description` text NOT NULL,
						  `image` varchar(512) DEFAULT NULL,
						  `skill_level` varchar(256) DEFAULT NULL,
						  `languages` varchar(1000) DEFAULT NULL,
						  `preview_image` varchar(512) DEFAULT NULL,
						  `preview_file` varchar(512) DEFAULT NULL,
						  `course_price` decimal(10,2) NOT NULL DEFAULT '0.00',
						  `admin_commission_percentage` decimal(10,2) NOT NULL DEFAULT '0.00',
						  `max_downloads` tinyint(5) NOT NULL,
						  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
						  `admin_approved` enum('Yes','No') NOT NULL DEFAULT 'No',
						  `total_purchases` int(11) DEFAULT '0' COMMENT 'overall purchases',
						  `total_downloads` int(11) DEFAULT '0' COMMENT 'overall downloads',
						  `created_at` datetime DEFAULT NULL,
						  `updated_at` datetime DEFAULT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=latin1";


		$table8_query = "CREATE TABLE `pre_tutor_selling_courses_curriculum` (
						  `file_id` bigint(20) NOT NULL,
						  `sc_id` int(11) NOT NULL,
						  `title` varchar(512) NOT NULL,
						  `source_type` enum('url','file') NOT NULL,
						  `file_name` varchar(512) NOT NULL,
						  `file_ext` varchar(10) DEFAULT NULL,
						  `file_size` varchar(128) DEFAULT NULL COMMENT 'In Bytes. When displaying convert as needed'
						) ENGINE=InnoDB DEFAULT CHARSET=latin1";


		$table9_query = "CREATE TABLE `pre_webmoney_data` (
						  `id` int(11) NOT NULL,
						  `data` text,
						  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
						) ENGINE=InnoDB DEFAULT CHARSET=latin1";



		//Insert Operations
		$table1_insert_query = "INSERT INTO `pre_course_downloads` (`cd_id`, `purchase_id`, `sc_id`, `tutor_id`, `user_id`, `ip_address`, `browser`, `browser_version`, `platform`, `mobile_device`, `robot`, `is_download_success`, `downloaded_date`) VALUES
			(1, 1, 17, 7, 3, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:39:08'),
			(2, 1, 17, 7, 3, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:39:29'),
			(3, 1, 17, 7, 3, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:39:49'),
			(4, 1, 17, 7, 3, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:40:00'),
			(5, 2, 2, 7, 3, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:42:53'),
			(6, 2, 2, 7, 3, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:44:47'),
			(7, 4, 15, 7, 3, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:47:49'),
			(8, 3, 8, 7, 3, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:47:57'),
			(9, 3, 8, 7, 3, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:48:01'),
			(10, 6, 13, 7, 2, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:54:30'),
			(11, 5, 9, 7, 2, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:54:33'),
			(12, 7, 20, 7, 2, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:54:36'),
			(13, 8, 19, 7, 2, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:54:38'),
			(14, 5, 9, 7, 2, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:54:42'),
			(15, 8, 19, 7, 2, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:54:46'),
			(16, 6, 13, 7, 2, '10.0.0.16', 'Firefox', '51.0', 'Linux', '', '', 'Yes', '2017-02-24 17:54:48')";



		$table2_insert_query = "INSERT INTO `pre_course_purchases` (`purchase_id`, `sc_id`, `tutor_id`, `user_id`, `total_amount`, `admin_commission_percentage`, `admin_commission_val`, `max_downloads`, `total_downloads`, `payment_gateway_id`, `paid_date`, `paid_amount`, `transaction_id`, `payer_id`, `payer_email`, `payer_name`, `payment_status`, `status_of_payment_to_tutor`, `tutor_payment_details`, `last_modified`) VALUES
			(1, 17, 7, 3, '25.00', '10.00', '2.50', 4, 4, 28, '04:08:39 Feb 24, 2017 PST', '25.00', '0KX89230LA045973G', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 17:38:01'),
			(2, 2, 7, 3, '15.00', '10.00', '1.50', 10, 2, 28, '04:12:31 Feb 24, 2017 PST', '15.00', '6E12148838069451H', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 17:42:05'),
			(3, 8, 7, 3, '15.00', '10.00', '1.50', 6, 2, 28, '2017-02-24T12:15:56Z', '15.00', '6P911968PM9146541', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 17:45:19'),
			(4, 15, 7, 3, '20.00', '10.00', '2.00', 6, 1, 28, '2017-02-24T12:17:25Z', '20.00', '2NS35169P7055024G', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 17:47:10'),
			(5, 9, 7, 2, '15.00', '10.00', '1.50', 4, 2, 28, '2017-02-24T12:21:04Z', '15.00', '9FM24408HC572594Y', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 17:50:49'),
			(6, 13, 7, 2, '25.00', '10.00', '2.50', 4, 2, 28, '04:22:01 Feb 24, 2017 PST', '25.00', '1LX86264MN739704V', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 17:51:29'),
			(7, 20, 7, 2, '20.00', '10.00', '2.00', 5, 1, 28, '2017-02-24T12:22:59Z', '20.00', '7WK575170M917494L', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 17:52:40'),
			(8, 19, 7, 2, '30.00', '10.00', '3.00', 6, 2, 28, '04:23:55 Feb 24, 2017 PST', '30.00', '4UH765620V595141Y', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 17:53:34'),
			(9, 9, 7, 2, '15.00', '10.00', '1.50', 4, 0, 28, '05:01:19 Feb 24, 2017 PST', '15.00', '35V21737VY228311L', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 18:30:59'),
			(10, 28, 7, 2, '20.00', '10.00', '2.00', 5, 0, 28, '2017-02-24T13:02:35Z', '20.00', '7ED04426J9469825V', 'F2TMN4DXTNFZS', 'conqtech7-buyer@gmail.com', 'test buyer', 'Completed', 'Pending', NULL, '2017-02-24 18:32:05')";



		$table4_insert_query = "INSERT INTO `pre_sections` (`section_id`, `name`, `description`) VALUES
		(1, 'Advantages_Section', '<section>\r\n        <div class=\"container\">\r\n            <div class=\"row row-margin\">\r\n                <div class=\"col-md-4 col-sm-4 col-xs-12\">\r\n                    <div class=\"advantage\">\r\n                        <div class=\"media-left\">\r\n                            <img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-video.png\" alt=\"..\">\r\n                        </div>\r\n                        <div class=\"media-body\">\r\n                            <h4><a href=\"\">Videos &amp; Images</a></h4>\r\n                            <p>Listen to our teachers speeches and see our video testimonials before you take any decisions</p>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n                <div class=\"col-md-4 col-sm-4 col-xs-12\">\r\n                    <div class=\"advantage\">\r\n                        <div class=\"media-left\">\r\n                            <img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-score.png\" alt=\"..\">\r\n                        </div>\r\n                        <div class=\"media-body\">\r\n                            <h4><a href=\"\">Quality Scores</a></h4>\r\n                            <p>We have rated teachers for safety and convenience as we know how important this is for you</p>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n                <div class=\"col-md-4 col-sm-4 col-xs-12\">\r\n                    <div class=\"advantage\">\r\n                        <div class=\"media-left\">\r\n                            <img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-calendar.png\" alt=\"..\">\r\n                        </div>\r\n                        <div class=\"media-body\">\r\n                            <h4><a href=\"\">Reviews &amp; Ratings</a></h4>\r\n                            <p>No more emails, Calls or messaging friends for recommendations - Get acces to real reviews in seconds</p>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </section>'),
		(2, 'Are_You_A_Teacher_Section', '<section>\r\n<div class=\"container\">\r\n        <div class=\"row row-margin\">\r\n            <div class=\"col-md-12\">\r\n                <h2 class=\"heading\">Are you a <span>Teacher</span></h2>\r\n                <p class=\"heading-tag\">Looking for a better way to reach your target audience? We can help - Just list with us <strong>For free</strong>.</p>\r\n            </div>\r\n            <div class=\"col-md-4 col-sm-4 col-sm-12\">\r\n                <div class=\"choose-block center-block\">\r\n                    <div class=\"icon\">\r\n                        <img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-cal.png\" alt=\"\">\r\n                        <i class=\"sub-icon fa fa-check\"></i>\r\n                    </div>\r\n                    <p>Boost your sales and scale up all of your classes.</p>\r\n                </div>\r\n            </div>\r\n            <div class=\"col-md-4 col-sm-4 col-sm-12\">\r\n                <div class=\"choose-block center-block\">\r\n                    <div class=\"icon\">\r\n                        <img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-group.png\" alt=\"\">\r\n                        <i class=\"sub-icon fa fa-check\"></i>\r\n                    </div>\r\n                    <p>Get a lot of exposure &amp; brand recognition from everyone.</p>\r\n                </div>\r\n            </div>\r\n            <div class=\"col-md-4 col-sm-4 col-sm-12\">\r\n                <div class=\"choose-block center-block\">\r\n                    <div class=\"icon\">\r\n                        <img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-graph.png\" alt=\"\">\r\n                        <i class=\"sub-icon fa fa-check\"></i>\r\n                    </div>\r\n                    <p>Participate in various events and school programs whenever you want.</p>\r\n                </div>\r\n            </div>\r\n                    </div>\r\n    </div>\r\n</section>'),
		(3, 'Featured_On_Section', '<section class=\"featured-on\">\r\n        <div class=\"container\">\r\n            <div class=\"row\">\r\n                <div class=\"col-lg-2 col-md-3 col-sm-3 col-xs-12\">\r\n                    <h4>FEATURED ON</h4>\r\n                </div>\r\n                <div class=\"col-lg-10 col-md-9 col-sm-9 col-xs-12\">\r\n                    <ul>\r\n                        <li>\r\n                            <a href=\"#\"><img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/1.png\" alt=\"\"></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href=\"#\"><img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/2.png\" alt=\"\"></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href=\"#\"><img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/3.png\" alt=\"\"></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href=\"#\"><img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/4.png\" alt=\"\"></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href=\"#\"><img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/5.png\" alt=\"\"></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href=\"#\"><img src=\"http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/6.png\" alt=\"\"></a>\r\n                        </li>\r\n                    </ul>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </section>')";


		$table5_insert_query = "INSERT INTO `pre_seosettings` (`id`, `type`, `allowed_variables`, `seo_title`, `seo_description`, `seo_keywords`, `created`, `updated`) VALUES
			(1, 'courses_single', '__COURSE_NAME__\n\n__COURSES__\n__CATEGORIES__\n__LOCATIONS__\n__TEACHING_TYPES__', '__COURSE_NAME__ TItle ######################### __CATEGORIES__', 'gsdfgsd', 'gsgfsdgfsdgsfd', '2017-02-23 07:09:30', NULL),
			(3, 'location', '__COURSES__\r\n__CATEGORIES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', 'Location SEO title', 'Location Seo description', 'Location Seo keywords', '2017-02-06 09:14:52', NULL),
			(4, 'teaching_type', '__COURSES__\r\n__CATEGORIES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', 'Teaching Type Seo title', 'Teaching Type Seo description', 'Teaching Type Seo keywords', '2017-02-06 09:14:57', NULL),
			(5, 'homepage', '__COURSES__\r\n__CATEGORIES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', 'Home Page SEO __COURSE_NAME__ TItle', 'Home Page Seo description', 'Home Page Seo keywords', '2017-02-06 09:15:02', NULL),
			(6, 'findtutor', '__COURSES__\r\n__CATEGORIES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', 'Find Tutor SEO __COURSES__ TItle', 'Find Tutor Seo description', 'Find Tutor Seo keywords ', '2017-02-06 09:15:06', NULL),
			(7, 'findleads', '__COURSES__\r\n__CATEGORIES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', 'FInd LEads Seo title', 'FInd LEads Seo description', 'FInd LEads Seo keywords', '2017-02-06 09:15:10', NULL),
			(8, 'findinstitute', '__COURSES__\r\n__CATEGORIES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', 'Find Institute SEO TItle __COURSE_NAME__ __ADIYYA__', 'Find Institute Seo description', 'Find Institute Seo keywords', '2017-02-06 09:15:15', NULL),
			(9, 'categories', '__COURSES__\r\n__CATEGORIES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', 'Categories Seo title', 'Categories Seo description ', 'Categories Seo keywords ', '2017-02-06 08:34:13', NULL),
			(10, 'courses', '__COURSES__\r\n__CATEGORIES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', 'Courses Seo title __CATEGORIES__ ################################ __COURSES__', 'Courses Seo description ', 'Courses Seo keywords ', '2017-02-06 08:34:25', NULL),
			(11, 'tutor_single', '__TUTOR_NAME__\r\n__COURSES__\r\n__LOCATIONS__', 'Single Tutor SEO Title', 'Single Tutor SEO description ', 'Single Tutor SEO keywords ', '2017-02-06 07:41:45', NULL),
			(12, 'dynamicpage', '__COURSES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', NULL, NULL, NULL, '2017-02-06 08:34:30', NULL),
			(13, 'institute_single', '__INSTITUTE_NAME__\r\n\r\n__COURSES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', NULL, NULL, NULL, '2017-02-06 08:34:36', NULL),
			(15, 'student_single', '__COURSES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', NULL, NULL, NULL, '2017-02-06 08:34:40', NULL),
			(17, 'categories_single', '__COURSES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', NULL, NULL, NULL, '2017-02-06 08:34:48', NULL),
			(18, 'about_us', '__COURSES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', NULL, NULL, NULL, '2017-02-06 08:33:13', NULL),
			(19, 'contact_us', '__COURSES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', NULL, NULL, NULL, '2017-02-06 08:33:08', NULL),
			(20, 'faqs', '__COURSES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', NULL, NULL, NULL, '2017-02-06 08:33:03', NULL),
			(21, 'login', '__COURSES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', '__COURSES__ TItle', NULL, NULL, '2017-02-06 08:32:38', NULL)";




		$table7_insert_query = "INSERT INTO `pre_tutor_selling_courses` (`sc_id`, `tutor_id`, `course_name`, `slug`, `course_title`, `description`, `image`, `skill_level`, `languages`, `preview_image`, `preview_file`, `course_price`, `admin_commission_percentage`, `max_downloads`, `status`, `admin_approved`, `total_purchases`, `total_downloads`, `created_at`, `updated_at`) VALUES
			(1, 7, 'PHP', 'php', 'PHP for Beginners', '<p>\r\n This is a complete and free PHP programming course for beginners. It&#39;s assumed that you already have some HTML skills. But you don&#39;t need to be a guru, by any means. If you need a refresher on HTML, then click the link for the Web Design course on the left of this page. Everything you need to get started with this PHP course is set out in section one below. Good luck!</p>', 'a4cb2-php_2017022401181912571886.jpg', 'Beginners Level', 'English', 'PHP-Tutorial-for-Beginners-How-to-Get-Started-with-PHP1_20170224011819944909548.jpeg', '392520036_201702240118192072880938.mp4', '10.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 13:18:19', '2017-02-24 13:18:19'),
			(2, 7, 'Javascript', 'javascript', 'JavaScript for Geeks', '<h2>\r\n About the Course</h2>\r\n<p>\r\n JavaScript is a cross-platform, object-oriented scripting language. It is a small and lightweight language. Inside a host environment (for example, a web browser), JavaScript can be connected to the objects of its environment to provide programmatic control over them.</p>', 'Javascript_20170224012856544056998.jpeg', 'Beginners Level', 'English', 'Javascript_201702240128561826486567.jpeg', '36_1_201702240128561621981106.pdf', '15.00', '10.00', 10, 'Active', 'Yes', 1, 2, '2017-02-24 13:28:56', '2017-02-24 13:28:56'),
			(3, 7, 'ASP.NET', 'aspnet', 'ASP.NET Advanced', '<p>\r\n <span class=\"_Tgc\"><b>ASP</b>.<b>NET</b> is an open-source server-side web application framework designed for web development to produce dynamic web pages. It was developed by Microsoft to allow programmers to build dynamic web sites, web applications and web services</span></p>', 'images_201702240243151551643217.png', 'High', 'English', 'index_20170224024315323368095.png', 'index_201702240243152056525191.jpeg', '20.00', '10.00', 4, 'Active', 'Yes', 0, 0, '2017-02-24 14:42:12', '2017-02-24 14:43:15'),
			(4, 7, 'VB. NET', 'vb-net', 'VB. NET for Begineers', '<p>\r\n <span class=\"_Tgc\"><b>Visual Basic</b> .<b>NET</b> (<b>VB</b>.<b>NET</b>) is a multi-paradigm, object-oriented programming language, implemented on the .<b>NET</b> Framework. Microsoft launched <b>VB</b>.<b>NET</b> in 2002 as the successor to its original <b>Visual Basic</b> language.</span></p>', 'vb_net_201702240253251938029824.jpeg', 'Beginners', 'English', 'vb_net1_20170224025325699625896.jpeg', NULL, '21.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 14:53:25', '2017-02-24 14:53:25'),
			(5, 7, 'VB. NET Advanced', 'vb-net-advanced', 'VB.Net for Advanced', '<p>\r\n <span class=\"_Tgc\"><b>Visual Basic</b> .<b>NET</b> (<b>VB</b>.<b>NET</b>) is a multi-paradigm, object-oriented programming language, implemented on the .<b>NET</b> Framework. Microsoft launched <b>VB</b>.<b>NET</b> in 2002 as the successor to its original <b>Visual Basic</b> language.</span></p>', 'vb_net1_20170224025701675944812.jpeg', 'Advanced Level', 'English', 'vb_net_20170224025701878697088.jpeg', NULL, '10.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 14:57:01', '2017-02-24 14:57:01'),
			(6, 7, 'Java', 'java', 'Core Java', '<p>\r\n <span class=\"_Tgc\">\"<b>Core Java</b>\" is Sun&#39;s term, used to refer to <b>Java</b> SE, the standard edition and a set of related technologies, like the <b>Java</b> VM, CORBA, et cetera. This is mostly to differentiate from, say, <b>Java</b> ME or <b>Java</b> EE. Also note that they&#39;re talking about a set of libraries rather than the programming language.</span></p>', 'Core Java2_20170224030248445198418.jpeg', 'Basice Java', 'English', 'Core Java1_20170224030248264139.jpeg', 'Core Java_201702240302481521566904.jpeg', '25.00', '10.00', 6, 'Active', 'Yes', 0, 0, '2017-02-24 15:02:48', '2017-02-24 15:02:48'),
			(7, 7, 'Java', 'java-1', 'Advanced Java', '<p>\r\n Java is based on the C and C++ programming languages, but differs from these languages is some important ways. The main difference between C/C++ and Java is that in Java all development is done with objects and classes. This main difference provides distinct advantages for programs written in Java, such as multiple threads of control and dynamic loading.</p>', 'advanced java2_201702240309031469962645.png', 'Advance Java', 'English', 'advanced java1_2017022403090385193016.jpeg', 'advanced java_2017022403090329320370.png', '30.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 15:09:03', '2017-02-24 15:09:03'),
			(8, 7, 'J2EE', 'j2ee', 'J2EE', '<p>\r\n <span class=\"_Tgc\"><b>J2EE</b> is a platform-independent, Java-centric environment from Sun for developing, building and deploying Web-based enterprise applications online. The <b>J2EE</b> platform consists of a set of services, APIs, and protocols that provide the functionality for developing multitiered, Web-based applications.</span></p>', 'J2EE2_20170224031502990018658.png', 'High', 'English', 'J2EE1_20170224031502133378315.png', NULL, '15.00', '10.00', 6, 'Active', 'Yes', 1, 2, '2017-02-24 15:15:02', '2017-02-24 15:15:02'),
			(9, 7, 'Adobe Photoshop', 'adobe-photoshop', 'Adobe Photoshop', '<p>\r\n <span class=\"_Tgc\">An image editing software developed and manufactured by <b>Adobe</b> Systems Inc. <b>Photoshop</b> is considered one of the leaders in photo editing software. The software allows users to manipulate, crop, resize, and correct color on digital photos.</span></p>', 'photoshop2_201702240320381627573671.jpeg', 'Beginners', 'English', 'photoshop1_201702240320381422715363.jpeg', 'photoshop_201702240320381800689921.jpeg', '15.00', '10.00', 4, 'Active', 'Yes', 2, 2, '2017-02-24 15:20:38', '2017-02-24 15:20:38'),
			(10, 7, 'Adobe Illustrator', 'adobe-illustrator', 'Adobe Illustrator', '<p>\r\n <span class=\"_Tgc\"><b>Adobe Illustrator</b> is a program used by both artists and graphic designers to create vector images. These images will then be used for company logos, promotional uses or even personal work, both in print and digital form.</span></p>', 'adobe illustrator2_201702240330361372652499.jpeg', 'Beginners', 'English', 'adobe illustrator1_201702240330361793679065.png', NULL, '20.00', '10.00', 6, 'Active', 'Yes', 0, 0, '2017-02-24 15:30:36', '2017-02-24 15:30:36'),
			(11, 7, 'Adobe In design', 'adobe-in-design', 'Adobe In design', '<p>\r\n <span class=\"_Tgc\"><b>Adobe</b> InDesign is a desktop publishing software application produced by <b>Adobe</b> Systems. It can be used to create works such as posters, flyers, brochures, magazines, newspapers, and books.</span></p>', 'adobe indesign2_201702240336221911544902.jpeg', 'High', 'English', 'adobe indesign1_201702240336221912178825.png', 'adobe indesign_201702240336221979300358.jpeg', '15.00', '10.00', 6, 'Active', 'Yes', 0, 0, '2017-02-24 15:36:22', '2017-02-24 15:36:22'),
			(12, 7, 'Adobe Premier', 'adobe-premier', 'Adobe Premier', '<p>\r\n <span class=\"_Tgc\"><b>Adobe Premiere</b> Pro. <b>Adobe Premiere</b> Pro is a timeline-based video editing software application. It is part of the <b>Adobe</b> Creative Cloud, which includes video editing, graphic design, and web development programs</span></p>', 'Adobe Premier3_201702240344282128690777.jpeg', 'High', 'English', 'Adobe Premier2_20170224034428149495172.jpeg', 'premiere_pro_reference_2017022403442851340063.pdf', '30.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 15:44:28', '2017-02-24 15:44:28'),
			(13, 7, 'AutoCAD', 'autocad', 'AutoCAD', '<p>\r\n <span class=\"_Tgc\"><b>AutoCAD</b> is a computer-aided design (CAD) program used for 2-D and 3-D design and drafting. <b>AutoCAD</b> is developed and marketed by Autodesk Inc. and was one of the first CAD programs that could be executed on personal computers.</span></p>', 'AutoCAD_20170224034846573698015.jpeg', 'Beginners', 'English', 'AutoCAD1_201702240348461988087761.jpeg', 'AutoCAD2_201702240348461704529751.jpeg', '25.00', '10.00', 4, 'Active', 'Yes', 1, 2, '2017-02-24 15:48:46', '2017-02-24 15:48:46'),
			(14, 7, 'Unix', 'unix', 'Unix Basics', '<p>\r\n <span class=\"_Tgc\"><b>Unix</b> (often spelled \"<b>UNIX</b>,\" especially as an official trademark) is an operating system that originated at Bell Labs in 1969 as an interactive time-sharing system. Ken Thompson and Dennis Ritchie are considered the inventors of <b>Unix</b></span></p>', 'unix2_20170224035318878882697.jpeg', 'Beginners', 'English', 'unix1_201702240353182039825312.jpeg', NULL, '14.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 15:53:18', '2017-02-24 15:53:18'),
			(15, 7, 'Linux', 'linux', 'Linux Basics', '<p>\r\n <span class=\"_Tgc\">The <b>Linux</b> open source operating system, or <b>Linux</b> OS, is a freely distributable, cross-platform operating system based on Unix that can be installed on PCs, laptops, netbooks, mobile and tablet devices, video game consoles, servers, supercomputers and more.</span></p>', 'Linux2_20170224035839237218605.png', 'High', 'English', 'Linux1_20170224035839227036979.png', NULL, '20.00', '10.00', 6, 'Active', 'Yes', 1, 1, '2017-02-24 15:58:39', '2017-02-24 15:58:39'),
			(16, 7, 'C  Language', 'c-language', 'C  Language', '<p>\r\n <span class=\"_Tgc\"><b>C</b> is a high-level and general-purpose <b>programming language</b> that is ideal for developing firmware or portable applications. Originally intended for writing system software, <b>C</b> was developed at Bell Labs by Dennis Ritchie for the Unix Operating System &#40;OS&#41; in the early 1970s.</span></p>', 'C  Language2_2017022404032627491715.jpeg', 'High', 'English', 'C  Language1_20170224040326941524782.jpeg', 'C  Language_201702240403261567994348.jpeg', '15.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 16:03:26', '2017-02-24 16:03:26'),
			(17, 7, 'C ++ Language', 'c-language', 'C ++ Language', '<p>\r\n <span class=\"_Tgc _y9e\"><b>C++</b> is an object oriented programming (OOP) language, developed by Bjarne Stroustrup, and is an extension of C language. It is therefore possible to code <b>C++</b> in a \"C style\" or \"object-oriented style.\" In certain scenarios, it can be coded in either way and is thus an effective example of a hybrid language.</span></p>', 'C ++ Language2_201702240410171109926245.png', 'Beginners', 'English', 'C ++ Language1_201702240410171739703513.png', NULL, '25.00', '10.00', 4, 'Active', 'Yes', 1, 4, '2017-02-24 16:10:17', '2017-02-24 16:10:17'),
			(18, 7, 'Oracle', 'oracle', 'Oracle', '<p>\r\n <span class=\"_Tgc\"><b>Oracle</b> database (<b>Oracle</b> DB) is a relational database management system &#40;RDBMS&#41; from the <b>Oracle</b> Corporation. Originally developed in 1977 by Lawrence Ellison and other developers, <b>Oracle</b> DB is one of the most trusted and widely-used relational database engines.</span></p>', 'Oracle2_20170224041440463807118.jpeg', 'High', 'English', 'Oracle1_201702240414401766992673.png', 'e40540_201702240414401748840799.pdf', '30.00', '10.00', 6, 'Active', 'Yes', 0, 0, '2017-02-24 16:14:40', '2017-02-24 16:14:40'),
			(19, 7, 'SQL Server', 'sql-server', 'SQL Server', '<p>\r\n <span class=\"_Tgc\"><b>SQL Server</b> is Microsoft&#39;s relational database management system &#40;RDBMS&#41;. It is a full-featured database primarily designed to compete against competitors Oracle Database (DB) and MySQL. ... <b>SQL Server</b> is sometimes referred to as MSSQL and Microsoft <b>SQL Server</b>.</span></p>', 'SQL Server2_20170224043420682067685.png', 'Beginners', 'English', 'SQL Server1_201702240434201188815766.jpeg', 'SQL Server_201702240434201100359368.png', '30.00', '10.00', 6, 'Active', 'Yes', 1, 2, '2017-02-24 16:34:20', '2017-02-24 16:34:20'),
			(20, 7, 'Selenium Testing', 'selenium-testing', 'Selenium Tool', '<p>\r\n <span class=\"_Tgc _y9e\"><b>Selenium</b> provides a record/playback tool for authoring <b>tests</b> without learning a <b>test</b> scripting language (<b>Selenium</b> IDE). It also provides a <b>test</b> domain-specific language (Selenese) to write <b>tests</b> in a number of popular programming languages, including C#, Groovy, Java, Perl, PHP, Python, Ruby and Scala.</span></p>', 'selenium2_20170224043731541211946.jpeg', 'High', 'English', 'selenium1_20170224043731459491976.jpeg', 'selenium_201702240437311001158441.jpeg', '20.00', '10.00', 5, 'Active', 'Yes', 1, 1, '2017-02-24 16:37:31', '2017-02-24 16:37:31'),
			(21, 7, 'Web Services Testing', 'web-services-testing', 'Web Services Testing', '<p>\r\n <span class=\"_Tgc _y9e\">Software Applications communicate and exchange data with each other using a WebService. ... REST support XML, Json or exchange of data in simple URL. WSDL is XML based language which will be used to describe the <b>services</b> offered by a <b>web service</b>. SOAP is defined using WSDL. To <b>test</b> WebService you </span></p>', 'Web Services Testing2_201702240441071201868206.png', 'Beginners', 'English', 'Web Services Testing1_201702240441071763285221.png', 'e40540_201702240441071675875030.pdf', '30.00', '10.00', 6, 'Active', 'Yes', 0, 0, '2017-02-24 16:41:07', '2017-02-24 16:41:07'),
			(22, 7, 'LoadRunner Tool', 'loadrunner-tool', 'LoadRunner', '<p>\r\n <span class=\"_Tgc\">HPE <b>LoadRunner</b> is a software testing tool from Hewlett Packard Enterprise. In Sept 2016, HPE announced it is selling its software business, including Mercury products, to Micro Focus.</span></p>', 'LoadRunner2_201702240444481146325704.png', 'Advance Java', 'English', 'LoadRunner1_201702240444481040556274.png', 'LoadRunner_201702240444481124724910.png', '15.00', '10.00', 6, 'Active', 'Yes', 0, 0, '2017-02-24 16:44:48', '2017-02-24 16:44:48'),
			(23, 7, 'Database Testing', 'database-testing', 'Database Testing Tool', '<p>\r\n Database Testing is one of the major test requirements in case of backend testing. Testing data residing in database requires specific skill set to test properly.</p>', 'Database Testing2_20170224044847253432281.jpeg', 'Beginners', 'English', 'Database Testing1_20170224044847496411544.jpeg', 'Database Testing_201702240448471365649851.jpeg', '15.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 16:48:47', '2017-02-24 16:48:47'),
			(24, 7, 'SAP Testing', 'sap-testing', 'SAP Testing', '<p>\r\n SAP is the heartbeat of the global economy. SAP R/3 is an ERP software package implementation designed to coordinate all the key elements required to complete the business process. SAP designed into different functional modules covering the typical functions of an organization.</p>', 'SAP Testing2_20170224045321584377851.jpeg', 'Beginners', 'English', 'SAP Testing1_201702240453211277197380.jpeg', 'sap_testing_tutorial_2017022404532173778901.pdf', '30.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 16:53:21', '2017-02-24 16:53:21'),
			(25, 7, 'Mobile Testing', 'mobile-testing', 'Mobile Testing', '<p>\r\n <span class=\"_Tgc\"><b>Mobile</b> application <b>testing</b> is a process by which application software developed for hand held <b>mobile</b> devices is <b>tested</b> for its functionality, usability and consistency. <b>Mobile</b> application <b>testing</b> can be automated or manual type of <b>testing</b>.</span></p>', 'Mobile Testing2_20170224045713742422287.jpeg', 'Beginners', 'English', 'Mobile Testing1_20170224045713391603537.jpeg', 'Mobile Testing_20170224045713972064372.jpeg', '30.00', '10.00', 6, 'Active', 'Yes', 0, 0, '2017-02-24 16:57:13', '2017-02-24 16:57:13'),
			(26, 7, 'Spring in java', 'spring-in-java', 'Spring', '<p>\r\n <span class=\"_Tgc _y9e\">The <b>Spring</b> Framework is an application framework and inversion of control container for the <b>Java</b> platform. The framework&#39;s core features can be used by any <b>Java</b> application, but there are extensions for building web applications on top of the <b>Java</b> EE platform.</span></p>', 'Springs2_201702240502451208036135.jpeg', 'Beginners', 'English', 'Springs1_20170224050245371079052.jpeg', 'Springs_201702240502451498790748.jpeg', '20.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 17:02:45', '2017-02-24 17:02:45'),
			(27, 7, 'GRE Course', 'gre-course', 'GRE Course', '<p>\r\n <span class=\"_Tgc _y9e\">Much like the SAT and ACT, the <b>GRE</b> exam is a broad assessment of your critical thinking, analytical writing, verbal reasoning, and quantitative reasoning skills — all skills developed over the course of many years. Some schools may also require you to take one or more <b>GRE</b> Subject Tests</span></p>', 'GRE Course2_201702240508351996320414.png', 'Beginners', 'English', 'GRE Course1_20170224050835964841420.jpeg', 'GRE Course_201702240508351559045890.jpeg', '20.00', '10.00', 5, 'Active', 'Yes', 0, 0, '2017-02-24 17:08:35', '2017-02-24 17:08:35'),
			(28, 7, 'Struts in java', 'struts-in-java', 'Struts', '<p>\r\n <span class=\"_Tgc\"><b>Struts</b> is an open source framework that extends the <b>Java</b> Servlet API and employs a Model, View, Controller (MVC) architecture. It enables you to create maintainable, extensible, and flexible web </span></p>', 'Struts2_201702240513141851245046.jpeg', 'Beginners', 'English', 'Struts1_2017022405131483947441.jpeg', 'Struts_2017022405131454081050.png', '20.00', '10.00', 5, 'Active', 'Yes', 1, 0, '2017-02-24 17:13:14', '2017-02-24 17:13:14'),
			(29, 7, 'Salesforce', 'salesforce', 'Salesforce', '<p>\r\n <span class=\"_Tgc _y9e\"><b>Salesforce</b> is the leader in cloud computing, offering applications for all aspects of your business, including CRM, sales, ERP, customer service, marketing automation, business analytics, mobile application building, and much more.</span></p>', 'Salesforce2_201702240520311698111364.jpeg', 'Beginners', 'English', 'Salesforce1_20170224052031721176448.jpeg', NULL, '20.00', '10.00', 6, 'Active', 'Yes', 0, 0, '2017-02-24 17:20:31', '2017-02-24 17:20:31'),
			(30, 7, 'JQuery', 'jquery', 'JQuery', '<p>\r\n <span class=\"_Tgc\"><b>jQuery</b> is a concise and fast JavaScript library that can be used to simplify event handling, HTML document traversing, Ajax interactions and animation for speedy website development. <b>jQuery</b> simplifies the HTML&#39;s client-side scripting, thus simplifying Web 2.0 applications development.</span></p>', 'JQuery2_20170224052527417619731.png', 'Beginners', 'English', 'JQuery1_201702240525271041510943.png', 'jquery_tutorial_201702240525271361816430.pdf', '30.00', '10.00', 6, 'Active', 'Yes', 0, 0, '2017-02-24 17:25:27', '2017-02-24 17:25:27')";



		$table8_insert_query = "INSERT INTO `pre_tutor_selling_courses_curriculum` (`file_id`, `sc_id`, `title`, `source_type`, `file_name`, `file_ext`, `file_size`) VALUES
			(1, 1, 'Introduction to PHP', 'file', '1_1_201702240118192137827355.mp4', 'mp4', '8819935'),
			(2, 1, 'Conditionals and Control Flow', 'url', 'https://www.codecademy.com/courses/web-beginner-en-StaFQ/0/1?curriculum_id=5124ef4c78d510dd89003eb8', NULL, NULL),
			(3, 2, 'Introduction to JS', 'url', 'https://www.w3schools.com/js/js_intro.asp', NULL, NULL),
			(4, 2, 'vaScript Syntax', 'url', 'https://www.w3schools.com/js/js_syntax.asp', NULL, NULL),
			(5, 2, 'JavaScript Statements', 'url', 'https://www.w3schools.com/js/js_statements.asp', NULL, NULL),
			(6, 3, 'Asp.Net Course', 'url', 'https://www.youtube.com/watch?v=wQZRC7vXT08', NULL, NULL),
			(7, 3, 'Asp.Net Course', 'url', 'https://www.youtube.com/watch?v=wQZRC7vXT08', NULL, NULL),
			(8, 4, 'VB.Net Curriculum', 'url', 'https://www.youtube.com/watch?v=4iMS3Y0yei8', NULL, NULL),
			(9, 5, 'VB.NET Advanced Course', 'url', 'https://www.youtube.com/watch?v=4iMS3Y0yei8', NULL, NULL),
			(10, 6, 'Core Java Curriculum', 'file', '6_1_201702240302481429849223.pdf', 'pdf', '4478613'),
			(11, 7, 'Advanced Java Course', 'file', '7_1_201702240309031544630272.pdf', 'pdf', '4478613'),
			(12, 8, 'J2EE', 'url', 'https://www.youtube.com/watch?v=H9VT8S9yIh4', NULL, NULL),
			(13, 9, 'Adobe Photoshop', 'url', 'https://www.youtube.com/watch?v=pFyOznL9UvA', NULL, NULL),
			(14, 10, 'Adobe illustrator', 'file', '10_1_201702240330362023307460.pdf', 'pdf', '20006876'),
			(15, 11, 'Adobe indesign', 'file', '11_1_201702240336221839383101.pdf', 'pdf', '20006876'),
			(16, 12, 'Adobe premiere', 'url', 'https://www.youtube.com/watch?v=Hls3Tp7JS8E', NULL, NULL),
			(17, 13, 'AutoCAD Curriculum ', 'file', '13_1_20170224034846356440815.pdf', 'pdf', '6244949'),
			(18, 14, 'Unix', 'file', '14_1_201702240353181933035479.jpeg', 'jpeg', '4416'),
			(19, 15, 'Linux Curriculum', 'url', 'https://www.youtube.com/watch?v=HIXzJ3Rz9po', NULL, NULL),
			(20, 16, 'C Language', 'file', '16_1_201702240403272010145415.pdf', 'pdf', '1614974'),
			(21, 17, 'C ++ Language', 'url', 'https://www.youtube.com/watch?v=-CpG3oATGIs', NULL, NULL),
			(22, 18, 'Oracle Curriculum', 'url', 'https://www.youtube.com/watch?v=Kk6MuZegDNs', NULL, NULL),
			(23, 19, 'SQL Server Curriculum', 'url', 'https://www.youtube.com/watch?v=ZanGRT1MsHM', NULL, NULL),
			(24, 20, 'Selenium Testing ', 'url', 'https://www.youtube.com/watch?v=eNCzuCxuEXM', NULL, NULL),
			(25, 21, 'Web Services Testing', 'url', 'https://www.youtube.com/watch?v=ijMT1HIO9tg', NULL, NULL),
			(26, 22, 'LoadRunner Tool Curriculum', 'file', '22_1_20170224044448397078478.pdf', 'pdf', '6717800'),
			(27, 23, 'Database Testing Curriculum', 'url', 'https://www.youtube.com/watch?v=sRaPFn9ZQDs', NULL, NULL),
			(28, 24, 'SAP Testing Curriculum', 'url', 'https://www.youtube.com/watch?v=jBNUiWFtfVM', NULL, NULL),
			(29, 25, 'Mobile Testing Curriculum', 'url', 'https://www.youtube.com/watch?v=mTIdoDEuXrM', NULL, NULL),
			(30, 26, 'Spring Curriculum', 'url', 'https://www.youtube.com/watch?v=-weKK-oNuhA', NULL, NULL),
			(31, 27, 'GRE Course Curriculum', 'url', 'https://www.youtube.com/watch?v=9kcmT1_lz3g', NULL, NULL),
			(32, 28, 'Struts in java Curriculum', 'url', 'https://www.youtube.com/watch?v=28AfOA2I6LE', NULL, NULL),
			(33, 29, 'Salesforce', 'url', 'https://www.youtube.com/watch?v=9Gsmiff27do', NULL, NULL),
			(34, 30, 'JQuery Curriculum', 'file', '30_1_201702240525271569144267.pdf', 'pdf', '2424486')";



		$settings_table_insert_query = "INSERT INTO `pre_system_settings_fields` (`field_id`, `type_id`, `field_name`, `field_key`, `is_required`, `field_order`, `field_type`, `field_type_values`, `field_output_value`, `field_type_slug`, `created`, `updated`) VALUES
			(163, 41, 'Test Secret Key', 'stripe_key_test_secret', 'Yes', 2, 'text', 'sk_test_FHxf1NsgaWbAFAGny5zJELqU', 'sk_test_FHxf1NsgaWbAFAGny5zJELqU', 'Publishable Key', '2017-01-17 05:16:57', NULL),
			(164, 41, 'Mode', 'stripe_test_mode', 'Yes', 3, 'select', '<p>yes,no</p>\r\n', '<p>yes</p>\r\n', 'Mode', '2017-01-17 05:26:32', NULL),
			(165, 41, 'Test Publishable Key:', 'stripe_key_test_publishable', 'Yes', 0, 'text', 'pk_test_H8R3tFH4RiyF0VGzTcXwl8NF', 'pk_test_H8R3tFH4RiyF0VGzTcXwl8NF', NULL, '2017-01-17 13:17:30', NULL),
			(166, 41, 'Live Secret Key:', 'stripe_key_live_secret', 'Yes', 0, 'text', 'sk_live_hoU7qpgZtg0gwTxu2My7KAcZ', 'sk_live_hoU7qpgZtg0gwTxu2My7KAcZ', NULL, '2017-01-17 13:18:20', NULL),
			(167, 41, 'Live Publishable Key:', 'stripe_key_live_publishable', 'Yes', 0, 'text', 'pk_live_wPo6I0iKgXrs9mrk08cfwzc4', 'pk_live_wPo6I0iKgXrs9mrk08cfwzc4', NULL, '2017-01-17 13:19:27', NULL),
			(168, 47, 'Instructions', 'manual_instructions', 'No', 0, 'text', 'Acc NO. - 50100043054861\r\nName : ADIYYA TADIKAMALLA\r\nBank : HDFC\r\nBranch - BEGUMPET\r\nIFSC : HDFC0000621\r\n\r\nSend your details after made a transaction.', 'Acc NO. - 50100043054861\r\nName : ADIYYA TADIKAMALLA\r\nBank : HDFC\r\nBranch - BEGUMPET\r\nIFSC : HDFC0000621\r\n\r\nSend your details after made a transaction.', NULL, '2017-01-18 08:17:09', NULL),
			(169, 48, 'Seller Id', '2check_seller_id', 'Yes', 0, 'text', '901337661', '901337661', NULL, '2017-01-18 09:07:43', NULL),
			(170, 48, 'Secret Word', '2check_secret_word', 'Yes', 0, 'text', 'ZDg3MzVkZTAtYzIzZi00ZGNhLTg2MzctYWU0MDJhYzVmZjQ4', 'ZDg3MzVkZTAtYzIzZi00ZGNhLTg2MzctYWU0MDJhYzVmZjQ4', NULL, '2017-01-18 09:08:45', NULL),
			(171, 48, 'Is Demo', '2check_is_demo', 'Yes', 0, 'select', 'yes,no', 'yes', NULL, '2017-01-18 09:09:00', NULL),
			(172, 42, 'ID Sprzedawcy', 'transferuj_merchantid', 'Yes', 0, 'text', '1010', '1010', NULL, '2017-01-19 05:11:32', NULL),
			(173, 42, 'Secrete Key', 'transferuj_secretpass', 'Yes', 0, 'text', 'demo', 'demo', NULL, '2017-01-19 05:22:19', NULL),
			(174, 43, 'PagSeguro Sandbox Email', 'pagseguro_sandbox_email', 'No', 0, 'text', 'adiyya@gmail.com', 'adiyya@gmail.com', NULL, '2017-01-19 12:51:43', NULL),
			(175, 43, 'PagSeguro Sandbox Token', 'pagseguro_sandbox_token', 'No', 0, 'text', '2E3BDB1A45D745C5AB3B54EC45093A95', '2E3BDB1A45D745C5AB3B54EC45093A95', NULL, '2017-01-19 12:52:37', NULL),
			(176, 43, 'PagSeguro Email', 'pagseguro_email', 'Yes', 0, 'text', 'adiyya@gmail.com', 'adiyya@gmail.com', NULL, '2017-01-19 12:53:01', NULL),
			(177, 43, 'PagSeguro Token', 'pagseguro_token', 'Yes', 0, 'text', '2E3BDB1A45D745C5AB3B54EC45093A95', '2E3BDB1A45D745C5AB3B54EC45093A95', NULL, '2017-01-19 12:53:58', NULL),
			(178, 43, 'PagSeguro Mode', 'pagseguro_mode', 'Yes', 0, 'select', 'sandbox,live', 'sandbox', NULL, '2017-01-19 13:54:43', NULL),
			(179, 44, 'Online/Offline gateway', 'is_test', 'Yes', 0, 'select', 'yes,no', 'yes', NULL, '2017-01-20 05:49:49', '2017-01-27 11:42:32'),
			(180, 44, 'Purse WMZ', 'purse_wmz', 'Yes', 0, 'text', 'Z', 'Z', NULL, '2017-01-20 05:50:49', '2017-01-27 11:42:32'),
			(181, 44, 'Secret key for WMZ', 'purse_wmz_secret', 'Yes', 0, 'text', NULL, 'Z145179295679', NULL, '2017-01-20 05:51:17', '2017-01-27 11:42:32'),
			(182, 44, 'Purse WME', 'purse_wme', 'Yes', 0, 'text', NULL, 'E', NULL, '2017-01-20 05:51:48', '2017-01-27 11:42:32'),
			(183, 44, 'Secret key for WME', 'purse_wme_secret', 'Yes', 0, 'text', NULL, 'test', NULL, '2017-01-20 05:52:42', '2017-01-27 11:42:32'),
			(184, 44, 'Purse WMR', 'purse_wmr', 'Yes', 0, 'text', NULL, 'R', NULL, '2017-01-20 05:53:26', '2017-01-27 11:42:32'),
			(185, 44, 'Secret key for WMR', 'purse_wmr_secret', 'Yes', 0, 'text', NULL, 'R397656178472', NULL, '2017-01-20 05:53:51', '2017-01-27 11:42:32'),
			(186, 44, 'Purse WMU', 'purse_wmu', 'Yes', 0, 'text', NULL, 'U', NULL, '2017-01-20 05:54:20', '2017-01-27 11:42:32'),
			(187, 44, 'Secret key for WMU', 'purse_wmu_secret', 'Yes', 0, 'text', NULL, 'test', NULL, '2017-01-20 05:54:42', '2017-01-27 11:42:32'),
			(188, 45, 'Shop ID', 'ym_ShopID', 'Yes', 0, 'text', '151', '151', NULL, '2017-01-30 06:59:51', NULL),
			(189, 45, 'Number Store Front', 'ym_Scid', 'Yes', 0, 'text', '363', '363', NULL, '2017-01-30 07:01:14', NULL),
			(190, 45, 'Payment Types', 'ym_payment_types', 'Yes', 0, 'checkbox', 'Yandex Purse, Credit Card,Cash desk and terminals', 'Yandex Purse, Credit Card,Cash desk and terminals', NULL, '2017-01-30 07:26:20', NULL),
			(191, 45, 'Mode', 'ym_mode', 'Yes', 0, 'select', 'sandbox,live', 'sandbox', NULL, '2017-01-30 10:12:32', NULL),
			(192, 46, 'Merchant ID', 'payza_email', 'Yes', 0, 'text', NULL, 'adiyya@gmail.com', NULL, '2017-01-30 11:54:01', NULL),
			(193, 46, 'Mode', 'payza_mode', 'Yes', 0, 'select', 'sandbox,live', 'sandbox', NULL, '2017-01-30 12:19:08', NULL),
			(194, 49, 'Key ID', 'razorpay_key_id', 'Yes', 0, 'text', NULL, 'rzp_test_tjwMzd8bqhZkMr', NULL, '2017-01-30 12:19:26', NULL),
			(195, 49, 'Key Secret', 'razorpay_key_secret', 'Yes', 0, 'text', NULL, 'EWI9VQiMH43p6LDCbpsgvvHZ', NULL, '2017-01-31 04:58:16', NULL),
			(196, 49, 'Payment Action', 'razorpay_payment_action', 'Yes', 0, 'select', 'authorize,capture', 'capture', NULL, '2017-01-31 04:58:58', NULL),
			(197, 49, 'Mode', 'razorpay_mode', 'Yes', 0, 'select', 'sandbox,live', 'sandbox', NULL, '2017-01-31 05:00:35', NULL),
			(198, 1, 'Admin Commission Percentage On Each Purchase', 'admin_commission_on_course_purchase', 'Yes', 0, 'text', NULL, '10', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:27')";




			//Indexes for Tables
			$table1_indexes_query = "ALTER TABLE `pre_course_downloads` ADD PRIMARY KEY (`cd_id`)";

			$table2_indexes_query = "ALTER TABLE `pre_course_purchases` ADD PRIMARY KEY (`purchase_id`)";

			$table3_indexes_query = "ALTER TABLE `pre_payments_data` ADD PRIMARY KEY (`id`)";

			$table4_indexes_query = "ALTER TABLE `pre_sections` ADD PRIMARY KEY (`section_id`)";

			$table5_indexes_query = "ALTER TABLE `pre_seosettings` ADD PRIMARY KEY (`id`)";

			$table6_indexes_query = "ALTER TABLE `pre_tpay_data` ADD KEY `id` (`id`)";

			$table7_indexes_query = "ALTER TABLE `pre_tutor_selling_courses` ADD PRIMARY KEY (`sc_id`)";

			$table8_indexes_query = "ALTER TABLE `pre_tutor_selling_courses_curriculum` ADD PRIMARY KEY (`file_id`)";

			$table9_indexes_query = "ALTER TABLE `pre_webmoney_data` ADD KEY `id` (`id`)";


			//Auto Increments for Tables
			$table1_incr_query = "ALTER TABLE `pre_course_downloads` MODIFY `cd_id` bigint(20) NOT NULL AUTO_INCREMENT";

			$table2_incr_query = "ALTER TABLE `pre_course_purchases` MODIFY `purchase_id` bigint(20) NOT NULL AUTO_INCREMENT";

			$table3_incr_query = "ALTER TABLE `pre_payments_data` MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT";

			$table4_incr_query = "ALTER TABLE `pre_sections` MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4";

			$table5_incr_query = "ALTER TABLE `pre_seosettings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22";

			$table6_incr_query = "ALTER TABLE `pre_tpay_data` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";

			$table7_incr_query = "ALTER TABLE `pre_tutor_selling_courses` MODIFY `sc_id` int(11) NOT NULL AUTO_INCREMENT;";

			$table8_incr_query = "ALTER TABLE `pre_tutor_selling_courses_curriculum` MODIFY `file_id` bigint(20) NOT NULL AUTO_INCREMENT;";

			$table9_incr_query = "ALTER TABLE `pre_webmoney_data` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";




			//Run Queries
			$this->db->query($table1_query);
			$this->db->query($table2_query);
			$this->db->query($table3_query);
			$this->db->query($table4_query);
			$this->db->query($table5_query);
			$this->db->query($table6_query);
			$this->db->query($table7_query);
			$this->db->query($table8_query);
			$this->db->query($table9_query);

			$this->db->query($table1_indexes_query);
			$this->db->query($table2_indexes_query);
			$this->db->query($table3_indexes_query);
			$this->db->query($table4_indexes_query);
			$this->db->query($table5_indexes_query);
			$this->db->query($table6_indexes_query);
			$this->db->query($table7_indexes_query);
			$this->db->query($table8_indexes_query);
			$this->db->query($table9_indexes_query);

			$this->db->query($table1_incr_query);
			$this->db->query($table2_incr_query);
			$this->db->query($table3_incr_query);
			$this->db->query($table4_incr_query);
			$this->db->query($table5_incr_query);
			$this->db->query($table6_incr_query);
			$this->db->query($table7_incr_query);
			$this->db->query($table8_incr_query);
			$this->db->query($table9_incr_query);

			$this->db->query($table1_insert_query);
			$this->db->query($table2_insert_query);
			$this->db->query($table4_insert_query);
			$this->db->query($table5_insert_query);
			$this->db->query($table7_insert_query);
			$this->db->query($table8_insert_query);
			$this->db->query($settings_table_insert_query);
			
			$query = "INSERT INTO `pre_system_settings_types` (`type_id`,`type_title`,`parent_id`,`type_slug`,`created`,`updated`,`is_default`,`status`) VALUES
(50,'Bigbluebutton',0,'bigbluebutton','2017-02-03 18:49:13',NULL,'No','Active')";
$this->db->query($query);

$query = "INSERT INTO `pre_system_settings_fields` (`field_id`,`type_id`,`field_name`,`field_key`,`is_required`,`field_order`,`field_type`,`field_type_values`,`field_output_value`,`field_type_slug`,`created`,`updated`) VALUES
(199,50,'Bigbluebutton Security Salt','bbb_security_salt','Yes',0,'text',NULL,'8cd8ef52e8e101574e400365b55e11a6',NULL,'2017-02-03 18:44:14',NULL),(200,50,'Bigbluebutton Server URL','bbb_base_server_url','Yes',0,'text','','http://test-install.blindsidenetworks.com/bigbluebutton/',NULL,'2017-02-03 18:45:15',NULL),(201,50,'Enable Live Classes','bbb_enable','Yes',0,'select','yes,no','yes',NULL,'2017-02-03 19:15:38',NULL)";
$this->db->query($query);

			if($this->db->affected_rows()) {

				$this->prepare_flashmessage(get_languageword("DB Upgrade Success"), 0);

			} else {

				$this->prepare_flashmessage(get_languageword("DB Upgrade failed due to some technical issue Please contact Admin"), 2);
			}

			redirect(URL_AUTH_LOGIN, REFRESH);


	}




}
