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
class Webmoney {

    /**
     * Current currency
     *
     * @var string
     */
    public $currency;

    /**
     * All support currency
     *
     * @var array
     */
    public $currency_all = array('RUB', 'EUR', 'USD', 'UAH');

    /**
     * Current purse
     *
     * @var string (12)
     */
    public $purse;

    /**
     * Secret key for current purse
     *
     * @var string
     */
    public $secret_key;

    /**
     * Allow wallets
     *
     * @var array
     */
    public $purse_all = array();

    /**
     * Unique payment id
     *
     * @var string
     */
    public $id = '';

    /**
     * Form url for Merchant
     *
     * @var string
     */
    public $form_url = 'https://merchant.webmoney.ru/lmi/payment.asp';
		
	/**
     * Allow config
     *
     * @var array
     */
    public $config = array();
	
	/**
	 * Constructor
	 *
	 * @param	string
	 * @return	void
	 */
	public function __construct( $config = array() )
	{
		$defaults = array(
			'product_name' => 'Product', // LMI_PAYMENT_DESC
			'product_id' => 0, // LMI_PAYMENT_NO
			'is_test' => 'yes', // LMI_SIM_MODE (0,1,2)
			'email' => '', // LMI_PAYMER_EMAIL
			'total_amount' => 0, // LMI_PAYMENT_AMOUNT
			
			'currency' => 'RUB',
			
			'purse_wmr' => 'R',
			'purse_wmr_secret' => '',
			
			'purse_wme' => 'E',
			'purse_wme_secret' => '',
			
			'purse_wmz' => 'Z',
			'purse_wmz_secret' => '',
			
			'purse_wmu' => 'U',
			'purse_wmu_secret' => '',
			
			'success_url' => '',
			'result_url' => '',
			'cancel_url' => '',
		);
		
		$this->config = $this->prepare_config( $defaults, $config );
		
		/**
         * Get currency
         */
        $this->currency = isset( $this->config['currency'] ) ? $this->config['currency'] : 'RUB';

        /**
         * Set WMR
         */
        if( $this->get_option('purse_wmr_secret') !== '' )
        {
            $this->add_purse('RUB', $this->get_option('purse_wmr'), $this->get_option('purse_wmr_secret') );
        }

        /**
         * Set WME
         */
        if( $this->get_option('purse_wme_secret') !== '' )
        {
            $this->add_purse('EUR', $this->get_option('purse_wme'), $this->get_option('purse_wme_secret'));
        }

        /**
         * Set WMZ
         */
        if($this->get_option('purse_wmz_secret') !== '')
        {
            $this->add_purse('USD', $this->get_option('purse_wmz'), $this->get_option('purse_wmz_secret'));
        }

        /**
         * Set WMU
         */
        if($this->get_option('purse_wmu_secret') !== '')
        {
            $this->add_purse('UAH', $this->get_option('purse_wmu'), $this->get_option('purse_wmu_secret'));
        }
		
        /**
         * Select purse
         */
        $data_wallet = $this->get_purse_from_currency($this->currency);		
        $this->purse = $data_wallet['purse'];
		$this->secret_key = $data_wallet['secret_key'];
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
     * Add purse to allow wallets
     *
     * @param $currency
     * @param $purse
     * @param $secret_key
     *
     * @return bool
     */
    public function add_purse( $currency, $purse, $secret_key )
    {
		/**
         * Validate purse
         */
        if(!$this->validate_purse($purse))
        {
           return false;
        }
        else
        {
            /**
             * Add purse to buffer
             */
            $this->purse_all[$currency] = array
            (
                'purse' => $purse,

                'secret_key' => $secret_key
            );

            return true;
        }
    }
	
	/**
     * Validate purse
     *
     * @param $purse
     *
     * @return bool
     */
    public function validate_purse( $purse )
    {
		if(!preg_match('#^[ZREU]$#i', trim($purse)))
        {
            return false;
        }

        return true;
    }
	
	/**
     * Return the values of array element
     *
     * @param $purse
     *
     * @return bool
     */
    public function get_option( $key )
    {
        $val = isset($this->config[ $key ]) ? $this->config[ $key ] : '';

        return $val;
    }
	
	/**
     * @param $currency
     * @return mixed
     */
    public function get_purse_from_currency($currency)
    {
        if(array_key_exists($currency, $this->purse_all))
        {
            return $this->purse_all[$currency];
        }

        return false;
    }
	
	/**
     * Check if this gateway is enabled and available in the user's country
     */
    public function is_valid_for_use()
    {
        $return = true;

        /**
         * Check allow currency
         */
        if (!in_array($this->currency, $this->currency_all, false))
        {
            $return = false;
        }
        return $return;
    }
	
	/**
     * Check instant payment notification
     **/
    public function check_ipn()
    {
        /**
         * Insert $_REQUEST into debug mode
         */
        $this->base_model->insert_operation(array('data' => print_r($_REQUEST,true)), 'tpay_date');

        
            /**
             * Order id
             */
            $LMI_PAYMENT_NO = 0;
            if(array_key_exists('LMI_PAYMENT_NO', $_POST))
            {
                $LMI_PAYMENT_NO = $_POST['LMI_PAYMENT_NO'];
            }
			$this->id = $LMI_PAYMENT_NO;

            /**
             * Sum
             */
            $LMI_PAYMENT_AMOUNT = 0;
            if(array_key_exists('LMI_PAYMENT_AMOUNT', $_POST))
            {
                $LMI_PAYMENT_AMOUNT = $_POST['LMI_PAYMENT_AMOUNT'];
            }

            /**
             * Pre request flag
             */
            $LMI_PREREQUEST = 0;
            if(array_key_exists('LMI_PREREQUEST', $_POST))
            {
                $LMI_PREREQUEST = (int) $_POST['LMI_PREREQUEST'];
            }

            /**
             * Purse merchant
             */
            $LMI_PAYEE_PURSE = '';
            if(array_key_exists('LMI_PAYEE_PURSE', $_POST))
            {
                $LMI_PAYEE_PURSE = $_POST['LMI_PAYEE_PURSE'];
            }

            /**
             * Testing?
             */
            $LMI_MODE = 0;
            if(array_key_exists('LMI_MODE', $_POST))
            {
                $LMI_MODE = (int) $_POST['LMI_MODE'];
            }

            /**
             * Secret key
             */
            $LMI_SECRET_KEY = '';
            if(array_key_exists('LMI_SECRET_KEY', $_POST))
            {
                $LMI_SECRET_KEY = $_POST['LMI_SECRET_KEY'];
            }

            /**
             * Order id from WebMoney Transfer
             */
            $LMI_SYS_INVS_NO = 0;
            if(array_key_exists('LMI_SYS_INVS_NO', $_POST))
            {
                $LMI_SYS_INVS_NO = $_POST['LMI_SYS_INVS_NO'];
            }

            /**
             * Payment id from WebMoney Transfer
             */
            $LMI_SYS_TRANS_NO = 0;
            if(array_key_exists('LMI_SYS_TRANS_NO', $_POST))
            {
                $LMI_SYS_TRANS_NO = $_POST['LMI_SYS_TRANS_NO'];
            }

            /**
             * Payment date and time from WebMoney Transfer
             */
            $LMI_SYS_TRANS_DATE = '';
            if(array_key_exists('LMI_SYS_TRANS_DATE', $_POST))
            {
                $LMI_SYS_TRANS_DATE = $_POST['LMI_SYS_TRANS_DATE'];
            }

            /**
             * Purse client
             */
            $LMI_PAYER_PURSE = '';
            if(array_key_exists('LMI_PAYER_PURSE', $_POST))
            {
                $LMI_PAYER_PURSE = $_POST['LMI_PAYER_PURSE'];
            }

            /**
             * WMID client
             */
            $LMI_PAYER_WM = '';
            if(array_key_exists('LMI_PAYER_WM', $_POST))
            {
                $LMI_PAYER_WM = $_POST['LMI_PAYER_WM'];
            }

            /**
             * WMID Capitaller
             */
            $LMI_CAPITALLER_WMID = '';
            if(array_key_exists('LMI_CAPITALLER_WMID', $_POST))
            {
                $LMI_CAPITALLER_WMID =  $_POST['LMI_CAPITALLER_WMID'];
            }

            /**
             * Email client
             */
            $LMI_PAYMER_EMAIL = '';
            if(array_key_exists('LMI_PAYMER_EMAIL', $_POST))
            {
                $LMI_PAYMER_EMAIL =  $_POST['LMI_PAYMER_EMAIL'];
            }

            /**
             * IP client
             */
            $LMI_PAYER_IP = '';
            if(array_key_exists('LMI_PAYER_IP', $_POST))
            {
                $LMI_PAYER_IP =  $_POST['LMI_PAYER_IP'];
            }

            /**
             * Hash
             */
            $LMI_HASH = '';
            if(array_key_exists('LMI_HASH', $_POST))
            {
                $LMI_HASH = $_POST['LMI_HASH'];
            }

            /**
             * Check purse allow
             */
            $local_purse = $this->get_purse_from_currency($this->currency);
            $this->purse = $local_purse['purse'];
            $this->secret_key = $local_purse['secret_key'];

            /**
             * Local hash
             */
            $local_hash = strtoupper(hash('sha256', $LMI_PAYEE_PURSE.$LMI_PAYMENT_AMOUNT.$LMI_PAYMENT_NO.$LMI_MODE.$LMI_SYS_INVS_NO.$LMI_SYS_TRANS_NO.$LMI_SYS_TRANS_DATE.$this->secret_key.$LMI_PAYER_PURSE.$LMI_PAYER_WM));

            /**
             * Get order object
             */
			$CI = & get_instance();
			$order = $CI->db->query( 'SELECT * FROM ' . $CI->db_dbprefix('subscriptions') . ' WHERE id = ' . $LMI_PAYMENT_NO );

            /**
             * Order not found
             */
            if($order === false && array_key_exists('action', $_GET) && $_GET['action'] !== '')
            {
                /**
                 * Send Service unavailable
                 */
                die( 'Order not found. Payment Error' );
            }

            /**
             * Result
             */
            if ($_GET['action'] === 'result')
            {
                /**
                 * Check pre request
                 */
                if($LMI_PREREQUEST === 1)
                {
                    /**
                     * Add order note
                     */
                    $this->add_order_note(sprintf('Webmoney PRE request success. WMID: %1$s and purse: %2$s and IP: %3$s', $LMI_PAYER_WM, $LMI_PAYER_PURSE, $LMI_PAYER_IP));
                    die('YES');
                }
                else
                {
                    /**
                     * Validated flag
                     */
                    $validate = true;

                    /**
                     * Check hash
                     */
                    if(count($LMI_HASH) > 0 && $local_hash !== $LMI_HASH)
                    {
                        $validate = false;

                        /**
                         * Add order note
                         */
                        $this->add_order_note(sprintf('Validate hash error. Local: %1$s Remote: %2$s', $local_hash, $LMI_HASH));
                    }

                    /**
                     * Check secret key
                     */
                    if($LMI_SECRET_KEY !== '' && $this->secret_key !== $LMI_SECRET_KEY)
                    {
                        $validate = false;

                        /**
                         * Add order note
                         */
                        $this->add_order_note(sprintf('Validate secret key error. Local: %1$s Remote: %2$s', $this->secret_key, $LMI_SECRET_KEY));
                    }

                    /**
                     * Validated
                     */
                    if($validate === true)
                    {                      

                        /**
                         * Check mode
                         */
                        $test = false;
                        if($LMI_MODE === 1)
                        {
                            $test = true;
                        }

                        /**
                         * Testing
                         */
                        if($test)
                        {
                            /**
                             * Add order note
                             */
                            $this->add_order_note(sprintf('Order successfully paid (TEST MODE). WMID: %1$s and purse: %2$s and IP: %3$s', $LMI_PAYER_WM, $LMI_PAYER_PURSE, $LMI_PAYER_IP));
                        }
                        /**
                         * Real payment
                         */
                        else
                        {
                            /**
                             * Add order note
                             */
                            $this->add_order_note(sprintf('Order successfully paid. WMID: %1$s and purse: %2$s and IP: %3$s', $LMI_PAYER_WM, $LMI_PAYER_PURSE, $LMI_PAYER_IP));                            
                        }         

                        /**
                         * Set status is payment
                         */
                        $order->payment_complete($LMI_SYS_TRANS_NO);
                        die();
                    }
                    else
                    { 
                        /**
                         * Send Service unavailable
                         */
                        die( 'Payment error, please pay other time.' );
                    }
                }          

                /**
                 * Send Service unavailable
                 */
				 die('Payment error, please pay other time.');
            }
            /**
             * Success
             */
            else if ($_GET['action'] === 'success')
            {
                /**
                 * Redirect to success
                 */
                redirect( $this->get_option( 'success_url' ) );
                die();
            }
            /**
             * Fail
             */
            else if ($_GET['action'] === 'fail')
            {
                /**
                 * Add order note
                 */
                $this->add_order_note(sprintf('The order has not been paid. WMID: %1$s and purse: %2$s and IP: %3$s', $LMI_PAYER_WM, $LMI_PAYER_PURSE, $LMI_PAYER_IP));

                /**
                 * Sen status is failed
                 */
                $order->update_status('failed');

                /**
                 * Redirect to cancel
                 */
                redirect( $this->get_option( 'cancel_url' )  );
                die();
         }
    }
	
	function add_order_note( $remarks ) {
		$CI = & get_instance();
		$CI->db->query( 'UPDATE ' . $CI->db->dbprefix('subscriptions') . ' SET remarks = "'.$remarks.'" WHERE id = ' . $this->id );
	}
	
	/**
	 * Function to submi the form to webmoney site.
	 * Only one of LMI_PAYMENT_DESC and LMI_PAYMENT_DESC_BASE64 parameters presense is necessary.
	 * action https://merchant.wmtransfer.com/lmi/payment.asp
	*/
	function submit_form() {
		?>
		<form action="<?php echo $this->form_url; ?>" method="POST" id="webmoney_payment_form" name="webmoney_payment_form" accept-charset="windows-1251">
		<?php 
		$str = '';
		foreach( $this->config as $key => $val )
		{
			if ( $key == 'total_amount' ) {
				$str .= '<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="' . $val . '">'; // Amount paid by the customer. Decimal separator is used.
			}
			if ( $key == 'product_name' ) {
				$str .= '<input type="hidden" name="LMI_PAYMENT_DESC" value="' . $val . '">'; // A comment for the payment; serves for the merchant could verify comments of payments for mismatches. This field is transmitted after the URLEncode function is processed. As the form sent from merchant's site to the merchant.wmtransfer.com is transmitted by means of the client's browser, the merchant, if necessary, can verify not only the amount of payment but its original comment as well. Exhange and financial service should perform such verification obligatory.
				$str .= '<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="' . base64_encode( $val ) . '">'; // A description of goods or services encoded UTF-8 and then encrypted with Base64 algorithm. Is formed by the merchant. If used the result will be substituted for LMI_PAYMENT_DESC. It allows text content not to depend on the encoding used on merchant's website.
			}
			
			if ( $key == 'is_test' ) {
				if ( $val == 'yes' ) {
					$str .= '<input type="hidden" name="LMI_SIM_MODE" value="0">'; // 0 or empty: All test payments will be successful; 
				} else {
					$str .= '<input type="hidden" name="LMI_SIM_MODE" value="1">'; // 1: All test payments will fail; 
				}
			}
			if ( $key == 'email' ) {
				$str .= '<input type="hidden" name="LMI_PAYMER_EMAIL" value="' . $val . '">'; // If the merchant knows the customer's email address he can transmit it in the given fields whereupon the customer will not have to specify it when paying with WM-note, Paymer check or WM-card.
			}
			if ( $key == 'success_url' ) {
				$str .= '<input type="hidden" name="LMI_SUCCESS_URL" value="' . $val . '">'; // This field lets the merchant temporarily replace the Success URL specified on a special web page of the Merchant WebMoney Transfer site. 
			}
			if ( $key == 'result_url' ) {
				$str .= '<input type="hidden" name="LMI_RESULT_URL" value="' . $val . '">'; // This field lets the merchant temporarily replace the Result URL specified on a special web page of the Merchant WebMoney Transfer site.
			}
			if ( $key == 'cancel_url' ) {
				$str .= '<input type="hidden" name="LMI_FAIL_URL" value="' . $val . '">';
			}
		}
		$str .= '<input type="hidden" name="LMI_PAYEE_PURSE" value="' . $this->secret_key . '">'; // Merchant's purse to which the customer has made the payment. Format is a letter and 12 digits.
		echo $str;
		?>
		<input type="submit" value="Submit"/>
		</form>
		<b> Please wait we are redirecting</b>	
		<p>If the page is not redirected automatically, click "Send"</p>

		<script type="text/javascript" event="onload">
		//document.webmoney_payment_form.submit();
		</script>
		<?php
	}
}
// END Webmoney Class

/* End of file Webmoney.php */
/* Location: ./application/libraries/Webmoney.php */