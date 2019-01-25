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
					$options = array(
					"Student"=> get_languageword('student'),
					"Tutor"=> get_languageword('tutor'),
					"Both"=> get_languageword('both')

					);

					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$select = $this->input->post( 'package_for' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$select = $details[0]->package_for;
					}
					else
					{
						$select = '';
					}
					echo form_dropdown('package_for',$options,$select,'class = "chzn-select"');?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('package_for')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  
			  <div class="form-group">
                <div class="group">
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
						'required' => 'required',
						'class' => 'ckeditor1',						 
					);			
					echo form_textarea($element);
					?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('package_description')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
				<?php
				$options = array(
				"Usage"=>get_languageword('usage'),
				"Days"=>get_languageword('days')

				);

				if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
				{
				$select = $this->input->post( 'validity_type' );
				}
				elseif( isset($details) &&  count($details) > 0)
				{
				$select = $details[0]->validity_type;
				}
				else
				{
				$select = '';
				}

				echo form_dropdown('validity_type',$options,$select,'class = "chzn-select"');
				?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('validity_type')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'validity_value' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->validity_value;
					}
					else
					{
						$val = '';
					}
					$element = array(
						'name'	=>	'validity_value',
						'id'	=>	'validity_value',
						'value'	=>	$val,
						'required' => 'required',
					);			
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('validity_value')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
				<?php
				$options = array(
                  "No" => get_languageword('no'),
                  "Yes" => get_languageword('yes'),                  
                  );

				if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
				{
				$select = $this->input->post( 'avail_discount' );
				}
				elseif( isset($details) &&  count($details) > 0)
				{
				$select = $details[0]->avail_discount;
				}
				else
				{
				$select = '';
				}

				echo form_dropdown('avail_discount',$options,$select,'class = "chzn-select"');
				?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('avail_discount')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'pkg_actual_cost' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->actual_cost;
					}
					else
					{
						$val = '0';
					}
					if(isset($details[0]->avail_discount)
					&& $details[0]->avail_discount == "Yes")
					{
						$element = array(
						'name'	=>	'pkg_actual_cost',
						'id'	=>	'pkg_actual_cost',
						'value'	=>	$val,
						'required' => 'required',
						'onblur' => 'myFunction()',
						);	
					}
					else
					{
					$element = array(
					'name'	=>	'pkg_actual_cost',
					'id'	=>	'pkg_actual_cost',
					'value'	=>	$val,
					'required' => 'required',
					'onblur' => 'myFunction()',
					'readonly' => TRUE,
					);
					}	
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('actual_cost')?><font color="red">*</font></label>
                </div>
              </div>
			  
			  <div class="form-group">
                <div class="group">
                  <?php			   
					if( ( isset($_POST['submitbutt']) && $_POST['submitbutt'] ) )
					{
						$val = $this->input->post( 'discount' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->discount;
					}
					else
					{
						$val = '0';
					}
					if(isset($details[0]->avail_discount)
					&& $details[0]->avail_discount == "Yes")
					{
						$element = array(
						'name'	=>	'pkg_discount',
						'id'	=>	'pkg_discount',
						'value'	=>	$val,
						'required' => 'required',
						'onblur' => 'myFunction()',
						);	
					}
					else
					{
					$element = array(
					'name'	=>	'pkg_discount',
					'id'	=>	'pkg_discount',
					'value'	=>	$val,
					'required' => 'required',
					'onblur' => 'myFunction()',
					'readonly' => TRUE,
					);
					}	
					echo form_input($element);
					?>				  
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('discount')?>%<font color="red">*</font></label>
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
						'required' => 'required',
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
						$val = $this->input->post( 'status' );
					}
					elseif( isset($details) &&  count($details) > 0)
					{
						$val = $details[0]->status;
					}
					else
					{
						$val = '';
					}
				  $options = activeinactive();
				  echo form_dropdown('status', $options, $val, 'required="required" class="chzn-select"');
				  ?>
                  <span class="highlight"></span> <span class="bar"></span>
                  <label class="digiEffectLabel"><?php echo get_languageword('status')?></label>
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
 

<script type="text/javascript">
function actionDivz(val)
{  	
	if(val=="Yes")
	{
		$('#pkg_discount').removeAttr("readonly");
		$('#pkg_actual_cost').removeAttr("readonly");
	}
	else if(val=="No")
	{
		$('#pkg_discount').attr("readonly","readonly");
		$('#pkg_actual_cost').attr("readonly","readonly");
		$('#pkg_discount').val(0);
		$('#pkg_actual_cost').val(0);
	}   
}
</script> 
<script type="text/javascript">
   function myFunction() 
   { 
	var actualcost = $("#pkg_actual_cost").val();
	var discount = $("#pkg_discount").val();
	if(actualcost != "" && discount != "")
	{
		var discounted_amount = (discount/100)*actualcost;
		var final_amount = actualcost-discounted_amount;
		$('#package_cost').val(final_amount.toFixed(2));
	}
	else
	{
		$('#package_cost').val(actualcost);
	}
              
   }
</script>