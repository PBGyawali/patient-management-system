<?php

//setting_action.php

include_once('config.php');
include_once(INC.'init.php');

if(isset($_POST["facility_name"]))
{
	$setup=false;
	if(isset($_SESSION['setup']) &&!empty($_SESSION['setup']))
	{
		$setup=true;
	}
	$selectvalue=explode('***',$_POST["facility_currency"]);
	$facility_currency=	$selectvalue[0];
	$currency_symbol=	$selectvalue[1];
	$facility_name=$pms->clean_input($_POST["facility_name"]);
	$sql="UPDATE ";
	if($setup)
		$sql="INSERT INTO ";
	$data = array(
		':facility_name'		 =>	$facility_name,
		':facility_address'	  	 =>	$pms->clean_input(	$_POST["facility_address"]),
		':facility_contact_no'	 =>	$pms->clean_input($_POST["facility_contact_no"]),
		':facility_email'	 	 =>	$pms->clean_input($_POST["facility_email"]),
		':facility_target'	 	 =>	$pms->clean_input($_POST["facility_target"]),		
		':facility_currency'	 =>	$pms->clean_input($facility_currency),
		':currency_symbol'	 	 =>	$pms->clean_input($currency_symbol),
		':facility_timezone'	 =>	$pms->clean_input($_POST["facility_timezone"]),
		
	);
	$pms->query = $sql." facility_table SET facility_name = :facility_name, facility_address = :facility_address,
	 facility_contact_no = :facility_contact_no,facility_email = :facility_email,facility_target = :facility_target,
	 facility_currency = :facility_currency, currency_symbol= :currency_symbol, facility_timezone = :facility_timezone ";
	$pms->execute($data);
	if($pms->row()>0)
			{
				$_SESSION['setup']='';
				$_SESSION['website']=$facility_name;
				$message='<div class="alert alert-success">Details Updated Successfully</div>';
			}
	

	if(isset($_POST["admin_email"]))
	{	$password=$pms->clean_input($_POST["admin_password"]);
		$master_user_data = array(
									':user_name'				=>$pms->clean_input	($_POST["admin_email"]),
									':user_email'				=>$pms->clean_input	($_POST["admin_email"]),
									':user_password'			=>	password_hash($password, PASSWORD_DEFAULT),
									':user_type'				=>	'Master',
								);
		$pms->query = "	INSERT INTO users 	(user_name, user_email, user_password, user_type) 
		VALUES (:user_name,  :user_email, :user_password, :user_type)	";

		$pms->execute($master_user_data);
		if($pms->row()>0)
			{
				$_SESSION['setup']='';
				
				$message = '<div class="alert alert-success">Your Account is Created, Now you can Login</div>';
			}
	}

	
	$data = array();
	$data['success'] = $message;

	echo json_encode($data);
}



?>