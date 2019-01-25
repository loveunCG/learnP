<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<title>Installing Website</title>

<?php 
		$redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $redir .= "://".$_SERVER['HTTP_HOST'];
        $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
        $redir = str_replace('install/loading_website/','',$redir); 
 ?>
<meta http-equiv="refresh" content="30; url=<?php echo $redir; ?>">

<style type="text/css">

html, body {
  	background: #e27d7f;
  	background-size:cover;
  	width: 100%;
  	height: auto; background-repeat:no-repeat; background-attachment:local;  font-size: 90%;
	font-family: Helvetica,Arial,sans-serif;
	margin: 0 auto; 
}


.install {
	background: none repeat scroll 0 0 #fff;
    border-radius: 5px;
    margin: 65px auto 0;
    width: 32%;
}

.form-hed {
	font-size: 20px;
	color: #437ba3;
	text-shadow: 0 0px 0px rgba(0, 0, 0, 0.0) !important;
	text-align: left;
	border-bottom: 1px solid #ccc; 
	padding:25px 10px 10px; 
	margin-top:10px; 
	float:left; 
	width:93.5%;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 15px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 20px 0;
	padding: 70px 10px;
}
.logo {
	padding:0px 10px; float:left; height:48px;
}

p.img-header {
	margin-bottom: -20px;
	padding: 0 13px 7px;
	background: #534051;
}
.prog {
    width:100%;
    height:50px;
    border:1px solid #269abc;
}
.filler {
    width:0%;
    height:50px;
    background-color:#31b0d5;
}

</style>
</head>

<body>
<div class="install"> 
<p align="middle" class="img-header"><img src="../logo.png" align="center" ></p>

    <h1 class="form-hed">Installation in Progress</h1> 

	<code>
		<div id="prog" class="prog">
		    <div id="filler" class="filler"></div>
		</div>
		<p>Your website will be ready in next <span id="counter">30</span> seconds</p>
	</code>

</div>

<script>

	var stepSize = 290;
	setTimeout((function() {
	    var filler 		= document.getElementById("filler"),
	    	prog 		= document.getElementById("prog"),
	        percentage 	= 0;
	    return function progress() {
	        filler.style.width = percentage + "%";
	        percentage +=1;
	        if (percentage <= 100) {
	        	if(percentage >= 70) {
	        		prog.style.border 		= "1px solid #4cae4c";
	        		filler.style.background = "#5cb85c";
	        	}
	            setTimeout(progress, stepSize);
	        }
	    }

	}()), stepSize);


	setInterval(function(){
		counter_val = parseInt(document.getElementById('counter').innerHTML);
		if(counter_val > 0)
			counter_val = counter_val - 1;
		document.getElementById('counter').innerHTML = counter_val;
	}, 1000);
</script>

</body>
</html>
