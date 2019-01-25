<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<div class="row">
		
		<?php 
		$attributes = array('name' => 'profile_form', 'id' => 'profile_form', 'class' => 'comment-form dark-fields');
		echo form_open_multipart('student/manage-privacy',$attributes);?>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Free Demo');?><?php echo required_symbol();?></label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'free_demo' );
					}
					elseif( isset($profile->free_demo) && !isset($_POST['submitbutt']))
					{
						$val = $profile->free_demo;
					}			
					echo form_dropdown('free_demo', array('No' => get_languageword('No'), 'Yes' => get_languageword('Yes')), $val, 'class="tags"');
					?>
					<?php echo form_error('free_demo');?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Visibility in Search');?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'visibility_in_search' );
					}
					elseif( isset($profile->visibility_in_search) && !isset($_POST['submitbutt']))
					{
						$val = $profile->visibility_in_search;
					}
					echo form_dropdown('visibility_in_search', array('1' => get_languageword('Yes'), '0' => get_languageword('No')), $val, 'class="tags"');
					?>
					<?php echo form_error('visibility_in_search');?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Set Privacy');?><?php echo required_symbol();?>:</label>
					<?php			   
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'show_contact' );
					}
					elseif( isset($profile->show_contact) && !isset($_POST['submitbutt']))
					{
						$val = $profile->show_contact;
					}
					echo form_dropdown('show_contact', array('All' => get_languageword('Show All'), 'Email' => get_languageword('Show Email'), 'Mobile' => get_languageword('Show Mobile'),'None' => get_languageword('Show None')), $val, 'class="tags"');
					?>
					<?php echo form_error('show_contact');?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<label><?php echo get_languageword('Availability');?><?php echo required_symbol();?>:</label>
				<div class="row">
					<div class="col-xs-3 padright5">
						<div class="input-group ">
							<?php			   
							$val = '';
							if( isset($_POST['submitbutt']) )
							{
								$val = $this->input->post( 'availability_status' );
							}
							elseif( isset($profile->availability_status) && !isset($_POST['submitbutt']))
							{
								$val = $profile->availability_status;
							}
							echo form_dropdown('availability_status', array('1' => get_languageword('Yes'), '0' => get_languageword('No')), $val, 'class="tags"');
							?>
							<?php echo form_error('availability_status');?>
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="col-sm-12 ">
				<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('SAVE');?></button>
			</div>

		</form>
	</div>

</div>
<!-- Dashboard panel ends -->