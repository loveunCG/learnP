<div class="dashboard-panel">
	<?php echo $message;?>

	<p class="text-danger">
		<small>
		<strong><?php echo get_languageword('Note1'); ?>:</strong> <?php echo get_languageword('Please upload files only with allowed formats')." ".get_languageword('Allowed File Foramts are')." 'mp2', 'mp3', 'mp4', '3gp', 'pdf', 'ppt', 'pptx', 'doc', 'docx', 'rtf', 'rtx', 'txt', 'text', 'webm', 'aac', 'wav', 'wmv', 'flv', 'avi', 'ogg', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp'."; ?>
		<br />
		<strong><?php echo get_languageword('Note2'); ?>:</strong> <?php echo get_languageword('Admin_Commission_Percentage_On_Each_Purchase').': '.$this->config->item('site_settings')->admin_commission_on_course_purchase; ?>
		</small>
	</p>

	<div class="row">

		<?php
		$attributes = array('name' => 'sell_courses_form', 'id' => 'sell_courses_form', 'class' => 'comment-form dark-fields');
		echo form_open_multipart('',$attributes);?>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Course_Name');?><?php echo required_symbol();?></label>
					<?php

						$val = set_value('course_name', (!empty($record->course_name)) ? $record->course_name : '');

						$element = array(
							'name'	=>	'course_name',
							'id'	=>	'course_name',
							'value'	=>	$val,
							'class' => 'form-control',
							'placeholder' => get_languageword('course_name'),
						);
						echo form_input($element);
					?>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Course_Title');?><?php echo required_symbol();?></label>
					<?php

						$val = set_value('course_title', (!empty($record->course_title)) ? $record->course_title : '');

						$element = array(
							'name'	=>	'course_title',
							'id'	=>	'course_title',
							'value'	=>	$val,
							'class' => 'form-control',
							'placeholder' => get_languageword('course_title'),
						);
						echo form_input($element);
					?>
				</div>
			</div>
			<div class="col-sm-12 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Description');?><?php echo required_symbol();?></label>
					<textarea name="description" id="description" class="form-control texteditor"><?php echo set_value('description', (!empty($record->description)) ? $record->description : ''); ?></textarea>
				</div>
			</div>
			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Skill_Level');?></label>
					<?php

						$val = set_value('skill_level', (!empty($record->skill_level)) ? $record->skill_level : '');

						$element = array(
							'name'	=>	'skill_level',
							'id'	=>	'skill_level',
							'value'	=>	$val,
							'class' => 'form-control',
							'placeholder' => get_languageword('skill_level'),
						);
						echo form_input($element);
					?>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Languages');?></label>
					<?php

						$val = set_value('languages', (!empty($record->languages)) ? explode(',', $record->languages) : '');

						echo form_multiselect('languages[]',$language_options,$val,'id="languages" class="form-control multiple-tags" multiple="multiple" ');
					?>
				</div>
			</div>


			<div class="col-sm-12 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Course_Image');?> <code><small><?php echo "('.jpg, .jpeg, .png, .svg, .bmp'".get_languageword('_are_allowed_formats_for_course_image').")"; ?></small></code></label>
					<?php

						$val = (!empty($record->image)) ? $record->image : '';

						$element = array(
							'type'	=>	'file',
							'name'	=>	'course_image',
							'id'	=>	'course_image',
							'class' => 	'form-control',
							'placeholder' => get_languageword('Course_Image'),
						);
						echo form_input($element);
						if(!empty($val) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$val)) echo '&nbsp;<a target="_blank" href="'.URL_PUBLIC_UPLOADS2.'course_curriculum_files/'.$val.'">'.$val.'</a>';
					?>
				</div>
			</div>


			<div class="col-sm-12 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Preview_Image');?> <code><small><?php echo "('.jpg, .jpeg, .png, .svg, .bmp'".get_languageword('_are_allowed_formats_for_preview_image').")"; ?></small></code></label>
					<?php

						$val = (!empty($record->preview_image)) ? $record->preview_image : '';

						$element = array(
							'type'	=>	'file',
							'name'	=>	'preview_image',
							'id'	=>	'preview_image',
							'class' => 	'form-control',
							'placeholder' => get_languageword('Preview_Image'),
						);
						echo form_input($element);
						if(!empty($val) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$val)) echo '&nbsp;<a target="_blank" href="'.URL_PUBLIC_UPLOADS2.'course_curriculum_files/'.$val.'">'.$val.'</a>';
					?>
				</div>
			</div>

			<div class="col-sm-12 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Preview_File');?> <code><small><?php echo "('.mp2, .mp3, .mp4, .3gp, .webm, .aac, .wav, .wmv, .flv, .avi, .ogg, .jpg, .jpeg, .png, .svg, .bmp'".get_languageword('_are_allowed_formats_for_preview_file').")"; ?></small></code></label>
					<?php

						$val = (!empty($record->preview_file)) ? $record->preview_file : '';

						$element = array(
							'type'	=>	'file',
							'name'	=>	'preview_file',
							'id'	=>	'preview_file',
							'class' => 	'form-control',
							'placeholder' => get_languageword('Preview_File'),
						);
						echo form_input($element);
						if(!empty($val) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$val)) echo '&nbsp;<a target="_blank" href="'.URL_PUBLIC_UPLOADS2.'course_curriculum_files/'.$val.'">'.$val.'</a>';
					?>
				</div>
			</div>

			<div class="col-sm-12">
				<h4><?php echo get_languageword('Curriculum'); ?><?php if(empty($record->sc_id)) echo required_symbol();?> <code><small><?php echo get_languageword('maximum_allowed_file_size_is_20_MB_for_each_file').")"; ?> <?php if(!empty($record->sellingcourse_curriculum)) echo '(<a href="'.URL_TUTOR_VIEW_SELLING_COURSE_CURRICULUM.'/'.$record->sc_id.'">'.get_languageword('view_uploaded_curriculum').'</a>)'; ?></small> </code></h4>
			</div>
			<div class="col-sm-12 add-curriculum">
				<div id="add_curriculum">
					<?php

	                        $appending_div = "div_curclm";
	                        $key = 1;
	                        $cls = "";
	                        $max_curr 	= (!empty($record->sellingcourse_curriculum)) ? 25-count($record->sellingcourse_curriculum) : 24;
	                        $btn_action = '<span title="'.get_languageword('add_more').'" class="btn btn-success" id="add_curclm_field" onclick=\'append_field('.$max_curr.', "add_curriculum", this.id, "'.$appending_div.'", "Title", "Source", "lesson_title", "lesson_url");\'><i class="fa fa-plus"></i></span> ';

	                ?>

					<div class="row <?php echo $cls; ?>" id="<?php echo $appending_div.$key; ?>">

						<div class="col-sm-5 ">
							<label>Title <?php echo $key; ?></label>
					    	<input type="text" name="lesson_title[]" class="form-control" />
						</div>
						<div class="col-sm-2 ">
							<label>Source Type</label>
							<?php

								$sourcetype_opts = array(
														'url' 	=> get_languageword('URL'),
														'file' 	=> get_languageword('file')
													);

								echo form_dropdown('source_type[]', $sourcetype_opts, '', 'id="source_type_'.$key.'" class="form-control cls-source_type" ');
							?>
						</div>
						<div class="col-sm-4 ">
							<label>Source <?php echo $key; ?></label>
							<div class="cls-source" id="source_<?php echo $key; ?>">
								<input type="text" name="lesson_url[]" class="form-control" />
							</div>
						</div>
						<div class="col-sm-1">
							<label>&nbsp;</label>
							<?php echo $btn_action; ?>
						</div>

					</div>

				</div>

			</div>

			<p>&nbsp;</p>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Course_Price');?><?php echo required_symbol();?></label>
					<?php

						$val = set_value('course_price', (!empty($record->course_price)) ? $record->course_price : '');

						$element = array(
							'name'	=>	'course_price',
							'id'	=>	'course_price',
							'value'	=>	$val,
							'class' => 'form-control',
							'placeholder' => get_languageword('course_price'),
						);
						echo form_input($element);
					?>
				</div>
			</div>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Maximum_number_of_Downloads');?><?php echo required_symbol();?></label>
					<?php

						$val = set_value('max_downloads', (!empty($record->max_downloads)) ? $record->max_downloads : '');

						$element = array(
							'name'	=>	'max_downloads',
							'id'	=>	'max_downloads',
							'value'	=>	$val,
							'class' => 'form-control',
							'placeholder' => get_languageword('max_downloads'),
						);
						echo form_input($element);
					?>
				</div>
			</div>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Status');?><?php echo required_symbol();?></label>
					<div class="dark-picker dark-picker-bright">
					<?php

						$status_opts = array(
												'Active' 	=> get_languageword('Active'),
												'Inactive' 	=> get_languageword('Inactive')
											);

						$val = set_value('status', (!empty($record->status)) ? $record->status : '');

						echo form_dropdown('status', $status_opts, $val, 'class="select-picker" ');
					?>
					</div>
				</div>
			</div>

			<?php
					$sc_id = "";
					$actn_btn_txt = get_languageword('publish');
					if(!empty($record->sc_id)) {
						$sc_id = $record->sc_id;
						$actn_btn_txt = get_languageword('update');
					}
			?>

			<input type="hidden" name="sc_id" value="<?php echo $sc_id; ?>" />

			<div class="col-sm-12 ">
				<div class="col-sm-6">
					<button class="btn-link-dark dash-btn pull-right" name="submitbutt" type="Submit"><?php echo $actn_btn_txt;?></button>
				</div>
				<div class="col-sm-6 ">
					<button onclick="location.href='<?php echo URL_TUTOR_LIST_SELLING_COURSES; ?>'" class="btn-link-dark dash-btn pull-left" type="button" ><?php echo get_languageword('cancel');?></button>
				</div>
			</div>

		</form>
	</div>

</div>

<!-- Dashboard panel ends -->