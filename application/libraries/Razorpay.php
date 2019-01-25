<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package  	CodeIgniter
 * @author  	Digisamaritan
 * @copyright 	Copyright (c) 2017
 * @license  	GLP
 * @since  		Version 1.0
 * @version  	1.0
 */
use Razorpay\Api\Api;

require_once __DIR__.'/razorpay-sdk/Razorpay.php';

 class Razorpay {

    /**
     * Form url for Merchant
     *
     * @var string
     */
    public $form_url = 'https://api.razorpay.com/';
	
	/**
     * Version
     *
     * @var string
     */
    public $version = 'v1';
		
	/**
     * Allow config
     *
     * @var array
     */
    public $config = array();
	
	public $key_id = 'rzp_test_tjwMzd8bqhZkMr';
	
	public $key_secret = 'EWI9VQiMH43p6LDCbpsgvvHZ';
	
	public $total_amount = '0';
	
	public $payment_action = 'capture';
	
	/**
	 * Constructor
	 *
	 * @param	string
	 * @return	void
	 */
	public function __construct( $config = array() )
	{
		$defaults = array(
			'razorpay_key_id' => 'rzp_test_tjwMzd8bqhZkMr', // razorpay_key_id
			'razorpay_key_secret' => 'EWI9VQiMH43p6LDCbpsgvvHZ', // razorpay_key_secret
			'razorpay_payment_action' => 'capture', // razorpay_payment_action
			'razorpay_mode' => 'sandbox', // ShopID
			'total_amount' => 0,
			
			'product_name' => 'Item',
			'firstname' => '',
			'lastname' => '',
			'email' => '',
			'phone' => '',
			
			'success_url' => '',
			'cancel_url' => '',
			'failed_url' => '',
		);
		$this->config = $this->prepare_config( $defaults, $config );
		
		$this->key_id = $this->config['razorpay_key_id'];
		$this->key_secret = $this->config['razorpay_key_secret'];
		$this->order_total = $this->config['total_amount'] * 100;
		$this->payment_action = $this->config['razorpay_payment_action'];
	}

	/**
     * Add purse to allow wallets
     *
     * @param $currency
     * @param $purse
     * @param $secret_key
     *
     * @return bool
     */
	 function prepare_config( $defaults, $config ) {
		 $atts = $config;
		$out = array();
		foreach ($defaults as $name => $default) {
			if ( array_key_exists($name, $config) )
				$out[$name] = $config[$name];
			else
				$out[$name] = $default;
		}
		return $out;
	 }
	 
	 protected function getSessionKey($orderId)
	{
		return "razorpay_order_id.$orderId";
	}
	
		
	/**
	 * Check for valid razorpay server callback
	 **/
	function check_razorpay_response( $params )
	{
		$order_id = isset($params['razorpay_payment_id']) ? $params['razorpay_payment_id'] : '';
		$response = array('status' => 'failed', 'msg' => 'Payment failed');
		if ( isset($params['razorpay_payment_id']) && ! empty( $params['razorpay_payment_id'] ) )
		{
			$razorpay_payment_id = $params['razorpay_payment_id'];
			$key_id = $this->key_id;
			$key_secret = $this->key_secret;
			$amount = $this->order_total;
			$success = false;
			$error = "";
			$api = new Api($key_id, $key_secret);
			$payment = $api->payment->fetch($razorpay_payment_id);
			
			if ( $payment['amount'] == $amount)
			{
				$success = true;
			} 
			
			
			if ($success === true)
			{
				$response = array('status' => 'success', 'msg' => "Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be processing your order soon. Order Id: $order_id");
			}
			else
			{
				$response = array('status' => 'failed', 'msg' => 'Thank you for shopping with us. However, the payment failed.');
			}
		}
		// We don't have a proper order id
		else
		{
			if ($order_id !== null)
			{
				$response = array('status' => 'failed', 'msg' => 'Customer cancelled the payment.');
			}
		}
		return $response;
	}
}
// END Razorpay Class

/* End of file Razorpay.php */
/* Location: ./application/libraries/Razorpay.php */