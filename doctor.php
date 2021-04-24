<?php

//doctor.php

include_once('config.php');
include_once(INC.'init.php');

$doctor = new pms();

if(!$doctor->is_login())
{
	header("location:".$doctor->base_url."");
}

include_once(INC.'header.php');
include_once(INC.'sidebar.php');



?>

<script>
$(document).ready(function(){        
    $('.input-daterange').datepicker({
        todayBtn: "linked",
        format: "yyyy-mm-dd",
        autoclose: true
    });
        
});
</script>
	
	
	        <div class="col-sm-10 offset-sm-2 py-4">
	        	<span id="message"></span>
	            <div class="card">
	            	<div class="card-header">
	            		<div class="row">
	            			<div class="col-sm-4">
	            				<h2>Doctor Area</h2>
							</div>	 
							<div class="col-sm-6">
	            				
	            			</div>           			
	            			<div class="col-md-2 text-right" >	            				
	            				<button type="button" name="add_doctor" id="add_doctor" class="btn btn-success btn-sm" ><i class="fas fa-user-plus"></i></button>
	            			</div>
	            		</div>
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="doctor_table">
	            				<thead>
	            					<tr>
	            						<th>Doctor Name</th>
										<th>Department</th>
										<th>Specialization</th>										
										<th>Email</th>
										<th>Contact no</th>
										<th>Address</th>
										<th>Status</th>																	
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

<div id="doctorModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="doctor_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add doctor</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Doctor Name</label>
			            	<div class="col-md-8">
			            		<input type="text" name="doctor_name" id="doctor_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Identifying Email</label>
			            	<div class="col-md-8">
								<select name="doctor_email" id="doctor_email" class="form-control" required data-parsley-trigger="on change">
			            		<option value="">Select Doctor Email</option>
									<?php echo $doctor->load_email(); ?>
									</select>
			            	</div>
			            </div>
		          	</div>		          
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Department</label>
			            	<div class="col-md-8">
			            		<select name="doctor_department" id="doctor_department" class="form-control" required data-parsley-trigger="on change">
			            			<option value="">Select Departent</option>
			            			<?php echo $doctor->load_department(); ?>
			            		</select>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Specialization</label>
			            	<div class="col-md-8">
			            		<select name="doctor_specialization" id="doctor_specialization" class="form-control" required data-parsley-trigger="on change">
									<option value="">Select specialization</option>
									<?php echo $doctor->load_specialization(); ?>
			            		</select>
			            	</div>
			            </div>
		          	</div>		          	
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="doctor_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Add" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<div id="doctor_detailModal" class="modal fade">
  	<div class="modal-dialog modal-lg">
    	<form method="post" id="doctor_details_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">View doctor Details</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">

		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Doctor Name</b></label>
			            	<div class="col-md-8">
			            		<span id="doctor_name_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Doctor Email</b></label>
			            	<div class="col-md-8">
			            		<span id="doctor_email_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Doctor Mobile No.</b></label>
			            	<div class="col-md-8">
			            		<span id="doctor_mobile_no_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Doctor Address</b></label>
			            	<div class="col-md-8">
			            		<span id="doctor_address_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Department</b></label>
			            	<div class="col-md-8">
			            		<span id="doctor_department_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Doctor specialization</b></label>
			            	<div class="col-md-8">
			            		<span id="doctor_specialization_detail"></span>
			            	</div>
			            </div>
		          	</div>		          
		          
        		</div>
        		<div class="modal-footer">          			
          			<button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<script>

$(document).ready(function(){
	var url="doctor_action.php";
	$('#doctor_form').parsley();
		var dataTable = $('#doctor_table').DataTable({
			"processing" : true,
			"serverSide" : true,
			"order" : [],
			"ajax" : {
				url:url,
				type:"POST",
				data:{action:'fetch'}
			},
			"columnDefs":[
				{	<?php 
					if($doctor->is_admin())
						 echo '"targets":[7]';
					else
						 echo '"targets":[6]'
					?>,
					"orderable":false,					
				},
			],
		});
	$('#add_doctor').click(function(){		
		$('#doctor_form')[0].reset();
		$('#doctor_form').parsley().reset();
    	$('#modal_title').text('Add doctor');
    	$('#action').val('Add');
    	$('#submit_button').val('Add');
    	$('#doctorModal').modal('show');
	});	

	$('#doctor_form').on('submit', function(event){
		event.preventDefault();
		if($('#doctor_form').parsley().isValid())
		{		
			$.ajax({
				url:url,
				method:"POST",
				data:$(this).serialize(),
				dataType:'JSON',
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				complete:function(){
					$('#submit_button').attr('disabled', false);
				},
				success:function(data)
				{					
					$('#doctorModal').modal('hide');
					result(data,dataTable);					
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){
		var doctor_id = $(this).data('id');		
		$('#doctor_form').parsley().reset();
		view_data(doctor_id,url); 		
	});
	
	  $(document).on('click', '.view_button', function(){
  		var doctor_id = $(this).data('id');
		  view_data(doctor_id,url,'_detail','text');  		
  	});
		function view_data(doctor_id,url,detail='',method='val'){								
			$.ajax({
				url:url,
				method:"POST",
				data:{doctor_id:doctor_id, action:'fetch_single'},
				dataType:'JSON',
				success:function(data){
					$('#doctor_name'+detail)[method](data.doctor_name);
					$('#doctor_email'+detail)[method](data.doctor_email);
					$('#doctor_mobile_no'+detail)[method](data.doctor_mobile_no);
					$('#doctor_address'+detail)[method](data.doctor_address);
					$('#doctor'+detail+'Modal').modal('show');
					$('#modal_title').text('Edit Data');
					$('#action').val('Edit');
					$('#submit_button').val('Edit');						
					$('#hidden_id').val(doctor_id);	
					$('#doctor_department'+detail)[method](data['doctor_department'+detail]);	      		
					$('#doctor_specialization'+detail)[method](data['doctor_specialization' +detail]);	
				}
			})
					
		}

	$(document).on('click', '.delete_button', function(){
		var id = $(this).data('id');		
		data={doctor_id:id, action:'delete'};
		disable(url,dataTable,data,'delete the data');		
	  });
	  
	  $(document).on('click', '.status_button', function(){
		var id = $(this).data('id');
		var status = $(this).data('status');
		data={doctor_id:id,status:status, action:'update_status'};
		disable(url,dataTable,data);
  	});	
});
</script>
<?php include_once(INC."footer.php");?>