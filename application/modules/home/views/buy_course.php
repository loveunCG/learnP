   <!-- >> Blog-->
   <?php $this->session->unset_userdata('req_from'); ?>
    <section class="blog-content">
        <div class="container">
            <div class="row row-margin">

                <?php echo $this->session->flashdata('message'); ?>

                <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">

                    <?php if( (!empty($record->preview_image) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$record->preview_image)) || (!empty($record->preview_file) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$record->preview_file)) ) { ?>
                    <div class="play-video">

                        <?php if( !empty($record->preview_image) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$record->preview_image) ) { ?>
                            <img src="<?php echo URL_PUBLIC_UPLOADS2.'course_curriculum_files/'.$record->preview_image; ?>" class="img-responsive" alt="<?php echo $record->preview_image; ?>">
                        <?php } ?>

                        <?php if( !empty($record->preview_file) && file_exists(URL_PUBLIC_UPLOADS.'course_curriculum_files/'.$record->preview_file) ) { 

                                $ext = pathinfo($record->preview_file, PATHINFO_EXTENSION);

                                if(in_array($ext, unserialize(VIDEO_FORMATS)))
                                    $icls = 'fa fa-play-circle-o';
                                else if(in_array($ext, unserialize(AUDIO_FORMATS)))
                                    $icls = 'fa fa-file-audio-o';
                                else if(in_array($ext, unserialize(IMAGE_FORMATS)))
                                    $icls = 'fa fa-image';
                                else
                                    $icls = 'fa fa-file-text-o';

                                $file_src = URL_PUBLIC_UPLOADS2.'course_curriculum_files/'.$record->preview_file;
                            ?>
                        <a class="videopopUp" href="<?php echo $file_src; ?>">
                            <i title="<?php echo get_languageword('Click_to_view'); ?>" class="pop-original <?php echo $icls; ?>"></i>
                        </a>
                        <?php } ?>

                    </div>
                    <?php } ?>

                    <h3><?php if(!empty($record->course_title)) echo $record->course_title; ?></h3>
                    <ul class="related-videos">
                        <li>by <?php if(!empty($record->username)) echo '<a href="'.URL_HOME_TUTOR_PROFILE.'/'.$record->tutor_slug.'">'.$record->username.'</a>'; ?></li>
                        <li><?php if(!empty($record->updated_at)) echo date('M jS, Y', strtotime($record->updated_at)); ?></li>

                        <li> <a href="<?php echo URL_HOME_BUY_COURSES; ?>"><?php if(!empty($record->course_name)) echo $record->course_name; ?></a></li>
                    </ul>


                    <!-- Video Description-->
                    <?php if(!empty($record->description)) echo $record->description; ?>
                    <!-- /Video Description-->

                    <!-- Curriculam -->
                    <?php if(!empty($record->sellingcourse_curriculum)) { ?>
                    <h2 class="heading-line mtop4"><?php echo get_languageword('curriculum'); ?></h2>
                    <ul class="list-group">
                        <?php foreach ($record->sellingcourse_curriculum as $key => $value) { 

                                if(!empty($value->file_ext)) {

                                    $ext = $value->file_ext;
                                    if(in_array($ext, unserialize(VIDEO_FORMATS)))
                                        $iclass = 'fa fa-play-circle';
                                    else if(in_array($ext, unserialize(AUDIO_FORMATS)))
                                        $iclass = 'fa fa-file-audio';
                                    else if(in_array($ext, unserialize(IMAGE_FORMATS)))
                                        $iclass = 'fa fa-image';
                                    else
                                        $iclass = 'fa fa-file-text';

                                } else {

                                    $iclass = 'fa fa-link';
                                }
                        ?>
                        <li class="list-group-item">
                            <?php echo $value->title; ?>
                            <ul class="lessions-list">
                                <li><font color="#e27d7f"><i class="<?php echo $iclass; ?>"></i></font></li>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                    <!-- /Curriculam -->

                    <!-- Related Items-->
                    <?php if(!empty($more_selling_courses) && count($more_selling_courses) > 1) { ?>
                    <h2 class="heading-line mtop4">More from This Tutor Name</h2>
                    <div class="row">

                        <?php foreach($more_selling_courses as $row) { 

                                if($row->sc_id != $record->sc_id) {
                            ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <!-- Sigle blog post -->
                            <div class="blog-card">
                                <div class="blog-card-img related-itm-img">
                                    <a href="<?php echo URL_HOME_BUY_COURSE.'/'.$row->slug;?>">
                                        <figure class="imghvr-zoom-in "><img src="<?php echo get_selling_course_img($row->image); ?>" alt="" class="img-responsive">
                                            <figcaption>
                                                <span class="btn btn-read"><?php echo get_languageword('Get_This_Course'); ?></span>
                                            </figcaption>
                                        </figure>
                                    </a>
                                    <div class="blog-card-ribbon"><?php echo $row->course_name; ?></div>
                                </div>
                                <p class="related-link"><a href="<?php echo URL_HOME_BUY_COURSE.'/'.$row->slug;?>">  <?php echo $row->course_title; ?> </a></p>
                                <ul class="related-videos">
                                    <li>
                                        <?php if(!empty($record->updated_at)) echo date('M jS, Y', strtotime($record->updated_at)); ?>
                                    </li>
                                    <li> <?php echo $this->config->item('site_settings')->currency_symbol.' '.$row->course_price; ?></li>
                                </ul>
                            </div>
                            <!-- Sigle blog post Ends -->
                        </div>
                        <?php } } ?>

                    </div>
                    <?php } ?>
                    <!-- /Related Items-->
                </div>
                <!-- Sidebar/Widgets bar -->
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <!-- Price Widget -->
                    <div class="get-video-course">
                        <h2 class="sell-price"> <?php if(!empty($record->course_price)) echo $this->config->item('site_settings')->currency_symbol.' '.$record->course_price; ?> </h2>

                        <div class="mobile-effect">

                            <a href="<?php echo URL_HOME_CHECKOUT.'/'.$record->slug; ?>" class="btn-accept" ><?php echo get_languageword('Buy_This_Course'); ?></a>

                        </div>

                        <ul class="list">
                            <?php if(!empty($record->sellingcourse_curriculum)) { ?>
                            <li class="list-item">
                                <span class="list-left"><?php echo get_languageword('lectures'); ?></span>
                                <span class="list-right"><?php echo count($record->sellingcourse_curriculum); ?></span>
                            </li>
                            <?php } ?>
                            <?php if(!empty($record->skill_level)) { ?>
                            <li class="list-item">
                                <span class="list-left"><?php echo get_languageword('Skill_Level'); ?></span>
                                <span class="list-right" title="<?php echo $record->skill_level; ?>"><?php echo $record->skill_level; ?></span>
                            </li>
                            <?php } ?>
                            <?php if(!empty($record->languages)) { ?>
                            <li class="list-item">
                                <span class="list-left"><?php echo get_languageword('languages'); ?></span>
                                <span class="list-right" title="<?php echo $record->languages; ?>">
                                    <?php echo $record->languages; ?>
                                </span>
                            </li>
                            <?php } ?>
                            <?php if(!empty($record->max_downloads)) { ?>
                            <li class="list-item">
                                <span class="list-left" title="<?php echo get_languageword('Maximum_Downloads'); ?>"><?php echo get_languageword('Max_Downloads'); ?></span>
                                <span class="list-right"> <?php echo $record->max_downloads; ?> </span>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- /Price Widget -->
                    <!-- Tutor Widget -->
                    <div class="get-video-course mtop4 text-center">
                        <?php if(!empty($record->photo) || !empty($record->username)) { ?>
                        <a href="<?php echo URL_HOME_TUTOR_PROFILE.'/'.$record->tutor_slug; ?>">
                            <div class="profile-img"><img class="img-responsive img-circle center-block " src="<?php echo get_tutor_img($record->photo, $record->gender); ?>" alt="<?php echo $record->username; ?>"></div>
                            <h4><?php if(!empty($record->username)) echo $record->username; ?></h4>
                        </a>
                        <?php } ?>
                        <?php if(!empty($record->profile)) { ?>
                        <p class="blog-info-text"><?php echo $record->profile; ?> <a href="<?php echo URL_HOME_TUTOR_PROFILE.'/'.$record->tutor_slug; ?>" class="link">Read More</a></p>
                        <?php } ?>

                    </div>
                    <!-- /Tutor Widget-->

                    <!-- Attachment Widget-->
                    <?php if(!empty($is_purchased) && $is_purchased->max_downloads > 0) { ?>
                    <div class="get-video-attachment mtop4">
                        <h4><?php echo get_languageword('attachments'); ?></h4>
                        <ul>
                            <li><a href="<?php echo URL_STUDENT_COURSE_PURCHASES; ?>"><i class="fa fa fa-download"></i> <?php echo get_languageword('Download'); ?></a></li>
                        </ul>
                    </div>
                    <?php } ?>
                    <!-- /Attachment Widget -->

                </div>
            </div>
        </div>
    </section>
