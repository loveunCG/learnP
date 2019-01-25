	<!-- Dashboard panel -->
	<div class="dashboard-panel">
		<?php echo $message;?>
	<?php 
	$attributes = array('name' => 'profile_form', 'id' => 'profile_form', 'class' => 'comment-form dark-fields');
	echo form_open('tutor/update-contact-information',$attributes);?>
			<div class="row">
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('City');?> <sup>*</sup>:</label>
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'city' );
						}
						elseif( isset($profile->city) && !isset($_POST['submitbutt']))
						{
						$val = $profile->city;
						}
						?>
						<input type="text" class="form-control" name="city" id="city" value="<?php echo $val;?>">
						<?php echo form_error('city');?>
					</div>
				</div>
				
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Landmark');?> <sup>*</sup>:</label>
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'land_mark' );
						}
						elseif( isset($profile->land_mark) && !isset($_POST['submitbutt']))
						{
						$val = $profile->land_mark;
						}
						?>
						<input type="text" class="form-control" name="land_mark" id="land_mark" value="<?php echo $val;?>">
						<?php echo form_error('land_mark');?>
					</div>
				</div>
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Country');?> <sup>*</sup>:</label>
						<div class="dark-picker dark-picker-bright" style="margin-bottom:0px;">
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'country' );
						}
						elseif( isset($profile->country) && !isset($_POST['submitbutt']))
						{
						$val = $profile->phone_code.'_'.$profile->country;
						}
						echo form_dropdown('country', $countries, $val, 'class="select-picker"');
						?>
						<?php echo form_error('country');?>
						</div>
					</div>
				</div>
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Pincode');?> <sup>*</sup>:</label>
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'pin_code' );
						}
						elseif( isset($profile->pin_code) && !isset($_POST['submitbutt']))
						{
						$val = $profile->pin_code;
						}
						?>
						<input type="text" class="form-control" name="pin_code" id="pin_code" value="<?php echo $val;?>">
						<?php echo form_error('pin_code');?>
					</div>
				</div>
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('Phone');?> <sup>*</sup>:</label>
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'phone' );
						}
						elseif( isset($profile->phone) && !isset($_POST['submitbutt']))
						{
						$val = $profile->phone;
						}
						?>
						<input type="text" class="form-control" name="phone" id="phone" value="<?php echo $val;?>" maxlength="10">
						<?php echo form_error('phone');?>
					</div>
				</div>

			  <div class="col-sm-6 ">
			  <div class="input-group ">
				<label><?php echo get_languageword('Type of Classes');?>:</label>
				<div class="checkbox">
					<label>
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'academic_class' );
						}
						elseif( isset($profile->academic_class) && !isset($_POST['submitbutt']))
						{
						$val = $profile->academic_class;
						}
						?>
						<input type="checkbox"  value="yes" name="academic_class" <?php if($val == 'yes') echo 'checked';?>>
						<span class="checkbox-content">
							<span class="item-content"><?php echo get_languageword('Academic');?></span>
							<i aria-hidden="true" class="fa fa-check "></i>
							<i class="check-square"></i>
						</span>
					</label>
					<label>
						<?php			   
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
						$val = $this->input->post( 'non_academic_class' );
						}
						elseif( isset($profile->non_academic_class) && !isset($_POST['submitbutt']))
						{
						$val = $profile->non_academic_class;
						}
						?>
						<input type="checkbox" value="yes" name="non_academic_class" <?php if($val == 'yes') echo 'checked';?>>
						<span class="checkbox-content">
							<span class="item-content"><?php echo get_languageword('Non-academic');?></span>
							<i aria-hidden="true" class="fa fa-check "></i>
							<i class="check-square"></i>
						</span>

					</label>
				</div>
			</div>
			</div>
			</div>
			
			<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('SAVE');?></button>
		</form>
	</div>
<!-- Dashboard panel ends -->