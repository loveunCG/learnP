<!--Start Breadcrumb-->  
  <ul class="breadcrumb breadCrumb">
<li><a href="<?php echo URL_ADMIN_INDEX;?>"> <i class="fa fa-home"></i> <?php echo get_languageword('home')?></a></li>
    <li><a href="<?php echo URL_PAGES?>"><?php echo get_languageword('pages')?></a></li>
  </ul>
  <!--End Breadcrumb--> 
<div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="dashboardMenu clearfix">
          <ul>
            <li <?php if(isset($activesubmenu) && in_array($activesubmenu, array('view'))) echo 'class="active"';?>>
              <a href="<?php echo URL_PAGES;?>"><div class="cir"><i class="flaticon-view24"></i></div>
              <h2><?php echo get_languageword('view_pages')?> <br>
         </h2></a>
            </li>
                     
          </ul>
        </div>
      </div>
    </div>
  </div>