<div class="panel-group dashboard-menu" id="accordion">
<div class="dashboard-profile">
	<?php 
		  $user_id = $this->ion_auth->get_user_id();

	?>
	<div class="media media-team">
		<a href="<?php echo base_url();?>student/index">
			<figure class="imghvr-zoom-in">
				<img class="media-object  img-circle" src="<?php echo get_student_img($my_profile->photo, $my_profile->gender); ?>" alt="<?php echo $my_profile->first_name;?> <?php echo $my_profile->last_name;?>">
				<figcaption></figcaption>
			</figure>
			<h4><?php echo $my_profile->first_name;?> <?php echo $my_profile->last_name;?></h4>
			<p><?php echo get_languageword('User Login');?>: <?php echo date('d/m/Y H:i:s',$my_profile->last_login );?></p>
			<p><?php echo get_languageword('net_credits');?>: <strong><?php echo get_user_credits($user_id);?></strong>

                <span class="pull-right"><?php echo get_languageword('per_credit_value');?>: <strong><?php echo get_system_settings('currency_symbol').get_system_settings('per_credit_value');?></strong></span></p>
		</a>
	</div>
</div>
<div class="dashboard-menu-panel">
<div class="dashboard-link"><a <?php if(isset($activemenu) && $activemenu == 'dashboard') echo 'class="active"';?> href="<?php echo URL_STUDENT_INDEX ?>"><i class="fa fa-tachometer"></i><?php echo get_languageword('Dashboard');?></a></div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
			<i class="fa fa-search"></i><?php echo get_languageword('Bookings');?>
		</a>
	</h4>
	</div>
	<!--/.panel-heading -->
	<div id="collapseOne" class="panel-collapse <?php if(isset($activemenu) && $activemenu == 'enquiries') echo 'collapse in'; else echo 'collapse';?>">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'all') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_ENQUIRIES;?>"><?php echo get_languageword('All')?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == get_languageword('pending')) echo 'class="active"';?>><a href="<?php echo URL_STUDENT_ENQUIRIES;?>/pending"><?php echo get_languageword('pending'); ?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == get_languageword('approved')) echo 'class="active"';?>><a href="<?php echo URL_STUDENT_ENQUIRIES;?>/approved"><?php echo get_languageword('approved'); ?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == get_languageword('session_initiated')) echo 'class="active"';?>><a href="<?php echo URL_STUDENT_ENQUIRIES;?>/session_initiated"><?php echo get_languageword('session_initiated'); ?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == get_languageword('running')) echo 'class="active"';?>><a href="<?php echo URL_STUDENT_ENQUIRIES;?>/running"><?php echo get_languageword('running'); ?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == get_languageword('completed')) echo 'class="active"';?>><a href="<?php echo URL_STUDENT_ENQUIRIES;?>/completed"><?php echo get_languageword('completed'); ?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == get_languageword('called_for_admin_intervention')) echo 'class="active"';?>><a href="<?php echo URL_STUDENT_ENQUIRIES;?>/called_for_admin_intervention"><?php echo get_languageword('claim_for_admin_intervention'); ?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == get_languageword('closed')) echo 'class="active"';?>><a href="<?php echo URL_STUDENT_ENQUIRIES;?>/closed"><?php echo get_languageword('closed'); ?> </a></li>
			</ul>
		</div>
		<!--/.panel-body -->
	</div>
	<!--/.panel-collapse -->
</div>
<!-- /.panel -->

<div class="dashboard-link"><a <?php if(isset($activemenu) && $activemenu == 'enrolled_courses') echo 'class="active"';?> href="<?php echo URL_STUDENT_ENROLLED_COURSES ?>"><i class="fa fa-book"></i><?php echo get_languageword('Enrolled Courses');?></a></div>


<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseLeads">
			<i class="fa fa-retweet"></i><?php echo get_languageword('My Leads');?>
		</a>
	</h4>
	</div>
	<!--/.panel-heading -->
	<div id="collapseLeads" class="panel-collapse <?php if(isset($activemenu) && $activemenu == 'myleads') echo 'collapse in'; else echo 'collapse';?>">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'student_leads') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_LEADS?>"><?php echo get_languageword('Leads');?></a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'post_requirement') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_REQUIREMENTS ?>"><?php echo get_languageword('Post Requirement');?></a></li>
			</ul>
		</div>
		<!--/.panel-body -->
	</div>
	<!--/.panel-collapse -->
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePackages">
			<i class="fa fa-archive"></i><?php echo get_languageword('Packages')?>
		</a>
	</h4>
	</div>
	<!--/.panel-heading -->
	<div id="collapsePackages" class="panel-collapse <?php if(isset($activemenu) && $activemenu == 'packages') echo 'collapse in'; else echo 'collapse';?>">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'list_packages') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_LIST_PACKAGES ?>"><?php echo get_languageword('List Packages');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'mysubscriptions') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_SUBSCRIPTIONS ?>"><?php echo get_languageword('My Subscriptions');?> </a></li>
			</ul>
		</div>
		<!--/.panel-body -->
	</div>
	<!--/.panel-collapse -->
</div>
<!-- /.panel -->


<div class="dashboard-link"><a <?php if(isset($activemenu) && $activemenu == 'my_course_purchases') echo 'class="active"';?> href="<?php echo URL_STUDENT_COURSE_PURCHASES; ?>"><i class="fa fa-book"></i><?php echo get_languageword('My_Course_Purchases');?></a></div>




<div class="dashboard-link"><a <?php if(isset($activemenu) && $activemenu == 'user_credit_transactions') echo 'class="active"';?> href="<?php echo URL_STUDENT_CREDITS_TRANSACTION_HISTORY;?>"><i class="fa fa-calendar"></i><?php echo get_languageword('credits_Transactions');?><span class="hidden-xs"> <?php echo get_languageword('History')?> </span></a></div>

<!-- /.panel -->
<div class="panel panel-default">

	<!--/.panel-heading -->
	<div id="collapse-Payment" class="panel-collapse collapse">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li><a href="<?php echo base_url();?>student/personal-info">Personnel Information </a></li>
				<li><a href="<?php echo base_url();?>student/profile-information">Profile Information </a></li>
				<li><a href="<?php echo base_url();?>student/update-contact-info">Contact Information</a></li>
				<li><a href="<?php echo base_url();?>student/gellery">Gallery</a></li>
			</ul>
		</div>
		<!--/.panel-body -->
	</div>
	<!--/.panel-collapse -->
</div>
<!-- /.panel -->

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseManage">
			<i class="fa fa-credit-card"></i><?php echo get_languageword('Manage')?>
		</a>
	</h4>
	</div>
	<!--/.panel-heading -->
	<div id="collapseManage" class="panel-collapse <?php if(isset($activemenu) && $activemenu == 'manage') echo 'collapse in'; else echo 'collapse';?>">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'manage_courses') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_MANAGE_COURSES;?>"><?php echo get_languageword('courses');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'manage_locations') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_MANAGE_LOCATIONS ?>"><?php echo get_languageword('Locations');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'manage_teaching_types') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_PREFFERED_TEACHING_TYPES ?>"><?php echo get_languageword('Preffered Teaching Type');?></a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'certificates') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_CERTIFICATES ?>"><?php echo get_languageword('Certificates');?></a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'manage_privacy') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_MANAGE_PRIVACY ?>"><?php echo get_languageword('Privacy');?></a></li>
			</ul>
		</div>
		<!--/.panel-body -->
	</div>
	<!--/.panel-collapse -->
</div>
<!-- /.panel -->

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
		<a class="<?php if(isset($activemenu) && $activemenu == 'account') echo ''; else echo 'collapsed';?>" data-toggle="collapse" data-parent="#accordion" href="#collapse-Account">
			<i class="fa fa-user"></i><?php echo get_languageword('Account');?>
		</a>
	</h4>
	</div>
	<!--/.panel-heading -->
	<div id="collapse-Account" class="panel-collapse <?php if(isset($activemenu) && $activemenu == 'account') echo 'collapse in'; else echo 'collapse';?>">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'personal_info') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_PERSONAL_INFO ?>"><?php echo get_languageword('Personnel Information')?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'profile_information') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_PROFILE_INFO ?>"><?php echo get_languageword('Profile Information');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'update_contact_info') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_CONTACT_INFO ?>"><?php echo get_languageword('My Address');?></a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'gallery') echo 'class="active"';?>><a href="<?php echo URL_STUDENT_MY_GALLARY ?>"><?php echo get_languageword('Gallery');?></a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'change_password') echo 'class="active"';?>><a href="<?php echo URL_AUTH_CHANGE_PASSWORD ?>"><?php echo get_languageword('Change Password');?></a></li>
			</ul>
		</div>
		<!--/.panel-body -->
	</div>
	<!--/.panel-collapse -->
	<div class="dashboard-link"><a href="<?php echo base_url();?>auth/logout"><i class="fa fa-sign-out"></i><?php echo get_languageword('Logout');?></a></div>
</div>
<!-- /.panel -->
</div>
</div>