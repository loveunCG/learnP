<!-- Dashboard panel ends -->
<?php echo $this->session->flashdata('message');?>
<div class="dashboard-panel">
<?php 
//neatPrint($subjects);
if(count($locations) > 0) { ?>
<?php echo form_open('institute/locations', 'id="student_subject_mngt" class="form-multi-select"');?>
	<div class="custom_accordion">
		<?php
            foreach($locations as $row=>$val) {
            
            if(count($val) > 0) {
            ?>
		<h3><?php echo $row;?></h3>
		<div class="row">
		<?php
		$i = $counter  = 1;
		foreach($val as $sub) 
		{
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
						<input type="checkbox" value="<?php echo $sub->id?>" name="institute_locations[]" <?php if(in_array($sub->id, $instituteLocationIds)) echo "checked";?>>
							<span class="checkbox-content">
								<span class="item-content"><?php echo $sub->location_name;?></span>
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
	
			<?php }}
			?>
			</div>
			<button class="btn-link-dark dash-btn" name="Submit" type="Submit"><?php echo get_languageword('UPDATE');?></button>
</form>
			
			<?php
			} ?>
</div>