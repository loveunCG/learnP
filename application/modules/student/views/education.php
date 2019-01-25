<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<?php 
	$attributes = array('name' => 'profile_form', 'id' => 'profile_form', 'class' => 'comment-form dark-fields');
	$str = '';
	if(!empty($param1))
		$str .= '/'.$param1;
	if(!empty($param2))
		$str .= '/'.$param2;
	echo form_open_multipart('student/education'.$str,$attributes);?>
		
		<h4 class="dash-head"><?php echo get_languageword('Education')?>:</h4>
		<div class="education add-more"><a class="btn-add" href="<?php echo base_url();?>student/education/add"><i class="fa  fa-plus"></i> &nbsp;<?php echo get_languageword('Add More');?></a></div>
		
		<?php
		if(in_array($param1, array('add', 'edit')))
		{
			?>
			<div id="education">
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Degree');?>:</label>
					<div class="dark-picker dark-picker-bright">
						<?php
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
							$val = $this->input->post( 'degree' );
						}
						elseif( isset($profile->education_id) && !isset($_POST['submitbutt']))
						{
							$val = $profile->education_id;
						}
						echo form_dropdown('degree',$degrees,$val,'class = "select-picker" id="degree" onchange="toggle_select(this.value)"');
						?>
					</div>
					<input type="text" class="form-control" name="other_title" id="other_title" style="display:none;">
						<script>
						function toggle_select(val)
						{
							if(val == 0)
								document.getElementById('other_title').style.display = 'block';
							else
								document.getElementById('other_title').style.display = 'none';
						}
						toggle_select(<?php echo $val;?>);
						</script>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('University');?>:<sup>*</sup></label>
					<?php
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'education_institute' );
					}
					elseif( isset($profile->education_institute) && !isset($_POST['submitbutt']))
					{
						$val = $profile->education_institute;
					}
					?>
					<input type="text" class="form-control" name="education_institute" id="education_institute" maxlength="50" value="<?php echo $val;?>">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Address');?>:<sup>*</sup></label>
					<?php
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post( 'education_address' );
					}
					elseif( isset($profile->education_address) && !isset($_POST['submitbutt']))
					{
						$val = $profile->education_address;
					}
					?>
					<textarea name="education_address" maxlength="250" id="education_address"><?php echo $val;?></textarea>
					
				</div>
			</div>
			
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Year');?>:</label>
					<div class="dark-picker dark-picker-bright">						
						<?php
						$val = '';
						if( isset($_POST['submitbutt']) )
						{
							$val = $this->input->post( 'education_year' );
						}
						elseif( isset($profile->education_year) && !isset($_POST['submitbutt']))
						{
							$val = $profile->education_year;
						}
						?>
						<?php
						echo form_dropdown('education_year',$years,$val,'class = "select-picker"');
						?>
					</div>
				</div>
			</div>
		</div>
		</div>
		
		<?php
		$id = '';
		if($param1 == 'edit' && $param2 != '')
		$id = $param2;
		?>
		<input type="hidden" name="update_rec_id" value="<?php echo $id;?>">
		<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('SAVE');?></button>		
			<?php
		}
		?>
		

	</form>
		<?php
		if(!empty($educations)) 
		{
			foreach($educations as $education)
			{
			?>
		<div id="education">
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Degree');?>:</label>
					<div class="dark-picker dark-picker-bright">
						<?php
						$val = $education->education_id;
						echo form_dropdown('degree',$degrees,$education->education_id,'class = "select-picker" id="degree" onchange="toggle_select(this.value)" disabled');
						?>
					</div>
					<input type="text" class="form-control" name="other_title" id="other_title_<?php echo $education->record_id;?>" style="display:none;" value="<?php echo $education->other_title;?>" disabled>
						<script>
						function toggle_select(val)
						{
							if(val == 0)
								document.getElementById('other_title_<?php echo $education->record_id;?>').style.display = 'block';
							else
								document.getElementById('other_title_<?php echo $education->record_id;?>').style.display = 'none';
						}
						toggle_select(<?php echo $val;?>);
						</script>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('University');?>:</label>
					<?php
					$val = $education->education_institute;				
					?>
					<input type="text" class="form-control" name="education_institute" id="education_institute" value="<?php echo $val;?>" disabled>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Address');?>:</label>
					<?php
					$val = $education->education_address;				
					?>
					<textarea name="education_address" id="education_address" class="form-control" rows="4" disabled><?php echo $val;?></textarea>
					
				</div>
			</div>
			
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Year');?>:</label>
					<div class="dark-picker dark-picker-bright">						
						<?php
						$val = $education->education_year;				
						?>
						<?php
						echo form_dropdown('education_year',$years,$val,'class = "select-picker" disabled');
						?>
					</div>
				</div>
			</div>
		</div>
		</div>
		<div class="delete"><a class="btn-delete" href="<?php echo base_url();?>student/education/delete/<?php echo $education->record_id?>" onclick="return confirm('<?php echo get_languageword('are you sure?');?>')"><i class="fa  fa-minus"></i> &nbsp;<?php echo get_languageword('delete');?></a> &nbsp; <a class="btn-edit" href="<?php echo base_url();?>student/education/edit/<?php echo $education->record_id?>"><i class="fa  fa-edit"></i> &nbsp;<?php echo get_languageword('edit');?></a></div>
			<?php }} ?>
		

</div>
<!-- Dashboard panel ends -->