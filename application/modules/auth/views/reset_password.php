<div class="container">
  <div class="row row-margin">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="sign-block signin-left">
        <h2><i class="fa fa-key"></i><span> <?php echo lang('reset_password_heading'); ?></span></h2>

        <span class="error"><?php echo $message;?></span>
        <?php $attributes = array('id'=>'reset_form','name'=>'reset_form', 'class' => 'form-signin  comment-form');
              echo form_open('auth/reset_password/' . $code, $attributes);
            ?>

          <div class="input-group ">
            <label><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?><?php echo required_symbol();?></label>
            <?php echo form_input($new_password);?>
          </div>

          <div class="input-group ">
            <label><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?><?php echo required_symbol();?></label>
            <?php echo form_input($new_password_confirm);?>
          </div>

          <?php echo form_input($user_id);?>
          <?php echo form_hidden($csrf); ?>

          <button class="btn-link-dark signin-btn center-block" type="submit" name="btnLogin"><?php echo lang('reset_password_submit_btn');?></button>
        <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>