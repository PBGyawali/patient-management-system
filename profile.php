<?php

//department.php

include_once('config.php');
include_once(INC.'init.php');

$patient = new pms();

if(!$patient->is_login())
{
	header("location:".$patient->base_url."");
}

$patient->query = " SELECT * FROM users WHERE user_id = '".$_SESSION["user_id"]."' ";
$result = $patient->get_result();
include_once(INC.'header.php');
include_once(INC.'sidebar.php');
?>
	<?php   foreach($result as $row) :  ?>
	
	        <div class="col-sm-10 offset-sm-2 py-4">
	        	<span id="message"></span>
	            <div class="card">
	            	<div class="card-header">
	            		<div class="row">
	            			<div class="col">
	            				<h2>Profile</h2>
	            			</div>
	            			<div class="col text-right">
	            			</div>
	            		</div>
	            	</div>
	            	<div class="card-body">
	            		<div class="col-md-3">&nbsp;</div>
	            		<div class="col-md-6">
	            			<form method="post" id="user_form" enctype="multipart/form-data">
							<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">User Profile</label>
						            	<div class="col-md-8">
						            	
											<span id="user_uploaded_image" class="mt-2">
												<img src="<?php echo $row["user_profile"];  ?>" class="img-fluid img-thumbnail rounded-circle" width="200" height="200"/>
												
											</span>
						            	</div>
						            </div>
					          	</div>
	            				<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">User Name <span class="text-danger">*</span></label>
						            	<div class="col-md-8">
						            		<input type="text" name="user_name" id="user_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" value="<?php echo $row['user_name']; ?>" />
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">User Contact No. <span class="text-danger"></span></label>
						            	<div class="col-md-8">
						            		<input type="text" name="user_contact_no" id="user_contact_no" class="form-control" data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="16" data-parsley-trigger="keyup" value="<?php echo $row['user_contact_no']; ?>" />
						            	</div>
						            </div>
					          	</div>
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">User Email <span class="text-danger">*</span></label>
						            	<div class="col-md-8">
						            		<input type="text" name="user_email" id="user_email" class="form-control" required data-parsley-type="email" data-parsley-maxlength="150" data-parsley-trigger="keyup" value="<?php echo $row['user_email']; ?>" />
						            	</div>
						            </div>
					          	</div>
					          	
					          	<div class="form-group">
					          		<div class="row">
						            	<label class="col-md-4 text-right">User Profile</label>
						            	<div class="col-md-8">
						            		<input type="file" name="user_image" class="file_upload" id="user_image" data-allowed_file='[<?php echo '"' . implode('","', ALLOWED_IMAGES) . '"'?>]' data-upload_time="later" accept="<?php echo "image/" . implode(", image/", ALLOWED_IMAGES);?>"/>
											<span class="text-muted">Only <?php  echo join(' and ', array_filter(array_merge(array(join(', ', array_slice(ALLOWED_IMAGES, 0, -1))), array_slice(ALLOWED_IMAGES, -1)), 'strlen'));?> extensions are supported</span>
												
												<input type="hidden" name="hidden_user_image" value="<?php echo $row["user_profile"]; ?>" />
										
						            	</div>
						            </div>
					          	</div>
					          	<br />
					          	<div class="form-group text-center">
					          		<input type="hidden" name="hidden_id" value="<?php echo $row["user_id"]; ?>" />
					          		<input type="hidden" name="action" value="profile" />
					          		<button type="submit" name="submit" id="submit_button" class="btn btn-success"><i class="far fa-save"></i> Save</button>
					          	</div>
					        <?php endforeach   ?>
	            			</form>
	            		</div>
	            		<div class="col-md-3">&nbsp;</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>

</body>
</html>

<script>

$(document).ready(function(){

	$('#user_form').parsley();
	var url="user_action.php";
	$('#user_form').on('submit', function(){
		event.preventDefault();
		if($('#user_form').parsley().isValid())
		{		
			var button_value=$('#submit_button').html();
			$.ajax({
				url:url,
				method:"POST",
				data:new FormData(this),
				contentType:false,
				processData:false,
				dataType:"JSON",
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').html('wait...');
				},
				complete:function()
				{
					$('#submit_button').attr('disabled', false);
                    $('#submit_button').html(button_value); 
				},uccess:function(data)
				{					
					if(data.error != '')					
						result(data.error);					
					else
					{
						$('#user_uploaded_image').html('<img src="'+data.user_profile+'" class="img-thumbnail img-fluid rounded-circle" width="200" height="200" /><input type="hidden" name="hidden_user_image" value="'+data.user_profile+'" />');
						result(data.success);
					}
				}
			})
		}
	});



});

</script>
<?php include_once(INC."footer.php");?>