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
 * Force Download
 *
 * Generates headers that force a download to happen
 *
 * @access	public
 * @param	string	filename
 * @param	mixed	the data to be downloaded
 * @return	void
 */
if ( ! function_exists('payuhash'))
{
	function payuhash( $posted, $salt )
	{
		// Hash Sequence
		$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
		$hashVarsSeq = explode('|', $hashSequence);
		$hash_string = '';	
		foreach($hashVarsSeq as $hash_var) {
		  $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
		  $hash_string .= '|';
		}
		$hash_string .= $salt;
		$hash = strtolower(hash('sha512', $hash_string));
		return $hash;
	}
}

if ( ! function_exists('call_payu'))
{
	function call_payu($params)
	{
		if( !is_array( $params ) )
		{
			return 'Parameters should be in form of array';
		}		
		$params['txnid'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
		$required = array('key', 'txnid', 'amount', 'firstname', 'email', 'phone', 'productinfo', 'surl', 'furl', 'service_provider', 'action', 'salt');
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
		
		if( !$procede )
		{
			return 'Parameters missed <b>' . $missed . '</b>';
		}		
		$params['hash'] = payuhash($params, $params['salt']);		
		$str = '<form name="payuForm" method="post" action="'.$params['action'].'">';
		foreach( $params as $key => $val )
		{
			if(!in_array($key, array('action'))) {
				$str .= '<input type="hidden" name="'.$key.'" value="'.$val.'">';
			}
		}
		
		$str .= '</form>';		
		$str .= '
			<script>
		window.onload = function() { 
		document.payuForm.submit();
		}
		</script>';		
		return $str;
	}
}


/* End of file download_helper.php */
/* Location: ./system/helpers/download_helper.php */