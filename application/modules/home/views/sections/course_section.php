<?php 
	  if(!empty($courses)): 
		foreach($courses as $row): 
?>
 <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="lession-card">
        <a href="<?php echo URL_HOME_SEARCH_TUTOR.'/'.$row->slug;?>">
            <figure class="imghvr-zoom-in">
                <div class="card">
                    <div class="card-img all-c">
                        <img src="<?php echo get_course_img($row->image); ?>" class="img-responsive" alt="">
                        <figcaption></figcaption>
                    </div>
                    <div class="card-content opc">
                        <h4 class="card-title" title="<?php echo $row->name; ?>"><?php echo $row->name; ?></h4>
                        <p class="card-info animated fadeIn" title="<?php echo $row->description; ?>"><?php if(!empty($row->description)) echo $row->description; else echo '&nbsp'; ?></p>
                    </div>
                </div>
            </figure>
        </a>
    </div>
</div>
<?php endforeach; else: ?>

<?php endif; ?>