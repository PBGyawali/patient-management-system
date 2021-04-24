
<?php
include_once('config.php');
include_once(INC.'init.php');
$pms = new pms();


if($_POST["user_email"])
{
	sleep(2);
	$error = '';
	$data = array(
		':admin_email'	=>	$_POST["user_email"]
	);

	$pms->query = "	SELECT * FROM users	WHERE admin_email = :admin_email";

	$pms->execute($data);

	$total_row = $pms->row_count();

	if($total_row == 0)
	{
		$error = '<div class="alert alert-danger">Wrong Email Address</div>';
	}
	else
	{
		$result = $pms->statement_result();

		foreach($result as $row)
		{
			if($row["admin_status"] == 'Enable')
			{
				if(password_verify($_POST["user_password"], $row["admin_password"]))
				{
					$_SESSION['admin_id'] = $row['admin_id'];
					$_SESSION['admin_type'] = $row['admin_type'];
					$_SESSION['admin_name'] = $row['admin_name'];
				}
				else
				{
					$error = '<div class="alert alert-danger">Wrong Password</div>';
				}
			}
			else
			{
				$error = '<div class="alert alert-danger">Sorry, Your account has been disable, contact Admin</div>';
			}
		}
	}

	$output = array(
		'error'		=>	$error
	);

	echo json_encode($output);
}

?>