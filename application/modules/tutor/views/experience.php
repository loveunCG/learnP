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
	echo form_open_multipart('tutor/experience'.$str,$attributes);?>
		
		<h4 class="dash-head"><?php echo get_languageword('experience')?>:</h4>
		<div class="education add-more"><a class="btn-add" href="<?php echo base_url();?>tutor/experience/add"><i class="fa  fa-plus"></i> &nbsp;<?php echo get_languageword('Add More');?></a></div>
		
		<?php
		if(in_array($param1, array('add', 'edit')))
		{
			?>
			<div id="education">
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Company_Name');?>:<sup>*</sup></label>
					<?php
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post('company');
					}
					elseif( isset($profile->company) && !isset($_POST['submitbutt']))
					{
						$val = $profile->company;
					}
					?>
					<input type="text" class="form-control" name="company" maxlength="50" id="company" value="<?php echo $val;?>">
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Role');?>:<sup>*</sup></label>
					<?php
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post('role');
					}
					elseif( isset($profile->role) && !isset($_POST['submitbutt']))
					{
						$val = $profile->role;
					}
					?>
					<input type="text" class="form-control" name="role" maxlength="50" id="role" value="<?php echo $val;?>">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('description');?>:<sup>*</sup></label>
					<?php
					$val = '';
					if( isset($_POST['submitbutt']) )
					{
						$val = $this->input->post('description');
					}
					elseif( isset($profile->description) && !isset($_POST['submitbutt']))
					{
						$val = $profile->description;
					}
					?>
					<textarea name="description" class="form-control" rows= "6" maxlength="250" id="description"><?php echo $val;?></textarea>
					
				</div>
			</div>
			
			<div class="col-sm-6 ">
				
					<div class="row">
						<div class="col-sm-6 pad-right0">
                            <div class="input-group ">
							<label><?php echo get_languageword('From');?>:</label>
								<div class="dark-picker dark-picker-bright pickr-full">						
									<?php
									$val = '';
									if( isset($_POST['submitbutt']) )
									{
										$val = $this->input->post( 'from_month');
									}
									elseif( isset($profile->from_date) && !isset($_POST['submitbutt']))
									{
										$value= array();
										$value = explode(' ', $profile->from_date);
										$val = $value[0];	
									}
									?>
									<?php
									echo form_dropdown('from_month',$months,$val,'class = "select-picker"');
									?>
								</div>
						</div>	
						</div>	
						<div class="col-sm-6">
                            <div class="input-group ">
                                <label>&nbsp;</label>
                                <div class="dark-picker dark-picker-bright pickr-full">						
								<?php
								$val = '';
								if( isset($_POST['submitbutt']) )
								{
									$val = $this->input->post( 'from_year' );
								}
								elseif( isset($profile->from_date) && !isset($_POST['submitbutt']))
								{
									$value= array();
									$value = explode(' ', $profile->from_date);
									$val = $value[1];
								}
								?>
								<?php
								echo form_dropdown('from_year',$years,$val,'class = "select-picker"');
								?>
							</div>
							</div>
                                
						</div>
					</div>
			</div>	

			<div class="col-sm-6 ">
				
					<div class="row">
						<div class="col-sm-6 pad-right0">
                            <div class="input-group ">
							<label><?php echo get_languageword('To');?>:</label>
                            <div class="dark-picker dark-picker-bright pickr-full">						
									<?php
									$val = '';
									if( isset($_POST['submitbutt']) )
									{
										$val = $this->input->post('to_month');
									}
									elseif( isset($profile->to_date) && !isset($_POST['submitbutt']))
									{
										$value =array();
										$value= explode(' ',$profile->to_date);
										$val = $value[0];
									}
									?>
									<?php
									echo form_dropdown('to_month',$months,$val,'class = "select-picker"');
									?>
								</div>
								</div>
						</div>	
						<div class="col-sm-6">
                            <div class="input-group ">
                            <label>&nbsp;</label>
                            <div class="dark-picker dark-picker-bright pickr-full">						
								<?php
								$val = '';
								if( isset($_POST['submitbutt']) )
								{
									$val = $this->input->post( 'to_year' );
								}
								elseif( isset($profile->to_year) && !isset($_POST['submitbutt']))
								{
									$value =array();
									$value= explode(' ',$profile->to_date);
									$val = $value[1];
								}
								?>
								<?php
								echo form_dropdown('to_year',$years,$val,'class = "select-picker"');
								?>
							</div>
							</div>
						</div>
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
	<div class="scroll-height">
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
						<label><?php echo get_languageword('Company_Name');?>:</label>
						<?php
						$val = $education->company;				
						?>
						<input type="text" class="form-control" name="company" id="company" value="<?php echo $val;?>" disabled>
					</div>
				</div>

				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('role');?>:</label>
						<?php
						$val = $education->role;				
						?>
						<input type="text" class="form-control" name="role" id="role" value="<?php echo $val;?>" disabled>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo get_languageword('description');?>:</label>
						<?php
						$val = $education->description;				
						?>
						<textarea name="description" id="description" class="form-control" rows="6" cols="35" disabled><?php echo $val;?></textarea>
						
					</div>
				</div>

				<div class="col-sm-6 ">
					
						<div class="row">
							<div class="col-sm-6 pad-right0">
							<div class="input-group ">
								<label><?php echo get_languageword('From');?>:</label>
									<div class="dark-picker dark-picker-bright pickr-full">						
										<?php
											$val = array();
											$val = explode(' ', $education->from_date);
											$value = $val[0];
										?>
										<?php
											echo form_dropdown('from_month',$months,$value,'class = "select-picker" disabled');
										?>
									</div>
								</div>
							</div>	
							<div class="col-sm-6">
								<div class="input-group ">
								<label>&nbsp;</label>
								<div class="dark-picker dark-picker-bright pickr-full">						
									<?php
										$val = array();
										$val = explode(' ', $education->from_date);
										$value = $val[1];
									?>
									<?php
										echo form_dropdown('from_year',$years,$value,'class = "select-picker" disabled');
									?>
								</div>
							</div>
							</div>
						</div>
					
				</div>	

				<div class="col-sm-6 ">
						<div class="row">
							<div class="col-sm-6 pad-right0">

					<div class="input-group ">
								<label><?php echo get_languageword('To');?>:</label>
									<div class="dark-picker dark-picker-bright pickr-full">						
										<?php
											$val = array();
											$val = explode(' ', $education->to_date);
											$value = $val[0];			
											?>
											<?php
											echo form_dropdown('to_month',$months,$value,'class = "select-picker" disabled');
										?>
									</div>
								</div>
							</div>
							<?php if($value != 'Present') { ?>	
							<div class="col-sm-6">
							<div class="input-group ">
								<label>&nbsp;</label>
									<div class="dark-picker dark-picker-bright pickr-full">	
										
									<?php
										$val = array();
										$val = explode(' ', $education->to_date);
										$value = $val[1];				
										?>
										<?php
										echo form_dropdown('to_year',$years,$value,'class = "select-picker" disabled');
									?>
								</div>
							</div>
							</div>
							<?php } ?>
						</div>
					
				</div>	
				
			</div>
		</div>
		<div class="delete"><a class="btn-delete" href="<?php echo base_url();?>tutor/experience/delete/<?php echo $education->record_id?>" onclick="return confirm('<?php echo get_languageword('are you sure?');?>')"><i class="fa  fa-minus"></i> &nbsp;<?php echo get_languageword('delete');?></a> &nbsp; <a class="btn-edit" href="<?php echo base_url();?>tutor/experience/edit/<?php echo $education->record_id?>"><i class="fa  fa-edit"></i> &nbsp;<?php echo get_languageword('edit');?></a></div>
			<?php }} ?>
	</div>

</div>
<!-- Dashboard panel ends -->
