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
            <div class="panel-heading main_small_heding"><?php echo get_languageword('add_categories')?>
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
						$val = $this->input->post( 'type_title' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->type_title;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'type_title',
						'id'	=>	'type_title',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('type_title')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'parent_id' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->parent_id;
					}
					else
					{
						$val = '';
					}
					$element = array('0' => get_languageword('parent'));
					foreach($types as $key => $value)
					$element[$value->type_id] = $value->type_title;		
					echo form_dropdown('parent_id', $element, $val);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('parent')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'is_default' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->is_default;
					}
					else
					{
						$val = '';
					}					
					$element = noyes();			
					echo form_dropdown('is_default', $element, $val);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('is_default')?><font color="red">*</font></label>
                </div>
              </div>
                        
            
              
			<div class="form-group text-right">
				<button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
				 <i class="fa fa-send"></i> <?php echo get_languageword('submit')?> 
				</button>

				<button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="addnew">
			 <i class="fa fa-send"></i> <?php echo get_languageword('add__new')?> 
				</button>

				<button type="button" class="digi-defult-btn digi-defult-btn" value="Submit" onclick="document.location='<?php echo URL_SETTINGS_TYPES;?>'">
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
      <?php //$this->load->view('statistics');?>
      <!--STATISTICS--> 
      
    </div>
  </div>