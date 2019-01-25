    <!-- Contact Details -->
    <div class="container">
        <div class="row-margin">
            <div class="row ">
                <?php echo $this->session->flashdata('message'); ?>
                <div class="col-sm-12">
                    <h2 class="heading-line"><?php echo get_languageword('Contact Details');?></h2>
                </div>
                <?php if(!empty($this->config->item('contact_settings'))) { ?>
                <div class="col-lg-3 col-md-3 col-sm-6 ">
                    <h3 class="contact-head"><?php echo get_languageword('Contact Us');?></h3>
                    <ul class="contact-details">
                        <?php if(!empty($this->config->item('contact_settings')->general_inquiries)) { ?>
                        <li>
                            <h4><?php echo get_languageword('General Inquiries');?>:</h4>
                            <p><?php echo $this->config->item('contact_settings')->general_inquiries; ?></p>
                        </li>
                        <?php } ?>
                        <?php if(!empty($this->config->item('contact_settings')->media_requests)) { ?>
                        <li>
                            <h4><?php echo get_languageword('Media Requests');?>:</h4>
                            <p><?php echo $this->config->item('contact_settings')->media_requests; ?></p>
                        </li>
                        <?php } ?>
                        <?php if(!empty($this->config->item('contact_settings')->offline_support)) { ?>
                        <li>
                            <h4><?php echo get_languageword('Offline Support');?>:</h4>
                            <p><?php echo $this->config->item('contact_settings')->offline_support; ?></p>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
                <?php if(!empty($this->config->item('social_settings'))) { ?>
                <div class="col-lg-3 col-md-3 col-sm-6 no-padleft">
                    <h3 class="contact-head"><?php echo get_languageword('follow us');?></h3>
                    <ul class="contact-details">

                        <?php if(!empty($this->config->item('social_settings')->facebook)) { ?>
                        <li>
                            <h4><?php echo get_languageword('Facebook')?>:</h4>
                            <p><?php echo $this->config->item('social_settings')->facebook; ?></p>
                        </li>
                        <?php } ?>

                        <?php if(!empty($this->config->item('social_settings')->twitter)) { ?>
                        <li>
                            <h4><?php echo get_languageword('Twitter');?>:</h4>
                            <p><?php echo $this->config->item('social_settings')->twitter; ?></p>
                        </li>
                        <?php } ?>

                        <?php if(!empty($this->config->item('social_settings')->google)) { ?>
                        <li>
                            <h4><?php echo get_languageword('Google Plus')?>:</h4>
                            <p><?php echo $this->config->item('social_settings')->google; ?></p>
                        </li>
                        <?php } ?>

                        <?php if(!empty($this->config->item('social_settings')->youtube)) { ?>
                        <li>
                            <h4><?php echo get_languageword('YouTube')?>:</h4>
                            <p><?php echo $this->config->item('social_settings')->youtube; ?></p>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <!--Google Map -->
                    <div class="google-map" id="tutors_map"></div>
                    <!--Google Map/- -->
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h4><?php echo get_languageword('Address');?>:</h4>
                            <p><?php echo $this->config->item('site_settings')->address .', '.$this->config->item('site_settings')->city.', '. $this->config->item('site_settings')->state .', '.$this->config->item('site_settings')->country.', '.$this->config->item('site_settings')->zipcode ; ?>
                            </p>
                </div>
            </div>
            <div class="row mtop4">
                <div class="col-sm-12">
                    <h2 class="heading-line"><?php echo get_languageword('Contact Form');?></h2>
                </div>

                    <?php 
                            $attributes = 'id="contact_form" name="chekout" class="comment-form dark-fields"';
                            echo form_open(URL_HOME_CONTACT_US, $attributes);
                     ?>

                    <div class="col-sm-6 ">
                        <div class="input-group ">
                            <label><?php echo get_languageword('First Name'); ?> <span class="required"> *</span></label>
                            <input type="text" class="form-control" name="fname" value="<?php echo set_value('fname'); ?>" />
                            <?php echo form_error('fname'); ?>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <div class="input-group ">
                            <label><?php echo get_languageword('Last Name'); ?> <span class="required"> *</span></label>
                            <input type="text" class="form-control" name="lname" value="<?php echo set_value('lname'); ?>" />
                            <?php echo form_error('lname'); ?>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <div class="input-group ">
                            <label><?php echo get_languageword('E-mail Address'); ?> <span class="required"> *</span></label>
                            <input type="email" class="form-control" placeholder="example@gmail.com" name="email" value="<?php echo set_value('email'); ?>" />
                            <?php echo form_error('email'); ?>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <div class="input-group ">
                            <label><?php echo get_languageword('Subject'); ?> <span class="required"> *</span></label>
                            <input type="text" class="form-control" name="sub" value="<?php echo set_value('sub'); ?>" />
                            <?php echo form_error('sub'); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 ">
                        <div class="input-group ">
                            <label><?php echo get_languageword('Message'); ?></label>
                            <textarea rows="5" name="msg"><?php echo set_value('msg'); ?></textarea>
                            <?php echo form_error('msg'); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 ">

                        <button class="btn btn-link" name="Submit" type="Submit"><?php echo get_languageword('Send Message'); ?></button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- Contact Details -->

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
              $("#contact_form").validate({
                  rules: {
                        "fname": {
                            required: true
                        },
                        "lname": {
                            required: true
                        },
                        "email": {
                            required: true,
                            email: true
                        },
                        "sub": {
                            required: true
                        }
                  },

                  messages: {
                        "fname": {
                            required: "<?php echo get_languageword('please_enter_first_name'); ?>"
                        },
                        "lname": {
                            required: "<?php echo get_languageword('please_enter_last_name'); ?>"
                        },
                        "email": {
                            required: "<?php echo get_languageword('please_enter_email_id'); ?>"
                        },
                        "sub": {
                            required: "<?php echo get_languageword('please_enter_subject'); ?>"
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
</script>  

