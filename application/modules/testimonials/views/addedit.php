<!--Inner Dashboard Sub Menu-->
<?php $this->load->view('navigation');?>
<!--Inner Dashboard Sub Menu--> 

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
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'package_name' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->package_name;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'package_name',
						'id'	=>	'package_name',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('package_name')?><font color="red">*</font></label>
                </div>
              </div>
			  
              <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'subscription_duration_in_days' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->subscription_duration_in_days;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'subscription_duration_in_days',
						'id'	=>	'subscription_duration_in_days',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element);
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('subscription_duration_in_days')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'package_cost' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->package_cost;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'package_cost',
						'id'	=>	'package_cost',
						'value'	=>	$val,
						'required' => 'required'
						 
					);			
					echo form_input($element);
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('package_cost')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'no_of_quotes' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->no_of_quotes;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'no_of_quotes',
						'id'	=>	'no_of_quotes',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element);
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('no_of_quotes')?><font color="red">*</font></label>
                </div>
              </div>
			  	  
			  <div class="form-group">
                <div class="group">
                  <?php
				  if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'package_status' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->package_status;
					}
					else
					{
						$val = '';
					}
				  $options = activeinactive();
				  echo form_dropdown('package_status', $options, $val, 'required="required"');
				  ?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('package_status')?></label>
                </div>
              </div>
			  
              <div class="form-group">
                <div class="fileType"> <label ><?php echo get_languageword('package_image')?></label>                
                  <input id="image" name="image" class="file" type="file" placeholder="Upload File"  data-min-file-count="3">
				  <?php			   
					$image = '';
					if( isset($details) &&  count($details) > 0)
					{
						$image = $details[0]->image;
					}
					if(!empty($image))
					echo '<img src="'.URL_PUBLIC_UPLOADS_PACKAGES . $image.'" alt="'.$details[0]->image.'">';
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
            
               <button type="button" class="digi-defult-btn digi-defult-btn" value="Submit" onclick="document.location='<?php echo URL_PACKAGE_VIEWPACKAGES;?>'">
           <i class="flaticon-round73"></i> <?php echo get_languageword('cancel')?> 
            </button>
            </div>
			<input type="hidden" name="id" value="<?php echo $id;?>">
              
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
  
<script src="<?php echo URL_ADMIN_JS; ?>jquery-1.11.1.min.js" type="text/javascript"></script>