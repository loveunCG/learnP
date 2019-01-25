<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	/****** Send Email ******/
	if ( ! function_exists('sendEmail'))
	{
		function sendEmail($from = null, $to = null, $sub = null, $msg = null, $reply_to = null, $cc = null, $bcc = null, $attachment = null)
		{
			
			if(!filter_var($from, FILTER_VALIDATE_EMAIL) ) {
				return false;
			}
			
			$CI = & get_instance();
			if($msg != "") {		
				
				$CI->load->library('email');			
				$CI->email->clear();	

				$options = $CI->config->item('email_settings');

				$default = 'default php';
				$smtp_host = $smtp_port = $smtp_user = $smtp_password = $mandrill_api_key = '';
				foreach($options as $key => $val)
				{
					if(is_array($val))
					{
						if($val['is_default'] == 'Yes')
							$default = $key;
						if(!empty($val['Host']))
							$smtp_host = $val['Host'];
						if(!empty($val['Port']))
							$smtp_port = $val['Port'];
						if(!empty($val['User Name']))
							$smtp_user = $val['User Name'];
						if(!empty($val['Password']))
							$smtp_password = $val['Password'];
					}
					elseif($val == 'Yes')
						$default = $key;					
				}

				if($default == "webmail"){

					$config = Array(
							'protocol' 	=> 'smtp',
							'smtp_host' => $smtp_host,
							'smtp_port' => $smtp_port,
							'smtp_user' => $smtp_user,
							'smtp_pass' => $smtp_password,
							'charset' 	=> 'utf-8',
							'mailtype' 	=> 'html',
							'newline' 	=> "\r\n",
							'wordwrap' 	=> TRUE
						);

					$CI->email->initialize($config);

					$CI->email->from($smtp_user, $CI->config->item('site_settings')->site_title);

					$CI->email->to($to);

					if($reply_to != "" && filter_var($reply_to, FILTER_VALIDATE_EMAIL))
						$CI->email->reply_to($reply_to);
					if($cc != "" && filter_var($cc, FILTER_VALIDATE_EMAIL))
						$CI->email->cc($cc);
					if($bcc != "" && filter_var($bcc, FILTER_VALIDATE_EMAIL))
						$CI->email->bcc($bcc);

					if($attachment != "")
						$CI->email->attach($attachment);

					$CI->email->subject($sub);
					$CI->email->message($msg);

						if( $CI->email->send() )
						return true;
				}
				elseif($default == 'default')
				{		
					$config = array();
					$config['mailtype'] = 'html';
					$CI->email->initialize($config);				
					$CI->email->from($from, $CI->config->item('site_settings')->site_title);
					
					if($reply_to != "" && filter_var($reply_to, FILTER_VALIDATE_EMAIL))
						$CI->email->reply_to($reply_to);
					if($cc != "" && filter_var($cc, FILTER_VALIDATE_EMAIL))
						$CI->email->cc($cc);
					if($bcc != "" && filter_var($bcc, FILTER_VALIDATE_EMAIL))
						$CI->email->bcc($bcc);
					if($attachment != "")
						$CI->email->attach($attachment);				
					$CI->email->subject( $sub );
					$CI->email->message( $msg );
					if( $CI->email->send() )
						return true;
				}
				elseif($default == 'default php')
				{
						//$from = $CI->config->item('emailSettings')->from_email;
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
						$headers .= "X-Priority: 1\r\n"; 
						$headers .= 'From: <'.$from.'>' . "\r\n";
						$headers .= 'Reply-To: ' . $from . "\r\n";
						$headers .= 'X-Mailer: PHP/' . phpversion();
						mail($to,$sub,$msg, $headers);
						return true;
				}
				/**end of  sendEmail through Web mail settings**/
				else {

					$CI->load->config('mandrill');

					$CI->load->library('mandrill');	

					$mandrill_ready = NULL;

					try {
						$CI->mandrill->init( $mandrill_api_key );
						$mandrill_ready = TRUE;
					} catch(Mandrill_Exception $e) {
						$mandrill_ready = FALSE;
					}

					if( $mandrill_ready ) {

						//Send us some email!
						$email = array(
							'html' => $msg, //Consider using a view file
							'text' => '',
							'subject' => $sub,
							'from_email' => $from,
							'from_name' => $CI->config->item('site_settings')->site_title,
							'to' => array(array('email' => $to )),
							);

							$result = $CI->mandrill->messages_send($email);
							print_r($result);die();
							if($result[0]['status']=='sent')
							return TRUE;
							else
							return FALSE;
					}
									
				}
			}
			return false;
		}
	}
	
	//Get User INfo
	if( ! function_exists('getUserRec'))
	{
		function getUserRec($userId='')
		{			
			$CI =& get_instance();
			$user = $CI->ion_auth->user()->row();
			if($userId!='' && is_numeric($userId))
			{
				$user = $CI->ion_auth->user($userId)->row();
			}			
			return $user;
		}
	}
	
	if( ! function_exists('getCurrentUserId'))
	{
		function getCurrentUserId()
		{
			$CI =& get_instance();
			return getUserRec()->id;
		}
	}
	//Get User Type
	if( ! function_exists('getUserType'))
	{
		function getUserType($user_id='')
		{
			$CI =& get_instance();
			$user_type='';
			if($user_id=='')
			{
				$user_id = getUserRec()->id;
			}
			$user_groups = $CI->ion_auth->get_users_groups($user_id)->result();
			switch($user_groups[0]->id)
			{
				case 1: $user_type='admin';
					break;
				case 2: $user_type='student';
					break;
				case 3: $user_type='tutor';
					break;
				case 4: $user_type='institute';
					break;				
				default:
					break;
			} 
			return $user_type;
		}
	}
	
	//Get User Type Id
	if( ! function_exists('getUserTypeId'))
	{
		function getUserTypeId($user_id='')
		{
			$CI =& get_instance();
			$user_type='';
			if(getUserRec() != NULL)
			{
			if($user_id=='') 
			{
				$user_id = getUserRec()->id;
			}
			$user_groups = $CI->ion_auth->get_users_groups($user_id)->result();
			if(count($user_groups))
				return $user_groups[0]->id;
			else
				return 0;
			}else
			{
				return 0;
			}
		}
	}
	
	if( ! function_exists('getUserId'))
	{
		function getUserId($user_id='')
		{
			$CI =& get_instance();
			$user_type='';
			if(getUserRec() != NULL)
			{
				if($user_id=='') 
				{
					$user_id = getUserRec()->id;
				}
				$user_groups = $CI->ion_auth->get_users_groups($user_id)->result();
				if($user_id != '')
					return $user_id;
				else
					return 0;
			}
			else
			{
				return 0;
			}
		}
	}
		
	//Get User Type
	if( ! function_exists('getTemplate'))
	{
		function getTemplate($user_id='')
		{
			$CI =& get_instance();
			$user_type='';
			$template='';
			if($user_id=='') 
			{
				$user_id = getUserRec()->id;
			}
			$user_groups = $CI->ion_auth->get_users_groups($user_id)->result();
			switch($user_groups[0]->id)
			{
				case 1: 
					$user_type='admin';
					$template = $user_type.'-template';
					break;
				case 2: 
					$user_type='user';
					$template = 'site-template';
					break;
			} 
			return $template;
		}
	}
	
	if( ! function_exists('clean_url'))
	{
		function clean_url($text)
		{
			$result_with_dashes = true; //set this to false if you want output with spaces as a separator
			$input_is_english_only = true; //set this to false if your input contains non english words
			
			$text = str_replace(array('"','+',"'"), array('',' ',''), urldecode($text));
			if($input_is_english_only === true)
			{
				$text = preg_replace('/[~`\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/', " ", $text);
			}
			else
			{
				$text = preg_replace('/[^A-Za-z0-9\s\s+\.\)\(\{\}\-]/', " ", $text);
			}
			$bad_brackets = array("(", ")", "{", "}");
			$text = str_replace($bad_brackets, " ", $text);
			$text = preg_replace('/\s+/', ' ', $text);
			$text = trim($text,' .-');
			if($result_with_dashes === true)
			{
				$text = str_replace(' ','-',$text);
			}
			$text = preg_replace('/-+/', '-', $text);
			return strtolower($text);
		}
	}
	
	function clean_text($string) {
	   $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

	   return preg_replace('/[^A-Za-z0-9\_]/', '', $string); // Removes special chars.
	}	
	/**
	 * Prepare message
	 *
	 */
	function prepare_message($msg,$type = 2)
	{
		$returnmsg='';
		switch($type){
			case 0: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-success'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('success')."!</strong> ". $msg."
										</div>
									</div>";
				break;
			case 1: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-danger'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('error')."!</strong> ". $msg."
										</div>
									</div>";
				break;
			case 2: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-info'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('info')."!</strong> ". $msg."
										</div>
									</div>";
				break;
			case 3: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-warning'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>".get_languageword('warning')."!</strong> ". $msg."
										</div>
									</div>";
				break;
		}
		
		return $returnmsg;
	}
	
	if ( ! function_exists('get_languageword'))
	{
		function get_languageword($phrase = '', $variables = array()) {
			$phrase = strip_tags($phrase);
			$CI	=&	get_instance();
			$CI->load->database();
			$current_language	=	strtolower($CI->session->userdata('current_language'));	
			$sitedefault = $CI->config->item('site_settings')->default_language;
			//echo $current_language;die();
			if ( $current_language	==	'') {				
				if($sitedefault != '') {				
					$CI->session->set_userdata('current_language' , $sitedefault);
				}
				else {
					$CI->session->set_userdata('current_language' , 'english');
				}
				
				$CI->session->set_userdata('current_language' , 'english');
				$current_language	=	strtolower($CI->session->userdata('current_language'));
			} 
			elseif($current_language != $sitedefault)
			{
				if($sitedefault != '') {				
					$CI->session->set_userdata('current_language' , $sitedefault);
				}
				else {
					$CI->session->set_userdata('current_language' , 'english');
				}
			}
			$query = $CI->db->get_where('languagewords' , array('lang_key' => $phrase));
			$row   	=	$query->row();
			
			if($row == null)
			{
				/*
				$apiKey = '<paste your API key here>';
				$url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($phrase) . '&source=en&target=fr';
				$handle = curl_init($url);
				curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($handle);                 
				$responseDecoded = json_decode($response, true);
				curl_close($handle);

				echo 'Source: ' . $text . '<br>';
				echo 'Translation: ' . $responseDecoded['data']['translations'][0]['translatedText'];
				*/
	
				$data = array(
					'lang_key' => $phrase,
					'english' => ucfirst(str_replace('_',' ',$phrase)),
				);
				$CI->db->insert('languagewords', $data); //If word is not found in database we are inserting it as new word
			}
			
			if (isset($row->$current_language) && $row->$current_language !="")
			{
				if(empty($variables))
				{
					return $row->$current_language;
				}
				else
				{
					return sprintf($row->$current_language, implode($variables));
				}
			}
				
			else
			{
				return ucfirst(str_replace('_',' ',$phrase));
			}
			
		}
	}
	
	/**
	 * Validate URL
	 *
	 * @access    public
	 * @param    string
	 * @return    string
	 */
	if ( ! function_exists('valid_url'))
	{
		function valid_url($url)
		{
			echo $url;
			die();
			$pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
			if (!preg_match($pattern, $url))
			{
				return FALSE;
			}

			return TRUE;
		}
	}
	
	/**
	 * Check for logged in uyser
	 *
	 * @access    public
	 * @param    string
	 * @return    string
	 */
	if ( ! function_exists('check_access'))
	{
		function check_access( $type = 'admin')
		{
			$CI	=&	get_instance();
			
			if (!$CI->ion_auth->logged_in())
			{
				redirect(URL_AUTH_LOGIN, 'refresh');
			}
			elseif($type == 'admin')
			{
				if (!$CI->ion_auth->is_admin())
				{
					$CI->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
					redirect(SITEURL2);
				}
			}
			elseif($type == 'user')
			{
				if (!$CI->ion_auth->is_member())
				{
					$CI->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
					redirect(SITEURL2);
				}
			}
		}
	}
	
	/**
	 * Find record for give value
	 *
	 * @access    public
	 * @param    string
	 * @return    string
	 */
	 function find_record($key, $values)
	 {
		 $record = '';
		 if(count($values))
		 {
			 foreach($values as $index => $val)
			 {
				 if($val->available_day == $key)
					 $record = $val;
			 }
		 }
		 return $record;
	 }
	 	
	if ( ! function_exists('checkAvatar'))
	{
		function checkAvatar($img_url)
		{
			$img = IMG_DEFAULT_AVATAR;							
			if(! is_null($img_url) && ($img_url!='') && (file_exists(URL_PUBLIC_UPLOADS_THUMBS.$img_url)) )			{
				$img = URL_PUBLIC_UPLOADS_THUMBS.$img_url;
			}			
			return $img;
		}
	}
	if ( ! function_exists('getUserPackage'))
	{
		function getUserPackage($userId=NULL,$subscriptionId=NULL)
		{
			$CI	=&	get_instance();
			$subscription = array();							
			
			if(is_numeric($userId))
			{
				if($userId > 0 && $subscriptionId > 0)
				{
					$subscription = $CI->base_model->fetch_records_from(TBL_SUBSCRIPTIONS,array('payer_id'=>$userId,'subscription_id'=>$subscriptionId,'status'=>'Active'));
				}
				else if($userId > 0 && $subscriptionId == '')
				{
					$subscription = $CI->base_model->fetch_records_from(TBL_SUBSCRIPTIONS,array('payer_id'=>$userId,'status'=>'Active'));
				}
			}
			else
			{
				$subscription = array();	
			}
			
			
			if(count($subscription)>0)
			{
				$subscription = $subscription[0];
			}

			return $subscription;
		}
	}
	
	if ( ! function_exists('current_uri'))
	{
		function current_uri()
		{
			$CI	=&	get_instance();
			return SITEURL2.'/'.$CI->uri->uri_string();
		}
	}
	
	function print_message($message = '')
	{
		if($message != '')
			$message = '<div id="infoMessage">'.$message.'</div>';
		return $message;
	}
if( ! function_exists('sendSMS'))
{
	function sendSMS($mobile_number='',$message='')
	{
		if($mobile_number=='' || $message=='') 
		{
			return array('result' => 0, 'message' => 'Please enter mobile number');
		}
		
		$CI =& get_instance();
		$query = 'SELECT sst2.* FROM '.$CI->db->dbprefix('system_settings_types').' sst1 INNER JOIN '.$CI->db->dbprefix('system_settings_types').' sst2 ON sst1.type_id = sst2.parent_id WHERE sst1.type_slug = "SMS_SETTINGS" AND sst2.status="Active" AND sst2.is_default = "Yes"';
		$sms_settings = $CI->db->query($query)->result();
		
		
		if(count($sms_settings) == 0) //If there is no default SMS gateway, we will pick the any one gateway to send the SMS
		{
			$query = 'SELECT sst2.* FROM '.$CI->db->dbprefix('system_settings_types').' sst1 INNER JOIN '.$CI->db->dbprefix('system_settings_types').' sst2 ON sst1.type_id = sst2.parent_id WHERE sst1.type_slug = "SMS_SETTINGS" AND sst2.status="Active" ORDER BY type_title LIMIT 1';
			$sms_settings = $CI->db->query($query)->result();
		}
		//print_r($sms_settings);die();
	    if(count($sms_settings)>0 && $sms_settings[0]->status=='Active') 
		{
			$fields = $CI->db->query('SELECT * FROM  '.$CI->db->dbprefix('system_settings_fields').' sf WHERE type_id = '.$sms_settings[0]->type_id)->result();
			$to = $mobile_number;			
			if(count($fields) > 0)
			{
				$result = array();
				if($sms_settings[0]->type_title == 'Cliakatell') 
				{
					$CI->load->library('clickatell');
					$response = $CI->clickatell->send_message($to, $message);
					if($response === FALSE)
					{
						$result = array('result' => 0, 'message' => $CI->clickatell->error_message);
					}
					else
					{
						$result = array('result' => 1, 'message' => get_languageword('message_sent_successfully'));
					}
				}
				elseif($sms_settings[0]->type_title == 'Nexmo') 
				{
					$CI->load->library('nexmo');
					$CI->nexmo->set_format('json');
					$from = '1234567890';
					$smstext = array(
							'text' => $message,
						);
					$response = $CI->nexmo->send_message($from, $to, $smstext);
					// echo "<pre>";print_r($response);die();
					$other_details = serialize($response);
					$status = $response['messages'][0]['status'];
					if($status == 0) {
						$result = array('result' => 1, 'message' => get_languageword('message_sent_successfully'));
					} else {
						$result = array('result' => 0, 'message' => $response['messages'][0]['error-text']);
					}
				}
				elseif($sms_settings[0]->type_title == 'Plivo') 
				{
					$CI->load->library('plivo');
					$sms_data = array(
							'src' => '919700376656', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
							'dst' => $to, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
							'text' => $message, // The text to send
							'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
						);
					$response = $CI->plivo->send_sms($sms_data);
					$other_details = serialize($response);
					if ($response[0] == '202') //Success
					{
						$result = array('result' => 1, 'message' => get_languageword('message_sent_successfully'));
					}
					else
					{
						$response2 = json_decode($response[1], TRUE);
						//print_r($response2);print_r($response);die();
						$result = array('result' => 0, 'message' => $response2["error"]);
					}
				}
				elseif($sms_settings[0]->type_title == 'Solutionsinfini') 
				{
					$CI->load->helper('solutionsinfini');
					$solution_object = new sendsms();
					$response = $solution_object->send_sms($to, $message, current_url());
					if(strpos($response,'Message GID') === false) //Failed
					{
						$result = array('result' => 0, 'message' => $response);
					}
					else
					{
						$result = array('result' => 1, 'message' => get_languageword('message_sent_successfully'));
					}
				}
				elseif($sms_settings[0]->type_title == 'Twilio') 
				{
					$CI->load->helper('ctech-twilio');
					$client = get_twilio_service();
					$twilio_number = '';
					//print_r($fields);die();
					foreach($fields as $field)
					{
						if($field->field_key == 'Twilio_Phone_Number')
							$twilio_number = $field->field_output_value;
					}
					try {
						$response = $client->account->messages->sendMessage($twilio_number,'+'.$to,$message);
						//print_r($response);die();
						$result = array('result' => 1, 'message' => get_languageword('message_sent_successfully'));
					} catch (Exception $e ){
						$result = array('result' => 0, 'message' => $e->getMessage());
					}
				}
				return $result;
			}
			else
			{
				return array('result' => 0, 'message' => 'No SMS gateway configured. Please contact administrator');
			}
			
		}
		else
		{
			return array('result' => 0, 'message' => 'No SMS gateway configured. Please contact administrator'); 
		}
		
	}
}
if(!function_exists('getPostedDate'))
{
	function getPostedDate($timeString)
	{
		$str = explode(",", timespan($timeString, time()))[0]." ago";
		return $str;
	}
}
/**
 * CodeIgniter helper for generate share url and buttons (Twitter, Facebook, Buzz, VKontakte)
 *
 * @version 1.0
 * @author Ibragimov Renat <info@mrak7.com> www.mrak7.com
 */

if( !function_exists('share_check') ){
	/**
	 * Check type of share and return $URL or FALSE
	 * 
	 * @param	string $type	type of share
	 * @return	string|bool
	 */
	function share_check( $type='' ){
		$url = array(
			'twitter'	=> 'http://twitter.com/share',
			'facebook'	=> 'http://facebook.com/sharer.php',
			'buzz'		=> 'http://www.google.com/buzz/post',
			'vkontakte'	=> 'http://vkontakte.ru/share.php',
			'googleplus' => 'https://plus.google.com/share',
			'pinterest' => 'http://pinterest.com/pin/create/button',
		);
		return (isset($url[$type])) ? $url[$type] : FALSE;
	}
}

if( !function_exists('share_url') ){
	/**
	 * Generate url for share at some social networks
	 *
	 * @param	string $type	type of share
	 * @param	array $args		parameters for share
	 * @return	string
	 */
	function share_url( $type='', $args=array() ){
		$url = share_check( $type );
		if( $url === FALSE ){
			log_message( 'debug', 'Please check your type share_url('.$type.')' );
			return "#ERROR-check_share_url_type";
		}

		$params = array();
		if( $type == 'twitter' ){
			foreach( explode(' ', 'url via text related count lang counturl') as $v ){
				if( isset($args[$v]) ) $params[$v] = $args[$v];
			}
		}elseif( $type == 'facebook' ){
			$params['u']		= $args['url'];
			$params['title']		= $args['text'];
			$params['picture']		= (isset($args['picture'])) ? $args['picture'] : '';
		}elseif( $type == 'buzz'){
			$params['url']		= $args['url'];
			$params['imageurl']	= $args['image'];
			$params['message']	= $args['text'];
		}elseif( $type == 'vkontakte'){
			$params['url']		= $args['url'];
		}elseif($type == 'googleplus') {
			$params['url']		= $args['url'];
			$params['description']	= $args['text'];
		}elseif($type == 'pinterest') {
			$params['url']		= $args['url'];
			$params['description']	= $args['text'];
			$params['media']		= (isset($args['picture'])) ? $args['picture'] : '';
		}
		$param = '';
		
		foreach( $params as $k=>$v ) $param .= '&'.$k.'='.urlencode($v);
		return $url.'?'.trim($param, '&');
	}
}

if( !function_exists('share_button') ){
	/**
	 * Generate buttons for share at some social networks
	 *
	 * @param	string $type	type of share
	 * @param	array $args		parameters for share
	 * @return string
	 */
	function share_button( $type='', $args=array() ){
		$url = share_check( $type );
		if( $url === FALSE ){
			log_message( 'debug', 'Please check your type share_button('.$type.')' );
			return "#ERROR-check_share_button_type";
		}

		$params = array();
		$param	= '';

		if( $type == 'twitter'){
			if( isset($args['iframe']) ){
				$url = share_url( $type, $args );
				list($url, $param) = explode('?', $url);
				$button = <<<DOT
				<iframe allowtransparency="true" frameborder="0" scrolling="no" style="width:130px; height:50px;"
				src="http://platform.twitter.com/widgets/tweet_button.html?{$param}"></iframe>
DOT;
			}else{
				foreach( explode(' ', 'url via text related count lang counturl') as $v ){
					if( isset($args[$v]) ) $params[] = 'data-'.$v.'="'.$args[$v].'"';
				}
				$param = implode( ' ', $params );
				$button = <<<DOT
				<a href="http://twitter.com/share" class="twitter-share-button" {$param}>Tweet</a>
				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
DOT;
			}
		}elseif( $type == 'facebook' ){
			if( !isset($args['type']) ) $args['type'] = 'button_count';
			if( isset($args['fb']) ){
				$params = array( 'type'=>'type', 'href'=>'url', 'class'=>'class' );
				foreach( $params as $k=>$v ){
					if( isset($args[$v]) ) $param .= $k.'="'.$args[$v].'"';
				}
				$button = "<fb:share-button {$param}></fb:share-button>";
			}else{
				$params = array( 'type'=>'type', 'share_url'=>'url' );
				foreach( $params as $k=>$v ){
					if( isset($args[$v]) ) $param .= $k.'="'.$args[$v].'"';
				}
				if( !isset($args['button_text']) ) $args['button_text'] = 'Share to Facebook';
				$button = <<<DOT
				<a name="fb_share" {$param}>{$args['button_text']}</a>
				<script src="//connect.facebook.net/en_US/sdk.js"; type="text/javascript"></script>
DOT;
			}
		}elseif( $type == 'buzz' ){
			$params = array( 'button-style'=>'type', 'local'=>'lang', 'url'=>'url', 'imageurl'=>'image');
			foreach( $params as $k=>$v ){
				if( isset($args[$v]) ) $param .= ' data-'.$k.'="'.$args[$v].'"';
			}
			if( !isset($args['title']) ) $args['title'] = 'Share to Google Buzz';
			$button = <<<DOT
			<a title="{$args['title']}" class="google-buzz-button" href="http://www.google.com/buzz/post" {$param}></a>
			<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script>
DOT;
		}elseif( $type == 'vkontakte' ){
			$url = isset($args['url']) ? '{url: "'.$args['url'].'"}' : 'false';
			foreach( explode(' ', 'type text') as $v ){
				if( isset($args[$v]) ) $param[] = $k.': "'.urlencode($args[$v]).'"';
			}
			$param = implode( ', ', $params );
			if( !empty($param) ) $param = ', {'.$param.'}';
			$button = <<<DOT
			<script type="text/javascript" src="http://vkontakte.ru/js/api/share.js?9" charset="windows-1251"></script>
			<script type="text/javascript">document.write(VK.Share.button({$url}{$param}));</script>
DOT;
		}
		return $button;
	}
}

function strip_single_tag($str,$tag){

    $str1=preg_replace('/<\/'.$tag.'>/i', '', $str);
    if($str1 != $str){
        $str=preg_replace('/<'.$tag.'[^>]*>/i', '', $str1);
    }
    return $str;
}

function getWikiimage($author_name = '', $author_id = '')
{
	$url = 'https://en.wikipedia.org/wiki/'.addslashes($author_name);
	//echo $url;die();
	$data = @file_get_contents($url, FALSE);	//Getting HTML Content from wikmipedia.org	
	$original = $data;
	if($url == "https://en.wikipedia.org/wiki/Anthony J. D\'Angelo") {
	 //var_dump($data);die();
	}
	$image = '';
	if($data)
	{
	$data2 =  between('<table class="infobox biography vcard" style="width:22em">', '</table>', $data); //Get vCard information	
	//var_dump($data2);die();
	if($data2 == '')
	{
	$data2 =  between('<table class="infobox vcard" style="width:22em">', '</table>', $data); //Get vCard information
	}
	if($data2 == '')
	{
	$data2 =  between('<table class="infobox vcard" style="width:22em;width: 25em; font-size: 95%; line-height: 1.2em;">', '</table>', $data); //Get vCard information
	}
	if($data2 == '')
	{
	$data2 =  between('<table class="infobox vcard plainlist" style="width:22em">', '</table>', $data); //Get vCard information
	}
	if($data2 == '')
	{
	$data2 =  between('<table class="infobox vcard" style="width:22em;width:315px;border-spacing:2px;">', '</table>', $data); //Get vCard information
	}
	
	if($data2 == '')
	{
	$data2 =  between('<table class="infobox bordered vcard" style="width:22em">', '</table>', $data); //Get vCard information
	}
	
	if($data2 == '')
	{
	$data2 =  between('<table class="infobox vcard" style="width:22em;width: 24em;">', '</table>', $data); //Get vCard information
	}
	
	if($data2 == '')
	{
	$data2 =  between('<table class="infobox" style="width:22em">', '</table>', $data); //Get vCard information
	}
		
	$data2 = str_replace(array('<wbr>','<wbr />'),'',$data2);
	
	/*$data2 = preg_replace('#<a.*?>([^>]*)</a>#i', '$1', $data2); //Remove Anchor Tags from HTML*/
	$data2 = strip_single_tag($data2,'audio');
	//echo $data2;die('ppppppppp');
	if($data2 != '')
	{
	$doc = new DOMDocument();
	$doc->loadHTML($data2);
	$imageTags = $doc->getElementsByTagName('img');
	
	$i = 0;
	foreach($imageTags as $tag) {
		$i++;
		$image = $tag->getAttribute('src');
		if($i == 1) break;
	}
	}
	else
	{
		//$image = URL_FRONT_IMAGES.'No_image_available.png';
	}
	}
	else
	{
		//$image = URL_FRONT_IMAGES.'No_image_available.png';	
	}
	
	if($image == '')
	{
		$data2 =  between('<div class="thumb tright">', '</div>', $original); //Get vCard information
		$data2 = str_replace(array('<wbr>','<wbr />'),'',$data2);	
		$data = preg_replace('#<a.*?>([^>]*)</a>#i', '$1', $data2); //Remove Anchor Tags from HTML
		$data2 = strip_single_tag($data,'audio');
		if($data2 != '')
		{
		$doc = new DOMDocument();
		$doc->loadHTML($data2);
		$imageTags = $doc->getElementsByTagName('img');
		//print_r($imageTags);die();
		$i = 0;
		foreach($imageTags as $tag) {
			$i++;
			$image = $tag->getAttribute('src');
			if($i == 1) break;
		}
		}
	}
	
	if($author_id != '')
	{
		$CI =& get_instance();
	$CI->db->query('UPDATE '.TBL_PREFIX.TBL_TERMS_DATA.' SET term_image = "'.$image.'", updated_from_wiki="Yes" WHERE term_id = '.$author_id);
	}
	
	//$image = URL_FRONT_IMAGES.'No_image_available.png';
	return $image;
	
}

function getTextBetweenTags($string, $tagname) {
    $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
    preg_match($pattern, $string, $matches);
    return $matches[1];
}

function getElementsByClass(&$parentNode, $tagName, $className) {
    $nodes=array();

    $childNodeList = $parentNode->getElementsByTagName($tagName);
    for ($i = 0; $i < $childNodeList->length; $i++) {
        $temp = $childNodeList->item($i);
        if (stripos($temp->getAttribute('class'), $className) !== false) {
            $nodes[]=$childNodeList;
        }
    }

    return $nodes;
}

function before ($this, $inthat)
{
	return substr($inthat, 0, strpos($inthat, $this));
}
function between($this, $that, $inthat)
{
	return before ($that, after($this, $inthat));
}
function after ($this, $inthat)
{
	if (!is_bool(strpos($inthat, $this)))
	return substr($inthat, strpos($inthat,$this)+strlen($this));
}

function getWikiVcard($author_name = '', $length = '', $author_id = ''){
	$url = 'https://en.wikipedia.org/wiki/'.addslashes($author_name);
	$data = @file_get_contents($url, FALSE);	//Getting HTML Content from wikmipedia.org	
	//var_dump($data);die();
	$data2 =  between('<table class="infobox biography vcard" style="width:22em">', '</table>', $data); //Get vCard information	
	
	if($data2 == '' || $data2 == false)
	{
	$data2 =  between('<table class="infobox vcard" style="width:22em">', '</table>', $data); //Get vCard information
	}
	$datafound = TRUE;
	if($data2 == '' || $data2 == false)
	{
		//$data2 =  between('<div role="main" class="mw-body" id="content">', '</div>', $data); //Get vCard information
		$url = 'https://en.wikipedia.org/w/api.php?action=opensearch&search='.$author_name.'&format=json';
		$data3 = file_get_contents($url, FALSE);
		$doc = json_decode($data3);
		$description = '';
		if(count($doc) > 0)
		{
			
			if(isset($doc[1]) && count($doc[1]) > 0)
			{
				foreach($doc[1] as $key => $val)
				{
					$description .= '<b>'.$doc[1][$key].'</b>:'.$doc[2][$key].'<br>';
				}
			}
		}
		$data2 = $description;
		$datafound = FALSE;
	}
	//var_dump($data2);die();
	//$data2 = ($length == '') ? $data2 : substr($data2,0,$length).'...';
	if($data2 != '' && $datafound == TRUE)
	{
	$data2 = $data2.'<script>$( "tr:nth-child(1)").hide();$( "tr:nth-child(2)").hide();</script>';	
	$data = preg_replace('#<a.*?>([^>]*)</a>#i', '$1', $data2);
	}
	elseif($data2 != '' && $datafound == FALSE)
	{
	$data = preg_replace('#<a.*?>([^>]*)</a>#i', '$1', $data2);
	}
	if($data == false) $data = '';
	if($author_id != '' && $data != '')
	{
		$CI =& get_instance();
	if($data2 == '')
	{
		
	}
		elseif($datafound == TRUE)
		{	
			$data = '<table class="infobox vcard" style="width:22em">'.$data.'</table>';
		}
		elseif($datafound == FALSE)
		{
			$data = '<table class="infobox vcard" style="width:22em"><tr><td>'.$data.'</td></tr></table>';
		}	
		$CI->base_model->update_operation(array('term_content'=>addslashes($data)),TBL_TERMS_DATA,array('term_id'=>$author_id));	
	}
	return $data;
}

function getWikiauthor($author, $length = '', $author_id = '')
{
	$url = 'https://en.wikipedia.org/w/api.php?action=opensearch&search='.$author.'&format=json';
	$data = file_get_contents($url, FALSE);	//Getting HTML Content from wikmipedia.org
	//echo $data;
	$doc = json_decode($data);
	/*
	if(isset($doc[1]) && count($doc[1]) == 0)
	{
		$url = 'https://en.wikipedia.org/w/api.php?action=opensearch&search='.urldecode($author).'&format=json';
		$data = file_get_contents($url, FALSE);	//Getting HTML Content from wikmipedia.org
		$doc = json_decode($data);
	}
	*/
	$description = '';
	if(count($doc) > 0)
	{
		
		if(isset($doc[1]) && count($doc[1]) > 0)
		{
			foreach($doc[1] as $key => $val)
			{
				$description .= '<b>'.$doc[1][$key].'</b>:'.$doc[2][$key].'<br>';
			}
		}
	}
	//echo $length;die();
	if($description != '')
	{
	//$description = ($length == '') ? $description : substr($description,0,$length).'...';
	}
	
	if($author_id != '' && $description != '')
	{
	$CI =& get_instance();
	$CI->db->query("UPDATE ".TBL_PREFIX.TBL_TERMS_DATA." SET author_wiki = '".addslashes($description)."' WHERE term_id = '".$author_id."' ");
	}
	
	return $description;
}

/**
 * Count number of records for given condition
 *
 * @access  public
 * @param   table_name
 * @param 	conditon
 * @return  integer
 */
if ( ! function_exists('count_records'))
{
	function count_records( $table_name, $condition = array())
	{
		$CI	=&	get_instance();
		$records = $CI->db->get_where($table_name, $condition)->result();
		return count($records);
	}
}

/**
 * Count number of records for given query
 *
 * @access  public
 * @param   table_name
 * @param 	conditon
 * @return  integer
 */
if ( ! function_exists('count_records_query'))
{
	function count_records_query( $query )
	{
		$CI	=&	get_instance();
		$records = $CI->db->query($query)->result();
		return count($records);
	}
}

function activeinactive()
{
	return array('Active' => get_languageword('active'), 'In-Active' => get_languageword('inactive'));
}

function noyes()
{
	return array('No' => get_languageword('no'), 'Yes' => get_languageword('yes'));
}

function yesno()
{
	return array('Yes' => get_languageword('yes'), 'No' => get_languageword('no'));
}

function neatPrint($content = array(), $stop = TRUE)
{
	if(empty($content)) 
		$content = $_POST;
	echo '<pre>';print_r($content);
	if($stop)
	die();
}

function required_symbol()
{
	return '&nbsp;<font color="red">*</font>';
}

if( ! function_exists('clean_url'))
{
	function clean_url($text)
	{
		$result_with_dashes = true; //set this to false if you want output with spaces as a separator
		$input_is_english_only = true; //set this to false if your input contains non english words
		
		$text = str_replace(array('"','+',"'"), array('',' ',''), urldecode($text));
		if($input_is_english_only === true)
		{
			$text = preg_replace('/[~`\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/', " ", $text);
		}
		else
		{
			$text = preg_replace('/[^A-Za-z0-9\s\s+\.\)\(\{\}\-]/', " ", $text);
		}
		$bad_brackets = array("(", ")", "{", "}");
		$text = str_replace($bad_brackets, " ", $text);
		$text = preg_replace('/\s+/', ' ', $text);
		$text = trim($text,' .-');
		if($result_with_dashes === true)
		{
			$text = str_replace(' ','-',$text);
		}
		$text = preg_replace('/-+/', '-', $text);
		return strtolower($text);
	}
}



if( ! function_exists('calcAge'))
{
	function calcAge($from = '', $to = '')
	{
		if(empty($from))
			return 0;

		$from = new DateTime($from);
		$to   = (!empty($to)) ? $to : new DateTime('today');
		return $from->diff($to)->y;
	}
}



if ( ! function_exists('get_categories'))
{
	function get_categories($params = array())
	{
		$CI	=&	get_instance();
		$CI->load->model('home_model');
		$records = $CI->home_model->get_categories($params);
		return $records;
	}
}



if ( ! function_exists('get_course_img'))
{
	function get_course_img($image = "")
	{
		if(!empty($image) && file_exists(URL_UPLOADS_COURSES_PHYSICAL.$image))
			return URL_UPLOADS_COURSES.$image;
		else
			return URL_UPLOADS_COURSES_DEFAULT_IMAGE;
	}
}


if ( ! function_exists('get_selling_course_img'))
{
	function get_selling_course_img($image = "")
	{
		if(!empty($image) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$image))
			return URL_PUBLIC_UPLOADS2.'course_curriculum_files/'.$image;
		else
			return URL_UPLOADS_COURSES_DEFAULT_IMAGE;
	}
}


if ( ! function_exists('get_tutor_img'))
{
	function get_tutor_img($image = "", $gender = "male")
	{
		if(!empty($image) && file_exists(URL_UPLOADS_PROFILES_PHYSICAL.$image)) {

			return URL_UPLOADS_PROFILES.$image;

		} else {

			if(strcasecmp($gender, 'male') == 0)
				return URL_UPLOADS_PROFILES_TUTOR_MALE_DEFAULT_IMAGE;
			else
				return URL_UPLOADS_PROFILES_TUTOR_FEMALE_DEFAULT_IMAGE; 
		}

	}
}


if ( ! function_exists('get_inst_img'))
{
	function get_inst_img($image = "")
	{
		if(!empty($image) && file_exists(URL_UPLOADS_PROFILES_PHYSICAL.$image)) {

			return URL_UPLOADS_PROFILES.$image;

		} else {

			return URL_UPLOADS_PROFILES_INSTITUTE_DEFAULT_IMAGE; 
		}

	}
}



if ( ! function_exists('get_student_img'))
{
	function get_student_img($image = "", $gender = "male")
	{
		if(!empty($image) && file_exists(URL_UPLOADS_PROFILES_PHYSICAL.$image)) {

			return URL_UPLOADS_PROFILES.$image;

		} else {

			if(strcasecmp($gender, 'male') == 0)
				return URL_UPLOADS_PROFILES_STUDENT_MALE_DEFAULT_IMAGE;
			else
				return URL_UPLOADS_PROFILES_STUDENT_FEMALE_DEFAULT_IMAGE; 
		}

	}
}



if ( ! function_exists('get_system_settings'))
{
	function get_system_settings($field_key = "")
	{
		if(empty($field_key))
			return NULL;

		$CI	=&	get_instance();
		$CI->load->model('settings/settings_model');
		return $CI->settings_model->get_system_settings($field_key);
	}
}



if ( ! function_exists('get_user_online_status'))
{
	function get_user_online_status($is_online = "")
	{
		if(empty($is_online))
			return NULL;

		if(strcasecmp($is_online, 'yes') == 0)
			return '<p class="user-status"><i class="fa fa-clock-o"></i> '.get_languageword('online_now').'</p>';
		else
			return '<p class="user-status offline"><i class="fa fa-clock-o"></i> '.get_languageword('offline_now').'</p>';
	}
}



if ( ! function_exists('log_user_credits_transaction'))
{
	function log_user_credits_transaction($log_data = array())
	{
		if(empty($log_data))
            return NULL;

		$CI	=&	get_instance();
		$CI->load->model('home_model');
		return $CI->home_model->log_user_credits_transaction($log_data);
	}
}


if ( ! function_exists('get_user_credits'))
{
	function get_user_credits($user_id = "")
	{
		if(!($user_id > 0))
            return 0;

		return getUserRec($user_id)->net_credits;
	}
}



if ( ! function_exists('is_premium'))
{
	function is_premium($user_id = "")
	{
		if(!($user_id > 0))
			return FALSE;

		$min_credits_for_premium_txt = "";
		$user_type = getUserType($user_id);

		if($user_type == "tutor")
			$min_credits_for_premium_txt = 'min_credits_for_premium_tutor';
		else if($user_type == "student")
			$min_credits_for_premium_txt = 'min_credits_for_premium_student';
		else if($user_type == "institute")
			$min_credits_for_premium_txt = 'min_credits_for_premium_institute';

		$user_net_credits = get_user_credits($user_id);
		$min_credits_for_premium_val = get_system_settings($min_credits_for_premium_txt);

		if($user_net_credits >= $min_credits_for_premium_val)
			return TRUE;
		else
			return FALSE;
	}
}


if ( ! function_exists('is_eligible_to_make_booking'))
{
	function is_eligible_to_make_booking($student_id = "", $fee = "")
	{
		if(!($student_id > 0) || empty($fee))
			return FALSE;

		$student_net_credits = get_user_credits($student_id);

		if($student_net_credits >= $fee)
			return TRUE;
		else
			return FALSE;
	}
}



if ( ! function_exists('get_day_count'))
{
	function get_day_count($day = "", $start_date = "", $end_date = "") //E.g: $day='MON', $start_date='2016-09-22', $end_date = '2016-09-30'
	{
		$days = array(
						'MON' => 1,
						'TUE' => 2,
						'WED' => 3,
						'THU' => 4,
						'FRI' => 5,
						'SAT' => 6,
						'SUN' => 7
					);

		if(!array_key_exists($day, $days) || empty($start_date) || empty($end_date))
			return 0;


		$start_date = date('Y-m-d', strtotime($start_date));
		$end_date   = date('Y-m-d', strtotime($end_date));

		$no = 0;

		$start = new DateTime($start_date);
		$end   = new DateTime($end_date);
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($start, $interval, $end);
		foreach ($period as $dt)
		{
		    if ($dt->format('N') == $days[$day])
		    {
		        $no++;
		    }
		}
		return $no;
	}
}



if ( ! function_exists('is_inst_tutor'))
{
	function is_inst_tutor($tutor_id = "")
	{
		if(!($tutor_id > 0))
			return FALSE;

		$CI	=&	get_instance();
		$rs = $CI->db->select('parent_id')->get_where($CI->db->dbprefix('users'), array('id' => $tutor_id));
 
		return ($rs->num_rows() > 0) ? $rs->row()->parent_id : FALSE;
	}
}




if ( ! function_exists('update_user_credits'))
{
	function update_user_credits($user_id = "", $credits = "", $action = "")
	{
		if(!($user_id > 0) || !($credits > 0) || !in_array($action, array('credit', 'debit')))
			return NULL;

		$CI	=&	get_instance();
		$CI->load->model('home_model');
		return $CI->home_model->update_user_credits($user_id, $credits, $action);
	}
}



if ( ! function_exists('is_uploaded_certificates'))
{
	function is_uploaded_certificates($user_id = "")
	{
		if(!($user_id > 0))
			return FALSE;

		$CI	=&	get_instance();
		$CI->load->model('home_model');
		return $CI->home_model->is_uploaded_certificates($user_id);
	}
}


if ( ! function_exists('get_lessons_taught_cnt'))
{
	function get_lessons_taught_cnt()
	{

		$CI	=&	get_instance();
		$bookings_cnt = $CI->db->query("SELECT sum(if(status = 'closed', 1, 0)) as bookings_cnt FROM ".TBL_BOOKINGS." ")->row()->bookings_cnt;
		$enrollments_cnt = $CI->db->query("SELECT sum(if(status = 'closed', 1, 0)) as enrollments_cnt FROM ".TBL_INST_ENROLLED_STUDENTS." ")->row()->enrollments_cnt;

		return ($bookings_cnt+$enrollments_cnt);
	}
}


if ( ! function_exists('clean_string'))
{
	function clean_string($string = "")
	{
		if(empty($string))
			return "";

		$string = strtolower($string);
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   		$string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

		return $string;
	}
}


if ( ! function_exists('prepare_slug'))
{
	function prepare_slug($string = "", $column_to_be_checked = "", $table_name = "")
	{
		if(empty($string) || empty($column_to_be_checked) || empty($table_name))
			return "";

		$string = clean_string($string);

		$CI	=&	get_instance();
		$duplicate_rec_cnt = $CI->db->where($column_to_be_checked, $string)->count_all_results($table_name);

		if($duplicate_rec_cnt > 0) {
			$string .= '-'.$duplicate_rec_cnt;
		}

		return $string;
	}
}

if ( ! function_exists( 'safe_redirect')) {
	function safe_redirect( $user_id ) {
		$user_type = getUserType($user_id);
		if($user_type == "institute")
			redirect(URL_INSTITUTE_SUBSCRIPTIONS, 'refresh');
		else if($user_type == "tutor")
			redirect(URL_TUTOR_SUBSCRIPTIONS, 'refresh');
		else if($user_type == "student")
			redirect(URL_STUDENT_SUBSCRIPTIONS, 'refresh');
		else
			redirect(URL_AUTH_INDEX);			
	}
}



if ( ! function_exists( 'get_tutor_sellingcourse_info')) {
	function get_tutor_sellingcourse_info( $sc_id = "" ) {

		$tutor_sellingcourse_info = array();

		if(!($sc_id > 0))
			return $tutor_sellingcourse_info;

		$CI	=&	get_instance();

		$query = "SELECT sc.*, t.username, t.slug AS tutor_slug, t.profile, t.photo, t.gender FROM ".TBL_PREFIX."tutor_selling_courses sc INNER JOIN ".TBL_PREFIX."users t ON t.id=sc.tutor_id WHERE sc.sc_id=".$sc_id." ";

		$record = $CI->db->query($query)->result();

		if(!empty($record)) {

			$record[0]->sellingcourse_curriculum = $CI->db->get_where('tutor_selling_courses_curriculum', array('sc_id' => $sc_id))->result();

			$tutor_sellingcourse_info = $record[0];

		}

		return $tutor_sellingcourse_info;

	}
}


if ( ! function_exists('formatSizeUnits'))
{
 	function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
	}
}









?>