<?php

//patient_action.php

include_once('config.php');
include_once(INC.'init.php');

$patient = new pms();

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('appointments.patient_name', 'appointments.appointment_doctor_id', 'appointments.appointment_department_id', 'appointments.appointment_start_time', 'appointments.appointment_end_time', 'appointments.appointment_status', 'users.user_name');

		$output = array();

		$main_query = "	SELECT * FROM appointments 
		LEFT JOIN users ON users.user_id = appointments.appointment_enter_by 
		LEFT JOIN department ON department.department_id=appointments.appointment_department_id 
		LEFT JOIN doctor ON doctor.doctor_id=appointments.appointment_doctor_id 
		
		";
		
			if($_POST["from_date"] != '')
			{
				$search_query = "WHERE DATE(appointments.appointment_start_time) BETWEEN '".$_POST["from_date"]."' AND  '".$_POST["to_date"]."' AND ( ";
			}
			else
			{
				$search_query = "WHERE ";	
			}
		
		

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'appointments.patient_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR appointments.appointment_doctor_id LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR appointments.appointment_department_id LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR appointments.appointment_start_time LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR appointments.appointment_end_time LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR appointments.appointment_status LIKE "%'.$_POST["search"]["value"].'%" ';	
			$search_query .= 'OR users.user_name LIKE "%'.$_POST["search"]["value"].'%" ';
			if($_POST["from_date"] != '')
			{
				$search_query .= ') ';
			}
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY appointments.appointment_id DESC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

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
			$sub_array[] = htmlspecialchars($row["patient_name"]);
			$sub_array[] = htmlspecialchars($row["doctor_name"]);
			$sub_array[] = $row["department_name"];
			$sub_array[] = $row["appointment_start_time"];
			$sub_array[] = $row["appointment_end_time"];
			$status = '';
			if($row["appointment_status"] == 'confirmed')			
				$status = '<span class="badge badge-success">Confirmed</span>';			
			else			
				$status = '<span class="badge badge-danger">Waiting</span>';
			
			$sub_array[] = $status;
			if($pms->is_admin())
			{
				$sub_array[] = ucwords($row["user_name"]);
			}
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-primary btn-sm view_button" data-id="'.$row["appointment_id"].'"><i class="fas fa-eye"></i></button>
			&nbsp;
			<button type="button" name="edit_button" class="btn btn-warning btn-sm edit_button" data-id="'.$row["appointment_id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-sm delete_button" data-id="'.$row["appointment_id"].'"><i class="fas fa-times"></i></button>
			<button type="button" name="status_button" class="btn btn-success btn-sm status_button p-0" data-id="'.$row["appointment_id"].'"><i class="far fa-calendar-check  fa-2x"></i></button>
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
		$data = array(
			':patient_name'			=>	$patient->clean_input($_POST["patient_name"]),					
			':appointment_doctor_id' =>	$_POST["appointment_doctor_id"],
			':appointment_department_id'=>	$_POST["appointment_department_id"],			
			':appointment_start_time'	=>	$_POST["appointment_start_time"],			
			':appointment_status'		=>	'Waiting',
			':patient_enter_by'		=>	$_SESSION["user_id"]
		);

		$patient->query = "	INSERT INTO appointments 
		(patient_name,appointment_doctor_id, appointment_department_id, appointment_start_time, appointment_status, patient_enter_by) 
		VALUES (:patient_name,  :appointment_doctor_id, :appointment_department_id,:appointment_start_time, :appointment_status, :patient_enter_by)
			";

		$result=$patient->execute($data);
if($result)
	echo '<div class="alert alert-success">Appointment Added</div>';
else
	echo '<div class="alert alert-warning">Appointment Data could not be added due to some error</div>';
	}

	if($_POST["action"] == 'fetch_single')
	{
		$patient->query = "	SELECT * FROM appointments
		LEFT JOIN patient ON patient.patient_id=appointments.appointment_id
		LEFT JOIN department ON department.department_id=appointments.appointment_department_id 
		LEFT JOIN doctor ON doctor.doctor_id=appointments.appointment_doctor_id
		 WHERE appointments.appointment_id = '".$_POST["appointment_id"]."'	";
		$result = $patient->get_result();
		$data = array();
		foreach($result as $row)
		{
			$data['patient_name'] = $row['patient_name'];
			$data['patient_email'] = $row['patient_email'];
			$data['patient_mobile_no'] = $row['patient_contact'];
			$data['patient_address'] = $row['patient_address'];
			$data['appointment_doctor_id'] = $pms->load_doctor($row['appointment_department_id']);
			$data['appointment_department_id'] = $row['appointment_department_id'];
			$data['appointment_doctor_id_detail'] = $row['doctor_name'];
			$data['appointment_department_id_detail'] = $row['department_name'];			
			$data['appointment_date'] =date('d-m-Y', strtotime($row["appointment_start_time"])) ;
			$data['appointment_time'] = date('H:i', strtotime($row["appointment_start_time"]));
		}
		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$data = array(
			':patient_name'			=>	$patient->clean_input($_POST["patient_name"]),			
			':appointment_doctor_id' =>	$_POST["appointment_doctor_id"],
			':appointment_department_id'	=>	$_POST["appointment_department_id"],			
		);

		$patient->query = "	UPDATE appointments 
		SET patient_name = :patient_name, 		
		appointment_doctor_id = :appointment_doctor_id, 
		appointment_department_id = :appointment_department_id, 	
		WHERE appointment_id = '".$_POST['hidden_id']."'		";
		$patient->execute($data);
		echo '<div class="alert alert-success">Patient Details Updated</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$patient->query = "	DELETE FROM appointments WHERE appointment_id = '".$_POST["id"]."'	";
		$patient->execute();
		echo json_encode('<div class="alert alert-success">Patient Details Deleted</div>');
	}

	
	if($_POST["action"] == 'load_doctor')
	{	$doctor_id=$_POST["doctor_id"];
		$html=$pms->load_doctor($doctor_id);
		echo json_encode($html);
	}






}

?>