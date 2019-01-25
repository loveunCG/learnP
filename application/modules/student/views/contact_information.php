<!-- Dashboard panel -->
<div class="dashboard-panel">

	<div class="dashboard-list">
		<div class="update-profile">
			<div><a href="<?php echo base_url()?>student/profile-information"><?php echo get_languageword('View Public Profile');?> <i class="fa fa-angle-double-right"></i></a></div>
			<div><a href="<?php echo base_url()?>student/update-contact-information"><i class="fa  fa-pencil-square-o"></i> &nbsp;<?php echo get_languageword('Update Info');?></a></div>
		</div>
		<h4><?php echo get_languageword('Address');?>:</h4>
		<p><?php if($profile->city != '')  echo $profile->city.',<br>';?><?php if($profile->land_mark != '') echo $profile->land_mark.',<br>'?><?php if($profile->country != '') echo $profile->country .',<br>';?><?php if($profile->pin_code != '') echo $profile->pin_code;?>.</p>
		<h4 class="mtop2"><?php echo get_languageword('Contact Information')?>:</h4>
		<p><strong><?php echo get_languageword('Mobile');?>: </strong> <?php if($profile->phone_code != '') echo '+'.$profile->phone_code; if($profile->phone != '') echo ' - '.$profile->phone;?></p>
		<p><strong><?php echo get_languageword('Email');?>: </strong> <?php if($profile->email != '') echo $profile->email;?> (Verified)</p>
	</div>

</div>
<!-- Dashboard panel ends -->