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
               <select name="multioperation" id="multioperation" onchange="javascript:multioperationfunction(this.value, '<?php echo URL_AUTH_DELETE;?>', '<?php echo URL_AUTH_STATUSTOGGLE;?>','yes')">
                <option selected="" disabled="" value=""><?php echo get_languageword('select');?></option>
				<option value="delete"><?php echo get_languageword('delete');?></option>
				<option value="activate"><?php echo get_languageword('activate');?></option>
				<option value="deactivate"><?php echo get_languageword('de_activate');?></option>                
              </select>
              </div>
              
            </div>
			<!--Multi Operation-->
                     
              <!-- Data table -->
              <div class="dateTable">
				<div class="flash_msg" <?php echo (empty($message)) ? 'style="display:none;"' : 'style="display:block;"'; ?>><?php echo $message;?></div>
                <table width="100%" class="digiTable" id="datable-normal">
                  <thead>
                    <tr>
                      <th><input id="checkbox-0" class="checkbox-custom selectall" name="checkbox-0" type="checkbox" onclick="selectall(this,'checkbox_class')">
                        <label for="checkbox-0" class="checkbox-custom-label" ></th>
						<th><?php echo get_languageword('name');?></th>
						<th><?php echo get_languageword('created_date');?> </th>
						<th> <?php echo get_languageword('email');?> </th>
						
						<th><?php echo get_languageword('operations');?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($users as $user):?>
					<tr>
                      <td><input id="checkbox-<?php echo $user->id;?>" class="checkbox-custom checkbox_class" name="ids[]" type="checkbox" value="<?php echo $user->id;?>" onclick="javascript:deselectall_check('selectall')">
                        <label for="checkbox-<?php echo $user->id;?>" class="checkbox-custom-label"> </label></td>
					  <td>
					  <?php
					  
					  $image = URL_PUBLIC_UPLOADS_PROFILES.'user.jpg';
					 if(isset($user->logo_profile_image) && $user->logo_profile_image != '' && file_exists('assets/uploads/profiles/thumbs/'.$user->logo_profile_image)) 
					 {
						$image = URL_PUBLIC_UPLOADS_PROFILES_THUMBS . $user->logo_profile_image;
					 }
					  ?>
					  <img src="<?php echo $image;?>" class="img-responsive"/> <span> <?php echo $user->first_name; if(!empty($user->last_name)) echo ' '.$user->last_name;?> </span></td>
                      <td><?php echo date('d M, Y', $user->created_on);?></td>
                      <td><?php echo $user->email;?></td>
                      
                      <td>
					  <?php if(getCurrentUserId() != $user->id) {?>
					  <div class="digiCrud"> 
					  <i class="flaticon-round73" data-toggle="modal" data-target="#deletemodal" data-placement="left" title="Remove" onclick="delete_record(<?php echo $user->id;?>,'<?php echo URL_AUTH_DELETE;?>','yes')">
					  </i>
					  </div>
					  <?php } ?>
                        <div class="digiCrud">
						<a href="<?php echo URL_AUTH_EDIT_USER;?>/<?php echo $user->id;?>">
						<i class="flaticon-pencil124" data-toggle="tooltip" data-placement="top" title="Edit"></i>
						</a>
						</div>
                         
                        <div class="digiCrud">
                          <div class="slideThree slideBlue">
                            <?php 
							$checked = '';
							if($user->active == '1')
							$checked = ' checked';
							if(getCurrentUserId() == $user->id)
							{
							?>
							<input type="checkbox" value="<?php echo $user->id;?>" id="status_<?php echo $user->id;?>" name="check_<?php echo $user->id;?>"<?php echo $checked;?> disabled/>
							<?php	
							}
							else
							{
							?>
							<input type="checkbox" value="<?php echo $user->id;?>" id="status_<?php echo $user->id;?>" name="check_<?php echo $user->id;?>" onclick="statustoggle(this, '<?php echo URL_AUTH_STATUSTOGGLE;?>')"<?php echo $checked;?>/>
							<?php } ?>
							<label for="status_<?php echo $user->id;?>"></label>  
                          </div>
                        </div></td>
                    </tr>
					<?php endforeach;?>
                  </tbody>
                </table>   
              
                
              </div>
              <!-- Data table --> 
            </div>
          </div>
        </div>
      </div> 
      
	<!--STATISTICS-->
	<?php if($showstatistics) $this->load->view('statistics');?>
	<!--STATISTICS-->  
      
    </div>
  </div>
  </form>