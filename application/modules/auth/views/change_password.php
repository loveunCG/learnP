<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $message;?>
	<div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform', 'class' => 'comment-form dark-fields');
		echo form_open('auth/change-password',$attributes) ?>
  <div class="col-lg-6">
        <div class="elements">
			   <div class="panel panel-default theameOfPanle">
			      <div class="panel-heading main_small_heding"><?php echo get_languageword('Change Password')?>:</div>
				              <div class="panel-body"> 
				<div class="input-group ">
					<label><?php echo get_languageword('Current Password')?>:</label>
					<?php echo form_input($old_password);?>
					
				</div>

				<div class="input-group ">
					<label><?php echo get_languageword('New Password')?> <small>(<?php echo get_languageword('must be at least')?> <?php echo $min_password_length;?> <?php echo get_languageword('characters')?>)</small>:</label>
					<?php echo form_input($new_password);?>
				</div>

				<div class="input-group ">
					<label><?php echo get_languageword('Confirm Password')?>:</label>
					<?php echo form_input($new_password_confirm);?>
				</div>

				<button class="btn-link-dark dash-btn n-b" name="submitbutt" type="Submit"><?php echo get_languageword('CHANGE PASSWORD')?></button>
				</div>
			</div>
			 </div>
	 </div>
		</form>
	</div>

</div>
<!-- Dashboard panel ends -->
  <?php /*?>
  <!-- Elements Of Web Site -->
  <div class="container-fluid">
    <div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform', 'enctype' => 'multipart/form-data');
		echo form_open('auth/change-password',$attributes) ?>
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
              			  			  
			  <div class="form-group">
                <div class="group">
                  <?php echo form_input($old_password);?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('old_password');?></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php echo form_input($new_password);?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('new_password');?></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php echo form_input($new_password_confirm);?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('confirm_password')?></label>
                </div>
              </div>
			        
              
        <div class="form-group text-right">
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
            <a><i class="fa fa-send"></i> <?php echo get_languageword('submit')?></a>
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
      
      
      
    </div>
  </div>
  <?php */?>