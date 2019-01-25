<script>
		function sendsms(id)
		{
			$('#info_div_'+id).fadeIn('slow').html('<div class="alert alert-danger top-space"><?php echo get_languageword('processing_please_wait');?></div>').delay(3000).fadeOut('slow');
			
			var mobileno = $('#smsform_'+id).find("input[id=mobile_number]").val();			
			//alert(mobileno);
			if (!(/^[0-9]+$/.test(mobileno))) {
				$('#info_div_'+id).fadeIn('slow').html('<div class="alert alert-danger top-space">'+'<?php echo get_languageword('please_enter_valid_mobile_number');?>'+'</div>').delay(3000).fadeOut('slow');
				return;
			}
		
			var dataString = $($('#smsform_'+id)).serialize();
			document.getElementById("smsform_"+id).addEventListener("click", function(event){
				event.preventDefault()
			});
			$.ajax({
                type: "POST",
                data: dataString,
                url: "<?php echo URL_QUOTES_SENDSMS;?>",
                cache: false,
                success: function(d) {
					var res = $.parseJSON( d );
					
					if (res.status == 'success') {
                        $('#info_div_'+id).fadeIn('slow').html('<div class="alert alert-success top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
                    } else {
                        $('#info_div_'+id).fadeIn('slow').html('<div class="alert alert-danger top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
                    }
                    //$("#get-ticket-now").attr("disabled", false);
                }
            });
		}
		
		function sendemail(id)
		{
			$('#info_div_'+id).fadeIn('slow').html('<div class="alert alert-danger top-space"><?php echo get_languageword('processing_please_wait');?></div>').delay(3000).fadeOut('slow');
			
			var dataString = $($('#emailform_'+id)).serialize();
			var email = $('#emailform_'+id).find("input[type=email]").val();
			var quote_iid = $('#emailform_'+id).find("input[id=quote_iid]").val();
			
			document.getElementById("emailform_"+id).addEventListener("click", function(event){
				event.preventDefault()
			});
			
			
			if((email == '' || quote_iid == '')) {
				$('#info_div_'+id).fadeIn('slow').html('<div class="alert alert-danger top-space"><?php echo get_languageword('please_enter_email');?></div>').delay(3000).fadeOut('slow');
			return;
			}
			$.ajax({
                type: "POST",
                data: dataString,
                url: "<?php echo URL_QUOTES_SENDEMAIL;?>",
                cache: false,
                success: function(d) {
					var res = $.parseJSON( d );
					if (res.status == 'success') {
                        $('#info_div_'+id).fadeIn('slow').html('<div class="alert alert-success top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
                    } else {
                        $('#info_div_'+id).fadeIn('slow').html('<div class="alert alert-danger top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
                    }
                    //$("#get-ticket-now").attr("disabled", false);
                }
            });
		}
		
		function savequote(quoteid)
		{
			$('#info_div_'+quoteid).fadeIn('slow').html('<div class="alert alert-danger top-space"><?php echo get_languageword('processing_please_wait');?></div>').delay(3000).fadeOut('slow');
			
			var quoteid = quoteid;
			if(quoteid=='')
				return;
			
			$.ajax({
                type:"POST",
                data:"<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>&quoteid="+quoteid,
                url :"<?php echo URL_QUOTES_SAVEQUOTE;?>",
                cache: false,
                success: function(d) {
					var res = $.parseJSON( d );
					if (res.status == 'success') {
                        $('#info_div_'+quoteid).fadeIn('slow').html('<div class="alert alert-success top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
						 //window.location.reload();
					} else {
                        $('#info_div_'+quoteid).fadeIn('slow').html('<div class="alert alert-danger top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
                    }
                    //$("#get-ticket-now").attr("disabled", false);
                }
            });
		}
		
		function deletequote(quoteid)
		{
			if(confirm('<?php echo get_languageword('are_you_sure')?>?'))
			{
			$('#info_div').fadeIn('slow').html('<div class="alert alert-danger top-space"><?php echo get_languageword('processing_please_wait');?></div>').delay(3000).fadeOut('slow');
			
			var quoteid = quoteid;
			if(quoteid=='')
				return;
			
			$.ajax({
                type:"POST",
                data:"<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>&quoteid="+quoteid,
                url :"<?php echo URL_QUOTES_DELETEQUOTE;?>",
                cache: false,
                success: function(d) {
					var res = $.parseJSON( d );
					if (res.status == 'success') {
                        $('#info_div').fadeIn('slow').html('<div class="alert alert-success top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
						 getPage(0,'savedquotes');
					} else {
                        $('#info_div').fadeIn('slow').html('<div class="alert alert-danger top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
                    }
                    //$("#get-ticket-now").attr("disabled", false);
                }
            });
			}
		}
		function deletesubmittedquote(quoteid)
		{
			if(confirm('<?php echo get_languageword('are_you_sure')?>?'))
			{
			$('#info_div').fadeIn('slow').html('<div class="alert alert-danger top-space"><?php echo get_languageword('processing_please_wait');?></div>').delay(3000).fadeOut('slow');
			
			var quoteid = quoteid;
			if(quoteid=='')
				return;
			
			$.ajax({
                type:"POST",
                data:"<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>&quoteid="+quoteid,
                url :"<?php echo URL_QUOTES_DELETESUBMITTEDQUOTE;?>",
                cache: false,
                success: function(d) {
					var res = $.parseJSON( d );
					if (res.status == 'success') {
                        $('#info_div').fadeIn('slow').html('<div class="alert alert-success top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
						 getPage(0,'submittedquotes');
					} else {
                        $('#info_div').fadeIn('slow').html('<div class="alert alert-danger top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
                    }
                    //$("#get-ticket-now").attr("disabled", false);
                }
            });
			}
		}
		function likedislike(quoteid,opinion)
		{
			$('#info_div_'+quoteid).fadeIn('slow').html('<div class="alert alert-danger top-space"><?php echo get_languageword('processing_please_wait');?></div>').delay(3000).fadeOut('slow');
			
			var quoteid = quoteid;
			var opinion = opinion;
			if(quoteid=='' || opinion == '')
				return;
			
			$.ajax({
                type:"POST",
                data:"<?php echo $this->security->get_csrf_token_name();?>=<?php echo $this->security->get_csrf_hash();?>&quoteid="+quoteid+"&opinion="+opinion,
                url :"<?php echo URL_QUOTES_LIKEQUOTE;?>",
                cache: false,
                success: function(d) {
					var res = $.parseJSON( d );
					
					if (res.status == 'success') {
                        $('#info_div_'+quoteid).fadeIn('slow').html('<div class="alert alert-success top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
						 //window.location.reload();
					} else if(res.status=='fail'){
                        $('#info_div_'+quoteid).fadeIn('slow').html('<div class="alert alert-danger top-space">'+res.message+'</div>').delay(3000).fadeOut('slow');
                    } else {
						window.location.href = res.redirect;
					}
                    //$("#get-ticket-now").attr("disabled", false);
                }
            });
			
		}
	</script>