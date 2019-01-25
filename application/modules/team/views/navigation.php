<!--Start Breadcrumb-->  
  <ul class="breadcrumb breadCrumb">
<li><a href="<?php echo URL_ADMIN_INDEX;?>"> <i class="fa fa-home"></i> <?php echo get_languageword('home')?></a></li>
    <li><a href="<?php echo URL_PACKAGE_VIEWPACKAGES?>"><?php echo get_languageword('packages')?></a></li>
    <li class="active"><?php echo isset($pagetitle) ? $pagetitle :  get_languageword('no_title');?></li>
  </ul>
  <!--End Breadcrumb--> 
<div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="dashboardMenu clearfix">
          <ul>
            <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('view'))) echo 'class="active"';?>>
              <a href="<?php echo URL_PACKAGE_VIEWPACKAGES;?>">  <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword('view_packages')?> <br>
         </h2></a>
            </li>
            <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('add', 'update'))) echo 'class="active"';?>>
            <a href="<?php echo URL_PACKAGE_ADDEDIT;?>">
              <div class="cir"><i class="flaticon-multimediaoption16"></i> </div>
              <h2><?php echo get_languageword('add_package')?> <br>
         </h2>
                </a>
            </li>           
            
          </ul>
        </div>
      </div>
    </div>
  </div>