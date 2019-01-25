<!-- User Profile Details -->
<div class="container">
	<div class="row-margin ">

		<?php echo $this->session->flashdata('message'); ?>

		<div class="margin-btm">
			<div class="row">
				<?php if(!empty($course_opts) || !empty($location_opts) || !empty($teaching_type_opts)) { 

						echo form_open(URL_HOME_SEARCH_STUDENT_LEADS, 'id="filter_form"');
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


		<div id="student_leads_list">
			<?php 
	              if(!empty($student_leads_list)): 
	                $this->load->view('sections/student_leads_list_section', array('student_leads_list' => $student_leads_list), false);
	              else:
	        ?>
	         <p><?php echo get_languageword('no_leads_available'); ?></p>
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
	        url:"<?php echo URL_HOME_LOAD_MORE_STUDENT_LEADS; ?>",
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

	                $('#div_load_more').html('<?php echo get_languageword("You have reached end of the list");?>');

	            } else {

	                $.getScript("<?php echo URL_FRONT_JS;?>main.js");
	                $(dta.result).hide().appendTo("#student_leads_list").fadeIn(1000);
	                $('#offset').val(dta.offset);
	                $('#limit').val(dta.limit);
	            }
	        }
	    })
	}

</script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo get_languageword('information'); ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo get_languageword('you_will_be_charged')." <strong><font color='#ff3300' size='5'>".get_system_settings('credits_for_viewing_lead')."</font></strong> ".get_languageword('credits_for_viewing_the_lead_Are_you_sure_to_continue'); ?></p>
      </div>
      <div class="modal-footer">
        <a id="yes_btn" href="#" type="button" class="btn btn-info" ><?php echo get_languageword('yes'); ?></a>
        <a type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_languageword('no'); ?></a>
      </div>
    </div>

  </div>
</div>

<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo get_languageword('information'); ?></h4>
      </div>
      <div class="modal-body">
        <p>
            <?php
                    if(!$this->ion_auth->logged_in()) {
                        $this->session->set_userdata('req_from', 'leads');
                        echo get_languageword('please')." <a href='".URL_AUTH_LOGIN."'> <strong>".get_languageword('login')."</strong></a> ".get_languageword('to_view_the_lead_details');
                    }
            ?>
        </p>
      </div>
    </div>

  </div>
</div>


<script type="text/javascript">

function get_lead_details(slug, lead_id)
{
    href = "<?php echo URL_VIEW_STUDENT_PROFILE; ?>"+"/"+slug+"/"+lead_id;
    $('#yes_btn').attr('href',href);
}

</script>