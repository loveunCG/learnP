<!--Start Breadcrumb-->  
  <ul class="breadcrumb breadCrumb">
<li><a href="<?php echo URL_ADMIN_INDEX;?>"> <i class="fa fa-home"></i> <?php echo get_languageword('home')?></a></li>
    <li><a href="<?php echo URL_AUTH_INDEX?>"><?php echo get_languageword('users_module')?></a></li>
    <li class="active"><?php echo isset($pagetitle) ? $pagetitle :  get_languageword('no_title');?></li>
  </ul>
  <!--End Breadcrumb--> 
<div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="dashboardMenu clearfix">
          <ul>
            <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('view'))) echo 'class="active"';?>>
              <a href="<?php echo URL_AUTH_INDEX;?>">  <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword('view_users')?> <br>
         </h2></a>
            </li>
			
			<li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('student'))) echo 'class="active"';?>>
            <a href="<?php echo URL_AUTH_INDEX;?>/student">
              <div class="cir"><i class="flaticon-multimediaoption16"></i> </div>
              <h2><?php echo get_languageword('students')?> <br>
         </h2>
                </a>
            </li>
			
			<li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('tutor'))) echo 'class="active"';?>>
            <a href="<?php echo URL_AUTH_INDEX;?>/tutor">
              <div class="cir"><i class="flaticon-multimediaoption16"></i> </div>
              <h2><?php echo get_languageword('tutors')?> <br>
         </h2>
                </a>
            </li>
			
            <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('add', 'update'))) echo 'class="active"';?>>
            <a href="<?php echo URL_AUTH_CREATE_USER;?>">
              <div class="cir"><i class="flaticon-multimediaoption16"></i> </div>
              <h2><?php echo get_languageword('add_user')?> <br>
         </h2>
                </a>
            </li>
            
          </ul>
        </div>
      </div>
    </div>
  </div>