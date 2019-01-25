<!-- News Scroller  -->
<?php 
		$this->load->model('home_model');

		$scroll_news = $this->home_model->get_scroll_news();

		if(!empty($scroll_news)) {
?>
<div class="news-scroll">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="news-scroll-block">
					<div class="marquee">

					<?php foreach ($scroll_news as $row) { ?>

						<span><a <?php if(!empty($row->url)) echo 'href="'.$row->url.'" target="_blank"'; ?> ><?php echo $row->title;?></a></span>
					
					<?php } ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<!-- News Scroller  -->