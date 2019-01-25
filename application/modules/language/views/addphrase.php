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
               <div class="btn digi-trash digi-remove pull-right help" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">  <img src="<?php echo IMAGES_PATH;?>help.png"> </div>
              <!-- Help -->
			  <?php } ?>
              
            </div>
            
			<div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"';?>><?php echo $message;?></div>
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
						  
              <div class="form-group">
                <div class="group">
                  <?php			   
					$val = '';
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'lang_key' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->lang_key;
					}
					$element = array(
						'name'	=>	'lang_key',
						'id'	=>	'lang_key',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('key');?><font color="red">*</font></label>
                </div>
              </div>

			<div class="form-group">
      <label><?php echo get_languageword('word');?><font color="red">*</font></label>
                  <?php			   
					$val = '';
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'english' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->english;
					}
					$element = array(
						'name'	=>	'english',
						'id'	=>	'english',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_textarea($element);
					?>				  
               
              
           
              </div>			  
            
              
        <div class="form-group text-right">
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="add">
          <i class="fa fa-send"></i> <?php echo get_languageword('add');?> 
            </button>
			
			<button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="addnew">
           <i class="fa fa-send"></i> <?php echo get_languageword('add__new');?> 
            </button>
            
               <button type="button" class="digi-defult-btn digi-defult-btn" value="Submit" onclick="document.location='<?php echo URL_LANGUAGE_PHRASES;?>'">
          <i class="flaticon-round73"></i> <?php echo get_languageword('cancel');?> 
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