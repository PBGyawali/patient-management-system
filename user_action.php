<?php

//user_action.php

include_once('config.php');
include_once(INC.'init.php');

$patient = new pms();

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('user_name', 'user_contact_no', 'user_email', 'user_created_on');

		$output = array();

		$main_query = "	SELECT * FROM users	WHERE user_type != 'admin' or user_type != 'admin'	";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND (user_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR user_contact_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR user_email LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR user_created_on LIKE "%'.$_POST["search"]["value"].'%") ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY user_id ASC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$patient->query = $main_query . $search_query . $order_query;

		$patient->execute();

		$filtered_rows = $patient->row_count();

		$patient->query .= $limit_query;

		$result = $patient->get_result();

		$patient->query = $main_query;

		$patient->execute();

		$total_rows = $patient->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = '<img src="'.$pms->image_check($row["user_profile"] ).'" class="img-fluid img-thumbnail" width="75" height="75" />';
			$sub_array[] = html_entity_decode($row["user_name"]);
			$sub_array[] = $row["user_contact_no"];
			$sub_array[] = $row["user_email"];
			$sub_array[] = $row["user_created_on"];
			$delete_button = '';
			if($row["user_status"] == 'Enable')
			{
				$delete_button = '<button type="button" name="delete_button" class="btn btn-primary btn-sm delete_button" data-id="'.$row["user_id"].'" data-status="'.$row["user_status"].'">'.$row["user_status"].'</button>';
			}
			else
			{
				$delete_button = '<button type="button" name="delete_button" class="btn btn-danger btn-sm delete_button" data-id="'.$row["user_id"].'" data-status="'.$row["user_status"].'">'.$row["user_status"].'</button>';
			}
			$sub_array[] = '
			<div align="center">
			<button type="button" name="edit_button" class="btn btn-warning btn-sm edit_button" data-id="'.$row["user_id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			'.$delete_button.'
			</div>';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
			
		echo json_encode($output);

	}

	if($_POST["action"] == 'Add')
	{
		$error = '';

		$success = '';

		$data = array(
			':user_email'	=>	$_POST["user_email"]
		);

		$patient->query = "	SELECT * FROM users WHERE user_email = :user_email	";

		$patient->execute($data);

		if($patient->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">User Email Already Exists</div>';
		}
		else
		{
			$user_type		=	'User';
			if(isset($_POST["user_type"]) && !empty($_POST["user_type"]))
			$user_type		=	$_POST["user_type"];
			$user_image = '';
			if($_FILES["user_image"]["name"] != '')			
				$user_image = upload_image();			
			else			
				$user_image = $patient->make_avatar(strtoupper($_POST["user_name"][0]));
			

			$data = array(
				':user_name'		=>	$patient->clean_input($_POST["user_name"]),
				':user_contact_no'	=>	$_POST["user_contact_no"],
				':user_email'		=>	$_POST["user_email"],
				':user_password'	=>	password_hash($_POST["user_password"], PASSWORD_DEFAULT),
				':user_profile'		=>	$user_image,
				':user_type'		=>	$user_type,
				':user_created_on'	=>	$patient->get_datetime()
			);

			$patient->query = "	INSERT INTO users 
			(user_name, user_contact_no, user_email, user_password, user_profile, user_type, user_created_on) 
			VALUES (:user_name, :user_contact_no, :user_email, :user_password, :user_profile, :user_type, :user_created_on)
			";

			$patient->execute($data);

			$success = '<div class="alert alert-success">User Added</div>';
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$patient->query = "	SELECT * FROM users WHERE user_id = '".$_POST["user_id"]."'	";
		$result = $patient->get_result();
		$data = array();
		foreach($result as $row)
		{
			$data['user_name'] = $row['user_name'];
			$data['user_contact_no'] = $row['user_contact_no'];
			$data['user_email'] = $row['user_email'];
			$data['user_profile'] = $row['user_profile'];
			$data['user_type'] = $row['user_type'];
		}
		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error =$success = '';
		$data = array(':user_email'=>$_POST["user_email"],':user_id'=>$_POST['hidden_id']);	
		$patient->query = "	SELECT * FROM users WHERE user_email = :user_email 	AND user_id != :user_id	";
		$patient->execute($data);
		if($patient->row_count() > 0)		
			$error = '<div class="alert alert-danger">User Email Already Exists</div>';		
		else
		{
			$user_image = $_POST["hidden_user_image"];
			$user_type=	'User';
			if(isset($_POST["user_type"]) && !empty($_POST["user_type"]))
			$user_type=	$_POST["user_type"];
			if($_FILES["user_image"]["name"] != '')		
				$user_image = upload_image();			
			$patient->query = "	UPDATE users 	SET user_name = :user_name, 
				user_contact_no = :user_contact_no, 	user_email = :user_email,  
				user_profile = :user_profile, user_type = :user_type  	";
			$data[':user_name'] = $patient->clean_input($_POST["user_name"]);
			$data[':user_contact_no'] = $_POST["user_contact_no"];
			$data[':user_email'] = $_POST["user_email"];				
			$data[':user_profile'] = $user_image;
			$data[':user_type'] = $user_type;
			if(isset($_POST["user_password"]) && !empty($_POST["user_password"]))
			{	$data[':user_password'] = password_hash($_POST["user_password"], PASSWORD_DEFAULT);	
				$patient->query .= " , user_password = :user_password ";				
			}
			$patient->query =" WHERE user_id = '".$_POST['hidden_id']."'";
			$patient->execute($data);
			$success = '<div class="alert alert-success">User Details Updated</div>';
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'delete')
	{
		$data = array(':user_status'=>	$_POST['next_status']);
		$patient->query = "	UPDATE users SET user_status = :user_status WHERE user_id = '".$_POST["id"]."'	";
		$patient->execute($data);
		echo json_encode('<div class="alert alert-success">User Status change to '.$_POST['next_status'].'</div>');
	}

	if($_POST["action"] == 'profile')
	{
		sleep(2);
		$error=$success=$user_name=$user_contact_no=$user_email=$user_profile= '';
		$data = array(
			':user_email'	=>	$_POST["user_email"],
			':user_id'		=>	$_POST['hidden_id']
		);

		$patient->query = "	SELECT * FROM users WHERE user_email = :user_email 	AND user_id != :user_id	";
		$patient->execute($data);

		if($patient->row_count() > 0)		
			$error = '<div class="alert alert-danger">User Email Already Exists</div>';		
		else
		{
			$user_image = $_POST["hidden_user_image"];
			if($_FILES["user_image"]["name"] != '')			
				$user_image = upload_image();			

			$user_name = $patient->clean_input($_POST["user_name"]);
			$user_contact_no = $_POST["user_contact_no"];
			$user_email = $_POST["user_email"];
			$user_profile = $user_image;

			$data = array(
				':user_name'	=>	$user_name,
				':user_contact_no'=> $user_contact_no,
				':user_email'	=>	$user_email,
				':user_profile'	=>	$user_profile
			);

			$patient->query = "	UPDATE users SET user_name = :user_name, user_contact_no = :user_contact_no, user_email = :user_email,  
			user_profile = :user_profile WHERE user_id = '".$_POST['hidden_id']."'	";

			$patient->execute($data);

			$success = '<div class="alert alert-success">User Details Updated</div>';
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success,			
			'user_profile'	=>	$user_profile
		);

		echo json_encode($output);
	}

	if($_POST["action"] == 'change_password')
	{
		$error = '';
		$success = '';
		$patient->query = "	SELECT user_password FROM users WHERE user_id = '".$_SESSION["user_id"]."'	";

		$result = $patient->get_result();

		foreach($result as $row)
		{
			if(password_verify($_POST["current_password"], $row["user_password"]))
			{
				$data = array(':user_password'=>password_hash($_POST["new_password"], PASSWORD_DEFAULT)	);
				$patient->query = "	UPDATE users SET user_password = :user_password WHERE user_id = '".$_SESSION["user_id"]."'	";
				$patient->execute($data);
				$success = '<div class="alert alert-success">Password Change Successfully</div>';
			}
			else			
				$error = '<div class="alert alert-danger">You have enter wrong current password</div>';
			
		}
		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);
		echo json_encode($output);
	}
}

function upload_image()
{
	if(isset($_FILES["user_image"]))
	{
		$extension = explode('.', $_FILES['user_image']['name']);
		$new_name = rand() . '.' . $extension[1];
		$destination = 'images/' . $new_name;
		move_uploaded_file($_FILES['user_image']['tmp_name'], $destination);
		return $destination;
	}
}



?>