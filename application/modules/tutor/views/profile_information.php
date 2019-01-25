<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<?php 
	$attributes = array('name' => 'profile_form', 'id' => 'profile_form', 'class' => 'comment-form dark-fields');
	echo form_open_multipart('tutor/profile-information',$attributes);?>
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Profile Image')?></label>
					<?php			   
					$val = '';
					if( isset($profile->photo) && $profile->photo != '')
					{
						$val = $profile->photo;
					}
					$element = array(
						'type' => 'file',
						'name'	=>	'photo',
						'id'	=>	'photo',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('Profile Image'),
						'onchange' => "readURL(this, 'site_logo')",
					);			
					echo form_input($element);
					?>
					<?php 
                     $src = "";
                     $style="display:none;";
                     if($val != '' && file_exists('assets/uploads/profiles/thumbs/'.$val)) {
						$src = base_url().'/'."assets/uploads/profiles/thumbs/".$val;
                     	$style="";
                     }
                     ?>
                  <img id="site_logo" src="<?php echo $src;?>" height="120" style="<?php echo $style;?>" />
					<?php echo form_error('photo');?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="input-group ">
					<label><?php echo get_languageword('your_profile_description')?>:<sup>*</sup></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'profile' );
					}
					elseif( isset($profile->profile) && !isset($_POST['submitbutt']))
					{
						$val = $profile->profile;
					}
					$element = array(
						'name'	=>	'profile',
						'id'	=>	'profile',
						'value'	=>	$val,
						'maxlength' => 500,
						'placeholder' => get_languageword('profile_description'),
						'rows' => 6,
					);			
					echo form_textarea($element);
					?>
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="input-group ">
					<label><?php echo get_languageword('your_meta_seo_keywords')?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'seo_keywords' );
					}
					elseif( isset($profile->seo_keywords) && !isset($_POST['submitbutt']))
					{
						$val = $profile->seo_keywords;
					}
					$element = array(
						'name'	=>	'seo_keywords',
						'id'	=>	'seo_keywords',
						'value'	=>	$val,
						'maxlength'=> 100,
						'placeholder' => get_languageword('meta_seo_keywords'),
						'rows' => 6,
					);			
					echo form_textarea($element);
					?>
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="input-group ">
					<label><?php echo get_languageword('meta_description')?></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'meta_desc' );
					}
					elseif( isset($profile->meta_desc) && !isset($_POST['submitbutt']))
					{
						$val = $profile->meta_desc;
					}
					$element = array(
						'name'	=>	'meta_desc',
						'id'	=>	'meta_desc',
						'value'	=>	$val,
						'maxlength'=>  100,
						'placeholder' => get_languageword('meta_description'),
						'rows' => 6,
					);			
					echo form_textarea($element);
					?>
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="input-group ">
					<label><?php echo get_languageword('Describe your Experience and Services')?>:<sup>*</sup></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'experience_desc' );
					}
					elseif( isset($profile->experience_desc) && !isset($_POST['submitbutt']))
					{
						$val = $profile->experience_desc;
					}
					$element = array(
						'name'	=>	'experience_desc',
						'id'	=>	'experience_desc',
						'value'	=>	$val,
						'maxlength'=>  500,
						'placeholder' => get_languageword('experience'),
						'rows' => 6,
					);			
					echo form_textarea($element);
					?>
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Profile Page Title')?><sup>*</sup></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'profile_page_title' );
					}
					elseif( isset($profile->profile_page_title) && !isset($_POST['submitbutt']))
					{
						$val = $profile->profile_page_title;
					}
					$element = array(
						'name'	=>	'profile_page_title',
						'id'	=>	'profile_page_title',
						'maxlength' => 100,
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('Profile Page Title'),
					);			
					echo form_input($element);
					?>
					
				</div>
			</div>
		
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Qualification')?><sup>*</sup></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'qualification' );
					}
					elseif( isset($profile->qualification) && !isset($_POST['submitbutt']))
					{
						$val = $profile->qualification;
					}
					$element = array(
						'name'	=>	'qualification',
						'id'	=>	'qualification',
						'maxlength'=>	'100',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('qualification'),
					);			
					echo form_input($element);
					?>
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Experiance Duration')?><sup>*</sup></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'teaching_experience' );
					}
					elseif( isset($profile->teaching_experience) && !isset($_POST['submitbutt']))
					{
						$val = $profile->teaching_experience;
					}
					$element = array(
						'name'	=>	'teaching_experience',
						'id'	=>	'teaching_experience',
						'value'	=>	$val,
						'type'	=>	'number',
						'step'  =>	"0.01",
						'class' => 'form-control',
						'placeholder' => get_languageword('experiance_duration Ex: 4 '),
					);			
					echo form_input($element);
					?>
					
				</div>
			</div>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Duration Type')?></label>
					<div class="dark-picker dark-picker-bright">
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'duration_of_experience' );
					}
					elseif( isset($profile->duration_of_experience) && !isset($_POST['submitbutt']))
					{
						$val = $profile->duration_of_experience;
					}
					$options = array(
				        'Months'         => 'Months',
				        'Years'          => 'Years',
				    );
		
					echo form_dropdown('duration_of_experience',$options, $val,'class="select-picker"');
					?>
					</div>
				</div>
			</div>
		</div>


		<!--
		<h4 class="dash-head"><?php echo get_languageword('Education')?>:</h4>
		<div id="education">
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label>Degree:</label>
					<div class="dark-picker dark-picker-bright">
						<?php
						$select = '';
						echo form_dropdown('degree[]',$degrees,$select,'class = "select-picker"');
						?>
					</div>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label>University:</label>
					<input type="text" class="form-control" name="education_institute[]" id="education_institute">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label>Address:</label>
					<textarea name="education_address[]" id="education_address"></textarea>
					
				</div>
			</div>
			
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label>Year:</label>
					<div class="dark-picker dark-picker-bright">						
						<?php
						$select = '';
						echo form_dropdown('education_year[]',$years,$select,'class = "select-picker"');
						?>
					</div>
				</div>
			</div>
		</div>
		</div>		
		<div class="education add-more"><a href="javascript:void(0);"><i class="fa  fa-plus"></i> &nbsp;Add More</a></div>
		-->
		<div class="row">
		<div class="col-sm-6 ">
			<div class="input-group ">
				<label><?php echo get_languageword('How far are you willing to travel').'?('.get_languageword('in Km').')';?><sup>*</sup></label>
				<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'willing_to_travel' );
					}
					elseif( isset($profile->willing_to_travel) && !isset($_POST['submitbutt']))
					{
						$val = $profile->willing_to_travel;
					}
					$element = array(
						'name'	=>	'willing_to_travel',
						'id'	=>	'willing_to_travel',
						'value'	=>	$val,
						'type'	=>	'number',
						'step'  =>	"0.01",
						'class' => 'form-control',
						'placeholder' => get_languageword('willing_to_travel'),
					);			
					echo form_input($element);
					?>
			</div>
		</div>
			<div class="col-sm-6">
				<div class="input-group ">
					<label><?php echo get_languageword('Do you have your own vehicle to travel');?></label>
					<?php			   
					$val = 'yes';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'own_vehicle' );
					}
					elseif( isset($profile->own_vehicle) && !isset($_POST['submitbutt']))
					{
						$val = $profile->own_vehicle;
					}					
					?>
					<div class="radio">
						<label>
							<input type="radio" value="yes" name="own_vehicle" <?php if($val == 'yes') echo 'checked';?>>
							<span class="radio-content">
								<span class="item-content"><?php echo get_languageword('Yes');?></span>
								<i aria-hidden="true" class="fa uncheck fa-circle-thin"></i>
								<i aria-hidden="true" class="fa check fa-dot-circle-o"></i>
							</span>
						</label>
						<label>
							<input type="radio" value="no" name="own_vehicle" <?php if($val == 'no') echo 'checked';?>>
							<span class="radio-content">
								<span class="item-content"><?php echo get_languageword('No');?></span>
								<i aria-hidden="true" class="fa uncheck fa-circle-thin"></i>
								<i aria-hidden="true" class="fa check fa-dot-circle-o"></i>
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