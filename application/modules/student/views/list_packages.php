<!-- Dashboard panel ends -->
<?php echo $message;?>
<div class="dashboard-panel">

<?php
if(count($package_data) > 0) { 

	echo '<div class="alert alert-info"><a href="#" class="close" data-dismiss="alert">×</a><strong>Info!</strong> '.get_languageword('please_become_premium_member_to_avail_additional_features_like').' "'.get_languageword('booking_tutors').'" '.get_languageword('and').' "'.get_languageword('sending_messages').'". '.get_languageword('credits_required_to_become_premium_member ').'<strong>'.get_system_settings('min_credits_for_premium_student').'</strong></div>';

	?>
<?php echo form_open('payment/index', 'id="tutor_subject_mngt" class="form-multi-select"');?>
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

					 <div class="radio">
						<label>
							<input type="radio" id="package_id" name="package_id" value="<?php echo $l->id?>" onclick="javascript:assign_price('<?php echo $l->id;?>', '<?php echo $final_cost * 100;?>', '<?php echo $l->package_name?>');">
							<span class="radio-content">
								<span class="item-content"><?php echo $l->package_name?></span>
								<i class="fa uncheck fa-circle-thin" aria-hidden="true"></i>
								<i class="fa check fa-dot-circle-o" aria-hidden="true"></i>
							</span>
						</label>
					</div>
				  </div>
				  <!--./pricing_div-->
			   	</div>
			   	<?php } ?>
		   	</div>
		</div>
		</br>
		<p class="top20"><b><?php echo get_languageword('select_Payment_Gateway'); ?></b></p>
		<?php
		$system_currency = get_system_settings('Currency_Code');
	   if(!empty($payment_gateways))
	   {
			echo '<div class="row">';
           
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
				<div class="col-lg-4 col-md-4 ">
				<div class=" radio">
				<label>
					<input type="radio" name="gateway_id" value="<?php echo $gateway->type_id;?>">
					<span class="radio-content">
						<span class="item-content"><?php echo $gateway->type_title?></span>
						<i class="fa uncheck fa-circle-thin" aria-hidden="true"></i>
						<i class="fa check fa-dot-circle-o" aria-hidden="true"></i>
					</span>
				</label>
			</div>
			<div id="gateway_<?php echo $gateway->type_id;?>"></div>
			</div>
										
			<?php
			}
			echo '</div>';
	   }
	   ?>
	   <script>
	   function assign_price( package_id, final_cost, name ) {
		   $.post( "<?php echo site_url('payment/get_additional_content');?>", { package_id: package_id, final_cost: final_cost, name:name })
			  .done(function( data ) {
				$('#gateway_<?php echo STRIPE_PAYMENT_GATEWAY;?>').html(data);
			  });			
	   }
	   function get_form(gateway, final_cost) {
		   if(gateway == <?php echo RAZORPAY_PAYMENT_GATEWAY;?>) {
			   var package_id = $('#package_id').val();
			   $.post( "<?php echo site_url('payment/get_razorpay_form');?>", { package_id: package_id, final_cost: final_cost})
				  .done(function( data ) {
					$('#gateway_<?php echo RAZORPAY_PAYMENT_GATEWAY;?>').html(data);
				  });
		   }
	   }
	   </script>
		
		</div>

			<button class="btn-link-dark dash-btn" name="Submit" type="Submit"><?php echo get_languageword('Buy now');?></button>
</form>
			
			<?php
			} ?>
</div>