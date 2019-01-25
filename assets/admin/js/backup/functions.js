//General Functions
function statustoggle(obj, targeturl, type,redirection)
{
	var ids = check = ''
	if(typeof(type) == 'undefined')
		type = 'single';
	if(type == 'single')
	{
		ids = $(obj).val();
		check = $(obj).is(':checked');
	}
	else
	{
		ids = obj;
		if(type == 'activate')
		check = 'true';
		else
		check = 'false';
	}
	var token = '';
	if($('[name="system_csrf"]').length)
	token = document.tokenform.system_csrf.value;
	
	$.ajax({
		   type:'POST',
		   url:targeturl,
		   data:{
			   ajax:1,
			   system_csrf:token,
			   id:ids,
			   status:check,
			   redirection:redirection
		   },
		   success:function(data){				   
				   var t = $.parseJSON( data );
				   if(redirection == 'yes')
				   {
					 window.location = t['url'];  
				   }
				   else
				   {
				   $('.flash_msg').show();
				   $('.flash_msg').html(" <div class='col-md-12'><div class='alert alert-success'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>"+t['action']+"!</strong> " +t['message'] +"</div></div>");
				   reload_table();
				   }
		   }
		   });
}

function delete_record(id, url,redirection)
{
	var redirection_var = 'no';
	if(redirection != 'undefined')
		redirection_var = redirection;
	$("#deleting_record_id").val(id);
	$("#deleting_record_id_url").val(url);
	$("#redirection").val(redirection_var);
	//reload_table();
}

function delete_record_final()
{
	var token = '';
	if($('[name="system_csrf"]').length)
	token = document.tokenform.system_csrf.value;
	$.ajax({
		   type:'POST',
		   url:$("#deleting_record_id_url").val(),
		   data:{
			   ajax:1,
			   system_csrf:token,
			   id:$("#deleting_record_id").val(),
			   redirection:$("#redirection").val()
		   },
		   success:function(data){				   
				   var t = $.parseJSON( data );
				   if(t['redirection'] == 'yes')
				   {
					  window.location = ''; 
				   }
				   else
				   {
				   if(t['status'] == 1)
				   {
						$('.flash_msg').html(" <div class='col-md-12'><div class='alert alert-success'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>"+t['action']+"!</strong> " +t['message'] +"</div></div>");
				   }
				   else
				   {
					  $('.flash_msg').html(" <div class='col-md-12'><div class='alert alert-error'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>"+t['action']+"!</strong> " +t['message'] +"</div></div>"); 
				   }
				   $('#deletemodal').modal('hide');
				   $('.flash_msg').show();
				   reload_table();
				   }
		   }
		   });
}

function reload_table()
{
  $('.selectall').prop('checked', false);
  $('#multioperation').val('');
  datatablevar.api().ajax.reload(null,false); //reload datatable ajax 
}

function multioperationfunction(val, delurl, toggleurl, redirection) 
{
   var redirection_var = 'no';
	if(redirection != 'undefined')
		redirection_var = redirection;
   var selected = 0;
   var ids = '';
   if($('.checkbox_class').length > 0) {
	   $('.checkbox_class').each(function(index, element){
		   if($(this).is(':checked'))
		   {
				selected++;
				ids += $(this).val() + ',';
		   }
	   });
   }
   if(selected == 0)
   {
	   alert("Please select at least one record");
	   $('#multioperation').val('');
	   return false;
   }
   if(val == 'activate' || val == 'deactivate') {
	   statustoggle(ids, toggleurl, val,redirection_var);
   }	   
   if(val == 'delete') {
	   $("#deletemodal").modal();
	   $("#deleting_record_id").val(ids);
	   $("#deleting_record_id_url").val(delurl);
	$("#redirection").val(redirection_var);	   
   }
}

function deselectall_check(classname)
{
	$('.'+classname).prop('checked', false);
}

function selectall(obj, classname)
{
	if($(obj).is(':checked'))
	$('.'+classname).prop('checked', true);
	else
	$('.'+classname).prop('checked', false);
}