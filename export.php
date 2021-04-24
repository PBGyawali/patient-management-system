<?php

//export.php

include_once('pms.php');

$patient = new pms();

$file_name = md5(rand()) . '.csv';
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$file_name");
header("Content-Type: application/csv;");
$file = fopen("php://output", "w");
$header = array("patient ID", "patient Name", "patient Email", "patient Mobile No.", "patient Address", "visit doctor Department", "visit doctor Name", "Reason for Visit", "Enter Time", "Outing Remark", "Out Time", "patient Status", "Enter By");
fputcsv($file, $header);

if(isset($_GET["from_date"]) && isset($_GET["to_date"]))
{
	$patient->query = "
	SELECT * FROM patient_history 
	INNER JOIN users 
	ON users.admin_id = patient_history.patient_enter_by 
	WHERE DATE(patient_history.patient_enter_time) BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."' 
	";
	if(!$patient->is_master_user())
	{
		$patient->query .= ' AND patient_history.patient_enter_by = "'.$_SESSION["admin_id"].'" ';
	}

}
else
{
	$patient->query = "
	SELECT * FROM patient_history 
	INNER JOIN users 
	ON users.admin_id = patient_history.patient_enter_by 
	";
	if(!$patient->is_master_user())
	{
		$patient->query .= ' WHERE patient_history.patient_enter_by = "'.$_SESSION["admin_id"].'" ';
	}
}

$patient->query .= 'ORDER BY patient_history.patient_id DESC';

$result = $patient->get_result();

foreach($result as $row)
{
	$data = array();
	$data[] = $row["patient_id"];
	$data[] = $row["patient_name"];
	$data[] = $row["patient_email"];
	$data[] = $row["patient_mobile_no"];
	$data[] = $row["patient_address"];
	$data[] = $row["patient_department"];
	$data[] = $row["patient_visit_doctor_name"];
	$data[] = $row["patient_reason_to_visit"];
	$data[] = $row["patient_enter_time"];
	$data[] = $row["patient_outing_remark"];
	$data[] = $row["patient_out_time"];
	$data[] = $row["patient_status"];
	$data[] = $row["admin_name"];
	fputcsv($file, $data);
}
fclose($file);
exit;

?>