<?php 
$website=(isset($_SESSION['website'])?$_SESSION['website']:'');
$page=((isset($page)&&$page=='index')?'WELCOME TO':$page)
?>
<!DOCTYPE html>
		<html>
		<head>			
		<html class='no-js' lang='en'>		
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible' />	
		<script type="text/javascript" src="<?php echo JS_URL?>jquery.min.js"></script>	  
		<script type="text/javascript" src="<?php echo JS_URL?>popper.min.js"></script>  	  	
		<script type="text/javascript" src="<?php echo JS_URL?>jquery-confirm.min.js"></script>
					
		<link rel="stylesheet" href="<?php echo CSS_URL?>jquery-confirm.min.css">
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<script type="text/javascript" src="<?php echo JS_URL?>datatables.min.js"></script>		
		<link rel="stylesheet" href="<?php echo CSS_URL?>datatables.min.css" >
		<link rel="stylesheet" href="<?php echo CSS_URL.'parsley.css'?>" >		
		<script type="text/javascript" src="<?php echo JS_URL.'parsley.min.js'?>"></script>	
		<link rel="stylesheet" type="text/css" href="<?php echo CSS_URL?>style.css"/>
		<link href="<?php echo CSS_URL?>bootstrap-timepicker.min.css" type="text/css" rel="stylesheet" media="screen">
		
		<link rel="stylesheet" href="<?php echo CSS_URL?>bootstrap.min.css">
		<script type="text/javascript" src="<?php echo JS_URL?>bootstrap.min.js"></script>
		
<script type="text/javascript" src="<?php echo JS_URL?>bootstrap-datepicker.js"></script>
		<script src="<?php echo JS_URL?>bootstrap-timepicker.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo CSS_URL?>bootstrap-datepicker.css"/>
		<link rel="stylesheet" href="<?php echo CSS_URL?>jquery.timeselector.css">
		<script type="text/javascript" src="<?php echo JS_URL?>jquery.timeselector.js"></script>
		<title><?php echo ucwords(isset($page)?$page.' ':'').(isset($website)?$website.' ':'').SITE_NAME?></title>
	