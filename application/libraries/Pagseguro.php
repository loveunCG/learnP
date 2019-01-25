<?php
class Pagseguro {

		/**
		* Please enter your PagSeguro email address. You can get your email you can get the email address here 
		* Sandbox : https://sandbox.pagseguro.uol.com.br/vendedor/configuracoes.html 
		* Live : https://sandbox.pagseguro.uol.com.br/vendedor/configuracoes.html
		*
		* @since    1.0
		*/
		private $email;
		
		/**
		* Please enter your PagSeguro token. You can get your token here
		* Sandbox : https://sandbox.pagseguro.uol.com.br/vendedor/configuracoes.html
		* Live : https://pagseguro.uol.com.br/vendedor/configuracoes.html
		*
		* @since    1.0
		*/
		private $token;
		
		private $payment_table;
		
		private $payment_table_key;


		/**
		* Menorah Tutor PagSeguro Gateway
		*
		* Launches first method.
		*
		* @return   void
		* @since    1.0
		*/

		function __construct( $params = array() )
		{
			$this->email = isset($params['email']) ? $params['email'] : '';
			$this->token = isset($params['token']) ? $params['token'] : '';
			$this->payment_table = isset($params['payment_table']) ? $params['payment_table'] : 'subscriptions';
			$this->payment_table_key = isset($params['payment_table_key']) ? $params['payment_table_key'] : 'id';
			$this->listen_for_pagseguro_ipn();
		}
		
		function listen_for_pagseguro_ipn()
		{
			// require PagSeguro files
			$this->load_pagseguro_sdk();
			
			// check for incoming order id
			$code = isset( $_POST['notificationCode'] ) && trim( $_POST['notificationCode'] ) !== "" ? trim( $_POST['notificationCode'] ) : null;
			$type = isset( $_POST['notificationType'] ) && trim( $_POST['notificationType'] ) !== "" ? trim( $_POST['notificationType'] ) : null;
			
			// get notification
			$notificationType = new PagSeguroNotificationType( $type );
			$strType = $notificationType->getTypeFromValue();
			
			// try to verify the notification
			try {
				$credentials = array();
				$credentials['email'] = $this->email;
				$credentials['token'] = $this->token;
				// generate credentials
				$credentials = new PagSeguroAccountCredentials( $credentials['email'], $credentials['token'] );

				// notification service
				$transaction = PagSeguroNotificationService::checkTransaction( $credentials, $code );				
var_dump($transaction);die();
				// get both values
				$reference = $transaction->getReference();
				$status = $transaction->getStatus();

				// check there is an external reference
				if ( isset( $reference ) && isset( $status ) ) {
					// check for succesful status
					if ( $status->getValue() == 3 ) {						
						$this->update_status( $this->payment_table, array('payment_received' => 1 ), array( $this->payment_table_key => $reference) );
					}
				} else {
					sendEmail( 'adiyya@gmail.com', 'adiyya@gmail', 'Pagseguro Payment Status', var_export($_POST, true) );
				}
			} catch ( Exception $e ) {
				sendEmail( 'adiyya@gmail.com', 'adiyya@gmail', 'Pagseguro Payment Status', $e->getMessage() );
				return;
			}
		}
		
		function load_pagseguro_sdk()
		{
			require_once( 'PagSeguroLibrary/PagSeguroLibrary.php' );
		}
		
		function update_status($data, $table, $condition ) {
			
		}
}
	