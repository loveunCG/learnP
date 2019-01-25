-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 23, 2017 at 12:43 PM
-- Server version: 5.7.17-0ubuntu0.16.04.1
-- PHP Version: 7.0.13-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `menorah-tutor-sellingcourses_nodata`
--

-- --------------------------------------------------------

--
-- Table structure for table `pre_admin_money_transactions`
--

CREATE TABLE `pre_admin_money_transactions` (
  `id` int(25) NOT NULL,
  `user_id` int(25) NOT NULL,
  `booking_id` bigint(20) NOT NULL COMMENT 'if user_type=tutor, booking_id can be referred from booking table ,,, if user_type=institute, booking_id can be referred from inst_enrolled_students with enroll_id',
  `user_type` enum('tutor','institute') DEFAULT NULL,
  `user_name` varchar(512) NOT NULL,
  `user_paypal_email` varchar(512) NOT NULL,
  `no_of_credits_to_be_converted` int(25) NOT NULL,
  `admin_commission_val` float NOT NULL DEFAULT '0' COMMENT 'in credits',
  `per_credit_cost` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status_of_payment` enum('Pending','Done') NOT NULL DEFAULT 'Pending',
  `user_bank_ac_details` text NOT NULL,
  `payment_mode` varchar(512) DEFAULT NULL,
  `transaction_details` varchar(1000) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_bookings`
--

CREATE TABLE `pre_bookings` (
  `booking_id` bigint(20) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `content` text CHARACTER SET utf8 COMMENT 'course content',
  `duration_value` tinyint(5) NOT NULL,
  `duration_type` enum('hours','days','months','years') CHARACTER SET utf8 NOT NULL DEFAULT 'days',
  `fee` float NOT NULL COMMENT 'in credits',
  `per_credit_value` float NOT NULL DEFAULT '1',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `time_slot` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `days_off` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
  `preferred_location` varchar(55) CHARACTER SET utf8 NOT NULL,
  `message` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `admin_commission` float NOT NULL DEFAULT '0' COMMENT 'admin commission in percentage with the fee in credits. result will be in round value of credits',
  `admin_commission_val` float NOT NULL DEFAULT '0' COMMENT 'admin commision value in credits',
  `prev_status` varchar(512) CHARACTER SET utf8 NOT NULL DEFAULT 'pending',
  `status` enum('pending','approved','cancelled_before_course_started','cancelled_when_course_running','cancelled_after_course_completed','session_initiated','running','completed','called_for_admin_intervention','closed') CHARACTER SET utf8 NOT NULL DEFAULT 'pending' COMMENT 'pending->when student makes booking, approved->when tutor approves student''s booking, cancelled->when tutor cancels the student''s booking request, session_initiated->when tutor starts the course session, running-when student joins the session, completed->when student confirms the tutor''s course completion, called_for_admin_intervention->when student not satisfied with the course can call for admin intervention, which may cost some credits, and if nothing wrong with student amt will be refunded to student with intervention charges and that intervention charges will be deducted from tutors account, if nothing is wrong with Tutor, course credits will be credited to that Tutor, closed->course training is closed for that booking.',
  `status_desc` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL COMMENT 'record updated user id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_calendar_timezones`
--

CREATE TABLE `pre_calendar_timezones` (
  `CountryCode` char(2) NOT NULL,
  `Coordinates` char(15) NOT NULL,
  `TimeZone` char(32) NOT NULL,
  `Comments` varchar(85) NOT NULL,
  `UTC offset` char(8) NOT NULL,
  `UTC DST offset` char(8) NOT NULL,
  `Notes` varchar(79) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_calendar_timezones`
--

INSERT INTO `pre_calendar_timezones` (`CountryCode`, `Coordinates`, `TimeZone`, `Comments`, `UTC offset`, `UTC DST offset`, `Notes`) VALUES
('CI', '+0519-00402', 'Africa/Abidjan', '', '+00:00', '+00:00', ''),
('GH', '+0533-00013', 'Africa/Accra', '', '+00:00', '+00:00', ''),
('ET', '+0902+03842', 'Africa/Addis_Ababa', '', '+03:00', '+03:00', ''),
('DZ', '+3647+00303', 'Africa/Algiers', '', '+01:00', '+01:00', ''),
('ER', '+1520+03853', 'Africa/Asmara', '', '+03:00', '+03:00', ''),
('', '', 'Africa/Asmera', '', '+03:00', '+03:00', 'Link to Africa/Asmara'),
('ML', '+1239-00800', 'Africa/Bamako', '', '+00:00', '+00:00', ''),
('CF', '+0422+01835', 'Africa/Bangui', '', '+01:00', '+01:00', ''),
('GM', '+1328-01639', 'Africa/Banjul', '', '+00:00', '+00:00', ''),
('GW', '+1151-01535', 'Africa/Bissau', '', '+00:00', '+00:00', ''),
('MW', '-1547+03500', 'Africa/Blantyre', '', '+02:00', '+02:00', ''),
('CG', '-0416+01517', 'Africa/Brazzaville', '', '+01:00', '+01:00', ''),
('BI', '-0323+02922', 'Africa/Bujumbura', '', '+02:00', '+02:00', ''),
('EG', '+3003+03115', 'Africa/Cairo', '', '+02:00', '+02:00', 'DST has been canceled since 2011'),
('MA', '+3339-00735', 'Africa/Casablanca', '', '+00:00', '+01:00', ''),
('ES', '+3553-00519', 'Africa/Ceuta', 'Ceuta & Melilla', '+01:00', '+02:00', ''),
('GN', '+0931-01343', 'Africa/Conakry', '', '+00:00', '+00:00', ''),
('SN', '+1440-01726', 'Africa/Dakar', '', '+00:00', '+00:00', ''),
('TZ', '-0648+03917', 'Africa/Dar_es_Salaam', '', '+03:00', '+03:00', ''),
('DJ', '+1136+04309', 'Africa/Djibouti', '', '+03:00', '+03:00', ''),
('CM', '+0403+00942', 'Africa/Douala', '', '+01:00', '+01:00', ''),
('EH', '+2709-01312', 'Africa/El_Aaiun', '', '+00:00', '+00:00', ''),
('SL', '+0830-01315', 'Africa/Freetown', '', '+00:00', '+00:00', ''),
('BW', '-2439+02555', 'Africa/Gaborone', '', '+02:00', '+02:00', ''),
('ZW', '-1750+03103', 'Africa/Harare', '', '+02:00', '+02:00', ''),
('ZA', '-2615+02800', 'Africa/Johannesburg', '', '+02:00', '+02:00', ''),
('SS', '+0451+03136', 'Africa/Juba', '', '+03:00', '+03:00', ''),
('UG', '+0019+03225', 'Africa/Kampala', '', '+03:00', '+03:00', ''),
('SD', '+1536+03232', 'Africa/Khartoum', '', '+03:00', '+03:00', ''),
('RW', '-0157+03004', 'Africa/Kigali', '', '+02:00', '+02:00', ''),
('CD', '-0418+01518', 'Africa/Kinshasa', 'west Dem. Rep. of Congo', '+01:00', '+01:00', ''),
('NG', '+0627+00324', 'Africa/Lagos', '', '+01:00', '+01:00', ''),
('GA', '+0023+00927', 'Africa/Libreville', '', '+01:00', '+01:00', ''),
('TG', '+0608+00113', 'Africa/Lome', '', '+00:00', '+00:00', ''),
('AO', '-0848+01314', 'Africa/Luanda', '', '+01:00', '+01:00', ''),
('CD', '-1140+02728', 'Africa/Lubumbashi', 'east Dem. Rep. of Congo', '+02:00', '+02:00', ''),
('ZM', '-1525+02817', 'Africa/Lusaka', '', '+02:00', '+02:00', ''),
('GQ', '+0345+00847', 'Africa/Malabo', '', '+01:00', '+01:00', ''),
('MZ', '-2558+03235', 'Africa/Maputo', '', '+02:00', '+02:00', ''),
('LS', '-2928+02730', 'Africa/Maseru', '', '+02:00', '+02:00', ''),
('SZ', '-2618+03106', 'Africa/Mbabane', '', '+02:00', '+02:00', ''),
('SO', '+0204+04522', 'Africa/Mogadishu', '', '+03:00', '+03:00', ''),
('LR', '+0618-01047', 'Africa/Monrovia', '', '+00:00', '+00:00', ''),
('KE', '-0117+03649', 'Africa/Nairobi', '', '+03:00', '+03:00', ''),
('TD', '+1207+01503', 'Africa/Ndjamena', '', '+01:00', '+01:00', ''),
('NE', '+1331+00207', 'Africa/Niamey', '', '+01:00', '+01:00', ''),
('MR', '+1806-01557', 'Africa/Nouakchott', '', '+00:00', '+00:00', ''),
('BF', '+1222-00131', 'Africa/Ouagadougou', '', '+00:00', '+00:00', ''),
('BJ', '+0629+00237', 'Africa/Porto-Novo', '', '+01:00', '+01:00', ''),
('ST', '+0020+00644', 'Africa/Sao_Tome', '', '+00:00', '+00:00', ''),
('', '', 'Africa/Timbuktu', '', '+00:00', '+00:00', 'Link to Africa/Bamako'),
('LY', '+3254+01311', 'Africa/Tripoli', '', '+01:00', '+02:00', ''),
('TN', '+3648+01011', 'Africa/Tunis', '', '+01:00', '+01:00', ''),
('NA', '-2234+01706', 'Africa/Windhoek', '', '+01:00', '+02:00', ''),
('', '', 'AKST9AKDT', '', '−09:00', '−08:00', 'Link to America/Anchorage'),
('US', '+515248-1763929', 'America/Adak', 'Aleutian Islands', '−10:00', '−09:00', ''),
('US', '+611305-1495401', 'America/Anchorage', 'Alaska Time', '−09:00', '−08:00', ''),
('AI', '+1812-06304', 'America/Anguilla', '', '−04:00', '−04:00', ''),
('AG', '+1703-06148', 'America/Antigua', '', '−04:00', '−04:00', ''),
('BR', '-0712-04812', 'America/Araguaina', 'Tocantins', '−03:00', '−03:00', ''),
('AR', '-3436-05827', 'America/Argentina/Buenos_Aires', 'Buenos Aires (BA, CF)', '−03:00', '−03:00', ''),
('AR', '-2828-06547', 'America/Argentina/Catamarca', 'Catamarca (CT), Chubut (CH)', '−03:00', '−03:00', ''),
('', '', 'America/Argentina/ComodRivadavia', '', '−03:00', '−03:00', 'Link to America/Argentina/Catamarca'),
('AR', '-3124-06411', 'America/Argentina/Cordoba', 'most locations (CB, CC, CN, ER, FM, MN, SE, SF)', '−03:00', '−03:00', ''),
('AR', '-2411-06518', 'America/Argentina/Jujuy', 'Jujuy (JY)', '−03:00', '−03:00', ''),
('AR', '-2926-06651', 'America/Argentina/La_Rioja', 'La Rioja (LR)', '−03:00', '−03:00', ''),
('AR', '-3253-06849', 'America/Argentina/Mendoza', 'Mendoza (MZ)', '−03:00', '−03:00', ''),
('AR', '-5138-06913', 'America/Argentina/Rio_Gallegos', 'Santa Cruz (SC)', '−03:00', '−03:00', ''),
('AR', '-2447-06525', 'America/Argentina/Salta', '(SA, LP, NQ, RN)', '−03:00', '−03:00', ''),
('AR', '-3132-06831', 'America/Argentina/San_Juan', 'San Juan (SJ)', '−03:00', '−03:00', ''),
('AR', '-3319-06621', 'America/Argentina/San_Luis', 'San Luis (SL)', '−03:00', '−03:00', ''),
('AR', '-2649-06513', 'America/Argentina/Tucuman', 'Tucuman (TM)', '−03:00', '−03:00', ''),
('AR', '-5448-06818', 'America/Argentina/Ushuaia', 'Tierra del Fuego (TF)', '−03:00', '−03:00', ''),
('AW', '+1230-06958', 'America/Aruba', '', '−04:00', '−04:00', ''),
('PY', '-2516-05740', 'America/Asuncion', '', '−04:00', '−03:00', ''),
('CA', '+484531-0913718', 'America/Atikokan', 'Eastern Standard Time - Atikokan, Ontario and Southampton I, Nunavut', '−05:00', '−05:00', ''),
('', '', 'America/Atka', '', '−10:00', '−09:00', 'Link to America/Adak'),
('BR', '-1259-03831', 'America/Bahia', 'Bahia', '−03:00', '−03:00', ''),
('MX', '+2048-10515', 'America/Bahia_Banderas', 'Mexican Central Time - Bahia de Banderas', '−06:00', '−05:00', ''),
('BB', '+1306-05937', 'America/Barbados', '', '−04:00', '−04:00', ''),
('BR', '-0127-04829', 'America/Belem', 'Amapa, E Para', '−03:00', '−03:00', ''),
('BZ', '+1730-08812', 'America/Belize', '', '−06:00', '−06:00', ''),
('CA', '+5125-05707', 'America/Blanc-Sablon', 'Atlantic Standard Time - Quebec - Lower North Shore', '−04:00', '−04:00', ''),
('BR', '+0249-06040', 'America/Boa_Vista', 'Roraima', '−04:00', '−04:00', ''),
('CO', '+0436-07405', 'America/Bogota', '', '−05:00', '−05:00', ''),
('US', '+433649-1161209', 'America/Boise', 'Mountain Time - south Idaho & east Oregon', '−07:00', '−06:00', ''),
('', '', 'America/Buenos_Aires', '', '−03:00', '−03:00', 'Link to America/Argentina/Buenos_Aires'),
('CA', '+690650-1050310', 'America/Cambridge_Bay', 'Mountain Time - west Nunavut', '−07:00', '−06:00', ''),
('BR', '-2027-05437', 'America/Campo_Grande', 'Mato Grosso do Sul', '−04:00', '−03:00', ''),
('MX', '+2105-08646', 'America/Cancun', 'Central Time - Quintana Roo', '−06:00', '−05:00', ''),
('VE', '+1030-06656', 'America/Caracas', '', '−04:30', '−04:30', ''),
('', '', 'America/Catamarca', '', '−03:00', '−03:00', 'Link to America/Argentina/Catamarca'),
('GF', '+0456-05220', 'America/Cayenne', '', '−03:00', '−03:00', ''),
('KY', '+1918-08123', 'America/Cayman', '', '−05:00', '−05:00', ''),
('US', '+415100-0873900', 'America/Chicago', 'Central Time', '−06:00', '−05:00', ''),
('MX', '+2838-10605', 'America/Chihuahua', 'Mexican Mountain Time - Chihuahua away from US border', '−07:00', '−06:00', ''),
('', '', 'America/Coral_Harbour', '', '−05:00', '−05:00', 'Link to America/Atikokan'),
('', '', 'America/Cordoba', '', '−03:00', '−03:00', 'Link to America/Argentina/Cordoba'),
('CR', '+0956-08405', 'America/Costa_Rica', '', '−06:00', '−06:00', ''),
('CA', '+4906-11631', 'America/Creston', 'Mountain Standard Time - Creston, British Columbia', '−07:00', '−07:00', ''),
('BR', '-1535-05605', 'America/Cuiaba', 'Mato Grosso', '−04:00', '−03:00', ''),
('CW', '+1211-06900', 'America/Curacao', '', '−04:00', '−04:00', ''),
('GL', '+7646-01840', 'America/Danmarkshavn', 'east coast, north of Scoresbysund', '+00:00', '+00:00', ''),
('CA', '+6404-13925', 'America/Dawson', 'Pacific Time - north Yukon', '−08:00', '−07:00', ''),
('CA', '+5946-12014', 'America/Dawson_Creek', 'Mountain Standard Time - Dawson Creek & Fort Saint John, British Columbia', '−07:00', '−07:00', ''),
('US', '+394421-1045903', 'America/Denver', 'Mountain Time', '−07:00', '−06:00', ''),
('US', '+421953-0830245', 'America/Detroit', 'Eastern Time - Michigan - most locations', '−05:00', '−04:00', ''),
('DM', '+1518-06124', 'America/Dominica', '', '−04:00', '−04:00', ''),
('CA', '+5333-11328', 'America/Edmonton', 'Mountain Time - Alberta, east British Columbia & west Saskatchewan', '−07:00', '−06:00', ''),
('BR', '-0640-06952', 'America/Eirunepe', 'W Amazonas', '−04:00', '−04:00', ''),
('SV', '+1342-08912', 'America/El_Salvador', '', '−06:00', '−06:00', ''),
('', '', 'America/Ensenada', '', '−08:00', '−07:00', 'Link to America/Tijuana'),
('BR', '-0343-03830', 'America/Fortaleza', 'NE Brazil (MA, PI, CE, RN, PB)', '−03:00', '−03:00', ''),
('', '', 'America/Fort_Wayne', '', '−05:00', '−04:00', 'Link to America/Indiana/Indianapolis'),
('CA', '+4612-05957', 'America/Glace_Bay', 'Atlantic Time - Nova Scotia - places that did not observe DST 1966-1971', '−04:00', '−03:00', ''),
('GL', '+6411-05144', 'America/Godthab', 'most locations', '−03:00', '−02:00', ''),
('CA', '+5320-06025', 'America/Goose_Bay', 'Atlantic Time - Labrador - most locations', '−04:00', '−03:00', ''),
('TC', '+2128-07108', 'America/Grand_Turk', '', '−05:00', '−04:00', ''),
('GD', '+1203-06145', 'America/Grenada', '', '−04:00', '−04:00', ''),
('GP', '+1614-06132', 'America/Guadeloupe', '', '−04:00', '−04:00', ''),
('GT', '+1438-09031', 'America/Guatemala', '', '−06:00', '−06:00', ''),
('EC', '-0210-07950', 'America/Guayaquil', 'mainland', '−05:00', '−05:00', ''),
('GY', '+0648-05810', 'America/Guyana', '', '−04:00', '−04:00', ''),
('CA', '+4439-06336', 'America/Halifax', 'Atlantic Time - Nova Scotia (most places), PEI', '−04:00', '−03:00', ''),
('CU', '+2308-08222', 'America/Havana', '', '−05:00', '−04:00', ''),
('MX', '+2904-11058', 'America/Hermosillo', 'Mountain Standard Time - Sonora', '−07:00', '−07:00', ''),
('US', '+394606-0860929', 'America/Indiana/Indianapolis', 'Eastern Time - Indiana - most locations', '−05:00', '−04:00', ''),
('US', '+411745-0863730', 'America/Indiana/Knox', 'Central Time - Indiana - Starke County', '−06:00', '−05:00', ''),
('US', '+382232-0862041', 'America/Indiana/Marengo', 'Eastern Time - Indiana - Crawford County', '−05:00', '−04:00', ''),
('US', '+382931-0871643', 'America/Indiana/Petersburg', 'Eastern Time - Indiana - Pike County', '−05:00', '−04:00', ''),
('US', '+375711-0864541', 'America/Indiana/Tell_City', 'Central Time - Indiana - Perry County', '−06:00', '−05:00', ''),
('US', '+384452-0850402', 'America/Indiana/Vevay', 'Eastern Time - Indiana - Switzerland County', '−05:00', '−04:00', ''),
('US', '+384038-0873143', 'America/Indiana/Vincennes', 'Eastern Time - Indiana - Daviess, Dubois, Knox & Martin Counties', '−05:00', '−04:00', ''),
('US', '+410305-0863611', 'America/Indiana/Winamac', 'Eastern Time - Indiana - Pulaski County', '−05:00', '−04:00', ''),
('', '', 'America/Indianapolis', '', '−05:00', '−04:00', 'Link to America/Indiana/Indianapolis'),
('CA', '+682059-1334300', 'America/Inuvik', 'Mountain Time - west Northwest Territories', '−07:00', '−06:00', ''),
('CA', '+6344-06828', 'America/Iqaluit', 'Eastern Time - east Nunavut - most locations', '−05:00', '−04:00', ''),
('JM', '+1800-07648', 'America/Jamaica', '', '−05:00', '−05:00', ''),
('', '', 'America/Jujuy', '', '−03:00', '−03:00', 'Link to America/Argentina/Jujuy'),
('US', '+581807-1342511', 'America/Juneau', 'Alaska Time - Alaska panhandle', '−09:00', '−08:00', ''),
('US', '+381515-0854534', 'America/Kentucky/Louisville', 'Eastern Time - Kentucky - Louisville area', '−05:00', '−04:00', ''),
('US', '+364947-0845057', 'America/Kentucky/Monticello', 'Eastern Time - Kentucky - Wayne County', '−05:00', '−04:00', ''),
('', '', 'America/Knox_IN', '', '−06:00', '−05:00', 'Link to America/Indiana/Knox'),
('BQ', '+120903-0681636', 'America/Kralendijk', '', '−04:00', '−04:00', 'Link to America/Curacao'),
('BO', '-1630-06809', 'America/La_Paz', '', '−04:00', '−04:00', ''),
('PE', '-1203-07703', 'America/Lima', '', '−05:00', '−05:00', ''),
('US', '+340308-1181434', 'America/Los_Angeles', 'Pacific Time', '−08:00', '−07:00', ''),
('', '', 'America/Louisville', '', '−05:00', '−04:00', 'Link to America/Kentucky/Louisville'),
('SX', '+180305-0630250', 'America/Lower_Princes', '', '−04:00', '−04:00', 'Link to America/Curacao'),
('BR', '-0940-03543', 'America/Maceio', 'Alagoas, Sergipe', '−03:00', '−03:00', ''),
('NI', '+1209-08617', 'America/Managua', '', '−06:00', '−06:00', ''),
('BR', '-0308-06001', 'America/Manaus', 'E Amazonas', '−04:00', '−04:00', ''),
('MF', '+1804-06305', 'America/Marigot', '', '−04:00', '−04:00', 'Link to America/Guadeloupe'),
('MQ', '+1436-06105', 'America/Martinique', '', '−04:00', '−04:00', ''),
('MX', '+2550-09730', 'America/Matamoros', 'US Central Time - Coahuila, Durango, Nuevo León, Tamaulipas near US border', '−06:00', '−05:00', ''),
('MX', '+2313-10625', 'America/Mazatlan', 'Mountain Time - S Baja, Nayarit, Sinaloa', '−07:00', '−06:00', ''),
('', '', 'America/Mendoza', '', '−03:00', '−03:00', 'Link to America/Argentina/Mendoza'),
('US', '+450628-0873651', 'America/Menominee', 'Central Time - Michigan - Dickinson, Gogebic, Iron & Menominee Counties', '−06:00', '−05:00', ''),
('MX', '+2058-08937', 'America/Merida', 'Central Time - Campeche, Yucatán', '−06:00', '−05:00', ''),
('US', '+550737-1313435', 'America/Metlakatla', 'Metlakatla Time - Annette Island', '−08:00', '−08:00', ''),
('MX', '+1924-09909', 'America/Mexico_City', 'Central Time - most locations', '−06:00', '−05:00', ''),
('PM', '+4703-05620', 'America/Miquelon', '', '−03:00', '−02:00', ''),
('CA', '+4606-06447', 'America/Moncton', 'Atlantic Time - New Brunswick', '−04:00', '−03:00', ''),
('MX', '+2540-10019', 'America/Monterrey', 'Mexican Central Time - Coahuila, Durango, Nuevo León, Tamaulipas away from US border', '−06:00', '−05:00', ''),
('UY', '-3453-05611', 'America/Montevideo', '', '−03:00', '−02:00', ''),
('CA', '+4531-07334', 'America/Montreal', 'Eastern Time - Quebec - most locations', '−05:00', '−04:00', ''),
('MS', '+1643-06213', 'America/Montserrat', '', '−04:00', '−04:00', ''),
('BS', '+2505-07721', 'America/Nassau', '', '−05:00', '−04:00', ''),
('US', '+404251-0740023', 'America/New_York', 'Eastern Time', '−05:00', '−04:00', ''),
('CA', '+4901-08816', 'America/Nipigon', 'Eastern Time - Ontario & Quebec - places that did not observe DST 1967-1973', '−05:00', '−04:00', ''),
('US', '+643004-1652423', 'America/Nome', 'Alaska Time - west Alaska', '−09:00', '−08:00', ''),
('BR', '-0351-03225', 'America/Noronha', 'Atlantic islands', '−02:00', '−02:00', ''),
('US', '+471551-1014640', 'America/North_Dakota/Beulah', 'Central Time - North Dakota - Mercer County', '−06:00', '−05:00', ''),
('US', '+470659-1011757', 'America/North_Dakota/Center', 'Central Time - North Dakota - Oliver County', '−06:00', '−05:00', ''),
('US', '+465042-1012439', 'America/North_Dakota/New_Salem', 'Central Time - North Dakota - Morton County (except Mandan area)', '−06:00', '−05:00', ''),
('MX', '+2934-10425', 'America/Ojinaga', 'US Mountain Time - Chihuahua near US border', '−07:00', '−06:00', ''),
('PA', '+0858-07932', 'America/Panama', '', '−05:00', '−05:00', ''),
('CA', '+6608-06544', 'America/Pangnirtung', 'Eastern Time - Pangnirtung, Nunavut', '−05:00', '−04:00', ''),
('SR', '+0550-05510', 'America/Paramaribo', '', '−03:00', '−03:00', ''),
('US', '+332654-1120424', 'America/Phoenix', 'Mountain Standard Time - Arizona', '−07:00', '−07:00', ''),
('HT', '+1832-07220', 'America/Port-au-Prince', '', '−05:00', '−04:00', ''),
('', '', 'America/Porto_Acre', '', '−04:00', '−04:00', 'Link to America/Rio_Branco'),
('BR', '-0846-06354', 'America/Porto_Velho', 'Rondonia', '−04:00', '−04:00', ''),
('TT', '+1039-06131', 'America/Port_of_Spain', '', '−04:00', '−04:00', ''),
('PR', '+182806-0660622', 'America/Puerto_Rico', '', '−04:00', '−04:00', ''),
('CA', '+4843-09434', 'America/Rainy_River', 'Central Time - Rainy River & Fort Frances, Ontario', '−06:00', '−05:00', ''),
('CA', '+624900-0920459', 'America/Rankin_Inlet', 'Central Time - central Nunavut', '−06:00', '−05:00', ''),
('BR', '-0803-03454', 'America/Recife', 'Pernambuco', '−03:00', '−03:00', ''),
('CA', '+5024-10439', 'America/Regina', 'Central Standard Time - Saskatchewan - most locations', '−06:00', '−06:00', ''),
('CA', '+744144-0944945', 'America/Resolute', 'Central Standard Time - Resolute, Nunavut', '−06:00', '−05:00', ''),
('BR', '-0958-06748', 'America/Rio_Branco', 'Acre', '−04:00', '−04:00', ''),
('', '', 'America/Rosario', '', '−03:00', '−03:00', 'Link to America/Argentina/Cordoba'),
('BR', '-0226-05452', 'America/Santarem', 'W Para', '−03:00', '−03:00', ''),
('MX', '+3018-11452', 'America/Santa_Isabel', 'Mexican Pacific Time - Baja California away from US border', '−08:00', '−07:00', ''),
('CL', '-3327-07040', 'America/Santiago', 'most locations', '−04:00', '−03:00', ''),
('DO', '+1828-06954', 'America/Santo_Domingo', '', '−04:00', '−04:00', ''),
('BR', '-2332-04637', 'America/Sao_Paulo', 'S & SE Brazil (GO, DF, MG, ES, RJ, SP, PR, SC, RS)', '−03:00', '−02:00', ''),
('GL', '+7029-02158', 'America/Scoresbysund', 'Scoresbysund / Ittoqqortoormiit', '−01:00', '+00:00', ''),
('US', '+364708-1084111', 'America/Shiprock', 'Mountain Time - Navajo', '−07:00', '−06:00', 'Link to America/Denver'),
('US', '+571035-1351807', 'America/Sitka', 'Alaska Time - southeast Alaska panhandle', '−09:00', '−08:00', ''),
('BL', '+1753-06251', 'America/St_Barthelemy', '', '−04:00', '−04:00', 'Link to America/Guadeloupe'),
('CA', '+4734-05243', 'America/St_Johns', 'Newfoundland Time, including SE Labrador', '−03:30', '−02:30', ''),
('KN', '+1718-06243', 'America/St_Kitts', '', '−04:00', '−04:00', ''),
('LC', '+1401-06100', 'America/St_Lucia', '', '−04:00', '−04:00', ''),
('VI', '+1821-06456', 'America/St_Thomas', '', '−04:00', '−04:00', ''),
('VC', '+1309-06114', 'America/St_Vincent', '', '−04:00', '−04:00', ''),
('CA', '+5017-10750', 'America/Swift_Current', 'Central Standard Time - Saskatchewan - midwest', '−06:00', '−06:00', ''),
('HN', '+1406-08713', 'America/Tegucigalpa', '', '−06:00', '−06:00', ''),
('GL', '+7634-06847', 'America/Thule', 'Thule / Pituffik', '−04:00', '−03:00', ''),
('CA', '+4823-08915', 'America/Thunder_Bay', 'Eastern Time - Thunder Bay, Ontario', '−05:00', '−04:00', ''),
('MX', '+3232-11701', 'America/Tijuana', 'US Pacific Time - Baja California near US border', '−08:00', '−07:00', ''),
('CA', '+4339-07923', 'America/Toronto', 'Eastern Time - Ontario - most locations', '−05:00', '−04:00', ''),
('VG', '+1827-06437', 'America/Tortola', '', '−04:00', '−04:00', ''),
('CA', '+4916-12307', 'America/Vancouver', 'Pacific Time - west British Columbia', '−08:00', '−07:00', ''),
('', '', 'America/Virgin', '', '−04:00', '−04:00', 'Link to America/St_Thomas'),
('CA', '+6043-13503', 'America/Whitehorse', 'Pacific Time - south Yukon', '−08:00', '−07:00', ''),
('CA', '+4953-09709', 'America/Winnipeg', 'Central Time - Manitoba & west Ontario', '−06:00', '−05:00', ''),
('US', '+593249-1394338', 'America/Yakutat', 'Alaska Time - Alaska panhandle neck', '−09:00', '−08:00', ''),
('CA', '+6227-11421', 'America/Yellowknife', 'Mountain Time - central Northwest Territories', '−07:00', '−06:00', ''),
('AQ', '-6617+11031', 'Antarctica/Casey', 'Casey Station, Bailey Peninsula', '+11:00', '+08:00', ''),
('AQ', '-6835+07758', 'Antarctica/Davis', 'Davis Station, Vestfold Hills', '+05:00', '+07:00', ''),
('AQ', '-6640+14001', 'Antarctica/DumontDUrville', 'Dumont-d\'Urville Station, Terre Adelie', '+10:00', '+10:00', ''),
('AQ', '-5430+15857', 'Antarctica/Macquarie', 'Macquarie Island Station, Macquarie Island', '+11:00', '+11:00', ''),
('AQ', '-6736+06253', 'Antarctica/Mawson', 'Mawson Station, Holme Bay', '+05:00', '+05:00', ''),
('AQ', '-7750+16636', 'Antarctica/McMurdo', 'McMurdo Station, Ross Island', '+12:00', '+13:00', ''),
('AQ', '-6448-06406', 'Antarctica/Palmer', 'Palmer Station, Anvers Island', '−04:00', '−03:00', ''),
('AQ', '-6734-06808', 'Antarctica/Rothera', 'Rothera Station, Adelaide Island', '−03:00', '−03:00', ''),
('AQ', '-9000+00000', 'Antarctica/South_Pole', 'Amundsen-Scott Station, South Pole', '+12:00', '+13:00', 'Link to Antarctica/McMurdo'),
('AQ', '-690022+0393524', 'Antarctica/Syowa', 'Syowa Station, E Ongul I', '+03:00', '+03:00', ''),
('AQ', '-7824+10654', 'Antarctica/Vostok', 'Vostok Station, Lake Vostok', '+06:00', '+06:00', ''),
('SJ', '+7800+01600', 'Arctic/Longyearbyen', '', '+01:00', '+02:00', 'Link to Europe/Oslo'),
('YE', '+1245+04512', 'Asia/Aden', '', '+03:00', '+03:00', ''),
('KZ', '+4315+07657', 'Asia/Almaty', 'most locations', '+06:00', '+06:00', ''),
('JO', '+3157+03556', 'Asia/Amman', '', '+03:00', '+03:00', ''),
('RU', '+6445+17729', 'Asia/Anadyr', 'Moscow+08 - Bering Sea', '+12:00', '+12:00', ''),
('KZ', '+4431+05016', 'Asia/Aqtau', 'Atyrau (Atirau, Gur\'yev), Mangghystau (Mankistau)', '+05:00', '+05:00', ''),
('KZ', '+5017+05710', 'Asia/Aqtobe', 'Aqtobe (Aktobe)', '+05:00', '+05:00', ''),
('TM', '+3757+05823', 'Asia/Ashgabat', '', '+05:00', '+05:00', ''),
('', '', 'Asia/Ashkhabad', '', '+05:00', '+05:00', 'Link to Asia/Ashgabat'),
('IQ', '+3321+04425', 'Asia/Baghdad', '', '+03:00', '+03:00', ''),
('BH', '+2623+05035', 'Asia/Bahrain', '', '+03:00', '+03:00', ''),
('AZ', '+4023+04951', 'Asia/Baku', '', '+04:00', '+05:00', ''),
('TH', '+1345+10031', 'Asia/Bangkok', '', '+07:00', '+07:00', ''),
('LB', '+3353+03530', 'Asia/Beirut', '', '+02:00', '+03:00', ''),
('KG', '+4254+07436', 'Asia/Bishkek', '', '+06:00', '+06:00', ''),
('BN', '+0456+11455', 'Asia/Brunei', '', '+08:00', '+08:00', ''),
('', '', 'Asia/Calcutta', '', '+05:30', '+05:30', 'Link to Asia/Kolkata'),
('MN', '+4804+11430', 'Asia/Choibalsan', 'Dornod, Sukhbaatar', '+08:00', '+08:00', ''),
('CN', '+2934+10635', 'Asia/Chongqing', 'central China - Sichuan, Yunnan, Guangxi, Shaanxi, Guizhou, etc.', '+08:00', '+08:00', 'Covering historic Kansu-Szechuan time zone.'),
('', '', 'Asia/Chungking', '', '+08:00', '+08:00', 'Link to Asia/Chongqing'),
('LK', '+0656+07951', 'Asia/Colombo', '', '+05:30', '+05:30', ''),
('', '', 'Asia/Dacca', '', '+06:00', '+06:00', 'Link to Asia/Dhaka'),
('SY', '+3330+03618', 'Asia/Damascus', '', '+02:00', '+03:00', ''),
('BD', '+2343+09025', 'Asia/Dhaka', '', '+06:00', '+06:00', ''),
('TL', '-0833+12535', 'Asia/Dili', '', '+09:00', '+09:00', ''),
('AE', '+2518+05518', 'Asia/Dubai', '', '+04:00', '+04:00', ''),
('TJ', '+3835+06848', 'Asia/Dushanbe', '', '+05:00', '+05:00', ''),
('PS', '+3130+03428', 'Asia/Gaza', 'Gaza Strip', '+02:00', '+03:00', ''),
('CN', '+4545+12641', 'Asia/Harbin', 'Heilongjiang (except Mohe), Jilin', '+08:00', '+08:00', 'Covering historic Changpai time zone.'),
('PS', '+313200+0350542', 'Asia/Hebron', 'West Bank', '+02:00', '+03:00', ''),
('HK', '+2217+11409', 'Asia/Hong_Kong', '', '+08:00', '+08:00', ''),
('MN', '+4801+09139', 'Asia/Hovd', 'Bayan-Olgiy, Govi-Altai, Hovd, Uvs, Zavkhan', '+07:00', '+07:00', ''),
('VN', '+1045+10640', 'Asia/Ho_Chi_Minh', '', '+07:00', '+07:00', ''),
('RU', '+5216+10420', 'Asia/Irkutsk', 'Moscow+05 - Lake Baikal', '+09:00', '+09:00', ''),
('', '', 'Asia/Istanbul', '', '+02:00', '+03:00', 'Link to Europe/Istanbul'),
('ID', '-0610+10648', 'Asia/Jakarta', 'Java & Sumatra', '+07:00', '+07:00', ''),
('ID', '-0232+14042', 'Asia/Jayapura', 'west New Guinea (Irian Jaya) & Malukus (Moluccas)', '+09:00', '+09:00', ''),
('IL', '+3146+03514', 'Asia/Jerusalem', '', '+02:00', '+03:00', ''),
('AF', '+3431+06912', 'Asia/Kabul', '', '+04:30', '+04:30', ''),
('RU', '+5301+15839', 'Asia/Kamchatka', 'Moscow+08 - Kamchatka', '+12:00', '+12:00', ''),
('PK', '+2452+06703', 'Asia/Karachi', '', '+05:00', '+05:00', ''),
('CN', '+3929+07559', 'Asia/Kashgar', 'west Tibet & Xinjiang', '+08:00', '+08:00', 'Covering historic Kunlun time zone.'),
('NP', '+2743+08519', 'Asia/Kathmandu', '', '+05:45', '+05:45', ''),
('', '', 'Asia/Katmandu', '', '+05:45', '+05:45', 'Link to Asia/Kathmandu'),
('IN', '+2232+08822', 'Asia/Kolkata', '', '+05:30', '+05:30', 'Note: Different zones in history, see Time in India.'),
('RU', '+5601+09250', 'Asia/Krasnoyarsk', 'Moscow+04 - Yenisei River', '+08:00', '+08:00', ''),
('MY', '+0310+10142', 'Asia/Kuala_Lumpur', 'peninsular Malaysia', '+08:00', '+08:00', ''),
('MY', '+0133+11020', 'Asia/Kuching', 'Sabah & Sarawak', '+08:00', '+08:00', ''),
('KW', '+2920+04759', 'Asia/Kuwait', '', '+03:00', '+03:00', ''),
('', '', 'Asia/Macao', '', '+08:00', '+08:00', 'Link to Asia/Macau'),
('MO', '+2214+11335', 'Asia/Macau', '', '+08:00', '+08:00', ''),
('RU', '+5934+15048', 'Asia/Magadan', 'Moscow+08 - Magadan', '+12:00', '+12:00', ''),
('ID', '-0507+11924', 'Asia/Makassar', 'east & south Borneo, Sulawesi (Celebes), Bali, Nusa Tenggara, west Timor', '+08:00', '+08:00', ''),
('PH', '+1435+12100', 'Asia/Manila', '', '+08:00', '+08:00', ''),
('OM', '+2336+05835', 'Asia/Muscat', '', '+04:00', '+04:00', ''),
('CY', '+3510+03322', 'Asia/Nicosia', '', '+02:00', '+03:00', ''),
('RU', '+5345+08707', 'Asia/Novokuznetsk', 'Moscow+03 - Novokuznetsk', '+07:00', '+07:00', ''),
('RU', '+5502+08255', 'Asia/Novosibirsk', 'Moscow+03 - Novosibirsk', '+07:00', '+07:00', ''),
('RU', '+5500+07324', 'Asia/Omsk', 'Moscow+03 - west Siberia', '+07:00', '+07:00', ''),
('KZ', '+5113+05121', 'Asia/Oral', 'West Kazakhstan', '+05:00', '+05:00', ''),
('KH', '+1133+10455', 'Asia/Phnom_Penh', '', '+07:00', '+07:00', ''),
('ID', '-0002+10920', 'Asia/Pontianak', 'west & central Borneo', '+07:00', '+07:00', ''),
('KP', '+3901+12545', 'Asia/Pyongyang', '', '+09:00', '+09:00', ''),
('QA', '+2517+05132', 'Asia/Qatar', '', '+03:00', '+03:00', ''),
('KZ', '+4448+06528', 'Asia/Qyzylorda', 'Qyzylorda (Kyzylorda, Kzyl-Orda)', '+06:00', '+06:00', ''),
('MM', '+1647+09610', 'Asia/Rangoon', '', '+06:30', '+06:30', ''),
('SA', '+2438+04643', 'Asia/Riyadh', '', '+03:00', '+03:00', ''),
('', '', 'Asia/Saigon', '', '+07:00', '+07:00', 'Link to Asia/Ho_Chi_Minh'),
('RU', '+4658+14242', 'Asia/Sakhalin', 'Moscow+07 - Sakhalin Island', '+11:00', '+11:00', ''),
('UZ', '+3940+06648', 'Asia/Samarkand', 'west Uzbekistan', '+05:00', '+05:00', ''),
('KR', '+3733+12658', 'Asia/Seoul', '', '+09:00', '+09:00', ''),
('CN', '+3114+12128', 'Asia/Shanghai', 'east China - Beijing, Guangdong, Shanghai, etc.', '+08:00', '+08:00', 'Covering historic Chungyuan time zone.'),
('SG', '+0117+10351', 'Asia/Singapore', '', '+08:00', '+08:00', ''),
('TW', '+2503+12130', 'Asia/Taipei', '', '+08:00', '+08:00', ''),
('UZ', '+4120+06918', 'Asia/Tashkent', 'east Uzbekistan', '+05:00', '+05:00', ''),
('GE', '+4143+04449', 'Asia/Tbilisi', '', '+04:00', '+04:00', ''),
('IR', '+3540+05126', 'Asia/Tehran', '', '+03:30', '+04:30', ''),
('', '', 'Asia/Tel_Aviv', '', '+02:00', '+03:00', 'Link to Asia/Jerusalem'),
('', '', 'Asia/Thimbu', '', '+06:00', '+06:00', 'Link to Asia/Thimphu'),
('BT', '+2728+08939', 'Asia/Thimphu', '', '+06:00', '+06:00', ''),
('JP', '+353916+1394441', 'Asia/Tokyo', '', '+09:00', '+09:00', ''),
('', '', 'Asia/Ujung_Pandang', '', '+08:00', '+08:00', 'Link to Asia/Makassar'),
('MN', '+4755+10653', 'Asia/Ulaanbaatar', 'most locations', '+08:00', '+08:00', ''),
('', '', 'Asia/Ulan_Bator', '', '+08:00', '+08:00', 'Link to Asia/Ulaanbaatar'),
('CN', '+4348+08735', 'Asia/Urumqi', 'most of Tibet & Xinjiang', '+08:00', '+08:00', 'Covering historic Sinkiang-Tibet time zone.'),
('LA', '+1758+10236', 'Asia/Vientiane', '', '+07:00', '+07:00', ''),
('RU', '+4310+13156', 'Asia/Vladivostok', 'Moscow+07 - Amur River', '+11:00', '+11:00', ''),
('RU', '+6200+12940', 'Asia/Yakutsk', 'Moscow+06 - Lena River', '+10:00', '+10:00', ''),
('RU', '+5651+06036', 'Asia/Yekaterinburg', 'Moscow+02 - Urals', '+06:00', '+06:00', ''),
('AM', '+4011+04430', 'Asia/Yerevan', '', '+04:00', '+04:00', ''),
('PT', '+3744-02540', 'Atlantic/Azores', 'Azores', '−01:00', '+00:00', ''),
('BM', '+3217-06446', 'Atlantic/Bermuda', '', '−04:00', '−03:00', ''),
('ES', '+2806-01524', 'Atlantic/Canary', 'Canary Islands', '+00:00', '+01:00', ''),
('CV', '+1455-02331', 'Atlantic/Cape_Verde', '', '−01:00', '−01:00', ''),
('', '', 'Atlantic/Faeroe', '', '+00:00', '+01:00', 'Link to Atlantic/Faroe'),
('FO', '+6201-00646', 'Atlantic/Faroe', '', '+00:00', '+01:00', ''),
('', '', 'Atlantic/Jan_Mayen', '', '+01:00', '+02:00', 'Link to Europe/Oslo'),
('PT', '+3238-01654', 'Atlantic/Madeira', 'Madeira Islands', '+00:00', '+01:00', ''),
('IS', '+6409-02151', 'Atlantic/Reykjavik', '', '+00:00', '+00:00', ''),
('GS', '-5416-03632', 'Atlantic/South_Georgia', '', '−02:00', '−02:00', ''),
('FK', '-5142-05751', 'Atlantic/Stanley', '', '−03:00', '−03:00', ''),
('SH', '-1555-00542', 'Atlantic/St_Helena', '', '+00:00', '+00:00', ''),
('', '', 'Australia/ACT', '', '+10:00', '+11:00', 'Link to Australia/Sydney'),
('AU', '-3455+13835', 'Australia/Adelaide', 'South Australia', '+09:30', '+10:30', ''),
('AU', '-2728+15302', 'Australia/Brisbane', 'Queensland - most locations', '+10:00', '+10:00', ''),
('AU', '-3157+14127', 'Australia/Broken_Hill', 'New South Wales - Yancowinna', '+09:30', '+10:30', ''),
('', '', 'Australia/Canberra', '', '+10:00', '+11:00', 'Link to Australia/Sydney'),
('AU', '-3956+14352', 'Australia/Currie', 'Tasmania - King Island', '+10:00', '+11:00', ''),
('AU', '-1228+13050', 'Australia/Darwin', 'Northern Territory', '+09:30', '+09:30', ''),
('AU', '-3143+12852', 'Australia/Eucla', 'Western Australia - Eucla area', '+08:45', '+08:45', ''),
('AU', '-4253+14719', 'Australia/Hobart', 'Tasmania - most locations', '+10:00', '+11:00', ''),
('', '', 'Australia/LHI', '', '+10:30', '+11:00', 'Link to Australia/Lord_Howe'),
('AU', '-2016+14900', 'Australia/Lindeman', 'Queensland - Holiday Islands', '+10:00', '+10:00', ''),
('AU', '-3133+15905', 'Australia/Lord_Howe', 'Lord Howe Island', '+10:30', '+11:00', ''),
('AU', '-3749+14458', 'Australia/Melbourne', 'Victoria', '+10:00', '+11:00', ''),
('', '', 'Australia/North', '', '+09:30', '+09:30', 'Link to Australia/Darwin'),
('', '', 'Australia/NSW', '', '+10:00', '+11:00', 'Link to Australia/Sydney'),
('AU', '-3157+11551', 'Australia/Perth', 'Western Australia - most locations', '+08:00', '+08:00', ''),
('', '', 'Australia/Queensland', '', '+10:00', '+10:00', 'Link to Australia/Brisbane'),
('', '', 'Australia/South', '', '+09:30', '+10:30', 'Link to Australia/Adelaide'),
('AU', '-3352+15113', 'Australia/Sydney', 'New South Wales - most locations', '+10:00', '+11:00', ''),
('', '', 'Australia/Tasmania', '', '+10:00', '+11:00', 'Link to Australia/Hobart'),
('', '', 'Australia/Victoria', '', '+10:00', '+11:00', 'Link to Australia/Melbourne'),
('', '', 'Australia/West', '', '+08:00', '+08:00', 'Link to Australia/Perth'),
('', '', 'Australia/Yancowinna', '', '+09:30', '+10:30', 'Link to Australia/Broken_Hill'),
('', '', 'Brazil/Acre', '', '−04:00', '−04:00', 'Link to America/Rio_Branco'),
('', '', 'Brazil/DeNoronha', '', '−02:00', '−02:00', 'Link to America/Noronha'),
('', '', 'Brazil/East', '', '−03:00', '−02:00', 'Link to America/Sao_Paulo'),
('', '', 'Brazil/West', '', '−04:00', '−04:00', 'Link to America/Manaus'),
('', '', 'Canada/Atlantic', '', '−04:00', '−03:00', 'Link to America/Halifax'),
('', '', 'Canada/Central', '', '−06:00', '−05:00', 'Link to America/Winnipeg'),
('', '', 'Canada/East-Saskatchewan', '', '−06:00', '−06:00', 'Link to America/Regina'),
('', '', 'Canada/Eastern', '', '−05:00', '−04:00', 'Link to America/Toronto'),
('', '', 'Canada/Mountain', '', '−07:00', '−06:00', 'Link to America/Edmonton'),
('', '', 'Canada/Newfoundland', '', '−03:30', '−02:30', 'Link to America/St_Johns'),
('', '', 'Canada/Pacific', '', '−08:00', '−07:00', 'Link to America/Vancouver'),
('', '', 'Canada/Saskatchewan', '', '−06:00', '−06:00', 'Link to America/Regina'),
('', '', 'Canada/Yukon', '', '−08:00', '−07:00', 'Link to America/Whitehorse'),
('', '', 'CET', '', '+01:00', '+02:00', ''),
('', '', 'Chile/Continental', '', '−04:00', '−03:00', 'Link to America/Santiago'),
('', '', 'Chile/EasterIsland', '', '−06:00', '−05:00', 'Link to Pacific/Easter'),
('', '', 'CST6CDT', '', '−06:00', '−05:00', ''),
('', '', 'Cuba', '', '−05:00', '−04:00', 'Link to America/Havana'),
('', '', 'EET', '', '+02:00', '+03:00', ''),
('', '', 'Egypt', '', '+02:00', '+02:00', 'Link to Africa/Cairo'),
('', '', 'Eire', '', '+00:00', '+01:00', 'Link to Europe/Dublin'),
('', '', 'EST', '', '−05:00', '−05:00', ''),
('', '', 'EST5EDT', '', '−05:00', '−04:00', ''),
('', '', 'Etc./GMT', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'Etc./GMT+0', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'Etc./UCT', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'Etc./Universal', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'Etc./UTC', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'Etc./Zulu', '', '+00:00', '+00:00', 'Link to UTC'),
('NL', '+5222+00454', 'Europe/Amsterdam', '', '+01:00', '+02:00', ''),
('AD', '+4230+00131', 'Europe/Andorra', '', '+01:00', '+02:00', ''),
('GR', '+3758+02343', 'Europe/Athens', '', '+02:00', '+03:00', ''),
('', '', 'Europe/Belfast', '', '+00:00', '+01:00', 'Link to Europe/London'),
('RS', '+4450+02030', 'Europe/Belgrade', '', '+01:00', '+02:00', ''),
('DE', '+5230+01322', 'Europe/Berlin', '', '+01:00', '+02:00', 'In 1945, the Trizone did not follow Berlin\'s switch to DST, see Time in Germany'),
('SK', '+4809+01707', 'Europe/Bratislava', '', '+01:00', '+02:00', 'Link to Europe/Prague'),
('BE', '+5050+00420', 'Europe/Brussels', '', '+01:00', '+02:00', ''),
('RO', '+4426+02606', 'Europe/Bucharest', '', '+02:00', '+03:00', ''),
('HU', '+4730+01905', 'Europe/Budapest', '', '+01:00', '+02:00', ''),
('MD', '+4700+02850', 'Europe/Chisinau', '', '+02:00', '+03:00', ''),
('DK', '+5540+01235', 'Europe/Copenhagen', '', '+01:00', '+02:00', ''),
('IE', '+5320-00615', 'Europe/Dublin', '', '+00:00', '+01:00', ''),
('GI', '+3608-00521', 'Europe/Gibraltar', '', '+01:00', '+02:00', ''),
('GG', '+4927-00232', 'Europe/Guernsey', '', '+00:00', '+01:00', 'Link to Europe/London'),
('FI', '+6010+02458', 'Europe/Helsinki', '', '+02:00', '+03:00', ''),
('IM', '+5409-00428', 'Europe/Isle_of_Man', '', '+00:00', '+01:00', 'Link to Europe/London'),
('TR', '+4101+02858', 'Europe/Istanbul', '', '+02:00', '+03:00', ''),
('JE', '+4912-00207', 'Europe/Jersey', '', '+00:00', '+01:00', 'Link to Europe/London'),
('RU', '+5443+02030', 'Europe/Kaliningrad', 'Moscow-01 - Kaliningrad', '+03:00', '+03:00', ''),
('UA', '+5026+03031', 'Europe/Kiev', 'most locations', '+02:00', '+03:00', ''),
('PT', '+3843-00908', 'Europe/Lisbon', 'mainland', '+00:00', '+01:00', ''),
('SI', '+4603+01431', 'Europe/Ljubljana', '', '+01:00', '+02:00', 'Link to Europe/Belgrade'),
('GB', '+513030-0000731', 'Europe/London', '', '+00:00', '+01:00', ''),
('LU', '+4936+00609', 'Europe/Luxembourg', '', '+01:00', '+02:00', ''),
('ES', '+4024-00341', 'Europe/Madrid', 'mainland', '+01:00', '+02:00', ''),
('MT', '+3554+01431', 'Europe/Malta', '', '+01:00', '+02:00', ''),
('AX', '+6006+01957', 'Europe/Mariehamn', '', '+02:00', '+03:00', 'Link to Europe/Helsinki'),
('BY', '+5354+02734', 'Europe/Minsk', '', '+03:00', '+03:00', ''),
('MC', '+4342+00723', 'Europe/Monaco', '', '+01:00', '+02:00', ''),
('RU', '+5545+03735', 'Europe/Moscow', 'Moscow+00 - west Russia', '+04:00', '+04:00', ''),
('', '', 'Europe/Nicosia', '', '+02:00', '+03:00', 'Link to Asia/Nicosia'),
('NO', '+5955+01045', 'Europe/Oslo', '', '+01:00', '+02:00', ''),
('FR', '+4852+00220', 'Europe/Paris', '', '+01:00', '+02:00', ''),
('ME', '+4226+01916', 'Europe/Podgorica', '', '+01:00', '+02:00', 'Link to Europe/Belgrade'),
('CZ', '+5005+01426', 'Europe/Prague', '', '+01:00', '+02:00', ''),
('LV', '+5657+02406', 'Europe/Riga', '', '+02:00', '+03:00', ''),
('IT', '+4154+01229', 'Europe/Rome', '', '+01:00', '+02:00', ''),
('RU', '+5312+05009', 'Europe/Samara', 'Moscow+00 - Samara, Udmurtia', '+04:00', '+04:00', ''),
('SM', '+4355+01228', 'Europe/San_Marino', '', '+01:00', '+02:00', 'Link to Europe/Rome'),
('BA', '+4352+01825', 'Europe/Sarajevo', '', '+01:00', '+02:00', 'Link to Europe/Belgrade'),
('UA', '+4457+03406', 'Europe/Simferopol', 'central Crimea', '+02:00', '+03:00', ''),
('MK', '+4159+02126', 'Europe/Skopje', '', '+01:00', '+02:00', 'Link to Europe/Belgrade'),
('BG', '+4241+02319', 'Europe/Sofia', '', '+02:00', '+03:00', ''),
('SE', '+5920+01803', 'Europe/Stockholm', '', '+01:00', '+02:00', ''),
('EE', '+5925+02445', 'Europe/Tallinn', '', '+02:00', '+03:00', ''),
('AL', '+4120+01950', 'Europe/Tirane', '', '+01:00', '+02:00', ''),
('', '', 'Europe/Tiraspol', '', '+02:00', '+03:00', 'Link to Europe/Chisinau'),
('UA', '+4837+02218', 'Europe/Uzhgorod', 'Ruthenia', '+02:00', '+03:00', ''),
('LI', '+4709+00931', 'Europe/Vaduz', '', '+01:00', '+02:00', ''),
('VA', '+415408+0122711', 'Europe/Vatican', '', '+01:00', '+02:00', 'Link to Europe/Rome'),
('AT', '+4813+01620', 'Europe/Vienna', '', '+01:00', '+02:00', ''),
('LT', '+5441+02519', 'Europe/Vilnius', '', '+02:00', '+03:00', ''),
('RU', '+4844+04425', 'Europe/Volgograd', 'Moscow+00 - Caspian Sea', '+04:00', '+04:00', ''),
('PL', '+5215+02100', 'Europe/Warsaw', '', '+01:00', '+02:00', ''),
('HR', '+4548+01558', 'Europe/Zagreb', '', '+01:00', '+02:00', 'Link to Europe/Belgrade'),
('UA', '+4750+03510', 'Europe/Zaporozhye', 'Zaporozh\'ye, E Lugansk / Zaporizhia, E Luhansk', '+02:00', '+03:00', ''),
('CH', '+4723+00832', 'Europe/Zurich', '', '+01:00', '+02:00', ''),
('', '', 'GB', '', '+00:00', '+01:00', 'Link to Europe/London'),
('', '', 'GB-Eire', '', '+00:00', '+01:00', 'Link to Europe/London'),
('', '', 'GMT', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'GMT+0', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'GMT-0', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'GMT0', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'Greenwich', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'Hong Kong', '', '+08:00', '+08:00', 'Link to Asia/Hong_Kong'),
('', '', 'HST', '', '−10:00', '−10:00', ''),
('', '', 'Iceland', '', '+00:00', '+00:00', 'Link to Atlantic/Reykjavik'),
('MG', '-1855+04731', 'Indian/Antananarivo', '', '+03:00', '+03:00', ''),
('IO', '-0720+07225', 'Indian/Chagos', '', '+06:00', '+06:00', ''),
('CX', '-1025+10543', 'Indian/Christmas', '', '+07:00', '+07:00', ''),
('CC', '-1210+09655', 'Indian/Cocos', '', '+06:30', '+06:30', ''),
('KM', '-1141+04316', 'Indian/Comoro', '', '+03:00', '+03:00', ''),
('TF', '-492110+0701303', 'Indian/Kerguelen', '', '+05:00', '+05:00', ''),
('SC', '-0440+05528', 'Indian/Mahe', '', '+04:00', '+04:00', ''),
('MV', '+0410+07330', 'Indian/Maldives', '', '+05:00', '+05:00', ''),
('MU', '-2010+05730', 'Indian/Mauritius', '', '+04:00', '+04:00', ''),
('YT', '-1247+04514', 'Indian/Mayotte', '', '+03:00', '+03:00', ''),
('RE', '-2052+05528', 'Indian/Reunion', '', '+04:00', '+04:00', ''),
('', '', 'Iran', '', '+03:30', '+04:30', 'Link to Asia/Tehran'),
('', '', 'Israel', '', '+02:00', '+03:00', 'Link to Asia/Jerusalem'),
('', '', 'Jamaica', '', '−05:00', '−05:00', 'Link to America/Jamaica'),
('', '', 'Japan', '', '+09:00', '+09:00', 'Link to Asia/Tokyo'),
('', '', 'JST-9', '', '+09:00', '+09:00', 'Link to Asia/Tokyo'),
('', '', 'Kwajalein', '', '+12:00', '+12:00', 'Link to Pacific/Kwajalein'),
('', '', 'Libya', '', '+02:00', '+02:00', 'Link to Africa/Tripoli'),
('', '', 'MET', '', '+01:00', '+02:00', ''),
('', '', 'Mexico/BajaNorte', '', '−08:00', '−07:00', 'Link to America/Tijuana'),
('', '', 'Mexico/BajaSur', '', '−07:00', '−06:00', 'Link to America/Mazatlan'),
('', '', 'Mexico/General', '', '−06:00', '−05:00', 'Link to America/Mexico_City'),
('', '', 'MST', '', '−07:00', '−07:00', ''),
('', '', 'MST7MDT', '', '−07:00', '−06:00', ''),
('', '', 'Navajo', '', '−07:00', '−06:00', 'Link to America/Denver'),
('', '', 'NZ', '', '+12:00', '+13:00', 'Link to Pacific/Auckland'),
('', '', 'NZ-CHAT', '', '+12:45', '+13:45', 'Link to Pacific/Chatham'),
('WS', '-1350-17144', 'Pacific/Apia', '', '+13:00', '+14:00', ''),
('NZ', '-3652+17446', 'Pacific/Auckland', 'most locations', '+12:00', '+13:00', ''),
('NZ', '-4357-17633', 'Pacific/Chatham', 'Chatham Islands', '+12:45', '+13:45', ''),
('FM', '+0725+15147', 'Pacific/Chuuk', 'Chuuk (Truk) and Yap', '+10:00', '+10:00', ''),
('CL', '-2709-10926', 'Pacific/Easter', 'Easter Island & Sala y Gomez', '−06:00', '−05:00', ''),
('VU', '-1740+16825', 'Pacific/Efate', '', '+11:00', '+11:00', ''),
('KI', '-0308-17105', 'Pacific/Enderbury', 'Phoenix Islands', '+13:00', '+13:00', ''),
('TK', '-0922-17114', 'Pacific/Fakaofo', '', '+13:00', '+13:00', ''),
('FJ', '-1808+17825', 'Pacific/Fiji', '', '+12:00', '+13:00', ''),
('TV', '-0831+17913', 'Pacific/Funafuti', '', '+12:00', '+12:00', ''),
('EC', '-0054-08936', 'Pacific/Galapagos', 'Galapagos Islands', '−06:00', '−06:00', ''),
('PF', '-2308-13457', 'Pacific/Gambier', 'Gambier Islands', '−09:00', '−09:00', ''),
('SB', '-0932+16012', 'Pacific/Guadalcanal', '', '+11:00', '+11:00', ''),
('GU', '+1328+14445', 'Pacific/Guam', '', '+10:00', '+10:00', ''),
('US', '+211825-1575130', 'Pacific/Honolulu', 'Hawaii', '−10:00', '−10:00', ''),
('UM', '+1645-16931', 'Pacific/Johnston', 'Johnston Atoll', '−10:00', '−10:00', ''),
('KI', '+0152-15720', 'Pacific/Kiritimati', 'Line Islands', '+14:00', '+14:00', ''),
('FM', '+0519+16259', 'Pacific/Kosrae', 'Kosrae', '+11:00', '+11:00', ''),
('MH', '+0905+16720', 'Pacific/Kwajalein', 'Kwajalein', '+12:00', '+12:00', ''),
('MH', '+0709+17112', 'Pacific/Majuro', 'most locations', '+12:00', '+12:00', ''),
('PF', '-0900-13930', 'Pacific/Marquesas', 'Marquesas Islands', '−09:30', '−09:30', ''),
('UM', '+2813-17722', 'Pacific/Midway', 'Midway Islands', '−11:00', '−11:00', ''),
('NR', '-0031+16655', 'Pacific/Nauru', '', '+12:00', '+12:00', ''),
('NU', '-1901-16955', 'Pacific/Niue', '', '−11:00', '−11:00', ''),
('NF', '-2903+16758', 'Pacific/Norfolk', '', '+11:30', '+11:30', ''),
('NC', '-2216+16627', 'Pacific/Noumea', '', '+11:00', '+11:00', ''),
('AS', '-1416-17042', 'Pacific/Pago_Pago', '', '−11:00', '−11:00', ''),
('PW', '+0720+13429', 'Pacific/Palau', '', '+09:00', '+09:00', ''),
('PN', '-2504-13005', 'Pacific/Pitcairn', '', '−08:00', '−08:00', ''),
('FM', '+0658+15813', 'Pacific/Pohnpei', 'Pohnpei (Ponape)', '+11:00', '+11:00', ''),
('', '', 'Pacific/Ponape', '', '+11:00', '+11:00', 'Link to Pacific/Pohnpei'),
('PG', '-0930+14710', 'Pacific/Port_Moresby', '', '+10:00', '+10:00', ''),
('CK', '-2114-15946', 'Pacific/Rarotonga', '', '−10:00', '−10:00', ''),
('MP', '+1512+14545', 'Pacific/Saipan', '', '+10:00', '+10:00', ''),
('', '', 'Pacific/Samoa', '', '−11:00', '−11:00', 'Link to Pacific/Pago_Pago'),
('PF', '-1732-14934', 'Pacific/Tahiti', 'Society Islands', '−10:00', '−10:00', ''),
('KI', '+0125+17300', 'Pacific/Tarawa', 'Gilbert Islands', '+12:00', '+12:00', ''),
('TO', '-2110-17510', 'Pacific/Tongatapu', '', '+13:00', '+13:00', ''),
('', '', 'Pacific/Truk', '', '+10:00', '+10:00', 'Link to Pacific/Chuuk'),
('UM', '+1917+16637', 'Pacific/Wake', 'Wake Island', '+12:00', '+12:00', ''),
('WF', '-1318-17610', 'Pacific/Wallis', '', '+12:00', '+12:00', ''),
('', '', 'Pacific/Yap', '', '+10:00', '+10:00', 'Link to Pacific/Chuuk'),
('', '', 'Poland', '', '+01:00', '+02:00', 'Link to Europe/Warsaw'),
('', '', 'Portugal', '', '+00:00', '+01:00', 'Link to Europe/Lisbon'),
('', '', 'PRC', '', '+08:00', '+08:00', 'Link to Asia/Shanghai'),
('', '', 'PST8PDT', '', '−08:00', '−07:00', ''),
('', '', 'ROC', '', '+08:00', '+08:00', 'Link to Asia/Taipei'),
('', '', 'ROK', '', '+09:00', '+09:00', 'Link to Asia/Seoul'),
('', '', 'Singapore', '', '+08:00', '+08:00', 'Link to Asia/Singapore'),
('', '', 'Turkey', '', '+02:00', '+03:00', 'Link to Europe/Istanbul'),
('', '', 'UCT', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'Universal', '', '+00:00', '+00:00', 'Link to UTC'),
('', '', 'US/Alaska', '', '−09:00', '−08:00', 'Link to America/Anchorage'),
('', '', 'US/Aleutian', '', '−10:00', '−09:00', 'Link to America/Adak'),
('', '', 'US/Arizona', '', '−07:00', '−07:00', 'Link to America/Phoenix'),
('', '', 'US/Central', '', '−06:00', '−05:00', 'Link to America/Chicago'),
('', '', 'US/East-Indiana', '', '−05:00', '−04:00', 'Link to America/Indiana/Indianapolis'),
('', '', 'US/Eastern', '', '−05:00', '−04:00', 'Link to America/New_York'),
('', '', 'US/Hawaii', '', '−10:00', '−10:00', 'Link to Pacific/Honolulu'),
('', '', 'US/Indiana-Starke', '', '−06:00', '−05:00', 'Link to America/Indiana/Knox'),
('', '', 'US/Michigan', '', '−05:00', '−04:00', 'Link to America/Detroit'),
('', '', 'US/Mountain', '', '−07:00', '−06:00', 'Link to America/Denver'),
('', '', 'US/Pacific', '', '−08:00', '−07:00', 'Link to America/Los_Angeles'),
('', '', 'US/Pacific-New', '', '−08:00', '−07:00', 'Link to America/Los_Angeles'),
('', '', 'US/Samoa', '', '−11:00', '−11:00', 'Link to Pacific/Pago_Pago'),
('', '', 'UTC', '', '+00:00', '+00:00', ''),
('', '', 'W-SU', '', '+04:00', '+04:00', 'Link to Europe/Moscow'),
('', '', 'WET', '', '+00:00', '+01:00', ''),
('', '', 'Zulu', '', '+00:00', '+00:00', 'Link to UTC');

-- --------------------------------------------------------

--
-- Table structure for table `pre_categories`
--

CREATE TABLE `pre_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `is_parent` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-category, 0-course',
  `name` varchar(256) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `code` varchar(8) NOT NULL,
  `image` varchar(512) DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-yes, 0-no',
  `slug` varchar(512) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-active, 0-inactive',
  `sort_order` int(11) NOT NULL DEFAULT '0' COMMENT 'records display order',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `categories` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_certificates`
--

CREATE TABLE `pre_certificates` (
  `certificate_id` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `description` text,
  `required` enum('Yes','No') DEFAULT 'No',
  `allowed_formats` varchar(256) DEFAULT 'jpg,gif,png,jpeg',
  `certificate_for` enum('tutors','institutes','students') DEFAULT NULL,
  `status` enum('Active','In-Active') DEFAULT 'Active',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_country`
--

CREATE TABLE `pre_country` (
  `id` int(11) NOT NULL,
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nicename` varchar(80) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_country`
--

INSERT INTO `pre_country` (`id`, `iso`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8, 355),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20, 376),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24, 244),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL, 0),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533, 297),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 61),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112, 375),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204, 229),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL, 0),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL, 246),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124, 1),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236),
(42, 'TD', 'CHAD', 'Chad', 'TCD', 148, 235),
(43, 'CL', 'CHILE', 'Chile', 'CHL', 152, 56),
(44, 'CN', 'CHINA', 'China', 'CHN', 156, 86),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL, 61),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL, 672),
(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57),
(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174, 269),
(49, 'CG', 'CONGO', 'Congo', 'COG', 178, 242),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 242),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506),
(53, 'CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384, 225),
(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191, 385),
(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192, 53),
(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420),
(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208, 45),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253),
(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809),
(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593),
(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818, 20),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240),
(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291),
(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233, 372),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298),
(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242, 679),
(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246, 358),
(73, 'FR', 'FRANCE', 'France', 'FRA', 250, 33),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL, 0),
(77, 'GA', 'GABON', 'Gabon', 'GAB', 266, 241),
(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220),
(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995),
(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276, 49),
(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288, 233),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350),
(83, 'GR', 'GREECE', 'Greece', 'GRC', 300, 30),
(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299),
(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590),
(87, 'GU', 'GUAM', 'Guam', 'GUM', 316, 1671),
(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502),
(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324, 224),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245),
(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328, 592),
(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332, 509),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL, 0),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39),
(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340, 504),
(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852),
(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36),
(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352, 354),
(99, 'IN', 'INDIA', 'India', 'IND', 356, 91),
(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98),
(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964),
(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372, 353),
(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376, 972),
(105, 'IT', 'ITALY', 'Italy', 'ITA', 380, 39),
(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876),
(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392, 81),
(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400, 962),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7),
(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404, 254),
(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408, 850),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82),
(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996),
(116, 'LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418, 856),
(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428, 371),
(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961),
(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266),
(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423),
(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352),
(125, 'MO', 'MACAO', 'Macao', 'MAC', 446, 853),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261),
(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454, 265),
(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60),
(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960),
(131, 'ML', 'MALI', 'Mali', 'MLI', 466, 223),
(132, 'MT', 'MALTA', 'Malta', 'MLT', 470, 356),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596),
(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222),
(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230),
(137, 'YT', 'MAYOTTE', 'Mayotte', NULL, NULL, 269),
(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484, 52),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373),
(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492, 377),
(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664),
(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258),
(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95),
(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264),
(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520, 674),
(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524, 977),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505),
(155, 'NE', 'NIGER', 'Niger', 'NER', 562, 227),
(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234),
(157, 'NU', 'NIUE', 'Niue', 'NIU', 570, 683),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670),
(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578, 47),
(161, 'OM', 'OMAN', 'Oman', 'OMN', 512, 968),
(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92),
(163, 'PW', 'PALAU', 'Palau', 'PLW', 585, 680),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL, 970),
(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591, 507),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675),
(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595),
(168, 'PE', 'PERU', 'Peru', 'PER', 604, 51),
(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0),
(171, 'PL', 'POLAND', 'Poland', 'POL', 616, 48),
(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787),
(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634, 974),
(175, 'RE', 'REUNION', 'Reunion', 'REU', 638, 262),
(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642, 40),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643, 70),
(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784),
(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882, 684),
(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966),
(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221),
(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL, 381),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232),
(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421),
(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677),
(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27),
(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL, 0),
(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724, 34),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94),
(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736, 249),
(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740, 597),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268),
(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255),
(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764, 66),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL, 670),
(213, 'TG', 'TOGO', 'Togo', 'TGO', 768, 228),
(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690),
(215, 'TO', 'TONGA', 'Tonga', 'TON', 776, 676),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868),
(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216),
(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792, 90),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649),
(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688),
(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800, 256),
(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44),
(226, 'US', 'UNITED STATES', 'United States', 'USA', 840, 1),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL, 1),
(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998),
(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678),
(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58),
(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212),
(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887, 967),
(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263),
(240, 'RS', 'SERBIA', 'Serbia', 'SRB', 688, 381),
(241, 'AP', 'ASIA PACIFIC REGION', 'Asia / Pacific Region', '0', 0, 0),
(242, 'ME', 'MONTENEGRO', 'Montenegro', 'MNE', 499, 382),
(243, 'AX', 'ALAND ISLANDS', 'Aland Islands', 'ALA', 248, 358),
(244, 'BQ', 'BONAIRE, SINT EUSTATIUS AND SABA', 'Bonaire, Sint Eustatius and Saba', 'BES', 535, 599),
(245, 'CW', 'CURACAO', 'Curacao', 'CUW', 531, 599),
(246, 'GG', 'GUERNSEY', 'Guernsey', 'GGY', 831, 44),
(247, 'IM', 'ISLE OF MAN', 'Isle of Man', 'IMN', 833, 44),
(248, 'JE', 'JERSEY', 'Jersey', 'JEY', 832, 44),
(249, 'XK', 'KOSOVO', 'Kosovo', '---', 0, 381),
(250, 'BL', 'SAINT BARTHELEMY', 'Saint Barthelemy', 'BLM', 652, 590),
(251, 'MF', 'SAINT MARTIN', 'Saint Martin', 'MAF', 663, 590),
(252, 'SX', 'SINT MAARTEN', 'Sint Maarten', 'SXM', 534, 1),
(253, 'SS', 'SOUTH SUDAN', 'South Sudan', 'SSD', 728, 211);

-- --------------------------------------------------------

--
-- Table structure for table `pre_course_categories`
--

CREATE TABLE `pre_course_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `course_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='For course_id and category_id, refer pre_categories tbl(is_parent=0 mean course)';

-- --------------------------------------------------------

--
-- Table structure for table `pre_course_downloads`
--

CREATE TABLE `pre_course_downloads` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_course_purchases`
--

CREATE TABLE `pre_course_purchases` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_email_templates`
--

CREATE TABLE `pre_email_templates` (
  `email_template_id` int(11) NOT NULL,
  `template_key` varbinary(256) NOT NULL,
  `template_subject` varchar(512) DEFAULT NULL,
  `template_content` longtext NOT NULL,
  `template_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `template_updated` date DEFAULT NULL,
  `template_status` enum('Active','In-Active') DEFAULT NULL,
  `template_variables` text,
  `from_email` varchar(126) DEFAULT NULL,
  `from_name` varchar(256) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_email_templates`
--

INSERT INTO `pre_email_templates` (`email_template_id`, `template_key`, `template_subject`, `template_content`, `template_created`, `template_updated`, `template_status`, `template_variables`, `from_email`, `from_name`) VALUES
(1, 0x526567697374726174696f6e, NULL, '__SITETITLE__\n\n__FIRST_NAME__\n\n__LAST_NAME__\n\n__ACTIVATELINK__\n\nMail footer content here', '2016-09-26 13:40:11', NULL, 'Active', '__SITETITLE__\n\n__FIRST_NAME__\n\n__LAST_NAME__\n\n__LOGO__\n\n__CONTACTUS__\n\n__ACTIVATELINK__\n\n__URL__\n\n__COPYRIGHTS__\n\n__EMAIL__\n\n__PASSWORD__', NULL, NULL),
(3, 0x466f72676f742050617373776f7264, NULL, '<p>\r\n	__SITE_TITLE__</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	__FIRST_NAME__</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	__LAST_NAME__</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	__LINK__</p>\r\n', '2016-08-20 07:49:41', NULL, 'Active', '<p>\r\n	__SITE_TITLE__</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	__FIRST_NAME__</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	__LAST_NAME__</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	__LINK__</p>', NULL, NULL),
(4, 0x41707020537562736372696265204c696e6b, 'Your App Links', '<p>\n	__ANDROID_LINK__</p>\n<p>\n	&nbsp;</p>\n<p>\n	__IOS_LINK__</p>\n', '2016-09-26 13:39:50', NULL, 'Active', '<p>\n	__ANDROID_LINK__</p>\n<p>\n	&nbsp;</p>\n<p>\n	__IOS_LINK__</p>\n', 'adiyya@gmail.com', 'Adiyya Tadikamalla'),
(5, 0x5475746f7220426f6f6b696e67205375636365737320456d61696c, 'Booking Request From Student', '<p>\r\n	Hello ___TUTOR_NAME___,</p>\r\n<p>\r\n	Student &quot;___STUDENT_NAME___&quot; booked you for the course &quot;___COURSE_NAME___&quot;</p>\r\n<p>\r\n	for the time-slot &quot;___DATE_TIME___&quot; and &quot; ___LOCATION___&quot; as preferred location for sessions.</p>\r\n<p>\r\n	Please ___LOGINLINK___ to view the booking details.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-09-27 05:35:31', NULL, 'Active', '__TUTOR_NAME__\n\n__STUDENT_NAME__\n\n__COURSE_NAME__\n\n__DATE_TIME__\n\n__LOCATION__\n\n__LOGIN_HERE__', NULL, NULL),
(6, 0x53656e642053747564656e742773204164647265737320456d61696c, 'Student\'s Address For Tutoring', '<p>\r\n	Hello ___TUTOR_NAME___,</p>\r\n<p>\r\n	You approved Student &quot;___STUDENT_NAME___&quot;&#39;s booking for the course &quot;___COURSE_NAME___&quot;</p>\r\n<p>\r\n	for the time-slot &quot;___DATE_TIME___&quot; and &quot; ___LOCATION___&quot; as preferred location for sessions.</p>\r\n<p>\r\n	Below is the address of the Student</p>\r\n<p>\r\n	___STUDENT_ADDRESS___</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-05 12:48:05', NULL, 'Active', '__TUTOR_NAME__\n\n__STUDENT_NAME__\n\n__COURSE_NAME__\n\n__DATE_TIME__\n\n__LOCATION__\n\n__STUDENT_ADDRESS__\n\n__LOGIN_HERE__', NULL, NULL),
(7, 0x53656e642053747564656e74277320536b797065204964, 'Send Student\'s Skype Id', '<p>\r\n	Hello ___TUTOR_NAME___,</p>\r\n<p>\r\n	Student &quot;___STUDENT_NAME___&quot; started the course &quot;___COURSE_NAME___&quot;</p>\r\n<p>\r\n	for the time-slot &quot;___DATE_TIME___&quot; and &quot; ___LOCATION___&quot; as preferred location for sessions.</p>\r\n<p>\r\n	Below is the Skype Id of the Student. Please add the Student&#39;s Skype id and start online tutoring.</p>\r\n<p>\r\n	___STUDENT_ADDRESS___</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-07 11:46:00', NULL, 'Active', NULL, NULL, NULL),
(8, 0x496e7374697475746520456e726f6c6c205375636365737320456d61696c, 'Enrollment Request From Student', '<p>\r\n	Hi,</p>\r\n<p>\r\n	Student &quot;___STUDENT_NAME___&quot; enrolled in the batch &quot;___BATCH_NAME___&quot; for the course &quot;___COURSE_NAME___&quot;</p>\r\n<p>\r\n	Offered by institute &quot;___INSTITUTE_NAME___&quot;.</p>\r\n<p>\r\n	Please ___LOGINLINK___ to view the enrollment details.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-24 07:26:31', NULL, 'Active', NULL, NULL, NULL),
(9, 0x436c61696d2042792053747564656e7420456d61696c, 'Claim By Student', '<p>\r\n	Hi,</p>\r\n<p>\r\n	Student &quot;___STUDENT_NAME___&quot; claimed for your intervention for the ___BOOKING_ID___.</p>\r\n<p>\r\n	Please ___LOGINLINK___ to view the details.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-20 10:04:49', NULL, 'Active', NULL, NULL, NULL),
(10, 0x436c61696d204279205475746f7220456d61696c, 'Claim By Tutor', '<p>\r\n	Hi,</p>\r\n<p>\r\n	Student &quot;___STUDENT_NAME___&quot; claimed for your intervention for the booking &quot;___BOOKING_ID___&quot;.</p>\r\n<p>\r\n	Please ___LOGINLINK___ to view the details.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-14 08:51:25', NULL, 'Active', NULL, NULL, NULL),
(11, 0x53657373696f6e20496e6974696174656420456d61696c, 'Session Initiated By Tutor', '<p>\r\n	Hello ___STUDENT_NAME___,</p>\r\n<p>\r\n	Tutor &quot;___TUTOR_NAME___&quot; has initiated the session for the course &quot;___COURSE_NAME___&quot;</p>\r\n<p>\r\n	for the time-slot &quot;___DATE_TIME___&quot; and &quot; ___LOCATION___&quot; as preferred location for sessions.</p>\r\n<p>\r\n	Please ___LOGINLINK___ to view the details and update status to &quot;Start Course&quot;.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-14 13:19:24', NULL, 'Active', NULL, NULL, NULL),
(12, 0x417070726f76656d656e7420537461747573204368616e67656420456d61696c, 'Approvement Status Changed', '<p>\r\n	Hello ___USER_NAME___,</p>\r\n<p>\r\n	Admin&nbsp; &quot;___APPROVEMENT_STATUS___&quot; you.</p>\r\n<p>\r\n	___LOGINLINK___ to upload required certificates.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-15 10:15:32', NULL, 'Active', NULL, NULL, NULL),
(13, 0x426174636820417070726f76656420416c65727420546f205475746f7220456d61696c, 'Batch Approved', '<p>\r\n	Hello ___TUTOR_NAME___,</p>\r\n<p>\r\n	Institute &quot;___INSTITUTE_NAME___&quot; approved the batch &quot;___BATCH_NAME___&quot;.</p>\r\n<p>\r\n	Please login and initiate the session.</p>\r\n<p>\r\n	Initiate Session option will be enable ___MINS___ before the start time of the session.</p>\r\n<p>\r\n	Please ___LOGINLINK___ to view the details.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-19 08:56:24', NULL, 'Active', NULL, NULL, NULL),
(14, 0x42617463682053657373696f6e20496e6974696174656420416c65727420546f2053747564656e747320456d61696c, 'Batch Session Initiated', '<p>\r\n	Hello ___STUDENT_NAME___,</p>\r\n<p>\r\n	Tutor &quot;___TUTOR_NAME___&quot; has initiated the session for the batch &quot;___BATCH_NAME___&quot;.</p>\r\n<p>\r\n	Please attend and continue the session.</p>\r\n<p>\r\n	Please ___LOGINLINK___ to view the details.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-19 12:36:39', NULL, 'Active', NULL, NULL, NULL),
(15, 0x436f7572736520436f6d706c6574656420666f722074686520426174636820416c65727420546f2053747564656e747320456d61696c, 'Course Completed for the Batch', '<p>\r\n	Hello ___STUDENT_NAME___,</p>\r\n<p>\r\n	Tutor &quot;___TUTOR_NAME___&quot; has completed the course of the batch &quot;___BATCH_NAME___&quot;.</p>\r\n<p>\r\n	If you are not satisfied with the session, you can claim to the Admin.</p>\r\n<p>\r\n	Please ___LOGINLINK___ to view the details.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-19 14:08:54', NULL, 'Active', NULL, NULL, NULL),
(16, 0x436f6e6174637420517565727920456d61696c, 'Conatct Query Received', '<p>\r\n	Hello&nbsp; Admin,</p>\r\n<p>\r\n	You got contact query. Below are the details.</p>\r\n<p>\r\n	<strong>First Name: </strong>___FIRST_NAME___</p>\r\n<p>\r\n	<strong>Last Name: </strong>___LAST_NAME___</p>\r\n<p>\r\n	<strong>Email: </strong>___EMAIL___</p>\r\n<p>\r\n	<strong>Subject: </strong>___SUBJECT___</p>\r\n<p>\r\n	<strong>Message: </strong>___MESSAGE___</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-21 13:24:28', NULL, 'Active', NULL, NULL, NULL),
(17, 0x53656e64204d65737361676520456d61696c, 'Message Received From', '<p>\n	Hi ___TO_NAME___,</p>\n<p>\n	You got a message from ___USER_TYPE___. Below are the details.</p>\n<p>\n	<strong>Name: </strong>___NAME___</p>\n<p>\n	<strong>Email: </strong>___EMAIL___</p>\n<p>\n	<strong>Phone: </strong>___PHONE___</p>\n<p>\n	<strong>Course Seeking: </strong>___COURSE___</p>\n<p>\n	<strong>Message: </strong>___MESSAGE___</p>\n<p>\n	&nbsp;</p>\n<p>\n	Footer content goes here</p>\n', '2016-10-24 09:23:34', NULL, 'Active', NULL, NULL, NULL),
(18, 0x53656e642041707020446f776e6c6f6164204c696e6b20456d61696c, 'Tutor App Download Link', '<p>\r\n	Hi,</p>\r\n<p>\r\n	Please click on the below link to download Tutors App.</p>\r\n<p>\r\n	App link goes here.</p>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	Footer content goes here</p>\r\n', '2016-10-28 05:26:14', NULL, 'Active', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pre_faqs`
--

CREATE TABLE `pre_faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` varchar(500) NOT NULL,
  `status` enum('Active','Inactive','','') NOT NULL DEFAULT 'Active',
  `created_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_gallery`
--

CREATE TABLE `pre_gallery` (
  `image_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image_title` varchar(256) DEFAULT NULL,
  `image_name` varchar(256) DEFAULT NULL,
  `image_order` int(11) DEFAULT '0',
  `image_status` enum('Active','In-Active') DEFAULT 'Active',
  `image_created` datetime DEFAULT NULL,
  `image_updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_groups`
--

CREATE TABLE `pre_groups` (
  `id` mediumint(8) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `group_status` enum('Active','In-Active') DEFAULT 'Active',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modules` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_groups`
--

INSERT INTO `pre_groups` (`id`, `name`, `description`, `group_status`, `created`, `modules`) VALUES
(1, 'admin', 'Administrator', 'Active', '2015-11-08 21:40:49', '2,3,1,4,5,6,7,8,9'),
(2, 'student', 'Registered Students', 'Active', '2015-11-08 21:40:49', '4,6'),
(3, 'tutor', 'Registered Tutors', 'Active', '2016-08-01 09:38:26', NULL),
(4, 'institute', 'Registered Institutes', 'Active', '2016-08-01 09:38:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pre_inst_batches`
--

CREATE TABLE `pre_inst_batches` (
  `batch_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `inst_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL COMMENT 'tutor assigned for this batch',
  `batch_code` varchar(55) NOT NULL,
  `batch_name` varchar(512) NOT NULL,
  `time_slot` varchar(15) NOT NULL,
  `course_offering_location` varchar(512) NOT NULL DEFAULT 'Online',
  `duration_value` tinyint(5) NOT NULL,
  `duration_type` enum('hours','days','months','years') NOT NULL DEFAULT 'days',
  `batch_start_date` date NOT NULL,
  `batch_end_date` date NOT NULL,
  `batch_max_strength` tinyint(5) NOT NULL,
  `fee` float NOT NULL,
  `per_credit_value` float NOT NULL,
  `course_content` text NOT NULL,
  `days_off` varchar(155) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint(5) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_inst_enrolled_students`
--

CREATE TABLE `pre_inst_enrolled_students` (
  `enroll_id` bigint(20) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `inst_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `batch_code` varchar(55) CHARACTER SET utf8 NOT NULL,
  `batch_name` varchar(512) CHARACTER SET utf8 NOT NULL,
  `time_slot` varchar(15) CHARACTER SET utf8 NOT NULL,
  `course_offering_location` varchar(512) CHARACTER SET utf8 NOT NULL,
  `duration_value` tinyint(5) NOT NULL,
  `duration_type` enum('hours','days','months','years') CHARACTER SET utf8 NOT NULL DEFAULT 'days',
  `batch_start_date` date NOT NULL,
  `batch_end_date` date NOT NULL,
  `fee` float NOT NULL,
  `per_credit_value` float NOT NULL DEFAULT '1',
  `course_content` text CHARACTER SET utf8,
  `days_off` varchar(155) CHARACTER SET utf8 DEFAULT NULL,
  `message` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `admin_commission` float NOT NULL DEFAULT '0' COMMENT 'admin commission in percentage with the fee in credits. result will be in round value of credits',
  `admin_commission_val` float NOT NULL DEFAULT '0' COMMENT 'admin commision value in credits',
  `prev_status` varchar(512) CHARACTER SET utf8 NOT NULL DEFAULT 'pending',
  `status` enum('pending','approved','session_initiated','running','completed','called_for_admin_intervention','closed','cancelled_before_course_started') CHARACTER SET utf8 NOT NULL DEFAULT 'pending',
  `status_desc` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_inst_locations`
--

CREATE TABLE `pre_inst_locations` (
  `id` int(11) NOT NULL,
  `inst_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint(5) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_inst_offered_courses`
--

CREATE TABLE `pre_inst_offered_courses` (
  `id` int(11) NOT NULL,
  `inst_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint(5) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_inst_teaching_types`
--

CREATE TABLE `pre_inst_teaching_types` (
  `id` int(11) NOT NULL,
  `inst_id` int(11) NOT NULL,
  `teaching_type_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint(5) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_languages`
--

CREATE TABLE `pre_languages` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL DEFAULT '',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_languages`
--

INSERT INTO `pre_languages` (`id`, `name`, `status`) VALUES
(2, 'English', 'Active'),
(3, 'French', 'Active'),
(4, 'Hindi', 'Active'),
(5, 'Arabic', 'Active'),
(7, 'Urdu', 'Active'),
(8, 'Dutch', 'Active'),
(10, 'Japanese', 'Inactive'),
(11, 'German', 'Active'),
(12, 'Chinese', 'Active'),
(13, 'Danish', 'Active'),
(14, 'Czech', 'Active'),
(15, 'Greek', 'Active'),
(16, 'Hebrew', 'Active'),
(17, 'Italian', 'Active'),
(18, 'Latin', 'Active'),
(19, 'portuguese', 'Active'),
(20, 'Nepali', 'Active'),
(21, 'spanish', 'Active'),
(22, 'Indonesian', 'Active'),
(23, 'Irish', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `pre_languagewords`
--

CREATE TABLE `pre_languagewords` (
  `lang_id` bigint(22) NOT NULL,
  `lang_key` varchar(256) DEFAULT NULL,
  `english` longtext,
  `spanish` longtext,
  `bengali` longtext,
  `french` longtext,
  `japanese` longtext,
  `hindi` longtext,
  `russian` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_languagewords`
--

INSERT INTO `pre_languagewords` (`lang_id`, `lang_key`, `english`, `spanish`, `bengali`, `french`, `japanese`, `hindi`, `russian`) VALUES
(1, 'select_location', 'Select Location', ' Seleccionar ubicación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выбрать Расположение'),
(2, 'type_of_course', 'Type Of Course', ' El tipo de curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Тип курса'),
(3, 'Tutors : Find Tutors Now', 'Tutors : Find Tutors Now', ' Tutores: encontrar profesor Ahora', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(4, 'My Dashboard', 'My Dashboard', ' Mi tablero', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мой Dashboard'),
(5, 'our_popular_courses', 'Our Popular Courses', 'Nuestros Cursos populares', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Наши популярные курсы'),
(6, 'see_all', 'See All', ' Ver todo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Увидеть все'),
(7, 'check_all_courses', 'Check All Courses', ' Compruebe todos los cursos', NULL, NULL, NULL, 'सभी पाठ्यक्रमों की जाँच करें', ' Проверить все курсы'),
(8, 'User Login', 'User Login', 'Inicio de sesión de usuario', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Логин пользователя'),
(9, 'Dashboard', 'Dashboard', ' Tablero', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Панель приборов'),
(10, 'Manage', 'Manage', 'Gestionar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'управлять'),
(11, 'Subjects', 'Subjects', ' Asignaturas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предметы'),
(12, 'Locations', 'Locations', ' Ubicaciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Место проживания'),
(13, 'Teaching Type', 'Teaching Type', ' Tipo de la enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Обучение Тип'),
(14, 'Packages', 'Packages', ' paquetes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'пакеты'),
(15, 'List Packages', 'List Packages', ' Paquetes lista', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список пакетов'),
(16, 'Personnel Information', 'Personnel Information', ' Información del personal', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Личная информация'),
(17, 'Profile Information', 'Profile Information', ' información del perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'информация профиля'),
(18, 'Education', 'Education', ' Educación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'образование'),
(19, 'Contact Information', 'Contact Information', ' Información del contacto', NULL, NULL, NULL, 'संपर्क जानकारी', 'Контактная информация'),
(20, 'Gallery', 'Gallery', ' Galería', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Галерея'),
(21, 'Change Password', 'Change Password', 'Cambia la contraseña', NULL, NULL, NULL, 'पासवर्ड बदलें', 'Изменить пароль'),
(22, 'Package Name', 'Package Name', ' Nombre del paquete', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Имя пакета'),
(23, 'Validity Type', 'Validity Type', ' Tipo de validez', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Срок действия Тип'),
(24, 'Package Cost', 'Package Cost', ' Costo del paquete', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Стоимость пакета'),
(25, 'Buy Now', 'Buy Now', 'Compra ahora', NULL, NULL, NULL, 'अभी खरीदें', 'купить сейчас'),
(26, 'MSG_NO_ENTRY', 'MSG NO ENTRY', 'MSG NO ENTRADA', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'не Msg никаких записей'),
(27, 'info', 'Info', 'información', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Информация'),
(28, 'package', 'Package', ' Paquete', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' пакет'),
(29, 'Payment gateway', 'Payment Gateway', ' Puerta de pago', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Платежный шлюз'),
(30, 'error', 'Error', ' Error', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'ошибка'),
(31, 'emailusername', 'Emailusername', 'Emailusername', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' E-mail имя пользователя'),
(32, 'password', 'Password', 'Contraseña', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'пароль'),
(33, 'select', 'Select', 'Seleccionar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выбрать'),
(34, 'load_more', 'Load More', ' Carga más', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Загрузи больше'),
(35, 'loading', 'Loading', ' Cargando', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'загрузка'),
(36, 'You have reached end of the list', 'You Have Reached End Of The List.', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Вы достигли конца списка'),
(37, 'You dont have permission to access this page', 'You Dont Have Permission To Access This Page', ' Usted no tiene permiso para acceder a esta página', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Вы не имеете прав доступа к этой странице'),
(38, 'find_student_leads', 'Find Student Leads', ' Encuentra Ventas Estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Найти Student потенциальных'),
(39, 'logged_in_successfully', 'Logged In Successfully', 'Conectado con éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' При успешном входе'),
(40, 'success', 'Success', 'Éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'успех'),
(41, 'profile', 'Profile', ' Perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Профиль'),
(42, 'Sign out', 'Sign Out', ' Desconectar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выход'),
(43, 'Users', 'Users', 'usuarios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'пользователей'),
(44, 'view_users', 'View Users', ' Ver todos los usuarios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотр пользователей'),
(45, 'students', 'Students', ' Los estudiantes', NULL, NULL, NULL, 'अभी बुक करें', 'Студенты'),
(46, 'tutors', 'Tutors', ' Los tutores', NULL, NULL, NULL, 'अभी बुक करें', 'Репетиторы'),
(47, 'institutes', 'Institutes', ' institutos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Институты'),
(48, 'create', 'Create', ' Crear', NULL, NULL, NULL, 'सर्जन करना', 'Создайте'),
(49, 'leads', 'Leads', ' plomos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Ведет'),
(50, 'all_leads', 'All Leads', 'todos los potenciales', NULL, NULL, NULL, ' सभी सुराग', 'Все Ведет'),
(51, 'premium_leads', 'Premium Leads', ' Ventas de primera calidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Премиум Ведет'),
(52, 'free_leads', 'Free Leads', 'Ventas libres', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Бесплатные Ведет'),
(53, 'open_leads', 'Open Leads', ' potenciales abiertos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' открытые Ведет'),
(54, 'closed_leads', 'Closed Leads', ' Ventas cerradas', NULL, NULL, NULL, 'बंद सुराग', 'Закрытые Ведет'),
(55, 'unregistered_leads', 'Unregistered Leads', 'Ventas no registrados', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Незарегистрированные Ведет'),
(56, 'messages', 'Messages', 'mensajes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Сообщения'),
(57, 'tutor_messages', 'Tutor Messages', ' Los mensajes de tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Репетитор сообщения'),
(58, 'student_messages', 'Student Messages', ' Los mensajes de los estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Студенческие сообщения'),
(59, 'sent', 'Sent', 'Expedido', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправлено'),
(60, 'categories', 'Categories', ' Categorías', NULL, NULL, NULL, ' श्रेणियाँ', 'категории'),
(61, 'list_categories', 'List Categories', ' Lista de categorías', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список категорий'),
(62, 'add_category', 'Add Category', 'añadir categoría', NULL, NULL, NULL, ' श्रेणी जोड़ना', 'Добавить категорию'),
(63, 'list_courses', 'List Courses', ' Cursos de lista', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список курсов'),
(64, 'add_course', 'Add Course', ' Agregar curso', NULL, NULL, NULL, ' कोर्स जोड़े', 'Добавить курс'),
(65, 'options', 'Options', ' opciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Опции'),
(66, 'list_degrees', 'List Degrees', ' Grados de la lista', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список ученых степеней'),
(67, 'add_location', 'Add Location', ' Agregar Direccion', NULL, NULL, NULL, 'स्थान बताईए', 'Добавить местоположение'),
(68, 'list_locations', 'List Locations', ' Localizaciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список Местоположение'),
(69, 'list_packages', 'List Packages', ' Paquetes lista', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список пакетов'),
(70, 'add_package', 'Add Package', 'Agregar paquete', NULL, NULL, NULL, ' पैकेज जोड़ें', 'Добавить пакет'),
(71, 'testimonials', 'Testimonials', 'Testimonios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्सलेनदेन इतिहास क्रेडिट्स', 'Отзывы'),
(72, 'all', 'All', ' Todas', NULL, NULL, NULL, ' सब', 'Все'),
(73, 'tutors', 'Tutor\'s', '', NULL, NULL, NULL, 'अभी बुक करें', 'Репетиторы'),
(74, 'students', 'Student\'s', '', NULL, NULL, NULL, 'अभी बुक करें', 'Студенты'),
(75, 'pages', 'Pages', 'páginas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'страницы'),
(76, 'about_us', 'About Us', 'Sobre nosotros', NULL, NULL, NULL, 'हमारे बारे में', 'О нас'),
(77, 'how_it_works', 'How It Works', ' Cómo funciona', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Как это работает'),
(78, 'terms_and_conditions', 'Terms And Conditions', ' Términos y Condiciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Условия и положения'),
(79, 'privacy_policy', 'Privacy Policy', ' Política de privacidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'политика конфиденциальности'),
(80, 'dynamic_pages', 'Dynamic Pages', ' Las páginas dinámicas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Динамические страницы'),
(81, 'faqs', 'FAQ\'s', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Кинофильмы'),
(82, 'settings', 'Settings', ' ajustes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'настройки'),
(83, 'email_templates', 'Email Templates', 'Plantillas de correo electrónico', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Шаблоны электронных сообщений'),
(84, 'sitetestimonials', 'Sitetestimonials', ' Sitetestimonials', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отзывы по сайту'),
(85, 'list_testimonials', 'List Testimonials', ' lista Testimonios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Список Отзывы'),
(86, 'add_testimonial', 'Add Testimonial', 'Añadir un testimonio', NULL, NULL, NULL, ' प्रशंसापत्र जोड़ें', 'Add Testimonial'),
(87, 'language_settings', 'Language Settings', ' Ajustes de idioma', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Языковые настройки'),
(88, 'view_languages', 'View Languages', ' Ver Idiomas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Просмотреть Языки'),
(89, 'add_language', 'Add Language', 'Agregar idioma', NULL, NULL, NULL, ' भाषा जोड़े', 'Добавить язык'),
(90, 'view_phrases', 'View Phrases', ' Ver Frases', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Посмотреть Фразы'),
(91, 'view_tutor_languages', 'View Tutor Languages', 'Ver Tutor Idiomas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Просмотр Tutor Языки'),
(92, 'reports', 'Reports', ' Informes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Отчеты'),
(93, 'premium_users', 'Premium Users', 'Los usuarios premium', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Премиум пользователи'),
(94, 'my_profile', 'My Profile', 'Mi perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мой профайл'),
(95, 'change_password', 'Change Password', ' Cambia la contraseña', NULL, NULL, NULL, 'पासवर्ड बदलें', 'Изменить пароль'),
(96, 'home', 'Home', ' Casa', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Главная'),
(97, 'View', 'View', 'Ver', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Посмотреть'),
(98, 'Types for ', 'Types For ', 'Para tipos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Типы Для'),
(99, 'add_setting_field', 'Add Setting Field', 'Añadir configuración Campo', NULL, NULL, NULL, ' फील्ड सेटिंग जोड़ें', 'Добавить параметр Field'),
(100, 'Paypal_Email', 'Paypal Email', 'e-mail de Paypal', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Paypal Электронная почта'),
(101, 'Currency_Code', 'Currency Code', 'Código de moneda', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Код валюты'),
(102, 'Account_TypeProductionSandbox', 'Account TypeProductionSandbox', 'TypeProductionSandbox cuenta', NULL, NULL, NULL, 'खाते का प्रकार उत्पादन सैंडबॉक्स', 'Тип счета Sandbox Производство'),
(103, 'Header_Logo', 'Header Logo', ' El logotipo de cabecera', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Логотип заголовка'),
(104, 'Status', 'Status', ' Estado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Положение дел'),
(105, 'submit', 'Submit', ' Enviar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправить'),
(106, 'cancel', 'Cancel', 'Cancelar', NULL, NULL, NULL, 'रद्द करना', 'Отмена'),
(107, 'MSG_RECORD_UPDATED', 'MSG RECORD UPDATED', 'MSG registro actualizado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'MSG запись обновляется'),
(108, 'Profile Image', 'Profile Image', ' Imagen de perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Изображение профиля'),
(109, 'Describe your Experience and Services', 'Describe Your Experience And Services', ' Describe su experiencia y Servicios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Опишите Ваш опыт и услуги'),
(110, 'experience', 'Experience', ' Experiencia', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Опыт'),
(111, 'Profile Page Title', 'Profile Page Title', 'Perfil Título', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Профиль Название страницы'),
(112, 'How far are you willing to travel in Km', 'How Far Are You Willing To Travel? (in Km)', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Как далеко вы готовы путешествовать? (В км)'),
(113, 'willing_to_travel', 'Willing To Travel', 'Dispuesto a viajar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Желание путешествовать'),
(114, 'Do you have your own vehicle to travel', 'Do You Have Your Own Vehicle To Travel?', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Есть ли у вас собственный автомобиль путешествовать?'),
(115, 'Yes', 'Yes', ' Sí', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्सलेनदेन इतिहास क्रेडिट्स', 'да'),
(116, 'No', 'No', ' No', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Нет'),
(117, 'SAVE', 'SAVE', ' SALVAR', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' СПАСТИ'),
(118, 'Payment success Transaction Id ', 'Payment Success. Transaction Id ', '', NULL, NULL, NULL, 'अभी बुक करें', 'Оплата успеха. ID транзакции'),
(119, 'Credits', 'Credits', ' créditos', NULL, NULL, NULL, 'क्रेडिट्स', 'кредиты'),
(120, 'package_for', 'Package For', 'Por paquete', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пакет для'),
(121, 'edit', 'Edit', ' Editar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'редактировать'),
(122, 'course', 'Course', 'Curso', NULL, NULL, NULL, 'कोर्स', 'Курс'),
(123, 'sort_order', 'Sort Order', ' Orden de Clasificación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Порядок сортировки'),
(124, 'courses', 'Courses', ' cursos', NULL, NULL, NULL, 'पाठ्यक्रम', 'курсы'),
(125, 'add', 'Add', 'Añadir', NULL, NULL, NULL, 'जोड़ना', 'Добавить'),
(126, 'category', 'Category', ' Categoría', NULL, NULL, NULL, 'वर्ग', 'категория'),
(127, 'degree', 'Degree', 'La licenciatura', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'степень'),
(128, 'degrees', 'Degrees', ' grados', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'степени'),
(129, 'location', 'Location', ' Ubicación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Место нахождения'),
(130, 'locaitons', 'Locaitons', ' Locaitons', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Место проживания'),
(131, 'user', 'User', ' Usuario', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'пользователь'),
(132, 'institute', 'Institute', ' Instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'институт'),
(133, 'tutor', 'Tutor', ' Tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' репетитор'),
(134, 'First Name', 'First Name', ' Nombre de pila', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Имя'),
(135, 'first_name', 'First Name', ' Nombre de pila', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Имя'),
(136, 'Last Name', 'Last Name', ' Apellido', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Фамилия'),
(137, 'last_name', 'Last Name', ' Apellido', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Фамилия'),
(138, 'E-mail Address', 'E-mail Address', 'Dirección de correo electrónico', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Адрес электронной почты'),
(139, 'email', 'Email', 'Email', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Эл. адрес'),
(140, 'Phone', 'Phone', 'Teléfono', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Телефон'),
(141, 'code', 'Code', 'Código', NULL, NULL, NULL, 'कोड', 'Код'),
(142, 'Gender', 'Gender', ' Género', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пол'),
(143, 'Male', 'Male', 'Masculino', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'мужчина'),
(144, 'Female', 'Female', 'Hembra', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'женский'),
(145, 'Languages Known', 'Languages Known', ' Idiomas Conocido', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Языки Известные'),
(146, 'Year of Birth', 'Year Of Birth', ' Año de nacimiento', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Год рождения'),
(147, 'date_of_birth', 'Date Of Birth', ' Fecha de nacimiento', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Дата рождения'),
(148, 'Blog/Website', 'Blog/Website', ' Blog / Sitio Web', NULL, NULL, NULL, 'ब्लॉग / वेबसाइट', ' Блог / Сайт'),
(149, 'Facebook Profile', 'Facebook Profile', 'Perfil de Facebook', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'facebook профиля'),
(150, 'Facebook', 'Facebook', 'Facebook', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'facebook'),
(151, 'Twitter Profile', 'Twitter Profile', 'Twitter Perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Twitter Профиль'),
(152, 'Twitter', 'Twitter', ' Gorjeo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'щебетать'),
(153, 'Linkedin Profile', 'Linkedin Profile', ' Perfil de Linkedin', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Linkedin Профиль'),
(154, 'Linkedin', 'Linkedin', ' Linkedin', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Linkedin'),
(155, 'UPDATE', 'UPDATE', 'ACTUALIZAR', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'ОБНОВИТЬ'),
(156, 'You have cancelled your transaction', 'You Have Cancelled Your Transaction', ' Usted ha cancelado su transacción', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Вы отменили мою Сделка'),
(157, 'Live_Merchant_Key', 'Live Merchant Key', ' Clave de comerciante en directo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Живой Ключ Торговец'),
(158, 'Live_Salt', 'Live Salt', ' sal vivo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Живая соль'),
(159, 'Live_URL', 'Live URL', 'URL en vivo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Живой URL'),
(160, 'Sandbox_Merchant_Key', 'Sandbox Merchant Key', ' Zona de pruebas clave de comerciante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Sandbox Ключ продавца'),
(161, 'Sandbox_Salt', 'Sandbox Salt', ' sal caja de arena', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Песочница Соль'),
(162, 'Test_URL', 'Test URL', ' URL de prueba', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Тестирование URL'),
(163, 'Account_TypeLIveSandbox', 'Account TypeLIveSandbox', 'TypeLIveSandbox cuenta', NULL, NULL, NULL, ' खाते का प्रकार रहते सैंडबॉक्स', 'TypeLIveSandbox Счет'),
(164, 'subscriptions', 'Subscriptions', 'Suscripciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Подписки'),
(165, 'My Subscriptions', 'My Subscriptions', ' mis Suscripciónes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мои подписки'),
(166, 'Privacy', 'Privacy', ' Intimidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Конфиденциальность'),
(167, 'all_courses', 'All Courses', 'todos los cursos', NULL, NULL, NULL, 'सभी पाठ्यक्रम', 'Все курсы'),
(168, 'courses_in', 'Courses In', 'En cursos', NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'Курсы В'),
(169, 'Free Demo', 'Free Demo', ' Prueba gratis', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Бесплатный Демо'),
(170, 'Visibility in Search', 'Visibility In Search', ' Visibilidad En busca', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Видимость В поисках'),
(171, 'Set Privacy', 'Set Privacy', ' conjunto de privacidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Набор конфиденциальности'),
(172, 'Show All', 'Show All', ' Mostrar todo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Показать все'),
(173, 'Show Email', 'Show Email', ' Ver el email', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Показать E-mail'),
(174, 'Show Mobile', 'Show Mobile', ' Mostrar Mobile', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Показать Мобильный'),
(176, 'Availability', 'Availability', 'Disponibilidad', NULL, NULL, NULL, 'उपलब्धता', 'Доступность'),
(177, 'other', 'Other', ' Otro', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Другие'),
(178, 'Add More', 'Add More', ' Añadir más', NULL, NULL, NULL, 'अधिक जोड़ें', 'Добавить больше'),
(179, 'University', 'University', ' Universidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Университет'),
(180, 'Address', 'Address', 'Dirección', NULL, NULL, NULL, ' पता', 'Адрес'),
(181, 'Year', 'Year', ' Año', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Год'),
(182, 'are you sure', 'Are You Sure?', '', NULL, NULL, NULL, ' छात्र', 'Ты уверен?'),
(183, 'delete', 'Delete', 'Borrar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Удалить'),
(184, 'privacy updated successfully', 'Privacy Updated Successfully', ' Privacidad Actualizado con éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Конфиденциальность успешно обновлен'),
(185, 'Manage Privacy', 'Manage Privacy', 'Manejo de privacidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Управление уровнем конфиденциальности'),
(186, 'My Gallery', 'My Gallery', 'Mi galería', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Моя галерея'),
(187, 'no_fields_yet_added', 'No Fields Yet Added', ' Sin embargo Añadido campos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Нет Поля Еще Добавлено'),
(188, 'Site_Title', 'Site Title', ' Título del sitio', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Название сайта'),
(189, 'City', 'City', ' Ciudad', NULL, NULL, NULL, 'शहर', ' город'),
(190, 'State', 'State', ' Estado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'состояние'),
(191, 'Country', 'Country', ' País', NULL, NULL, NULL, 'देश', 'Страна'),
(192, 'Zipcode', 'Zipcode', ' Código postal', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(193, 'Rights_Reserved_by', 'Rights Reserved By', 'Por los derechos reservados', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Права защищены'),
(194, 'Land_Line', 'Land Line', ' Línea de tierra', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'стационарный'),
(195, 'Fax', 'Fax', ' Fax', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'факс'),
(196, 'Portal_Email', 'Portal Email', ' portal de correo electrónico', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Портал E-mail'),
(197, 'Designed_By', 'Designed By', 'Diseñada por', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Разработано'),
(198, 'URL', 'URL', 'URL', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' URL'),
(199, 'Logo', 'Logo', ' Logo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'логотип'),
(200, 'Country_code', 'Country Code', 'Código de país', NULL, NULL, NULL, 'देश कोड', 'Код страны'),
(201, 'Default_Language', 'Default Language', ' Idioma predeterminado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Язык по умолчанию'),
(202, 'System_Type', 'System Type', ' Tipo de sistema', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Тип системы'),
(203, 'Site_Slogan', 'Site Slogan', ' lema del sitio', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Слоган сайта'),
(204, 'Google_Adsense_Header', 'Google Adsense Header', ' Cabecera Google Adsense', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Google Adsense Заголовок'),
(205, 'Google_Adsense_Block', 'Google Adsense Block', ' Google Adsense Bloque', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Google Adsense Блок'),
(206, 'adsense_home1', 'Adsense Home1', 'Adsense Home1', NULL, NULL, NULL, ' ऐडसेंस होम 1', 'Adsense home1'),
(207, 'adsense_home2', 'Adsense Home2', ' Adsense Home2', NULL, NULL, NULL, ' ऐडसेंस होम 2', 'Adsense Home2'),
(208, 'adsense_home3', 'Adsense Home3', ' Adsense Home3', NULL, NULL, NULL, ' ऐडसेंस होम 3', 'Adsense home3'),
(209, 'Logout', 'Logout', ' Cerrar sesión', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выйти'),
(210, 'About Us', 'About Us', ' Sobre nosotros', NULL, NULL, NULL, ' हमारे बारे में', 'О нас'),
(211, 'Find Your Course', 'Find Your Course', ' Encuentra tu curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Найди свой курс'),
(212, 'Feel free to call us anytime', 'Feel Free To Call Us Anytime', 'Llame a uno de nosotros en cualquier momento', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Не стесняйтесь звонить нам в любое время'),
(213, 'Find Leads', 'Find Leads', ' Encuentra Ventas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Найти потенциальных'),
(214, 'Find Courses', 'Find Courses', ' Busca el curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Найти курсы'),
(215, 'View All', 'View All', ' Ver todo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Посмотреть все'),
(216, 'Our Blog', 'Our Blog', ' Nuestro blog', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Наш блог'),
(217, 'Blog', 'Blog', 'Blog', NULL, NULL, NULL, 'ब्लॉग', 'Блог'),
(218, 'Blog Post', 'Blog Post', 'Entrada en el blog', NULL, NULL, NULL, 'ब्लॉग पोस्ट', 'Сообщение блога'),
(219, 'Contact Us', 'Contact Us', 'Contáctenos', NULL, NULL, NULL, 'हमसे संपर्क करें', 'Свяжитесь с нами'),
(220, 'Login', 'Login', ' Iniciar sesión', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Авторизоваться'),
(221, 'Call', 'Call', ' Llamada', NULL, NULL, NULL, 'पु का र ना', 'Вызов'),
(222, 'Find Tutor', 'Find Tutor', ' Encuentra tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Найти Tutor'),
(223, 'Or', 'Or', 'O', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'или'),
(224, 'Register', 'Register', 'Registro', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' регистр'),
(225, 'Need help finding a tutor', 'Need Help Finding A Tutor?', '', NULL, NULL, NULL, 'अभी बुक करें', 'Нужна помощь в поиске репетитора?'),
(226, 'Post Your Requirement', 'Post Your Requirement', ' Publique su requisito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Сообщение ваше требование'),
(227, 'Get to Know Us', 'Get To Know Us', ' Conocernos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Узнайте о нас'),
(228, 'Search for a Tutor', 'Search For A Tutor', ' Para buscar un tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Поиск репетитора'),
(229, 'Search for a Student', 'Search For A Student', ' Búsqueda de un Estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выполните поиск Student'),
(230, 'Become a Tutor', 'Become A Tutor', ' Convertirse en un tutor', NULL, NULL, NULL, 'एक अध्यापक बनें', 'Стать репетитором'),
(231, 'tutors by location', 'Tutors By Location', ' Los tutores Por país de residencia', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Репетиторы По местонахождению'),
(232, 'tutors by course', 'Tutors By Course', ' Por tutores del curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Репетиторы по курсу'),
(233, 'meet the team', 'Meet The Team', 'Conocer al equipo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Встретить команду'),
(234, 'Fields for ', 'Fields For ', 'Para campos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Поля для'),
(235, 'Fields', 'Fields', 'Campos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'поля'),
(236, 'insert_validation', 'Insert Validation', ' Insertar Validación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Вставить проверку'),
(237, 'insert', 'Insert', 'Insertar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Вставить'),
(238, 'ajax_list_info', 'Ajax List Info', 'Ajax Información de la lista', NULL, NULL, NULL, ' अजाक्स सूची जानकारी', 'Ajax Список информация'),
(239, 'ajax_list', 'Ajax List', 'Lista Ajax', NULL, NULL, NULL, 'अजाक्स सूची', 'Список Ajax'),
(240, 'Androd_App', 'Androd App', 'Androd App', NULL, NULL, NULL, 'एंड्रॉयड ऐप', 'Android App'),
(241, 'iOS_App', 'IOS App', ' IOS App', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' приложение IOS'),
(242, 'update_validation', 'Update Validation', ' actualización de Validación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Обновление проверки'),
(243, 'Need help finding a student', 'Need Help Finding A Student?', '', NULL, NULL, NULL, 'अभी बुक करें', 'Нужна помощь в поиске студент?'),
(244, 'Find Student Leads', 'Find Student Leads', ' Encuentra Ventas Estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Найти Student потенциальных'),
(245, 'Google Play', 'Google Play', 'Google Play', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Гугл игры'),
(246, 'Find a tutor fast', 'Find A Tutor Fast', 'Encontrar un tutor rápida', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Найти репетитор быстро'),
(247, 'Get our app', 'Get Our App', ' Obtener Nuestra App', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Получить наше приложение'),
(248, 'Send a download link to your mail', 'Send A Download Link To Your Mail', ' Enviar un Enlace de descarga a su correo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправить ссылку для загрузки на Ваш почтовый'),
(249, 'MailType_WebmailMandrill', 'MailType WebmailMandrill', ' MailType WebmailMandrill', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Почта Тип Почтовый веб-Mandrill'),
(250, 'SMTP_Host', 'SMTP Host', ' Host SMTP', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'SMTP хост'),
(251, 'SMTP_User', 'SMTP User', ' usuario SMTP', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'SMTP Пользователь'),
(252, 'SMTP_Password', 'SMTP Password', ' Contraseña SMTP', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пароль SMTP'),
(253, 'SMTP_Port', 'SMTP Port', 'Puerto SMTP', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Порт SMTP'),
(254, 'Mandrill_API_Key', 'Mandrill API Key', ' Mandril clave de API', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'мандрил ключ апи'),
(255, 'settings_no_aloowed', 'Settings No Aloowed', ' Sin ajustes Aloowed', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Настройки не разрешены'),
(256, 'sheekay', 'Sheekay', 'Sheekay', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Sheekay'),
(257, 'sheeka', 'Sheeka', ' Sheeka', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Sheeka'),
(258, 'template', 'Template', ' Modelo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'шаблон'),
(259, 'Make Default', 'Make Default', ' Hacer por defecto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Использовать по умолчанию'),
(260, 'View Values', 'View Values', 'Ver Valores', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотр значений'),
(261, 'View Fields', 'View Fields', 'Ver más campos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Просмотр поля'),
(262, 'Host', 'Host', 'Anfitrión', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'хозяин'),
(263, 'Port', 'Port', ' Puerto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' порт'),
(264, 'User_Name', 'User Name', ' Nombre de usuario', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Имя пользователя'),
(265, 'Mandril_API_Key', 'Mandril API Key', ' Mandril clave de API', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'мандрил ключ апи'),
(266, 'We have already sent link', 'We Have Already Sent Link', ' Tenemos ya ha sido enviada Enlace', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мы уже отправили ссылку'),
(267, 'Your links for our app', 'Your Links For Our App', ' Sus Enlaces Para nuestra aplicación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Ваши ссылки для нашего приложения'),
(268, 'We have sent email link to given address', 'We Have Sent Email Link To Given Address', ' Hemos mandado Correo Enlace con la dirección Dada', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мы отправили Отправить ссылку на данный адрес'),
(269, 'Team', 'Team', ' Equipo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'команда'),
(270, 'add_team', 'Add Team', ' Añadir equipo', NULL, NULL, NULL, ' टीम जोड़े', 'Добавить команду'),
(271, 'list_team', 'List Team', ' lista de equipo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список команд'),
(272, 'Sign In', 'Sign In', ' Registrarse', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Войти в систему'),
(273, 'With Your Account', 'With Your Account', ' Con su cuenta', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'С Вашего счета'),
(274, 'Username / Email address', 'Username / Email Address', 'Nombre de usuario / Dirección de correo electrónico', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Имя пользователя / E-mail адрес'),
(275, 'Forgot your password', 'Forgot Your Password?', '', NULL, NULL, NULL, 'अभी बुक करें', 'Забыли пароль?'),
(276, 'Remember Me', 'Remember Me', 'Recuérdame', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Запомни меня'),
(277, 'Sign in with', 'Sign In With', ' Inicia sesión con', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Войти в систему с'),
(278, 'Faceook', 'Faceook', 'faceook', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'facebook'),
(279, 'With Tutors', 'With Tutors', 'con tutores', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' с репетиторами'),
(280, 'must be at least', 'Must Be At Least', ' Al menos debe ser', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Должен быть не менее'),
(281, 'characters', 'Characters', ' Caracteres', NULL, NULL, NULL, 'वर्ण', 'Персонажи'),
(282, 'pin_code', 'Pin Code', 'Código PIN', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пин-код'),
(283, 'Phone Number', 'Phone Number', ' Número de teléfono', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Номер телефона'),
(284, 'Create an Account', 'Create An Account', 'Crea una cuenta', NULL, NULL, NULL, ' खाता बनाएं', 'Завести аккаунт'),
(285, 'Confirm Password', 'Confirm Password', ' Confirmar contraseña', NULL, NULL, NULL, 'पासवर्ड की पुष्टि कीजिये', ' Подтвердите Пароль'),
(286, 'confirm_password', 'Confirm Password', ' Confirmar contraseña', NULL, NULL, NULL, 'पासवर्ड की पुष्टि कीजिये', ' Подтвердите Пароль'),
(287, 'first_name', 'First  Name', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Имя'),
(288, 'registration_completed_successfully_activation_email_sent', 'Registration Completed Successfully Activation Email Sent', ' Registro completado con éxito la activación Correo electrónico enviado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Регистрация успешно завершена Активация E-mail Отправлено'),
(289, 'Register As', 'Register As', 'Como registro', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Регистрация Как'),
(290, 'Please select group', 'Please Select Group', 'Por favor seleccione Grupo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите группу'),
(291, 'Group', 'Group', ' Grupo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'группа'),
(292, 'your_account_activated_successfully_please_login', 'Your Account Activated Successfully Please Login', ' Su cuenta activado con éxito favor Ingresa', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(293, 'unable_to_login_please_try_again', 'Unable To Login Please Try Again', ' No es posible entrar Inténtelo de nuevo más', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Не могу войти Пожалуйста, попробуйте еще раз'),
(294, 'Made with', 'Made With', ' Hecho con', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Сделано с'),
(295, 'certificates', 'Certificates', ' certificados', NULL, NULL, NULL, 'प्रमाण पत्र', 'Сертификаты'),
(296, 'list_certificates', 'List Certificates', ' Certificados de la lista', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список сертификатов'),
(297, 'add_certificate', 'Add Certificate', ' Agregar certificado', NULL, NULL, NULL, ' प्रमाणपत्र जोड़ें', ' Добавить сертификат'),
(298, 'Show_Team', 'Show Team', 'Mostrar Equipo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Показать команды'),
(299, 'Need_admin_approval_for_tutor', 'Need Admin Approval For Tutor', 'Necesidad de aprobación de administrador Para tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Необходимость одобрения администратором Для воспитателя'),
(300, 'Please upload following certificates to procede', 'Please Upload Following Certificates To Procede', 'Por favor Sube Certificados siguiente para el Procede', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, Вы можете добавить следующие сертификаты Чтобы продолжить'),
(301, 'please select country', 'Please Select Country', ' Por favor seleccione su país', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, выберите страну'),
(302, 'Landmark', 'Landmark', ' Punto de referencia', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'веха'),
(303, 'Pincode', 'Pincode', ' Código PIN', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пин-код'),
(304, 'Type of Classes', 'Type Of Classes', ' Tipo de las clases', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Тип классов'),
(305, 'Academic', 'Academic', 'Académico', NULL, NULL, NULL, 'एकेडमिक', 'академический'),
(306, 'Non-academic', 'Non-academic', 'No docente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Неакадемический'),
(307, 'Share my phone number with customers who are looking for classes I conduct', 'Share My Phone Number With Customers Who Are Looking For Classes I Conduct', ' Comparte Mi número de teléfono con los clientes que están en busca de las clases I Conducta', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Доля мой номер телефона с клиентами, которые ищут классов I поведения'),
(308, 'no_details_available', 'No Details Available', ' No hay datos disponibles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Нет Подробности Доступные'),
(309, 'Teaching Types', 'Teaching Types', ' Tipos de enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Виды обучения'),
(310, 'Certificates uploaded successfully', 'Certificates Uploaded Successfully', ' Certificados cargado correctamente', NULL, NULL, NULL, 'प्रमाण पत्र सफलतापूर्वक अपलोड', ' Сертификаты Загружено Успешно'),
(311, 'online_now', 'Online Now', ' En línea ahora', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Сейчас в сети'),
(312, 'offline_now', 'Offline Now', ' Ahora Desconectado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Сейчас на форуме'),
(313, 'qualification', 'Qualification', ' Calificación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Квалификация'),
(314, 'language_of_teaching', 'Language Of Teaching', ' Lengua docente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Язык преподавания'),
(315, 'years', 'Years', ' Años', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' лет'),
(316, 'more_about_me', 'More About Me', ' Más sobre mí', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Больше обо мне'),
(317, 'tutoring_courses', 'Tutoring Courses', ' Cursos de tutoría', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Обучение и курсы'),
(318, 'tutoring_locations', 'Tutoring Locations', ' Ubicaciones de tutoría', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Репетиторство Местоположение'),
(319, 'career_experience', 'Career Experience', ' carrera Experiencia', NULL, NULL, NULL, ' कैरियर के अनुभव', ' Трудовая деятельность'),
(320, 'i_love_tutoring_because', 'I Love Tutoring Because', ' Amo Tutoría Debido', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'I Love Репетиторство Потому что'),
(321, 'other_interests', 'Other Interests', ' otros intereses', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Другие интересы'),
(322, 'my_experience', 'My Experience', ' Mi experiencia', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мой опыт'),
(323, 'reserve_your_spot', 'Reserve Your Spot', ' Reserve su lugar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Забронировать место'),
(324, 'start_date', 'Start Date', ' Fecha de inicio', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Дата начала'),
(325, 'no_slots_available', 'No Slots Available.', '', NULL, NULL, NULL, 'अभी बुक करें', 'Нет доступных слотов.'),
(326, 'send_me_your_message', 'Send Me Your Message', ' Enviarme Su Mensaje', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пришлите мне Ваше сообщение'),
(327, 'request_this_tutor', 'Request This Tutor', ' Este solicitar tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запрос этот репетитор'),
(328, 'student', 'Student', ' Estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Студент'),
(329, 'please_select_location', 'Please Select Location', ' Por favor seleccione la ubicación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите Расположение'),
(330, 'please_select_course', 'Please Select Course', ' Por favor seleccione de golf', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите курс'),
(331, 'click_here_to_send_me_your_message', 'Click Here To Send Me Your Message', ' Haga clic aquí para enviar su mensaje de mí', NULL, NULL, NULL, 'यहाँ क्लिक करें मुझे अपने संदेश भेजने के लिए', ' Нажмите здесь, чтобы послать мне Ваше сообщение'),
(332, 'course_content', 'Course Content', ' Contenido del curso', NULL, NULL, NULL, 'अध्य्यन विषयवस्तु', 'Содержание курса'),
(333, 'invalid_request', 'Invalid Request', ' Solicitud no válida', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'неверный запрос'),
(334, 'please_login_to_book_tutor', 'Please Login To Book Tutor', 'Inicia sesión para reservar tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, войдите забронировать репетитор'),
(335, 'select_a_course', 'Select A Course', ' Seleccione un curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите трассу'),
(336, 'select_preferred_location', 'Select Preferred Location', ' Seleccione Favorito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите Предпочтительная местность'),
(337, 'select_date', 'Select Date', ' Seleccione fecha', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите дату'),
(338, 'select_time-slot', 'Select Time-slot', ' Elija un intervalo de tiempo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выбор временного интервала'),
(339, 'please_select_course_first', 'Please Select Course First', ' Por favor seleccione Primer Curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите курс Первый'),
(340, 'write_your_message', 'Write Your Message', ' Escribe tu mensaje', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Написать Ваше сообщение'),
(341, 'please_select_date_on_which_you_want_to_start_the_course', 'Please Select Date,on Which You Want To Start The Course', '', NULL, NULL, NULL, 'अभी बुक करें', 'Пожалуйста, выберите дату, на котором вы хотите, чтобы начать курс'),
(342, 'please_select_time_slot', 'Please Select Time Slot', ' Por favor seleccione Time Slot', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, выберите временной интервал'),
(343, 'Sub Location for ', 'Sub Location For ', 'Sub Lugar Para', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Расположение к югу Для'),
(344, 'read', 'Read', ' Leer', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Читать'),
(345, 'testimonial', 'Testimonial', ' Testimonial', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'свидетельский'),
(346, 'please_login_to_purchase', 'Please Login To Purchase', ' Inicia sesión para Compra', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, войдите, чтобы купить'),
(347, 'print', 'Print', ' Impresión', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Распечатать'),
(348, 'please_select_course_and_date_first', 'Please Select Course And Date First', ' Por favor seleccione curso y la fecha Primero', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите курс и даты первых'),
(349, 'your_slot_with_the_tutor_booked_successfully', 'Your Slot With The Tutor Booked Successfully', ' Su ranura con el tutor de reserva con éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(350, 'time_slot_not_available', 'Time Slot Not Available', ' El intervalo de tiempo no disponible', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Время Слот Не Доступный'),
(351, 'Current Password', 'Current Password', ' contraseña actual', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'текущий пароль'),
(352, 'New Password', 'New Password', ' nueva contraseña', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'новый пароль'),
(353, 'please_become_premium_member_to_book_tutor', 'Please Become Premium Member To Book Tutor', 'Por favor Hágase Miembro Premium Para tutor libro', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста Стать Премиум Участник Чтобы забронировать самостоятельно'),
(354, 'experience_desc', 'Experience Desc', ' experiencia descripción', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Опыт Описание изделия'),
(355, 'profile_page_title', 'Profile Page Title', 'Perfil Título', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Профиль Название страницы'),
(356, 'profile updated successfully', 'Profile Updated Successfully', ' Perfil actualizado con éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Профиль успешно обновлен'),
(357, 'phone_code', 'Phone Code', ' Código de teléfono', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Телефонный код'),
(358, 'land_mark', 'Land Mark', ' Punto de referencia', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'веха'),
(359, 'record updated successfully', 'Record Updated Successfully', ' Registro actualizado con éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Запись успешно обновлена'),
(360, 'My Address', 'My Address', ' Mi dirección', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мой адрес'),
(361, 'password_changed_successfully', 'Password Changed Successfully', ' Contraseña cambiada con éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пароль успешно изменен'),
(362, 'old_password_is_wrong', 'Old Password Is Wrong', ' Vieja contraseña es incorrecta', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Старый неверный пароль'),
(363, 'Skype Profile', 'Skype Profile', ' Perfil de Skype', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'скайп Профиль'),
(364, 'Skype', 'Skype', 'Skype', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' скайп'),
(365, 'send_message', 'Send Message', ' Enviar mensaje', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправить сообщение'),
(366, 'Personal Information', 'Personal Information', ' Informacion personal', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Личная информация'),
(369, 'record added successfully', 'Record Added Successfully', ' Registro añadido correctamente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Запись Успешно добавлен'),
(370, 'record deleted successfully', 'Record Deleted Successfully', ' Registro eliminado con éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запись успешно удалена'),
(371, 'Prefferd Teaching Type', 'Prefferd Teaching Type', ' Prefferd Tipo Enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предпочтительный тип преподавания'),
(372, 'updated_successfully', 'Updated Successfully', ' Actualizado correctamente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Успешно Обновлено'),
(373, 'you_have_not_done_any_changes', 'You Have Not Done Any Changes', ' No lo ha hecho ningún Cambios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(374, 'updated successfully', 'Updated Successfully', ' Actualizado correctamente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Успешно Обновлено'),
(375, 'Preffered Teaching Type', 'Preffered Teaching Type', ' Preffered Tipo Enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предпочтительный тип преподавания'),
(376, 'Welcome back', 'Welcome Back', ' Dar una buena acogida', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Добро пожаловать назад'),
(377, 'Student Leads', 'Student Leads', 'Ventas de estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Студенческие снабжении'),
(378, 'student_leads', 'Student Leads', 'Ventas de estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Студенческие снабжении'),
(379, 'Post Requirement', 'Post Requirement', ' Requisito de post', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'сообщение Требование'),
(380, 'please select locaton', 'Please Select Locaton', ' Por favor seleccione Locaton', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите Расположение'),
(381, 'please select location', 'Please Select Location', ' Por favor seleccione la ubicación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите Расположение'),
(382, 'Tutoring Courses', 'Tutoring Courses', ' Cursos de tutoría', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Обучение и курсы'),
(383, 'Present Status of you', 'Present Status Of You', ' Estado actual de usted', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Современное состояние Вас'),
(384, 'present_status', 'Present Status', ' Estado actual', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Современное состояние'),
(385, 'Priority of Requirement', 'Priority Of Requirement', ' Prioridad de las necesidades', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Приоритет потребности'),
(386, 'Immediately', 'Immediately', ' Inmediatamente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Немедленно'),
(387, 'After a Week', 'After A Week', ' Despues de una semana', NULL, NULL, NULL, ' एक हफ्ते के बाद', 'После недели'),
(388, 'After A Month', 'After A Month', ' Despues de un mes', NULL, NULL, NULL, 'एक महीने के बाद', 'Через месяц'),
(389, 'Course you want to complete in', 'Course You Want To Complete In', 'Por supuesto que desea completar En', NULL, NULL, NULL, 'बेशक आप में प्रतिस्पर्धा करना चाहता हूँ', 'Конечно, вы хотите, чтобы конкурировать в'),
(390, 'duration_needed', 'Duration Needed', ' duración necesaria', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Продолжительность Необходимое'),
(391, 'Amount You can pay', 'Amount You Can Pay', ' Cantidad que usted puede pagar', NULL, NULL, NULL, ' राशि है जो आप भुगतान कर सकते हैं', 'Сумма Вы можете оплатить'),
(392, 'budget', 'Budget', ' Presupuesto', NULL, NULL, NULL, 'बजट', 'бюджет'),
(393, 'Payment Type', 'Payment Type', ' Tipo de pago', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Способ оплаты'),
(394, 'One Time', 'One Time', ' Una vez', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Один раз'),
(395, 'Hourly', 'Hourly', ' Cada hora', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' почасовой'),
(396, 'Month', 'Month', 'Mes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Месяц'),
(397, 'Requirement Details', 'Requirement Details', ' requisito detalles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Требование подробности'),
(398, 'requirement_details', 'Requirement Details', ' requisito detalles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Требование подробности'),
(399, 'You Need Tutor of Course', 'You Need Tutor Of Course', ' Que necesita tutor del Curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Вам нужен репетитор для гольфа'),
(400, 'title_of_requirement', 'Title Of Requirement', ' De la exigencia del título', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Название потребности');
INSERT INTO `pre_languagewords` (`lang_id`, `lang_key`, `english`, `spanish`, `bengali`, `french`, `japanese`, `hindi`, `russian`) VALUES
(401, 'present_status_ex:student or employee', 'Present Status Ex:student Or Employee', 'Estado actual Ej: estudiante o empleado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Современное состояние Ex: студент или сотрудник'),
(402, 'duration_needed_to_complete_course', 'Duration Needed To Complete Course', ' Duración del curso necesario para completar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Продолжительность, необходимое для завершения курса'),
(403, 'Monthly', 'Monthly', 'Mensual', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'ежемесячно'),
(404, 'location_id', 'Location Id', 'Ubicación Id', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Расположение Id'),
(405, 'course_id', 'Course Id', ' Id curso', NULL, NULL, NULL, 'पाठ्यक्रम आईडी', 'Id курс'),
(406, 'teaching_type_id', 'Teaching Type Id', ' ID del tipo de la enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Обучение Идентификатор типа'),
(407, 'priority_of_requirement', 'Priority Of Requirement', ' Prioridad de las necesidades', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Приоритет потребности'),
(408, 'teaching_type', 'Teaching Type', ' Tipo de la enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Обучение Тип'),
(409, 'tutory type', 'Tutory Type', ' Tipo Tutory', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'учебник Тип'),
(410, 'view_language', 'View Language', ' Ver idioma', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотр Язык:'),
(411, 'language', 'Language', ' Idioma', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'язык'),
(412, 'operations', 'Operations', 'operaciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'операции'),
(413, 'Add Language Words', 'Add Language Words', ' Añadir palabras de la lengua', NULL, NULL, NULL, 'भाषा के शब्दों को जोड़े', 'Добавить слов языка'),
(414, 'add_phrases', 'Add Phrases', ' Añadir frases', NULL, NULL, NULL, 'वाक्यांश जोड़', 'Добавить фразы'),
(415, 'phrase', 'Phrase', ' Frase', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Фраза'),
(416, 'Total Leads', 'Total Leads', ' Ventas totales', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Всего Ведет'),
(417, 'Manage Tutoring Courses', 'Manage Tutoring Courses', ' Manejo de cursos de clases particulares', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Управление Репетиторство Курсы'),
(418, 'Premium Leads', 'Premium Leads', ' Ventas de primera calidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Премиум Ведет'),
(419, 'Status closed Leads', 'Status Closed Leads', ' Ventas Estado cerrado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Статус Закрыто Ведет'),
(420, 'Status Opened Leads', 'Status Opened Leads', ' Ventas de estado abierto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Статус Открыт Ведет'),
(421, 'Reviews', 'Reviews', 'opiniones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Отзывы'),
(422, 'select_course', 'Select Course', ' Seleccionar Curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите курс'),
(423, 'student_reviews', 'Student Reviews', ' Comentarios de Estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Студенческие Отзывы'),
(424, 'net_credits', 'Net Credits', ' Los créditos netos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Чистые кредиты'),
(425, 'my_subscriptions', 'My Subscriptions', ' mis Suscripciónes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мои подписки'),
(426, 'course_fee_in_credits', 'Course Fee In Credits', 'Costo del curso En Créditos', NULL, NULL, NULL, 'क्रेडिट्स में पाठ्यक्रम शुल्क', ' Стоимость курсов в кредитах'),
(427, 'course_name', 'Course Name', 'Nombre del curso', NULL, NULL, NULL, 'कोर्स का नाम', ' Название курса'),
(429, 'fee', 'Fee', 'Cuota', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'плата'),
(430, 'please_select_atleast_one_preferred_location', 'Please Select Atleast One Preferred Location', 'Por favor seleccione Al menos uno Favorito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, выберите Atleast Один предпочтительный Расположение'),
(431, 'please_select_atleast_one_preferred_course', 'Please Select Atleast One Preferred Course', 'Por favor seleccione Al menos una vía preferida', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите Atleast Один предпочтительный курс'),
(432, 'not_authorized', 'Not Authorized', 'No autorizado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Не авторизован'),
(433, 'per_credit_value', 'Per Credit Value', ' Por valor de crédito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' За значение кредита'),
(436, 'Wrong operation', 'Wrong Operation', ' Funcionamiento incorrecto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Неправильный Операция'),
(437, 'please_login_to_access_this_page', 'Please Login To Access This Page', 'Por favor regístrate para acceder a la página', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, войдите Для доступа к этой странице'),
(438, 'Batches', 'Batches', ' lotes', NULL, NULL, NULL, 'बैचों', 'Порции'),
(439, 'institue_courses', 'Institue Courses', ' Cursos Institue', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Курсы Институт'),
(440, 'student_enquiries', 'Student Enquiries', ' Las consultas estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Студенческие запросы'),
(441, 'You have not done any changes', 'You Have Not Done Any Changes', ' No lo ha hecho ningún Cambios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'вы еще не сделали каких-либо изменений'),
(442, 'institute_batches', 'Institute Batches', ' Instituto lotes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Институт Порции'),
(443, 'select_tutors', 'Select Tutors', ' Seleccione tutores', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите репетиторы'),
(444, 'student_name', 'Student Name', ' Nombre del estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Имя ученика'),
(445, 'preferred_commence_date', 'Preferred Commence Date', ' Comenzar preferido Fecha', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предпочтительные Commence Дата'),
(446, 'course_seeking', 'Course Seeking', ' Buscando por supuesto', NULL, NULL, NULL, 'कोर्स की मांग', 'Знакомлюсь курс'),
(447, 'admin_commission_percentage', 'Admin Commission Percentage  (in Credits)', ' Porcentaje Comisión de administración (en créditos)', NULL, NULL, NULL, ' व्यवस्थापक आयोग प्रतिशत (क्रेडिट्स में)', ' Комиссия администратора в процентах (в кредитах)'),
(449, 'pending', 'Pending', 'Pendiente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'в ожидании'),
(450, 'update_status_as', 'Update Status As', 'Actualización del estado de Como', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Состояние обновления Как'),
(451, 'the_batch_you_are_trying_to_add_is_already_exists', 'The Batch You Are Trying To Add Is Already Exists', ' El lote que está tratando de añadir es ya existe', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пакетная Вы пытаетесь добавить уже Exists'),
(452, 'Enrolled_students_list', 'Enrolled Students List', ' Lista de los estudiantes matriculados', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Поступил Список студентов'),
(453, 'inst_enrolled_students', 'Inst Enrolled Students', ' Inst Matriculados Estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Inst зачисленных студентов'),
(454, 'Enrolled Students List', 'Enrolled Students List', 'Lista de los estudiantes matriculados', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Поступил Список студентов'),
(455, 'course_duration', 'Course Duration', ' Duración del curso', NULL, NULL, NULL, 'पाठ्यक्रम की अवधि', 'Длительность курса'),
(456, 'tutor_name', 'Tutor Name', 'Nombre tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Репетитор Имя'),
(457, 'Students List', 'Students List', ' Lista de los estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Студенты Список'),
(458, 'students_list', 'Students List', ' Lista de los estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Студенты Список'),
(459, 'Sbumit', 'Sbumit', ' Sbumit', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправить'),
(460, 'initiate_session', 'Initiate Session', 'iniciar Sesión', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Начать сеанс'),
(461, 'change_status', 'Change Status', ' Cambiar Estado', NULL, NULL, NULL, 'अवस्था बदलो', 'Изменить статус'),
(462, 'approve', 'Approve', ' Aprobar', NULL, NULL, NULL, 'मंजूर', 'Одобрить'),
(463, 'duration', 'Duration', ' Duración', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'продолжительность'),
(464, 'select_batch', 'Select Batch', 'Seleccione lote', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите Пакетное'),
(465, 'no_batches_available', 'No Batches Available', 'No hay lotes disponibles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Нет Порции Доступные'),
(466, 'enrolled_students', 'Enrolled Students', ' Los estudiantes inscritos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'зачисленных студентов'),
(467, 'my_batches', 'My Batches', ' Mis lotes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мои Порции'),
(468, 'batches_list', 'Batches List', ' Lista de lotes', NULL, NULL, NULL, 'बैचों सूची', 'Список Порции'),
(469, 'select_course_offering_location', 'Select Course Offering Location', ' Seleccionar Curso de Oferta Ubicación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Выберите предложение курса Расположение'),
(470, 'Need_admin_approval_for_tutor_certificates', 'Need Admin Approval For Tutor Certificates', ' Necesidad de aprobación de administrador para los certificados de los tutores', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Необходимость одобрения администратором Для преподавателем сертификации'),
(471, 'Admin_Commission_For_A_Booking_', 'Admin Commission For A Booking ', ' Comisión de administración para una reserva', NULL, NULL, NULL, ' आरक्षण के लिए व्यवस्थापक आयोग', 'Администратор Комиссия при бронировании'),
(472, 'Admin_Intervention_Charges_in_credits', 'Admin Intervention Charges In Credits', ' Cargos de la intervención del administrador de creditos', NULL, NULL, NULL, ' व्यवस्थापक हस्तक्षेप प्रभार क्रेडिट्स में', 'Действия администратора Вмешательство Обвинения в кредитах'),
(473, 'Minimum_Credits_for_Premium_Tutor', 'Minimum Credits For Premium Tutor', ' Créditos mínimos Paid tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Минимальные кредиты для премиум самостоятельно'),
(474, 'Minimum_Credits_for_Premium_Student', 'Minimum Credits For Premium Student', 'Créditos mínimos para el estudiante de primera calidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Минимальные кредиты для премиум студента'),
(475, 'Credits_for_viewing_message', 'Credits For Viewing Message', 'Créditos para la visualización de mensajes', NULL, NULL, NULL, 'देखने के लिए क्रेडिट संदेश', ' Кредиты за просмотр сообщение'),
(476, 'Currency_Symbol', 'Currency Symbol', ' Símbolo de moneda', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Символ валюты'),
(477, 'tutor_batchs_list', 'Tutor Batchs List', ' Lista batchs tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्सलेनदेन इतिहास क्रेडिट्स', 'Список Tutor партии'),
(478, 'view_batches', 'View Batches', 'Ver los lotes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Просмотр Порции'),
(479, 'tutor_batch_student_list', 'Tutor Batch Student List', 'Lista de lotes tutor del estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Репетитор список партии студент'),
(480, 'approve_tutor', 'Approve Tutor', ' aprobar tutor', NULL, NULL, NULL, 'अध्यापक स्वीकृत', 'Утвердить Tutor'),
(481, 'tutor_approved_successfully', 'Tutor Approved Successfully', ' Aprobado tutor con éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Репетитор Approved успешно'),
(482, 'is_approved', 'Is Approved', ' Esta aprobado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Одобрено'),
(483, 'you_have_already_posted_the_same_requirement', 'You Have Already Posted The Same Requirement', ' Usted ya ha Publicada el mismo requisito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(484, 'cancel_approvement', 'Cancel Approvement', ' Cancelar approvement', NULL, NULL, NULL, 'रद्द ', 'Отменить утверждении'),
(485, 'approvement_status_changed_successfully', 'Approvement Status Changed Successfully', ' Approvement ha cambiado de estado con éxito', NULL, NULL, NULL, ' स्थिति सफलतापूर्वक परिवर्तित', ' Статус изменен утверждении Успешно'),
(486, 'last_updated', 'Last Updated', ' Última actualización', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Последнее обновление'),
(487, 'location_name', 'Location Name', 'Nombre del lugar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Название местоположения'),
(488, 'users_information', 'Users Information', ' Información de los usuarios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пользователи информация'),
(489, 'user_type', 'User Type', ' Tipo de usuario', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Тип пользователя'),
(490, 'all_users_list', 'All Users List', ' Listado de todos los usuarios', NULL, NULL, NULL, 'सभी सदस्यों की सूची', 'Все Список пользователей'),
(491, 'phone_number', 'Phone Number', 'Número de teléfono', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Номер телефона'),
(492, 'approved', 'Approved', ' Aprobado', NULL, NULL, NULL, 'मंजूर की', 'утвержденный'),
(493, 'session_initiated', 'Session Initiated', ' sesión Iniciada', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'сессия Начатое'),
(494, 'running', 'Running', ' Corriendo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Бег'),
(495, 'completed', 'Completed', ' Terminado', NULL, NULL, NULL, 'पूरा कर लिया है', 'Завершенный'),
(496, 'claim_for_admin_intervention', 'Claim For Admin Intervention', ' Reclamación por la intervención del administrador', NULL, NULL, NULL, 'दावा व्यवस्थापक हस्तक्षेप के लिए', 'Заявление для администратора вмешательства'),
(497, 'closed', 'Closed', 'Cerrado', NULL, NULL, NULL, 'बन्द है', 'Закрыто'),
(498, 'enquiries', 'Enquiries', 'Consultas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'запросы'),
(499, 'course_booked', 'Course Booked', ' curso Reservados', NULL, NULL, NULL, 'कोर्स बुक', 'Курсы В...'),
(500, 'student_leads_details', 'Student Leads Details', ' Estudiante Conductores de detalles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Студент ведет Подробнее'),
(501, 'name', 'Name', ' Nombre', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'имя'),
(502, 'description', 'Description', 'Descripción', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Описание'),
(503, 'preffered_teaching_type', 'Preffered Teaching Type', ' Preffered Tipo Enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предпочтительный тип преподавания'),
(504, 'please_login_to_see_lead_details', 'Please Login To See Lead Details', ' Por favor, regístrate para ver los detalles de plomo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, войдите, чтобы увидеть детализирует'),
(505, 'package_cost', 'Package Cost', 'Costo del paquete', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Стоимость пакета'),
(506, 'information', 'Information', ' Información', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Информация'),
(507, 'you_will_be_charged', 'You will be charged\r\n', ' se te cobrará', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(509, 'total_subscribe', 'Total Subscribe', ' total Suscribirse', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Итого Подписка'),
(510, 'age', 'Age', ' Años', NULL, NULL, NULL, ' आयु', 'Возраст'),
(514, 'called_for_admin_intervention', 'Called for admin intervention', ' Llamado para la intervención del administrador', NULL, NULL, NULL, 'व्यवस्थापक हस्तक्षेप का आह्वान', 'Вызывается для вмешательства администратора'),
(515, 'not_yet_verified', 'Not yet verified', ' Todavía no se ha verificado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пока не подтверждено'),
(516, 'last_verified:', 'Last verified:', ' Última verificado:', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(517, 'your_profile_description', 'Your profile description', ' Su descripción de perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(518, 'profile_description', 'Profile description', ' Descripción del perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Профиль описание'),
(519, 'experience_description', 'Experience description', ' DESCRIPCIÓN DE LA eXPERIENCIA', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'описание Опыт работы'),
(520, 'frequently_asked_questions', 'Frequently asked questions', ' Preguntas frecuentes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Часто задаваемые вопросы'),
(521, 'FAQs', 'FAQs', 'Preguntas frecuentes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Кинофильмы'),
(522, 'please', 'Please', 'Por favor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'пожалуйста'),
(523, 'as_tutor_to_view_the_details', 'As tutor to view the details', ' Como tutor para ver los detalles', NULL, NULL, NULL, 'शिक्षक के रूप में विवरण देखने के लिए।', 'в качестве наставника для просмотра деталей.'),
(524, 'page_title', 'Page title', 'Título de la página', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Заголовок страницы'),
(525, '_as_tutor_to_view_the_details', ' as tutor to view the details', ' como tutor para ver los detalles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(526, 'as_tutor_to_view_the_details', ' as tutor to view the details.', '', NULL, NULL, NULL, 'शिक्षक के रूप में विवरण देखने के लिए।', 'в качестве наставника для просмотра деталей.'),
(528, 'premium_user', 'Premium user', ' usuario premium', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Премиум пользователь'),
(529, 'preferred_location', 'Preferred location', 'Ubicación preferida', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предпочтительное Местоположение'),
(530, 'students_present_status', 'Student\'s present status', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Нынешний статус студента'),
(531, 'budget_type', 'Budget type', ' tipo de presupuesto', NULL, NULL, NULL, 'बजट प्रकार', 'Тип бюджета'),
(532, 'Meta_Keyword', 'Meta Keyword', ' meta de palabras clave', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'мета ключевых слов'),
(533, 'Meta_Description', 'Meta Description', ' Metadescripción', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'метаописание'),
(534, 'Site_Description', 'Site Description', 'descripción del lugar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' описание сайта'),
(535, 'Google_Analytics', 'Google Analytics', ' Google analitico', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Гугл Аналитика'),
(536, 'tutors_system', 'Tutors system', 'sistema de tutores', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'система репетиторы'),
(538, 'credits_required_to_become_premium_member: ', 'Credits required to become premium member: ', ' Créditos necesarios para convertirse en miembro de la prima:', NULL, NULL, NULL, ' क्रेडिट प्रीमियम सदस्य बनने के लिए आवश्यक:', 'Кредиты должны стать премиум-членом:'),
(544, 'export', 'Export', ' Exportar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'экспорт'),
(545, 'no_tutors_available', 'No tutors available', ' No Institutos de ayuda disponibles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Нет в наличии наставники'),
(546, 'Find Institute', 'Find Institute', ' Encuentra Instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Найти институт'),
(547, 'Minimum_Credits_for_Premium_Institute', 'Minimum Credits for Premium Institute', ' Créditos mínimos para Instituto de primera calidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Минимальные кредиты для премиум института'),
(548, 'Time_Zone', 'Time Zone', 'Zona horaria', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Часовой пояс'),
(549, 'Credits_for_viewing_lead_details', 'Credits for viewing lead details', ' Créditos para la visualización de detalles de plomo', NULL, NULL, NULL, 'नेतृत्व विवरण देखने के लिए क्रेडिट', 'Кредиты для просмотра сведений о счете'),
(550, 'Please upload following certificates to proceed', 'Please upload following certificates to proceed', ' Por favor, sube después de los certificados de proceder', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, Вы можете добавить следующие сертификаты Чтобы продолжить'),
(551, 'Experiance Duration', 'Experiance Duration', ' Duración experiance', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Опыт Продолжительность'),
(552, 'experiance_duration', 'Experiance duration', ' duración experiance', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'продолжительность опыта'),
(553, 'Duration Type', 'Duration Type', ' Tipo duración', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Тип Продолжительность'),
(554, 'Establishment Year', 'Establishment Year', ' establecimiento Año', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Создание года'),
(555, 'experiance_duration Ex: 4 ', 'Experiance duration Ex: 4 ', ' duración Experiance Ex: 4', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Продолжительность опыта Пример: 4'),
(556, 'present_status:', 'Present status:', ' Estado actual:', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Современное состояние:'),
(557, 'qualification:', 'Qualification:', ' Calificación:', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Квалификация:'),
(558, 'view_details', 'View details', 'Ver detalles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Посмотреть детали'),
(559, 'experience:', 'Experience:', 'Experiencia:', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Опыт:'),
(560, 'view_profile', 'View profile', 'Ver perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Просмотреть профиль'),
(561, 'institutes_not_available', 'Institute(s) not available.', '', NULL, NULL, NULL, 'अभी बुक करें', 'Институт (ы) не доступен.'),
(562, 'account_information_successfully_updated', 'Account information successfully updated', ' La información de cuenta actualizado correctamente', NULL, NULL, NULL, ' खाता जानकारी सफलतापूर्वक अद्यतन', 'Информация об учетной записи успешно обновлен'),
(563, 'established', 'Established', ' Establecido', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'установленный'),
(564, 'website', 'Website', 'Sitio web', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Веб-сайт'),
(565, 'year_of_establishment', 'Year of establishment', ' Año de establecimiento', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Год основания'),
(566, 'free_demo:', 'Free demo:', ' Prueba gratis:', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Бесплатный Демо:'),
(567, 'you_do_not_have_enough_credits_to_view_the_lead_details', 'You don\'t have enough credits to view the lead details.', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(568, 'get_credits_here', 'Get credits here.', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' получить кредиты здесь.'),
(569, 'get_credits_here', ' get credits here.', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' получить кредиты здесь.'),
(570, 'student_enrolled_courses', 'Student enrolled courses', ' cursos de estudiantes matriculados', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Студенческие курсы зачислены'),
(571, 'institute_name', 'Institute name', 'Nombre del Instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Название института'),
(572, 'tutors_not_available', 'Tutor(s) not available.', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Тьютор (ы) не имеется.'),
(573, 'more_details', 'More details', ' Más detalles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Подробнее'),
(574, 'institute_offered_courses', 'Institute offered courses', ' Instituto ofreció cursos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Институт предложил курсы'),
(575, 'institute_tutoring_locations', 'Institute tutoring locations', 'ubicaciones de tutoría Institute', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Места репетиторство институт'),
(576, 'reserve_your_slot', 'Reserve your slot', ' Reserve su ranura', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Забронируйте свой слот'),
(577, 'select_course_first', 'Select course first', ' Seleccione curso de primeros', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите курс первой'),
(578, 'username', 'Username', ' Nombre de usuario', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Имя пользователя'),
(579, 'time_slot', 'Time slot', 'El intervalo de tiempo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Временной интервал'),
(580, 'course_offering_location', 'Course offering location', ' ubicación oferta de cursos', NULL, NULL, NULL, 'पाठ्यक्रम की पेशकश स्थान', 'месте и предлагает курс'),
(581, 'batch_start_date', 'Batch start date', 'Lotes fecha de inicio', NULL, NULL, NULL, 'बैच शुरू करने की तारीख', 'Пакетная дата начала'),
(582, 'batch_end_date', 'Batch end date', ' Lotes fecha de finalización', NULL, NULL, NULL, 'बैच के अंत की तारीख', 'Пакетная дата окончания'),
(583, 'days_off', 'Days off', ' Días de descanso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выходные дни'),
(584, 'batch_max_strength', 'Batch max strength', ' Lotes fuerza máxima', NULL, NULL, NULL, 'बैच अधिकतम शक्ति', 'Пакетная максимальная прочность'),
(585, 'the_user_name_is_not_available', 'The user name is not available', ' El nombre de usuario no está disponible', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Имя пользователя не доступен'),
(586, 'batch_code', 'Batch code', ' Código de lote', NULL, NULL, NULL, 'बैच कोड', 'Код партии'),
(587, 'Bookings', 'Bookings', ' reservas', NULL, NULL, NULL, 'बुकिंग', 'Бронирование'),
(588, 'student_bookings', 'Student bookings', ' las reservas de los estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' бронирование Student'),
(589, 'certificate_type', 'Certificate type', 'Tipo de certificado', NULL, NULL, NULL, 'प्रमाणपत्र का प्रकार', 'Тип сертификата'),
(590, 'view_certificates', 'View certificates', 'Ver certificados', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотр сертификатов'),
(591, 'institute_offering_courses', 'Institute offering courses', ' que ofrece cursos de instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Институт предлагают курсы'),
(592, 'tutoring_locations_of_institute', 'Tutoring locations of institute', ' Tutoría lugares de instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Репетиторы расположения института'),
(593, 'Need_admin_approval_for_institute', 'Need admin approval for institute', ' Necesitará la aprobación del administrador para el instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Необходимость одобрения администратором для института'),
(594, 'paypal_email_id', 'Paypal email id', 'Paypal correo electrónico de identificación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'PayPal электронный идентификатор'),
(595, 'bank_account_details', 'Bank account details', 'detalles de cuenta bancaria', NULL, NULL, NULL, 'बैंक खाता विवरण', 'детали банковского счета'),
(596, 'bank_ac_details', 'Bank ac details', 'Datos bancarios ac', NULL, NULL, NULL, 'बैंक एसी विवरण', 'Банковские реквизиты переменного тока'),
(597, 'money_conversion_request_from_tutor', 'Money conversion request from tutor', ' solicitud de conversión dinero de tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Конвертация денежных средств Форма запроса Репетитор'),
(598, 'close', 'Close', ' Cerca', NULL, NULL, NULL, 'बंद करे', 'Закрыть'),
(599, 'money_conversion_request_from_institute', 'Money conversion request from institute', ' solicitud de conversión dinero de instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Запрос Конвертация средств из института'),
(600, 'tutor_money_request', 'Tutor money request', 'solicitud de dinero tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запрос Tutor деньги'),
(601, 'institute_money_request', 'Institute money request', ' solicitud de dinero Instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запрос Институт денег'),
(602, 'moneyconversion-for-tutor', 'Moneyconversion-for-tutor', ' Moneyconversion-para-tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Деньги Конверсия-для-воспитателя'),
(603, 'moneyconversion-for-tutor-pending', 'Moneyconversion-for-tutor-pending', ' Moneyconversion-para-tutor en trámite', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Деньги Конверсия-для-репетитор находящейся на рассмотрении'),
(604, 'moneyconversion_for_institute_done', 'Moneyconversion for institute done', ' Moneyconversion de instituto de hecho', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Деньги для преобразования института сделал'),
(605, 'moneyconversion_for_institute_pending', 'Moneyconversion for institute pending', ' Moneyconversion de instituto pendiente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Деньги для преобразования института неурегулированных'),
(606, 'moneyconversion_for_institute_completed', 'Moneyconversion for institute completed', ' Moneyconversion de instituto completó', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Деньги для преобразования института завершен'),
(607, 'moneyconversion_for_tutor_pending', 'Moneyconversion for tutor pending', ' Moneyconversion de tutor pendiente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Деньги для преобразования Репетитор неурегулированных'),
(608, 'moneyconversion_for_tutor_completed', 'Moneyconversion for tutor completed', ' Moneyconversion de tutor completó', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Деньги преобразования для воспитателя завершен'),
(609, 'add_options', 'Add options', ' Añadir opciones', NULL, NULL, NULL, ' विकल्प जोड़ें', ' Добавление параметров'),
(610, 'add_degree', 'Add degree', 'Añadir grado', NULL, NULL, NULL, ' डिग्री जोड़े', 'Добавить степень'),
(611, 'Email_Activation_YesNo', 'Email Activation YesNo', ' Correo electrónico de activación YesNo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'E-mail Активация Да Нет'),
(612, 'Track_Login_IP_Address_YesNo', 'Track Login IP Address YesNo', 'YesNo dirección de pista de sesión IP', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Трек Войти IP-адрес Да Нет'),
(613, 'Max_Login_Attempts', 'Max Login Attempts', 'Max intentos de conexión', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Макс Вход Попытки'),
(614, 'Lockout_Time_milliseconds', 'Lockout Time milliseconds', ' milisegundos de tiempo de bloqueo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Время блокировки миллисекунды'),
(615, 'bttle', 'Bttle', ' bttle', NULL, NULL, NULL, 'लड़ाई', 'Боевой'),
(616, 'update_status', 'Update status', ' Estado de actualización', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Обновить состояние'),
(617, 'booking_status', 'Booking status', ' Estado de la reservación', NULL, NULL, NULL, 'बुकिंग स्थिति', ' статус бронирования'),
(618, 'Google', 'Google', 'google', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Google'),
(619, 'Instagram', 'Instagram', ' Instagram', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Instagram'),
(620, 'Youtube', 'Youtube', 'Youtube', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(621, 'Sharethis_Header', 'Sharethis Header', ' Cabecera sharethis', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Доля этого заголовка'),
(622, 'Sharethis_Links', 'Sharethis Links', ' Enlaces sharethis', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Поделись Ссылки'),
(623, 'From_No', 'From No', ' De n', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'От Нет'),
(624, 'API_Id', 'API Id', ' API Id', NULL, NULL, NULL, 'एपीआई आईडी', 'Идентификатор API'),
(625, 'Account_SID', 'Account SID', 'cuenta SID', NULL, NULL, NULL, ' खाता ', 'Счет SID'),
(626, 'Auth_Token', 'Auth Token', ' auth Token', NULL, NULL, NULL, 'प्रमाणीकरण टोकन', 'Auth Токен'),
(627, 'API_Version', 'API Version', ' La versión de la API', NULL, NULL, NULL, 'एपीआई संस्करण', 'Версия API'),
(628, 'Twilio_Phone_Number', 'Twilio Phone Number', ' Twilio número de teléfono', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Twilio Номер телефона'),
(629, 'view_batche_list', 'View batche list', ' Ver la lista batche', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотреть список партии'),
(630, 'no_leads_available', 'No lead(s) available.', '', NULL, NULL, NULL, 'अभी बुक करें', 'Нет новых потенциальных клиентов) доступны.'),
(631, 'no_course_details_found', 'No course details found', 'No se encontraron detalles del curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Нет курсов детали не найдено'),
(632, 'teaches', 'Teaches', ' enseña', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Преподает'),
(633, 'teaches:', 'Teaches:', ' enseña:', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Преподает:'),
(634, 'Institute Name', 'Institute Name', ' nombre del Instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Название института'),
(635, 'book_now', 'Book now', ' Reservar ahora', NULL, NULL, NULL, 'अभी बुक करें', 'Забронируйте сейчас'),
(636, 'View Profile', 'View Profile', 'Ver perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотреть профиль'),
(637, 'Book Now', 'Book Now', ' Reservar ahora', NULL, NULL, NULL, 'अभी बुक करें', 'Забронируйте сейчас'),
(638, 'Start_Course', 'Start Course', ' Inicio del curso', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Начало курса'),
(639, 'Login Here', 'Login Here', ' Entre aquí', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Авторизация'),
(640, 'invalid location', 'Invalid location', 'Locación invalida', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Неверное расположение'),
(641, 'admin_commission_percentage_with_credits', 'Admin commission percentage  (with credits)', '', NULL, NULL, NULL, 'व्यवस्थापक आयोग प्रतिशत (क्रेडिट्स में)', 'комиссия администратора процент (кредиты)'),
(642, 'from_email', 'From email', ' Desde el e-mail', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'От электронной почты'),
(643, 'course_completed', 'Course completed', ' curso completado', NULL, NULL, NULL, 'कोर्स पूरा', 'курс завершен'),
(644, 'please_update_profile', 'Please update profile', ' Por favor, actualice el perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Необходимо обновить профиль'),
(645, 'enroll_Now', 'Enroll Now', 'Enlístate ahora', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запишитесь сейчас'),
(646, 'please_update_your_profile_by_adding_tutoring_courses_and_preferred_teaching_types_to_avail_for_students', 'Please update your profile by adding tutoring courses and preferred teaching types to avail for students', ' Por favor, actualice su perfil añadiendo cursos de clases particulares y preferidas enseñanza de tipos para hacer uso de los estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, обновите свой профиль, добавляя репетиторство курсы и предпочитали учить типы пользоваться для студентов'),
(647, 'please_update_your_profile_by_adding_preferred_courses_and_preferred_teaching_types_to_get_tutors', 'Please update your profile by adding preferred courses and preferred teaching types to get tutors', 'Por favor, actualice su perfil añadiendo cursos preferidos y tipos de enseñanza preferidas para conseguir tutores', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, обновите свой профиль, добавляя предпочтительные курсы и предпочтительные виды обучения, чтобы получить репетиторов'),
(648, 'teaching_experience', 'Teaching experience', ' Experiencia en la enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Опыт преподавания'),
(649, 'certificates_of', 'Certificates of "', '', NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'Сертификаты'),
(650, 'view_institute_tutors', 'View institute tutors', ' Ver tutores instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотр института наставники'),
(651, 'institute_tutors_of', 'Institute tutors of"', '', NULL, NULL, NULL, 'अभी बुक करें', 'Институт наставники "'),
(652, 'scroll_News', 'Scroll News', 'Noticias de desplazamiento', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Свиток Новости'),
(653, 'terms_And_Conditons', 'Terms And Conditons', ' Términos y condiciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Условия и положения'),
(654, 'student_name : Phone_Num', 'Student name : Phone Num', ' Nombre del Estudiante: Teléfono Num', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(655, 'tutor_name : Phone_Num', 'Tutor name : Phone Num', 'Nombre del tutor: Teléfono Num', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(656, 'student_name - Phone_Num', 'Student name - Phone Num', ' El nombre del estudiante - Teléfono Num', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Имя Student - Телефон Num'),
(657, 'tutor_name - Phone_Num', 'Tutor name - Phone Num', ' Nombre del tutor - Teléfono Num', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(658, 'course_Subscribed', 'Course Subscribed', ' curso suscrito', NULL, NULL, NULL, 'कोर्स सदस्यता लिया', 'курс подпиской'),
(659, 'enroll_to_this_batch', 'Enroll to this batch', ' Inscribirse a este lote', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запишитесь к этой партии'),
(660, 'You do not have permission to access this page', 'You don\'t have permission to access this page', '', NULL, NULL, NULL, 'अभी बुक करें', 'У вас нет разрешения на доступ к этой странице'),
(661, 'list', 'List', ' Lista', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список'),
(662, 'packages_Subscribe_Details', 'Packages Subscribe Details', ' Paquetes Suscribirse detalles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пакеты Подписаться Подробности'),
(663, 'of_packages_Subscribe_Details', 'Of packages Subscribe Details', 'De paquetes Suscribirse detalles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пакетов Подписаться Подробности'),
(664, 'tutor_System', 'Tutor System', 'Sistema tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Репетитор системы'),
(665, 'Preferred Location', 'Preferred Location', ' ubicación preferida', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предпочтительное Местоположение'),
(666, 'Course Seeking', 'Course Seeking', 'Buscando por supuesto', NULL, NULL, NULL, 'कोर्स की मांग', 'Знакомлюсь курс'),
(667, 'Preferred Teaching type', 'Preferred Teaching type', 'Tipo de Enseñanza preferido', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предпочтительный тип преподавания'),
(668, 'Your Present Status', 'Your Present Status', 'Su Presente Estado', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(669, 'manage-courses', 'Manage-courses', ' Administrar-cursos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Управление-курсы'),
(670, 'sublocations', 'Sublocations', ' Sublocaitons', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Суб места'),
(672, 'sub_locations', 'Sub locations', 'ubicaciones sub', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्सलेनदेन इतिहास क्रेडिट्स', 'Суб места'),
(674, 'package_cost_after_discount', 'Package cost after discount', ' costo del paquete después del descuento', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Стоимость пакета после скидки'),
(675, 'enroll_in_this_batch', 'Enroll in this batch', ' Inscribirse en este lote', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запишитесь в этом пакете'),
(676, 'please_select_batch', 'Please select batch', ' Por favor, seleccione por lotes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, выберите партию'),
(677, 'send_us_your_message', 'Send us your message', ' Envíenos su mensaje', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправить нам сообщение'),
(678, 'General_Inquiries', 'General Inquiries', ' Consultas generales', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Общие запросы'),
(679, 'Media_Requests', 'Media Requests', ' Las solicitudes de los medios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Медиа-запросы'),
(680, 'Offline_Support', 'Offline Support', ' Soporte en línea', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Поддержка в автономном режиме'),
(681, 'Account_Type', 'Account Type', ' Tipo de cuenta', NULL, NULL, NULL, ' खाते का प्रकार', 'тип аккаунта'),
(682, 'credits_Transactions_History', 'Credits Transactions History', ' Créditos Transacciones Historia', NULL, NULL, NULL, 'क्रेडिट लेनदेन इतिहास', ' Кредиты Сделки История'),
(683, 'user_credit_transactions', 'User credit transactions', ' operaciones de crédito del usuario', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' кредитные операции пользователя'),
(684, 'credits_Transaction_History', 'Credits Transaction History', ' Créditos historial de transacciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Кредиты История транзакций'),
(685, 'Ratings', 'Ratings', ' calificaciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Рейтинг'),
(686, 'your_seo_keywords_description', 'Your seo keywords description', 'Su descripción palabras clave de SEO', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(687, 'seo_keywords_description', 'Seo keywords description', ' Descripción palabras clave Seo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Описание Seo ключевые слова'),
(688, 'meta_keywords_description', 'Meta keywords description', ' Descripción Meta palabras clave', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Описание Мета ключевые слова'),
(689, 'no_courses_available', 'No courses available', ' No hay cursos disponibles', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Нет доступных курсов'),
(690, 'you_do not_have_enough_credits_to_enroll_in_the_institute_Please_get_required_credits_here', 'You don\'t have enough credits to enroll in the institute. Please get required credits here', '', NULL, NULL, NULL, NULL, NULL),
(691, 'your_slot_with_booked_successfully_Once_isntitute_approved_your_booking and_tutor_initiated_the_session_you_can_start_the_course_on_course_starting_date', 'Your slot with booked successfully. Once isntitute approved your booking and tutor initiated the session, you can start the course on course starting date.', '', NULL, NULL, NULL, 'अभी बुक करें', NULL),
(692, 'duration_needed_to_complete_course_in', 'Duration needed to complete course in', ' Duración necesario para completar curso de', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Продолжительность, необходимое для завершения курса в'),
(694, 'Credits You can pay', 'Credits You can pay', ' Puede pagar créditos', NULL, NULL, NULL, 'क्रेडिट आप भुगतान कर सकते हैं', ' Кредиты Вы можете оплатить'),
(695, 'your requirement posted successfully', 'Your requirement posted successfully', 'Su requisito se ha publicado correctamente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(697, 'please_become_premium_member_to_enroll_in_institute', 'Please become premium member to enroll in institute', ' Por favor, convertido en miembro de la prima para inscribirse en el instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, стать членом премиум, чтобы поступить в институт'),
(698, 'slot_not_available_in_the_batch._Please_select_other_batch', 'Slot not available in the batch. Please select other batch', '', NULL, NULL, NULL, NULL, NULL),
(699, 'non_premium_user', 'Non premium user', ' usuario no premium', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Номера для пользователя премиум'),
(700, 'your_meta_seo_keywords', 'Your meta seo keywords', ' Sus meta palabras clave de SEO', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(701, 'meta_seo_keywords', 'Meta seo keywords', ' palabras clave de SEO Meta', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мета ключевые слова поисковая оптимизация'),
(702, 'refund_credits_to_student', 'Refund credits to student', ' créditos de reembolso a los estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Возврат кредитов студента'),
(703, 'refund_credits_to_tutor', 'Refund credits to tutor', ' Restitución acredita para dar clases', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Возврат кредитов к репетитору'),
(704, 'please_login_to_enroll_in_institute', 'Please login to enroll in institute', ' Inicia sesión para inscribirse en el instituto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, войдите, чтобы поступить в институт'),
(705, 'tranfer_credits_to_tutor', 'Tranfer credits to tutor', ' Tranfer acredita para dar clases', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Передача кредитов куратору'),
(706, 'send_credit_conversion_request', 'Send credit conversion request', 'Enviar solicitud de conversión de crédito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправить запрос кредитной конверсии'),
(708, 'send_credits_conversion_request', 'Send credits conversion request', ' Enviar solicitud de conversión créditos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправить запрос КРЕДИТЫ преобразования'),
(709, 'please_select_atleast_one_preferred_teaching_type', 'Please select atleast one preferred teaching type', 'Por favor, seleccione al menos un tipo de enseñanza preferida', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, выберите один по крайней мере предпочтительный тип преподавания'),
(710, 'please login to access this page', 'Please login to access this page', ' Por favor regístrate para acceder a la página', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, войдите, чтобы открыть эту страницу'),
(711, 'Update Booking status', 'Update Booking status', 'Actualizar el estado de reserva', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Обновить статус бронирования'),
(712, 'View Booking details', 'View Booking details', 'Ver detalles de la reservación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Просмотреть детали Бронирование'),
(713, 'Please complete your course to send credit conversion request', 'Please complete your course to send credit conversion request', ' Por favor, complete su curso para enviar solicitud de conversión del crédito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Заполните, пожалуйста, свой курс, чтобы отправить запрос кредитной конверсии'),
(714, 'Invalid request', 'Invalid request', ' Solicitud no válida', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Неверный запрос'),
(715, 'Money Conversion Request', 'Money Conversion Request', ' Solicitud de conversión de dinero', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Деньги преобразования Запрос'),
(716, 'Done', 'Done', ' Hecho', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Готово'),
(718, 'moneyconversion_for_institute \\"pending\\"', 'Moneyconversion for institute \\"pending\\"', '', NULL, NULL, NULL, NULL, NULL),
(720, 'moneyconversion_for_institute pending', 'Moneyconversion for institute pending', ' Moneyconversion de instituto pendiente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Деньги для преобразования института неурегулированных'),
(721, 'Credits to Money conversion request sent successfully', 'Credits to Money conversion request sent successfully', ' Créditos a solicitud de conversión dinero enviado con éxito', NULL, NULL, NULL, 'मनी रूपांतरण अनुरोध करने के लिए क्रेडिट सफलतापूर्वक भेजा', 'Кредиты на запрос конвертации денег успешно отправлено'),
(722, 'you_don\'t_have_enough_credits_to_book_the_tutor._Please_get_required_credits_here', 'You don\'t have enough credits to book the tutor. Please get required credits here', '', NULL, NULL, NULL, NULL, NULL),
(723, 'credit_conversion_requests', 'Credit conversion requests', ' las solicitudes de conversión de crédito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запросы кредитной конверсии'),
(724, 'total_Amount', 'Total Amount', ' Cantidad total', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Итого'),
(725, 'Please verify and upload required certificates.', 'Please verify and upload required certificates.', '', NULL, NULL, NULL, NULL, NULL),
(726, 'credits_History', 'Credits History', ' créditos Historia', NULL, NULL, NULL, 'क्रेडिट हिस्ट्री', 'Кредиты История'),
(727, 'credits_Transactions', 'Credits Transactions', 'créditos Transacciones', NULL, NULL, NULL, 'क्रेडिट लेनदेन', 'Кредиты Сделки'),
(728, 'Already sent the request And status of the payment is', 'Already sent the request. And status of the payment is ', '', NULL, NULL, NULL, 'अभी बुक करें', ' Уже послал запрос. И статус платежа'),
(730, 'fancybox', 'Fancybox', 'Caja lujosa', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Необычные коробки'),
(731, 'Please select a type', 'Please select a type', 'Por favor seleccione un tipo de', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, выберите тип'),
(732, 'view_Batch_list', 'View Batch list', ' Ver la lista de lotes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотреть список партии'),
(733, 'student_list', 'Student list', ' lista de alumnos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' список студент'),
(734, 'Course Name', 'Course Name', ' Nombre del curso', NULL, NULL, NULL, 'कोर्स का नाम', 'Название курса'),
(735, 'Student Name', 'Student Name', ' Nombre del estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Имя ученика'),
(736, 'Enable_Initiate_Session_Option_Before_Minutes', 'Enable Initiate Session Option Before Minutes', ' Habilitar la opción Iniciar Sesión Antes Minutos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Enable Инициировать Session Option перед минутами'),
(737, 'Enable_Course_Completed_Option_Before_Minutes', 'Enable Course Completed Option Before Minutes', ' Habilitar la opción Curso Completo Antes Minutos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Включение завершения курса вариант перед минутами'),
(738, 'view_students_list', 'View students list', ' Ir al listado de estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотр списка студентов'),
(739, 'tutor_batches_list', 'Tutor batches list', ' Lista de lotes tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Список Tutor партии'),
(740, 'view_enrolled_students', 'View enrolled students', ' Ver inscrito estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотр зачисленных студентов'),
(741, 'enrolled_student', 'Enrolled student', ' estudiante inscrito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Поступил студент'),
(742, 'History', 'History', ' Historia', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'история'),
(743, 'credits_Transaction', 'Credits Transaction', ' créditos Transacción', NULL, NULL, NULL, 'क्रेडिट लेनदेन', 'Кредиты сделки'),
(744, 'Money_Conversion', 'Money Conversion', ' Conversión de dinero', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'деньги Преобразование'),
(745, 'Request', 'Request', 'Solicitud', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Запрос'),
(746, 'credit_Conversion', 'Credit Conversion', ' Conversión de crédito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Кредитные преобразования'),
(747, 'Courses Offered', 'Courses Offered', ' Los cursos ofrecidos', NULL, NULL, NULL, 'पाठ्यक्रम की पेशकश की', 'Предлагаемые курсы'),
(748, 'Net Credits', 'Net Credits', ' Los créditos netos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Чистые кредиты'),
(749, 'Total Tutors', 'Total Tutors', ' Los tutores totales', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Всего репетиторы');
INSERT INTO `pre_languagewords` (`lang_id`, `lang_key`, `english`, `spanish`, `bengali`, `french`, `japanese`, `hindi`, `russian`) VALUES
(750, 'Total Batches', 'Total Batches', ' Los lotes totales', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Всего Порции'),
(751, 'Approve Batch Students', 'Approve Batch Students', ' Aprobar lotes Estudiantes', NULL, NULL, NULL, 'बैच के छात्रों को मंजूरी', ' Утвердить Batch студентов'),
(752, 'View Enrolled Students', 'View Enrolled Students', ' Ver Estudiantes Matriculados', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Просмотр зачисленных студентов'),
(753, 'batch_status', 'Batch status', ' estado del lote', NULL, NULL, NULL, 'बैच का दर्जा', 'статус пакетного'),
(754, 'Apple Store', 'Apple Store', ' tienda Apple', NULL, NULL, NULL, 'एप्पल स्टोर', 'Apple Store'),
(755, 'Write any information to batch', 'Write any information to batch', ' Escribe cualquier información a lote', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Написать какую-либо информацию партии'),
(756, 'Approve Batch', 'Approve Batch', ' aprobar por lotes', NULL, NULL, NULL, 'बैच स्वीकृत', 'Утвердить Batch'),
(757, 'Batch approved successfully', 'Batch approved successfully', ' Lote aprobado con éxito', NULL, NULL, NULL, 'बैच सफलतापूर्वक को मंजूरी दी', ' Пакетная утвержден успешно'),
(758, 'Batch already approved', 'Batch already approved', ' Ya aprobado por lotes', NULL, NULL, NULL, 'बैच पहले ही मंजूरी दे दी है', 'Пакетная уже утвержден'),
(760, 'other_title', 'Other title', ' otro título', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Другое название'),
(761, 'Show None', 'Show None', ' Mostrar Ninguno', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्सलेनदेन इतिहास क्रेडिट्स', ' Показать ни'),
(762, 'Tutor Name', 'Tutor Name', 'Nombre tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्सलेनदेन इतिहास क्रेडिट्स', 'Репетитор Имя'),
(763, 'This Tutor Is Not Available Now', 'This Tutor Is Not Available Now', ' Este tutor no está disponible ahora', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Этот учебник не доступен сейчас'),
(764, 'Initiate Session For Batch Students', 'Initiate Session For Batch Students', ' Iniciar Sesión Para el lote Estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Начать сеанс для пакетного студентов'),
(765, 'Institute:', 'Institute:', ' Instituto:', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(766, 'Coures:', 'Coures:', 'coures:', NULL, NULL, NULL, 'पाठ्यक्रम:', 'Курсы:'),
(767, 'No of Batches Tutoring', 'No of Batches Tutoring', ' Sin lotes de Tutoría', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Нет батчей Репетиторство'),
(768, 'Initiate Session for the Batch', 'Initiate Session for the Batch', ' Iniciar Sesión para el lote', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Инициировать сеанс для пакетной'),
(769, 'Write any information to students like if session is oline, provide any link/URL details', 'Write any information to students like if session is oline, provide any link/URL details', '', NULL, NULL, NULL, NULL, NULL),
(770, 'Initiate Session', 'Initiate Session', 'iniciar Sesión', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Начать сеанс'),
(771, 'Batch Session Initiated successfully', 'Batch Session Initiated successfully', ' Sesión lotes iniciado con éxito', NULL, NULL, NULL, 'बैच सत्र सफलतापूर्वक शुरू की', 'Пакетная Session Начатое успешно'),
(772, 'Batch Session already initiated', 'Batch Session already initiated', ' Sesión lotes ya iniciado', NULL, NULL, NULL, 'बैच सत्र पहले ही शुरू', 'Пакетная сессия уже инициировала'),
(773, 'Update as Course Completed For Batch', 'Update as Course Completed For Batch', ' Actualizar como Curso Completo Para el Lote', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Дополнение курса Завершенный для пакетного'),
(774, 'Update as Course Completed for the Batch', 'Update as Course Completed for the Batch', 'Update como Curso Completo para el lote', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Дополнение курса Завершенный для пакетного'),
(775, 'Update As Course Completed', 'Update As Course Completed', 'Como actualizar Curso Completo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Дополнение курса Завершенный'),
(776, 'Course completed for the Batch successfully', 'Course completed for the Batch successfully', 'Curso completó con éxito para el lote', NULL, NULL, NULL, 'कोर्स बैच के लिए सफलतापूर्वक पूरा कर लिया', 'Курс завершен для партии успешно'),
(777, 'Invalid Transaction. Please try again', 'Invalid Transaction. Please try again', '', NULL, NULL, NULL, NULL, NULL),
(778, 'Information updated successfully', 'Information updated successfully', ' Información actualizada correctamente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Информация об успешном обновлении'),
(779, 'enter_only_one_time-slot_for_one_batch', 'Enter only one time-slot for one batch', 'Ingrese sólo un intervalo de tiempo de un lote', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Введите только один тайм-слот для одной партии'),
(781, 'No of Batches', 'No of Batches', 'No de lotes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Нет батчей'),
(782, 'Tutoring', 'Tutoring', ' tutoría', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'репетиторство'),
(783, 'inst_batches', 'Inst batches', ' lotes Inst', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Inst партии'),
(784, 'send_credits_conversion_request_for_this_batch', 'Send credits conversion request for this batch', ' Enviar solicitud de conversión de créditos para este lote', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправить запрос конвертации кредитов для этой партии'),
(785, 'Batch Id', 'Batch Id', ' Número de identificación del lote', NULL, NULL, NULL, 'बैच क्रमांक', ' Пакетная Id'),
(786, 'Favicon', 'Favicon', ' favicon', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Favicon'),
(787, 'Invalid request Or Batch not yet completed/closed.', 'Invalid request Or Batch not yet completed/closed.', '', NULL, NULL, NULL, NULL, NULL),
(788, 'assigned_Tutor', 'Assigned Tutor', ' tutor asignado', NULL, NULL, NULL, 'सौंपा ट्यूटर', 'Назначено Tutor'),
(789, 'seo_keywords', 'Seo keywords', ' palabras clave Seo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Seo ключевые слова'),
(790, 'student_enrollment_details', 'Student enrollment details', ' detalles de inscripción del estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Детали набора студентов для обучения'),
(791, 'Teaching_class_types', 'Teaching class types', ' tipos de clases de la enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Типы Учебный класс'),
(792, 'Non_Academic', 'Non Academic', ' no académico', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Неакадемический'),
(793, 'institute_money_requests', 'Institute money requests', ' solicitudes de dinero Institute', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' денежные запросы Институт'),
(794, 'money_conversion_requests_from_isntitute', 'Money conversion requests from isntitute', ' las solicitudes de conversión de dinero isntitute', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запросы Конвертация средств из института'),
(795, 'per_credit_cost', 'Per credit cost', ' Por coste de crédito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'За стоимость кредита'),
(796, 'tutor_money_requests', 'Tutor money requests', ' solicitudes de dinero tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Репетитор денежные запросы'),
(797, 'money_conversion_requests_from_tutor', 'Money conversion requests from tutor', ' las solicitudes de conversión de dinero tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Запросы Конвертация денежных средств от воспитателя'),
(798, 'No Details Found', 'No Details Found', ' No hay detalles que se encuentran', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Нет Детали не найдены'),
(799, 'batch', 'Batch', ' Lote', NULL, NULL, NULL, 'जत्था', 'партия'),
(800, 'credits_acquired', 'Credits acquired', ' créditos adquiridos', NULL, NULL, NULL, 'क्रेडिट का अधिग्रहण', 'Кредиты приобрели'),
(801, 'online', 'Online', ' En línea', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' В сети'),
(802, 'select_Country', 'Select Country', 'Seleccionar país', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите страну'),
(803, 'select_Phone_Code', 'Select Phone Code', ' Seleccione Código de teléfono', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите код телефона'),
(804, 'contact_us', 'Contact us', ' Contáctenos', NULL, NULL, NULL, 'हमसे संपर्क करें', 'Свяжитесь с нами'),
(805, 'Subject', 'Subject', 'Tema', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предмет'),
(806, 'Message', 'Message', ' Mensaje', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Сообщение'),
(807, 'Send Message', 'Send Message', ' Enviar mensaje', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Отправить сообщение'),
(808, 'please_enter_first_name', 'Please enter first name', ' Por favor, introduzca primero nombre', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, введите имя'),
(809, 'please_enter_last_name', 'Please enter last name', ' Por favor, ingrese el apellido', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, введите фамилию'),
(810, 'please_enter_email_id', 'Please enter email id', ' Por favor, introduzca correo electrónico de identificación', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, введите электронный идентификатор'),
(811, 'please_enter_subject', 'Please enter subject', ' Por favor, introduzca sujeto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, введите тему'),
(812, 'Contact Query Received', 'Contact Query Received', 'Contacto Consulta recibida', NULL, NULL, NULL, 'संपर्क क्वेरी प्राप्त', 'Как связаться с запросом, полученных'),
(813, 'Hello Admin, ', 'Hello Admin, ', '', NULL, NULL, NULL, NULL, NULL),
(814, 'You got contact query. Below are the details.', 'You got contact query. Below are the details.', '', NULL, NULL, NULL, NULL, NULL),
(815, 'Thank you', 'Thank you', 'Gracias', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'спасибо'),
(816, 'Your contact request sent successfully', 'Your contact request sent successfully', 'Su solicitud de contacto enviado con éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Ваш контакт успешно отправлен запрос'),
(817, 'Number of credits required for sending message: ', 'Number of credits required for sending message: ', ' Número de créditos requeridos para enviar el mensaje:', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(818, 'Credits required for sending message: ', 'Credits required for sending message: ', 'Créditos necesarios para enviar el mensaje:', NULL, NULL, NULL, 'संदेश भेजने के लिए आवश्यक क्रेडिट:', 'Кредиты, необходимые для отправки сообщения'),
(819, 'Select Course *', 'Select Course *', '', NULL, NULL, NULL, NULL, NULL),
(820, 'please_enter_name', 'Please enter name', ' Por favor, introduzca el nombre', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, введите имя'),
(821, 'please_enter_phone', 'Please enter phone', ' Por favor, introduzca el teléfono', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, введите телефон'),
(822, 'please_enter_your_message', 'Please enter your message', ' Por favor ingrese su mensaje', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, введите ваше сообщение'),
(823, 'Message Received From Student', 'Message Received From Student', 'Mensaje recibido del Estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Сообщение получено от студента'),
(824, 'Hi ', 'Hi ', ' Hola', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Здравствуй'),
(826, ' Student', ' Student', ' Estudiante', NULL, NULL, NULL, ' छात्र', 'Студент'),
(827, 'Your message sent to Tutor successfully', 'Your message sent to Tutor successfully', ' Su mensaje enviado con éxito con el Tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्सलेनदेन इतिहास क्रेडिट्स', NULL),
(828, 'Student_type', 'Student type', ' tipo de estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Тип студента'),
(829, 'Credits_for_sending_message', 'Credits for sending message', ' Créditos para enviar el mensaje de', NULL, NULL, NULL, 'संदेश भेजने के लिए क्रेडिट', 'Кредиты для отправки сообщения'),
(830, 'Your message not sent due to some technical issue. Please send message after some time. Thankyou.', 'Your message not sent due to some technical issue. Please send message after some time. Thankyou.', '', NULL, NULL, NULL, NULL, NULL),
(831, 'Your message sent to Institute successfully', 'Your message sent to Institute successfully', ' Su mensaje enviado al Instituto éxito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्सलेनदेन इतिहास क्रेडिट्स', NULL),
(832, 'please_become_premium_member_to_send_message_to_student', 'Please become premium member to send message to student', ' Por favor hágase miembro premium para enviar un mensaje a un estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, стать членом премиум, чтобы отправить сообщение студенту'),
(833, 'Message Received From ', 'Message Received From ', ' El mensaje recibido del', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Полученное сообщение'),
(834, 'You got a message from Tutor. Below are the details.', 'You got a message from Tutor. Below are the details.', '', NULL, NULL, NULL, NULL, NULL),
(835, 'Your message sent to Student successfully', 'Your message sent to Student successfully', 'Su mensaje enviado al éxito del estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(836, 'You got a message from . Below are the details.', 'You got a message from . Below are the details.', '', NULL, NULL, NULL, NULL, NULL),
(837, 'You got a message from Institute. Below are the details.', 'You got a message from Institute. Below are the details.', '', NULL, NULL, NULL, NULL, NULL),
(838, 'update_contact_information', 'Update contact information', ' actualizar información de contactos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Обновление контактной информации'),
(839, 'please select phone code', 'Please select phone code', ' Por favor, seleccione el código de teléfono', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, выберите телефонный код'),
(840, 'Please send your request after 24 hours of the Batch closed time. Thank you.', 'Please send your request after 24 hours of the Batch closed time. Thank you.', '', NULL, NULL, NULL, NULL, NULL),
(841, 'please_become_premium_member_to_send_message_to_institute', 'Please become premium member to send message to institute', ' Por favor hágase miembro premium para enviar el mensaje a instituir', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, стать членом премиум, чтобы отправить сообщение возбуждать'),
(842, 'rate_this_tutor', 'Rate this tutor', 'Valorar este tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Оценить этот репетитор'),
(843, 'rate_tutor', 'Rate tutor', 'tasa tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Оценить Репетитор'),
(844, 'Rate Tutor', 'Rate Tutor', ' tasa tutor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Оценить Репетитор'),
(845, 'Comments', 'Comments', ' comentarios', NULL, NULL, NULL, 'टिप्पणियाँ', 'Комментарии'),
(846, 'fee_(in_credits)', 'Fee (in credits)', '', NULL, NULL, NULL, NULL, NULL),
(847, 'Your review successfully sent to the Tutor.', 'Your review successfully sent to the Tutor.', '', NULL, NULL, NULL, NULL, NULL),
(848, 'Thanks for rating the tutor. Your review successfully sent to the Tutor.', 'Thanks for rating the tutor. Your review successfully sent to the Tutor.', '', NULL, NULL, NULL, NULL, NULL),
(849, 'Manage Teaching Types', 'Manage Teaching Types', ' Administrar tipos de enseñanza', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Управление типами Преподавание'),
(850, 'Thanks for rating the tutor. Your review successfully updated to the Tutor.', 'Thanks for rating the tutor. Your review successfully updated to the Tutor.', '', NULL, NULL, NULL, NULL, NULL),
(851, 'rating_(out_of_5)', 'Rating (out of 5)', '', NULL, NULL, NULL, NULL, NULL),
(852, 'Opened Leads', 'Opened Leads', ' Ventas abiertas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'открытые Ведет'),
(853, 'closed Leads', 'Closed Leads', ' Ventas cerradas', NULL, NULL, NULL, 'बंद सुराग', 'Закрытые Ведет'),
(854, 'E-Templates', 'E-Templates', 'E-Plantillas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Электронные шаблоны'),
(855, 'money_conversion_requests', 'Money conversion requests', 'las solicitudes de conversión de dinero', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Запросы Конвертация денежных средств'),
(856, 'manage_courses', 'Manage courses', ' gestión de cursos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Управление курсы'),
(857, 'manage_locations', 'Manage locations', 'administrar las ubicaciones', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्सलेनदेन इतिहास क्रेडिट्स', 'Управление местоположениями'),
(858, 'personal_information', 'Personal information', ' Informacion personal', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Личная информация'),
(859, 'For viewing lead ', 'For viewing lead ', ' Para la visualización de plomo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Для просмотра свинца'),
(860, 'of Student', 'Of Student', ' del Estudiante', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' студенческих'),
(861, 'profile_information', 'Profile information', 'Información del perfil', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Информация профиля'),
(862, 'Explore - Enrich - Excel', 'Explore - Enrich - Excel', ' Explora - Enriquecer - Excel', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Исследуйте - Пополните - первенствует'),
(863, 'Everything you need in order to find the', 'Everything you need in order to find the', ' Todo lo que necesita con el fin de encontrar la', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Все, что нужно для того, чтобы найти'),
(864, 'right', 'Right', ' Derecha', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Правильно'),
(866, 'Videos & Images', 'Videos & Images', '', NULL, NULL, NULL, NULL, NULL),
(868, 'Quality Scores', 'Quality Scores', ' Niveles de calidad', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'показатели качества'),
(869, 'We have rated teachers for safety and convenience as we know how important this is for you.', 'We have rated teachers for safety and convenience as we know how important this is for you.', '', NULL, NULL, NULL, NULL, NULL),
(870, 'Reviews & Ratings', 'Reviews & Ratings', '', NULL, NULL, NULL, NULL, NULL),
(871, 'No more emails calls or messaging friends for recommendations  get acces to real reviews in seconds', 'No more emails, calls or messaging friends for recommendations - get acces to real reviews in seconds.', '', NULL, NULL, NULL, NULL, 'Нет больше писем, звонков или друзей по обмену сообщениями для рекомендаций - получить доступ к реальным отзывы в секундах.'),
(872, 'FEATURED ON:', 'FEATURED ON:', ' PRESENTADO EN:', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'размещённые на:'),
(873, 'Recent Added', 'Recent Added', 'Agregado reciente', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Недавно добавленный'),
(874, 'How Does This', 'How Does This', ' ¿Cómo hace este', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Как это'),
(875, 'Work', 'Work', 'Trabajo', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Работа'),
(876, 'Start Your Search', 'Start Your Search', 'Realice su búsqueda', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Начать поиск'),
(877, 'Search for online tutoring. Need help with your search? Request a tutor and we’ll have tutors contact you very soon!', 'Search for online tutoring. Need help with your search? Request a tutor and we’ll have tutors contact you very soon!', '', NULL, NULL, NULL, NULL, NULL),
(878, 'Review', 'Review', 'revisión', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Обзор'),
(879, 'Read feedback and ratings from parents and students. Detailed tutor profiles also include photos more.', 'Read feedback and ratings from parents and students. Detailed tutor profiles also include photos more.', '', NULL, NULL, NULL, NULL, NULL),
(880, 'Schedule', 'Schedule', ' Programar', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' График'),
(881, 'Communicate directly with tutors to find a time that works for you Whether you need a single lesson', 'Communicate directly with tutors to find a time that works for you. Whether you need a single lesson.', '', NULL, NULL, NULL, NULL, 'Общайтесь с репетиторами, чтобы найти время, которое работает для вас. Вам нужно ли одного урока.'),
(882, 'Start Learning', 'Start Learning', ' Comienza a aprender', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Начало обучения'),
(883, 'One-on-one instruction is the most effective way to learn. Let us handle payments and administrative details.', 'One-on-one instruction is the most effective way to learn. Let us handle payments and administrative details.', '', NULL, NULL, NULL, NULL, NULL),
(884, 'Why Students', 'Why Students', ' ¿Por qué los estudiantes', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Почему студенты'),
(885, 'Love Us', 'Love Us', ' Amarnos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Люби нас'),
(886, 'Are you a', 'Are you a', ' Eres un', NULL, NULL, NULL, 'क्या तुम एक', 'Вы'),
(887, 'Teacher', 'Teacher', ' Profesor', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'учитель'),
(888, 'Looking for a better way to reach your target audience? We can help - just list with us', 'Looking for a better way to reach your target audience? We can help - just list with us', '', NULL, NULL, NULL, NULL, NULL),
(889, 'for free', 'For free', 'Gratis', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Бесплатно'),
(890, 'Boost your sales and scale up all of your classes', 'Boost your sales and scale up all of your classes.', '', NULL, NULL, NULL, 'अभी बुक करें', 'Повышение продаж и расширение масштабов всех ваших классов.'),
(891, 'Get a lot of exposure and brand recognition from everyone', 'Get a lot of exposure & brand recognition from everyone.', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Получите много экспозиции и бренд признания от всех.'),
(892, 'Participate in various events and school programs whenever you want', 'Participate in various events and school programs whenever you want.', '', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Примите участие в различных мероприятиях и школьных программ, когда вы хотите.'),
(893, 'site_Testimonials', 'Site Testimonials', ' sitio Testimonios', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Отзывы по сайту'),
(894, 'as a Teacher', 'As a Teacher', 'Como maestro', NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'Как учитель'),
(895, 'view_tutoring_languages', 'View tutoring languages', ' Ver idiomas de tutoría', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Языки Просмотр репетиторство'),
(896, 'Tutoring_Languages', 'Tutoring Languages', ' tutoría Idiomas', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Репетиторство Языки'),
(897, 'success_phrases_updated_successfuly', 'Success phrases updated successfuly', ' frases de éxito actualizados con exito', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Фразы успеха успешно обновлен'),
(899, 'manage_certificates', 'Manage certificates', ' gestionar certificados', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Управление сертификатами'),
(900, 'Please select a subject', 'Please select a subject', 'Por favor, seleccione un tema', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пожалуйста, выберите тему'),
(901, 'View_Fields', 'View Fields', ' Ver más campos', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просмотр поля'),
(902, 'Edit_Values', 'Edit Values', ' Editar valores', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Изменить значения'),
(903, 'enter_new_password', 'Enter new password', ' Introduzca nueva contraseña', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Введите новый пароль'),
(904, 'confirm_new_password', 'Confirm new password', ' Confirmar nueva contraseña', NULL, NULL, NULL, 'नए पासवर्ड की पुष्टि करें', ' Подтвердите новый пароль'),
(905, 'Make_Default', 'Make Default', 'Hacer por defecto', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Использовать по умолчанию'),
(906, 'password_and_confirm_passwords_should_match', 'Password and confirm passwords should match', ' Contraseña y confirme las contraseñas deben coincidir', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Пароль и подтверждение пароля должны совпадать'),
(907, 'please_enter_new_password', 'Please enter new password', ' Por favor, introduzca una nueva contraseña', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, введите новый пароль'),
(908, 'please_confirm_new_password', 'Please confirm new password', ' Por favor, confirme la nueva contraseña', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, подтвердите новый пароль'),
(909, 'opened_Leads', 'Opened Leads', 'Opened Leads', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'открытые Ведет'),
(910, 'select_Payment_Gateway', 'Select Payment Gateway', 'Select Payment Gateway', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите Платежный шлюз'),
(911, 'personal_info', 'Personal info', 'Personal info', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Личная информация'),
(912, 'preffered_teaching_types', 'Preffered teaching types', 'Preffered teaching types', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Предпочтительный тип преподавания'),
(913, 'enrolled_courses', 'Enrolled courses', 'Enrolled courses', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Зарегистрировавшиеся курсы'),
(915, 'How far are you willing to travel', 'How far are you willing to travel', 'How far are you willing to travel', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Как далеко вы готовы путешествовать'),
(916, 'Key', 'Key', 'Key', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'ключ'),
(917, 'Sender', 'Sender', 'Sender', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'отправитель'),
(918, 'API_URL', 'API URL', 'API URL', NULL, NULL, NULL, 'एपीआई यूआरएल', 'API URL'),
(919, 'Admin_Commission_For_A_Booking_In_Percentage', 'Admin Commission For A Booking In Percentage', 'Admin Commission For A Booking ', NULL, NULL, NULL, ' एक बुकिंग में प्रतिशत के लिए व्यवस्थापक आयोग', 'Администратор комиссия за бронирование в процентах'),
(920, 'active', 'Active', 'Active', NULL, NULL, NULL, 'सक्रिय', 'активный'),
(921, 'inactive', 'Inactive', ' Inactive', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Неактивный'),
(922, 'your_Email', 'Your Email', 'Your Email', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(923, 'App not sent due to some technical issue Please try after some time Thankyou', 'App not sent due to some technical issue. Please try after some time. Thankyou.', NULL, NULL, NULL, NULL, 'अनुप्रयोग नहीं कुछ तकनीकी समस्या के कारण भेजा है। कृपया कुछ देर बाद प्रयास करें। धन्यवाद।', 'App не отправляется из-за какой-то технической проблемы. Пожалуйста, попробуйте через некоторое время. Спасибо.'),
(924, 'App not sent due to some technical issue Please enter valid email Thankyou', 'App not sent due to some technical issue. Please enter valid email. Thankyou.', NULL, NULL, NULL, NULL, 'अनुप्रयोग नहीं कुछ तकनीकी समस्या के कारण भेजा है। कृपया मान्य ईमेल को दर्ज करें। धन्यवाद।', 'App не отправляется из-за какой-то технической проблемы. Пожалуйста, введите действительный адрес электронной почты. Спасибо.'),
(925, 'Tutor_App_Download_Link_sent_to_your_email_successfully', 'Tutor App Download Link sent to your email successfully.', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Репетитор App Скачать Ссылка отправлен на ваш адрес электронной почты успешно.'),
(926, 'booking', 'Booking', 'Booking ', NULL, NULL, NULL, 'बुकिंग', 'бронирование'),
(928, 'manage_tutors', 'Manage tutors', 'Manage tutors', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Управление репетиторов'),
(929, 'Called for admin intervention', 'Called for admin intervention', 'Called for admin intervention', NULL, NULL, NULL, 'व्यवस्थापक हस्तक्षेप का आह्वान', 'Вызывается для вмешательства администратора'),
(930, 'Manage Tutors', 'Manage Tutors', 'Manage Tutors', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Управление Репетиторы'),
(931, 'mysubscriptions', 'Mysubscriptions', 'Mysubscriptions', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мои подписки'),
(932, 'Session Initiated', 'Session Initiated', 'Session Initiated', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'сессия Начатое'),
(933, 'update_contact_info', 'Update contact info', 'Update contact info', NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Обновление контактные данные'),
(934, 'URL_For_Designed_By', 'URL For Designed By', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'URL для Дизайн'),
(935, 'Account Successfully Created', 'Account Successfully Created', NULL, NULL, NULL, NULL, ' खाता सफलतापूर्वक बनाया गया', 'Счет успешно создан'),
(936, 'Activation Email Sent', 'Activation Email Sent', NULL, NULL, NULL, NULL, 'सक्रियण ईमेल भेजा', 'Активация Email Sent'),
(937, 'Account Successfully Created and Activation Email Sent', 'Account Successfully Created and Activation Email Sent', NULL, NULL, NULL, NULL, ' खाता सफलतापूर्वक बनाया और सक्रियण ईमेल भेजा', 'Аккаунт успешно создан активации электронной почты, отправляемой'),
(938, 'student Bookings', 'Student Bookings', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Студенческие Бронирование'),
(939, 'Institute Batches', 'Institute Batches', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Институт Порции'),
(940, 'Boost your sales and scale up all of your classes', 'Boost your sales and scale up all of your classes.', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Повышение продаж и расширение масштабов всех ваших классов.'),
(943, 'You have reached end of the list', 'You have reached end of the list', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Вы достигли конца списка'),
(944, 'credits_for_viewing_the_lead_Are_you_sure_to_continue', 'Credits for viewing the lead Are you sure to continue', NULL, NULL, NULL, NULL, 'नेतृत्व को देखने के लिए आप क्रेडिट जारी रखने के लिए निश्चित हैं', 'Кредиты для просмотра свинца Вы уверены, чтобы продолжить'),
(945, 'to_view_the_lead_details', 'To view the lead details', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Для просмотра информации свинцовые'),
(947, 'free_demo', 'Free demo', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Бесплатный Демо'),
(948, 'hello_I_am', 'Hello I am', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Здравствуйте, я'),
(949, 'Explore', 'Explore', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Исследовать'),
(950, 'Enrich', 'Enrich', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'обогащать'),
(951, 'Excel', 'Excel', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'превосходить'),
(952, 'class for you', 'Class for you', NULL, NULL, NULL, NULL, 'आप के लिए कक्षा', 'Класс для Вас'),
(953, 'Videos', 'Videos', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Видео'),
(954, 'Images', 'Images', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Изображений'),
(955, 'Listen to our teachers speeches and see our video testimonials before you take any decisions', 'Listen to our teachers speeches and see our video testimonials before you take any decisions', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Слушайте наши учителя речи и посмотреть наши видео свидетельства, прежде чем принимать какие-либо решения'),
(956, 'We have rated teachers for safety and convenience as we know how important this is for you', 'We have rated teachers for safety and convenience as we know how important this is for you', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Мы дали оценку учителей для безопасности и удобства, как мы знаем, насколько это важно для вас'),
(957, 'No more emails', 'No more emails', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Нет больше писем'),
(958, 'calls or messaging friends for recommendations', 'Calls or messaging friends for recommendations', NULL, NULL, NULL, NULL, 'कॉल या संदेश भेजने मित्र सिफारिशों के लिए', ' Звонки или друзей по обмену сообщениями для рекомендаций'),
(959, 'get acces to real reviews in seconds', 'Get acces to real reviews in seconds', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Получите доступ к реальным отзывы в секундах'),
(960, 'fee_ (in_credits)', 'Fee  (in credits)', NULL, NULL, NULL, NULL, NULL, NULL),
(961, 'FEATURED ON', 'FEATURED ON', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'размещённые на'),
(962, 'There are', 'There are', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Есть'),
(963, 'lessons taught already', 'Lessons taught already', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Уроки учат уже'),
(964, 'weekly_top', 'Weekly top', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' еженедельное'),
(965, 'Looking for a better way to reach your target audience', 'Looking for a better way to reach your target audience', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Ищете лучший способ добраться до вашей целевой аудитории'),
(966, 'We can help', 'We can help', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Мы можем помочь'),
(967, 'just list with us', 'Just list with us', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Просто список с нами'),
(968, 'Feel_free_to_call_us_anytime_on', 'Feel free to call us anytime on', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Не стесняйтесь, звоните нам в любое время на'),
(970, 'please_become_premium_member_to_avail_additional_features_like', 'Please become premium member to avail additional features like', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Пожалуйста, стать членом премиум воспользоваться дополнительные функции, такие как'),
(971, 'booking_tutors', 'Booking tutors', NULL, NULL, NULL, NULL, 'बुकिंग ट्यूटर्स', 'Бронирование отелей наставники'),
(972, 'and', 'And', NULL, NULL, NULL, NULL, ' तथा', 'А также'),
(973, 'sending_messages', 'Sending messages', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Отправка сообщения'),
(974, 'credits_required_to_become_premium_member ', 'Credits required to become premium member ', NULL, NULL, NULL, NULL, 'क्रेडिट प्रीमियम सदस्य बनने के लिए आवश्यक', 'Кредиты должны стать премиум-членом'),
(975, 'in_credits', 'In credits', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'В кредитах'),
(976, 'admin_commission_val', 'Admin commission val', NULL, NULL, NULL, NULL, ' व्यवस्थापक आयोग वैल', ' Администратор комиссии Вэл'),
(977, 'with_credits', 'With credits', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'С помощью кредитов'),
(978, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(979, 'slots_available', 'Slots available', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Свободные слоты'),
(980, 'your_slot_with_the_tutor_booked_successfully_Once_tutor_approved_your_booking and_initiated_the_session_you_can_start_the_course_on_the_booked_date', 'Your slot with the tutor booked successfully Once tutor approved your booking and initiated the session you can start the course on the booked date', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(982, 'your_slot_booked_successfully_Once_isntitute_approved_your_booking and_tutor_initiated_the_session_you_can_start_the_course_on_course_starting_date', 'Your slot booked successfully Once isntitute approved your booking and tutor initiated the session you can start the course on course starting date', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', NULL),
(983, 'enter_only_one_timeslot_for_one_batch', 'Enter only one timeslot for one batch', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Введите только один временной интервал для одной партии'),
(984, 'tutor(s)_not_available.', 'Tutor(s) not available.', NULL, NULL, NULL, NULL, NULL, NULL),
(985, 'Phone_Num', 'Phone Num', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Телефон Num'),
(986, 'rating', 'Rating', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Рейтинг'),
(987, 'out_of', 'Out of', NULL, NULL, NULL, NULL, 'अभी बुक करें', ' Снаружи'),
(991, 'No Student enrolled in this batch', 'No Student enrolled in this batch', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Нет Студент поступил в этой партии'),
(992, 'Invalid request Or Batch not yet completed', 'Invalid request Or Batch not yet completed', NULL, NULL, NULL, NULL, 'अभी बुक करें', ' Неверный запрос или пакетным еще не завершена'),
(993, 'You dont have permission to perform this action', 'You dont have permission to perform this action', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Вы не имеете прав доступа к этой странице'),
(994, 'you_already_enrolled_in_this_batch_and_your_course_not_yet_completed', 'You already enrolled in this batch and your course not yet completed', NULL, NULL, NULL, NULL, NULL, NULL),
(995, 'student_present_status', 'Student present status', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Студент Современное состояние'),
(996, 'certificates_of', 'Certificates of', NULL, NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'Сертификаты'),
(997, 'Frequently', 'Frequently', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Часто'),
(998, 'Asked_Questions', 'Asked Questions', NULL, NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'задаваемые вопросы'),
(999, 'See_our_frequently_asked_questions', 'See our frequently asked questions', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Смотрите наши часто задаваемые вопросы'),
(1000, 'Total Bookings', 'Total Bookings', NULL, NULL, NULL, NULL, 'अभी बुक करें', ' Всего Бронирование'),
(1001, 'Bookings Pending', 'Bookings Pending', NULL, NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'Заказы ожидающие'),
(1002, 'Bookings Completd', 'Bookings Completd', NULL, NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'Бронирование завершено'),
(1003, 'Bookings Approved', 'Bookings Approved', NULL, NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'Заказы утвержден'),
(1004, 'Open Leads', 'Open Leads', NULL, NULL, NULL, NULL, 'अभी बुक करें', ' открытые Ведет'),
(1005, 'Institue Enrolled Courses', 'Institue Enrolled Courses', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Институт зачислен Курсы'),
(1006, 'Booking Completd', 'Booking Completd', NULL, NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'Бронирование полная'),
(1007, 'Booking Running', 'Booking Running', NULL, NULL, NULL, NULL, 'एक शिक्षक के रूप में', 'Бронирование работает'),
(1008, 'Total Tutoring Courses', 'Total Tutoring Courses', NULL, NULL, NULL, NULL, 'अभी बुक करें', ' Всего курсы Репетиторство'),
(1009, 'admin_commission_percentage_in_credits', 'Admin commission percentage in credits', NULL, NULL, NULL, NULL, 'व्यवस्थापक आयोग प्रतिशत (क्रेडिट्स में)', 'Администратор комиссии процент по кредитам'),
(1018, 'Account', 'Account', NULL, NULL, NULL, NULL, 'एकेडमिक', 'Счет'),
(1024, 'Total_Payments', 'Total Payments', NULL, NULL, NULL, NULL, 'भाषा जोड़े', 'Общая сумма выплат'),
(1025, 'Users Information', 'Users Information', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Пользователи информация'),
(1026, 'Package Subscriptions', 'Package Subscriptions', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Пакет подписки'),
(1027, 'Enrolled Courses', 'Enrolled Courses', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Зарегистрировавшиеся Курсы'),
(1028, 'My Leads', 'My Leads', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Мои Ведет'),
(1030, 'please_select_date,on_which_you_want_to_start_the_course', 'Please select date,on which you want to start the course', NULL, NULL, NULL, NULL, NULL, NULL),
(1032, 'viewing_student_posted_leads', 'Viewing student posted leads', NULL, NULL, NULL, NULL, 'अभी बुक करें', ' Просмотр студентов размещены потенциальных клиентов'),
(1033, 'Discount:', 'Discount:', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'скидка:'),
(1034, 'View Public Profile', 'View Public Profile', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Посмотреть профиль'),
(1035, 'Update Info', 'Update Info', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Обновить данные'),
(1036, 'Mobile', 'Mobile', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'мобильный'),
(1037, 'EX: 15 days or 2 months or ..', 'EX: 15 days or 2 months or ..', NULL, NULL, NULL, NULL, NULL, NULL),
(1038, 'Discount', 'Discount', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'скидка'),
(1039, 'Do you have nnn your own vehicle to travel', 'Do you have nnn your own vehicle to travel', NULL, NULL, NULL, NULL, NULL, ' Showing translation for Do you have on your own vehicle to travel Translate instead Do you have nnn your own vehicle to travel Есть ли у вас на вашем собственном автомобиле путешествовать'),
(1040, 'in Km', 'In Km', NULL, NULL, NULL, NULL, 'क्रेडिट्स', 'В Km'),
(1042, 'View_Public_Profile', 'View Public Profile', NULL, NULL, NULL, NULL, NULL, ' Посмотреть профиль'),
(1043, 'credits_transaction_history', 'Credits Transaction History', NULL, NULL, NULL, NULL, NULL, ' Кредиты История транзакций'),
(1044, 'approved', 'Approved', NULL, NULL, NULL, NULL, NULL, 'утвержденный'),
(1045, 'completed', 'Completed', NULL, NULL, NULL, NULL, NULL, 'Завершенный'),
(1046, 'claim_for_admin_intervention', 'Claim for admin intervention', NULL, NULL, NULL, NULL, NULL, 'Заявление для администратора вмешательства'),
(1047, 'closed', 'Closed', NULL, NULL, NULL, NULL, NULL, 'Закрыто'),
(1048, 'SSC certificate of Student', 'SSC certificate of Student', NULL, NULL, NULL, NULL, NULL, 'ССК сертификат студента'),
(1049, 'Select Package', 'Select Package', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Выберите пакет'),
(1051, 'Search Your Teacher', 'Search Your Teacher', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Поиск своего учителя'),
(1052, 'Contact Details', 'Contact Details', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Контактная информация'),
(1053, 'General Inquiries', 'General Inquiries', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Общие запросы'),
(1054, 'Media Requests', 'Media Requests', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Медиа-запросы'),
(1055, 'Offline Support', 'Offline Support', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Поддержка в автономном режиме'),
(1056, 'follow us', 'Follow us', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Подписывайтесь на нас'),
(1057, 'Google Plus', 'Google Plus', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Гугл плюс'),
(1058, 'Contact Form', 'Contact Form', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Форма обратной связи'),
(1059, 'Select Course', 'Select Course', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Выберите курс'),
(1061, 'We will never sell or rent your private info', 'We will never sell or rent your private info', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', ' Мы никогда не будем продавать или сдавать в аренду вашу личную информацию'),
(1062, 'Credits required for sending message', 'Credits required for sending message', NULL, NULL, NULL, NULL, 'क्रेडिट्स', 'Кредиты, необходимые для отправки сообщения'),
(1063, 'My Reviews', 'My Reviews', NULL, NULL, NULL, NULL, 'अभी बुक करें', 'Мои отзывы'),
(1064, 'Hello My name is Diana and', 'Hello My name is Diana and', NULL, NULL, NULL, NULL, 'लेनदेन इतिहास क्रेडिट्स', 'Здравствуйте! Меня зовут Диана и'),
(1065, 'My message is', 'My message is', NULL, NULL, NULL, NULL, 'अभी बुक करें', ' Мое сообщение'),
(1066, 'institute(s)_not_available.', 'Institute(s) not available.', NULL, NULL, NULL, NULL, NULL, NULL),
(1067, 'mobile_number', 'Mobile number', NULL, NULL, NULL, NULL, NULL, 'Мобильный номер'),
(1068, 'profile_pic', 'Profile pic', NULL, NULL, NULL, NULL, NULL, ' Картинка профиля'),
(1070, 'you_don\'t_have_enough_credits_to_view_the_lead_details. Please', 'You don\'t have enough credits to view the lead details. Please', NULL, NULL, NULL, NULL, NULL, NULL),
(1071, '_get_credits_here', ' get credits here.', NULL, NULL, NULL, NULL, NULL, NULL),
(1072, 'example_format', 'Example format', NULL, NULL, NULL, NULL, NULL, 'Пример формата'),
(1073, 'cancelled_before_course_started', 'Cancelled before course started', NULL, NULL, NULL, NULL, 'रद्द करना', 'Отменено до начала курс'),
(1074, 'cancelled_when_course_running', 'Cancelled when course running', NULL, NULL, NULL, NULL, 'रद्द करना', 'Отменено, когда курс работает'),
(1075, 'cancelled_after_course_completed', 'Cancelled after course completed', NULL, NULL, NULL, NULL, 'रद्द करना', ' Отменено после курса завершены'),
(1077, 'no_slots_available.', 'No slots available.', NULL, NULL, NULL, NULL, NULL, NULL),
(1079, 'please_become_premium_member_to_send_message_to_tutor', 'Please become premium member to send message to tutor', NULL, NULL, NULL, NULL, NULL, ' Пожалуйста, стать членом премиум, чтобы отправить сообщение репетитору'),
(1080, 'please_login_to_continue', 'Please login to continue', NULL, NULL, NULL, NULL, NULL, 'Пожалуйста, войдите, чтобы продолжить'),
(1081, 'please_login_to_send_message', 'Please login to send message', NULL, NULL, NULL, NULL, NULL, 'Пожалуйста, войдите, чтобы отправить сообщение'),
(1082, 'Need help finding a tutor?', 'Need help finding a tutor?', NULL, NULL, NULL, NULL, NULL, NULL),
(1083, 'MSG_LANGUAGE_ADDED', 'MSG LANGUAGE ADDED', NULL, NULL, NULL, NULL, NULL, 'MSG Добавленный язык'),
(1084, 'You got a message from Student Below are the details', 'You got a message from Student Below are the details', NULL, NULL, NULL, NULL, NULL, 'Вы получили сообщение от студента Ниже приведены подробные сведения'),
(1087, 'avail_in_students_course_search_results', 'Avail top in students course search results', NULL, NULL, NULL, NULL, NULL, 'Свободна в результатах поиска студентов курса'),
(1088, '_get_credits_here.', ' get credits here.', NULL, NULL, NULL, NULL, NULL, NULL),
(1089, '2', '2', NULL, NULL, NULL, NULL, NULL, NULL),
(1090, 'You got a message from Tutor Below are the details', 'You got a message from Tutor Below are the details', NULL, NULL, NULL, NULL, NULL, 'Вы получили сообщение от Tutor Ниже приведены подробные сведения'),
(1091, 'You have reached end of the list.', 'You have reached end of the list.', NULL, NULL, NULL, NULL, NULL, NULL),
(1092, 'You got a message from Institute Below are the details', 'You got a message from Institute Below are the details', NULL, NULL, NULL, NULL, NULL, 'Вы получили сообщение из Института Ниже приведены подробные сведения'),
(1093, 'not_yet_started', 'Not yet started', NULL, NULL, NULL, NULL, NULL, NULL),
(1094, 'you_do_not_have_enough_credits_to_book_the_tutor_Please_get_required_credits_here', 'You do not have enough credits to book the tutor Please get required credits here', NULL, NULL, NULL, NULL, NULL, NULL),
(1095, 'No Student enrolled in this batch.', 'No Student enrolled in this batch.', NULL, NULL, NULL, NULL, NULL, NULL),
(1096, 'no_categories_available', 'No categories available', NULL, NULL, NULL, NULL, NULL, NULL),
(1097, 'sub_Locaitons', 'Sub Locaitons', NULL, NULL, NULL, NULL, NULL, NULL),
(1098, 'you_do_not_have_enough_credits_to_enroll_in_the_institute_Please_get_required_credits_here', 'You do not have enough credits to enroll in the institute Please get required credits here', NULL, NULL, NULL, NULL, NULL, NULL),
(1099, 'you_already_booked_this_tutor_and_your_course_not_yet_completed', 'You already booked this tutor and your course not yet completed', NULL, NULL, NULL, NULL, NULL, NULL),
(1100, 'minutes', 'Minutes', NULL, NULL, NULL, NULL, NULL, 'минут'),
(1101, 'Thanks for rating the tutor Your review successfully sent to the Tutor', 'Thanks for rating the tutor Your review successfully sent to the Tutor', NULL, NULL, NULL, NULL, NULL, NULL),
(1102, 'Company_Name', 'Company Name', NULL, NULL, NULL, NULL, NULL, 'название компании'),
(1103, 'role', 'Role', NULL, NULL, NULL, NULL, NULL, NULL),
(1104, 'From', 'From', NULL, NULL, NULL, NULL, NULL, 'Из'),
(1105, 'To', 'To', NULL, NULL, NULL, NULL, NULL, NULL),
(1106, 'Forgot your password?', 'Forgot your password?', NULL, NULL, NULL, NULL, NULL, NULL),
(1107, 'are you sure?', 'Are you sure?', NULL, NULL, NULL, NULL, NULL, NULL),
(1108, 'Please send your request after twenty four hours of the Batch closed time Thank you', 'Please send your request after twenty four hours of the Batch closed time Thank you', NULL, NULL, NULL, NULL, NULL, NULL),
(1109, 'continue_course', 'Continue course', NULL, NULL, NULL, NULL, NULL, NULL),
(1110, 'Show_Available_Records_Count_in_Search_Filters', 'Show Available Records Count in Search Filters', NULL, NULL, NULL, NULL, NULL, NULL),
(1111, 'Advantages_Section', 'Advantages Section', NULL, NULL, NULL, NULL, NULL, NULL),
(1112, 'Featured_on_Section', 'Featured on Section', NULL, NULL, NULL, NULL, NULL, NULL),
(1113, 'Are_You_A_Teacher_Section', 'Are You A Teacher Section', NULL, NULL, NULL, NULL, NULL, NULL),
(1114, 'Footer_Section', 'Footer Section', NULL, NULL, NULL, NULL, NULL, NULL),
(1115, 'Get_Our_App_Section', 'Get Our App Section', NULL, NULL, NULL, NULL, NULL, NULL),
(1116, 'Primary_Footer_Section', 'Primary Footer Section', NULL, NULL, NULL, NULL, NULL, NULL),
(1117, 'Bottom_Section', 'Bottom Section', NULL, NULL, NULL, NULL, NULL, NULL),
(1118, 'Top_Most_Section', 'Top Most Section', NULL, NULL, NULL, NULL, NULL, NULL),
(1119, 'CRUD_opeartions_disabled_in_Demo_version', 'CRUD opeartions disabled in Demo version', NULL, NULL, NULL, NULL, NULL, NULL),
(1120, 'Please_do_not_delete_first_4_rows_as_they_are_deafult_pages_in_the_system', 'Please do not delete first 4 rows as they are deafult pages in the system', NULL, NULL, NULL, NULL, NULL, NULL),
(1121, 'Payment failed : ', 'Payment failed : ', NULL, NULL, NULL, NULL, NULL, NULL),
(1122, 'Seller_Id', 'Seller Id', NULL, NULL, NULL, NULL, NULL, NULL),
(1123, 'Secret_Word', 'Secret Word', NULL, NULL, NULL, NULL, NULL, NULL),
(1124, 'Is_Demo', 'Is Demo', NULL, NULL, NULL, NULL, NULL, NULL),
(1125, 'manual_payment_status', 'Manual payment status', NULL, NULL, NULL, NULL, NULL, NULL),
(1126, 'Enter you comments / Enter your transaction details so that admin can respond', 'Enter you comments / Enter your transaction details so that admin can respond', NULL, NULL, NULL, NULL, NULL, NULL),
(1127, 'Admin Response', 'Admin Response', NULL, NULL, NULL, NULL, NULL, NULL),
(1128, 'Subscription Date', 'Subscription Date', NULL, NULL, NULL, NULL, NULL, NULL),
(1129, 'Amount Paid', 'Amount Paid', NULL, NULL, NULL, NULL, NULL, NULL),
(1130, 'Enter you comments', 'Enter you comments', NULL, NULL, NULL, NULL, NULL, NULL),
(1131, 'payments', 'Payments', NULL, NULL, NULL, NULL, NULL, NULL),
(1132, 'Enter your comments so that user can respond', 'Enter your comments so that user can respond', NULL, NULL, NULL, NULL, NULL, NULL),
(1133, 'User Response', 'User Response', NULL, NULL, NULL, NULL, NULL, NULL),
(1134, 'Please select', 'Please select', NULL, NULL, NULL, NULL, NULL, NULL),
(1135, 'Payment Received?', 'Payment Received?', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `pre_languagewords` (`lang_id`, `lang_key`, `english`, `spanish`, `bengali`, `french`, `japanese`, `hindi`, `russian`) VALUES
(1136, 'Reference No', 'Reference No', NULL, NULL, NULL, NULL, NULL, NULL),
(1137, 'pending payments', 'Pending payments', NULL, NULL, NULL, NULL, NULL, NULL),
(1138, 'Payment success', 'Payment success', NULL, NULL, NULL, NULL, NULL, NULL),
(1139, 'OnlineOffline_gateway', 'OnlineOffline gateway', NULL, NULL, NULL, NULL, NULL, NULL),
(1140, 'Purse_WMZ', 'Purse WMZ', NULL, NULL, NULL, NULL, NULL, NULL),
(1141, 'Secret_key_for_WMZ', 'Secret key for WMZ', NULL, NULL, NULL, NULL, NULL, NULL),
(1142, 'Purse_WME', 'Purse WME', NULL, NULL, NULL, NULL, NULL, NULL),
(1143, 'Secret_key_for_WME', 'Secret key for WME', NULL, NULL, NULL, NULL, NULL, NULL),
(1144, 'Purse_WMR', 'Purse WMR', NULL, NULL, NULL, NULL, NULL, NULL),
(1145, 'Secret_key_for_WMR', 'Secret key for WMR', NULL, NULL, NULL, NULL, NULL, NULL),
(1146, 'Purse_WMU', 'Purse WMU', NULL, NULL, NULL, NULL, NULL, NULL),
(1147, 'Secret_key_for_WMU', 'Secret key for WMU', NULL, NULL, NULL, NULL, NULL, NULL),
(1148, 'Instructions', 'Instructions', NULL, NULL, NULL, NULL, NULL, NULL),
(1149, 'Payment_Gateway', 'Payment Gateway', NULL, NULL, NULL, NULL, NULL, NULL),
(1150, 'Title : ', 'Title : ', NULL, NULL, NULL, NULL, NULL, NULL),
(1151, 'buy_courses', 'Buy courses', NULL, NULL, NULL, NULL, NULL, NULL),
(1152, 'join', 'Join', NULL, NULL, NULL, NULL, NULL, NULL),
(1153, 'Initiate Meeting : ', 'Initiate Meeting : ', NULL, NULL, NULL, NULL, NULL, NULL),
(1154, 'with_meeting_id', 'With meeting id', NULL, NULL, NULL, NULL, NULL, NULL),
(1155, 'join_now', 'Join now', NULL, NULL, NULL, NULL, NULL, NULL),
(1156, 'Invalid Meeting Details', 'Invalid Meeting Details', NULL, NULL, NULL, NULL, NULL, NULL),
(1157, 'checkout', 'Checkout', NULL, NULL, NULL, NULL, NULL, NULL),
(1158, 'Sell_Courses_Online', 'Sell Courses Online', NULL, NULL, NULL, NULL, NULL, NULL),
(1159, 'Course_Title', 'Course Title', NULL, NULL, NULL, NULL, NULL, NULL),
(1160, 'About_the_Course', 'About the Course', NULL, NULL, NULL, NULL, NULL, NULL),
(1161, 'Skill_Level', 'Skill Level', NULL, NULL, NULL, NULL, NULL, NULL),
(1162, 'Languages', 'Languages', NULL, NULL, NULL, NULL, NULL, NULL),
(1163, 'Preview_File', 'Preview File', NULL, NULL, NULL, NULL, NULL, NULL),
(1164, 'Course_Price', 'Course Price', NULL, NULL, NULL, NULL, NULL, NULL),
(1165, 'Maximum_number_of_Downloads', 'Maximum number of Downloads', NULL, NULL, NULL, NULL, NULL, NULL),
(1166, 'max_downloads', 'Max downloads', NULL, NULL, NULL, NULL, NULL, NULL),
(1167, 'Curriculum', 'Curriculum', NULL, NULL, NULL, NULL, NULL, NULL),
(1168, 'Curriculum_Titles', 'Curriculum Titles', NULL, NULL, NULL, NULL, NULL, NULL),
(1169, 'Curriculum_Files', 'Curriculum Files', NULL, NULL, NULL, NULL, NULL, NULL),
(1170, 'Please upload files only with allowed formats', 'Please upload files only with allowed formats', NULL, NULL, NULL, NULL, NULL, NULL),
(1171, 'Allowed File Foramts are', 'Allowed File Foramts are', NULL, NULL, NULL, NULL, NULL, NULL),
(1172, 'publish', 'Publish', NULL, NULL, NULL, NULL, NULL, NULL),
(1173, 'List_Selling_Courses', 'List Selling Courses', NULL, NULL, NULL, NULL, NULL, NULL),
(1174, 'file', 'File', NULL, NULL, NULL, NULL, NULL, NULL),
(1175, 'Curriculum_Source', 'Curriculum Source', NULL, NULL, NULL, NULL, NULL, NULL),
(1176, 'Your course has been published successfully', 'Your course has been published successfully', NULL, NULL, NULL, NULL, NULL, NULL),
(1177, 'Published_Courses', 'Published Courses', NULL, NULL, NULL, NULL, NULL, NULL),
(1178, 'My_Selling_Courses_List', 'My Selling Courses List', NULL, NULL, NULL, NULL, NULL, NULL),
(1179, 'title', 'Title', NULL, NULL, NULL, NULL, NULL, NULL),
(1180, 'price', 'Price', NULL, NULL, NULL, NULL, NULL, NULL),
(1181, 'Your course not published due to invalid input data', 'Your course not published due to invalid input data', NULL, NULL, NULL, NULL, NULL, NULL),
(1182, 'My_Selling_Courses', 'My Selling Courses', NULL, NULL, NULL, NULL, NULL, NULL),
(1183, 'Are you sure that you want to delete this record?', 'Are you sure that you want to delete this record?', NULL, NULL, NULL, NULL, NULL, NULL),
(1184, 'Record_Deleted_Successfully', 'Record Deleted Successfully', NULL, NULL, NULL, NULL, NULL, NULL),
(1185, 'View_Curriculum', 'View Curriculum', NULL, NULL, NULL, NULL, NULL, NULL),
(1186, 'Selling_Course_Curriculum', 'Selling Course Curriculum', NULL, NULL, NULL, NULL, NULL, NULL),
(1187, 'Selling_Course_Information', 'Selling Course Information', NULL, NULL, NULL, NULL, NULL, NULL),
(1188, 'Admin_Approved', 'Admin Approved', NULL, NULL, NULL, NULL, NULL, NULL),
(1189, 'Created_At', 'Created At', NULL, NULL, NULL, NULL, NULL, NULL),
(1190, 'Updated_At', 'Updated At', NULL, NULL, NULL, NULL, NULL, NULL),
(1191, 'File_Size', 'File Size', NULL, NULL, NULL, NULL, NULL, NULL),
(1192, 'back', 'Back', NULL, NULL, NULL, NULL, NULL, NULL),
(1193, 'Note1', 'Note1', NULL, NULL, NULL, NULL, NULL, NULL),
(1194, 'Admin_Commission_Percentage_On_Each_Purchase', 'Admin Commission Percentage On Each Purchase', NULL, NULL, NULL, NULL, NULL, NULL),
(1195, 'Note2', 'Note2', NULL, NULL, NULL, NULL, NULL, NULL),
(1196, 'Published_By', 'Published By', NULL, NULL, NULL, NULL, NULL, NULL),
(1197, 'Tutor_Selling_Courses', 'Tutor Selling Courses', NULL, NULL, NULL, NULL, NULL, NULL),
(1198, 'selling_courses', 'Selling courses', NULL, NULL, NULL, NULL, NULL, NULL),
(1199, 'buy_course', 'Buy course', NULL, NULL, NULL, NULL, NULL, NULL),
(1200, 'Buy_This_Course', 'Buy This Course', NULL, NULL, NULL, NULL, NULL, NULL),
(1201, 'lectures', 'Lectures', NULL, NULL, NULL, NULL, NULL, NULL),
(1202, 'Maximum_Downloads', 'Maximum Downloads', NULL, NULL, NULL, NULL, NULL, NULL),
(1203, 'attachments', 'Attachments', NULL, NULL, NULL, NULL, NULL, NULL),
(1204, 'add_more', 'Add more', NULL, NULL, NULL, NULL, NULL, NULL),
(1205, 'remove_this', 'Remove this', NULL, NULL, NULL, NULL, NULL, NULL),
(1206, 'Preview_Image', 'Preview Image', NULL, NULL, NULL, NULL, NULL, NULL),
(1207, 'are_allowed_formats_for_preview_image', 'Are allowed formats for preview image', NULL, NULL, NULL, NULL, NULL, NULL),
(1208, '_are_allowed_formats_for_preview_image', ' are allowed formats for preview image', NULL, NULL, NULL, NULL, NULL, NULL),
(1209, 'Your course has been updated successfully', 'Your course has been updated successfully', NULL, NULL, NULL, NULL, NULL, NULL),
(1210, 'Course_Image', 'Course Image', NULL, NULL, NULL, NULL, NULL, NULL),
(1211, '_are_allowed_formats_for_course_image', ' are allowed formats for course image', NULL, NULL, NULL, NULL, NULL, NULL),
(1212, 'Click_to_view', 'Click to view', NULL, NULL, NULL, NULL, NULL, NULL),
(1213, 'pay', 'Pay', NULL, NULL, NULL, NULL, NULL, NULL),
(1214, 'By placing the order You have read and agreed to our', 'By placing the order You have read and agreed to our', NULL, NULL, NULL, NULL, NULL, NULL),
(1215, 'Terms of Use and Privacy Policy', 'Terms of Use and Privacy Policy', NULL, NULL, NULL, NULL, NULL, NULL),
(1216, 'Choose_Payment_Method', 'Choose Payment Method', NULL, NULL, NULL, NULL, NULL, NULL),
(1217, 'Please_select_payment_gateway', 'Please select payment gateway', NULL, NULL, NULL, NULL, NULL, NULL),
(1218, 'Total Amount', 'Total Amount', NULL, NULL, NULL, NULL, NULL, NULL),
(1219, 'Download', 'Download', NULL, NULL, NULL, NULL, NULL, NULL),
(1220, 'My_Course_Purchases', 'My Course Purchases', NULL, NULL, NULL, NULL, NULL, NULL),
(1221, 'Purchased_On', 'Purchased On', NULL, NULL, NULL, NULL, NULL, NULL),
(1222, 'Download_Course_Curriculum', 'Download Course Curriculum', NULL, NULL, NULL, NULL, NULL, NULL),
(1223, 'You_have_reached_maximum_limit_of_downloads_Please_purchase_the_course_again_to_download_the_fiels_Thank_you', 'You have reached maximum limit of downloads Please purchase the course again to download the fiels Thank you', NULL, NULL, NULL, NULL, NULL, NULL),
(1224, 'Course_Curriculum_could_not_be_downloaded_due_to_some_technical_issue_Please_download_after_some_time', 'Course Curriculum could not be downloaded due to some technical issue Please download after some time', NULL, NULL, NULL, NULL, NULL, NULL),
(1225, 'Purchased_Courses', 'Purchased Courses', NULL, NULL, NULL, NULL, NULL, NULL),
(1226, 'Payment_from_Admin', 'Payment from Admin', NULL, NULL, NULL, NULL, NULL, NULL),
(1227, 'View_Download_History', 'View Download History', NULL, NULL, NULL, NULL, NULL, NULL),
(1228, 'Course_Download_History', 'Course Download History', NULL, NULL, NULL, NULL, NULL, NULL),
(1229, 'View_Purchased_Courses', 'View Purchased Courses', NULL, NULL, NULL, NULL, NULL, NULL),
(1230, 'sections', 'Sections', NULL, NULL, NULL, NULL, NULL, NULL),
(1231, 'list_sections', 'List sections', NULL, NULL, NULL, NULL, NULL, NULL),
(1232, 'section_name', 'Section name', NULL, NULL, NULL, NULL, NULL, NULL),
(1233, 'Get_This_Course', 'Get This Course', NULL, NULL, NULL, NULL, NULL, NULL),
(1234, 'import', 'Import', NULL, NULL, NULL, NULL, NULL, NULL),
(1235, 'file import', 'File import', NULL, NULL, NULL, NULL, NULL, NULL),
(1236, 'file to import', 'File to import', NULL, NULL, NULL, NULL, NULL, NULL),
(1237, '_are_allowed_formats_for_preview_file', ' are allowed formats for preview file', NULL, NULL, NULL, NULL, NULL, NULL),
(1238, 'maximum_allowed_file_size_is_20_MB_for_each_file', 'Maximum allowed file size is 20 MB for each file', NULL, NULL, NULL, NULL, NULL, NULL),
(1239, 'view_uploaded_curriculum', 'View uploaded curriculum', NULL, NULL, NULL, NULL, NULL, NULL),
(1240, 'action', 'Action', NULL, NULL, NULL, NULL, NULL, NULL),
(1241, 'No_Details_Found', 'No Details Found', NULL, NULL, NULL, NULL, NULL, NULL),
(1242, 'No_Curriculum_added', 'No Curriculum added', NULL, NULL, NULL, NULL, NULL, NULL),
(1243, 'checkout_with_Razorpay', 'Checkout with Razorpay', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pre_locations`
--

CREATE TABLE `pre_locations` (
  `id` int(11) NOT NULL,
  `location_name` varchar(512) NOT NULL DEFAULT '',
  `parent_location_id` int(11) NOT NULL DEFAULT '0',
  `code` char(8) DEFAULT NULL,
  `slug` varchar(256) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_login_attempts`
--

CREATE TABLE `pre_login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_messages`
--

CREATE TABLE `pre_messages` (
  `message_id` bigint(20) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `course_slug` varchar(512) DEFAULT NULL,
  `message` varchar(1000) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_newsletter_subscriptions`
--

CREATE TABLE `pre_newsletter_subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `email_address` varchar(256) DEFAULT NULL,
  `created` datetime NOT NULL,
  `ipaddress` varchar(20) NOT NULL,
  `browser` varchar(512) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_packages`
--

CREATE TABLE `pre_packages` (
  `id` int(11) NOT NULL,
  `package_name` varchar(512) NOT NULL DEFAULT '',
  `package_for` enum('Student','Tutor','Institute','All') NOT NULL,
  `description` text NOT NULL,
  `image` varchar(50) NOT NULL DEFAULT '',
  `credits` int(11) NOT NULL DEFAULT '0',
  `discount_type` enum('Value','Percent') NOT NULL DEFAULT 'Percent',
  `discount` int(11) NOT NULL DEFAULT '0',
  `package_cost` varchar(512) NOT NULL DEFAULT '',
  `status` enum('Active','In-Active') NOT NULL DEFAULT 'Active',
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_pages`
--

CREATE TABLE `pre_pages` (
  `id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `meta_tag` varchar(512) NOT NULL,
  `meta_description` varchar(512) NOT NULL,
  `seo_keywords` varchar(512) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_pages`
--

INSERT INTO `pre_pages` (`id`, `name`, `slug`, `description`, `meta_tag`, `meta_description`, `seo_keywords`, `status`) VALUES
(1, 'About Us', 'about-us', '<div class="container">\r\n	<div class="row row-top">\r\n		<div class="col-sm-12">\r\n			<h2 class="heading-line">\r\n				Why Choose Us</h2>\r\n		</div>\r\n		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\r\n			<div class="about-block">\r\n				<div class="media-left">\r\n					&nbsp;</div>\r\n				<div class="media-body">\r\n					<h4>\r\n						Exceptional tutors.</h4>\r\n					<p>\r\n						We only accept the best from thousands of applicants, so you can choose from the widest range of qualified experts.</p>\r\n				</div>\r\n			</div>\r\n			<div class="about-block">\r\n				<div class="media-left">\r\n					&nbsp;</div>\r\n				<div class="media-body">\r\n					<h4>\r\n						24/7 Access</h4>\r\n					<p>\r\n						School is tough. Getting a tutor is easy. Get a real tutor anytime, anywhere in our online classroom.</p>\r\n				</div>\r\n			</div>\r\n			<div class="about-block">\r\n				<div class="media-left">\r\n					&nbsp;</div>\r\n				<div class="media-body">\r\n					<h4>\r\n						Variety of Subjects</h4>\r\n					<p>\r\n						From Algebra, Calculus and Statistics to English, Chemistry and Physics. Test prep and AP, too.</p>\r\n				</div>\r\n			</div>\r\n		</div>\r\n		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\r\n			<img alt="" class="img-responsive" src="http://dev.mindsworthy.com/tutorsci/assets/front/images/why-choose.png" /></div>\r\n	</div>\r\n</div>\r\n<p>\r\n	&nbsp;</p>\r\n', 'aboutus - tutors', 'about us', 'about us, tutors about, about tutors', 'Active'),
(2, 'How It Works', 'how-it-works', '<section class="how-it-works">\r\n	<div class="container">\r\n		<div class="row row-margin">\r\n			<div class="col-sm-12 ">\r\n				<h2 class="heading">\r\n					How Does This <span>Work</span></h2>\r\n			</div>\r\n			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">\r\n				<div class="step-block center-block">\r\n					<div class="step-img">\r\n						<img alt="" src="http://dev.mindsworthy.com/tutorsci/assets/front/images/step1.png" />\r\n						<div class="step-icon">\r\n							1</div>\r\n					</div>\r\n					<h4>\r\n						Start Your Search</h4>\r\n					<p>\r\n						Search for online tutoring. Need help with your search? Request a tutor and we&rsquo;ll have tutors contact you very soon!</p>\r\n				</div>\r\n			</div>\r\n			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">\r\n				<div class="step-block center-block">\r\n					<div class="step-img">\r\n						<img alt="" src="http://dev.mindsworthy.com/tutorsci/assets/front/images/step2.png" />\r\n						<div class="step-icon">\r\n							2</div>\r\n					</div>\r\n					<h4>\r\n						Review</h4>\r\n					<p>\r\n						Read feedback and ratings from parents and students. Detailed tutor profiles also include photos more.</p>\r\n				</div>\r\n			</div>\r\n			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">\r\n				<div class="step-block center-block">\r\n					<div class="step-img">\r\n						<img alt="" src="http://dev.mindsworthy.com/tutorsci/assets/front/images/step3.png" />\r\n						<div class="step-icon">\r\n							3</div>\r\n					</div>\r\n					<h4>\r\n						Schedule</h4>\r\n					<p>\r\n						Communicate directly with tutors to find a time that works for you. Whether you need a single lesson.</p>\r\n				</div>\r\n			</div>\r\n			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">\r\n				<div class="step-block center-block">\r\n					<div class="step-img">\r\n						<img alt="" src="http://dev.mindsworthy.com/tutorsci/assets/front/images/step4.png" />\r\n						<div class="step-icon">\r\n							4</div>\r\n					</div>\r\n					<h4>\r\n						Start Learning</h4>\r\n					<p>\r\n						One-on-one instruction is the most effective way to learn. Let us handle payments and administrative details.</p>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n</section>\r\n<p>\r\n	&nbsp;</p>\r\n', '', '', '', 'Active'),
(3, 'Terms and Conditions', 'terms-and-conditions', '<p>\r\n	<strong>Terms and Conditions</strong></p>\r\n<p>\r\n	These are the terms and condition</p>\r\n<p>\r\n	&nbsp;</p>\r\n', '', '', '', 'Active'),
(4, 'Privacy and Policy', 'privacy-and-policy', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>\r\n', '', '', '', 'Active'),
(5, 'Test Page', 'test-page', '<p>\r\n	Test Page content goes here</p>\r\n', '', '', '', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `pre_payments_data`
--

CREATE TABLE `pre_payments_data` (
  `id` bigint(20) NOT NULL,
  `gateway` varchar(50) DEFAULT NULL,
  `data` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_scroll_news`
--

CREATE TABLE `pre_scroll_news` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `cereated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_sections`
--

CREATE TABLE `pre_sections` (
  `section_id` int(11) NOT NULL,
  `name` varchar(512) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_sections`
--

INSERT INTO `pre_sections` (`section_id`, `name`, `description`) VALUES
(1, 'Advantages_Section', '<section>\r\n        <div class="container">\r\n            <div class="row row-margin">\r\n                <div class="col-md-4 col-sm-4 col-xs-12">\r\n                    <div class="advantage">\r\n                        <div class="media-left">\r\n                            <img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-video.png" alt="..">\r\n                        </div>\r\n                        <div class="media-body">\r\n                            <h4><a href="">Videos &amp; Images</a></h4>\r\n                            <p>Listen to our teachers speeches and see our video testimonials before you take any decisions</p>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n                <div class="col-md-4 col-sm-4 col-xs-12">\r\n                    <div class="advantage">\r\n                        <div class="media-left">\r\n                            <img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-score.png" alt="..">\r\n                        </div>\r\n                        <div class="media-body">\r\n                            <h4><a href="">Quality Scores</a></h4>\r\n                            <p>We have rated teachers for safety and convenience as we know how important this is for you</p>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n                <div class="col-md-4 col-sm-4 col-xs-12">\r\n                    <div class="advantage">\r\n                        <div class="media-left">\r\n                            <img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-calendar.png" alt="..">\r\n                        </div>\r\n                        <div class="media-body">\r\n                            <h4><a href="">Reviews &amp; Ratings</a></h4>\r\n                            <p>No more emails, Calls or messaging friends for recommendations - Get acces to real reviews in seconds</p>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </section>'),
(2, 'Are_You_A_Teacher_Section', '<section>\r\n<div class="container">\r\n        <div class="row row-margin">\r\n            <div class="col-md-12">\r\n                <h2 class="heading">Are you a <span>Teacher</span></h2>\r\n                <p class="heading-tag">Looking for a better way to reach your target audience? We can help - Just list with us <strong>For free</strong>.</p>\r\n            </div>\r\n            <div class="col-md-4 col-sm-4 col-sm-12">\r\n                <div class="choose-block center-block">\r\n                    <div class="icon">\r\n                        <img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-cal.png" alt="">\r\n                        <i class="sub-icon fa fa-check"></i>\r\n                    </div>\r\n                    <p>Boost your sales and scale up all of your classes.</p>\r\n                </div>\r\n            </div>\r\n            <div class="col-md-4 col-sm-4 col-sm-12">\r\n                <div class="choose-block center-block">\r\n                    <div class="icon">\r\n                        <img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-group.png" alt="">\r\n                        <i class="sub-icon fa fa-check"></i>\r\n                    </div>\r\n                    <p>Get a lot of exposure &amp; brand recognition from everyone.</p>\r\n                </div>\r\n            </div>\r\n            <div class="col-md-4 col-sm-4 col-sm-12">\r\n                <div class="choose-block center-block">\r\n                    <div class="icon">\r\n                        <img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/icn-graph.png" alt="">\r\n                        <i class="sub-icon fa fa-check"></i>\r\n                    </div>\r\n                    <p>Participate in various events and school programs whenever you want.</p>\r\n                </div>\r\n            </div>\r\n                    </div>\r\n    </div>\r\n</section>'),
(3, 'Featured_On_Section', '<section class="featured-on">\r\n        <div class="container">\r\n            <div class="row">\r\n                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">\r\n                    <h4>FEATURED ON</h4>\r\n                </div>\r\n                <div class="col-lg-10 col-md-9 col-sm-9 col-xs-12">\r\n                    <ul>\r\n                        <li>\r\n                            <a href="#"><img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/1.png" alt=""></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href="#"><img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/2.png" alt=""></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href="#"><img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/3.png" alt=""></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href="#"><img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/4.png" alt=""></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href="#"><img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/5.png" alt=""></a>\r\n                        </li>\r\n                        <li>\r\n                            <a href="#"><img src="http://10.0.0.14/menorah-tutor-sellingcourses/assets/front/images/6.png" alt=""></a>\r\n                        </li>\r\n                    </ul>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </section>');

-- --------------------------------------------------------

--
-- Table structure for table `pre_seosettings`
--

CREATE TABLE `pre_seosettings` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `allowed_variables` text,
  `seo_title` text,
  `seo_description` text,
  `seo_keywords` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_seosettings`
--

INSERT INTO `pre_seosettings` (`id`, `type`, `allowed_variables`, `seo_title`, `seo_description`, `seo_keywords`, `created`, `updated`) VALUES
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
(21, 'login', '__COURSES__\r\n__LOCATIONS__\r\n__TEACHING_TYPES__', '__COURSES__ TItle', NULL, NULL, '2017-02-06 08:32:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pre_sessions`
--

CREATE TABLE `pre_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_sitetestimonials`
--

CREATE TABLE `pre_sitetestimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `position` varbinary(256) DEFAULT NULL,
  `comments` text,
  `image` varchar(256) DEFAULT NULL,
  `status` enum('Active','In-Active') DEFAULT 'Active',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_student_leads`
--

CREATE TABLE `pre_student_leads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `teaching_type_id` int(11) NOT NULL,
  `present_status` varchar(512) NOT NULL COMMENT 'student''s present status like student or employed etc.',
  `priority_of_requirement` enum('immediately','after_a_week','after_a_month') NOT NULL,
  `duration_needed` varchar(55) NOT NULL,
  `budget` varchar(512) NOT NULL,
  `budget_type` enum('one_time','hourly','monthly') NOT NULL,
  `title_of_requirement` varchar(512) NOT NULL,
  `requirement_details` varchar(1000) NOT NULL,
  `no_of_views` int(11) NOT NULL DEFAULT '0',
  `status` enum('Opened','Closed') NOT NULL DEFAULT 'Opened',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='student posted requirements';

-- --------------------------------------------------------

--
-- Table structure for table `pre_student_locations`
--

CREATE TABLE `pre_student_locations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint(5) DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_student_prefferd_teaching_types`
--

CREATE TABLE `pre_student_prefferd_teaching_types` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `teaching_type_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_student_preffered_courses`
--

CREATE TABLE `pre_student_preffered_courses` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_subscriptions`
--

CREATE TABLE `pre_subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(512) NOT NULL DEFAULT '',
  `user_type` enum('Tutor','Student','Institute') NOT NULL,
  `package_id` int(11) NOT NULL DEFAULT '0',
  `package_name` varchar(512) NOT NULL DEFAULT '',
  `package_cost` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `credits` int(11) NOT NULL,
  `payment_type` varchar(512) NOT NULL,
  `transaction_no` varchar(512) NOT NULL,
  `payment_received` int(11) NOT NULL DEFAULT '0',
  `payer_id` varchar(512) DEFAULT NULL,
  `payer_email` varchar(512) NOT NULL DEFAULT '',
  `payer_name` varchar(512) DEFAULT '',
  `subscribe_date` datetime NOT NULL,
  `user_group_id` int(11) DEFAULT NULL,
  `discount_type` enum('Value','Percent') DEFAULT 'Percent',
  `discount_value` decimal(10,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `payment_updated_user` enum('yes','no','settled') DEFAULT 'no',
  `payment_updated_user_date` timestamp NULL DEFAULT NULL,
  `payment_updated_user_message` text,
  `payment_updated_admin` enum('yes','no','settled') DEFAULT 'no',
  `payment_updated_admin_time` timestamp NULL DEFAULT NULL,
  `payment_updated_admin_message` text,
  `remarks` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_system_settings_fields`
--

CREATE TABLE `pre_system_settings_fields` (
  `field_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `field_name` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `field_key` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `is_required` enum('Yes','No') CHARACTER SET latin1 DEFAULT 'No',
  `field_order` int(10) DEFAULT '0',
  `field_type` enum('select','file','email','text','textarea','checkbox') DEFAULT 'text',
  `field_type_values` text,
  `field_output_value` text,
  `field_type_slug` varchar(20) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_system_settings_fields`
--

INSERT INTO `pre_system_settings_fields` (`field_id`, `type_id`, `field_name`, `field_key`, `is_required`, `field_order`, `field_type`, `field_type_values`, `field_output_value`, `field_type_slug`, `created`, `updated`) VALUES
(9, 16, 'Account SID', 'Account_SID', 'No', 0, 'text', '', '343', NULL, '2015-11-09 12:17:24', NULL),
(10, 16, 'Auth Token', 'Auth_Token', 'No', 0, 'text', '', '23232', NULL, '2015-11-09 12:18:21', NULL),
(14, 13, 'Meta Keyword', 'Meta_Keyword', 'No', 0, 'text', NULL, 'Test Meta Keyword Value ', 'SEO_SETTINGS', '2015-11-09 13:20:57', '2016-11-23 14:43:20'),
(15, 13, 'Meta Description', 'Meta_Description', 'No', 0, 'text', NULL, 'Test Meta Description Value', 'SEO_SETTINGS', '2015-11-09 13:21:15', '2016-11-23 14:43:20'),
(22, 1, 'Site Title', 'Site_Title', 'Yes', 0, 'text', '', 'Tutors Menorah', 'SYSTEM_SETTINGS', '2015-11-24 09:43:37', '2017-02-14 18:43:26'),
(23, 1, 'Address', 'Address', 'Yes', 0, 'textarea', '', 'Silicon Valley, Hitech City , Digital Vidhya', 'SYSTEM_SETTINGS', '2015-11-24 09:46:55', '2017-02-14 18:43:26'),
(24, 1, 'City', 'City', 'Yes', 0, 'text', NULL, 'Hyderabad', 'SYSTEM_SETTINGS', '2015-11-24 09:48:43', '2017-02-14 18:43:26'),
(25, 1, 'State', 'State', 'Yes', 0, 'text', '', 'Telangana', 'SYSTEM_SETTINGS', '2015-11-24 09:49:16', '2017-02-14 18:43:26'),
(26, 1, 'Country', 'Country', 'Yes', 0, 'text', '', 'India', 'SYSTEM_SETTINGS', '2015-11-24 09:52:57', '2017-02-14 18:43:26'),
(27, 1, 'Zipcode', 'Zipcode', 'Yes', 0, 'text', '', '500081', 'SYSTEM_SETTINGS', '2015-11-24 09:53:20', '2017-02-14 18:43:26'),
(28, 1, 'Rights Reserved by', 'Rights_Reserved_by', 'No', 0, 'text', NULL, 'Copyright &copy; 2016  All rights reserved', 'SYSTEM_SETTINGS', '2015-11-24 09:53:45', '2017-02-14 18:43:26'),
(29, 1, 'Phone', 'Phone', 'Yes', 0, 'text', '', '+91 9988776666', 'SYSTEM_SETTINGS', '2015-11-24 09:54:11', '2017-02-14 18:43:26'),
(30, 1, 'Land Line', 'Land_Line', 'No', 0, 'text', '', '+91 40-44556666', 'SYSTEM_SETTINGS', '2015-11-24 09:54:31', '2017-02-14 18:43:26'),
(31, 1, 'Fax', 'Fax', 'No', 0, 'text', '', '1523', 'SYSTEM_SETTINGS', '2015-11-24 09:54:52', '2017-02-14 18:43:26'),
(32, 1, 'Portal Email', 'Portal_Email', 'Yes', 0, 'text', '', 'micheljohn930@gmail.com', 'SYSTEM_SETTINGS', '2015-11-24 09:55:30', '2017-02-14 18:43:26'),
(33, 1, 'Designed By', 'Designed_By', 'No', 0, 'text', NULL, 'Digital Vidhya', 'SYSTEM_SETTINGS', '2015-11-24 09:56:03', '2017-02-14 18:43:26'),
(35, 1, 'Logo', 'Logo', 'No', 0, 'file', '', 'setting_35.png', 'SYSTEM_SETTINGS', '2015-11-24 10:00:58', NULL),
(40, 13, 'Site Description', 'Site_Description', 'Yes', 0, '', '', 'Very useful site for all users', 'SEO_SETTINGS', '2015-11-24 10:16:41', '2016-11-23 14:43:20'),
(49, 13, 'Google Analytics', 'Google_Analytics', 'No', 0, 'text', '', 'Google Analytics', 'SEO_SETTINGS', '2015-11-24 10:37:12', '2016-11-23 14:43:20'),
(50, 23, 'Facebook', 'Facebook', 'Yes', 0, 'text', '', 'https://www.facebook.com', 'SOCIAL_SETTINGS', '2015-11-24 10:41:34', '2016-10-25 14:24:30'),
(51, 23, 'Twitter', 'Twitter', 'No', 0, 'text', '', 'https://twitter.com/', 'SOCIAL_SETTINGS', '2015-11-24 10:42:04', '2016-10-25 14:24:30'),
(52, 23, 'Linkedin', 'Linkedin', 'No', 0, 'text', '', 'https://www.linkedin.com/', 'SOCIAL_SETTINGS', '2015-11-24 10:42:38', '2016-10-25 14:24:30'),
(53, 23, 'Google+', 'Google', 'No', 0, 'text', '', 'https://www.google.co.in/?gfe_rd=cr&ei=FV8EWKenFOOK8Qf4kbmQCg&gws_rd=ssl', 'SOCIAL_SETTINGS', '2015-11-24 10:43:03', '2016-10-25 14:24:30'),
(54, 23, 'Instagram', 'Instagram', 'No', 0, 'text', '', 'https://www.instagram.com/?hl=en', 'SOCIAL_SETTINGS', '2015-11-24 10:43:37', '2016-10-25 14:24:30'),
(55, 23, 'Youtube', 'Youtube', 'No', 0, 'text', '', 'https://www.youtube.com/?hl=en-GB&gl=IN', 'SOCIAL_SETTINGS', '2015-11-24 10:44:16', '2016-10-25 14:24:30'),
(56, 24, 'Email Activation (Yes/No)', 'email_activation', 'Yes', 0, 'select', 'TRUE,FALSE', 'TRUE', 'REGISTRATION_SETTING', '2015-11-24 10:47:00', '2016-11-04 11:52:49'),
(57, 24, 'Track Login IP Address (Yes/No)', 'track_login_ip_address', 'Yes', 0, 'select', 'TRUE,FALSE', 'TRUE', 'REGISTRATION_SETTING', '2015-11-24 10:47:54', '2016-11-04 11:52:49'),
(58, 24, 'Max. Login Attempts', 'maximum_login_attempts', 'Yes', 0, 'text', '', '5', 'REGISTRATION_SETTING', '2015-11-24 10:48:22', '2016-11-04 11:52:49'),
(59, 24, 'Lockout Time (milliseconds)', 'lockout_time', 'Yes', 0, 'text', '', '200', 'REGISTRATION_SETTING', '2015-11-24 10:49:56', '2016-11-04 11:52:49'),
(60, 27, 'Live Merchant Key', 'Live_Merchant_Key', 'Yes', 0, 'text', '', 'Merchant Key', NULL, '2015-11-24 10:55:20', '2016-11-02 19:38:43'),
(61, 27, 'Live Salt', 'Live_Salt', 'Yes', 0, 'text', '', 'Salt', NULL, '2015-11-24 10:55:54', '2016-11-02 19:38:43'),
(62, 27, 'Live URL', 'Live_URL', 'No', 0, 'text', '', 'https://secure.payu.in/_payment', NULL, '2015-11-24 11:02:48', '2016-11-02 19:38:43'),
(63, 27, 'Sandbox Merchant Key', 'Sandbox_Merchant_Key', 'No', 0, 'text', '', 'fnlZps', NULL, '2015-11-24 11:04:04', '2016-11-02 19:38:43'),
(64, 27, 'Sandbox Salt', 'Sandbox_Salt', 'No', 0, 'text', '', 'dY3ExaGJ', NULL, '2015-11-24 11:04:35', '2016-11-02 19:38:43'),
(65, 27, 'Test URL', 'Test_URL', 'No', 0, 'text', '', 'https://test.payu.in/_payment', NULL, '2015-11-24 11:05:04', '2016-11-02 19:38:43'),
(66, 27, 'Account Type(LIve/Sandbox)', 'Account_TypeLIveSandbox', 'No', 0, 'select', 'Sandbox,Live', 'Sandbox', NULL, '2015-11-24 11:05:59', '2016-11-02 19:38:43'),
(67, 28, 'Paypal Email', 'Paypal_Email', 'No', 0, 'text', '', 'digionlineexam@gmail.com', 'PAYPAL_SETTINGS', '2015-11-24 11:07:34', '2016-11-02 19:44:58'),
(68, 28, 'Currency Code', 'Currency_Code', 'No', 0, 'text', '', 'USD', 'SYSTEM_SETTINGS', '2015-11-24 11:08:29', '2016-11-02 19:44:58'),
(69, 28, 'Account Type(Production/Sandbox)', 'Account_TypeProductionSandbox', 'No', 0, 'text', '', 'Sandbox', 'PAYPAL_SETTINGS', '2015-11-24 11:09:17', '2016-11-02 19:44:58'),
(70, 28, 'Header Logo', 'Header_Logo', 'No', 0, 'file', '', 'setting_70.jpg', 'PAYPAL_SETTINGS', '2015-11-24 11:10:22', NULL),
(71, 1, 'Country code', 'Country_code', 'Yes', 0, 'text', '', 'IN', 'SYSTEM_SETTINGS', '2015-11-26 17:36:03', '2017-02-14 18:43:26'),
(73, 1, 'Default Language', 'Default_Language', 'No', 0, 'select', 'english,spanish,bengali,french,japanese,hindi,russian', 'english', 'SYSTEM_SETTINGS', '2015-11-29 17:38:06', '2017-02-14 18:43:26'),
(75, 23, 'Sharethis Header', 'sharethis_header', 'No', 0, 'text', NULL, '<script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher: "65e5d610-c749-4c89-b1f3-44950db3ff9d", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>        ', 'SOCIAL_SETTINGS', '2016-01-18 22:28:20', '2016-10-25 14:24:30'),
(76, 23, 'Sharethis Links', 'sharethis_links', 'No', 0, 'text', NULL, '<span class=\'st_sharethis\' displayText=\'\'></span><span class=\'st_facebook\' displayText=\'\'></span><span class=\'st_twitter\' displayText=\'\'></span><span class=\'st_linkedin\' displayText=\'\'></span><span class=\'st_pinterest\' displayText=\'\'></span><span class=\'st_email\' displayText=\'\'></span>        ', 'SOCIAL_SETTINGS', '2016-01-18 22:28:20', '2016-10-25 14:24:30'),
(79, 29, 'API SECRET', 'API_SECRET', 'Yes', 0, 'text', '', '7d132b33', NULL, '2016-02-11 20:05:36', '2016-02-12 07:06:40'),
(80, 29, 'API KEY', 'API_KEY', 'Yes', 0, 'text', '', '8bbf4318', NULL, '2016-02-11 20:06:04', '2016-02-12 07:06:40'),
(81, 9, 'API SECRET', 'API_SECRET', 'Yes', 0, 'text', '', '7d132b33', NULL, '2016-02-11 20:10:25', NULL),
(82, 9, 'API KEY', 'API_KEY', 'Yes', 0, 'text', '', '8bbf4318', NULL, '2016-02-11 20:10:44', NULL),
(83, 30, 'Password', 'Password', 'Yes', 0, 'text', '', 'YPXNDbFMFbYKRB', NULL, '2016-02-11 20:30:23', '2016-10-27 11:59:56'),
(84, 30, 'User Name', 'User_Name', 'Yes', 0, 'text', '', 'conquerors', NULL, '2016-02-11 20:30:43', '2016-10-27 11:59:56'),
(85, 30, 'From No', 'From_No', 'Yes', 0, 'text', '', 'CodeIgniter', NULL, '2016-02-11 20:31:02', '2016-10-27 11:59:56'),
(86, 30, 'API Id', 'API_Id', 'Yes', 0, 'text', '', '3608283', NULL, '2016-02-11 20:31:23', '2016-10-27 11:59:56'),
(87, 31, 'API KEY', 'API_KEY', 'Yes', 0, 'text', '', '06261775', NULL, '2016-02-11 20:31:54', '2016-06-18 06:00:49'),
(88, 31, 'API SECRET', 'API_SECRET', 'Yes', 0, 'text', '', '2fb13425a2e546c0', NULL, '2016-02-11 20:32:14', '2016-06-18 06:00:49'),
(89, 32, 'AUTH ID', 'AUTH_ID', 'Yes', 0, 'text', '', 'MAZGIZYJI5MGE3N2JIMD', NULL, '2016-02-11 20:32:54', '2016-06-18 06:37:28'),
(90, 32, 'AUTH TOKEN', 'AUTH_TOKEN', 'Yes', 0, 'text', '', 'NmNmMzQ5ZTBiM2IxODRhMzc3OTllNjY0ZDM5ZGEx', NULL, '2016-02-11 20:33:11', '2016-06-18 06:37:28'),
(91, 32, 'API VERSION', 'API_VERSION', 'Yes', 0, 'text', '', 'v1', NULL, '2016-02-11 20:33:29', '2016-06-18 06:37:28'),
(92, 32, 'END POINT', 'END_POINT', 'Yes', 0, 'text', '', 'https://api.plivo.com', NULL, '2016-02-11 20:33:46', '2016-06-18 06:37:28'),
(93, 33, 'Key', 'Key', 'Yes', 0, 'text', '', 'A03b780b9662f5cc19f5541a6e3858478', NULL, '2016-02-11 20:34:14', '2016-02-29 14:14:19'),
(94, 33, 'Sender', 'Sender', 'Yes', 0, 'text', '', 'SIDEMO', NULL, '2016-02-11 20:34:35', '2016-02-29 14:14:19'),
(95, 33, 'API URL', 'API_URL', 'Yes', 0, 'text', '', 'http://alerts.solutionsinfini.com/', NULL, '2016-02-11 20:34:51', '2016-02-29 14:14:19'),
(96, 34, 'Account SID', 'Account_SID', 'Yes', 0, 'text', '', 'ACd36d992497f42532824a07ec3a9337fd', NULL, '2016-02-11 20:35:20', NULL),
(97, 34, 'Auth Token', 'Auth_Token', 'Yes', 0, 'text', '', 'afa33e8e1241f421278567db3a04ee30', NULL, '2016-02-11 20:35:44', NULL),
(98, 34, 'API Version', 'API_Version', 'Yes', 0, 'text', '', '2010-04-01', NULL, '2016-02-11 20:36:05', NULL),
(99, 34, 'Twilio Phone Number', 'Twilio_Phone_Number', 'Yes', 0, 'text', '', '+15005550006', NULL, '2016-02-11 20:36:25', NULL),
(100, 1, 'Site Slogan', 'Site_Slogan', 'No', 0, 'text', '', 'This is my Site Slogan ', NULL, '2016-03-01 21:11:29', '2017-02-14 18:43:26'),
(109, 35, 'myname', 'myname', 'Yes', 0, '', '', 'my wish', NULL, '2016-03-03 03:07:38', NULL),
(121, 27, 'Status', 'status', 'Yes', 0, 'select', 'Active,In-Active', 'Active', NULL, '2016-06-17 23:56:55', '2016-11-02 19:38:43'),
(123, 28, 'Account Type', 'Account_Type', 'Yes', 0, 'select', 'live,sandbox', 'sandbox', NULL, '2016-08-18 10:54:24', '2016-11-02 19:44:58'),
(124, 1, 'Androd App', 'Androd_App', 'No', 0, 'text', NULL, 'https://play.google.com/store?hl=en', 'Androd App', '2016-08-20 06:55:35', '2017-02-14 18:43:26'),
(125, 1, 'iOS App', 'iOS_App', 'No', 0, 'text', NULL, 'https://itunes.apple.com/in/genre/ios/id36?mt=8', 'iOS App', '2016-08-20 06:57:30', '2017-02-14 18:43:26'),
(126, 36, 'Host', 'Host', 'No', 0, 'text', '', 'server host', NULL, '2016-08-20 10:18:38', '2016-10-28 12:28:28'),
(127, 36, 'Port', 'Port', 'No', 0, 'text', '', 'smtp port', NULL, '2016-08-20 10:18:50', '2016-10-28 12:28:28'),
(128, 36, 'User Name', 'User Name', 'No', 0, 'text', '', 'smtp username', NULL, '2016-08-20 10:19:10', '2016-10-28 12:28:28'),
(129, 36, 'Password', 'Password', 'No', 0, 'text', '', 'smtp password', NULL, '2016-08-20 10:19:24', '2016-10-28 12:28:28'),
(130, 39, 'Mandril API Key', 'Mandril API Key', 'No', 0, 'text', NULL, NULL, NULL, '2016-08-20 10:19:46', NULL),
(131, 1, 'Show Team', 'Show_Team', 'No', 0, 'select', 'Yes,No', 'Yes', NULL, '2016-08-22 05:26:58', '2017-02-14 18:43:26'),
(132, 1, 'Need admin approval for tutor', 'need_admin_for_tutor', 'No', 0, 'select', 'Yes,No', 'No', NULL, '2016-08-22 11:29:30', '2017-02-14 18:43:26'),
(133, 1, 'Admin Commission For A Booking (In Percentage)', 'admin_commission_for_a_booking', 'No', 0, 'text', NULL, '10', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:26'),
(135, 1, 'Per Credit Value', 'per_credit_value', 'Yes', 0, 'text', NULL, '2', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:26'),
(137, 1, 'Minimum Credits for Premium Tutor', 'min_credits_for_premium_tutor', 'Yes', 0, 'text', NULL, '50', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:26'),
(138, 1, 'Minimum Credits for Premium Student', 'min_credits_for_premium_student', 'Yes', 0, 'text', NULL, '25', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:26'),
(139, 1, 'Credits for sending message', 'credits_for_sending_message', 'Yes', 0, 'text', NULL, '1', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:26'),
(140, 1, 'Currency Symbol', 'currency_symbol', 'Yes', 0, 'text', NULL, '$', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:26'),
(141, 1, 'Minimum Credits for Premium Institute', 'min_credits_for_premium_institute', 'Yes', 0, 'text', NULL, '100', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:26'),
(142, 1, 'Time Zone', 'time_zone', 'Yes', 0, 'select', 'Africa/Abidjan,Africa/Accra,Africa/Addis_Ababa,Africa/Algiers,Africa/Asmara,Africa/Asmera,Africa/Bamako,Africa/Bangui,Africa/Banjul,Africa/Bissau,Africa/Blantyre,Africa/Brazzaville,Africa/Bujumbura,Africa/Cairo,Africa/Casablanca,Africa/Ceuta,Africa/Conakry,Africa/Dakar,Africa/Dar_es_Salaam,Africa/Djibouti,Africa/Douala,Africa/El_Aaiun,Africa/Freetown,Africa/Gaborone,Africa/Harare,Africa/Johannesburg,Africa/Juba,Africa/Kampala,Africa/Khartoum,Africa/Kigali,Africa/Kinshasa,Africa/Lagos,Africa/Libreville,Africa/Lome,Africa/Luanda,Africa/Lubumbashi,Africa/Lusaka,Africa/Malabo,Africa/Maputo,Africa/Maseru,Africa/Mbabane,Africa/Mogadishu,Africa/Monrovia,Africa/Nairobi,Africa/Ndjamena,Africa/Niamey,Africa/Nouakchott,Africa/Ouagadougou,Africa/Porto-Novo,Africa/Sao_Tome,Africa/Timbuktu,Africa/Tripoli,Africa/Tunis,Africa/Windhoek,AKST9AKDT,America/Adak,America/Anchorage,America/Anguilla,America/Antigua,America/Araguaina,America/Argentina/Buenos_Aires,America/Argentina/Catamarca,America/Argentina/ComodRivadavia,America/Argentina/Cordoba,America/Argentina/Jujuy,America/Argentina/La_Rioja,America/Argentina/Mendoza,America/Argentina/Rio_Gallegos,America/Argentina/Salta,America/Argentina/San_Juan,America/Argentina/San_Luis,America/Argentina/Tucuman,America/Argentina/Ushuaia,America/Aruba,America/Asuncion,America/Atikokan,America/Atka,America/Bahia,America/Bahia_Banderas,America/Barbados,America/Belem,America/Belize,America/Blanc-Sablon,America/Boa_Vista,America/Bogota,America/Boise,America/Buenos_Aires,America/Cambridge_Bay,America/Campo_Grande,America/Cancun,America/Caracas,America/Catamarca,America/Cayenne,America/Cayman,America/Chicago,America/Chihuahua,America/Coral_Harbour,America/Cordoba,America/Costa_Rica,America/Creston,America/Cuiaba,America/Curacao,America/Danmarkshavn,America/Dawson,America/Dawson_Creek,America/Denver,America/Detroit,America/Dominica,America/Edmonton,America/Eirunepe,America/El_Salvador,America/Ensenada,America/Fortaleza,America/Fort_Wayne,America/Glace_Bay,America/Godthab,America/Goose_Bay,America/Grand_Turk,America/Grenada,America/Guadeloupe,America/Guatemala,America/Guayaquil,America/Guyana,America/Halifax,America/Havana,America/Hermosillo,America/Indiana/Indianapolis,America/Indiana/Knox,America/Indiana/Marengo,America/Indiana/Petersburg,America/Indiana/Tell_City,America/Indiana/Vevay,America/Indiana/Vincennes,America/Indiana/Winamac,America/Indianapolis,America/Inuvik,America/Iqaluit,America/Jamaica,America/Jujuy,America/Juneau,America/Kentucky/Louisville,America/Kentucky/Monticello,America/Knox_IN,America/Kralendijk,America/La_Paz,America/Lima,America/Los_Angeles,America/Louisville,America/Lower_Princes,America/Maceio,America/Managua,America/Manaus,America/Marigot,America/Martinique,America/Matamoros,America/Mazatlan,America/Mendoza,America/Menominee,America/Merida,America/Metlakatla,America/Mexico_City,America/Miquelon,America/Moncton,America/Monterrey,America/Montevideo,America/Montreal,America/Montserrat,America/Nassau,America/New_York,America/Nipigon,America/Nome,America/Noronha,America/North_Dakota/Beulah,America/North_Dakota/Center,America/North_Dakota/New_Salem,America/Ojinaga,America/Panama,America/Pangnirtung,America/Paramaribo,America/Phoenix,America/Port-au-Prince,America/Porto_Acre,America/Porto_Velho,America/Port_of_Spain,America/Puerto_Rico,America/Rainy_River,America/Rankin_Inlet,America/Recife,America/Regina,America/Resolute,America/Rio_Branco,America/Rosario,America/Santarem,America/Santa_Isabel,America/Santiago,America/Santo_Domingo,America/Sao_Paulo,America/Scoresbysund,America/Shiprock,America/Sitka,America/St_Barthelemy,America/St_Johns,America/St_Kitts,America/St_Lucia,America/St_Thomas,America/St_Vincent,America/Swift_Current,America/Tegucigalpa,America/Thule,America/Thunder_Bay,America/Tijuana,America/Toronto,America/Tortola,America/Vancouver,America/Virgin,America/Whitehorse,America/Winnipeg,America/Yakutat,America/Yellowknife,Antarctica/Casey,Antarctica/Davis,Antarctica/DumontDUrville,Antarctica/Macquarie,Antarctica/Mawson,Antarctica/McMurdo,Antarctica/Palmer,Antarctica/Rothera,Antarctica/South_Pole,Antarctica/Syowa,Antarctica/Vostok,Arctic/Longyearbyen,Asia/Aden,Asia/Almaty,Asia/Amman,Asia/Anadyr,Asia/Aqtau,Asia/Aqtobe,Asia/Ashgabat,Asia/Ashkhabad,Asia/Baghdad,Asia/Bahrain,Asia/Baku,Asia/Bangkok,Asia/Beirut,Asia/Bishkek,Asia/Brunei,Asia/Calcutta,Asia/Choibalsan,Asia/Chongqing,Asia/Chungking,Asia/Colombo,Asia/Dacca,Asia/Damascus,Asia/Dhaka,Asia/Dili,Asia/Dubai,Asia/Dushanbe,Asia/Gaza,Asia/Harbin,Asia/Hebron,Asia/Hong_Kong,Asia/Hovd,Asia/Ho_Chi_Minh,Asia/Irkutsk,Asia/Istanbul,Asia/Jakarta,Asia/Jayapura,Asia/Jerusalem,Asia/Kabul,Asia/Kamchatka,Asia/Karachi,Asia/Kashgar,Asia/Kathmandu,Asia/Katmandu,Asia/Kolkata,Asia/Krasnoyarsk,Asia/Kuala_Lumpur,Asia/Kuching,Asia/Kuwait,Asia/Macao,Asia/Macau,Asia/Magadan,Asia/Makassar,Asia/Manila,Asia/Muscat,Asia/Nicosia,Asia/Novokuznetsk,Asia/Novosibirsk,Asia/Omsk,Asia/Oral,Asia/Phnom_Penh,Asia/Pontianak,Asia/Pyongyang,Asia/Qatar,Asia/Qyzylorda,Asia/Rangoon,Asia/Riyadh,Asia/Saigon,Asia/Sakhalin,Asia/Samarkand,Asia/Seoul,Asia/Shanghai,Asia/Singapore,Asia/Taipei,Asia/Tashkent,Asia/Tbilisi,Asia/Tehran,Asia/Tel_Aviv,Asia/Thimbu,Asia/Thimphu,Asia/Tokyo,Asia/Ujung_Pandang,Asia/Ulaanbaatar,Asia/Ulan_Bator,Asia/Urumqi,Asia/Vientiane,Asia/Vladivostok,Asia/Yakutsk,Asia/Yekaterinburg,Asia/Yerevan,Atlantic/Azores,Atlantic/Bermuda,Atlantic/Canary,Atlantic/Cape_Verde,Atlantic/Faeroe,Atlantic/Faroe,Atlantic/Jan_Mayen,Atlantic/Madeira,Atlantic/Reykjavik,Atlantic/South_Georgia,Atlantic/Stanley,Atlantic/St_Helena,Australia/ACT,Australia/Adelaide,Australia/Brisbane,Australia/Broken_Hill,Australia/Canberra,Australia/Currie,Australia/Darwin,Australia/Eucla,Australia/Hobart,Australia/LHI,Australia/Lindeman,Australia/Lord_Howe,Australia/Melbourne,Australia/North,Australia/NSW,Australia/Perth,Australia/Queensland,Australia/South,Australia/Sydney,Australia/Tasmania,Australia/Victoria,Australia/West,Australia/Yancowinna,Brazil/Acre,Brazil/DeNoronha,Brazil/East,Brazil/West,Canada/Atlantic,Canada/Central,Canada/East-Saskatchewan,Canada/Eastern,Canada/Mountain,Canada/Newfoundland,Canada/Pacific,Canada/Saskatchewan,Canada/Yukon,CET,Chile/Continental,Chile/EasterIsland,CST6CDT,Cuba,EET,Egypt,Eire,EST,EST5EDT,Etc./GMT,Etc./GMT+0,Etc./UCT,Etc./Universal,Etc./UTC,Etc./Zulu,Europe/Amsterdam,Europe/Andorra,Europe/Athens,Europe/Belfast,Europe/Belgrade,Europe/Berlin,Europe/Bratislava,Europe/Brussels,Europe/Bucharest,Europe/Budapest,Europe/Chisinau,Europe/Copenhagen,Europe/Dublin,Europe/Gibraltar,Europe/Guernsey,Europe/Helsinki,Europe/Isle_of_Man,Europe/Istanbul,Europe/Jersey,Europe/Kaliningrad,Europe/Kiev,Europe/Lisbon,Europe/Ljubljana,Europe/London,Europe/Luxembourg,Europe/Madrid,Europe/Malta,Europe/Mariehamn,Europe/Minsk,Europe/Monaco,Europe/Moscow,Europe/Nicosia,Europe/Oslo,Europe/Paris,Europe/Podgorica,Europe/Prague,Europe/Riga,Europe/Rome,Europe/Samara,Europe/San_Marino,Europe/Sarajevo,Europe/Simferopol,Europe/Skopje,Europe/Sofia,Europe/Stockholm,Europe/Tallinn,Europe/Tirane,Europe/Tiraspol,Europe/Uzhgorod,Europe/Vaduz,Europe/Vatican,Europe/Vienna,Europe/Vilnius,Europe/Volgograd,Europe/Warsaw,Europe/Zagreb,Europe/Zaporozhye,Europe/Zurich,GB,GB-Eire,GMT,GMT+0,GMT-0,GMT0,Greenwich,Hong Kong,HST,Iceland,Indian/Antananarivo,Indian/Chagos,Indian/Christmas,Indian/Cocos,Indian/Comoro,Indian/Kerguelen,Indian/Mahe,Indian/Maldives,Indian/Mauritius,Indian/Mayotte,Indian/Reunion,Iran,Israel,Jamaica,Japan,JST-9,Kwajalein,Libya,MET,Mexico/BajaNorte,Mexico/BajaSur,Mexico/General,MST,MST7MDT,Navajo,NZ,NZ-CHAT,Pacific/Apia,Pacific/Auckland,Pacific/Chatham,Pacific/Chuuk,Pacific/Easter,Pacific/Efate,Pacific/Enderbury,Pacific/Fakaofo,Pacific/Fiji,Pacific/Funafuti,Pacific/Galapagos,Pacific/Gambier,Pacific/Guadalcanal,Pacific/Guam,Pacific/Honolulu,Pacific/Johnston,Pacific/Kiritimati,Pacific/Kosrae,Pacific/Kwajalein,Pacific/Majuro,Pacific/Marquesas,Pacific/Midway,Pacific/Nauru,Pacific/Niue,Pacific/Norfolk,Pacific/Noumea,Pacific/Pago_Pago,Pacific/Palau,Pacific/Pitcairn,Pacific/Pohnpei,Pacific/Ponape,Pacific/Port_Moresby,Pacific/Rarotonga,Pacific/Saipan,Pacific/Samoa,Pacific/Tahiti,Pacific/Tarawa,Pacific/Tongatapu,Pacific/Truk,Pacific/Wake,Pacific/Wallis,Pacific/Yap,Poland,Portugal,PRC,PST8PDT,ROC,ROK,Singapore,Turkey,UCT,Universal,US/Alaska,US/Aleutian,US/Arizona,US/Central,US/East-Indiana,US/Eastern,US/Hawaii,US/Indiana-Starke,US/Michigan,US/Mountain,US/Pacific,US/Pacific-New,US/Samoa,UTC,W-SU,WET,Zulu', 'Asia/Kolkata', 'SYSTEM_SETTINGS', '2015-11-26 17:36:03', '2017-02-14 18:43:26'),
(143, 1, 'Credits for viewing lead details', 'credits_for_viewing_lead', 'Yes', 0, 'text', NULL, '5', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:26'),
(144, 1, 'Need admin approval for institute', 'need_admin_for_inst', 'No', 0, 'select', 'Yes,No', 'No', NULL, '2016-08-22 11:29:30', '2017-02-14 18:43:26'),
(145, 41, 'Secret Key', 'secret_key', 'Yes', 0, 'text', '<p>\r\n	<span style="color: rgb(0, 0, 0); font-family: monospace; font-size: 13px;">sk_test_FHxf1NsgaWbAFAGny5zJELqU</span></p>\r\n', '<p>\r\n	<span style="color: rgb(0, 0, 0); font-family: monospace; font-size: 13px;">sk_test_FHxf1NsgaWbAFAGny5zJELqU</span></p>\r\n', 'Secret Key', '2016-10-05 13:32:00', '2017-01-17 10:48:38'),
(147, 40, 'General Inquiries', 'General_Inquiries', 'Yes', 0, 'text', NULL, '<p>	<span style="color: rgb(83, 64, 81); font-family: Lato, sans-serif; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(253, 253, 253); display: inline !important; float: none;">contact@tutors-listings.org</span></p>', 'CONTACT_SETTINGS', '2016-10-07 16:05:52', '2016-10-19 16:53:24'),
(148, 40, 'Media Requests', 'Media_Requests', NULL, 0, NULL, NULL, '<p>	<span style="color: rgb(83, 64, 81); font-family: Lato, sans-serif; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(253, 253, 253); display: inline !important; float: none;">support@tutors-listings.org</span></p>', 'CONTACT_SETTINGS', '2016-10-07 16:05:52', '2016-10-19 16:53:24'),
(149, 40, 'Offline Support', 'Offline_Support', NULL, 0, NULL, NULL, '<p>	<span style="color: rgb(83, 64, 81); font-family: Lato, sans-serif; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(253, 253, 253); display: inline !important; float: none;">offline@tutors-listings.org</span></p>', 'CONTACT_SETTINGS', '2016-10-07 16:05:52', '2016-10-19 16:53:24'),
(150, 1, 'Enable Initiate Session Option Before Minutes', 'enable_initiate_session_option_before_mins', 'Yes', 0, 'text', NULL, '30', NULL, '2016-10-18 10:10:19', '2017-02-14 18:43:26'),
(151, 1, 'Enable Course Completed Option Before Minutes', 'enable_course_completed_option_before_mins', 'Yes', 0, 'text', NULL, '15', NULL, '2016-10-18 10:37:32', '2017-02-14 18:43:26'),
(152, 1, 'Favicon', 'favicon', 'No', 0, 'file', NULL, 'setting_152.png', ' SYSTEM_SETTINGS', '2016-10-21 10:18:58', NULL),
(153, 1, 'URL For Designed By', 'url_designed_by', 'No', 0, 'text', NULL, 'http://www.digitalvidhya.com/', 'SYSTEM_SETTINGS', '2015-11-24 09:56:03', '2017-02-14 18:43:26'),
(154, 1, 'Show Available Records Count in Search Filters', 'show_records_count_in_search_filters', 'Yes', 0, 'select', 'Yes,No', 'Yes', ' SYSTEM_SETTINGS', '2016-11-22 14:58:41', '2017-02-14 18:43:26'),
(155, 1, 'Advantages Section', 'advantages_section', 'Yes', 0, 'select', 'On,Off', 'On', ' SYSTEM_SETTINGS', '2016-11-22 18:53:43', '2017-02-14 18:43:26'),
(156, 1, 'Featured on Section', 'featured_on_section', 'Yes', 0, 'select', 'On,Off', 'On', ' SYSTEM_SETTINGS', '2016-11-22 18:53:43', '2017-02-14 18:43:26'),
(157, 1, 'Are You A Teacher Section', 'are_you_teacher_section', 'Yes', 0, 'select', 'On,Off', 'On', ' SYSTEM_SETTINGS', '2016-11-22 18:53:43', '2017-02-14 18:43:26'),
(158, 1, 'Footer Section', 'footer_section', 'Yes', 0, 'select', 'On,Off', 'On', ' SYSTEM_SETTINGS', '2016-11-22 18:53:43', '2017-02-14 18:43:27'),
(159, 1, 'Get Our App Section', 'get_app_section', 'Yes', 0, 'select', 'On,Off', 'On', ' SYSTEM_SETTINGS', '2016-11-22 18:53:43', '2017-02-14 18:43:27'),
(160, 1, 'Primary Footer Section', 'primary_footer_section', 'Yes', 0, 'select', 'On,Off', 'On', ' SYSTEM_SETTINGS', '2016-11-22 18:53:43', '2017-02-14 18:43:27'),
(161, 1, 'Bottom Section', 'bottom_section', 'Yes', 0, 'select', '<p>\r\n	On,Off</p>\r\n', '<p>\r\n	On', ' SYSTEM_SETTINGS', '2016-11-22 18:53:43', '2017-02-14 18:43:27'),
(162, 1, 'Top Most Section', 'top_most_section', 'Yes', 0, 'select', 'On,Off', 'On', ' SYSTEM_SETTINGS', '2016-11-22 18:53:43', '2017-02-14 18:43:27'),
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
(198, 1, 'Admin Commission Percentage On Each Purchase', 'admin_commission_on_course_purchase', 'Yes', 0, 'text', NULL, '10', 'SYSTEM_SETTINGS', '2016-08-31 10:42:16', '2017-02-14 18:43:27'),
(199, 50, 'Bigbluebutton Security Salt', 'bbb_security_salt', 'Yes', 0, 'text', NULL, '8cd8ef52e8e101574e400365b55e11a6', 'bigbluebutton', '2016-08-31 10:42:16', '2017-02-14 18:43:27'),
(200, 50, 'Bigbluebutton Server URL', 'bbb_base_server_url', 'Yes', 0, 'text', NULL, 'http://test-install.blindsidenetworks.com/bigbluebutton/', 'bigbluebutton', '2016-08-31 10:42:16', '2017-02-14 18:43:27'),
(201, 50, 'Enable Live Classes', 'bbb_enable', 'Yes', 0, 'select', 'yes,no', 'yes', 'bigbluebutton', '2016-08-31 10:42:16', '2017-02-14 18:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `pre_system_settings_types`
--

CREATE TABLE `pre_system_settings_types` (
  `type_id` int(11) NOT NULL,
  `type_title` varchar(512) CHARACTER SET latin1 DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  `type_slug` varchar(50) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `is_default` enum('Yes','No') NOT NULL DEFAULT 'No',
  `status` enum('Active','In-Active') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_system_settings_types`
--

INSERT INTO `pre_system_settings_types` (`type_id`, `type_title`, `parent_id`, `type_slug`, `created`, `updated`, `is_default`, `status`) VALUES
(1, 'System Settings', 0, 'SYSTEM_SETTINGS', '2015-11-08 21:45:41', NULL, 'Yes', 'Active'),
(9, 'SMS Settings', 0, 'SMS_SETTINGS', '2015-11-09 14:46:57', NULL, 'No', 'Active'),
(13, 'Global SEO Settings', 0, 'SEO_SETTINGS', '2015-11-09 14:48:24', NULL, 'No', 'Active'),
(14, 'Email Settings', 0, 'EMAIL_SETTINGS', '2015-11-09 14:48:33', NULL, 'No', 'Active'),
(23, 'Social Network Settings', 0, 'SOCIAL_SETTINGS', '2015-11-24 16:11:00', NULL, 'No', 'Active'),
(24, 'Registration Settings', 0, 'REGISTRATION_SETTINGS', '2015-11-24 16:15:24', NULL, 'No', 'Active'),
(27, 'Payu', 35, 'PAYU_SETTINGS', '2015-11-24 16:24:00', '2016-10-28 18:55:08', 'Yes', 'Active'),
(28, 'Paypal', 35, 'PAYPAL_SETTINGS', '2015-11-24 16:36:50', '2016-10-28 18:54:28', 'No', 'Active'),
(30, 'Cliakatell', 9, 'Cliakatell slug', '2016-02-11 20:24:18', '2016-10-27 12:00:05', 'No', 'Active'),
(31, 'Nexmo', 9, '', '2016-02-11 20:24:29', NULL, 'No', 'Active'),
(32, 'Plivo', 9, '', '2016-02-11 20:24:38', NULL, 'Yes', 'Active'),
(33, 'Solutionsinfini', 9, '', '2016-02-11 20:24:47', NULL, 'No', 'Active'),
(34, 'Twilio', 9, '', '2016-02-11 20:24:58', NULL, 'No', 'Active'),
(35, 'Payment Settings', 0, 'PAYMENT_SETTINGS', '2016-08-17 07:14:01', NULL, 'No', 'Active'),
(36, 'Webmail', 14, '', '2016-08-20 09:21:43', NULL, 'No', 'Active'),
(37, 'Default CI', 14, '', '2016-08-20 09:44:05', NULL, 'No', 'Active'),
(38, 'default PHP', 14, '', '2016-08-20 09:44:23', NULL, 'Yes', 'Active'),
(39, 'Mandril', 14, '', '2016-08-20 09:44:44', NULL, 'No', 'Active'),
(40, 'Contact Details Settings', 0, 'CONTACT_SETTINGS', '2015-11-24 16:11:00', NULL, 'No', 'Active'),
(41, 'Stripe', 35, 'Stripe', '2017-01-17 05:03:37', NULL, 'No', 'Active'),
(42, 'tpay.com', 35, 'tpay.com', '2017-01-17 05:06:04', NULL, 'No', 'Active'),
(43, 'PagSeguro', 35, 'PagSeguro', '2017-01-17 05:08:28', NULL, 'No', 'Active'),
(44, 'Web money', 35, 'Web money', '2017-01-17 05:08:38', NULL, 'No', 'Active'),
(45, 'Yandex', 35, 'Yandex', '2017-01-17 05:08:59', NULL, 'No', 'Active'),
(46, 'Payza', 35, 'Payza', '2017-01-17 05:09:09', NULL, 'No', 'Active'),
(47, 'Manual Transfer', 35, 'Manual Transfer', '2017-01-17 05:19:54', NULL, 'No', 'Active'),
(48, '2Checkout', 35, '2Checkout', '2017-01-18 09:05:33', NULL, 'No', 'Active'),
(49, 'Razorpay', 35, 'Razorpay', '2017-01-31 04:27:04', NULL, 'No', 'Active'),
(50, 'Bigbluebutton', 0, 'bigbluebutton', '2017-02-03 13:19:13', NULL, 'No', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `pre_teaching_types`
--

CREATE TABLE `pre_teaching_types` (
  `id` int(11) NOT NULL,
  `teaching_type` varchar(128) CHARACTER SET utf8 NOT NULL,
  `description` varchar(512) CHARACTER SET utf8 NOT NULL,
  `slug` varchar(512) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pre_teaching_types`
--

INSERT INTO `pre_teaching_types` (`id`, `teaching_type`, `description`, `slug`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Home', 'Your Home', 'home', 1, 1, '2016-08-09 17:16:22', '2016-08-09 17:16:22'),
(2, 'Willing to Travel', 'Willing to Travel', 'willing-to-travel', 1, 2, '2016-08-09 17:17:22', '2016-08-09 17:17:22'),
(3, 'Online', 'Online Teaching', 'online', 1, 3, '2016-08-09 17:18:22', '2016-08-09 17:18:22'),
(4, 'Online BBB', 'Online Bigbluebutton', 'online-bbb', 1, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pre_team`
--

CREATE TABLE `pre_team` (
  `id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `position` varbinary(256) DEFAULT NULL,
  `description` text,
  `image` varchar(256) DEFAULT NULL,
  `status` enum('Active','In-Active') DEFAULT 'Active',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_terms_data`
--

CREATE TABLE `pre_terms_data` (
  `term_id` bigint(11) NOT NULL,
  `parent_id` bigint(11) DEFAULT '0',
  `term_title` varchar(512) CHARACTER SET latin1 NOT NULL,
  `term_content` longtext,
  `term_slug` varchar(512) CHARACTER SET latin1 DEFAULT NULL,
  `term_image` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `term_status` enum('Active','In-Active') CHARACTER SET latin1 DEFAULT 'Active',
  `term_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `term_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `term_type` varchar(50) CHARACTER SET latin1 DEFAULT NULL COMMENT 'This may be ''Categories'',''Departments'',''Specialities'' etc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_testimonials`
--

CREATE TABLE `pre_testimonials` (
  `testimony_id` int(25) NOT NULL,
  `user_id` int(25) NOT NULL,
  `user_group_id` int(25) NOT NULL,
  `content` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `rating_value` float NOT NULL,
  `date_posted` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('Pending','Approved','Blocked') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Pending'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pre_tpay_data`
--

CREATE TABLE `pre_tpay_data` (
  `id` int(11) NOT NULL,
  `data` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_tutor_courses`
--

CREATE TABLE `pre_tutor_courses` (
  `id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `duration_value` tinyint(5) NOT NULL,
  `duration_type` enum('hours','days','months','years') NOT NULL DEFAULT 'days',
  `fee` float NOT NULL COMMENT 'in credits',
  `per_credit_value` float NOT NULL DEFAULT '1' COMMENT 'per credit value already set by Admin',
  `content` text COMMENT 'course content that is covered by tutor',
  `time_slots` varchar(1000) DEFAULT NULL COMMENT 'time slots in which tutor is avail for teaching for particular course',
  `days_off` varchar(512) NOT NULL DEFAULT 'SUN',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-active, 0-inactive',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='are the courses teached by tutors';

-- --------------------------------------------------------

--
-- Table structure for table `pre_tutor_locations`
--

CREATE TABLE `pre_tutor_locations` (
  `id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-active, 0-inactive',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='locations in which tutor willing to provide his services';

-- --------------------------------------------------------

--
-- Table structure for table `pre_tutor_reviews`
--

CREATE TABLE `pre_tutor_reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `rating` float NOT NULL,
  `comments` varchar(512) CHARACTER SET utf8 NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') CHARACTER SET utf8 NOT NULL DEFAULT 'Pending'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_tutor_selected_types`
--

CREATE TABLE `pre_tutor_selected_types` (
  `id` int(222) NOT NULL,
  `user_id` int(222) NOT NULL,
  `tutor_type_id` int(222) NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pre_tutor_selling_courses`
--

CREATE TABLE `pre_tutor_selling_courses` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_tutor_selling_courses_curriculum`
--

CREATE TABLE `pre_tutor_selling_courses_curriculum` (
  `file_id` bigint(20) NOT NULL,
  `sc_id` int(11) NOT NULL,
  `title` varchar(512) NOT NULL,
  `source_type` enum('url','file') NOT NULL,
  `file_name` varchar(512) NOT NULL,
  `file_ext` varchar(10) DEFAULT NULL,
  `file_size` varchar(128) DEFAULT NULL COMMENT 'In Bytes. When displaying convert as needed'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_tutor_teaching_types`
--

CREATE TABLE `pre_tutor_teaching_types` (
  `id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `teaching_type_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-active, 0-inactive',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_users`
--

CREATE TABLE `pre_users` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL DEFAULT '127.0.0.1',
  `username` varchar(100) NOT NULL,
  `slug` varchar(512) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `company` varchar(100) DEFAULT NULL,
  `phone_code` varchar(5) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `seo_keywords` varchar(1000) DEFAULT NULL,
  `meta_desc` varchar(1000) DEFAULT NULL,
  `photo` varchar(512) DEFAULT '',
  `location_id` int(11) DEFAULT '0',
  `language_of_teaching` varchar(512) DEFAULT '',
  `teaching_experience` int(11) DEFAULT '0',
  `duration_of_experience` enum('Months','Years') DEFAULT 'Months',
  `experience_desc` longtext,
  `video_profile_url` varchar(512) DEFAULT '',
  `show_contact` enum('All','Email','Mobile','None') DEFAULT 'None',
  `visibility_in_search` enum('0','1') DEFAULT '1',
  `availability_status` enum('0','1') DEFAULT '1',
  `profile` varchar(512) DEFAULT '',
  `no_of_profile_views` int(11) DEFAULT '0',
  `qualification` varchar(512) DEFAULT '',
  `tutor_rating` int(5) DEFAULT '0',
  `is_profile_update` tinyint(1) NOT NULL DEFAULT '0',
  `facebook` varchar(512) DEFAULT '',
  `twitter` varchar(512) DEFAULT '',
  `linkedin` varchar(512) DEFAULT '',
  `skype` varchar(512) DEFAULT '',
  `isTestimonyGiven` enum('0','1') DEFAULT '0',
  `is_social_login` enum('yes','no') DEFAULT 'no',
  `website` varchar(256) DEFAULT NULL,
  `profile_page_title` varchar(512) DEFAULT '',
  `willing_to_travel` decimal(10,2) DEFAULT '0.00',
  `own_vehicle` enum('yes','no') DEFAULT 'no',
  `land_mark` varchar(512) DEFAULT NULL,
  `country` varchar(256) DEFAULT NULL,
  `pin_code` char(10) DEFAULT NULL,
  `paypal_email` varchar(100) DEFAULT NULL,
  `bank_ac_details` varchar(512) DEFAULT NULL,
  `academic_class` enum('yes','no') DEFAULT 'yes',
  `non_academic_class` enum('yes','no') DEFAULT 'yes',
  `share_phone_number` enum('yes','no') DEFAULT 'no',
  `is_online` enum('yes','no') DEFAULT 'no',
  `city` varchar(256) DEFAULT NULL,
  `user_belongs_group` int(11) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL,
  `free_demo` enum('Yes','No') DEFAULT 'No',
  `admin_approved` enum('Yes','No') DEFAULT 'No' COMMENT 'If admin enables to approve tutors by admin',
  `admin_approved_date` datetime DEFAULT NULL,
  `i_love_tutoring_because` varchar(1000) DEFAULT NULL,
  `other_interests` varchar(1000) DEFAULT NULL,
  `net_credits` int(25) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'it will be the id of institute id if institute added this tutor',
  `last_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_users`
--

INSERT INTO `pre_users` (`id`, `ip_address`, `username`, `slug`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `dob`, `gender`, `company`, `phone_code`, `phone`, `seo_keywords`, `meta_desc`, `photo`, `location_id`, `language_of_teaching`, `teaching_experience`, `duration_of_experience`, `experience_desc`, `video_profile_url`, `show_contact`, `visibility_in_search`, `availability_status`, `profile`, `no_of_profile_views`, `qualification`, `tutor_rating`, `is_profile_update`, `facebook`, `twitter`, `linkedin`, `skype`, `isTestimonyGiven`, `is_social_login`, `website`, `profile_page_title`, `willing_to_travel`, `own_vehicle`, `land_mark`, `country`, `pin_code`, `paypal_email`, `bank_ac_details`, `academic_class`, `non_academic_class`, `share_phone_number`, `is_online`, `city`, `user_belongs_group`, `subscription_id`, `free_demo`, `admin_approved`, `admin_approved_date`, `i_love_tutoring_because`, `other_interests`, `net_credits`, `parent_id`, `last_updated`) VALUES
(1, '127.0.0.1', 'administrator', 'administrator', '$2y$08$3fjJSaWH/Zp7vHKKpOfN..MDF7AtSlKexWDAel3xtTx3VRVJkJO0G', '', 'admin@admin.com', '', NULL, NULL, NULL, 1268889823, 1487832459, 1, 'Admin', 'istrator', '1980-02-28', 'Male', 'ADMIN', NULL, '1234567890', NULL, NULL, '', 2, '', 0, 'Months', '', '', 'All', '1', '1', '', 0, '', 0, 1, '', '', '', '', '0', 'no', NULL, '', '2.00', 'no', NULL, NULL, NULL, '', '', 'yes', 'yes', 'no', 'yes', NULL, 1, NULL, 'No', 'No', NULL, NULL, NULL, 2147483647, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pre_users_certificates`
--

CREATE TABLE `pre_users_certificates` (
  `user_certificate_id` int(11) NOT NULL,
  `admin_certificate_id` varchar(20) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `admin_status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `certificate_type` enum('admin','other') DEFAULT NULL,
  `certificate_name` varchar(100) NOT NULL,
  `admin_naming_convention` varchar(100) NOT NULL DEFAULT '',
  `file_type` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_users_education`
--

CREATE TABLE `pre_users_education` (
  `record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `education_id` int(11) DEFAULT '0' COMMENT 'foreign key for ''term_id'' in ''pre_terms_data'' otehrwise ''0''',
  `other_title` varchar(256) DEFAULT NULL,
  `education_institute` varchar(512) DEFAULT NULL COMMENT 'It may be ''Institute'' or ''College'' or Univarsity',
  `education_address` varchar(512) DEFAULT NULL,
  `education_year` char(6) DEFAULT NULL,
  `record_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_users_experience`
--

CREATE TABLE `pre_users_experience` (
  `record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company` varchar(55) NOT NULL,
  `role` varchar(25) NOT NULL,
  `description` varchar(650) NOT NULL,
  `from_date` varchar(15) NOT NULL,
  `to_date` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_users_groups`
--

CREATE TABLE `pre_users_groups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` mediumint(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pre_users_groups`
--

INSERT INTO `pre_users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pre_users_reviews`
--

CREATE TABLE `pre_users_reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reviewer_id` int(11) DEFAULT NULL,
  `reviewer_comments` text,
  `rating` tinyint(2) DEFAULT NULL,
  `appraval_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `record_created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_user_credit_transactions`
--

CREATE TABLE `pre_user_credit_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `per_credit_value` float NOT NULL DEFAULT '1',
  `action` enum('credited','debited') CHARACTER SET latin1 NOT NULL,
  `purpose` text NOT NULL,
  `date_of_action` datetime DEFAULT NULL,
  `reference_table` char(50) CHARACTER SET latin1 DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pre_user_status_texts`
--

CREATE TABLE `pre_user_status_texts` (
  `value` tinyint(1) NOT NULL,
  `text` varchar(25) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_webmoney_data`
--

CREATE TABLE `pre_webmoney_data` (
  `id` int(11) NOT NULL,
  `data` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_yesno_status_texts`
--

CREATE TABLE `pre_yesno_status_texts` (
  `value` tinyint(1) NOT NULL,
  `text` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pre_admin_money_transactions`
--
ALTER TABLE `pre_admin_money_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_bookings`
--
ALTER TABLE `pre_bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `pre_categories`
--
ALTER TABLE `pre_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_certificates`
--
ALTER TABLE `pre_certificates`
  ADD PRIMARY KEY (`certificate_id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `pre_country`
--
ALTER TABLE `pre_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_course_categories`
--
ALTER TABLE `pre_course_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_course_downloads`
--
ALTER TABLE `pre_course_downloads`
  ADD PRIMARY KEY (`cd_id`);

--
-- Indexes for table `pre_course_purchases`
--
ALTER TABLE `pre_course_purchases`
  ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `pre_email_templates`
--
ALTER TABLE `pre_email_templates`
  ADD PRIMARY KEY (`email_template_id`);

--
-- Indexes for table `pre_faqs`
--
ALTER TABLE `pre_faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_gallery`
--
ALTER TABLE `pre_gallery`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `pre_groups`
--
ALTER TABLE `pre_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_inst_batches`
--
ALTER TABLE `pre_inst_batches`
  ADD PRIMARY KEY (`batch_id`);

--
-- Indexes for table `pre_inst_enrolled_students`
--
ALTER TABLE `pre_inst_enrolled_students`
  ADD PRIMARY KEY (`enroll_id`);

--
-- Indexes for table `pre_inst_locations`
--
ALTER TABLE `pre_inst_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_inst_offered_courses`
--
ALTER TABLE `pre_inst_offered_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_inst_teaching_types`
--
ALTER TABLE `pre_inst_teaching_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_languages`
--
ALTER TABLE `pre_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_languagewords`
--
ALTER TABLE `pre_languagewords`
  ADD PRIMARY KEY (`lang_id`);

--
-- Indexes for table `pre_locations`
--
ALTER TABLE `pre_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_login_attempts`
--
ALTER TABLE `pre_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_messages`
--
ALTER TABLE `pre_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `pre_newsletter_subscriptions`
--
ALTER TABLE `pre_newsletter_subscriptions`
  ADD PRIMARY KEY (`subscription_id`);

--
-- Indexes for table `pre_packages`
--
ALTER TABLE `pre_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_pages`
--
ALTER TABLE `pre_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_payments_data`
--
ALTER TABLE `pre_payments_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_scroll_news`
--
ALTER TABLE `pre_scroll_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_sections`
--
ALTER TABLE `pre_sections`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `pre_seosettings`
--
ALTER TABLE `pre_seosettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_sessions`
--
ALTER TABLE `pre_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `pre_sitetestimonials`
--
ALTER TABLE `pre_sitetestimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_student_leads`
--
ALTER TABLE `pre_student_leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_student_locations`
--
ALTER TABLE `pre_student_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_student_prefferd_teaching_types`
--
ALTER TABLE `pre_student_prefferd_teaching_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_student_preffered_courses`
--
ALTER TABLE `pre_student_preffered_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_subscriptions`
--
ALTER TABLE `pre_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_system_settings_fields`
--
ALTER TABLE `pre_system_settings_fields`
  ADD PRIMARY KEY (`field_id`);

--
-- Indexes for table `pre_system_settings_types`
--
ALTER TABLE `pre_system_settings_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `pre_teaching_types`
--
ALTER TABLE `pre_teaching_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_team`
--
ALTER TABLE `pre_team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_terms_data`
--
ALTER TABLE `pre_terms_data`
  ADD PRIMARY KEY (`term_id`);

--
-- Indexes for table `pre_testimonials`
--
ALTER TABLE `pre_testimonials`
  ADD PRIMARY KEY (`testimony_id`);

--
-- Indexes for table `pre_tpay_data`
--
ALTER TABLE `pre_tpay_data`
  ADD KEY `id` (`id`);

--
-- Indexes for table `pre_tutor_courses`
--
ALTER TABLE `pre_tutor_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_tutor_locations`
--
ALTER TABLE `pre_tutor_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_tutor_reviews`
--
ALTER TABLE `pre_tutor_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_tutor_selected_types`
--
ALTER TABLE `pre_tutor_selected_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_tutor_selling_courses`
--
ALTER TABLE `pre_tutor_selling_courses`
  ADD PRIMARY KEY (`sc_id`);

--
-- Indexes for table `pre_tutor_selling_courses_curriculum`
--
ALTER TABLE `pre_tutor_selling_courses_curriculum`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `pre_tutor_teaching_types`
--
ALTER TABLE `pre_tutor_teaching_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_users`
--
ALTER TABLE `pre_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_users_certificates`
--
ALTER TABLE `pre_users_certificates`
  ADD PRIMARY KEY (`user_certificate_id`);

--
-- Indexes for table `pre_users_education`
--
ALTER TABLE `pre_users_education`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `pre_users_experience`
--
ALTER TABLE `pre_users_experience`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `pre_users_groups`
--
ALTER TABLE `pre_users_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_group_id` (`group_id`);

--
-- Indexes for table `pre_users_reviews`
--
ALTER TABLE `pre_users_reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `pre_user_credit_transactions`
--
ALTER TABLE `pre_user_credit_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_user_status_texts`
--
ALTER TABLE `pre_user_status_texts`
  ADD PRIMARY KEY (`value`);

--
-- Indexes for table `pre_webmoney_data`
--
ALTER TABLE `pre_webmoney_data`
  ADD KEY `id` (`id`);

--
-- Indexes for table `pre_yesno_status_texts`
--
ALTER TABLE `pre_yesno_status_texts`
  ADD PRIMARY KEY (`value`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pre_admin_money_transactions`
--
ALTER TABLE `pre_admin_money_transactions`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_bookings`
--
ALTER TABLE `pre_bookings`
  MODIFY `booking_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_categories`
--
ALTER TABLE `pre_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_certificates`
--
ALTER TABLE `pre_certificates`
  MODIFY `certificate_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_country`
--
ALTER TABLE `pre_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;
--
-- AUTO_INCREMENT for table `pre_course_categories`
--
ALTER TABLE `pre_course_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_course_downloads`
--
ALTER TABLE `pre_course_downloads`
  MODIFY `cd_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_course_purchases`
--
ALTER TABLE `pre_course_purchases`
  MODIFY `purchase_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_email_templates`
--
ALTER TABLE `pre_email_templates`
  MODIFY `email_template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `pre_faqs`
--
ALTER TABLE `pre_faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_gallery`
--
ALTER TABLE `pre_gallery`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_groups`
--
ALTER TABLE `pre_groups`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pre_inst_batches`
--
ALTER TABLE `pre_inst_batches`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_inst_enrolled_students`
--
ALTER TABLE `pre_inst_enrolled_students`
  MODIFY `enroll_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_inst_locations`
--
ALTER TABLE `pre_inst_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_inst_offered_courses`
--
ALTER TABLE `pre_inst_offered_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_inst_teaching_types`
--
ALTER TABLE `pre_inst_teaching_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_languages`
--
ALTER TABLE `pre_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `pre_languagewords`
--
ALTER TABLE `pre_languagewords`
  MODIFY `lang_id` bigint(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1244;
--
-- AUTO_INCREMENT for table `pre_locations`
--
ALTER TABLE `pre_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_login_attempts`
--
ALTER TABLE `pre_login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_messages`
--
ALTER TABLE `pre_messages`
  MODIFY `message_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_newsletter_subscriptions`
--
ALTER TABLE `pre_newsletter_subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_packages`
--
ALTER TABLE `pre_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_pages`
--
ALTER TABLE `pre_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `pre_payments_data`
--
ALTER TABLE `pre_payments_data`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_scroll_news`
--
ALTER TABLE `pre_scroll_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_sections`
--
ALTER TABLE `pre_sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pre_seosettings`
--
ALTER TABLE `pre_seosettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `pre_sitetestimonials`
--
ALTER TABLE `pre_sitetestimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_student_leads`
--
ALTER TABLE `pre_student_leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_student_locations`
--
ALTER TABLE `pre_student_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_student_prefferd_teaching_types`
--
ALTER TABLE `pre_student_prefferd_teaching_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_student_preffered_courses`
--
ALTER TABLE `pre_student_preffered_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_subscriptions`
--
ALTER TABLE `pre_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_system_settings_fields`
--
ALTER TABLE `pre_system_settings_fields`
  MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;
--
-- AUTO_INCREMENT for table `pre_system_settings_types`
--
ALTER TABLE `pre_system_settings_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `pre_teaching_types`
--
ALTER TABLE `pre_teaching_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pre_team`
--
ALTER TABLE `pre_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_terms_data`
--
ALTER TABLE `pre_terms_data`
  MODIFY `term_id` bigint(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_testimonials`
--
ALTER TABLE `pre_testimonials`
  MODIFY `testimony_id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_tpay_data`
--
ALTER TABLE `pre_tpay_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_tutor_courses`
--
ALTER TABLE `pre_tutor_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_tutor_locations`
--
ALTER TABLE `pre_tutor_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_tutor_reviews`
--
ALTER TABLE `pre_tutor_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_tutor_selected_types`
--
ALTER TABLE `pre_tutor_selected_types`
  MODIFY `id` int(222) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_tutor_selling_courses`
--
ALTER TABLE `pre_tutor_selling_courses`
  MODIFY `sc_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_tutor_selling_courses_curriculum`
--
ALTER TABLE `pre_tutor_selling_courses_curriculum`
  MODIFY `file_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_tutor_teaching_types`
--
ALTER TABLE `pre_tutor_teaching_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_users`
--
ALTER TABLE `pre_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pre_users_certificates`
--
ALTER TABLE `pre_users_certificates`
  MODIFY `user_certificate_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_users_education`
--
ALTER TABLE `pre_users_education`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_users_experience`
--
ALTER TABLE `pre_users_experience`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_users_groups`
--
ALTER TABLE `pre_users_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pre_users_reviews`
--
ALTER TABLE `pre_users_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_user_credit_transactions`
--
ALTER TABLE `pre_user_credit_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pre_webmoney_data`
--
ALTER TABLE `pre_webmoney_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `pre_users_groups`
--
ALTER TABLE `pre_users_groups`
  ADD CONSTRAINT `fk_group_id` FOREIGN KEY (`group_id`) REFERENCES `pre_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `pre_users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
