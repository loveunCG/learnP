<!--Search 
================================================== -->
<div class="header">
	<div class="container">
		<div class="row mspace">
			<div class="col-lg-10 col-lg-offset-1">
				<?php $attributes = array('name'=>'searchform','id'=>'searchform');
					echo form_open('home/search',$attributes) ?>

					<div class="searchbar text-center " style="margin-top:150px;">
					<?php $records = $this->quotes_model->getAuthors('Active');
					$authors = array();
					if(count($records)>0)
					{
						foreach($records as $record):
							$authors[$record->term_id] = $record->term_title;
						endforeach;
					}
					
					?>
						<ul class="sea">
							
							<li class="styled-select">
								<?php /*?>
								<select id="author" name="author">
									<option selected disabled value="" value=""><?php echo get_languageword('select_author');?></option>
									<?php if(count($authors)>0) {
										foreach($authors as $key=>$val) :?>
									<option value="<?php echo $key.'-'.$val;?>"><?php echo $val;?></option>
									<?php endforeach;
									} ?>
								</select>
								<?php */?>
								<input type="text" name="author_input" id="author_input" class="searchinput" placeholder="<?php echo get_languageword('search_authors_here');?>" onclick="loadauthors(this.value)" onkeyup="loadauthors(this.value)">
								<input type="hidden" name="author" id="author">
								<input type="hidden" name="author_id" id="author_id">
								<div id="authors_div" class="sea-author" name="authors_div" style="display:none;"></div>
							</li>
							

							<li id="search-inputfield">
								<input type="text" name="topic" id="topic" class="searchinput" placeholder="<?php echo get_languageword('search_quotes_here');?>" onclick="loadtopics(this.value)" onkeyup="loadtopics(this.value)">
								<div id="topics_div" class="sea-author" name="topics_div" style="display:none;"></div>
							</li>

							
							<li>
								<button type="submit" name="srchbtn" class="searchbtn "><i class="glyphicon glyphicon-search "></i></button>
							</li>
							
						</ul>

					</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>

<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
<script type="text/javascript"> 
function loadtopics(val)
{
	var author_id = $('#author_id').val();
	$.ajax({
		type: "post",
		url : '<?php echo SITEURL;?>home/topics',
		dataType: "json",
		data:'<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>&term='+val+'&author_id='+author_id,
		success: function( data ) {
			//alert(data);
				$('#topics_div').show();
				//dta = $.parseJSON(data);
   				$('#topics_div').html(data);
   				//$('#topics_div').trigger("liszt:updated");
		}
	});
}

function loadauthors(val)
{
	$.ajax({
		type: "post",
		url : '<?php echo SITEURL;?>home/authors',
		dataType: "json",
		data:'<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>&term='+val,
		success: function( data ) {
				$('#authors_div').show();
   				$('#authors_div').html(data);
		}
	});
}

function assign(id, slug, value)
{
	$('#author').val(id+'-'+slug);
	$('#author_input').val(value);
	$('#author_id').val(id);
	$('#authors_div').hide();
}


/*
jQuery(document).ready(function($) {
$('#author').autocomplete({
	source: function( request, response ) {
		$('#authors_div').hide();
		$.ajax({
			type: "post",
			url : '<?php echo SITEURL;?>home/authors',
			dataType: "json",
			data: {
			   term: request.term,
			   <?php echo $this->security->get_csrf_token_name();?>:'<?php echo $this->security->get_csrf_hash(); ?>'
			},
			 success: function( data ) {
			  response(data); 
			}
		});
	},
	autoFocus: true,
	minLength: 1      	
  });
});
*/
</script>