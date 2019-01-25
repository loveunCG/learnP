<?php 
	  if(!empty($institute_list)): 
		foreach($institute_list as $row): 
?>
<div class="box-border">
            <div class="row ">
                <!-- User Profile -->
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 no-padright">
                    <div class="profile-listing">
                        <a href="<?php echo URL_HOME_INSTITUTE_PROFILE.'/'.$row->slug; ?>"><img src="<?php echo get_inst_img($row->photo); ?>" alt="" class="img-responsive img-circle"></a>
                    </div>

                    <?php echo get_user_online_status($row->is_online); ?>

                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 col-lg-push-7 col-md-push-5 col-sm-push-5">
                    <div class="send-quote-block text-center">
                        <p class="teaching-experience"><b><?php echo get_languageword('year_of_establishment'); ?></b>: <?php echo $row->teaching_experience; ?></p>
                        <p class="qualification"><b><?php echo get_languageword('free_demo')?></b>: <?php echo $row->free_demo; ?></p>
                        <div class="profile-view"><a href="<?php echo URL_HOME_INSTITUTE_PROFILE.'/'.$row->slug; ?>" class="btn-link-dark"><?php echo get_languageword('view_Profile'); ?></a></div>
                        <div class="profile-view"><a href="<?php echo URL_HOME_INSTITUTE_PROFILE.'/'.$row->slug.'#enroll-now'; ?>" class="btn-link-dark"><?php echo get_languageword('enroll_Now'); ?></a></div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-5 col-sm-5 col-xs-12 col-lg-pull-3 col-md-pull-4 col-sm-pull-4">
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
                        <h4 class="title"><a href="<?php echo URL_HOME_INSTITUTE_PROFILE.'/'.$row->slug; ?>"> <?php echo $row->username; ?></a></h4>
                        <p class="sub-title"><u> <?php echo $row->website; ?></u></p>

                        <p><?php echo $row->profile; ?> </p>

                    </div>
                </div>

            </div>
        </div>
<?php endforeach; ?>

<?php endif; ?>