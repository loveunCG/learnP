<!-- Dashboard panel -->
<div class="dashboard-panel">
	<?php echo $this->session->flashdata('message');?>
	<div class="row">

		<?php 
				$attributes = array('name' => 'rate_tutor_form', 'id' => 'rate_tutor_form', 'class' => 'comment-form dark-fields');
				echo form_open('',$attributes);
			?>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Rate Tutor');?> </label>
					<div class="rating" <?php if(!empty($review_det->rating)) echo 'data-score='.$review_det->rating;?> ></div>
				</div>
			</div>

			<div class="col-sm-6 ">
				<div class="input-group ">
					<label><?php echo get_languageword('Comments');?></label>
					<?php
					$val = set_value('comments', (!empty($review_det->comments)) ? $review_det->comments : '');
					$element = array(
						'name'	=>	'comments',
						'id'	=>	'comments',
						'value'	=>	$val,
						'class' => 'form-control',
						'placeholder' => get_languageword('comments'),
						'maxlength' => '512'
					);
					echo form_textarea($element);
					?>
				</div>
			</div>
			<?php if(!empty($booking_id)) echo form_hidden('booking_id', $booking_id); ?>
			<?php if(!empty($review_det->id)) echo form_hidden('review_id', $review_det->id); ?>
			<div class="col-sm-12 ">
				<button class="btn-link-dark dash-btn" name="submitbutt" type="Submit"><?php if(!empty($review_det->id)) echo get_languageword('Update'); else echo get_languageword('Submit'); ?></button>
			</div>

		</form>
	</div>

</div>

<script src="<?php echo URL_FRONT_JS;?>jquery.js"></script>
<link rel="stylesheet" href="<?php echo URL_FRONT_CSS;?>jquery.raty.css">
<script src="<?php echo URL_FRONT_JS;?>jquery.raty.js"></script>
<script>

    /****** Tutor Avg. Rating  ******/
   $('div.rating').raty({

    path: '<?php echo RESOURCES_FRONT;?>raty_images',
    score: function() {
      return $(this).attr('data-score');
    },
    half: true,
    cancel  : true
   });

</script>

<!-- Dashboard panel ends -->