    <!-- User Profile Details -->
    <?php 
  
    if(!empty($stduent_details)) {
            foreach ($stduent_details as $row) {
     ?>
    <div class="container">
        <div class="row-margin ">

            <?php echo $this->session->flashdata('message'); ?>

            <div class="box-border">
                <div class="row ">
                    <!-- User Profile -->
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="user-profile-pic">
                            <img src="<?php echo get_student_img($row->photo, $row->gender); ?>" alt="" class="img-responsive img-circle">
                        </div>
                        <?php echo get_user_online_status($row->is_online); ?>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-4 col-xs-12">
                        <div class="user-profile-content">
                            <ul class="user-badges">
                                <li>
                                    <a href="#" title="<?php if(is_premium($row->id)) echo get_languageword('premium_user'); else echo get_languageword('non_premium_user');  ?>" class="red-popover" data-toggle="popover" data-placement="top" data-trigger="hover"><i class="fa fa-heart"></i></a>
                                </li>
                            </ul>
                            <h4 class="title"> <?php echo $row->username; ?></h4>
                            <p class="sub-title"><u><?php echo $row->gender.", ".calcAge($row->dob)." ".get_languageword('years');  ?></u></p>
                            <ul class="user-info">
                                <?php if(!empty($row->qualification)) { ?>
                                <li><strong><?php echo get_languageword('qualification'); ?>:</strong>  <?php echo $row->qualification; ?></li>
                                <?php } ?>
                                <?php if(!empty($row->city)) { ?>
                                <li><i class="fa fa-map-marker"></i> <?php echo $row->city; ?></li>
                                <?php } ?>
                            </ul>
                            <hr>

                            <p> <?php echo $row->profile; ?> </p>

                            <hr>
                            <?php 
                                if($row->show_contact!='None'){
                                    if($row->show_contact=='All' || $row->show_contact=='Email'){?>
                                    <h4><strong><?php echo get_languageword('email'); ?>: </strong> <?php echo $row->email; ?></h4>
                                    <?php }

                                     if($row->show_contact=='All' || $row->show_contact=='Mobile'){?>
                                    <h4><strong><?php echo get_languageword('phone'); ?>: </strong> <?php echo $row->phone; ?></h4>
                                    <?php }
                            }?>
                             <?php if($row->academic_class != 'no' || $row->non_academic_class !='no'){?>
                            <h4><strong><?php echo get_languageword('Student_Type'); ?>: </strong> 
                            <?php if($row->academic_class != 'no')
                                     echo get_languageword('Academic'); 
                                    
                                  if($row->non_academic_class !='no')
                                   echo ', '. get_languageword('Non_Academic'); ?></h4><?php } ?>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                        <div class="send-quote-block">
                            <h2 class="heading-line"><?php echo get_languageword('send_me_your_message'); ?>:</h2>
                            <?php 
                                    $course = "";
                                    if(!empty($row->lead_details)) {
                                        $course = $row->lead_details[0]->course_name;
                                    }
                                    $this->load->view('send_message', array('user_course_opts' => array(), 'to_user_type' => 'student', 'to_user_id' => $row->id, 'course' => $course)); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!--  Lead Details -->
            <?php if(!empty($row->lead_details)) { ?>
            <div class="row mtop7">
                <div class="col-sm-12">
                    <h2 class="heading-line"><?php echo get_languageword('requirement_details'); ?></h2>
                    <ul class="user-more-details">
                    <div class="media-left "></div>
                    <ul class ="user-more-details">
                        <?php foreach ($row->lead_details as $lead) { ?>
                            <li><div class="media-left"><?php echo get_languageword('title_of_requirement') ;?>:</div><div class="media-body"><?php echo $lead->title_of_requirement; ?></div></li>
                            <li><div class="media-left"><?php echo get_languageword('requirement_details') ;?>:</div><div class="media-body"><?php echo $lead->requirement_details; ?></div></li>
                            <li><div class="media-left"><?php echo get_languageword('course_seeking') ;?>:</div><div class="media-body"><?php echo $lead->course_name; ?></div></li>
                            <li><div class="media-left"><?php echo get_languageword('preferred_location') ;?>:</div><div class="media-body"><?php echo $lead->location_name.", ".$lead->parent_location_name; ?></div></li>                            
                            <li><div class="media-left"><?php echo get_languageword('preffered_teaching_type') ;?>:</div><div class="media-body"><?php echo $lead->teaching_type; ?></div></li>
                            <li><div class="media-left"><?php echo get_languageword('student_present_status') ;?>:</div><div class="media-body"><?php echo $lead->present_status; ?></div></li>
                            <li><div class="media-left"><?php echo get_languageword('priority_of_requirement') ;?>:</div><div class="media-body"><?php echo humanize($lead->priority_of_requirement); ?></div></li>
                            <li><div class="media-left"><?php echo get_languageword('duration_needed') ;?>:</div><div class="media-body"><?php echo $lead->duration_needed; ?></div></li>
                            <li><div class="media-left"><?php echo get_languageword('budget') ;?>:</div><div class="media-body"><?php echo get_system_settings('currency_symbol').$lead->budget." / ".humanize($lead->budget_type); ?></div></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <?php } ?>

            <!-- Gallery -->
            <?php if(!empty($row->student_gallery)) { ?>
            <div class="row mtop7">
                <div class="col-sm-12">
                    <h2 class="heading-line"><?php echo get_languageword('gallery'); ?></h2>
                </div>
                <div class="col-sm-8">
                    <div class="tab-content tabpill-content">

                        <?php $i=1; foreach ($row->student_gallery as $gallery) { ?>
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
                            <?php $i=1; foreach ($row->student_gallery as $gallery_thumbs) { ?>
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



        </div>
    </div>

    <?php } } ?>
    <!-- User Profile Details  -->