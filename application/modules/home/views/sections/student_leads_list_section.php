<?php 
    $this->session->unset_userdata('req_from');
    if(!empty($student_leads_list)): 
        $user_id = $this->ion_auth->get_user_id();
		foreach($student_leads_list as $row): 
?>
<div class="box-border">
            <div class="row ">
                <!-- User Profile -->
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 no-padright">
                    <div class="profile-listing">
                        <?php

                            if ($this->ion_auth->logged_in()) {

                                if(get_system_settings('credits_for_viewing_lead') > 0 && !$this->home_model->is_already_viewed_the_lead($user_id, 'student_leads', $row->lead_id))
                                    $actions = 'data-toggle="modal" data-target="#myModal" onclick="get_lead_details(\''.$row->slug.'\', '.$row->lead_id.');" ';
                                else
                                    $actions = ' href="'.URL_VIEW_STUDENT_PROFILE.'/'.$row->slug.'/'.$row->lead_id.'" ';

                            } else {

                                $actions = 'data-toggle="modal" data-target="#myModal1" ';
                            }
                        ?>
                        <a <?php echo $actions; ?> ><img src="<?php if(!empty($row->photo) && file_exists(URL_UPLOADS_PROFILES_PHYSICAL.$row->photo)) echo URL_UPLOADS_PROFILES.$row->photo; else { if($row->gender == 'Male') echo URL_UPLOADS_PROFILES_STUDENT_MALE_DEFAULT_IMAGE; else echo URL_UPLOADS_PROFILES_STUDENT_FEMALE_DEFAULT_IMAGE; } ?>" alt="" class="img-responsive img-circle"></a>
                    </div>
                    <?php echo get_user_online_status($row->is_online); ?>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-lg-push-7 col-md-push-5 col-sm-push-5">
                    <div class="send-quote-block text-center">
                        <p class="teaching-experience"> <?php echo "<strong>".get_languageword('present_status')."</strong> ".$row->present_status; ?></p>
                        <p class="qualification"> <?php echo "<strong>".get_languageword('qualification')."</strong> ".$row->qualification; ?></p>
                        <div id ="profile-view" class="profile-view">

                            <a <?php echo $actions; ?> class="btn-link-dark" ><?php echo get_languageword('view_details'); ?> </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-5 col-sm-5 col-xs-12 col-lg-pull-3 col-md-pull-4 col-sm-pull-4">
                    <div class="user-profile-content">
                        <ul class="user-badges">
                            <li>
                                <a href="#" title="<?php echo get_languageword('priority_of_requirement'); ?>:" data-content="<?php echo humanize($row->priority_of_requirement); ?>" class="red-popover" data-toggle="popover" data-placement="top" data-trigger="hover"><i class="fa fa-heart"></i></a>
                            </li>
                        </ul>
                        <h4 class="title"><a <?php echo $actions; ?> > <?php echo character_limiter(ucwords($row->title_of_requirement), 100); ?></a></h4>
                        <p class="sub-title"><u> <?php echo $row->username;  ?></u></p>

                        <p><?php echo character_limiter($row->requirement_details, 400); ?> </p>

                    </div>
                </div>

            </div>
</div>
<?php endforeach; ?>

<?php endif; ?>