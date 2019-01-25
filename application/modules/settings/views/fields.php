<!--Inner Dashboard Sub Menu-->
<?php $this->load->view('navigation');?>
<!--Inner Dashboard Sub Menu--> 

  <!-- Elements Of Web Site -->
  <?php $attributes = array('name'=>'tokenform','id'=>'tokenform');
		echo form_open('',$attributes) ?>
  <div class="container-fluid">
    <div class="row">
          <div class="col-lg-12">
        <div class="elements">
          <div class="panel panel-default theameOfPanle">
		  <div class="panel-heading main_small_heding"><?php echo isset($pagetitle) ? $pagetitle :  get_languageword('no_title');?>
                
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

			<!--Multi Operation-->
            <div class="form-group filerSear clearfix">       
              <div class="col-lg-3 col-sm-3 col-xs-12 padding-l">
               <select name="multioperation" id="multioperation" onchange="javascript:multioperationfunction(this.value, '<?php echo URL_SETTINGS_FIELDDELETE;?>')">
                <option selected="" disabled="" value=""><?php echo get_languageword('select');?></option>
				<option value="delete"><?php echo get_languageword('delete');?></option>				              
              </select>
              </div>              
            </div>
			<!--Multi Operation-->
                     
              <!-- Data table -->
              <div class="dateTable">
				<div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"'; ?>><?php echo $message;?></div>
                <table width="100%" class="digiTable" id="table-id">
                  <thead>
                    <tr>
                      <th><input id="checkbox-1" class="checkbox-custom selectall" name="checkbox-1" type="checkbox" onclick="selectall(this,'checkbox_class')">
                        <label for="checkbox-1" class="checkbox-custom-label" ></th>
					  <th><?php echo get_languageword('name');?> </th>
                      <th><?php echo get_languageword('key');?> </th>
                      <th> <?php echo get_languageword('type');?> </th>
                      <th><?php echo get_languageword('operations');?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td colspan="5"><?php echo get_languageword('loading_data_from_server');?></td></tr>
                  </tbody>
                </table>   
              
                
              </div>
              <!-- Data table --> 
            </div>
          </div>
        </div>
      </div> 
      
	<!--STATISTICS-->
	<?php //$this->load->view('statistics');?>
	<!--STATISTICS-->  
      
    </div>
  </div>
  </form>