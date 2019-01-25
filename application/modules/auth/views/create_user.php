<!--Inner Dashboard Sub Menu-->
<?php $this->load->view('navigation');?>
<!--Inner Dashboard Sub Menu--> 

  <!-- Elements Of Web Site -->
  <div class="container-fluid">
    <div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform', 'enctype' => 'multipart/form-data');
		echo form_open('',$attributes) ?>
	  <div class="col-lg-12">
        <div class="elements">
          <div class="panel panel-default theameOfPanle">
            <div class="panel-heading main_small_heding"><?php echo isset($pagetitle) ? $pagetitle : 'No Title'?>
              <?php if(isset($helptext) && count($helptext) > 0) {?>
			  <!--Help--> 
               <div class="btn digi-trash digi-remove pull-right help" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">  <img src="<?php echo URL_ADMIN_IMAGES;?>help.png"> </div>
              <!-- Help -->
			  <?php } ?>
              
            </div>
            <div class="panel-body">
 <div class="col-lg-6">			
              <?php if(isset($helptext) && count($helptext) > 0) {?>
			  <!--Help Collapse-->
              <div class="collapse" id="collapseExample">
                <div class="well help_coll">
                  <ul>
                    <?php foreach($helptext as $helpmessage) {?>
					<li><span class="glyphicon glyphicon-ok-circle"></span> <?php echo $helpmessage;?> </li>
					<?php } ?>                    
                  </ul>
                </div>
              </div>
              <!--Help Collapse-->
			  <?php } ?>
              <!--Input Text Feilds-->
              <div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"'; ?>><?php echo $message;?></div>
              <h2 class="devideHeader"><?php echo get_languageword('basic_details')?></h2>
			  
              <div class="form-group">
                <div class="group">
                  <?php echo form_input($first_name);?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('first_name')?><font color="red">*</font></label>
                </div>
              </div>
              <div class="form-group">
                <div class="group">
                  <?php echo form_input($last_name);?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('last_name')?><font color="red">*</font></label>
                </div>
              </div>
			  
			<?php if($identity_column !== 'email') { ?>
			  <div class="form-group">
                <div class="group">
                  <?php echo form_input($identity); ?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('user_name')?><font color="red">*</font></label>
                </div>				
              </div>
			<?php } ?>
			  
			  <div class="form-group">
                <div class="group">
                  <?php echo form_input($email);?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('email');?><font color="red">*</font></label>
                </div>
              </div>
	 
			  <div class="form-group">
                <div class="group">
                  <?php echo form_input($phone);?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('phone')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php echo form_input($password);?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('password')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php echo form_input($password_confirm);?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('confirm_password')?><font color="red">*</font></label>
                </div>
              </div>
			  
              <div class="form-group">
                <div class="fileType"> <label ><?php echo get_languageword('profile_image')?></label>                
                  <input id="profile_image" name="profile_image" class="file" type="file" placeholder="Upload File"  data-min-file-count="3">
				  <?php			   
					/*
					$image = '';
					if( isset($details) &&  count($details) > 0)
					{
						$image = $details[0]->profile_image;
					}
					if(!empty($image))
					echo '<img src="'.URL_PUBLIC_UPLOADS.'thumbs/'.$image.'" alt="'.$details[0]->profile_image.'">';
				*/
					?>
                </div>
              </div>
			  
			          
            
              
        <div class="form-group text-right">
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
            <i class="fa fa-send"></i> <?php echo get_languageword('submit')?>
            </button>
			
			<button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="addnew">
           <i class="fa fa-send"></i> <?php echo get_languageword('add__new')?>
            </button>
            
               <button type="button" class="digi-defult-btn digi-defult-btn" value="Submit" onclick="document.location='<?php echo URL_AUTH_INDEX;?>'">
            <i class="flaticon-round73"></i> <?php echo get_languageword('cancel')?> 
            </button>
            </div>
			              
              <!--Input Text Feilds--> 
              </div> 
            </div>
          </div>
        </div>
      </div>
	  </form>
      
      <!--STATISTICS-->
      <?php if($showstatistics) $this->load->view('statistics');?>
      <!--STATISTICS--> 
      
    </div>
  </div>