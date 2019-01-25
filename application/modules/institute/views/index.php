<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<div class="row">
		
			<div class="col-md-4 pad10">
				<div class="dash-block d-block1">
					<h2><?php echo $my_profile->net_credits;?></h2>
					<p><?php echo get_languageword('Net Credits'); ?></p>
				</div>
			</div>
			
			<div class="col-md-4 pad10">
				<div class="dash-block d-block2">
					<h2><?php echo $inst_dashboard_data['num_of_tutors'];?><a class="pull-right" href="<?php echo base_url();?>auth/index/3"><?php echo get_languageword('View'); ?></a></h2>
					<p><?php echo get_languageword('Total Tutors'); ?></p>
					
				</div>
			</div>
			
			<div class="col-md-4 pad10">
				<div class="dash-block d-block3">
					<h2><?php echo $inst_dashboard_data['batches'];?> <a class="pull-right" href="<?php echo base_url();?>institute/batches"><?php echo get_languageword('View'); ?></a></h2>
					<p><?php echo get_languageword('Total Batches'); ?></p>
				</div>
			</div>
		
		
			<div class="col-md-4 pad10">
				<div class="dash-block d-block4">
					<h2><?php echo $inst_dashboard_data['courses'];?><a class="pull-right" href="<?php echo base_url();?>institute/courses"><?php echo get_languageword('View'); ?></a></h2>
					<p><?php echo get_languageword('Courses Offered'); ?></p>
				</div>
			</div>
	</div>

</div>
<!-- Dashboard panel ends --> 