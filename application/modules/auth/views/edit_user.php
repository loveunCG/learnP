<!--Inner Dashboard Sub Menu-->
<?php $this->load->view('navigation');?>
<!--Inner Dashboard Sub Menu--> 

 <script src="http://maps.google.com/maps/api/js?v=3.13&sensor=false&libraries=places" ></script> 
  <!-- Elements Of Web Site -->
  <div class="container-fluid">
    <div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform', 'enctype' => 'multipart/form-data');
		echo form_open('',$attributes) ?>
	  <div class="col-lg-9">
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
                  <label class="digiEffectLabel"><?php echo get_languageword('password');?></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php echo form_input($password_confirm);?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('confirm_password')?></label>
                </div>
              </div>
			  
			  <?php if ($this->ion_auth->is_admin()): ?>
              <div class="form-group">
                <div class="group"><?php echo get_languageword('user_groups')?><br>            
                  <?php foreach ($groups as $group):?>				  
					  <?php
						  $gID=$group['id'];
						  $checked = null;
						  $item = null;
						  foreach($currentGroups as $grp) {
							  if ($gID == $grp->id) {
								  $checked= ' checked="checked"';
							  break;
							  }
						  }
					  ?>
					  <input id="checkbox-<?php echo $grp->id;?>" class="checkbox-custom checkbox_class"  type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
					  <label for="checkbox-<?php echo $group['id'];?>" class="checkbox-custom-label"><?php echo get_languageword(htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8'));?> </label>		  
				  <?php endforeach?>
                </div>
              </div>
			  <?php endif ?>
			  
			 <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>         
              
        <div class="form-group text-right">
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
            <a><i class="fa fa-send"></i> <?php echo get_languageword('submit')?></a>
            </button>
			
			<button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="addnew">
            <a><i class="fa fa-send"></i> <?php echo get_languageword('add__new')?></a>
            </button>
            
               <button type="button" class="digi-defult-btn digi-defult-btn" value="Submit" onclick="document.location='<?php echo URL_AUTH_INDEX;?>'">
            <a><i class="flaticon-round73"></i> <?php echo get_languageword('cancel')?></a>
            </button>
            </div>
			
              
              <!--Input Text Feilds--> 
              
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