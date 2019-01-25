<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<?php 
	$attributes = array('name' => 'profile_form', 'id' => 'profile_form', 'class' => 'comment-form dark-fields');
	echo form_open_multipart('tutor/certificates',$attributes);?>
		<div class="row">
			
			<?php 
			if(!empty($certificates)) 
			{ 
				foreach($certificates as $certificate)
				{
				?>
				<div class="col-sm-6 ">
					<div class="input-group ">
						<label><?php echo $certificate->title;?></label>
						<?php			   
						$val = '';
						if($certificate->required == 'Yes' && !in_array($certificate->certificate_id, array_keys($user_uploads_arr))) //It required and not yet uploaded
						{
							$element = array(
							'type' => 'file',
							'name'	=>	'certificate['.$certificate->certificate_id.']',
							'id'	=>	'certificate_'.$certificate->certificate_id,
							'value'	=>	$val,
							'class' => 'form-control',
							'required' => TRUE,
							'onchange' => 'check_file('.$certificate->certificate_id.',\''.$certificate->allowed_formats.'\')',
						);
						}
						else
						{
						$element = array(
							'type' => 'file',
							'name'	=>	'certificate['.$certificate->certificate_id.']',
							'id'	=>	'certificate_'.$certificate->certificate_id,
							'value'	=>	$val,
							'class' => 'form-control',
							'onchange' => 'check_file('.$certificate->certificate_id.',\''.$certificate->allowed_formats.'\')',
						);
						}						
						echo form_input($element);
						?>
						<?php 
						if(in_array($certificate->certificate_id, array_keys($user_uploads_arr)))
						{
						$src = $src_thumb = "";
						$name = $user_uploads_arr[$certificate->certificate_id];
						$ext = pathinfo($name, PATHINFO_EXTENSION);
						if($name != '' && file_exists('assets/uploads/certificates/'.$name)) {
						$src = base_url()."assets/uploads/certificates/".$name;
						$src_thumb = base_url()."assets/uploads/certificates/thumbs/".$name;
						}
						?>
						&nbsp;&nbsp;&nbsp;<a  src="<?php echo $src;?>" target="_blank">
						<?php 
						if(in_array($ext, array('jpg', 'png', 'gif', 'jpeg')))
						{
							echo '<img src="'.$src_thumb.'" alt="'.$name.'">';
						}
						else
						{
						echo get_languageword('View');
						}
						?></a>
						<?php }?>
					</div>
				</div>
				<?php 
				}
			} ?>
			
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Other');?></label>
					<?php			   
					
					$element = array(
						'type' => 'file',
						'name'	=>	'other[]',
						'id'	=>	'certificate_99999',
						'class' => 'form-control',
						'onchange' => 'check_file(99999,\'jpg,jpeg,gif,png\')',
						'multiple' => 'multiple',
					);					
					echo form_input($element);
					?>
				</div>
			</div>
		</div>		
		
		<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('SAVE');?></button>

	</form>

</div>
<!-- Dashboard panel ends -->
<script>
function check_file(id, formats)
{
	if(formats == '')
	{
		formats = 'jpg,gif,png,jpeg';
	}
	var name = document.getElementById('certificate_'+id).value;
	var parts = name.split('.');
	var ext = parts[parts.length-1];
	if(!in_array(ext, formats.split(',')))
	{
		alert('Upload only '+formats+' files only');
		document.getElementById('certificate_'+id).value = '';
		return false;
	}
}
function in_array(needle, haystack, argStrict)
{
	var key = ''
	var strict = !!argStrict

	if (strict)
	{
		for (key in haystack) {
		if (haystack[key] === needle) {
		return true
		}
		}
	} else {
		for (key in haystack) 
		{
			if (haystack[key] == needle) { // eslint-disable-line eqeqeq
			return true
		}
		}
	}
	return false
}
</script>