
  <!-- Elements Of Web Site -->
  <div class="container-fluid">
    <div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform');
		echo form_open('',$attributes) ?>
	  <div class="col-lg-12">
        <div class="elements">
          <div class="panel panel-default theameOfPanle">
            <div class="panel-heading main_small_heding"><?php echo isset($pagetitle) ? $pagetitle : get_languageword('quote_settings');?>
            </div>
			<div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"'; ?>><?php echo $message;?></div>
             
            <div class="panel-body"> 
			<div class="col-lg-6">
              <!--Input Text Feilds-->
              <div class="form-group">
                <div class="group">
                  <?php			   
					if(( isset($_POST['submitbutt']) && $_POST['submitbutt'] ))
					{
						$val = $this->input->post( 'no_of_free_quotes_for_guest_user' );
					}
					elseif( isset($quotesettings) &&  count($quotesettings) > 0)
					{
						$val = $quotesettings[0]->no_of_free_quotes_for_guest_user;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'no_of_free_quotes_for_guest_user',
						'id'	=>	'no_of_free_quotes_for_guest_user',
						'value'	=>	$val,
						'required' => 'required',
						'type'=>'text'
					);			
					echo form_input($element).form_error('no_of_free_quotes_for_guest_user');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('no_of_free_quotes_for_guest_user')?><font color="red">*</font></label>
                </div>
              </div>
              <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'would_you_like_to_provide_package_to_user' );
					}
					elseif( isset($quotesettings) &&  count($quotesettings) > 0)
					{
						$val = $quotesettings[0]->would_you_like_to_provide_package_to_user;
					}
					else
					{
						$val = '';
					}
					 $options = array(''=>'Select','Yes' => get_languageword('yes'), 'No' => get_languageword('no'));		
					echo form_dropdown('would_you_like_to_provide_package_to_user',$options, $val, 'required="required" onchange="getpackages(this.value)" id="would_you_like_to_provide_package_to_user"').form_error('would_you_like_to_provide_package_to_user');
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('would_you_like_to_provide_any_package_to_user_when_registered')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			  
					$options = array();
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'package_id' );
					}
					elseif( isset($quotesettings) &&  count($quotesettings) > 0)
					{
						$val = $quotesettings[0]->package_id;
					}
					else
					{
						$val = '';
					}
							
					echo form_dropdown('package_id',$options, $val, ' id="package_id" class="chzn" onchange="getpackagedays(this.value)"').form_error('package_id');
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('package_id')?></label>
                </div>
              </div>
			
             <div class="form-group">
                <div class="group">
                  <?php			   
					if(( isset($_POST['submitbutt']) && $_POST['submitbutt'] ))
					{
						$val = $this->input->post( 'no_of_days_package_provide' );
					}
					elseif( isset($quotesettings) &&  count($quotesettings) > 0)
					{
						$val = $quotesettings[0]->no_of_days_package_provide;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'no_of_days_package_provide',
						'id'	=>	'no_of_days_package_provide',
						'value'	=>	$val,
						'type'=>'text'
					);			
					echo form_input($element).form_error('no_of_days_package_provide');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('no_of_days_package_provide')?></label>
                </div>
              </div>
			  
			  <div class="form-group">
               <label><?php echo get_languageword('email_header')?></label>
                  <?php			   
					if(( isset($_POST['submitbutt']) && $_POST['submitbutt'] ))
					{
						$val = $this->input->post( 'email_header' );
					}
					elseif( isset($quotesettings) &&  count($quotesettings) > 0)
					{
						$val = $quotesettings[0]->email_header;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'email_header',
						'id'	=>	'email_header',
						'value'	=>	$val,
					);			
					echo form_textarea($element).form_error('email_header');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
              
                
              </div>
			  
			  <div class="form-group">
  <label  ><?php echo get_languageword('email_footer')?></label>
                  <?php			   
					if(( isset($_POST['submitbutt']) && $_POST['submitbutt'] ))
					{
						$val = $this->input->post( 'email_footer' );
					}
					elseif( isset($quotesettings) &&  count($quotesettings) > 0)
					{
						$val = $quotesettings[0]->email_footer;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'email_footer',
						'id'	=>	'email_footer',
						'value'	=>	$val,
					);			
					echo form_textarea($element).form_error('email_footer');
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  
              
              </div>
			    
   
        <div class="form-group text-right">
		<?php echo form_hidden('update_record_id',set_value('update_record_id',$quotesettings[0]->id));?>
		<input type="hidden" name="selected_package" id="selected_package" value="<?php if(isset($quotesettings[0]->package_id)) echo $quotesettings[0]->package_id;?>">
		
             <button type="submit" class="digi-defult-btn digi-premier-btn" name="submitbutt" value="submitbutt">
          <i class="fa fa-send"></i> <?php echo get_languageword('submit')?> 
            </button>
        </div>
              <!--Input Text Feilds--> 
            </div> </div>
          </div>
        </div>
      </div>
	  </form>
    </div>
  </div>
  
<script src="<?php echo URL_ADMIN_JS; ?>jquery-1.11.1.min.js" type="text/javascript"></script> 
<script type="text/javascript">
$(document).ready(function() {
	var val = $("#would_you_like_to_provide_package_to_user").val();
	getpackages(val);
});
	 
  function getpackages(val)
  {
	  var package_id = $("#package_id").val();
	  var no_of_days = $("#no_of_days_package_provide").val();
	  if(val == '' || val == 'No')
	  {
		  $("#package_id").html('');
		  $("#no_of_days_package_provide").val('');
		  return;
	  }
	 
		  $.ajax({			 
   			 type: 'POST',			  
   			 async: false,
   			 cache: false,	
   			 url: "<?php echo URL_GET_PACKAGES;?>",
   			 data: '<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>',
   			 success: function(data) {
   			 if(data!='') {
				dta = $.parseJSON(data);
   				$('#package_id').html('').html(dta);
   				$('#package_id').trigger("liszt:updated");
			 }
   			 else {
   				alert('OOoopps...!');
			 }
			 $('#package_id').val($('#selected_package').val());
   			}		  		
   		});	  	  
	}
	function getpackagedays(val)
	{
	  if(val == '')
	  {
		  return;
	  }
	 
		  $.ajax({			 
   			 type: 'POST',			  
   			 async: false,
   			 cache: false,	
   			 url: "<?php echo URL_GET_PACKAGE_DAYS;?>",
   			 data: '<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>&package_id='+val,
   			 success: function(data) {
				 if(data!='') {
					$('#no_of_days_package_provide').val(data);
				 }
				 else {
					alert('OOoopps...!');
				 }
   			}		  		
   		});	  	  
	}
</script>