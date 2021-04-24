<?php

//doctor_action.php

include_once('config.php');
include_once(INC.'init.php');

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('doctor.doctor_name', 'users.user_email', 'department.department_name', 
		'doctor.department_id', 'users.user_contact_no', 'doctor.status');

		$output = array();

		$main_query = "	SELECT * FROM doctor 
		LEFT JOIN department ON department.department_id = doctor.department_id 
		LEFT JOIN users ON users.user_id = doctor.doctor_user_id
		LEFT JOIN specialization ON doctor.specialization_id = specialization.specialization_id
		";		
			
		$search_query = "WHERE ";

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'doctor.doctor_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR users.user_email LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR department.department_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR users.user_contact_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR doctor.department_id LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR doctor.status LIKE "%'.$_POST["search"]["value"].'%" ';	
						
		}

		if(isset($_POST["order"]))		
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';		
		else		
			$order_query = 'ORDER BY doctor.doctor_id ASC ';		

		$limit_query = '';

		if($_POST["length"] != -1)		
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];		

		$pms->query = $main_query . $search_query . $order_query;

		$pms->execute();

		$filtered_rows = $pms->row_count();

		$pms->query .= $limit_query;

		$result = $pms->get_result();

		$pms->query = $main_query;

		$pms->execute();

		$total_rows = $pms->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = htmlspecialchars($row["doctor_name"]);
			$sub_array[] = htmlspecialchars($row["department_name"]);
			$sub_array[] = htmlspecialchars($row["specialization_name"]);			
			$sub_array[] = htmlspecialchars($row["user_email"]);
			$sub_array[] = $row["user_contact_no"];
			$sub_array[] = htmlspecialchars($row["user_address"]);
			$status = '';
			if($row["status"] == 'active')			
				$status = '<span class="badge badge-success">In Room</span>';			
			else			
				$status = '<span class="badge badge-danger">Left</span>';			
			$sub_array[] = $status;
			
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-primary btn-sm view_button" data-id="'.$row["doctor_id"].'"><i class="fas fa-eye"></i></button>
			&nbsp;
			<button type="button" name="edit_button" class="btn btn-warning btn-sm edit_button" data-id="'.$row["doctor_id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-sm delete_button" data-id="'.$row["doctor_id"].'"><i class="fas fa-times"></i></button>
			<button type="button" name="status_button" class="btn btn-success btn-sm status_button p-0" data-status="'.$row["status"].'" data-id="'.$row["doctor_id"].'"><i class="far fa-calendar-check  fa-2x"></i></button>
			</div>
			';
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
		$doctor_id=$_POST["doctor_department"];
		$doctor_user_id=$pms->get_data('user_id','users','user_email',$_POST["doctor_email"]);
		$pms->query = "	INSERT INTO doctor (doctor_name,doctor_user_id, department_id,  specialization_id ) 
		VALUES (:doctor_name,:doctor_user_id,  :doctor_department, :doctor_specialization)	";
		$data = array(
			':doctor_name'			=>	$pms->clean_input($_POST["doctor_name"]),
			':doctor_department'	=>	$doctor_id,
			':doctor_user_id'		=>	$doctor_user_id,
			':doctor_specialization' =>	$pms->clean_input($_POST["doctor_specialization"]),

		);				
		$result=$pms->execute($data);
		if($result)
		{$deprtment='department_capacity';

			$pms->query="UPDATE department SET department_capacity=department_capacity+1 WHERE department_id='$doctor_id'";
			$pms->execute();
			echo json_encode('<div class="alert alert-success">Doctor Data Added</div>');
		}
		else
			echo json_encode('<div class="alert alert-warning">Doctor Data could not be added due to some error</div>');
}

	if($_POST["action"] == 'fetch_single')
	{
		$doctor_id=$_POST["doctor_id"];
		$pms->query = "	SELECT * FROM doctor 
		LEFT JOIN department ON department.department_id = doctor.department_id 
		LEFT JOIN users ON users.user_id = doctor.doctor_user_id
		LEFT JOIN specialization ON doctor.specialization_id = specialization.specialization_id 
		WHERE doctor_id = '$doctor_id'";
		$pms->execute();
		$row = $pms->get_array();
		$data = array();		
			$data['doctor_name'] = $row['doctor_name'];
			$data['doctor_email'] = $row['user_email'];
			$data['doctor_mobile_no'] = $row['user_contact_no'];
			$data['doctor_address'] = $row['user_address'];			
			$data['doctor_department'] = $row['department_id'];			
			$data['doctor_specialization'] = $row['specialization_id'];
			$data['doctor_department_detail'] = $row['department_name'];			
			$data['doctor_specialization_detail'] = $row['specialization_name'];
		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{	
		$doctor_id=$_POST["doctor_id"];
		$pms->query = "	UPDATE doctor 	SET doctor_name = :doctor_name 
		 department_id = :doctor_department, specialization_id = :doctor_specialization ";
		$data = array(
			':doctor_name'			=>	$pms->clean_input($_POST["doctor_name"]),			 			
			':doctor_department'	=>	$_POST["doctor_department"],
			':doctor_specialization' =>	$pms->clean_input($_POST["doctor_specialization"]),
		);		
		$pms->query .= " WHERE doctor_id = '$doctor_id' ";
		$pms->execute($data);
		echo json_encode('<div class="alert alert-success">Doctor Details Updated</div>');
	}

	if($_POST["action"] == 'delete')
	{
		$doctor_id=$_POST["doctor_id"];
		$department_id=$pms->get_data('department_id','doctor','doctor_id',$doctor_id,'',1);
		$pms->query = "	DELETE FROM doctor WHERE doctor_id = '$doctor_id'	";
		$pms->execute();
		$pms->query="UPDATE department SET department_capacity=department_capacity-1 WHERE department_id='$department_id'";
		$pms->execute();
		echo json_encode('<div class="alert alert-success">Doctor Details Deleted</div>');
	}
	if($_POST["action"] == 'update_status')
	{
		$nextstatus='active';
		if ($_POST["status"] == 'active')
		$nextstatus='inactive';
		$data = array(
			':doctor_id'	=>	$pms->clean_input($_POST["doctor_id"]),			
			':status'			=>	$nextstatus
		);
		$pms->query = "	UPDATE doctor	SET status = :status	WHERE doctor_id = :doctor_id";
		$pms->execute($data);
		echo json_encode('<div class="alert alert-success">Doctor status Updated</div>');
	}
}
?>