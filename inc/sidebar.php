<?php 					
	$profile_active=$dashboard_active=$patient_active=$appointment_active=$user_active=$department_active=$doctor_active=$specialization_active='inactive';					
	${$page."_active"} = 'active';
	$user_type= (isset($_SESSION['type']))?$_SESSION['type']:'';
	$username=(isset($_SESSION['user_name']))?ucwords($_SESSION['user_name']):'';
	$user_id=(isset($_SESSION['user_id']))?$_SESSION['user_id']:'';
	$profile_image=(!empty($_SESSION['user_image']))?$_SESSION['user_image']:'images/user_profile.png';
	$website=(!empty($_SESSION['website']))?$_SESSION['website']:'';
	?>
<div class="container-fluid fixed-top bg-dark py-3" style="z-index:1049;">
	    <div class="row">
	        <div class="col-8">           
	                <h3 class="mt-2 mb-2 text-right text-white"><?php echo $website?> Patient Management System </h3>           
			</div>
			<div class="col-4 sidebar text-right">
			<ul class="nav navbar-nav navbar-right">
			<div class="shadow dropdown-list dropdown-menu  dropdown-menu-right " aria-labelledby="alertsDropdown"></div>                                              
                            <li class="dropdown " role="presentation">							
							<a  data-toggle="dropdown" aria-expanded="false" href="#">
							<span class="label label-pill label-danger count text-white"><?php echo ucwords($username) ?></span>	
							<span id="user_uploaded_image_small" class="mt-0">							
							<img src="<?php echo $profile_image; ?>" class="img-fluid rounded-circle" width="30" height="30"/></a></span>
									<div class="dropdown-menu shadow dropdown-menu-right animated--grow-in" role="menu">
									<a class="dropdown-item" role="presentation" href="profile.php"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Profile</a>
									<a class="dropdown-item" role="presentation" href="change_password.php"><i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Change password</a>
									<?php if($pms->is_admin()):?>
									<a class="dropdown-item" role="presentation" href="settings.php"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Settings</a>
									<?php endif	?>	
                                     <div class="dropdown-divider"></div><a class="dropdown-item logout" role="presentation" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Logout</a></div>
				  					</div>
					</li>					
	        </div> 
	        </div>
	    </div>
	</div>
	<div class="container-fluid">
	    <div class="row vh-100 flex-nowrap">
	        <div class="col-sm-2  sidebar bg-dark bg-purple px-0 position-fixed">
	            <ul class="nav flex-column flex-nowrap pt-2 vh-100" id="sidebar">
					
	            	<li class="nav-item">
	                    <a class="nav-link <?php echo $dashboard_active; ?>" href="dashboard.php"><span class="ml-2 d-none d-sm-inline"><i class="fas fa-tachometer-alt"></i> Dashboard</span></a>
					</li>
					<li class="nav-item">
	                    <a class="nav-link <?php echo $patient_active; ?>" href="patient.php"><span class="ml-2 d-none d-sm-inline"><i class="fas fa-wheelchair"></i>&nbsp;&nbsp;Patient</span></a>
					</li>
					<li class="nav-item">
	                    <a class="nav-link <?php echo $appointment_active; ?>" href="appointment.php"><span class="ml-2 d-none d-sm-inline"><i class="fas fa-calendar"></i>&nbsp;&nbsp;Appointments</span></a>
	                </li>
	            	<?php   if($pms->is_admin()): ?>
	            	<li class="nav-item">
	                    <a class="nav-link <?php echo $user_active; ?>" href="user.php"><span class="ml-2 d-none d-sm-inline"><i class="fas fa-users"></i> User</span></a>
	                </li>
	                <li class="nav-item">
	                    <a class="nav-link <?php echo $department_active; ?>" href="department.php"><span class="ml-2 d-none d-sm-inline"><i class="fas fa-hospital"></i> Department</span></a>
					</li>
					<li class="nav-item">
	                    <a class="nav-link <?php echo $specialization_active; ?>" href="specialization.php"><span class="ml-2 d-none d-sm-inline"><i class="fas fa-diagnoses"></i> Specialization</span></a>
					</li>
					<li class="nav-item">
	                    <a class="nav-link <?php echo $doctor_active; ?>" href="doctor.php"><span class="ml-2 d-none d-sm-inline"><i class="fas fa-user-md"></i> Doctor</span></a>
	                </li>
	            	<?php endif	?>
	            	
	              
	            </ul>
	        </div>