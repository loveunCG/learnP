<script src='<?php echo URL_ADMIN_JS;?>adminlte.min.js'></script>
<?php
if(isset($grocery) && $grocery == TRUE) 
{
?>
<?php foreach($js_files as $file): 
//echo basename($file).'<br>';
//if(in_array(basename($file), array('lazyload-min.js'))) {
?>
<script src="<?php echo $file; ?>"></script>
<?php //}
endforeach; ?>
<?php } ?>


<?php if(!empty($activemenu) && $activemenu == "tutor_selling_courses") { ?>
<script type="text/javascript" src="<?php echo URL_FRONT_JS;?>jquery.magnific-popup.js"></script>
<script> 
$(document).on('click', '.delete-icon-grocery', function() {

    return confirm("<?php echo get_languageword('Are you sure that you want to delete this record?'); ?>");
});
</script>
<?php } ?>



<!--
<script type="text/javascript">
$(document).ready(function(){
  $('.tDiv3').append('<a id="my_button" href="#">new button</a>');
});
</script>
-->
<!--<script src='<?php echo URL_ADMIN_JS;?>lib.min.js'></script>
<script src='<?php echo URL_ADMIN_JS;?>app.min.js'></script>-->
</section>
	</div>

		<footer class="main-footer">
			<div class="pull-right hidden-xs">
			CI Bootstrap Version: <strong>Build 20160707</strong>, 
			CI Version: <strong>3.1.0</strong>, 
			Elapsed Time: <strong>0.0810</strong> seconds, 
			Memory Usage: <strong>5.84MB</strong>
		</div>
		<?php if(isset($this->config->item('site_settings')->rights_reserved_by) && $this->config->item('site_settings')->rights_reserved_by != '') { ?>
				<span class="copy-right"><?php echo $this->config->item('site_settings')->rights_reserved_by;?></span>
		<?php } ?>
		</footer>
</div>
	
		</body>
</html>
<?php /*?>
</div>

<!--DashBoard Right Content Start--> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
<?php 
if(isset($grocery) && $grocery == TRUE) 
{
?>
<?php foreach($js_files as $file): ?>
<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<?php } else { ?>
<script src="<?php echo URL_ADMIN_JS;?>jquery-1.11.1.min.js"></script>
<?php } ?>
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php echo URL_ADMIN_JS;?>bootstrap.min.js"></script> 
<!-- Left Side Navigation JS--> 
<script src="<?php echo URL_ADMIN_JS;?>custom.js"></script> 
<script src="<?php echo URL_ADMIN_JS;?>jquery.nicescroll.js"></script> 
<!-- Left Side Navigation JS-->

<script src="<?php echo URL_ADMIN_JS;?>functions.js"></script>

<script src="<?php echo URL_ADMIN_JS;?>chosen.jquery.min.js"></script>

<!-- Data tables-->
<script type="text/javascript" language="javascript" src="<?php echo URL_ADMIN_DATATABLES;?>js/jquery.dataTables.js"></script> 

<!-- Select Styles--> 
<script src="<?php echo URL_ADMIN_JS;?>classie.js"></script> 
<script src="<?php echo URL_ADMIN_JS;?>selectFx.js"></script> 

<script>
	$(function() {
		$(".chzn-select").chosen();
	});
</script>

<script>
			(function() {
				[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
					new SelectFx(el);
				} );
			})();
		</script> 
<!-- Select Styles--> 

<!-- Tool Tip--> 
<script type="text/javascript">
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script> 
<!-- Tool Tip--> 

<!-- DatePicker--> 
<!--<script src="<?php echo URL_ADMIN_JS;?>bootstrap-datepicker.js"></script>-->
<script src="<?php echo URL_ADMIN_JS;?>bootstrap-datetimepicker.js"></script>
<!--DatePicker--> 



<!--ClockPicker--> 
<script type="text/javascript" src="<?php echo URL_ADMIN_JS;?>jquery-clockpicker.min.js"></script> 
<script type="text/javascript">
$('.clockpicker').clockpicker()
	.find('input').change(function(){
		console.log(this.value);
	});
var input = $('#single-input').clockpicker({
	placement: 'bottom',
	align: 'left',
	autoclose: true,
	'default': 'now'
});

// Manually toggle to the minutes view
$('#check-minutes').click(function(e){
	// Have to stop propagation here
	e.stopPropagation();
	input.clockpicker('show')
			.clockpicker('toggleView', 'minutes');
});
if (/mobile/i.test(navigator.userAgent)) {
	$('input').prop('readOnly', true);
}
</script> 
<!--ClockPicker--> 

<!--Multipule & Searcable Select Box--> 
<script type="text/javascript" src="<?php echo URL_ADMIN_JS;?>bootstrap-select.js"></script> 
<script>
  $(document).ready(function () {
    var mySelect = $('#first-disabled2');

    $('#special').on('click', function () {
      mySelect.find('option:selected').prop('disabled', true);
      mySelect.selectpicker('refresh');
    });

    $('#special2').on('click', function () {
      mySelect.find('option:disabled').prop('disabled', false);
      mySelect.selectpicker('refresh');
    });

    $('#basic2').selectpicker({
      liveSearch: true,
      maxOptions: 1
    });
  });
</script> 
<!--Multipule & Searcable Select Box-->

<!-- Data tables -->
<script type="text/javascript" language="javascript" class="init">
var datatablevar;
$(document).ready(function() {
	<?php
	if(isset($ajaxrequest)) { ?>
	datatablevar = $('#table-id').dataTable(	
	{
        <?php if(isset($ajaxrequest['disablesorting'])) {?>
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ <?php echo $ajaxrequest['disablesorting'];?> ] }
		],
		<?php } ?>
			
		<?php if(isset($ajaxrequest['url'])) {?>
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "<?php echo $ajaxrequest['url'];?>",
			"type": "POST",
			"data": function ( d ) {
				<?php if(isset($ajaxrequest['type']) && $ajaxrequest['type'] != '') {?>
					d.type = "<?php echo $ajaxrequest['type'];?>";
				<?php } else {?>
					d.type = "Category";
				<?php } ?>
				<?php if(isset($ajaxrequest['params']) && count($ajaxrequest['params']) > 0) 
				{
					foreach($ajaxrequest['params'] as $pakey => $paval)
					{ ?>
						d.<?php echo $pakey;?> = "<?php echo $paval;?>";
					<?php } ?>								
				<?php } ?>
			}
		},
		<?php } ?>
    }
	
	);
	<?php } ?>
	$('#datable-normal').dataTable({
		"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [] }
		]
    });
} );

function showhide()
{
	var val = $('#field_type').val();
	if(val == 'select')
	{
		$('#selectlist').show();
	}
	else
	{
		$('#selectlist').hide();
	}
}
showhide();
</script>
<?php if(isset($ckeditor)) {
	
?>
<!-- CK EDITOR -->
<script src="http://cdn.ckeditor.com/4.5.8/standard-all/ckeditor.js"></script>
<script>
   $(function() {
    $('.ckeditor').each(function(){    
    CKEDITOR.replace($(this).attr('id'), {
      extraPlugins: 'mathjax',
        mathJaxLib: 'http://cdn.mathjax.org/mathjax/2.6-latest/MathJax.js?config=TeX-AMS_HTML',
        height: 320
    });
  });   
   });   
</script>
<?php
} 
?>
<script src="<?php echo URL_ADMIN_JS;?>functions.js"></script>  


<!-- Select2 --> 
<script src="<?php echo URL_ADMIN_PLUGINS;?>select2<?php echo DS;?>select2.full.min.js"></script>

<script type="text/javascript">

function previewImages(input, id)
{
  var fileList = input.files;

  var anyWindow = window.URL || window.webkitURL;
  input.style.width = '100%';
  $('.preview-area').html('');
	 for(var i = 0; i < fileList.length; i++){
	  
	   var objectUrl = anyWindow.createObjectURL(fileList[i]);
		
	   $('#'+id).append('<img height="100" src="' + objectUrl + '" />');
	   
	   window.URL.revokeObjectURL(fileList[i]);

	 }
}
</script>
</body>
</html>
<?php */?>