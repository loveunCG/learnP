  <!-- Elements Of Web Site -->
  <div class="container-fluid">
    <div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform', 'enctype' => 'multipart/form-data');
		echo form_open('',$attributes) ?>
	  <div class="col-lg-12">
        <div class="elements">
          <div class="panel panel-default theameOfPanle">
            <div class="panel-heading main_small_heding"><?php echo isset($pagetitle) ? $pagetitle : 'Change Password'?>
            
              
            </div>
            <div class="panel-body"> 
              <div class="col-lg-6">
              <!--Input Text Feilds-->
              <div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"'; ?>><?php echo $message;?></div>
             
			  
              <div class="form-group">
                <div class="group">
                  <?php			   
					if(( isset($_POST['submitbutt']) && $_POST['submitbutt'] ))
					{
						$val = $this->input->post( 'current_password' );
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'current_password',
						'id'	=>	'current_password',
						'value'	=>	$val,
						'required' => 'required',
						'type'=>'password'
					);			
					echo form_input($element).form_error('current_password');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('current_password')?><font color="red">*</font></label>
                </div>
              </div>
              <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'new_password' );
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'new_password',
						'id'	=>	'new_password',
						'value'	=>	$val,
						'required' => 'required',
						'type'=>'password'
					);			
					echo form_input($element).form_error('new_password');
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('new_password')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'retype_password' );
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'retype_password',
						'id'	=>	'retype_password',
						'value'	=>	$val,
						'required' => 'required',
						'type'=>'password'
					);			
					echo form_input($element).form_error('retype_password');
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('retype_password')?><font color="red">*</font></label>
                </div>
              </div>
			
             
			    
   
        <div class="form-group text-right">
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
            <a href="#"><i class="fa fa-send"></i> <?php echo get_languageword('submit')?></a>
            </button>
        </div>
              <!--Input Text Feilds--> 
          </div>  </div>
          </div>
        </div>
      </div>
	  </form>
    </div>
  </div>