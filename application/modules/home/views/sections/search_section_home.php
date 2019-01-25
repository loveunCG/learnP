<?php if(!empty($location_opts) || !empty($course_opts)) { 

        echo form_open(URL_HOME_SEARCH_TUTOR, 'id="search_form"');
?>

<ul class="home-search">


    <?php if(!empty($location_opts)) { ?>
    <li>
        <?php

                echo form_dropdown('location_slug[]', $location_opts, '', 'class="select-picker" required="required" ');

        ?>
    </li>
    <?php } ?>


    <?php if(!empty($course_opts)) { ?>
    <li>
        <?php

                echo form_dropdown('course_slug[]', $course_opts, '', 'class="select-picker" required="required" ');

        ?>
    </li>
    <?php } ?>


    <li>
        <button type="submit" class="btn btn-search"><i class="fa fa-search"></i><?php echo get_languageword('Search Your Teacher');?></button>
    </li>


</ul>

<?php 
        echo form_close();
?>

<style>
    label.error {
        color: #FF3300;
        float: left;
        margin-top: -30px;
    }
</style>


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
              $("#search_form").validate({
                  rules: {
                        "location_slug[]": {
                            required: true
                        },
                        "course_slug[]": {
                            required: true
                        }
                  },

                  messages: {
                        "location_slug[]": {
                            required: "<?php echo get_languageword('please_select_location'); ?>"
                        },
                        "course_slug[]": {
                            required: "<?php echo get_languageword('please_select_course'); ?>"
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




<?php } ?>
