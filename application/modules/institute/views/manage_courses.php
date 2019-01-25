<!-- Dashboard panel ends -->
<?php echo $this->session->flashdata('message');?>
<div class="dashboard-panel">
<?php 

// neatPrint($subjects);
if(count($courses) > 0) { ?>
<?php echo form_open('institute/courses', 'id="student_subject_mngt" class="form-multi-select"');?>
	<div class="custom_accordion">
		<?php
            foreach($courses as $key=>$val) {
            
            $category = explode('_', $key);

            //Category Details
            $category_id   = $category[0];
            $category_slug = $category[1];
            $category_name = $category[2];

            ?>
		<h3><?php echo $category_name;?></h3>
		<div class="row">
		<?php
		$i = $counter  = 1;
		foreach($val as $key1=>$val1) 
		{
			
			$course   = explode('_', $val1);
            //Course Details
            $course_id   = $course[0];
            $course_slug = $course[1];
            $course_name = $course[2];

			if($i == 1)
			{
			?>
			<div class="col-md-4 col-sm-6">
			<?php
			} 
			
			?>
				<div class="input-group ">
					<div class="checkbox">
						<label>
						<input type="checkbox" value="<?php echo $course_id;?>" name="institute_courses[]" <?php if(in_array($course_id, $instituteOfferedCourseIds)) echo "checked";?>>
							<span class="checkbox-content">
								<span class="item-content"><?php echo $course_name;?></span>
								<i aria-hidden="true" class="fa fa-check "></i>
								<i class="check-square"></i>
							</span>
						</label>
					</div>
				</div>
			<?php
			$i++;
			if($i == 3 || count($val) == $counter) { // three items in a row. Edit this to get more or less items on a row
        echo '</div>';
        $i = 1;
    }
	
	$counter++;
		} ?>
		</div>
	
			<?php }
			?>
			</div>
			<button class="btn-link-dark dash-btn" name="Submit" type="Submit"><?php echo get_languageword('UPDATE');?></button>
</form>
			
			<?php
			} ?>
</div>