<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<?php $CI =& get_instance(); if($CI->uri->segment(1) == "") {?>
<title>Welcome - Tutors</title>
<meta http-equiv="refresh" content="30">
<?php } else { ?>
<title>Database Error</title>
<?php } ?>

<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 15px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 25px 10px 25px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>
</head>
<body>
	<?php
		if($CI->uri->segment(1) == "") {
			echo "<code>Loading Website...<span id='counter'>30</span></code>";

		} else {

			echo '<div id="container"><h1>'.$heading.'</h1>';
			echo $message."</div>";
		}
	?>
	<script>
		setInterval(function(){
			document.getElementById('counter').innerHTML = parseInt(document.getElementById('counter').innerHTML) - 1;
			
		}, 1000);
	</script>
</body>
</html>