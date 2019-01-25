<?php //print_r($profile);?>
<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<div class="row">
		
		<?php 
		$attributes = array('name' => 'profile_form', 'id' => 'profile_form', 'class' => 'comment-form dark-fields');
		echo form_open_multipart('institute/personal_info',$attributes);?>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('First Name');?><?php echo required_symbol();?></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'first_name' );
					}
					elseif( isset($profile->first_name) && !isset($_POST['submitbutt']))
					{
						$val = $profile->first_name;
					}
					$element = array(
						'name'	=>	'first_name',
						'id'	=>	'first_name',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('first_name'),
					);			
					echo form_input($element);
					?>
					<?php echo form_error('first_name');?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Last Name');?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'last_name' );
					}
					elseif( isset($profile->last_name) && !isset($_POST['submitbutt']))
					{
						$val = $profile->last_name;
					}
					$element = array(
						'name'	=>	'last_name',
						'id'	=>	'last_name',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('last_name'),
					);			
					echo form_input($element);
					?>
					<?php echo form_error('last_name');?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('E-mail Address');?><?php echo required_symbol();?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'email' );
					}
					elseif( isset($profile->email) && !isset($_POST['submitbutt']))
					{
						$val = $profile->email;
					}
					$element = array(
						'name'	=>	'email',
						'id'	=>	'email',
						'value'	=>	$val,
						'class' => 'form-control',
						'disable' => TRUE,
						'readonly'=>'true',
						'placeholder' => get_languageword('email'),
					);			
					echo form_input($element);
					?>
					
				</div>
			</div>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Year of Birth');?><?php echo required_symbol();?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'dob' );
					}
					elseif( isset($profile->dob) && !isset($_POST['submitbutt']))
					{
						$val = $profile->dob;
					}
					$element = array(
						'name'	=>	'dob',
						'id'	=>	'dob',
						'value'	=>	$val,
						'class' => 'calendar form-control',
						'data-maxdate' => 'today',
						'placeholder' => get_languageword('date_of_birth')
					);			
					echo form_input($element);
					?>
					<?php echo form_error('dob');?>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="input-group ">
					<label><?php echo get_languageword('Gender');?>:</label>
					<?php
					$val = 'Male';
					if(isset($_POST['submitbutt']))
					{
						$val = $this->input->post('gender');
					}
					elseif(isset($profile->gender) && !isset($_POST['submitbutt']))
					{
						$val = $profile->gender;
					}
					?>
					<div class="radio">
						<label>
							<input type="radio" value="Male" name="gender" <?php if($val == 'Male') echo 'checked';?>>
							<span class="radio-content">
								<span class="item-content"><?php echo get_languageword('Male');?></span>
								<i aria-hidden="true" class="fa uncheck fa-circle-thin"></i>
								<i aria-hidden="true" class="fa check fa-dot-circle-o"></i>
							</span>
						</label>
						<label>
							<input type="radio" value="Female" name="gender" <?php if($val == 'Female') echo 'checked';?>>
							<span class="radio-content">
								<span class="item-content"><?php echo get_languageword('Female');?></span>
								<i aria-hidden="true" class="fa uncheck fa-circle-thin"></i>
								<i aria-hidden="true" class="fa check fa-dot-circle-o"></i>
							</span>
						</label>
					</div>
				</div>

			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Languages Known');?>:</label>

					<?php
					$select = array();
					if(isset($_POST['submitbutt']))
					{
						$select = $this->input->post('language_of_teaching');
					}
					elseif(isset($profile->language_of_teaching) && !isset($_POST['submitbutt']))
					{
						$select = explode(",",$profile->language_of_teaching);
					}
					echo form_multiselect('language_of_teaching[]',$language_options,$select,'class="multiple-tags" multiple="multiple"');
					?>  
					<?php echo form_error('language_of_teaching','<div class="error">', '</div>');?>
				</div>
			</div>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Blog/Website');?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'website' );
					}
					elseif( isset($profile->website) && !isset($_POST['submitbutt']))
					{
						$val = $profile->website;
					}
					$element = array(
						'name'	=>	'website',
						'id'	=>	'website',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('Blog/Website'),
					);			
					echo form_input($element);
					?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Facebook Profile');?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'facebook' );
					}
					elseif( isset($profile->facebook) && !isset($_POST['submitbutt']))
					{
						$val = $profile->facebook;
					}
					$element = array(
						'name'	=>	'facebook',
						'id'	=>	'facebook',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('Facebook'),
					);			
					echo form_input($element);
					?>
					
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Twitter Profile');?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'twitter' );
					}
					elseif( isset($profile->twitter) && !isset($_POST['submitbutt']))
					{
						$val = $profile->twitter;
					}
					$element = array(
						'name'	=>	'twitter',
						'id'	=>	'twitter',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('Twitter'),
					);			
					echo form_input($element);
					?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Linkedin Profile');?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'linkedin' );
					}
					elseif( isset($profile->linkedin) && !isset($_POST['submitbutt']))
					{
						$val = $profile->linkedin;
					}
					$element = array(
						'name'	=>	'linkedin',
						'id'	=>	'linkedin',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('Linkedin'),
					);			
					echo form_input($element);
					?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('paypal_email');?><?php echo required_symbol();?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'paypal_email' );
					}
					elseif( isset($profile->paypal_email) && !isset($_POST['submitbutt']))
					{
						$val = $profile->paypal_email;
					}
					$element = array(
						'name'	=>	'paypal_email',
						'id'	=>	'paypal_email',
						'type'  =>	'email',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('paypal_email_id'),
					);			
					echo form_input($element);
					?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('bank_account_details');?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'bank_ac_details' );
					}
					elseif( isset($profile->bank_ac_details) && !isset($_POST['submitbutt']))
					{
						$val = $profile->bank_ac_details;
					}
					$element = array(
						'name'	=>	'bank_ac_details',
						'id'	=>	'bank_ac_details',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('bank_ac_details'),
					);			
					echo form_textarea($element);
					?>
				</div>
			</div>
			<div class="col-sm-12 ">
				<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('SAVE');?></button>
			</div>

		</form>
	</div>

</div>
<!-- Dashboard panel ends -->