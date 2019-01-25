<!-- Dashboard panel ends -->
<?php echo $message;?>
<div class="dashboard-panel">

<?php
if(count($package_data) > 0) { 

	echo '<div class="alert alert-info"><a href="#" class="close" data-dismiss="alert">×</a><strong>Info!</strong> '.get_languageword('please_become_premium_member_to_avail_additional_features_like').' "'.get_languageword('avail_in_students_course_search_results').'", "'.get_languageword('viewing_student_posted_leads').'" '.get_languageword('and').' "'.get_languageword('sending_messages').'". '.get_languageword('credits_required_to_become_premium_member ').'<strong>'.get_system_settings('min_credits_for_premium_institute').'</strong></div>';

	?>
<?php echo form_open('payment/index', 'id="tutor_subject_mngt" class="form-multi-select"');?>
	<div class="custom_accordion1">
		<p><?php echo get_languageword('Select Package');?>:</p>
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
						<p><?php echo get_languageword('Package Name');?>:<strong><?php echo $l->package_name?></strong></p>
						<p><?php echo get_languageword('Credits'); ?> :<strong> <?php echo $l->credits?></strong></p>

						<?php if(isset($l->discount) && ($l->discount_type == 'Value')){?>
						<p><strong> <?php echo get_languageword('Discount');?>:<?php echo get_system_settings('currency_symbol').' '. $l->discount;?></strong></p>
						<?php }
						else
						{?>
							<p> <?php echo get_languageword('Discount')?>:<strong><?php echo  $l->discount;?> %</strong></p>
						<?php }
						?>

						<p><?php echo get_languageword('Package Cost');?> <strike><?php echo get_system_settings('currency_symbol').' '.$l->package_cost?></strike><strong><b>
						<?php echo  get_system_settings('currency_symbol').' '. $final_cost;?></b></strong></p>
						
					 </div>

					 <div class="radio">
						<label>
							<input type="radio" name="package_id" value="<?php echo $l->id?>">
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
	   if(!empty($payment_gateways))
	   {
			echo '<div class="row">';

			foreach($payment_gateways as $gateway)
			{
				?>
				<div class="col-lg-4 col-md-4 ">
					<div class=" radio">
						<label>
							<input type="radio" name="gateway_id" value="<?php echo $gateway->type_id?>">
							<span class="radio-content">
								<span class="item-content"><?php echo $gateway->type_title?></span>
								<i class="fa uncheck fa-circle-thin" aria-hidden="true"></i>
								<i class="fa check fa-dot-circle-o" aria-hidden="true"></i>
							</span>
						</label>
					</div>
				</div>
				<?php
			}
			echo '</div>';
	   }?>
			</div>
			<button class="btn-link-dark dash-btn" name="Submit" type="Submit"><?php echo get_languageword('Buy now');?></button>
</form>
			
			<?php
			} ?>
</div>