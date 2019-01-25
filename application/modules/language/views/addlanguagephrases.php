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
      <?php if(empty($message)) echo $this->session->flashdata('message'); ?>
			<div class="panel-body"> 
				  <div class="col-lg-12">
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
				

			<?php 
			   //print_r($languagewords);
			   foreach( $languagewords as $row) { ?>
              <div class="form-group">
                <div class="group ap">
                  <?php			   
					$val = '';
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$words = $this->input->post( 'word' );
						$val = $words[$row->lang_key];
					}
					elseif( isset($row->$id))
					{
						$val = $row->$id;
					}
          $lang_key = str_replace(' ', '___', $row->lang_key);
					$element = array(
						'name'	=>	'word['.$lang_key.']',
						'id'	=>	'word['.$lang_key.']',
						'value'	=>	$val,
					);			
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo $row->english;?> (<?php echo $row->lang_key;?>)</label>
                </div>
              </div>
			   <?php } ?>
        <input type="hidden" name="id" value="<?php echo $id;?>">
        <div class="form-group text-right">
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="add">
          <i class="fa fa-send"></i> <?php echo get_languageword('add');?> 
            </button>
			            
           <button type="button" class="digi-defult-btn digi-defult-btn" value="Submit" onclick="document.location='<?php echo URL_LANGUAGE_INDEX;?>'">
          <i class="flaticon-round73"></i> <?php echo get_languageword('cancel');?> 
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