<!-- Dashboard panel ends -->
<?php echo $message;?>
<div class="dashboard-panel">

<?php
if(count($package_data) > 0) { 

	echo '<div class="alert alert-info"><a href="#" class="close" data-dismiss="alert">×</a><strong>Info!</strong> '.get_languageword('please_become_premium_member_to_avail_additional_features_like').' "'.get_languageword('booking_tutors').'" '.get_languageword('and').' "'.get_languageword('sending_messages').'". '.get_languageword('credits_required_to_become_premium_member ').'<strong>'.get_system_settings('min_credits_for_premium_student').'</strong></div>';

	?>
	<div class="custom_accordion1">
		
		<div class="row">
			<div class="pricing-box-height">
				<?php 
				  foreach($package_data as $l) { ?>
			   	<div class="col-lg-6 col-md-6 col-sm-12">
				  <div class="pricing_div">
					  <div class="site_pac_hed green-hed">
						
						<?php 
						$currency_symbol = '';
						if(isset($site_settings->currency_symbol))
						    $currency_symbol = $site_settings->currency_symbol;
						$final_cost = $l->package_cost;
						   if(isset($l->discount) && ($l->discount != 0))
							if($l->discount_type == 'Value')
							{
								$final_cost = $l->package_cost - $l->discount;
													
							}
							else
							{
								$discount = ($l->discount/100)*$l->package_cost;							
								$final_cost = $l->package_cost - $discount;
							?>
						<?php } else { ?>
						&nbsp &nbsp 
						<?php 
						   if($currency_symbol != '')
							echo $currency_symbol.' ';
							echo $final_cost;
						}
							?> 
					 </div>
					<div class="pack-list">
						<p><?php echo get_languageword('Package Name');?> <strong><?php echo $l->package_name?></strong></p>
						<p><?php echo get_languageword('Credits'); ?> <strong><?php echo $l->credits?></strong></p>

						<?php if(isset($l->discount) && ($l->discount_type == 'Value')){?>
						<p><strong> <?php echo get_languageword('Discount:');?><?php echo get_system_settings('currency_symbol').' '. $l->discount;?></strong></p>
						<?php }
						else
						{?>
							<p> <?php echo get_languageword('Discount');?><strong><?php echo  $l->discount;?> %</strong></p>
						<?php }
						?>

						<p><?php echo get_languageword('Package Cost');?> <strike><?php echo get_system_settings('currency_symbol').' '.$l->package_cost?></strike><strong> <b>
						<?php echo  get_system_settings('currency_symbol').' '. $final_cost;?></b></strong></p>
						
					 </div>

					 <input type="hidden" id="package_id" name="package_id" value="<?php echo $l->id?>">					 
				  </div>
				  <!--./pricing_div-->
			   	</div>
			   	<?php } ?>
		   	</div>
		</div>
		</br>
		<p class="top20"><b><?php echo get_languageword('Payment_Gateway'); ?></b></p>
		<?php
		$system_currency = get_system_settings('Currency_Code');
	   if(!empty($payment_gateways))
	   {
			echo '<p>';
           
			foreach($payment_gateways as $gateway)
			{
				if ( in_array( $gateway->type_slug, array( 'Web money' ) ) ) { // Let us check whether the payment gateway supports the currency
					if ( $gateway->type_slug == 'Web money' ) {
						if ( ! in_array( $system_currency, array( 'RUB', 'EUR', 'USD', 'UAH' ) ) ) { // Let us skip if the system not in the supported currency
							continue;
						}
					}
				}
				?>
				<input type="hidden" name="gateway_id" value="<?php echo $gateway->type_id;?>">
				<?php echo get_languageword('Title : ');?> <?php echo $gateway->type_title; ?>
										
			<?php
			}
			echo '</p>';
	   }
	   ?>		
		</div>

		<form action="<?php echo $razorpay['success_url'];?>" method="POST">
      <script
        src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="<?php echo $razorpay['razorpay_key_id'];?>"
        data-amount="<?php echo $razorpay['total_amount'];?>"
        data-name="<?php echo $razorpay['product_name'];?>"
        data-description="<?php echo $razorpay['product_name'];?>"        
        data-netbanking="true"
        data-prefill.name="<?php echo $razorpay['firstname'];?> <?php echo $razorpay['lastname'];?>"
        data-prefill.email="<?php echo $razorpay['email'];?>"
        data-prefill.contact="<?php echo $razorpay['phone'];?>"
        data-notes.shopping_order_id="21">
      </script>
      <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
      <input type="hidden" name="shopping_order_id" value="21">
    </form>

			
			<?php
			} ?>
</div>