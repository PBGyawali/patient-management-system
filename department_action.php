<?php

//department_action.php

include_once('config.php');
include_once(INC.'init.php');

$pms = new pms();

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('department_name', 'department_capacity','department_doctor');

		$output = array();	

		$main_query = "	SELECT department.department_id as id, department_name,department_capacity, group_concat(doctor_name) as doctor
		FROM department LEFT JOIN doctor ON department.department_id = doctor.department_id 	 ";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= ' WHERE department_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= ' OR department_capacity LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = ' ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = ' ORDER BY department.department_name DESC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$group_query=" GROUP BY department.department_id ";
		$pms->query = $main_query . $search_query.$group_query ;
		//. $order_query;
		$pms->execute();

		$filtered_rows = $pms->row_count();

		$pms->query .= $limit_query;

		$result = $pms->statement_result();

		$pms->query = $main_query.$group_query;

		$pms->execute();

		$total_rows = $pms->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = html_entity_decode($row["department_name"]);
			$sub_array[] = html_entity_decode($row["department_capacity"]);
			$sub_array[] = html_entity_decode($row["doctor"]);
			$sub_array[] = '
			<div align="center">
			<button type="button" name="edit_button" class="btn btn-warning btn-sm edit_button" data-id="'.$row["id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-sm delete_button" data-id="'.$row["id"].'"><i class="fas fa-times"></i></button>
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
		$error = '';
		$success = '';
		$data = array('department_name');
		$value = array($pms->clean_input($_POST["department_name"]));
		$count=$pms->CountTable('department',$data,$value);		
		if($count > 0)		
			$error = '<div class="alert alert-danger">Department Already Exists</div>';		
		else
		{			
			$pms->insert('department',$data,$value);			
			$success = '<div class="alert alert-success">Department Added</div>';		
		}
		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);
		echo json_encode($output);
	}	

	if($_POST["action"] == 'Edit')
	{
		$error = '';
		$success = '';
		$department_id=$_POST['hidden_id'];
		$data = array('department_name','department_id!');
		$value = array($pms->clean_input($_POST["department_name"]),$_POST['hidden_id']);
		$count=$pms->CountTable('department',$data,$value);		
		if($count > 0)			
			$error = '<div class="alert alert-danger">Department Already Exists</div>';		
		else
		{				
			$department_capacity=$pms->CountTable('doctor','department_id',$department_id);
			$data = array('department_name','department_capacity');
			$value = array(	$pms->clean_input($_POST["department_name"]),$department_capacity);
			$pms->UpdateDataColumn('department',$data,$value,'department_id',$department_id);
			$success = '<div class="alert alert-success">Department Updated</div>';
		}
		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);
		echo json_encode($output);
	}

	if($_POST["action"] == 'delete')
	{
		$pms->Delete('department','department_id',$_POST["id"]);
		echo json_encode('<div class="alert alert-success">Department Deleted</div>');
	}
}

?>