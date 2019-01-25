<div class="dashboard-panel">
	<?php echo $message;?>
	<div class="row">

		<?php
		$attributes = array('name' => 'initiate_batch_session_form', 'id' => 'initiate_batch_session_form', 'class' => 'comment-form dark-fields');
		echo form_open(URL_TUTOR_INITIATE_BATCH_SESSION, $attributes);?>

			<div class="col-sm-12 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Description');?>:</label>
					<?php			   

					$val = set_value('status_desc', (!empty($status_desc)) ? $status_desc : '');

					$element = array(
						'name'	=>	'status_desc',
						'id'	=>	'status_desc',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('Write any information to students like if session is oline, provide any link/URL details'),
					);
					echo form_textarea($element);
					?>
				</div>
			</div>
			<?php echo form_hidden('course_id', $course_id);
				  echo form_hidden('batch_id', $batch_id);
			?>
			<div class="col-sm-12 ">
				<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('Initiate Session');?></button>
			</div>

		</form>
	</div>

</div>
<!-- Dashboard panel ends -->