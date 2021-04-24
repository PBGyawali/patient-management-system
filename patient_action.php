<?php

//patient_action.php

include_once('config.php');
include_once(INC.'init.php');

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('patient_history.patient_name', 'patient_history.patient_visit_doctor_name', 'patient_history.patient_department', 'patient_history.patient_enter_time', 'patient_history.patient_out_time', 'patient_history.patient_status', 'users.user_name');

		$output = array();

		$main_query = "	SELECT * FROM patient_history 
		LEFT JOIN users ON users.user_id = patient_history.patient_enter_by 
		LEFT JOIN department ON department.department_id=patient_history.patient_department 
		LEFT JOIN doctor ON doctor.doctor_id=patient_history.patient_visit_doctor_name 		
		";
		
				
		$search_query = "WHERE ";

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'patient_history.patient_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR patient_history.patient_visit_doctor_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR patient_history.patient_department LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR patient_history.patient_enter_time LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR patient_history.patient_out_time LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR patient_history.patient_status LIKE "%'.$_POST["search"]["value"].'%" ';	
			$search_query .= 'OR users.user_name LIKE "%'.$_POST["search"]["value"].'%" ';				
						
		}

		if(isset($_POST["order"]))		
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';		
		else		
			$order_query = 'ORDER BY patient_history.patient_id DESC ';		

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
			$sub_array[] = htmlspecialchars($row["patient_name"]);
			$sub_array[] = htmlspecialchars($row["doctor_name"]);
			$sub_array[] = $row["department_name"];
			$sub_array[] = $row["patient_enter_time"];
			$sub_array[] = $row["patient_out_time"];
			$status = '';
			if($row["patient_status"] == 'In')			
				$status = '<span class="badge badge-success">In Room</span>';
			else			
				$status = '<span class="badge badge-danger">Left</span>';			
			$sub_array[] = $status;
			if($pms->is_admin())			
				$sub_array[] = ucwords($row["user_name"]);
			
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-primary btn-sm view_button" data-id="'.$row["patient_history_id"].'"><i class="fas fa-eye"></i></button>
			&nbsp;
			<button type="button" name="edit_button" class="btn btn-warning btn-sm edit_button" data-id="'.$row["patient_history_id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-sm delete_button" data-id="'.$row["patient_history_id"].'"><i class="fas fa-times"></i></button>
			<button type="button" name="status_button" class="btn btn-success btn-sm status_button p-0" data-id="'.$row["patient_history_id"].'"><i class="far fa-calendar-check  fa-2x"></i></button>
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
			'patient_name'			,			
			'patient_doctor_id' ,
			'patient_department_id'	,
			'patient_reason_to_visit' ,
			'patient_enter_time'	,			
			'patient_status'	,
			'patient_enter_by'		
		);
		$values= array(
						$pms->clean_input($_POST["patient_name"]),			
						$_POST["patient_visit_doctor_name"],
						$_POST["patient_department"],
						$pms->clean_input($_POST["patient_reason_to_visit"]),
						$pms->get_datetime(),			
						'In',
						$_SESSION["user_id"]
		);
		$result=$pms->insert('patient_history',$data,$values);;
	if($result)
	echo '<div class="alert alert-success">Patient Data Added</div>';
	else
		echo '<div class="alert alert-warning">Patient Data could not be added due to some error</div>';
	}

	if($_POST["action"] == 'fetch_single')
	{
		
		$join=array(
					' LEFT JOIN '=>array('patient'=>'patient.patient_id=patient_history.patient_id',
										'department'=>'department.department_id=patient_history.patient_department',
										'doctor'=>'doctor.doctor_id=patient_history.patient_visit_doctor_name'
										)
					);		
		$result =$pms->getAllArray('patient_history','patient_history.patient_id',$_POST["patient_id"],'','','','',$join);
		$data = array();
		foreach($result as $row)
		{
			$data['patient_name'] = $row['patient_name'];
			$data['patient_email'] = $row['patient_email'];
			$data['patient_mobile_no'] = $row['patient_contact'];
			$data['patient_address'] = $row['patient_address'];
			$data['patient_visit_doctor_name'] = $pms->load_doctor($row['patient_department']);
			$data['patient_department'] = $row['patient_department'];
			$data['patient_visit_doctor_name_detail'] = $row['doctor_name'];
			$data['patient_department_detail'] = $row['department_name'];
			$data['patient_reason_to_visit'] = $row['patient_reason_to_visit'];
			$data['patient_outing_remark'] = $row['patient_outing_remark'];
		}
		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$data = array(
						'patient_name',			
						'patient_visit_doctor_name',
						'patient_department',
						'patient_reason_to_visit',
		);
		$values== array(
						$pms->clean_input($_POST["patient_name"]),			
						$_POST["patient_visit_doctor_name"],
						$_POST["patient_department"],
						$pms->clean_input($_POST["patient_reason_to_visit"]),
		);
		$pms->UpdateDataColumn('patient_history',$data,$values,'patient_id',$_POST['hidden_id']);	
		echo '<div class="alert alert-success">Patient Details Updated</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$pms->Delete('patient_history','patient_id',$_POST["id"]);
		echo json_encode('<div class="alert alert-success">Patient Details Deleted</div>');
	}

	if($_POST["action"] == 'dashboard_reset')
	{	
		$result = $pms->getAllArray('department');
		$html = '<div class="row">';
		foreach($result as $row)
		{	
			$count=$pms->CountTable('patient_history',array('patient_status','patient_department'),array('In',$row['department_name']));
			if($count)
			{				
				$html .= '
				<div class="col-lg-2 mb-3">
					<div class="card bg-info text-white shadow">
						<div class="card-body">
							'.$row["department_name"].'
							<div class="mt-1 text-white-50 small">'.(($row['department_capacity']<=$count['total'])?'Full':$row['department_capacity']-$count['total'].' Place left' ).'</div>
						</div>
					</div>
				</div>
				';
			}
			else
			{
				$html .= '
				<div class="col-lg-2 mb-3">
					<div class="card bg-light text-black shadow">
						<div class="card-body">
							'.$row["department_name"].'
							<div class="mt-1 text-black-50 small">'.$row['department_capacity'].' Places Free</div>
						</div>
					</div>
				</div>
				';
			}
		}
		echo json_encode($html);
	}

if($_POST["action"] == 'load_doctor')
	{	$doctor_id=$_POST["doctor_id"];
		$html=$pms->load_doctor($doctor_id);
		echo json_encode($html);
	}





}

?>