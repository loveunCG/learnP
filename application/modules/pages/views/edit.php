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
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'name' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->name;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'name',
						'id'	=>	'name',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element).form_error('name');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('name')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
			  <label><?php echo get_languageword('description')?><font color="red">*</font></label>
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'description' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->description;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'description',
						'id'	=>	'description',
						'value'	=>	$val,
						'required' => 'required'
						
					);			
					echo form_textarea($element).form_error('description');
					?>				  
            
       
                
              </div>
		
               <div class="form-group">
   <label><?php echo get_languageword('meta_tag')?></label>
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'meta_tag' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->meta_tag;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'meta_tag',
						'id'	=>	'meta_tag',
						'value'	=>	$val,
					);			
					echo form_textarea($element);
					?>				  
                
                
             
              </div>
              
               <div class="form-group">
                           <label ><?php echo get_languageword('meta_description')?></label> 
                <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'meta_description' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->meta_description;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'meta_description',
						'id'	=>	'meta_description',
						'value'	=>	$val,
					);			
					echo form_textarea($element);
					?>				
                <span class="highlight"></span> <span class="bar"></span>
 
            </div>
            
             <div class="form-group">
 
              <label ><?php echo get_languageword('seo_keywords')?></label>
                <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'seo_keywords' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->seo_keywords;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'seo_keywords',
						'id'	=>	'seo_keywords',
						'value'	=>	$val,
					);			
					echo form_textarea($element);
					?>
                <span class="highlight"></span> <span class="bar"></span>
 
            </div>
            
            
              
        <div class="form-group text-right">
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
            <a><i class="fa fa-send"></i> <?php echo get_languageword('submit')?></a>
            </button>
			
               <button type="button" class="digi-defult-btn digi-defult-btn" value="Submit" onclick="document.location='<?php echo URL_PAGES;?>'">
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
  
<script src="<?php echo URL_ADMIN_JS; ?>jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="<?php echo URL_ADMIN_JS; ?>ckeditor.js" type="text/javascript"></script>
<script>
   var editor;
   // The instanceReady event is fired, when an instance of CKEditor has finished
   // its initialization.
   CKEDITOR.on( 'instanceReady', function( ev ) {
   	editor = ev.editor;
   	// Show this "on" button.
   	document.getElementById( 'readOnlyOn' ).style.display = '';
   	// Event fired when the readOnly property changes.
   	editor.on( 'readOnly', function() {
   		document.getElementById( 'readOnlyOn' ).style.display = this.readOnly ? 'none' : '';
   		document.getElementById( 'readOnlyOff' ).style.display = this.readOnly ? '' : 'none';
   	});
   });
   function toggleReadOnly( isReadOnly ) {
   	// Change the read-only state of the editor.
   	// http://docs.ckeditor.com/#!/api/CKEDITOR.editor-method-setReadOnly
   	editor.setReadOnly( isReadOnly );
   }
</script>	 