<div class="container">
	<div class="row row-margin">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<div class="sign-block signin-left">
				<h2><span> <?php echo lang('forgot_password_heading');?></span></h2>
				<p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>

				<span class="error"><?php echo $message;?></span>
				<?php echo form_open("auth/forgot_password", 'id="forgot_password_form" class="form-signin  comment-form"');?>

					<div class="input-group ">
						<label><?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?><?php echo required_symbol();?></label>
						<?php echo form_input($identity);?>
					</div>

					<button class="btn-link-dark signin-btn center-block" type="submit" name="btnLogin"><?php echo lang('forgot_password_submit_btn');?></button>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>