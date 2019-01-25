<?php
        $attributes = 'id="send_message_form" class="form-quote" ';
        echo form_open('', $attributes);
?>

    <div class="input-group">
        <input type="text" class="form-control" name="name" id="name" placeholder="<?php echo get_languageword('Name');?>*" value="<?php echo set_value('name', ($this->session->userdata('username')) ? $this->session->userdata('username') : ''); ?>" />
        <?php echo form_error('name'); ?>
    </div>
    <?php if(!empty($user_course_opts)) { ?>
    <div class="dark-picker">
        <?php 
                $user_course_opts[''] = get_languageword('Select Course').'*';
                echo form_dropdown('course_slug1', $user_course_opts, set_value('course_slug1'), 'id="course_slug1" class="select-picker"  ').form_error('course_slug1');
        ?>
    </div>
    <?php } else { 

            if(!empty($course))
              echo form_hidden('course_slug1', $course);

    } ?>

    <div class="input-group">
        <input type="text" class="form-control" name="email" id="email" placeholder="<?php echo get_languageword('Email');?>*" value="<?php echo set_value('email', ($this->session->userdata('email')) ? $this->session->userdata('email') : ''); ?>" />
        <?php echo form_error('email'); ?>
    </div>
    <div class="input-group">
        <input type="text" class="form-control" name="phone" id="phone" placeholder="<?php echo get_languageword('Phone');?>*" value="<?php echo set_value('phone', ($this->session->userdata('phone')) ? $this->session->userdata('phone') : ''); ?>" />
        <?php echo form_error('phone'); ?>
    </div>
    <div class="input-group ">
        <textarea rows="3" name="msg" placeholder="<?php echo get_languageword('My message is').'....';?>*"><?php echo set_value('msg'); ?></textarea>
        <?php echo form_error('msg'); ?>
    </div>
    <p class="margin-bottom15"><?php echo get_languageword('We will never sell or rent your private info').'.' ?></p>
    <?php 
            $credits_for_sending_message = $this->config->item('site_settings')->credits_for_sending_message;
            if($credits_for_sending_message > 0) {
                echo '<p class="margin-bottom15"><strong>'.get_languageword('Credits required for sending message').' : '.$this->config->item('site_settings')->credits_for_sending_message.'</strong></p>';
            }
        ?>
    <?php
            echo form_hidden('to_user_type', $to_user_type);
            echo form_hidden('to_user_id', $to_user_id);
    ?>
    <button type="submit" class="btn-link-dark"><?php echo get_languageword('send_message'); ?></button>
</form>

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
              $("#send_message_form").validate({
                  rules: {
                        "name": {
                            required: true
                        },
                        "course_slug1": {
                            required: true
                        },
                        "email": {
                            required: true,
                            email: true
                        },
                        "phone": {
                            required: true
                        },
                        "msg": {
                            required: true
                        }
                  },

                  messages: {
                        "name": {
                            required: "<?php echo get_languageword('please_enter_name'); ?>"
                        },
                        "course_slug1": {
                            required: "<?php echo get_languageword('please_select_course'); ?>"
                        },
                        "email": {
                            required: "<?php echo get_languageword('please_enter_email_id'); ?>"
                        },
                        "phone": {
                            required: "<?php echo get_languageword('please_enter_phone'); ?>"
                        },
                        "msg": {
                            required: "<?php echo get_languageword('please_enter_your_message'); ?>"
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