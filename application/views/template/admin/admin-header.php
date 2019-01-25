<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">	
	<base href="<?php echo base_url();?>" />
	<link rel="shortcut icon" href="<?php if(isset($this->config->item('site_settings')->favicon) && file_exists(URL_PUBLIC_UPLOADS . 'settings/thumbs/'.$this->config->item('site_settings')->favicon)) echo URL_PUBLIC_UPLOADS2.'settings/thumbs/'.''.$this->config->item('site_settings')->favicon; else echo URL_FRONT_IMAGES.'favicon.ico';?>"/>

	<title><?php if(isset($pagetitle)) echo $pagetitle .'-'. $this->config->item('site_settings')->site_title ;?></title>

	<?php 
	if(isset($grocery) && $grocery == TRUE) 
	{
	?>
		<?php 
		foreach($css_files as $file): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
		<?php endforeach; ?>
	<?php
	}?>
	
	<link href='<?php echo URL_ADMIN_CSS;?>adminlte.min.css' rel='stylesheet' media='screen'>
	<link href='<?php echo URL_ADMIN_CSS;?>lib.min.css' rel='stylesheet' media='screen'>
	<link href='<?php echo URL_ADMIN_CSS;?>app.min.css' rel='stylesheet' media='screen'>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<?php if(!empty($activemenu) && $activemenu == "tutor_selling_courses") { ?>
		<link href='<?php echo URL_FRONT_CSS;?>magnific-popup.css' rel='stylesheet' media='screen'>
	<?php } ?>


</head>
<body class="skin-red"><div class="wrapper">

	<header class="main-header">
	<a href="<?php echo base_url();?>"><img src="<?php  if(isset($this->config->item('site_settings')->logo) && $this->config->item('site_settings')->logo != '') echo URL_PUBLIC_UPLOADS_SETTINGS.''.$this->config->item('site_settings')->logo; else echo URL_FRONT_IMAGES.'Logo.png';?>" class="logo <?php if($this->ion_auth->logged_in() && !empty($my_profile)) echo "dahboard-logo"; ?>" alt="logo"></a>
	<nav class="navbar navbar-static-top" role="navigation">
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="hidden-xs"><?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name');?></span>
					</a>
					<ul class="dropdown-menu">
						<li>
                            <a href="<?php echo URL_AUTH_PROFILE;?>"><i class="fa fa-user"></i> <?php echo get_languageword('profile');?></a>
						</li>
						<li>
							<a href="<?php echo URL_AUTH_LOGOUT;?>"><i class="fa  fa-power-off"></i> <?php echo get_languageword('Sign out');?></a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>
		<aside class="main-sidebar">
		<section class="sidebar">
			<!--
			<div class="user-panel" style="height:65px">
				<div class="pull-left info" style="left:5px">
					<p>Webmaster</p>
					<a href="panel/account"><i class="fa fa-circle text-success"></i> Online</a>
				</div>
			</div>-->
			<?php 
			//neatPrint($this->session->all_userdata());
			$this->load->view('template/admin/admin-navigation');?>								</section>
	</aside>

		<div class="content-wrapper">
		<section class="content-header">
			<h1><?php if(isset($pagetitle)) echo $pagetitle; elseif(isset($maintitle)) echo $maintitle; else echo 'Title';?></h1>
			<ol class="breadcrumb">
	<li class=''><a href='<?php echo base_url();?>admin/index'><?php echo get_languageword('home')?></a></li>
	<?php 
	$pagetitle_active = '';
	$maintitle_active = 'active';
	if(isset($pagetitle) && isset($maintitle)) //If both are set means we are in submenu
		$pagetitle_active = 'active';
	?>
	
	<?php if(isset($maintitle)) { ?>
	<li class='<?php echo $maintitle_active;?>'>
	<?php if(isset($maintitle_link)) {
		echo '<a href="'.$maintitle_link.'">'.$maintitle.'</a>';
	} else {  echo $maintitle; }
	?></li>
	<?php } ?>
	
	<?php
	if(isset($pagetitle)) { ?>
	<li class='<?php echo $pagetitle_active;?>'><?php echo $pagetitle;?></li>
	<?php } ?>
	
	</ol>		</section>
		<section class="content">