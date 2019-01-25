<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<?php
	if( $type == 'users' ) {
		?>
		Click <a href="<?php echo base_url();?>assets/uploads/import_templates/users.csv">here</a> to download template 
		<?php
	}
	?>
	<?php
	if( $type == 'categories' ) {
		?>
		Click <a href="<?php echo base_url();?>assets/uploads/import_templates/categories.csv">here</a> to download template 
		<?php
	}
	?>
	<?php
	if( $type == 'courses' ) {
		?>
		Click <a href="<?php echo base_url();?>assets/uploads/import_templates/courses.csv">here</a> to download template 
		<?php
	}
	?>
	<?php
	if( $type == 'degree' ) {
		?>
		Click <a href="<?php echo base_url();?>assets/uploads/import_templates/degree.csv">here</a> to download template 
		<?php
	}
	?>
	<?php
	if( $type == 'locations' ) {
		?>
		Click <a href="<?php echo base_url();?>assets/uploads/import_templates/locations.csv">here</a> to download template 
		<?php
	}
	?>
	<?php
	if( $type == 'packages' ) {
		?>
		Click <a href="<?php echo base_url();?>assets/uploads/import_templates/packages.csv">here</a> to download template 
		<?php
	}
	?>
	<?php
	if( $type == 'certificates' ) {
		?>
		Click <a href="<?php echo base_url();?>assets/uploads/import_templates/certificates.csv">here</a> to download template 
		<?php
	}
	?>
	<?php 
	$attributes = array('name' => 'profile_form', 'id' => 'profile_form', 'class' => 'comment-form dark-fields');
	echo form_open_multipart('',$attributes);?>
		<div class="row">		
						
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('file to import');?></label>
					<?php					
					$element = array(
						'type' => 'file',
						'name'	=>	'import_file',
						'id'	=>	'import_file',
						'class' => 'form-control',
						'onchange' => 'check_file(\'import_file\',\'csv\')',
					);					
					echo form_input($element);
					?>
				</div>
			</div>
		</div>		
		<input name="file_type" type="hidden" value="<?php echo $type;?>">
		<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('SAVE');?></button>

	</form>

</div>
<!-- Dashboard panel ends -->
<script>
function check_file(id, formats)
{
	if(formats == '')
	{
		formats = 'csv';
	}
	var name = document.getElementById(id).value;
	var parts = name.split('.');
	var ext = parts[parts.length-1];
	if(!in_array(ext, formats.split(',')))
	{
		alert('Upload only '+formats+' files only');
		document.getElementById(id).value = '';
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