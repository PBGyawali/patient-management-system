<?php

//specialization_action.php

include_once('config.php');
include_once(INC.'init.php');
$error = '';
$success = '';
if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{		
			$specialization_name=	$pms->clean_input($_POST["specialization_name"]);		
			$data =$pms-> CountTable('specialization','specialization_name',$specialization_name);			
			if($data)		
				$error = '<div class="alert alert-danger">Specialization Already Exists</div>';		
			else
			{			
				$pms->query = "	INSERT INTO specialization (specialization_name) VALUES (?)	";
				$pms->execute($specialization_name);
				if($pms->row()>0)	
				$success = '<div class="alert alert-success">Specialization Name Added</div>';
			}
			$output = array('error'	=>$error,'success'	=>	$success);
			echo json_encode($output);	
	}
	
	if($_POST['btn_action'] == 'fetch_single')
	{
		$pms->query = "SELECT * FROM specialization WHERE specialization_id = :specialization_id";		
		$pms->execute(
			array(
				':specialization_id'	=>	$_POST["specialization_id"]
			)
		);
		$result =$pms->statement_result();
		foreach($result as $row)
		{
			$output['specialization_name'] = $row['specialization_name'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
			$specialization_id = $pms->clean_input($_POST['specialization_id']);
			$specialization_name=$pms->clean_input($_POST["specialization_name"]);		
			$data = array(
				':specialization_name'		=>	$specialization_name,
				':specialization_id'			=>	$specialization_id
			);
			$count =$pms-> CountTable('specialization',array('specialization_name','specialization_id !'),array($specialization_name,$specialization_id));
			if($count)		
				$error = '<div class="alert alert-danger">Specialization Already Exists</div>';		
			else
			{			
				$pms->query = "UPDATE specialization SET specialization_name = :specialization_name WHERE specialization_id = :specialization_id";
				$pms->execute($data);
				if($pms->row()>0)	
				$success = '<div class="alert alert-success">Specialization Name Updated</div>';
			}
			$output = array('error'	=>	$error,	'success'=>	$success);
			echo json_encode($output);
	}
	if($_POST['btn_action'] == 'delete')
	{
		$status = 'active';
		if($_POST['status'] == 'active')		
			$status = 'inactive';	
		
		$pms->query = "UPDATE specialization 	SET specialization_status = :specialization_status 	WHERE specialization_id = :specialization_id ";		
		$pms->execute(
			array(
				':specialization_status'	=>	$status,
				':specialization_id'		=>	$_POST["specialization_id"]
			)
		);		
		if($pms->row())		
		echo json_encode('<div class="alert alert-info">Specialization status changed to ' . $status.'</div>');
	}		
}



if(isset($_POST['action'])&& $_POST['action'] == 'fetch')
{
		$output = array();
		$query = "SELECT * FROM specialization ";
		if(isset($_POST["search"]["value"]))
		{
			$query .= 'WHERE specialization_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$query .= 'OR specialization_status LIKE "%'.$_POST["search"]["value"].'%" ';
		}
		if(isset($_POST['order']))		
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';		
		else		
			$query .= 'ORDER BY specialization_id DESC ';		

		if($_POST['length'] != -1)		
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];		

		$pms->query=$query;
		$pms->execute();
		$result = $pms->statement_result();
		$data = array();
		$filtered_rows = $pms->row();
		foreach($result as $row)
		{
			$status = '';
			if($row['specialization_status'] == 'active')			
				$status = '<span class="badge badge-success">Active</span>';			
			else			
				$status = '<span class="badge badge-danger">Inactive</span>';			
			$sub_array = array();
			$sub_array[] = $row['specialization_id'];
			$sub_array[] = $row['specialization_name'];
			$sub_array[] = $status;
			$sub_array[] = '<button type="button" name="update" id="'.$row["specialization_id"].'" class="btn btn-warning btn-sm update">Update</button>';
			$sub_array[] = '<button type="button" name="delete" id="'.$row["specialization_id"].'" class="btn btn-primary btn-sm delete" data-status="'.$row["specialization_status"].'">Change Status</button>';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"			=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $filtered_rows,
			"recordsFiltered" 	=> $pms->CountTable('specialization'),
			"data"				=>	$data
		);

		echo json_encode($output);
}
?>