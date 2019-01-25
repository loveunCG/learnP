	<!-- Dashboard panel -->
	<div class="dashboard-panel">
		<?php echo $message;?>
	<?php 
	$attributes = array('name' => 'profile_form', 'id' => 'profile_form', 'class' => 'comment-form dark-fields');
	echo form_open('',$attributes);?>
			<div class="row">
				<div class="col-sm-12 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Enter you comments / Enter your transaction details so that admin can respond');?> <sup>*</sup>:</label>
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'payment_updated_user_message' );
						}
						elseif( isset($profile->payment_updated_user_message) && !isset($_POST['submitbutt']))
						{
						$val = $profile->payment_updated_user_message;
						}
						?>
						<textarea class="form-control" name="payment_updated_user_message" id="payment_updated_user_message"><?php echo $val;?></textarea>
						<?php echo form_error('payment_updated_user_message');?>
					</div>
				</div>
				<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('SAVE');?></button><!--&nbsp|&nbsp;<button class="btn-link-dark dash-btn" onclick="javascript:document.location = '<?php echo URL_STUDENT_SUBSCRIPTIONS;?>'"><?php echo get_languageword('Cancel');?></button>-->
				
				<?php			   
				$val = '';
				if( isset($profile->payment_updated_admin_message) )
				{
				$val = $profile->payment_updated_admin_message;
				}
				if ( $val != '' ) {
				?>
				<div class="col-sm-12 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Admin Response');?>:</label>
						
						<textarea class="form-control" name="payment_updated_admin_message" id="payment_updated_admin_message" disabled><?php echo $val;?></textarea>
					</div>
				</div>
				<?php } ?>
				
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Package');?> <sup>*</sup>:</label>
						<?php			   
						$val = '';
						if( isset($profile->package_name) )
						{
						$val = $profile->package_name;
						}
						?>
						<input type="text" class="form-control" name="package_name" id="package_name" value="<?php echo $val;?>" disabled>
					</div>
				</div>
				
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Subscription Date');?> <sup>*</sup>:</label>
						<?php			   
						$val = '';
						if( isset($profile->subscribe_date) )
						{
						$val = $profile->subscribe_date;
						}
						?>
						<input type="text" class="form-control" name="subscribe_date" id="subscribe_date" value="<?php echo $val;?>" disabled>
					</div>
				</div>	
				
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Package Cost');?> <sup>*</sup>:</label>
						<?php			   
						$val = '';
						if( isset( $profile->package_cost) )
						{
						$val = $profile->package_cost;
						}
						?>
						<input type="text" class="form-control" name="package_cost" id="package_cost" value="<?php echo $val;?>" disabled>
					</div>
				</div>
				
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Amount Paid');?> <sup>*</sup>:</label>
						<?php			   
						$val = '';
						if( isset( $profile->amount_paid) )
						{
						$val = $profile->amount_paid;
						}
						?>
						<input type="text" class="form-control" name="amount_paid" id="amount_paid" value="<?php echo $val;?>" disabled>
					</div>
				</div>
				
							
			</div>
			
			
		</form>
	</div>
<!-- Dashboard panel ends -->