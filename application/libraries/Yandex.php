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
class Yandex {

    /**
     * Form url for Merchant
     *
     * @var string
     */
    public $form_url = 'https://demomoney.yandex.ru/eshop.xml';
		
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
			'firstname' => '', // firstname
			'lastname' => '', // lastname
			'scid' => '363', // scid
			'ShopID' => '151', // ShopID
			'shopSuccessUrl' => '', // shopSuccessUrl			
			'shopFailUrl' => '',			
			'CustomerNumber' => '', // CustomerNumber (Order Number)
			'cps_phone' => '', // Customer Phone			
			'cps_email' => '', // Customer Email
			'Sum' => '0', // Total Amount			
			'paymentType' => 'PC', // paymentType (PC - Yandex Purse, AC - Pay with any credit card, GP - Payment in cash at the cash desks and terminals)
			'cms_name' => 'CodeIgniter',
			
			'ym_mode' => 'sandbox',
		);
		$this->config = $this->prepare_config( $defaults, $config );
		
		if ( $this->config['ym_mode'] == 'live' ) {
			$this->form_url = 'https://money.yandex.ru/eshop.xml';
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
	 * Function to submi the form to Yandex site.
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
			if ( ! in_array( $key, array( 'ym_mode' ) ) ) {
				$str .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
			}
		}
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