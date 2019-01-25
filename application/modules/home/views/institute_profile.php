    <!-- User Profile Details -->
    <?php if(!empty($inst_details)) {
            foreach ($inst_details as $row) {
     ?>
    <div class="container">
        <div class="row-margin ">

            <?php echo $this->session->flashdata('message'); ?>

            <div class="box-border">
                <div class="row ">
                    <!-- User Profile -->
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="user-profile-pic">
                            <img src="<?php echo get_inst_img($row->photo); ?>" alt="" class="img-responsive img-circle">
                        </div>
                        <?php echo get_user_online_status($row->is_online); ?>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
                        <div class="user-profile-content">
                            <ul class="user-badges">
                                <?php
                                      if(strcasecmp(get_system_settings('need_admin_for_inst'), 'yes') == 0) {

                                        $title = get_languageword('not_yet_verified');
                                        $last_verified_date = "";
                                        if(!empty($row->admin_approved_date)) {
                                            $title = get_languageword('last_verified');
                                            $last_verified_date = date('jS F, Y', strtotime($row->admin_approved_date));
                                        }
                                ?>
                                <li>
                                    <a href="#" title="<?php echo $title; ?>" data-content="<?php echo $last_verified_date; ?>" class="red-popover" data-toggle="popover" data-placement="top" data-trigger="hover"><i class="fa fa-heart"></i></a>
                                </li>
                                <?php } ?>
                            </ul>
                            <h4 class="title"> <?php echo $row->username; ?></h4>
                            <p class="sub-title"><u><?php echo $row->website;  ?></u></p>
                            <hr>
                            <p> <?php echo $row->profile; ?> </p>

                            <hr>
                            <h4><strong><?php echo get_languageword('year_of_establishment'); ?>: </strong> <?php echo $row->teaching_experience; ?></h4>

                            <?php if($row->show_contact=='All' || $row->show_contact=='Email'){?>
                            <h4><strong><?php echo get_languageword('email'); ?>: </strong> <?php echo $row->email; ?></h4>
                            <?php }
                             if($row->show_contact=='All' || $row->show_contact=='Mobile'){?>
                            <h4><strong><?php echo get_languageword('phone'); ?>: </strong> <?php echo $row->phone; ?></h4>
                            <?php }?>
                            <h4><strong><?php echo get_languageword('language_of_teaching'); ?>:</strong>  <?php echo $row->language_of_teaching; ?></h4>
                            <?php if($row->academic_class != 'no' || $row->non_academic_class !='no'){?>
                            <h4><strong><?php echo get_languageword('Teaching_Class_Types'); ?>: </strong> 
                            <?php if($row->academic_class != 'no')
                                     echo get_languageword('Academic'); 
                                    
                                  if($row->non_academic_class !='no')
                                   echo ', '. get_languageword('Non_Academic'); ?></h4><?php } ?>
                        </div>
                    </div>

                    <?php 
                            if(!empty($row->institute_offered_courses)) {

                                $inst_course_opts[''] = get_languageword('select_course');
                                $inst_course_opts_with_slugs[''] = get_languageword('select_course');
                                $list = "";
                                foreach($row->institute_offered_courses as $key=>$value) {
                                    $inst_course_opts[$value->course_id] = $value->name;
                                    $inst_course_opts_with_slugs[$value->slug] = $value->name;
                                    $list .= $value->name.", ";
                                }
                         ?>


                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                        <div class="send-quote-block">
                            <h2 class="heading-line"><?php echo get_languageword('send_us_your_message'); ?>:</h2>
                            <?php $this->load->view('send_message', array('user_course_opts' => $inst_course_opts_with_slugs, 'to_user_type' => 'institute', 'to_user_id' => $row->id)); ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <!--  More about Me -->
            <div class="row mtop7">
                <div class="col-sm-12">
                    <h2 class="heading-line"><?php echo get_languageword('more_details'); ?></h2>
                    <ul class="user-more-details">

                        <?php if(!empty($row->institute_offered_courses)) { ?>
                        <li>
                            <div class="media-left "><?php echo get_languageword('institute_offering_courses'); ?>:</div>
                               <div class="media-body">
                                 <?php 
                                    echo $list;
                                 ?>
                                </div>
                             
                        </li>
                        <?php } ?>
                        <?php if(!empty($row->institute_tutoring_locations)) { ?>
                        <li>
                            <div class="media-left "><?php echo get_languageword('tutoring_locations_of_institute'); ?>:</div>

                            <div class="media-body">
                            <?php foreach($row->institute_tutoring_locations as $inst_locations){
                                         echo $inst_locations->location_name.",\t" ;} 
                                 ?>
                            </div>
                        </li>
                        <?php } ?>
                        <?php if(!empty($row->experience_desc)) { ?>
                        <li>
                            <div class="media-left "><?php echo get_languageword('career_experience'); ?>:</div>
                            <div class="media-body"><?php echo $row->experience_desc; ?></div>
                        </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>

            <!-- Gallery -->
            <?php if(!empty($row->inst_gallery)) { ?>
            <div class="row mtop7">
                <div class="col-sm-12">
                    <h2 class="heading-line"><?php echo get_languageword('gallery'); ?></h2>
                </div>
                <div class="col-sm-8">
                    <div class="tab-content tabpill-content">

                        <?php $i=1; foreach ($row->inst_gallery as $gallery) { ?>
                        <div id="vid<?php echo $i; ?>" class="tab-pane fade <?php if($i++ == 1) echo "active in"; ?> ">
                            <div class="my-images popup-gallery">
                                <a href="<?php echo URL_UPLOADS_GALLERY.'/'.$gallery->image_name; ?>" title="<?php echo $gallery->image_title; ?>">
                                    <img src="<?php echo URL_UPLOADS_GALLERY.'/'.$gallery->image_name; ?>" class="img-responsive" alt="">
                                </a>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="NavPillTabs">
                        <ul class=" video-tabs video-thumbs">
                            <?php $i=1; foreach ($row->inst_gallery as $gallery_thumbs) { ?>
                            <li class="<?php if($i == 1) echo 'active'; ?>">
                                <a data-toggle="pill" href="#vid<?php echo $i++; ?>">
                                    <img src="<?php echo URL_UPLOADS_GALLERY.'/thumb__'.$gallery_thumbs->image_name; ?>" alt="" class="img-responsive">
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>


            <!-- Reserve Your Spot -->
            <div class="row mtop7" id="enroll-now">
                <div class="col-sm-12">
                    <h2 class="heading-line"><?php echo get_languageword('reserve_your_slot'); ?></h2>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <h2 class="fee" id="fee"> </h2>
                    <div class="feeperhour" id="duration"> </div>
                    <div class="feeperhour" id="days_off"> </div>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                    <?php 
                            $attributes = 'id="enroll_in_inst_form" class="comment-form" ';
                            echo form_open(URL_STUDENT_ENROLL_IN_INSTITUTE, $attributes); 
                        ?>

                        <?php 
                                echo form_hidden('inst_id', $row->id);
                                echo form_hidden('inst_slug', $row->slug);
                        ?>

                        <ul class="reserve-form">
                            <?php $sno = 1;
                                        if(!empty($inst_course_opts)) {
                                  ?>
                            <li><span class="step-num"><?php echo $sno++; ?></span> <?php echo get_languageword('select_course'); ?>:
                            <div class="dark-picker dark-picker-bright top20">
                            <?php
                                    $select='';
                                    if(isset($_POST['submitbutt']))
                                    {
                                        $select = $this->input->post('course_id');
                                    }

                                    echo form_dropdown('course_id',$inst_course_opts, $select, 'id="course_id" class="select-picker" onchange="getBatches()"')?>
                            </div>
                            </li>
                            <?php } ?>

                            <li><span class="step-num"><?php echo $sno++; ?></span> <?php echo get_languageword('select_batch'); ?>:
                                <div class="dark-picker dark-picker-bright top20">
                                <select name="batch_id" id="batch_id" class="select-picker" onchange="get_batch_info();">
                                    <option value=""><?php echo get_languageword('select_course_first'); ?></option>
                                </select>
                            </div>
                            </li>

                            <div id="batchInfo" style="">

                            </div>
                            <br><br>

                            <li><span class="step-num"><?php echo $sno++; ?></span> <?php echo get_languageword('write_your_message'); ?>:
                                <div class="appointment-msg">
                                    <div class="input-group ">
                                        <textarea name="message" rows="8" placeholder="<?php echo get_languageword('hello_I_am').' ...'; ?>"><?php echo set_value('message'); ?></textarea>
                                        <?php echo form_error('message'); ?>
                                    </div>
                                    <button id="request_tutor_btn" class="btn btn-link-dark" name="Submit" type="Submit"><?php echo get_languageword('enroll_in_this_batch'); ?></button>
                                </div>
                            </li>
                        </ul>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>


    <script src="<?php echo URL_FRONT_JS;?>jquery.js"></script>
    <script src="<?php echo URL_FRONT_JS;?>jquery.validate.min.js"></script>
    <script type="text/javascript"> 
      (function($,W,D)
       {
          var JQUERY4U = {};
       
          JQUERY4U.UTIL =
          {
              setupFormValidation: function()
              {

                  //form validation rules
                  $("#enroll_in_inst_form").validate({
                      rules: {
                            course_id: {
                                required: true
                            },
                            batch_id: {
                                required: true
                            }
                      },

                      messages: {
                            course_id: {
                                required: "<?php echo get_languageword('please_select_course'); ?>"
                            },
                            batch_id: {
                                required: "<?php echo get_languageword('please_select_batch'); ?>"
                            }
                      },

                      submitHandler: function(form) {
                          form.submit();
                      }
                  });
              }
          }
             //when the dom has loaded setup form validation rules
         $(D).ready(function($) {
             JQUERY4U.UTIL.setupFormValidation();
         });
     })(jQuery, window, document);
   


// getting batches script
function getBatches()
{
    val = $('#course_id option:selected').val();

    if(!val) {

        $('#batch_id').empty();
        $('#batch_id').append('<option value=""><?php echo get_languageword("select_course_first"); ?></option>');
        $("#batch_id").trigger("change");
        $('#batchInfo').slideUp().html('');
        return;
    }

    $.ajax({
        type: "POST",
        url: "<?php echo URL_HOME_AJAX_GET_INSTITUTE_BATCHES; ?>",
        data:'course_id='+val+'&inst_id=<?php echo $row->id; ?>',
        success: function(data) {

            $("#batch_id").empty();
            $("#batch_id").append(data);

            <?php if(!empty($batch_id)) { ?>
                $('#batch_id option[value="'+<?php echo $batch_id; ?>+'"]').attr('selected', 'selected');
            <?php } ?>

            $("#batch_id").trigger("change");
        }
    });
}


function get_batch_info()
{
    val = $('#course_id option:selected').val();
    batch_id = $('#batch_id option:selected').val();

    if(!batch_id) {

        $('#batchInfo').slideUp().html('');
        return;
    }

    $.ajax({
        type:"POST",
        url:"<?php echo URL_HOME_AJAX_GET_INSTITUTE_BATCHES_INFO; ?>",
        data: 'course_id='+val+'&batch_id='+batch_id+'&inst_id=<?php echo $row->id; ?>',
        success:function(data){

            $.getScript("<?php echo URL_FRONT_JS;?>main.js");
            $('#batchInfo').html(data).slideDown();
        }
    });
}

 </script>

 <style>
    .dataTables_paginate {
        display: none;
    }
 </style>

 <?php } } ?>

    <!-- User Profile Details  -->