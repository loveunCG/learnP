<div class="dashboard-panel">
	<?php echo $message;?>
	<div class="row">

		<?php
		$attributes = array('name' => 'course_completed_for_batch_students_form', 'id' => 'course_completed_for_batch_students_form', 'class' => 'comment-form dark-fields');
		echo form_open(URL_TUTOR_COMPLETE_BATCH_SESSION, $attributes);?>

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
				<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php echo get_languageword('Update As Course Completed');?></button>
			</div>

		</form>
	</div>

</div>
<!-- Dashboard panel ends -->