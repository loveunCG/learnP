
  <!-- Elements Of Web Site -->
  <div class="container-fluid">
    <div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform', 'enctype' => 'multipart/form-data');
		echo form_open('',$attributes) ?>
	  <div class="col-lg-12">
        <div class="elements">
          <div class="panel panel-default theameOfPanle">
            <div class="panel-heading main_small_heding"><?php echo isset($pagetitle) ? $pagetitle : 'Profile'?>
            
              
            </div>
            <div class="panel-body"> 
              <div class="col-lg-6">
              <!--Input Text Feilds-->
              <div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"'; ?>><?php echo $message;?></div>
	
				<div class="col-sm-12 ">
					<div class="input-group ">						
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'payment_updated_admin_message' );
						}
						elseif( isset($profile->payment_updated_admin_message) && !isset($_POST['submitbutt']))
						{
						$val = $profile->payment_updated_admin_message;
						}
						?>
						<textarea class="form-control" name="payment_updated_admin_message" id="payment_updated_admin_message"><?php echo $val;?></textarea>
						<?php echo form_error('payment_updated_admin_message');?>
					<span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('Enter your comments so that user can respond')?><font color="red">*</font></label>
                </div>
              </div>
				<div class="col-sm-12 ">
					<div class="input-group ">
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'is_received' );
						}
						elseif( isset($profile->is_received) && !isset($_POST['submitbutt']))
						{
						$val = $profile->is_received;
						}
						?>
						<select name="is_received" id="is_received" class="form-control">
							<option value=""><?php echo get_languageword('Please select');?></option>
							<option value="yes" <?php if ( $val == 'yes') echo ' selected'?>><?php echo get_languageword('Yes');?></option>
							<option value="no" <?php if ( $val == 'no') echo ' selected'?>><?php echo get_languageword('No');?></option>
						</select>						
						<?php echo form_error('is_received');?>
					<span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('Payment Received?')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="col-sm-12 ">
					<div class="input-group ">
                  <?php					  
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
					$val = $this->input->post( 'transaction_no' );
					}
					elseif( isset($profile->transaction_no) )
					{
					$val = $profile->transaction_no;
					}
					?>
					<input type="text" class="form-control" name="transaction_no" id="transaction_no" value="<?php echo $val;?>">
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('Reference No')?><font color="red">*</font></label>
                </div>
              </div>
			  
				<div class="form-group text-right">
					 <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
					<a><i class="fa fa-send"></i> <?php echo get_languageword('submit')?></a>
					</button>
				</div>
								
				<?php			   
				$val = '';
				if( isset($profile->payment_updated_user_message) )
				{
				$val = $profile->payment_updated_user_message;
				}
				if ( $val != '' ) {
				?>
				<div class="form-group">
                <div class="group">						
						<textarea class="form-control" name="payment_updated_user_message" id="payment_updated_user_message" disabled><?php echo $val;?></textarea>
					<span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('User Response')?></label>
                </div>
              </div>
				<?php } ?>
				
				<div class="form-group">
                <div class="group">
                  <?php					  
					$val = '';
					if( isset($profile->package_name) )
					{
					$val = $profile->package_name;
					}
					?>
					<input type="text" class="form-control" name="package_name" id="package_name" value="<?php echo $val;?>" disabled>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('Package Name')?><font color="red">*</font></label>
                </div>
              </div>
				
				
				<div class="form-group">
                <div class="group">						
						<?php			   
						$val = '';
						if( isset($profile->subscribe_date) )
						{
						$val = $profile->subscribe_date;
						}
						?>
						<input type="text" class="form-control" name="subscribe_date" id="subscribe_date" value="<?php echo $val;?>" disabled>
					<span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('Subscription Date');?></label>
                </div>
              </div>
				
				<div class="form-group">
                <div class="group">	
						<?php			   
						$val = '';
						if( isset( $profile->package_cost) )
						{
						$val = $profile->package_cost;
						}
						?>
						<input type="text" class="form-control" name="package_cost" id="package_cost" value="<?php echo $val;?>" disabled>
					<span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('Package Cost');?><font color="red">*</font></label>
                </div>
              </div>
				
				<div class="form-group">
                <div class="group">
						<?php			   
						$val = '';
						if( isset( $profile->amount_paid) )
						{
						$val = $profile->amount_paid;
						}
						?>
						<input type="text" class="form-control" name="amount_paid" id="amount_paid" value="<?php echo $val;?>" disabled>
					<span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('Amount Paid');?><font color="red">*</font></label>
                </div>
              </div>
				
	</div>  </div>
          </div>
        </div>
      </div>
	  </form>
    </div>
  </div>						
			