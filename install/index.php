<?php

//error_reporting(E_NONE);
 //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.
 
$db_config_path = '../application/config/database.php';
// Only load the classes in case the user submitted the form
if($_POST) {

	// Load the classes and create the new objects
	require_once('includes/core_class.php');
	require_once('includes/database_class.php');

	$core = new Core();
	$database = new Database();


	// Validate the post data
	if($core->validate_post($_POST) == true)
	{

		// First create the database, then create tables, then write config file
		if($database->create_database($_POST) == false) {
			$message = $core->show_message('error',"The database could not be created, please verify your settings.");
		} else if ($database->create_tables($_POST) == false) {
			$message = $core->show_message('error',"The database tables could not be created, please verify your settings.");
		} else if ($core->write_config($_POST) == false) {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod application/config/database.php file to 777");
		}

		// If no errors, redirect to registration page
		if(!isset($message)) {
		  	header( 'Location: loading_website') ;
		}

	}
	else {
		$message = $core->show_message('error','Not all fields have been filled in correctly. The host, username, password, and database name are required.');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title>Install | Tutors</title>

		<style type="text/css">
	 html, body{
      background: #e27d7f;
      background-size:cover;
      width: 100%;
      height: auto; background-repeat:no-repeat; background-attachment:local;  font-size: 90%;
		    font-family: Helvetica,Arial,sans-serif;
		   
		    margin: 0 auto; 
      }
		  input, select  {
		    display: block;
		    font-size: 12px;
		    margin: 0; height:25px; padding:0px 5px;
		  
		  }
		  label {
		    margin: 5px 0px !important; float:left; font-weight:bold;
		  }
		  input.input_text, select.input_text {
		    width: 96%; 
		  }
		  input#submit {
		    float:left; margin:15px 0px;
		    font-size: 15px; 
			
			width: 75px;
height: 32px;
background: #384145;
border-radius: 0 !important;
border-bottom: 4px solid #000;
border-left: 0 !important;
border-right: 0 !important;
border-top: 0 !important;
font-size: 14px;
line-height: 30px; color:#fff;
 
 
		  }
		  fieldset {
		    padding: 15px;
		    border-radius:10px; border:none;
		  }
		  legend {
		    font-size: 14px;
		    font-weight: bold; padding:0px 15px;
		  }
		  .error {
		    background: #ffd1d1;
		    border: 1px solid #ff5858;
        padding: 4px; margin:5px; clear:both;
		  }
		  .install{    background: none repeat scroll 0 0 #fff;
    border-radius: 5px;
    margin: 65px auto 0;
    width: 32%;}
		  .form-hed {
font-size: 20px;
color: #437ba3;
text-shadow: 0 0px 0px rgba(0, 0, 0, 0.0) !important;
text-align: left;
border-bottom: 1px solid #ccc; padding:5px 10px; margin-top:10px; float:left; width:93.5%;
  }
  .fluid-hedder {
    background: none repeat scroll 0 0 #fff;
    border-bottom: 3px solid #ffffff;
    clear: both;
    display: flex;
    margin: 0 auto;
    position: relative;
    width: 100%;
    z-index: 999999;
}
.logo {
 
padding:0px 10px; float:left; height:48px;
}

p.img-header {
	margin-bottom: -20px;
	padding: 0 13px;
	background: #534051;
}

		</style>
	</head>
	<body>

<div class="install"> 
<p align="middle" class="img-header"><img src="logo.png" align="center" ></p>
    <br />
    <h1 class="form-hed">Installation</h1> 
    <?php if(is_writable($db_config_path)){?>

		  <?php if(isset($message)) {echo '<p class="error">' . $message . '</p>';}?>

		  <form id="install_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <fieldset>
        
          <label for="hostname">Host Name</label><input type="text" id="hostname" value="localhost" class="input_text" name="hostname" required />
          <label for="username">User Name</label><input type="text" id="username" class="input_text" name="username" required />
          <label for="password">Password</label><input type="password" id="password" class="input_text" name="password"  />
          <label for="database">Database Name</label><input type="text" id="database" class="input_text" name="database" required />
          <label for="install_type">Installation Type</label>
          <select name="install_type" class="input_text" required>
          	<option value="0"> Without Data</option>
          	<option value="1"> With Data</option>
          </select>
          <input type="submit" value="Install" id="submit" />
        </fieldset>
		  </form>

	  <?php } else { ?>
      <p class="error">Please make the application/config/database.php file writable. <strong>Example</strong>:<br /><br /><code>chmod 777 application/config/database.php</code></p>
	  <?php } ?>
</div>
	</body>
</html>