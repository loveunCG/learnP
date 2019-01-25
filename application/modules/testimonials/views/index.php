<?php $this->load->view('template/common/search');?>
<div class="container">
<div class="row">
<div class="col-lg-12">
<ol class="breadcrumb bdb">
 <li><a href="<?php echo URL_USER;?>"><?php echo get_languageword('dashboard');?></a></li>
  <li><?php echo get_languageword('packages');?></li>
</ol>
</div>
</div>
</div>

<div class="container">
<div class="row">

<div id="infoMessage"><?php echo print_message($message);?></div>
 
    <?php
	foreach($packages as $package)
	{
		$image = URL_PUBLIC_UPLOADS_PACKAGES . 'package-icon.png';
		
		if(isset($package->image) && $package->image != '' && file_exists('assets/uploads/packages/'.$package->image))
		{
		$image = URL_PUBLIC_UPLOADS_PACKAGES . $package->image;
		}
		?>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="package">
		<img src="<?php echo $image;?>" alt="" title="">
		 <h3><?php echo $package->package_name;?> </h3> 
		<p><i class="fa fa-angle-right"></i> <?php echo get_languageword('package_cost');?> <?php echo $package->package_cost;?></p>
		<p><i class="fa fa-angle-right"></i> <?php echo get_languageword('duration');?> <?php echo $package->subscription_duration_in_days;?> <?php echo get_languageword('days');?></p>
		<p><i class="fa fa-angle-right"></i> <?php echo get_languageword('no_of_quotes_provided');?> <?php echo $package->no_of_quotes;?></p>
	
		
		<div class="btn btn-default">
	<a href="<?php echo URL_PAYMENT_INDEX . DS . $package->package_id;?>"> 	<?php echo get_languageword('buy_now');?>	</a>
		</div>
		
		</div>
		</div>
	<?php } ?>
 

 
		  </div>
		  <!--form-->
        <?php echo form_open(URL_PAYMENT,array('name'=>'package_subscription', 'id'=>'package_subscription')); ?>
         <input  type="hidden" name="package_id" id="package_id" value="">
         <input  type="hidden" name="amount" id="amount" value="">
       <?php echo form_close();?>
   <!--form-->
</div>
 

<script>
/***payment***/
function doPayment(package_id,package_cost) 
{ 		
    var package_id = package_id;
	var package_cost = package_cost;
	$('#package_id').val(package_id);
	$('#amount').val(package_cost);
	$('#package_subscription').submit();  
}
</script>

