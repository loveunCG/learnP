<div class="container">
  <div class="row"> 
    
    <!--DIV Parent of Total Blocks in inner page Start-->
    <div class="parentDIV"> 
      
      <div class="col-lg-12">
      <div class="breacrumb">
      	<h1>Packages </h1>
      	<small> <a href="<?php echo URL_DOCTOR_DASHBOARD;?>"> Home </a> <i class="fa fa-angle-right"></i> Packages </small>
      </div>
      </div>
      <!--Left Side Search Start-->
       
      <!--Left Side Search End--> 
      
      <!--Right Side Doctors List Start-->
      <div class="col-lg-12">  
	   <?php echo $this->session->flashdata('message');?>
	   
       <div class="inner-pages department">
      <!--Doctor Profile List-->
 
    <ul>
    <?php
	foreach($packages as $package)
	{
		$image = URL_PUBLIC_UPLOADS .'packages/'. 'package-icon.png';
		
		if($package->image != '')
		{
		$image = URL_PUBLIC_UPLOADS . 'packages/' . $package->image;
		}
		?>
		<li><a href="javascript:void(0)" onclick="doPayment('<?php echo $package->package_id;?>','<?php echo $package->package_cost;?>');"> <img src="<?php echo $image;?>" alt="" title=""> <h3><?php echo $package->package_title;?> </h3> 
		<p>Package Cost <?php echo $package->package_cost;?></p>
		<p>Duration <?php echo $package->subscription_duration_in_days;?> Days</p>
		<p>Max. Visits/Month <?php echo $package->visits_max_allowed_per_month;?></p>
		</a></li>
	<?php } ?>
    </ul>
     
      <!--Doctor Profile List--> 
       </div> 
       <!--<div class="btn btn-default viewMore"><a href="#">View More</a></div>-->
 
      </div>
              
      <!--Right Side Doctors List End--> 
      
    </div>
    <!--DIV Parent of Total Blocks in inner pages End--> 
    
	
	<!--form-->
        <?php echo form_open(URL_PAYMENT,array('name'=>'package_subscription', 'id'=>'package_subscription')); ?>
         <input  type="hidden" name="package_id" id="package_id" value="">
         <input  type="hidden" name="amount" id="amount" value="">
       <?php echo form_close();?>
   <!--form-->
		 
  </div>
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