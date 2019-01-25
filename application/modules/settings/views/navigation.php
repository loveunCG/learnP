<!--Start Breadcrumb-->  
  <ul class="breadcrumb breadCrumb">
<li><a href="<?php echo URL_ADMIN_INDEX;?>"> <i class="fa fa-home"></i> <?php echo get_languageword('home')?></a></li>
    <li><a href="<?php echo URL_SETTINGS_INDEX?>"><?php echo get_languageword('settings_module')?></a></li>
    <li class="active"><?php echo isset($pagetitle) ? $pagetitle :  get_languageword('no_title');?></li>
  </ul>
  <!--End Breadcrumb-->
  <?php if(count($menues) > 0) {?>
<div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="dashboardMenu clearfix">
          <ul>
		  <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('view', 'settingtypes'))) echo 'class="active"';?>>
              <a href="<?php echo URL_SETTINGS_INDEX;?>">  <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword('types')?> <br>
         </h2></a>
            </li>
			
			<li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('addsettingtypes'))) echo 'class="active"';?>>
             <a href="<?php echo URL_SETTINGS_TYPEADDEDIT;?>">   <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword('add_setting_types')?><br>
         </h2>    </a>
            </li>
			
			<li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('addsettingfields'))) echo 'class="active"';?>>
             <a href="<?php echo URL_SETTINGS_FIELDADDEDIT;?>">   <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword('add_setting_fields')?><br>
         </h2>    </a>
            </li>
			
			<li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('settingfields'))) echo 'class="active"';?>>
             <a href="<?php echo URL_SETTINGS_FIELDS;?>">   <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword('view_setting_fields')?><br>
         </h2>    </a>
            </li>
		  <?php 
		  foreach($menues as $menu)
		  {
			 $check = $this->base_model->fetch_records_from(TBL_SETTINGS_TYPES, array('parent_id' => $menu->type_id));
			if(count($check) == 0)
				$url = URL_SETTINGS_FIELDSVALUES;
			else
				$url = URL_SETTINGS_SUBTYPES;
			 ?>
			 <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array($menu->type_id))) echo 'class="active"';?>>
              <a href="<?php echo $url;?>/<?php echo $menu->type_id;?>">  <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword(strtolower(str_replace(' ', '_',$menu->type_title)));?><?php //echo $menu->type_title?> <br>
         </h2></a>
            </li>
			 <?php 
		  }
		  /*?>
            
            <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('add', 'update'))) echo 'class="active"';?>>
            <a href="<?php echo URL_SETTINGS_ADDEDIT;?>">
              <div class="cir"><i class="flaticon-multimediaoption16"></i> </div>
              <h2><?php echo strtoupper(get_languageword('sms'));?> <?php echo get_languageword('settings')?> <br>
         </h2>
                </a>
            </li>
            <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('addsub', 'updatesub'))) echo 'class="active"';?>>
            <a href="<?php echo URL_SETTINGS_ADDEDIT;?>">    <div class="cir"><i class="flaticon-multimediaoption16"></i> </div>
              <h2><?php echo get_languageword('site')?> <?php echo strtoupper(get_languageword('seo'));?> <?php echo get_languageword('settings')?><br>
         </h2>    </a>
            </li>
            <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('globalseo'))) echo 'class="active"';?>>
             <a href="<?php echo URL_SETTINGS_ADDEDIT;?>/viewsub">   <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword('global')?> <?php echo strtoupper(get_languageword('seo'));?> <?php echo get_languageword('settings')?><br>
         </h2>    </a>
            </li>
			
			<li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('emailsettings'))) echo 'class="active"';?>>
             <a href="<?php echo URL_SETTINGS_ADDEDIT;?>/viewsub">   <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword('email_settings')?><br>
         </h2>    </a>
            </li>
			
			<li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('settingtypes'))) echo 'class="active"';?>>
             <a href="<?php echo URL_SETTINGS_TYPES;?>">   <div class="cir"><i class="flaticon-view24"></i> </div>
              <h2><?php echo get_languageword('setting_types')?><br>
         </h2>    </a>
            </li>
			
			
			
			
			
			
			<?php */?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>