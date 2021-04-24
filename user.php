<?php

include_once('config.php');
include_once(INC.'init.php');

$patient = new pms();

if(!$patient->is_login())
	header("location:".$patient->base_url."");


if(!$patient->is_admin())
	header("location:".$patient->base_url."dashboard.php");

include_once(INC.'header.php');
include_once(INC.'sidebar.php');
?>	
	        <div class="col-sm-10 offset-sm-2 py-4">
	        	<span id="message"></span>
	            <div class="card">
	            	<div class="card-header">
	            		<div class="row">
	            			<div class="col">
	            				<h2>User Management</h2>
	            			</div>
	            			<div class="col text-right">
	            				<button type="button" name="add_user" id="add_user" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
	            			</div>
	            		</div>
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="user_table">
	            				<thead>
	            					<tr>
	            						<th>Image</th>
	            						<th>User Name</th>
										<th>User Contact No.</th>
										<th>User Email</th>
										<th>Created On</th>					
										<th>Action</th>
	            					</tr>
	            				</thead>
	            			</table>
	            		</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>

</body>
</html>

<div id="userModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="user_form" enctype="multipart/form-data">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add User</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Name <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="text" name="user_name" id="user_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Contact No. <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="text" name="user_contact_no" id="user_contact_no" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Email <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="email" name="user_email" id="user_email" class="form-control" required data-parsley-type="email" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>

		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Password <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
			            		<input type="password" name="user_password" id="user_password" class="form-control" required data-parsley-minlength="6" data-parsley-maxlength="16" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Type <span class="text-danger">*</span></label>
			            	<div class="col-md-8">
							<select name="user_type" id="user_type" class="form-control" >
								<option value="" selected>Select User type</option>
			            		<option value="user">User</option>
								<option value="admin">Admin</option>
								<option value="doctor">Doctor</option>
								<option value="Master">Master</option>
								</select>
			            		
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">User Profile</label>
			            	<div class="col-md-8">
			            		<input type="file" name="user_image" id="user_image" />
								<span id="user_uploaded_image"></span>
			            	</div>
			            </div>
		          	</div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Add" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<script>

$(document).ready(function(){

url="user_action.php";
$('#user_form').parsley();

	var dataTable = $('#user_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:url,
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[0, 5],
				"orderable":false,
			},
		],
	});

	$('#add_user').click(function(){		
		$('#user_form')[0].reset();
		$('#user_form').parsley().reset();
		$('#user_uploaded_image').html('');
    	$('#modal_title').text('Add User');    	
    	$('#submit_button,#action').val('Add');
    	$('#userModal').modal('show');
    	$('#user_password').attr({'required': true,'data-parsley-minlength':'6','data-parsley-trigger':'on change'});	    
	});	

	$('#user_form').on('submit', function(event){
		event.preventDefault();
		if($('#user_form').parsley().isValid())
		{			
			$.ajax({
				url:url,
				method:"POST",
				data:new FormData(this),
				contentType:false,
				processData:false,
				beforeSend:function(){
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data){
					$('#submit_button').attr('disabled', false);
					$('#userModal').modal('hide');
					result(data,dataTable);						
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){
		var user_id = $(this).data('id');
		$('#user_form').parsley().reset();
		$.ajax({
	      	url:url,
	      	method:"POST",
	      	data:{user_id:user_id, action:'fetch_single'},
	      	dataType:'JSON',
	      	success:function(data){
	        	$('#user_name').val(data.user_name);	        	
	        	$('#user_contact_no').val(data.user_contact_no);
	        	$('#user_email').val(data.user_email);
				$('#user_type').val(data.user_type);
	        	$('#user_uploaded_image').html('<img src="'+data.user_profile+'" class="img-fluid img-thumbnail" width="75" height="75" /><input type="hidden" name="hidden_user_image" value="'+data.user_profile+'" />');
	        	$('#user_password').removeAttr('required data-parsley-minlength data-parsley-trigger' );	        	
	        	$('#modal_title').text('Edit Data');
	        	$('#action,#submit_button').val('Edit');	        	
	        	$('#userModal').modal('show');
	        	$('#hidden_id').val(user_id);
	      	}
	    })
	});  	

		$(document).on('click', '.delete_button', function(){
		var id = $(this).data('id');
		var status = $(this).data('status');
		var next_status = 'Disable';
		if(status == 'Disable')		
			next_status = 'Enable';		
		data={id:id, action:'delete', status:status, next_status:next_status};
		disable(url,dataTable,data,next_status+' the user ');	
  	});

});
</script>
<?php include_once(INC.'footer.php');?>