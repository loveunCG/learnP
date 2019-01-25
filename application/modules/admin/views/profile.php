
  <!-- Elements Of Web Site -->
  <div class="container-fluid">
    <div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform', 'enctype' => 'multipart/form-data');
		echo form_open('',$attributes) ?>
	  <div class="col-lg-12">
        <div class="elements">
          <div class="panel panel-default theameOfPanle">
            <div class="panel-heading main_small_heding"><?php echo isset($pagetitle) ? $pagetitle : 'Profile'?>
            
              
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
						$val = $this->input->post( 'first_name' );
					}
					elseif(isset($details))
					{
						$val = $details[0]->first_name;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'first_name',
						'id'	=>	'first_name',
						'value'	=>	$val,
						'required' => 'required',
						'type'=>'text'
					);			
					echo form_input($element).form_error('first_name');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('first_name')?><font color="red">*</font></label>
                </div>
              </div>
              <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'last_name' );
					}
					elseif(isset($details))
					{
						$val = $details[0]->last_name;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'last_name',
						'id'	=>	'last_name',
						'value'	=>	$val,
						'type'=>'text'
					);			
					echo form_input($element).form_error('last_name');
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('last_name')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'phone_code' );
					}
					elseif(isset($details))
					{
						$val = $details[0]->phone_code;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'phone_code',
						'id'	=>	'phone_code',
						'value'	=>	$val,
						'required' => 'required',
						'type'=>'text'
					);			
					echo form_input($element).form_error('phone_code');
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('code')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'mobile_number' );
					}
					elseif(isset($details))
					{
						$val = $details[0]->mobile_number;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'mobile_number',
						'id'	=>	'mobile_number',
						'value'	=>	$val,
						'required' => 'required',
						'type'=>'text'
					);			
					echo form_input($element).form_error('mobile_number');
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('mobile_number')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'logo_profile_image' );
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'logo_profile_image',
						'id'	=>	'logo_profile_image',
						'value'	=>	$val,
						'type'=>'file'
					);			
					echo form_input($element).form_error('logo_profile_image');
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('profile_pic')?><font color="red">*</font></label>
                </div>
              </div>
   
        <div class="form-group text-right">
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
            <a><i class="fa fa-send"></i> <?php echo get_languageword('submit')?></a>
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