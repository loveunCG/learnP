<!-- User Profile Details -->
<div class="container">
	<div class="row-margin ">

		<?php echo $this->session->flashdata('message'); ?>

		<div class="margin-btm">
			<div class="row">
				<?php if(!empty($course_opts) || !empty($location_opts) || !empty($teaching_type_opts)) { 

						echo form_open(URL_HOME_SEARCH_TUTOR, 'id="filter_form"');
				?>
				<?php if(!empty($course_opts)) { ?>
				<div class="col-md-4">
					<label><?php echo get_languageword('course'); ?></label>
					<?php

							$sel = set_value('course_slug', (!empty($course_slug)) ? $course_slug : '');
							echo form_multiselect('course_slug[]', $course_opts, $sel, 'class="select-picker" onchange="get_filter_result();"');

					?>
				</div>
				<?php } ?>
				<?php if(!empty($location_opts)) { ?>
				<div class="col-md-4">
					<label><?php echo get_languageword('location'); ?></label>
					<?php

							$sel = set_value('location_slug', (!empty($location_slug)) ? $location_slug : '');
							echo form_multiselect('location_slug[]', $location_opts, $sel, 'class="select-picker" onchange="get_filter_result();" ');

					?>
				</div>
				<?php } ?>
				<?php if(!empty($teaching_type_opts)) { ?>
				<div class="col-md-4">
					<label><?php echo get_languageword('teaching_type'); ?></label>
					<?php

							$sel = set_value('teaching_type_slug', (!empty($teaching_type_slug)) ? $teaching_type_slug : '');
							echo form_multiselect('teaching_type_slug[]', $teaching_type_opts, $sel, 'class="select-picker" onchange="get_filter_result();" ');

					?>
				</div>
				<?php } ?>
				<?php 
						echo form_close(); 
					} 
				?>
			</div>
		</div>


		<div id="tutor_list">
			<?php 
					
	              if(!empty($tutor_list)):
	                $this->load->view('sections/tutor_list_section', array('tutor_list' => $tutor_list), false);
	              else:
	        ?>
	         <p><?php echo get_languageword('tutor(s)_not_available.'); ?></p>
	        <?php endif; ?>
    	</div>


		<?php if($total_records > LIMIT_PROFILES_LIST) { ?>
        <div class="row" id="div_load_more">
            <div class="col-sm-12 text-center">
                <div class="load-more" onclick="load_more();">
                    <input type="hidden" name="limit" id="limit" value="<?php echo LIMIT_PROFILES_LIST;?>" />
                    <input type="hidden" name="offset" id="offset" value="<?php echo LIMIT_PROFILES_LIST;?>" />
                    <input type="hidden" name="course_slug" id="course_slug" value="<?php if(!empty($course_slug)) echo implode(',', $course_slug);?>" />
                    <input type="hidden" name="location_slug" id="location_slug" value="<?php if(!empty($location_slug)) echo implode(',', $location_slug);?>" />
                    <input type="hidden" name="teaching_type_slug" id="teaching_type_slug" value="<?php if(!empty($teaching_type_slug)) echo implode(',', $teaching_type_slug);?>" />
                    <a class="btn-link" id="btn_load_more"> <?php echo get_languageword("load_more");?></a>
                </div>
            </div>
        </div>
        <?php } ?>

	</div>
</div>
<!-- User Profile Details  -->

<script src="<?php echo URL_FRONT_JS;?>jquery.js"></script>
<script>

	$(function() {

        $('option[value=""]').attr('disabled', true);
        $('option[value=""]').prop('selected', false);
   });


	function get_filter_result()
	{
		document.getElementById('filter_form').submit();
	}


	function load_more()
	{
	    $.ajax({
	        url:"<?php echo URL_HOME_LOAD_MORE_TUTORS; ?>",
	        data:{
	          offset        : $('#offset').val(),
	          limit         : $('#limit').val(), 
	          course_slug   : $('#course_slug').val(), 
	          location_slug : $('#location_slug').val(), 
	          teaching_type_slug : $('#teaching_type_slug').val()
	        },
	        type:"post", 
	        beforeSend: function() {
	            $('#btn_load_more').html('<i class="fa fa-spinner"></i> <?php echo get_languageword("loading");?>');
	        },
	        success :function(data){

	            $('#btn_load_more').html(' <?php echo get_languageword("load_more");?>');

	            dta = $.parseJSON(data);

	            if(dta.result == "\n" || dta.result == "") {

	                $('#div_load_more').html('<?php echo get_languageword("You have reached end of the list.");?>');

	            } else {

	                $.getScript("<?php echo URL_FRONT_JS;?>main.js");

	                $(dta.result).hide().appendTo("#tutor_list").fadeIn(1000);

	                $('#offset').val(dta.offset);
	                $('#limit').val(dta.limit);
	            }
	        }
	    });
	}

</script>
