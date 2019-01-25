<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->library(array('ion_auth','form_validation'));
		
		$group = array('admin', 'tutor', 'student','institute');
		if (!$this->ion_auth->in_group($group)) {
			$this->prepare_flashmessage(get_languageword('MSG_NO_ENTRY'),2);
			redirect(getUserType());
		}
	}
	
	/** Displays the Index Page**/
	function index()
	{
		if(isset($_POST['Submit']))
		{
			$this->form_validation->set_rules('package_id', get_languageword('package'), 'trim|required|xss_clean');
			$this->form_validation->set_rules('gateway_id', get_languageword('Payment gateway'), 'trim|required|xss_clean');			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if ($this->form_validation->run() == TRUE)
			{
				$package_id = $this->input->post('package_id');
				$gateway_id = $this->input->post('gateway_id');
				//print_r($_POST);die();
				$gateway_details = $this->base_model->get_payment_gateways(' AND st2.type_id = '.$gateway_id);
				
				$package_info 	= $this->db->get_where('packages',array('id' => $package_id))->result();
				if(count($gateway_details) > 0 && count($package_info) > 0)
				{
					$field_values = $this->db->get_where('system_settings_fields',array('type_id' => $gateway_id))->result();
					$total_amount 	= $package_info[0]->package_cost;
					if(isset($package_info[0]->discount) && ($package_info[0]->discount != 0))
					{
						if($package_info[0]->discount_type == 'Value')
						{
							$total_amount = $package_info[0]->package_cost - $package_info[0]->discount;				
						}
						else
						{
							$discount = ($package_info[0]->discount/100)*$package_info[0]->package_cost;						
							$total_amount = $package_info[0]->package_cost - $discount;
						}
					}
					$package_name = $package_info[0]->package_name;
					$this->session->set_userdata( 'is_valid_request', 1 );
					$this->session->set_userdata( 'package_id', $package_id );
					$this->session->set_userdata( 'gateway_id', $gateway_id );
					if($gateway_details[0]->type_id == PAYPAL_PAYMENT_GATEWAY ) //Paypal Settings
					{
						$config['return'] 				= base_url().'payment/paypal_success';
						$config['cancel_return'] 		= base_url().'payment/paypal_cancel';
						$config['production'] 	= true;
						$config['currency_code'] 		= 'USD';
						$config['custom'] = 'user_id='.$this->ion_auth->get_user_id().'&package_id='.$package_id;						
						foreach($field_values as $value) {
							if($value->field_key == 'Paypal_Email') {
								$config['business'] = $value->field_output_value;
							}
							if($value->field_key == 'Account_Type' && $value->field_output_value == 'sandbox') {
								$config['production'] = false;
							}
							if($value->field_key == 'Currency_Code') {
								$config['currency_code'] = $value->field_output_value;
							}
							if($value->field_key == 'Header_Logo') {
								$config['cpp_header_image'] = URL_PUBLIC_UPLOADS2.'settings/thumbs/'.$value->field_output_value;
							}
						}						

						$this->load->library('paypal', $config);
						$this->paypal->__initialize($config);
						$this->paypal->add($package_name, $total_amount);
						$this->paypal->pay(); /*Process the payment*/
					}
					elseif($gateway_details[0]->type_id == PAYU_PAYMENT_GATEWAY ) //Payu Settings
					{
						$payuparams = array();
						$MERCHANT_KEY = $SALT = $account_type = '';
						$PAYU_BASE_URL = 'https://test.payu.in';
						foreach($field_values as $value) {
							if($value->field_key == 'Account_TypeLIveSandbox') {
								$account_type = $value->field_output_value;
							}
						}
						foreach($field_values as $value) {						
							
							if($account_type == 'Sandbox')
							{
								if($value->field_key == 'Sandbox_Merchant_Key') {
									$payuparams['key'] = $value->field_output_value;
								}
								if($value->field_key == 'Sandbox_Salt') {
									$payuparams['salt'] = $value->field_output_value;
								}
								if($value->field_key == 'Test_URL') {
									$payuparams['action'] = $value->field_output_value;
								}								
							}
							else
							{
								if($value->field_key == 'Live_Merchant_Key') {
									$payuparams['key'] = $value->field_output_value;
								}
								if($value->field_key == 'Live_Salt') {
									$payuparams['salt'] = $value->field_output_value;
								}
								if($value->field_key == 'Live_URL') {
									$payuparams['action'] = $value->field_output_value;
								}
							}
						}
						$payuparams['surl'] = base_url() . 'payment/payu_success';
						$payuparams['furl'] = base_url() . 'payment/payu_cancel';
						
						$payuparams['udf1'] = $this->ion_auth->get_user_id();
						$payuparams['udf2'] = $package_id;
						
						$payuparams['service_provider'] = 'payu_paisa';
						$payuparams['productinfo'] = $package_name;
						$payuparams['amount'] = $total_amount;
						
						$user_details = $this->base_model->fetch_records_from('users', array('id' => $this->ion_auth->get_user_id()));
						if(!empty($user_details))
						{
							$payuparams['firstname'] = $user_details[0]->first_name;
							$payuparams['lastname'] = $user_details[0]->last_name;
							$payuparams['email'] = $user_details[0]->email;
							$payuparams['phone'] = $user_details[0]->phone;
						}						
						$this->load->helper('payu');					
						echo call_payu( $payuparams );
						die();
					} elseif ( $gateway_details[0]->type_id == TWOCHECKOUT_PAYMENT_GATEWAY ) {
						$this->load->helper('2check-payment');
						$config = array();
						$url = 'https://www.2checkout.com/checkout/purchase';
						foreach($field_values as $value) {
							if($value->field_key == '2check_is_demo') {
								if ( strip_tags( trim( $value->field_output_value ) ) == 'yes' ) {
									$url = 'https://sandbox.2checkout.com/checkout/purchase';
									$config['demo'] =	'Y';
								}
							}
							if($value->field_key == '2check_seller_id') {
									$config['sid'] = $value->field_output_value;
							}							
						}
						$config['mode'] = '2CO';
						$config['currency_code'] = get_system_settings('Currency_Code');
						$config['li_0_type'] 	= 'product'; // Always Lower Case, ‘product’, ‘shipping’, ‘tax’ or ‘coupon’, defaults to ‘product’
						$config['li_0_name'] 	= $package_name;
						$config['li_0_price'] 	= $total_amount;
						$config['li_0_quantity'] = 1;
						$config['li_0_tangible'] = 'N'; // If( is_virtual || is_downloadable ) Then it is 'Y'
						
						$user_details = $this->base_model->fetch_records_from('users', array('id' => $this->ion_auth->get_user_id()));
						if(!empty($user_details))
						{
							$config['first_name'] = $user_details[0]->first_name;
							$config['last_name'] = $user_details[0]->last_name;
							$config['street_address'] = $user_details[0]->land_mark;
							$config['street_address2'] = $user_details[0]->land_mark;
							$config['city'] = $user_details[0]->city;
							$config['state'] = '';
							$config['zip'] = $user_details[0]->pin_code;
							$config['country'] = $user_details[0]->country;
							$config['email'] = $user_details[0]->email;
							$config['phone'] = $user_details[0]->phone;
						}
						$config['return_url']			= base_url() . 'payment/twocheckout_status';
						$config['x_receipt_link_url'] 	= base_url() . 'payment/twocheckout_status';
						twocheck_redirect( $url, $config );
					} elseif( $gateway_details[0]->type_id == TPAY_PAYMENT_GATEWAY ) {
						$this->insert_transaction( 'tpay' );
						$config = array();
						foreach($field_values as $value) {
							$config[ $value->field_key ] = $value->field_output_value;						
						}
						$config['success_url'] = base_url() . 'payment/tpay_status';
						$config['result_url'] = base_url() . 'payment/tpay_ipn';
						$config['total_amount'] = $total_amount; // Total amount
						$user_details = $this->base_model->fetch_records_from('users', array('id' => $this->ion_auth->get_user_id()));
						if( ! empty( $user_details ) )
						{
							$config['full_name'] = $user_details[0]->first_name . ' ' . $user_details[0]->last_name;
							$config['first_name'] = $user_details[0]->first_name;
							$config['address'] = $user_details[0]->land_mark;
							$config['city'] = $user_details[0]->city;							
							$config['zip'] = $user_details[0]->pin_code;
							$config['country'] = $user_details[0]->country;
							$config['email'] = $user_details[0]->email;
							$config['phone'] = $user_details[0]->phone;
						}						
						$this->load->helper('tpay');					
						echo transferuj_process_payment( $config );
						die();
					} elseif( $gateway_details[0]->type_id == PAGSEGURO_PAYMENT_GATEWAY ) {
						$payment = $this->insert_transaction( 'pagseguro' );
						$config = array();
						$pagseguro_mode = 'sandbox';
						foreach($field_values as $value) {
							if ( $value->field_key == 'pagseguro_mode' ) {
								$pagseguro_mode = $value->field_output_value;
							}							
						}						
						foreach($field_values as $value) {
							if ( $pagseguro_mode == 'sandbox' ) {
								if ( $value->field_key == 'pagseguro_sandbox_email' ) {
									$config['email'] = $value->field_output_value;
								}
								if ( $value->field_key == 'pagseguro_sandbox_token' ) {
									$config['token'] = $value->field_output_value;
								}
							} else {
								if ( $value->field_key == 'pagseguro_email' ) {
									$config['email'] = $value->field_output_value;
								}
								if ( $value->field_key == 'pagseguro_token' ) {
									$config['token'] = $value->field_output_value;
								}
							}
						}
						$this->load->library( 'pagseguro', $config );
						// create payment request
						$paymentRequest = new PagSeguroPaymentRequest();
						
						// sets the currency
						$paymentRequest->setCurrency( 'BRL' );
						
						// payment request details
						$paymentRequest->addItem( '01', $package_name, '1', strval( $total_amount ) );
						
						// sets the reference code for this request
						$paymentRequest->setReference( $payment );

						$user_details = $this->base_model->fetch_records_from('users', array('id' => $this->ion_auth->get_user_id()));
						if( ! empty( $user_details ) )
						{
						// sets customer information
						$paymentRequest->setSender( $user_details[0]->first_name . ' ' . $user_details[0]->last_name, $user_details[0]->email );
						}
						// redirect url
						$paymentRequest->setRedirectUrl( base_url() . '/payment/pagseguro_status' );

						// IPN URL
						$paymentRequest->addParameter( 'notificationURL', base_url() );
						
						/* TRY CHECKOUT */
						try {

							// generate credentials
							$credentials = new PagSeguroAccountCredentials( $config['email'], $config['token'] );

							// register this payment request in PagSeguro, to obtain the payment URL for redirect your customer
							$checkout_uri = $paymentRequest->register( $config );

							if ( gettype( $checkout_uri ) != 'string' ) {
								throw new exception( $checkout_uri );
							}
							// send the user to PagSeguro
							redirect( $checkout_uri );
							die();

						} catch ( Exception $e ) {
							
							//catch exception
							sendEmail( 'adiyya@gmail.com', 'adiyya@gmail', 'Pagseguro Payment Status', $e->getMessage() );							
						}
						
					} elseif( $gateway_details[0]->type_id == WEBMONEY_PAYMENT_GATEWAY ) {
						$config = array();
						foreach($field_values as $value) {
							$config[ $value->field_key ] = $value->field_output_value;							
						}
						$config['product_name'] = $package_name;
						
						//$config['product_id'] = 0;
						$user_details = $this->base_model->fetch_records_from('users', array('id' => $this->ion_auth->get_user_id()));
						if( ! empty( $user_details ) )
						{
							$config['email'] = $user_details[0]->email;
						}						
						$config['total_amount'] = $total_amount;
						$config['currency'] = get_system_settings('Currency_Code');
						
						$config['success_url'] = base_url() . 'payment/webmoney_success';
						$config['result_url'] = base_url() . 'payment/webmoney_result';
						$config['cancel_url'] = base_url() . 'payment/webmoney_success';
						
						$this->load->library( 'webmoney', $config );
						$this->webmoney->submit_form();
						die();
					} elseif( $gateway_details[0]->type_id == YANDEX_PAYMENT_GATEWAY ) {
						$config = array();
						foreach($field_values as $value) {
							if ( $value->field_key == 'ym_ShopID' ) {
								$config['ShopID'] = $value->field_output_value;							
							}
							if ( $value->field_key == 'ym_Scid' ) {
								$config['scid'] = $value->field_output_value;							
							}
							if ( $value->field_key == 'ym_mode' ) {
								$config['ym_mode'] = $value->field_output_value;							
							}
						}
						
						$user_details = $this->base_model->fetch_records_from('users', array('id' => $this->ion_auth->get_user_id()));
						if( ! empty( $user_details ) )
						{
							$config['firstname'] = $user_details[0]->first_name;
							$config['lastname'] = $user_details[0]->last_name;
							$config['cps_email'] = $user_details[0]->email;
							$config['cps_phone'] = $user_details[0]->phone;
						}						
						$config['Sum'] = $total_amount;
												
						$config['shopSuccessUrl'] = base_url() . 'payment/yandex_success';
						$config['shopFailUrl'] = base_url() . 'payment/yandex_fail';						
						$this->load->library( 'yandex', $config );
						$this->yandex->submit_form();
						die();
					} elseif( $gateway_details[0]->type_id == PAYZA_PAYMENT_GATEWAY ) {
						$config = array();
						foreach($field_values as $value) {
							if ( $value->field_key == 'payza_email' ) {
								$config['ap_merchant'] = $value->field_output_value;							
							}
							if ( $value->field_key == 'payza_mode' ) {
								$config['ap_mode'] = $value->field_output_value;							
							}
						}												
						$config['ap_returnurl'] = base_url() . 'payment/payza_success';
						$config['ap_cancelurl'] = base_url() . 'payment/payza_fail';
						$config['ap_alerturl'] = base_url() . 'payment/payza_ipn';

						$cart_details = array();
						$cart_details[] = array(
							'name' => $package_name,
							'quantity' => 1,
							'subtotal' => $total_amount,
							'discount' => 0,
						);						
						$this->load->library( 'payza', $config );
						$this->payza->pay($this->ion_auth->get_user_id(), $package_id, $cart_details );
						die();
					} elseif( $gateway_details[0]->type_id == RAZORPAY_PAYMENT_GATEWAY ) {
						$url = base_url() . 'student/paywith_razorpay/package/'.$package_id.'/gateway/'.RAZORPAY_PAYMENT_GATEWAY;
						redirect( $url );
					}elseif ( $gateway_details[0]->type_id == MANUAL_TRANSFER ) {
						$this->insert_transaction( 'manual', array('is_valid_request_clear' => 'yes', 'is_redirect' => 'yes') );
					}
				}
				else
				{
					$this->safe_redirect('', 'There are no packages. Please contact administrator');
				}
			}
			else
			{
				$this->prepare_flashmessage(validation_errors(), 1);
				$user_type = getUserType();
				if($user_type == "student")
					$redirect_path = URL_STUDENT_LIST_PACKAGES;
				else if($user_type == "tutor")
					$redirect_path = URL_TUTOR_LIST_PACKAGES;
				else if($user_type == "institute")
					$redirect_path = URL_INSTITUTE_LIST_PACKAGES;
				redirect($redirect_path);
			}
		}
		else
		{
			$this->safe_redirect();
		}
	}
	
	function pagseguro_status() {
		
	}
		
	function issetCheck($post,$key)
	{
		if(isset($post[$key])){
		$return=$post[$key];
		}
		else{
		$return='';
		}
		return $return;
	}
	
	function paypal_success()
	{
		if($this->input->post() && $this->session->userdata('is_valid_request'))
		{
			$custom = array();
			parse_str($this->input->post("custom"), $custom);
			$user_id = $custom['user_id'];
			$package_id = $custom['package_id'];
			if($user_id != '' && $package_id != '')
			{
				$user_details = $this->base_model->get_user_details( $user_id );
				$package_details = $this->base_model->fetch_records_from('packages', array('id' => $package_id));
				if(!empty($user_details) && !empty($package_details))
				{
					$user_info = $user_details[0];
					$subscription_info['user_id'] = $user_id;
					$subscription_info['user_name'] = $user_info->first_name.' '.$user_info->last_name;
					$subscription_info['user_type'] = $user_info->group_name;
					$subscription_info['user_group_id'] = $user_info->group_id;
					
					$subscription_details 	= $package_details[0];
					$subscription_info['package_id'] = $package_id;
					$subscription_info['package_name'] = $subscription_details->package_name;
					$subscription_info['package_cost'] = $subscription_details->package_cost;

					$subscription_info['discount_type'] = $subscription_details->discount_type;
					$subscription_info['discount_value'] = $subscription_details->discount;
					$discount_amount 	= 0;
					if(isset($subscription_details->discount) && ($subscription_details->discount != 0))
					{
						if($subscription_details->discount_type == 'Value')
						{
							$discount_amount = $subscription_details->discount;				
						}
						else
						{
							$discount_amount = ($subscription_details->package_cost/$subscription_details->discount)/100;
						}
					}
					$subscription_info['discount_amount'] = $discount_amount;
					
					$subscription_info['amount_paid'] = $this->input->post('mc_gross');
					$subscription_info['credits'] 	  = $subscription_details->credits;
					$subscription_info['payment_type'] 		= "paypal";
					$subscription_info['transaction_no']   	= $this->input->post("txn_id");
					$subscription_info['payment_received'] 	= "1";					
					$subscription_info['payer_id'] 			= $this->input->post("payer_id");
					$subscription_info['payer_email'] 		= $this->input->post("payer_email");
					$subscription_info['payer_name'] 		= $this->input->post("first_name") . " " . 
					$this->input->post("last_name");
					$subscription_info['subscribe_date'] 	= date('Y-m-d H:i:s');
					$ref 	= $this->base_model->insert_operation_id($subscription_info, 'subscriptions');
					if($ref > 0)
					{
						$user_data['subscription_id'] 		= $ref;
						$this->base_model->update_operation($user_data, 'users', array('id' => $user_id));

						//Log Credits transaction data & update user net credits - Start
						$log_data = array(
							'user_id' => $user_id,
							'credits' => $subscription_details->credits,
							'per_credit_value' => get_system_settings('per_credit_value'),
							'action'  => 'credited',
							'purpose' => 'Package "'.$subscription_details->package_name.'" subscription',
							'date_of_action	' => date('Y-m-d H:i:s'),
							'reference_table' => 'subscriptions',
							'reference_id' => $ref,
						);
						log_user_credits_transaction($log_data);

						update_user_credits($user_id, $subscription_details->credits, 'credit');
						//Log Credits transaction data & update user net credits - End
					}
					$this->prepare_flashmessage(get_languageword('Payment success Transaction Id '). ": <strong>" . 
					$subscription_info['transaction_no'] . "</strong>", 0);
					$this->session->unset_userdata('is_valid_request');
					$this->session->unset_userdata('package_id');
					$this->session->unset_userdata('gateway_id');
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
		}
		else
		{
			$this->safe_redirect('', get_languageword('Wrong operation'), FALSE);
		}
	}
	
	function paypal_cancel()
	{
		$this->safe_redirect('', get_languageword('You have cancelled your transaction'), FALSE);
	}
	
	function payu_success()
	{

		if($this->input->post() && $this->session->userdata('is_valid_request'))
		{
			$status_message = '';
			$status=$_POST["status"];
			$firstname=$_POST["firstname"];
			$amount=$_POST["amount"];
			$txnid=$_POST["txnid"];
			$posted_hash=$_POST["hash"];
			$key=$_POST["key"];
			$productinfo=$_POST["productinfo"];
			$email=$_POST["email"];
			
			$user_id = $_POST['udf1'];
			$package_id = $_POST['udf2'];
			
			$salt = '';
			$field_values = $this->db->get_where('system_settings_fields',array('type_id' => 27))->result();
			$account_type = '';
			foreach($field_values as $value) {
				if($value->field_key == 'Account_TypeLIveSandbox') {
					$account_type = $value->field_output_value;
				}
			}
			foreach($field_values as $index => $value) {
				if($account_type == 'Sandbox')
				{
					if($value->field_key == 'Sandbox_Salt') {
						$salt = $value->field_output_value;
					}
				}
				else
				{
					if($value->field_key == 'Live_Salt') {
						$salt = $value->field_output_value;
					}
				}				
			}
			
			if (isset($_POST["additionalCharges"])) {
			$additionalCharges=$_POST["additionalCharges"];
			$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||'.$package_id.'|'.$user_id.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

			}
			else {
			$retHashSeq = $salt.'|'.$status.'|||||||||'.$package_id.'|'.$user_id.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
			}
			$hash = hash("sha512", $retHashSeq);

			if ($hash != $posted_hash) {
			   $status_message = get_languageword("Invalid Transaction Please try again");
			} 
			else
			{
				if($status == 'success')
				{
					$user_details = $this->base_model->get_user_details( $user_id );
					$user_info = $user_details[0];
					$subscription_info['user_id'] = $user_id;
					$subscription_info['user_name'] = $user_info->first_name.' '.$user_info->last_name;
					$subscription_info['user_type'] = $user_info->group_name;
					$subscription_info['user_group_id'] = $user_info->group_id;
					
					$package_details = $this->base_model->fetch_records_from('packages', array('id' => $package_id));
					$subscription_details 	= $package_details[0];
					$subscription_info['package_id'] = $package_id;
					$subscription_info['package_name'] = $subscription_details->package_name;
					$subscription_info['package_cost'] = $subscription_details->package_cost;

					$subscription_info['discount_type'] = $subscription_details->discount_type;
					$subscription_info['discount_value'] = $subscription_details->discount;
					$discount_amount 	= 0;
					if(isset($subscription_details->discount) && ($subscription_details->discount != 0))
					{
						if($subscription_details->discount_type == 'Value')
						{
							$discount_amount = $subscription_details->discount;				
						}
						else
						{
							$discount_amount = ($subscription_details->package_cost/$subscription_details->discount)/100;
						}
					}
					$subscription_info['discount_amount'] = $discount_amount;
					
					$subscription_info['amount_paid'] = $this->input->post('amount');
					$subscription_info['credits'] 	  = $subscription_details->credits;					
					$subscription_info['payment_type'] 		= "payu";
					$subscription_info['transaction_no']   	= $this->input->post("txnid");
					$subscription_info['payment_received'] 	= "1";					
					//$subscription_info['payer_id'] 			= $this->input->post("payer_id");
					$subscription_info['payer_email'] 		= $this->input->post("email");
					$subscription_info['payer_name'] 		= $this->input->post("firstname") . " " . 
					$this->input->post("lastname");
					$subscription_info['subscribe_date'] 	= date('Y-m-d H:i:s');

					$ref 	= $this->base_model->insert_operation_id($subscription_info, 'subscriptions');
					if($ref > 0)
					{
						$user_data['subscription_id'] 		= $ref;
						$this->base_model->update_operation($user_data, 'users', array('id' => $user_id));

						//Log Credits transaction data & update user net credits - Start
						$log_data = array(
							'user_id' => $user_id,
							'credits' => $subscription_details->credits,
							'per_credit_value' => get_system_settings('per_credit_value'),
							'action'  => 'credited',
							'purpose' => 'Package "'.$subscription_details->package_name.'" subscription',
							'date_of_action	' => date('Y-m-d H:i:s'),
							'reference_table' => 'subscriptions',
							'reference_id' => $ref,
						);
						log_user_credits_transaction($log_data);

						update_user_credits($user_id, $subscription_details->credits, 'credit');
						//Log Credits transaction data & update user net credits - End
					}

					$this->prepare_flashmessage(get_languageword('Payment success Transaction Id '). ": <strong>" . 
					$subscription_info['transaction_no'] . "</strong>", 0);
					$this->session->unset_userdata('is_valid_request');
					$this->session->unset_userdata('package_id');
					$this->session->unset_userdata('gateway_id');
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
				else
				{
					$this->safe_redirect('', get_languageword('Transaction Failed Please try again'), FALSE);
				}
			}	
		}
		else
		{
			$this->safe_redirect('', get_languageword('Wrong operation'), FALSE);
		}
	}
	
	function payu_cancel()
	{
		$this->safe_redirect('', get_languageword('You have cancelled your transaction'), FALSE);
	}
	
	function process_stripe() {
		$user_id = $this->ion_auth->get_user_id();

		if ( isset( $_POST['stripeToken'] ) && $user_id != '' ) {
			$package_id = $this->uri->segment(3);
			$gateway_id = STRIPE_PAYMENT_GATEWAY;
			$token  = $_POST['stripeToken'];
			
			$gateway_details = $this->base_model->get_payment_gateways(' AND st2.type_id = '.$gateway_id);
			$package_details 	= $this->db->get_where('packages',array('id' => $package_id))->result();
			$user_details = $this->base_model->get_user_details( $user_id );
			if(count($gateway_details) > 0 && count($package_details) > 0 && ! empty( $user_details )) {
				$field_values = $this->db->get_where('system_settings_fields',array('type_id' => $gateway_id))->result();
				$total_amount 	= $package_details[0]->package_cost;
				$stripeEmail = $_POST['stripeEmail'];
				$config = array('stripe_test_mode' => 'yes', 'stripe_key_test_secret' => 'sk_test_FHxf1NsgaWbAFAGny5zJELqU', 'stripe_key_live_secret' => 'pk_live_wPo6I0iKgXrs9mrk08cfwzc4', 'stripe_verify_ssl' => TRUE); // Default Values
				foreach($field_values as $value) {
					$config[ $value->field_key ] = $value->field_output_value;
				}				
				$this->load->library( 'stripe', $config );
				$customer = $this->stripe->customer_create( $token, $stripeEmail );
				$customer = json_decode( $customer ); // We are receiving data in JSON format so we need to decode it!
				
				if ( isset( $customer->error ) ) {
					$this->prepare_flashmessage(get_languageword('Payment failed : '). ": <strong>" . $customer->error->message . "</strong>", 1);
				} else {
					$charge = $this->stripe->charge_customer( $total_amount, $customer->id, $package_details[0]->package_name, get_system_settings('Currency_Code') ); // $amount, $customer_id, $desc
					
					$user_info = $user_details[0];
					$subscription_info['user_id'] = $user_id;
					$subscription_info['user_name'] = $user_info->first_name.' '.$user_info->last_name;
					$subscription_info['user_type'] = $user_info->group_name;
					$subscription_info['user_group_id'] = $user_info->group_id;
					
					$subscription_details 	= $package_details[0];
					$subscription_info['package_id'] = $package_id;
					$subscription_info['package_name'] = $subscription_details->package_name;
					$subscription_info['package_cost'] = $subscription_details->package_cost;

					$subscription_info['discount_type'] = $subscription_details->discount_type;
					$subscription_info['discount_value'] = $subscription_details->discount;
					$discount_amount 	= 0;
					if(isset($subscription_details->discount) && ($subscription_details->discount != 0))
					{
						if($subscription_details->discount_type == 'Value')
						{
							$discount_amount = $subscription_details->discount;				
						}
						else
						{
							$discount_amount = ($subscription_details->package_cost/$subscription_details->discount)/100;
						}
					}
					$subscription_info['discount_amount'] = $discount_amount;
					
					$subscription_info['amount_paid'] = $subscription_info['package_cost'];
					$subscription_info['credits'] 	  = $subscription_details->credits;
					$subscription_info['payment_type'] 		= "stripe";
					$subscription_info['transaction_no']   	= $token;
					$subscription_info['payment_received'] 	= "1";					
					$subscription_info['payer_id'] 			= $customer->id;
					$subscription_info['payer_email'] 		= $stripeEmail;
					
					$subscription_info['subscribe_date'] 	= date('Y-m-d H:i:s');
					$ref 	= $this->base_model->insert_operation_id($subscription_info, 'subscriptions');
					if( $ref > 0 ) {
						$user_data['subscription_id'] 		= $ref;
						$this->base_model->update_operation($user_data, 'users', array('id' => $user_id));

						//Log Credits transaction data & update user net credits - Start
						$log_data = array(
							'user_id' => $user_id,
							'credits' => $subscription_details->credits,
							'per_credit_value' => get_system_settings('per_credit_value'),
							'action'  => 'credited',
							'purpose' => 'Package "'.$subscription_details->package_name.'" subscription',
							'date_of_action	' => date('Y-m-d H:i:s'),
							'reference_table' => 'subscriptions',
							'reference_id' => $ref,
						);
						log_user_credits_transaction($log_data);

						update_user_credits($user_id, $subscription_details->credits, 'credit');
						//Log Credits transaction data & update user net credits - End
					}
					$this->prepare_flashmessage(get_languageword('Payment success Transaction Id '). ": <strong>" . 
					$subscription_info['transaction_no'] . "</strong>", 0);
				}
				$this->session->unset_userdata('is_valid_request');
				$this->session->unset_userdata('package_id');
					$this->session->unset_userdata('gateway_id');
				$user_type = getUserType($user_id);
				if($user_type == "institute")
					redirect(URL_INSTITUTE_SUBSCRIPTIONS, 'refresh');
				else if($user_type == "tutor")
					redirect(URL_TUTOR_SUBSCRIPTIONS, 'refresh');
				else if($user_type == "student")
					redirect(URL_STUDENT_SUBSCRIPTIONS, 'refresh');
				else
					redirect(URL_AUTH_INDEX);				
			} else {
				echo 'Bad Request. Not valid';
			}
		} else {
			echo 'Bad Request';
		}
	}
	
	function twocheckout_status() {
		print_r($_POST);die();
		if($this->input->post() && $this->session->userdata('is_valid_request')) {
			
		}
	}
	
	function insert_transaction( $payment_type, $params = array() ) {
			$user_id = $this->ion_auth->get_user_id();
			$package_id = $this->session->userdata( 'package_id');
			$gateway_id = $this->session->userdata( 'gateway_id' );
			
			$user_details = $this->base_model->get_user_details( $user_id );
			$gateway_details = $this->base_model->get_payment_gateways(' AND st2.type_id = '.$gateway_id);
			$package_details 	= $this->db->get_where('packages',array('id' => $package_id))->result();
			if ( ! empty( $user_details ) && ! empty( $gateway_details ) && ! empty( $package_details ) ) {
				$user_info = $user_details[0];
				$subscription_details 	= $package_details[0];
				
				$subscription_info = array();
				// Customer Details.
				$subscription_info['user_id'] = $user_id;
				$subscription_info['user_name'] = $user_info->first_name.' '.$user_info->last_name;
				$subscription_info['user_type'] = $user_info->group_name;
				$subscription_info['user_group_id'] = $user_info->group_id;
				
				// Package Details.
				$subscription_info['package_id'] = $package_id;
				$subscription_info['package_name'] = $subscription_details->package_name;
				$subscription_info['package_cost'] = $subscription_details->package_cost;
				$subscription_info['discount_type'] = $subscription_details->discount_type;
				
				// Discount Details.
				$subscription_info['discount_value'] = $subscription_details->discount;
				$discount_amount 	= 0;
				if(isset($subscription_details->discount) && ($subscription_details->discount != 0))
				{
					if($subscription_details->discount_type == 'Value')
					{
						$discount_amount = $subscription_details->discount;				
					}
					else
					{
						$discount_amount = ($subscription_details->package_cost/$subscription_details->discount)/100;
					}
				}
				$subscription_info['discount_amount'] = $discount_amount;
				
				// Payment Details.				
				$subscription_info['credits'] 	  = $subscription_details->credits;
				$subscription_info['payment_type'] 		= $payment_type;				
				if ( $payment_type == 'manual' ) {
					$subscription_info['amount_paid'] = 0;
					$subscription_info['payment_received'] 	= '0';
					$subscription_info['transaction_no']   	= 'manual';
				} elseif( $payment_type == 'tpay' ) { // For tpay gateway we are inserting data before processing, so amount paid is 0.
					$subscription_info['amount_paid'] = 0;
					$subscription_info['payment_received'] 	= '0';
					$subscription_info['transaction_no']   	= '';
				} elseif( $payment_type == 'razorpay' ) {
					
					if( isset($params['razorpay_payment_action']) && $params['razorpay_payment_action'] == 'authorize' ) {
						$subscription_info['payment_received'] 	= '0';
						$subscription_info['amount_paid'] = 0;
					} else {
						$subscription_info['payment_received'] 	= '1';
						$subscription_info['amount_paid'] = isset($params['amount_paid']) ? $params['amount_paid'] : 0;
					}					
					$subscription_info['transaction_no']   	= isset($params['razorpay_payment_id']) ? $params['razorpay_payment_id'] : '';
				}else {
					$subscription_info['amount_paid'] = $subscription_info['package_cost'];
					$subscription_info['payment_received'] 	= '1';
					$subscription_info['transaction_no']   	= isset( $params['token'] ) ? $params['token'] : '';
				}
				$subscription_info['payer_id'] 			= isset( $params['payer_id'] ) ? $params['payer_id'] : '';
				$subscription_info['payer_email'] 		= isset( $params['payer_email'] ) ? $params['payer_email'] : $user_info->email;
				$subscription_info['subscribe_date'] 	= date('Y-m-d H:i:s');
				$ref 	= $this->base_model->insert_operation_id($subscription_info, 'subscriptions');
				// If the payment is manual then we are not sure whether the payment is received the admin OR not, so we are not updating net credits.
				if( $ref > 0 && $payment_type != 'manual' ) {
					$user_data['subscription_id'] 		= $ref;
					$this->base_model->update_operation($user_data, 'users', array('id' => $user_id));

					// Log Credits transaction data & update user net credits - Start
					$log_data = array(
						'user_id' => $user_id,
						'credits' => $subscription_details->credits,
						'per_credit_value' => get_system_settings('per_credit_value'),
						'action'  => 'credited',
						'purpose' => 'Package "'.$subscription_details->package_name.'" subscription',
						'date_of_action	' => date('Y-m-d H:i:s'),
						'reference_table' => 'subscriptions',
						'reference_id' => $ref,
					);
					log_user_credits_transaction($log_data);
					update_user_credits($user_id, $subscription_details->credits, 'credit');
					// Log Credits transaction data & update user net credits - End
				}
				$this->prepare_flashmessage(get_languageword('Payment success Transaction Id '). ": <strong>" . 
					$subscription_info['transaction_no'] . "</strong>", 0);
				if( $payment_type == 'pagseguro' ) {
					return $ref;
				}
			} else {
				$this->prepare_flashmessage(get_languageword('Failed to insert data. Please contact administrator.'), 1);
			}
			if ( isset( $params['is_valid_request_clear'] ) && $params['is_valid_request_clear'] == 'yes' ) {
				$this->session->unset_userdata('is_valid_request');
				$this->session->unset_userdata('package_id');
				$this->session->unset_userdata('gateway_id');
			}
			
			if ( isset( $params['is_redirect'] ) && $params['is_redirect'] == 'yes' ) {
				safe_redirect( $user_id );
			}
			
	}
	function tpay_status() {
		if($this->session->userdata('is_valid_request')) {
			$this->prepare_flashmessage(get_languageword('Payment success'), 0);
			$user_id = $this->ion_auth->get_user_id();
			safe_redirect( $user_id );
		}
	}
	function tpay_ipn() {			
		$this->base_model->insert_operation_id(array('data' => $_POST), 'tpay_data');			
		$ip_table=array(
			'195.149.229.109',
			'148.251.96.163',
			'178.32.201.77',
			'46.248.167.59',
			'46.29.19.106'
			);
		if ( in_array( $_SERVER['REMOTE_ADDR'], $ip_table ) && ! empty( $_POST ) ) {
			$id_sprzedawcy = $_POST['id'];
			$status_transakcji = $_POST['tr_status'];
			$id_transakcji = $_POST['tr_id'];
			$kwota_transakcji = $_POST['tr_amount'];
			$kwota_zaplacona = $_POST['tr_paid'];
			$data_transakcji = $_POST['tr_date'];
			$opis_transakcji = $_POST['tr_desc'];
			$ciag_pomocniczy = $_POST['tr_crc'];
			$email_klienta = $_POST['tr_email'];
			$suma_kontrolna = $_POST['md5sum'];
			$blad = $_POST['tr_error'];
			$field_values = $this->db->get_where('system_settings_fields',array('type_id' => TPAY_PAYMENT_GATEWAY))->result();
			$config = array();
			foreach($field_values as $value) {
				$config[ $value->field_key ] = $value->field_output_value;						
			}
			$md5=md5($config['transferuj_merchantid'].$id_transakcji.$kwota_transakcji.$ciag_pomocniczy.$config['transferuj_secretpass']);
			if ( $md5 === $suma_kontrolna ) {
				$payment_id = base64_decode($ciag_pomocniczy);
				if ($status_transakcji == 'TRUE' && $blad == 'none') {
					$this->base_model->update_operation( array( 'payment_received' => 1, 'amount_paid' => $kwota_transakcji, 'transaction_no' => $id_transakcji ), 'subscriptions', array( 'id' => $payment_id ) );
				} else {
					$this->base_model->update_operation( array( 'payment_received' => 0, 'transaction_no' => $id_transakcji ), 'subscriptions', array( 'id' => $payment_id ) );
				}
			}
		}
	}
	
	function webmoney_success() {
		if ( ! empty( $_POST ) ) {
			print_r($_POST);
		}
	}
	
	function webmoney_result() {
		if ( ! empty( $_POST ) ) {
			$data = array(
				'gateway' => WEBMONEY_PAYMENT_GATEWAY,
				'data' => json_encode( $_POST ),				
				'ip_address' => $this->input->ip_address(),
			);
			$this->load->library('user_agent');
			$data['browser'] = $this->agent->browser();
			$this->base_model->insert_transaction($data, 'payments_data');
		}
	}
	
	function yandex_success() {
		if ( ! empty( $_POST ) ) {
			print_r($_POST);
		}
	}
	
	function yandex_fail() {
		if ( ! empty( $_POST ) ) {
			print_r($_POST);
		}
	}
	
	function payza_success() {
		if ( ! empty( $_POST ) ) {
			print_r($_POST);
		}
	}
	
	function payza_fail() {
		if ( ! empty( $_POST ) ) {
			print_r($_POST);
		}
	}
	
	function payza_ipn() {
		if ( ! empty( $_POST ) ) {
			$data = array(
				'gateway' => PAYZA_PAYMENT_GATEWAY,
				'data' => json_encode( $_POST ),				
				'ip_address' => $this->input->ip_address(),
			);
			$this->load->library('user_agent');
			$data['browser'] = $this->agent->browser();
			$this->base_model->insert_transaction($data, 'payments_data');
		}
	}
	
	function get_additional_content() {
		if ( $this->input->is_ajax_request() ) {
			$package_id = $_POST['package_id'];
			$final_cost = $_POST['final_cost'];
			$name = $_POST['name'];
			$content = '';
			$gateway_id = STRIPE_PAYMENT_GATEWAY;
			if ( $gateway_id == 41 ) { // Stripe
				$user_info = $this->base_model->get_user_details( $this->ion_auth->get_user_id() );
				$field_values = $this->db->get_where('system_settings_fields',array('type_id' => $gateway_id))->result();
				$key = 'pk_test_H8R3tFH4RiyF0VGzTcXwl8NF';
				$stripe_test_mode = FALSE;
				foreach($field_values as $value) {
					if($value->field_key == 'stripe_test_mode') {
						if ( strip_tags( trim( $value->field_output_value ) ) == 'yes' ) {
							$stripe_test_mode = TRUE;
						}
					}
				}
				foreach($field_values as $value) {
					if( $value->field_key == 'stripe_key_test_publishable' && $stripe_test_mode == TRUE ) {
						$key = $value->field_output_value;
					} elseif ( $value->field_key == 'stripe_key_live_publishable' && $stripe_test_mode == FALSE ) {
						$key = $value->field_output_value;
					}
				}
				ob_start();
				?>
				<form action="<?php echo site_url('payment/process_stripe/'.$package_id);?>" method="POST" id="frm_stripe">
				  <script
					src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button" id="stripe_script"
					data-key="<?php echo $key;?>"
					data-amount="<?php echo $final_cost;?>"
					data-name="<?php echo $name;?>"
					data-description="<?php echo $name;?>"
					data-image="<?php  if(isset($this->config->item('site_settings')->logo) && $this->config->item('site_settings')->logo != '') echo substr(URL_PUBLIC_UPLOADS_SETTINGS.''.$this->config->item('site_settings')->logo, 5); else echo substr(URL_FRONT_IMAGES.'Logo.png', 5);?>"
					data-package_id="<?php echo $package_id;?>"
					data-currency="<?php echo get_system_settings('Currency_Code');?>"
					data-email="<?php echo $user_info[0]->email;?>"
					data-locale="auto"
					>
				  </script>
				</form>
				<?php
				$content = ob_get_clean();
			}
			echo $content;
		}
	}
		
	function razorpay_failed() {
		print_r($_POST);die();
		if ( ! empty( $_POST ) ) {
			$data = array(
				'gateway' => RAZORPAY_PAYMENT_GATEWAY,
				'data' => json_encode( $_POST ),				
				'ip_address' => $this->input->ip_address(),
			);
			$this->load->library('user_agent');
			$data['browser'] = $this->agent->browser();
			$this->base_model->insert_operation_id($data, 'payments_data');
		}
	}
	
	function razorpay_success() {
		
		if ( ! empty( $_POST ) ) {
			$data = array(
				'gateway' => RAZORPAY_PAYMENT_GATEWAY,
				'data' => json_encode( $_POST ),				
				'ip_address' => $this->input->ip_address(),
			);
			$this->load->library('user_agent');
			$data['browser'] = $this->agent->browser();
			$this->base_model->insert_operation_id($data, 'payments_data');
			
			if ( ! empty( $_POST['razorpay_payment_id'] ) ) {
				$package_id = $this->session->userdata( 'package_id');
				$gateway_id = $this->session->userdata( 'gateway_id' );
				$user_id = $this->ion_auth->get_user_id();
				
				$user_details = $this->base_model->get_user_details( $user_id );
				$package_info 	= $this->db->get_where('packages',array('id' => $package_id))->result();
				$field_values = $this->db->get_where('system_settings_fields',array('type_id' => $gateway_id))->result();
				foreach($field_values as $value) {
					if( $value->field_key == 'razorpay_key_id' ) {
						$razorpay_key_id = $value->field_output_value;
					}
					if( $value->field_key == 'razorpay_key_secret' ) {
						$razorpay_key_secret = $value->field_output_value;
					}
					if( $value->field_key == 'razorpay_payment_action' ) {
						$razorpay_payment_action = $value->field_output_value;
					}
					if( $value->field_key == 'razorpay_mode' ) {
						$razorpay_mode = $value->field_output_value;
					}
				}
				$total_amount = $package_info[0]->package_cost;
				if(isset($package_info[0]->discount) && ($package_info[0]->discount != 0))
				{
					if($package_info[0]->discount_type == 'Value')
					{
						$total_amount = $package_info[0]->package_cost - $package_info[0]->discount;				
					}
					else
					{
						$discount = ($package_info[0]->discount/100)*$package_info[0]->package_cost;						
						$total_amount = $package_info[0]->package_cost - $discount;
					}
				}
				
				$config = array(
					'razorpay_key_id' => $razorpay_key_id,
					'razorpay_key_secret' => $razorpay_key_secret,
					'razorpay_payment_action' => $razorpay_payment_action,
					'razorpay_mode' => $razorpay_mode,
					'total_amount' => $total_amount,
				);
				$this->load->library('razorpay', $config);
				$razorpay_payment_id = $_POST['razorpay_payment_id'];
				
				$received_params = array('razorpay_payment_id' => $razorpay_payment_id);
				$response = $this->razorpay->check_razorpay_response( $received_params );
				
				if( $response['status'] == 'success' ) {
					$params = $_POST;
					$params['razorpay_payment_action'] = $razorpay_payment_action;
					$params['amount_paid'] = $total_amount;
					$params['is_valid_request_clear'] = 'yes';
					$params['is_redirect'] = 'yes';				
					$this->insert_transaction( 'razorpay', $params );
					die('ffff');
				} else {
					$this->session->unset_userdata('is_valid_request');
					$this->session->unset_userdata('package_id');
					$this->session->unset_userdata('gateway_id');
					
					$this->safe_redirect(site_url('student/list_packages'), 'Payment failed. Please try again');
				}
				
			} else {
				$this->safe_redirect('', 'Wrong operation');
			}
		}
	}
}