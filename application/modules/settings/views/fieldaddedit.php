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
            <div class="panel-heading main_small_heding"><?php echo get_languageword('add_setting_field')?>
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
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'field_name' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->field_name;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'field_name',
						'id'	=>	'field_name',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('field_name')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'field_output_value' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->field_output_value;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'field_output_value',
						'id'	=>	'field_output_value',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('Value')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'type_id' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->type_id;
					}
					else
					{
						$val = '';
					}					
					$element = array();					
					foreach($types as $key => $value)
					{
						if($value->parent_id == 0)
						$element[$value->type_id.'_'.$value->parent_id] = $value->child;
					else
						$element[$value->type_id.'_'.$value->parent_id] = $value->parent . '->' . $value->child;
					}
					echo form_dropdown('type_id', $element, $val, 'class="chzn-select"');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('type')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'is_required' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->is_required;
					}
					else
					{
						$val = '';
					}					
					$element = noyes();			
					echo form_dropdown('is_required', $element, $val, 'class="chzn-select"');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('is_required')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'field_type' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->field_type;
					}
					else
					{
						$val = '';
					}					
					$element = array('text' => get_languageword('text'), 'email' => get_languageword('email'), 'textarea' => get_languageword('text_area'), 'select' => get_languageword('select'), 'file' => get_languageword('file'), 'default_language' => get_languageword('default_language'));			
					echo form_dropdown('field_type', $element, $val, 'id="field_type" onchange="showhide()" class="chzn-select"');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('field_type')?><font color="red">*</font></label>
                </div>
              </div>
			  			  
			  <div class="form-group" id="selectlist">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'field_type_values' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->field_type_values;
					}
					else
					{
						$val = '';
					}					
					$element = array(
						'name'	=>	'field_type_values',
						'id'	=>	'field_type_values',
						'value'	=>	$val,
					);			
					echo form_textarea($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('values')?><?php echo get_languageword('enter_values_with_comma_separated')?><font color="red">*</font></label>
                </div>
              </div>
                        
            
              
			<div class="form-group text-right">
				<button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
				 <i class="fa fa-send"></i> <?php echo get_languageword('submit')?> 
				</button>

				<button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="addnew">
			 <i class="fa fa-send"></i> <?php echo get_languageword('add__new')?> 
				</button>

				<button type="button" class="digi-defult-btn digi-defult-btn" value="Submit" onclick="document.location='<?php echo URL_SETTINGS_FIELDS;?>'">
			 <i class="flaticon-round73"></i> <?php echo get_languageword('cancel')?> 
				</button>
			</div>
			<input type="hidden" name="id" value="<?php echo $id;?>">
              
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