<?php
include_once('config.php');
include_once(INC.'init.php');

$patient = new pms();

if(!$patient->is_login())
	header("location:".$patient->base_url."");

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
	            				<h2>Patient Area</h2>
	            			</div>
	            			<div class="col-sm-4">
	            				<div class="row input-daterange">
	            					<div class="col-md-6">
		            					<input type="text" name="from_date" id="from_date" class="form-control form-control-sm" placeholder="From Date" readonly />
		            				</div>
		            				<div class="col-md-6">
		            					<input type="text" name="to_date" id="to_date" class="form-control form-control-sm" placeholder="To Date" readonly />
		            				</div>
		            			</div>
		            		</div>
		            		<div class="col-md-2">
	            				<button type="button" name="filter" id="filter" class="btn btn-info btn-sm"><i class="fas fa-filter"></i></button>
	            				&nbsp;
	            				<button type="button" name="refresh" id="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
	            			</div>
	            			<div class="col-md-2 text-right" >	            				
	            				<button type="button" name="add_patient" id="add_patient" class="btn btn-success btn-sm" ><i class="fas fa-user-plus"></i></button>
	            			</div>
	            		</div>
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="patient_history">
	            				<thead>
	            					<tr>
	            						<th>Patient Name</th>
										<th>Visiting doctor Name</th>
										<th>Department</th>
										<th>In Time</th>
										<th>Out Time</th>
										<th>Status</th>
										<?php
										if($patient->is_admin())										
											echo '<th>Enter By</th>';										
										?>										
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

<div id="patientModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="patient_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add patient</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Name</label>
			            	<div class="col-md-8">
			            		<input type="text" name="patient_name" id="patient_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Email</label>
			            	<div class="col-md-8">
			            		<input type="text" name="patient_email" id="patient_email" class="form-control" required data-parsley-type="email" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Mobile No.</label>
			            	<div class="col-md-8">
			            		<input type="text" name="patient_mobile_no" id="patient_mobile_no" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Address</label>
			            	<div class="col-md-8">
			            		<textarea name="patient_address" id="patient_address" class="form-control" required data-parsley-maxlength="400" data-parsley-trigger="keyup"></textarea>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Department</label>
			            	<div class="col-md-8">
			            		<select name="patient_department" id="patient_department" class="form-control" required data-parsley-trigger="keyup">
			            			<option value="">Select Departent</option>
			            			<?php echo $patient->load_department(); ?>
			            		</select>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Doctor To visit</label>
			            	<div class="col-md-8">
			            		<select name="patient_visit_doctor_name" id="patient_visit_doctor_name" class="form-control" required data-parsley-trigger="keyup">
			            			<option value="">Select Doctor first</option>
			            		</select>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Reason to Visit</label>
			            	<div class="col-md-8">
			            		<textarea name="patient_reason_to_visit" id="patient_reason_to_visit" class="form-control" required data-parsley-maxlength="400" data-parsley-trigger="keyup"></textarea>
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

<div id="patient_detailModal" class="modal fade">
  	<div class="modal-dialog modal-lg">
    	<form method="post" id="patient_details_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">View Patient Details</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">

		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Patient Name</b></label>
			            	<div class="col-md-8">
			            		<span id="patient_name_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Patient Email</b></label>
			            	<div class="col-md-8">
			            		<span id="patient_email_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Patient Mobile No.</b></label>
			            	<div class="col-md-8">
			            		<span id="patient_mobile_no_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Patient Address</b></label>
			            	<div class="col-md-8">
			            		<span id="patient_address_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Department</b></label>
			            	<div class="col-md-8">
			            		<span id="patient_department_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Doctor To visit</b></label>
			            	<div class="col-md-8">
			            		<span id="patient_visit_doctor_name_detail"></span>
			            	</div>
			            </div>
		          	</div>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right"><b>Reason to Visit</b></label>
			            	<div class="col-md-8">
			            		<span id="patient_reason_to_visit_detail"></span>
			            	</div>
			            </div>
		          	</div>		          	
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id_detail" id="hidden_patient_id" />
          			<input type="hidden" name="action" value="update_outing_detail" />
          			<input type="submit" name="submit" id="detail_submit_button" class="btn btn-success" value="Save" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<script>

$(document).ready(function(){
	$('#patient_form').parsley();
	url="patient_action.php";
	
		var dataTable = $('#patient_history').DataTable({
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
					<?php if($patient->is_admin()) echo '"targets":[7]'; else echo '"targets":[6]'	?>,
					"orderable":false,					
				},
			],
		});


	$('#add_patient').click(function(){		
		$('#patient_form')[0].reset();
		$('#patient_form').parsley().reset();
    	$('#modal_title').text('Add patient');
    	$('#action').val('Add');
    	$('#submit_button').val('Add');
    	$('#patientModal').modal('show');
		$('#patient_visit_doctor_name').html('<option value="" >Select Department First</option>');
	});

	$(document).on('change', '#patient_department', function(){
		var doctor_id = $('#patient_department').find(':selected').data('doctor');		    
        $.ajax({
            url:url,
            method:"POST",
            dataType:'json',
            data:{doctor_id:doctor_id,action:'load_doctor'},
            success:function(data){
                $('#patient_visit_doctor_name').html(data);
            }
        });		
	})
	

	$('#patient_form').on('submit', function(event){
		event.preventDefault();
		if($('#patient_form').parsley().isValid()){		
			$.ajax({
				url:url,
				method:"POST",
				data:$(this).serialize(),
				beforeSend:function(){
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				complete:function(){
					$('#submit_button').attr('disabled', false);
				},
				success:function(data){					
					$('#patientModal').modal('hide');
					result(data,dataTable);										
				}
			})
		}
	});	

	$(document).on('click', '.delete_button', function(){
		var id = $(this).data('id');
		data={id:id, action:'delete'};
		disable(url,dataTable,data,' delete the data');		
  	});

	  $(document).on('click', '.edit_button', function(){
		var patient_id = $(this).data('id');
		$('#patient_form').parsley().reset();
		view_data(patient_id,url);	        		        	
	});

  	$(document).on('click', '.view_button', function(){
  		var patient_id = $(this).data('id');	      		
		  view_data(patient_id,url,'_detail','html');  		    
  	});

	
	  function view_data(patient_id,url,detail='',method='val'){		  					
			$.ajax({
				url:url,
				method:"POST",
				data:{patient_id:patient_id, action:'fetch_single'},
				dataType:'JSON',
				success:function(data){
					$('#patient_name'+detail)[method](data.patient_name);
					$('#patient_email'+detail)[method](data.patient_email);
					$('#patient_mobile_no'+detail)[method](data.patient_mobile_no);
					$('#patient_address'+detail)[method](data.patient_address);
					$('#patient_department'+detail)[method](data['patient_department'+detail]);
					$('#patient_visit_doctor_name'+detail).html(data['patient_visit_doctor_name'+detail]);	      		
					$('#patient_reason_to_visit'+detail)[method](data.patient_reason_to_visit);						
					$('#patient'+detail+'Modal').modal('show');
					$('#modal_title').text('Edit Data');
					$('#action').val('Edit');
					$('#submit_button').val('Edit');						
					$('#hidden_id'+detail).val(patient_id);						
				}
			})					
		}  	

  	$('#patient_details_form').on('submit', function(event){
  		event.preventDefault();
  		if($('#patient_details_form').parsley().isValid())
		{		
			$.ajax({
				url:url,
				method:"POST",
				data:$(this).serialize(),
				beforeSend:function(){
					$('#detail_submit_button').attr('disabled', 'disabled');
					$('#detail_submit_button').val('wait...');
				},
				success:function(data){
					$('#detail_submit_button').attr('disabled', false);
					$('#detail_submit_button').val('Save');
					$('#patientdetailModal').modal('hide');
					result(data,dataTable);					
				}
			});			
		}
  	});  

});
</script>
<?php include_once(INC.'footer.php');?>