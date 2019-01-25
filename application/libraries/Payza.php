<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Payza Payment Gateway
 *
 * @author Abid Omar
 */
class Payza
{
    /**
     * The Payza Merchant Email
     * @var string
     */
    public $merchant_id;

    /**
     * Purchase Type (item, service, subscription...)
     * @var string
     */
    public $purchase_type;

    /**
     * Return and Status URL
     * @var string
     */
    public $return_url;

    /**
     * Cancel URL
     * @var string
     */
    public $cancel_url;

    /**
     * IPN URL
     * @var string
     */
    public $ipn_url;

    /**
     * Sandbox mode for Payza
     * @var int
     */
    public $sandbox = false;

    /**
     * Purchase Description
     * @var string
     */
    public $description = null;

    /**
     * Transaction total amount
     * @var int
     */
    public $amount;

    /**
     * Transaction currency
     * @var string
     */
    public $currency;

    /**
     * Payza real Payment URL
     * @var string
     */
    public $real_api_url = 'https://secure.payza.com/checkout';

    /**
     * Payza sandbox Payment URL
     * @var string
     */
    public $sandbox_api_url = 'https://sandbox.payza.com/sandbox/payprocess.aspx';

    /**
     * Checkout URL
     *
     * @var string
     */
    private $checkout_url;

    /**
     * Saves error info.
     * @var
     */
    public $debug_info;


    /**
     * Creates a new instance of the Payza Class
     *
     * @param string $merchant_id Merchant Email Address
     * @param string $purchase_type Purchase Type (usually item)
     * @param string $currency Currency
     * @param string $return_url Return URL
     * @param string $cancel_url Cancel URL
     * @param string $ipn_url IPN URL
     * @param boolean $sandbox_mode (false)
     * @param string $description Description (null)
     */
    function __construct( $config = array() )
    {
        $defaults = array(
			'ap_merchant' => '',
			'ap_purchasetype' => 'item', // Must be one of the following: item, item-goods, item-auction, service or subscription.
			'ap_itemname' => 'Item',
			'ap_amount' => 0,
			'ap_currency' => 'USD',
			'ap_description' => '',
			'ap_itemcode' => '',
			'ap_quantity' => 1,
			'ap_additionalcharges' => 0,
			'ap_shippingcharges' => 0,
			'ap_taxamount' => 0,
			'ap_discountamount' => 0,
			
			'ap_returnurl' => '',
			'ap_cancelurl' => '',
			'ap_alerturl' => '', // The URL where you would like your IPNs to be sent. It is the URL of the location of your IPN Handler.
			
			'ap_ipnversion' => '1',
			'ap_mode' => 'sandbox',
		);
		
		$config = $this->prepare_config( $defaults, $config );
		// Fill the Class Properties
        $this->merchant_id = $config['ap_merchant'];
        $this->purchase_type = $config['ap_purchasetype'];
        $this->currency = $config['ap_currency'];
        $this->return_url = $config['ap_returnurl'];
        $this->cancel_url = $config['ap_cancelurl'];
        $this->ipn_url = $config['ap_alerturl'];
        if ( $config['ap_mode'] == 'sandbox' ) {
			$this->sandbox = true;	
		}		
        $this->description = $config['ap_description'];

        // Determine Checkout URL
        if (!$this->sandbox) {
            $this->checkout_url = $this->real_api_url;
        } else {
            $this->checkout_url = $this->sandbox_api_url;
        }
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

    /**
     * Creates a new transaction and returns the redirect URL
     *
     * @param integer $user_id
	 * @param integer $package_id
     * @param mixed $cart_details
     * @return string $redirect_url
     */
    public function pay($user_id, $package_id, $cart_details = false)
    {
        // Required Details
        $args = array(
            'ap_merchant' => $this->merchant_id,
            'ap_purchasetype' => $this->purchase_type,
            'ap_currency' => $this->currency,
            'ap_returnurl' => $this->return_url,
            'ap_cancelurl' => $this->cancel_url,
            'apc_1' => $user_id, // User ID
			'apc_2' => $package_id, // Package ID
            'ap_alerturl' => $this->ipn_url, //$this->ipn_url,//$this->ipn_url, // IPN URL
            'ap_ipnversion' => 2 // IPN Version 2.0
        );

        // Cart Details
        if ($cart_details) {
            $cart_details = $this->get_cart_details($cart_details);
            $args = array_merge($cart_details, $args);
        } else {
            $this->debug_info = 'Cart Details empty';
            return false;
        }

        // Build the redirect URL
        $query_str = http_build_query( $args );
        $redirect_url = $this->checkout_url . '?' . $query_str;
		header('LOCATION:'.$redirect_url);
    }

    /**
     * Format the Easy Digital Download Cart details to a format
     * compatible with the Payza payment gateway
     *
     * @param array $cart_details
     * @return array $formatted_cart
     */
    private function get_cart_details($cart_details)
    {
        $formatted_cart = array();
        $start = 1;
        $length = count($cart_details);
		
        for ($i = 0; $i < $length; $i++) {

            $item_amount = round( ( $cart_details[$i]['subtotal'] / $cart_details[$i]['quantity'] ) - ( $cart_details[$i]['discount'] / $cart_details[$i]['quantity'] ), 2 );

            if( $item_amount <= 0 ) {
                $item_amount = 0;
            }
            
            $formatted_cart['ap_itemname_' . $start] = $cart_details[$i]['name'];
            $formatted_cart['ap_quantity_' . $start] = $cart_details[$i]['quantity'];
            $formatted_cart['ap_amount_' . $start] = $item_amount;
            $start++;
        }
        return $formatted_cart;
    }
}

/**
 * IPN Handler
 *
 * @author Abid Omar
 */
class Payza_ipn
{
    /**
     * Currency
     * @var string
     */
    public $currency;

    /**
     * Request time out
     *
     * @var integer
     */
    public $timeout;

    /**
     * SSL Verification
     *
     * @var boolean
     */
    public $sslverify;

    /**
     * Debug Information
     *
     * @var string
     */
    public $debug_info;

    /**
     * Real IPN Handler
     *
     * @var string
     */
    private $real_ipn_handler = 'https://secure.payza.com/ipn2.ashx';

    /**
     * Sandbox IPN Handler
     *
     * @var string
     */
    private $sandbox_ipn_handler = 'https://sandbox.Payza.com/sandbox/IPN2.ashx';

    /**
     * IPN Handler
     *
     * @var string
     */
    private $ipn_handler;

    /**
     * Creates a new instance of the IPN Class
     *
     * @param string $currency
     * @param bool $sandbox_mode
     */
    function __construct($currency = "USD", $sandbox_mode = false)
    {
        // Sandbox Mode
        $this->ipn_handler = $this->real_ipn_handler;
        if ($sandbox_mode) {
            $this->ipn_handler = $this->sandbox_ipn_handler;
        }

        // Currency
        $this->currency = $currency;

        // SSL Verification
        $this->sslverify = false;
    }

    /**
     * Handle IPN request
     *
     * @static
     * @param string $token
     * @return boolean $success
     */
    public function handle_ipn($token)
    {
        $response = $this->retrieve_response($token);
        if (strlen($response) > 0 && $response != 'INVALID TOKEN') {
            // Extract Data
            parse_str($response, $data);
            $user_id = $data['apc_1']; // user_id
			$package_id = $data['apc_2']; // package_id
            $currency = $data['ap_currency']; // Currency
            $status = $data['ap_status']; // Status

            // Retrieve transaction details
			$CI = & get_instance();
			$payment_meta = $CI->db->query( 'SELECT * FROM ' . $CI->db_dbprefix('subscriptions') . ' WHERE id = ' . $transaction_id )->result_array();
			if ( empty( $payment_meta ) ) {
				return false;
			}
            $amount = $payment_meta['amount']; // amount
			
            if ($this->currency != $currency) {
                return false;
            }

            if ($amount != $data['ap_totalamount']) {
                return false;
            }

            if ($status != 'Success') {
                return false;
            }

			// Let us insert subscription
			$subscription_info = array();
			$user_details = $CI->base_model->get_user_details( $user_id );
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
			
			$subscription_info['amount_paid'] = $amount;
			$subscription_info['credits'] 	  = $subscription_details->credits;					
			$subscription_info['payment_type'] 		= "payza";
			$subscription_info['transaction_no']   	= $data['ap_referencenumber'];
			$subscription_info['payment_received'] 	= "1";					
			//$subscription_info['payer_id'] 			= $this->input->post("payer_id");
			$subscription_info['payer_email'] 		= $user_info->email;;
			$subscription_info['payer_name'] 		= $subscription_info['user_name'];
			$subscription_info['subscribe_date'] 	= date('Y-m-d H:i:s');
			$ref = $CI->base_model->insert_operation_id($subscription_info, 'subscriptions');
			
			if( $ref > 0 ) {
				$user_data['subscription_id'] 		= $ref;
				$CI->base_model->update_operation($user_data, 'users', array('id' => $user_id));

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
            return $transaction_id; // Payment Successful!
        }
        return false;
    }

    /**
     * Retrieve the transaction details
     *
     * @static
     * @param string $token Transaction Token
     * @return string $response Transaction details
     */
    private function retrieve_response($token)
    {
		$response = '';
		// send the URL encoded TOKEN string to the Payza's IPN handler
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->ipn_handler);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// $response holds the response string from the Payza's IPN.
		$response = curl_exec($ch);

		curl_close($ch);
		
		if($response != FALSE)
		{
			if(urldecode($response) == "INVALID TOKEN")
			{
				// the token is not valid
			}
			else
			{
				// urldecode the received response from Payza's IPN V2
				$response = urldecode($response);
			}
		}
		else
		{
			// something is wrong, no response is received from Payza
		}
		
        // Request succeeded, return the Session ID
        return $response;
    }
}