<script>
$(document).ready(function () {	   
   $("#loadMore").click(function(e){
			e.preventDefault();
			var page = $(this).data('val');
			getMore(page);

	});
});
var getMore = function(page){
	//alert(page);
	$("#loader").show();
	$("#loadMore").hide();
	//$(".loadmore").hide();
	
	$.ajax({
		url:"<?php echo site_url(uri_string()) ?>",
		type:'POST',
		data: {page:page,<?php echo $this->security->get_csrf_token_name();?>:"<?php echo $this->security->get_csrf_hash();?>"}
	}).done(function(response){
		var incpage = $('#loadMore').data('val')+1;
		$(".ibox").last().append(response);
		$("#loader").hide();
		$('#loadMore').data('val', incpage);
		//scroll();
		var scrollTop = $(window).scrollTop();
		var height = $(window).height();
		//alert(scrollTop+'@@'+height);
		window.scroll(0, scrollTop);
		//document.getElementById( 'loadMore' ).scrollIntoView();
		
		var totalrecords = <?php echo $total_quotes;?>;
		//var displayingrecords = incpage*<?php echo $per_page;?>+1+<?php echo $per_page;?>;
		
		var displayingrecords = incpage*<?php echo $per_page;?>;
		//alert(totalrecords+'@@'+displayingrecords+'@@'+<?php echo $per_page;?>);
		if(displayingrecords < totalrecords)
		{
			$("#loader").hide();
			$("#loadMore").show();
			//$(".loadmore").show();
		}
		else
		{
			$('#loadMore').hide();
			$(".loadmore").hide();
			$("#loader").hide();				
		}
	});
};

var getPage = function(page, idname){
	//alert(page);
	$("#loader").show();
	$("#loadMore").hide();
	//$(".loadmore").hide();
	
	$.ajax({
		url:"<?php echo site_url(uri_string()) ?>",
		type:'POST',
		data: {page:page,idname:idname,<?php echo $this->security->get_csrf_token_name();?>:"<?php echo $this->security->get_csrf_hash();?>"}
	}).done(function(response){
		var incpage = $('#loadMore').data('val')+1;
		$("#"+idname).html(response);
		$("#loader").hide();
		$('#loadMore').data('val', incpage);
		scroll();
		
		var totalrecords = <?php echo $total_quotes;?>;
		var displayingrecords = incpage*<?php echo $per_page;?>+1+<?php echo $per_page;?>;
		if(displayingrecords < totalrecords)
		{
			$("#loader").hide();
			$("#loadMore").show();
			//$(".loadmore").show();
		}
		else
		{
			$('#loadMore').hide();
			$(".loadmore").hide();
			$("#loader").hide();				
		}
	});
};
</script>