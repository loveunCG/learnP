
<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<div class="row">
		
		<?php 
		$attributes = array('name' => 'post_requirement', 'id' => 'post_requirement', 'class' => 'comment-form dark-fields');
		echo form_open_multipart('student/post_requirement',$attributes);?>
			<div class="col-sm-6 ">
				<div class="input-group ">
						<label><?php echo get_languageword('Preferred Location');?> <sup>*</sup>:</label>
						<div class="dark-picker dark-picker-bright">
							<?php			   

							echo form_dropdown('location_id', $location_opts, '', 'class="select-picker"');
							?>
							<?php echo form_error('location_id');?>
						</div>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
						<label><?php echo get_languageword('Course Seeking');?> <sup>*</sup>:</label>
						<div class="dark-picker dark-picker-bright">
							<?php			   
							
							echo form_dropdown('course_id', $course_opts, '', 'class="select-picker"');
							?>
							<?php echo form_error('course_id');?>
						</div>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
						<label><?php echo get_languageword('Preferred Teaching type');?> <sup>*</sup>:</label>
						<div class="dark-picker dark-picker-bright">
							<?php 
							echo form_dropdown('teaching_type_id', $teaching_types_options, '', 'class="select-picker"');
							?>
							<?php echo form_error('teaching_type_id');?>
						</div>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Priority of Requirement');?><?php echo required_symbol();?>:</label>
					<div class="dark-picker dark-picker-bright">
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'priority_of_requirement' );
					}
					elseif( isset($profile->priority_of_requirement) && !isset($_POST['submitbutt']))
					{
						$val = $profile->priority_of_requirement;
					}
					echo form_dropdown('priority_of_requirement', array('immediately' => get_languageword('Immediately'), 'after_a_week' => get_languageword('After a Week'), 'after_a_month' => get_languageword('After A Month')), $val, 'class="select-picker"');
					?>
					<?php echo form_error('priority_of_requirement');?>
				   </div>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Your Present Status');?></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'present_status' );
					}
					elseif( isset($profile->present_status) && !isset($_POST['submitbutt']))
					{
						$val = $profile->present_status;
					}
					$element = array(
						'name'	=>	'present_status',
						'id'	=>	'present_status',
						'value'	=>	$val,
						//'required' => 'required',
						'class' => 'form-control',
						'placeholder' => get_languageword('present_status_ex:student or employee'),
						'description'=>'Now you are ex: student or employee or etc'
					);			
					echo form_input($element);
					?>
					<?php echo form_error('present_status');?>
				</div>
			</div>
			
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('duration_needed_to_complete_course_in');?><sup>*</sup>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'duration_needed' );
					}
					elseif( isset($profile->duration_needed) && !isset($_POST['submitbutt']))
					{
						$val = $profile->duration_needed;
					}
					$element = array(
						'name'	=>	'duration_needed',
						'id'	=>	'duration_needed',
						'value'	=>	$val,
						//'required' => 'required',
						'class' => 'form-control',
						'placeholder' => get_languageword('EX: 15 days or 2 months or ..'),
					);			
					echo form_input($element);
					?>
					<?php echo form_error('duration_needed');?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Credits You can pay');?></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'budget' );
					}
					elseif( isset($profile->budget) && !isset($_POST['submitbutt']))
					{
						$val = $profile->budget;
					}
					$element = array(
						'name'	=>	'budget',
						'id'	=>	'budget',
						'value'	=>	$val,
						//'required' => 'required',
						'class' => 'form-control',
						'placeholder' => get_languageword('credits'),
					);			
					echo form_input($element);
					?>
					<?php echo form_error('budget');?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Payment Type');?></label>
					<div class="dark-picker dark-picker-bright">
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post('budget_type');
					}
					elseif( isset($profile->budget_type) && !isset($_POST['submitbutt']))
					{
						$val = $profile->budget_type;
					}
					echo form_dropdown('budget_type', array('one_time' => get_languageword('One Time'), 'hourly' => get_languageword('Hourly'), 'monthly' => get_languageword('Monthly')), $val, 'class="select-picker"');
					?>
					<?php echo form_error('budget_type');?>
					</div>
				</div>
			</div>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('title_of_requirement');?><sup>*</sup>:</label>
					<?php
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'title_of_requirement' );
					}
					elseif( isset($profile->title_of_requirement) && !isset($_POST['submitbutt']))
					{
						$val = $profile->title_of_requirement;
					}
					$element = array(
						'name'	=>	'title_of_requirement',
						'id'	=>	'title_of_requirement',
						'value'	=>	$val,
						//'required' => 'required',
						'class' => 'form-control',
						'placeholder' => get_languageword('title_of_requirement'),
					);
					echo form_input($element);
					?>
					
				</div>
			</div>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Requirement Details');?></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'requirement_details' );
					}
					elseif( isset($profile->requirement_details) && !isset($_POST['submitbutt']))
					{
						$val = $profile->requirement_details;
					}
					$element = array(
						'name'	=>	'requirement_details',
						'id'	=>	'requirement_details',
						'value'	=>	$val,
						'maxlength'	=> 200,
						'rows'	=>5,
						//'required' => 'required',
						'class' => 'form-control',
						'placeholder' => get_languageword('requirement_details'),
					);
					echo form_textarea($element);
					?>
					<?php echo form_error('requirement_details');?>
				</div>
			</div>

			<div class="col-sm-12 ">
				<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('SAVE');?></button>
			</div>

		</form>
	</div>

</div>
<!-- Dashboard panel ends -->