<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Download Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/download_helper.html
 */

// ------------------------------------------------------------------------

/**
 * tpay Helper
 *
 * Generates headers that force a download to happen
 *
 * @access	public
 * @param	string	filename
 * @param	mixed	the data to be tpay
 * @return	void
 * @link https://secure.tpay.com/partner/pliki/tpay-com-documentation-general-en.pdf
 */
if ( ! function_exists('transferuj_process_payment'))
{
	function transferuj_process_payment( $params )
	{
		if( ! is_array( $params ) )
		{
			return 'Parameters should be in form of array';
		}
		$payment = isset($params['payment_id']) ? $params['payment_id'] : 0; // We are not inserting order before the payment process, so the payment id is '0' here
		$ordernumber = str_pad($payment, 4, 0, STR_PAD_LEFT);
		$crc = base64_encode($ordernumber);
		$params['md5sum'] = md5($params['transferuj_merchantid'] . $params['total_amount'] . $crc . $params['transferuj_secretpass']);
		$required = array(
			'transferuj_merchantid', 
			'transferuj_secretpass', 
			'success_url', // Return URL
			'md5sum',
			'total_amount', // Total amount.
			);
		$missed = '';
		$procede = TRUE;
		for( $i = 0; $i < count( $required ); $i++ )
		{
			if( !in_array( $required[$i], array_keys( $params ) ) )
			{
				$missed .= $required[$i] . ', ';
				$procede = FALSE;
			}
		}
		
		if( ! $procede )
		{
			return 'Parameters missed <b>' . $missed . '</b>';
		}
		$transferuj_address = 'https://secure.tpay.com';
		$new_params = array(
			'opis' => $ordernumber, // Transaction description (Mandatory)
			'id' => $params['transferuj_merchantid'], // Numeric identifier assigned to the merchant during the registration. (Mandatory)
			'nazwisko' => isset($params['full_name']) ? $params['full_name'] : '', // Client’s name
			'imie' => isset($params['first_name']) ? $params['first_name'] : '', // Client’s first name
			'adres' => isset($params['address']) ? $params['address'] : '', // Client’s address
			'miasto' => isset($params['city']) ? $params['city'] : '', // Client’s city
			
			'kod' => isset($params['zip']) ? $params['zip'] : '', // Client’s post code 
			'kraj' => isset($params['country']) ? $params['country'] : '', // Client’s country 
			'telefon' => isset($params['phone']) ? $params['phone'] : '', // Client’s telephone number 
			
			'email' => isset($params['email']) ? $params['email'] : '', // Client’s email address 
			'crc' => $crc, // Auxiliary parameter to identify the transaction on the merchant side
			'kwota' => $params['total_amount'], // Transaction amount with dot as decimal separator. Mandatory parameter! Numeric
			'kanal' => 1, // Imposing pre-selected payment channel for the customer (the fulllist of channels along with their ID is available in the seller’s panel). 

			'akceptuje_regulamin' => 1, // Terms and conditions
			'pow_url' => $params['success_url'], // The URL to which the client will be forwarded after the correct transaction processing.
			//'pow_url_blad' => isset($params['failure_url']) ? $params['failure_url'] : '', // The URL to which the client will be forwarded in case transaction error occurs. By default, the same as pow_url
			'wyn_url' => isset($params['result_url']) ? $params['result_url'] : '',
			'md5sum' => $params['md5sum'], // The checksum used to verify the parameters received from the merchant. It is constructed in accordance with the following scheme using MD5 function: MD5(id + kwota + crc + seller’s verification code )
			//'wyn_email' => '', // E-mail address to which notifications of transaction states are sent. By default, the value set in the seller’s panel is used. 
			
			
		);		
		$str = '<form name="transferuj_payment_form" method="post" action="'.$transferuj_address.'">';
		foreach( $new_params as $key => $val )
		{
			if(!in_array($key, array('action'))) {
				$str .= '<input type="hidden" name="'.$key.'" value="'.$val.'">';
			}
		}		
		$str .= '<input type="submit" value="Wyślij"/></form>';		
		$str .= '<b> Trwa przekierowanie...</b>	
				<p>Jeżeli strona nie przekieruje Cię automatycznie, kliknij przycisk "Wyślij"</p>
                                		
				<script type="text/javascript" event="onload">
					document.transferuj_payment_form.submit();
				</script>';		
		return $str;
	}
}


/* End of file download_helper.php */
/* Location: ./system/helpers/download_helper.php */