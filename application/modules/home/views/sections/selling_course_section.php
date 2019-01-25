<?php 
	  if(!empty($selling_courses)): 
		foreach($selling_courses as $row): 
?>
 <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="lession-card">
        <a href="<?php echo URL_HOME_BUY_COURSE.'/'.$row->slug;?>">
            <figure class="imghvr-zoom-in">
                <div class="card">
                    <div class="card-img all-c">
                        <img src="<?php echo get_selling_course_img($row->image); ?>" class="img-responsive" alt="">
                        <figcaption></figcaption>
                    </div>
                    <div class="card-content opc">
                        <h4 class="card-title" title="<?php echo $row->course_name; ?>"><?php echo $row->course_name; ?></h4>
                        <p class="card-info animated fadeIn" title="<?php echo $row->course_title; ?>"><?php if(!empty($row->course_title)) echo $row->course_title; else echo '&nbsp'; ?></p>
                    </div>
                </div>
            </figure>
        </a>
    </div>
</div>
<?php endforeach; else: ?>

<?php endif; ?>