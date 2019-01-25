<div class="panel-group dashboard-menu" id="accordion">
<div class="dashboard-profile">
	<?php $user_id = $this->ion_auth->get_user_id(); ?>
	<div class="media media-team">
		<a href="<?php echo URL_INSTITUTE_INDEX ?>">
			<figure class="imghvr-zoom-in">
				<img class="media-object  img-circle" src="<?php echo get_tutor_img($my_profile->photo, $my_profile->gender);?>" alt="<?php echo $my_profile->first_name;?> <?php echo $my_profile->last_name;?>">
				<figcaption></figcaption>
			</figure>
			<h4><?php echo $my_profile->first_name;?> <?php echo $my_profile->last_name;?></h4>
			<p><?php echo get_languageword('User Login');?>: <?php echo date('d/m/Y H:i:s',$my_profile->last_login );?></p>
			<p><?php echo get_languageword('net_credits');?>: <strong><?php echo get_user_credits($user_id);?></strong><span class="pull-right"><?php echo get_languageword('per_credit_value');?>: <strong><?php echo get_system_settings('currency_symbol').get_system_settings('per_credit_value');?></strong></span></p>
		</a>
	</div>
</div>
 <div class="dashboard-menu-panel">
	<div class="dashboard-link"><a <?php if(isset($activemenu) && $activemenu == 'dashboard') echo 'class="active"';?> href="<?php echo base_url();?>institute/index"><i class="fa fa-tachometer"></i><?php echo get_languageword('Dashboard');?></a></div>


	<div class="dashboard-link"><a <?php if(isset($activemenu) && $activemenu == 'enrolled_students') echo 'class="active"';?> href="<?php echo URL_INSTITUTE_ENROLLED_STUDENTS ?>"><i class="fa fa-list-alt"></i><?php echo get_languageword('Enrolled_students_list');?></a></div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseManage">
			<i class="fa fa-cog"></i><?php echo get_languageword('Manage')?>
		</a>
	</h4>
	</div>
	<!--/.panel-heading -->
	<div id="collapseManage" class="panel-collapse <?php if(isset($activemenu) && $activemenu == 'manage') echo 'collapse in'; else echo 'collapse';?>">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'manage_courses') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_OFFERED_COURSES?>"><?php echo get_languageword('courses');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'manage_locations') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_LOCATIONS ?>"><?php echo get_languageword('Locations');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'manage_teaching_types') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_TEACHING_TYPES?>"><?php echo get_languageword('Teaching Type');?></a></li>
				<li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('add', 'edit', 'list', 'read', 'success', 'delete'))) echo 'class="active"';?>><a href="<?php echo URL_AUTH_INDEX; ?>/3"><?php echo get_languageword('Tutors');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'manage_batches') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_BATCHES?>"><?php echo get_languageword('Batches');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'certificates') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_CERTIFICATES ?>"><?php echo get_languageword('Certificates');?></a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'manage_privacy') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_MANAGE_PRIVACY; ?>"><?php echo get_languageword('Privacy');?></a></li>
				
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
		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePackages">
			<i class="fa fa-archive"></i><?php echo get_languageword('Packages')?>
		</a>
	</h4>
	</div>
	<!--/.panel-heading -->
	<div id="collapsePackages" class="panel-collapse <?php if(isset($activemenu) && $activemenu == 'Packages') echo 'collapse in'; else echo 'collapse';?>">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'list_packages') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_LIST_PACKAGES;?>"><?php echo get_languageword('List Packages');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'mysubscriptions') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_SUBSCRIPTIONS?>"><?php echo get_languageword('My Subscriptions');?> </a></li>
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
		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseMoneyConversinReqest">
			<i class="fa fa-money"></i><?php echo get_languageword('Money_Conversion')?><span class="hidden-xs"> <?php echo get_languageword('Request')?> </span>
		</a>
	</h4>
	</div>
	<!--/.panel-heading -->
	<div id="collapseMoneyConversinReqest" class="panel-collapse <?php if(isset($activemenu) && $activemenu == 'moneyconversion-institute') echo 'collapse in'; else echo 'collapse';?>">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'Pending') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_MONEY_CONVERSION_REQUEST;?>/Pending"><?php echo get_languageword('pending');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'Done') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_MONEY_CONVERSION_REQUEST; ?>/Done"><?php echo get_languageword('Done');?> </a></li>
				
			</ul>
		</div>
		<!--/.panel-body -->
	</div>
	<!--/.panel-collapse -->
</div>

<div class="dashboard-link"><a <?php if(isset($activemenu) && $activemenu == 'user_credit_transactions') echo 'class="active"';?> href="<?php echo URL_INSTITUTE_CREDITS_TRANSACTION_HISTORY ?>"><i class="fa fa-money"></i><?php echo get_languageword('credits_Transaction');?><span class="hidden-xs"> <?php echo get_languageword('History')?> </span></a></div>


<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
		<a class="<?php if(isset($activemenu) && $activemenu == 'account') echo ''; else echo 'collapsed';?>" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
			<i class="fa fa-user"></i><?php echo get_languageword('Account');?>
		</a>
	</h4>
	</div>
	<!--/.panel-heading -->
	<div id="collapseThree" class="panel-collapse <?php if(isset($activemenu) && $activemenu == 'account') echo 'collapse in'; else echo 'collapse';?>">
		<div class="panel-body">
			<ul class="dashboard-links">
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'personal_info') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_PERSONAL_INFO ?>"><?php echo get_languageword('Personnel Information')?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'profile_information') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_PROFILE_INFO ?>"><?php echo get_languageword('Profile Information');?> </a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'update_contact_info') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_CONTACT_INFO?>"><?php echo get_languageword('Contact Information');?></a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'gallery') echo 'class="active"';?>><a href="<?php echo URL_INSTITUTE_MY_GALLARY?>"><?php echo get_languageword('Gallery');?></a></li>
				<li <?php if(isset($activesubmenu) && $activesubmenu == 'change_password') echo 'class="active"';?>><a href="<?php echo base_url();?>auth/change-password"><?php echo get_languageword('Change Password');?></a></li>
			</ul> 
		</div>
		<!--/.panel-body -->
	</div>
	<!--/.panel-collapse -->
</div>
<!-- /.panel -->
<div class="panel panel-default">
	<div class="dashboard-link"><a href="<?php echo base_url();?>auth/logout"><i class="fa fa-sign-out"></i><?php echo get_languageword('Logout');?></a>
	</div>
</div>
</div>

</div>