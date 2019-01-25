  <!-- Elements Of Web Site -->
  <?php $attributes = array('name'=>'tokenform','id'=>'tokenform');
		echo form_open('',$attributes) ?>
  <div class="container-fluid">
    <div class="row">
          <div class="col-lg-12">
        <div class="elements">
          <div class="panel panel-default theameOfPanle">
		  <div class="panel-heading main_small_heding"><?php if(isset($pagetitle)) echo $pagetitle; else 'No Title'?>
                
			<?php if(isset($helptext) && count($helptext) > 0) { ?>
			<div class="btn digi-trash digi-remove pull-right help" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">  <img src="<?php echo URL_ADMIN_IMAGES?>help.png"> </div>
			<?php } ?>
              <!--Help--> 
           
            
              <!-- Help --> 
              
            </div>
            <div class="panel-body">
            <!--Help Collapse-->
              <?php if(isset($helptext) && count($helptext) > 0) { ?>
			  <div class="collapse" id="collapseExample">
                <div class="well help_coll"> 
                <ul>
                <?php foreach($helptext as $mess) {?>
				<li><span class="glyphicon glyphicon-ok-circle"></span><?php echo $mess;?></li>
				<?php } ?>                                              
                </ul>
                </div>
              </div>
			  <?php } ?>
            <!--Help Collapse-->
			            
                     
              <!-- Data table -->
              <div class="dateTable">              
				
				<div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"'; ?>><?php echo $message;?></div>
				
                <table width="100%" class="digiTable">
                  <thead>
                    <tr>
                      <th><?php echo get_languageword('language');?></th>                      
                      <th><?php echo get_languageword('operations');?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php					
					if(count($records) > 0) 
					{
						foreach($records as $index => $record)
						{
							if($record == 'lang_id' || $record == 'lang_key') continue;
						?>
					<tr>                      
                      <td><?php echo ucfirst($record);?></td>
                      
                      <td>
						<?php if($record != 'english') { ?>
						<div class="digiCrud">							
							<a data-toggle="modal" data-target="#deletemodal2" onclick="delete_record_local('<?php echo $record;?>')">
								<i class="flaticon-round73" data-toggle="tooltip" data-placement="left" title="Remove"></i>
							</a>
						</div>
						<div class="digiCrud">
							<a href="<?php echo URL_LANGUAGE_ADDLANGUEGE?>/<?php echo $record;?>">
								<i class="flaticon-pencil124" data-toggle="tooltip" data-placement="top" title="Edit"></i>
							</a>
						</div>
						<div class="digiCrud">
							<a href="<?php echo URL_LANGUAGE_ADDLANGUEGEPHRASES?>/<?php echo $record;?>">
								<i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="<?php echo get_languageword('Add Language Words')?>"></i>
							</a>
						</div>
						<?php } ?>
                        </td>
                    </tr>
						<?php }} else {
						?>
						<tr><td colspan="4" align="center"><?php echo MSG_NO_RECORDS;?> <?php echo get_languageword('click');?> <a href="<?php echo URL_LANGUAGE_ADDLANGUEGE;?>/add"><?php echo get_languageword('here');?></a> <?php echo get_languageword('to_add');?></td></tr>
						<?php
					}?>
                                           
                  </tbody>
                </table>                
              </div>
              <!-- Data table --> 
            </div>
          </div>
        </div>
      </div>
	  
	  
     
<script>
function delete_record_local(id)
{
	var str = "<?php echo URL_LANGUAGE_DELETE; ?>/" + id;
	$("#delete_url").html('<a href="'+str+'"><?php echo get_languageword('yes');?></a>');
}
</script>	 
      
	<!--STATISTICS-->
	<?php if($showstatistics) $this->load->view('statistics');?>
	<!--STATISTICS-->  
      
    </div>
  </div>
  </form>