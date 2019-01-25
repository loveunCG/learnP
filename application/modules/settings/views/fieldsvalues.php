  <!-- Elements Of Web Site -->
  <section class="content">
  <div class="flexigrid crud-form" style="width: 100%;">
  
    <div class="row">
		<?php $attributes = array('name'=>'tokenform','id'=>'tokenform', 'enctype' => 'multipart/form-data');
		echo form_open('',$attributes) ?>
	  <div class="col-lg-12">
        <div class="elements">
          <div class="panel panel-default theameOfPanle">
            <div class="panel-heading main_small_heding"><?php echo $pagetitle;?>             
            </div>
            <div class="panel-body"> 
			<div class="col-lg-6">
              
              <!--Input Text Feilds-->
              <div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"'; ?>><?php echo $message;?></div>
              			  
			  <?php
				   if(count($fields) > 0) {
				   foreach( $fields as $row) { ?>
				   
				   <div class="form-field-box odd" id="type_title_field_box">
				<div class="form-display-as-box" id="type_title_display_as_box">
					<?php echo get_languageword(clean_text($row->field_name));?> <?php if($row->is_required == 'Yes') { ?><font color="red">*</font><?php } ?>:
				</div>
				<div class="form-input-box" id="type_title_input_box">				
                  <?php			
					if($row->field_type == 'select') {
						$optionsarray = array('' => 'Please select');
						if($row->field_type_values != '') {
							$options = explode(',', $row->field_type_values);
							if(count($options) > 0) {
								$optionsarray = array();
								foreach($options as $key => $val)
								$optionsarray[$val] = ucfirst($val);
							}
						}
						$str = 'class="chosen-select"';
						if($row->is_required == 'Yes') {
							$str .= ' required';
						}
						$valu = (isset($row->field_output_value)) ? $row->field_output_value : '';
						echo form_dropdown('field['.$row->field_id.']', $optionsarray, $valu, $str);
						
					} elseif($row->field_type == 'file') {
						if($row->is_required == 'No') {
							$element = array(
								'type' => 'file',
								'name'	=>	'setting_'.$row->field_id,
								'id'	=>	'setting_'.$row->field_id,
								'value'	=>	(isset($row->field_output_value)) ? $row->field_output_value : '',
								'class' => 'form-control',
							);
						} else {
							$element = array(
								'type' => 'file',
								'name'	=>	'field['.$row->field_id.']',
								'id'	=>	'field['.$row->field_id.']',
								'value'	=>	(isset($row->field_output_value)) ? $row->field_output_value : '',
								'required' => 'required',
								'class' => 'form-control',
							);
						}
						echo form_input($element);
					}elseif($row->field_type == 'textarea') {
						if($row->is_required == 'No') {
							$element = array(
								'name'	=>	'setting_'.$row->field_id,
								'id'	=>	'setting_'.$row->field_id,
								'value'	=>	(isset($row->field_output_value)) ? $row->field_output_value : '',
								'class' => 'form-control',
							);
						} else {
							$element = array(
								'name'	=>	'field['.$row->field_id.']',
								'id'	=>	'field['.$row->field_id.']',
								'value'	=>	(isset($row->field_output_value)) ? $row->field_output_value : '',
								'required' => 'required',
								'class' => 'form-control',
							);
						}
						echo form_textarea($element);
					}else {
						if($row->is_required == 'No') {
							$element = array(
								'name'	=>	'field['.$row->field_id.']',
								'id'	=>	'field['.$row->field_id.']',
								'value'	=>	(isset($row->field_output_value)) ? $row->field_output_value : '',
								'class' => 'form-control',
							);
						} else {
							$element = array(
							'name'	=>	'field['.$row->field_id.']',
							'id'	=>	'field['.$row->field_id.']',
							'value'	=>	(isset($row->field_output_value)) ? $row->field_output_value : '',
							'required' => 'required',
							'class' => 'form-control',
						);
						}
						if($row->field_type == 'email')
							$element['type'] = 'email';
						echo form_input($element);
					}
					?>
					<?php			   
					$image = '';
					if( isset($row->field_output_value) && file_exists(URL_PUBLIC_UPLOADS . 'settings/thumbs/'.$row->field_output_value))
					{
						$image = $row->field_output_value;
					}
					if(!empty($image))
					echo '<img src="'.URL_PUBLIC_UPLOADS2.'settings/thumbs/'.$image.'" alt="'.$image.'">';
					?>
					</div>
					<div class="clear"></div>
			</div>
                
				   <?php } } ?>

<?php if(count($fields) == 0) {
	?>
	<div class="form-group">
                <div class="group">
				<?php echo get_languageword('no_fields_yet_added');?>
				Click <a href="<?php echo URL_SETTINGS_FIELDADDEDIT;?>">here</a> to add
				</div>
	</div>
	<?php
}?>				   
            
              
			<div class="form-group text-right">
				<button type="submit" class="btn btn-large" name="submitbutt" value="submitbutt"><?php echo get_languageword('submit')?></button>
				
				<?php
				$url = URL_SETTINGS_INDEX;
				if($editing == 'sub')
					$url = URL_SETTINGS_SUBTYPES . '/' . $activesubmenu;
				?>
				<button type="button" class="btn btn-large" value="Submit" onclick="document.location='<?php echo $url;?>'"><?php echo get_languageword('cancel')?></button>
			</div>
			<input type="hidden" name="gid" value="<?php echo $id;?>">
			<input type="hidden" name="editing" value="<?php echo $editing;?>">
            <input type="hidden" name="activesubmenu" value="<?php echo $activesubmenu;?>">
              <!--Input Text Feilds--> 
             </div> 
			 		<div class="col-lg-6"> </div>
			 
			 </div>
            </div>
          </div>
        </div>
      </div>
	  </form>
      
    </div>
  </div>
  </section>