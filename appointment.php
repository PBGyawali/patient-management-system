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
    $('.input-daterange, .datepicker').datepicker({
		todayBtn: "linked",
        format: "dd-mm-yyyy",
        autoclose: true
    });
	var date = new Date();
    date.setDate(date.getDate());

    $('.timepicker').timeselector({
    min:'09:30',
	max:'18:30',
	step: 5,

  })


    $(".timepicker").on("change.datetimepicker", function (e) {
        console.log('test');
        $('.timepicker').datetimepicker('minDate', e.date);
    });

  
    
});
</script>	
	        <div class="col-sm-10 offset-sm-2 py-4">
	        	<span id="message"></span>
	            <div class="card">
	            	<div class="card-header">
	            		<div class="row">
	            			<div class="col-sm-4">
	            				<h2>Appointment Area</h2>
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
	            				<button type="button" name="add_appointment" id="add_appointment" class="btn btn-success btn-sm" ><i class="fas fa-user-plus"></i></button>
	            			</div>
	            		</div>
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="appointment_table">
	            				<thead>
	            					<tr>
	            						<th>Patient Name</th>
										<th>Visiting doctor Name</th>
										<th>Department</th>
										<th>Appointment Date Time</th>
										<th>End Time</th>
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

<div id="appointmentModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="appointment_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add appointment</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Patient Full Name</label>
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
			            	<label class="col-md-4 text-right">Date</label>
			            	<div class="col-md-8">
							<input class="form-control datepicker" id="datepicker" name="appdate"  required="required" data-date-format="dd-mm-yyyy">
			            	</div>
			            </div>
					  </div>
					  <div class="form-group">
		          		<div class="row">
			            	<label class="col-md-4 text-right">Time</label>
			            	<div class="col-md-8">
							<input type="text" name="apptime" class="form-control  timepicker" data-toggle="datetimepicker"  required onkeydown="return false" onpaste="return false;" ondrop="return false;" autocomplete="off" />	
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
			            	<label class="col-md-4 text-right">Choose Doctor</label>
			            	<div class="col-md-8">
			            		<select name="patient_visit_doctor_name" id="patient_visit_doctor_name" class="form-control" required data-parsley-trigger="keyup">
			            			<option value="">Select doctor</option>
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

<div id="appointment_detailModal" class="modal fade">
  	<div class="modal-dialog modal-lg">
    	<form method="post" id="appointment_details_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">View appointment Details</h4>
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
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id_detail" id="hidden_appointment_id" />
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

	
url="appointment_action.php";
$('#appointment_form').parsley();
$('#appointment_details_form').parsley();
var dataTable =load_data();
	function load_data(from_date = '', to_date = '')
	{
		var dataTable = $('#appointment_table').DataTable({
			"processing" : true,
			"serverSide" : true,
			"order" : [],
			"ajax" : {
				url:url,
				type:"POST",
				data:{action:'fetch', from_date:from_date, to_date:to_date}
			},
			"columnDefs":[
				{
					<?php if($patient->is_admin()):?>
						<?php echo '"targets":[7]'?>
					<?php else:	?>
						<?php echo '"targets":[6]'?>
					<?php endif	?>,
					"orderable":false,
				},
			],
		});
		return dataTable;
	}

	

	$('#add_appointment').click(function(){		
		$('#appointment_form')[0].reset();
		$('#appointment_form').parsley().reset();
    	$('#modal_title').text('Add appointment');
    	$('#action,#submit_button').val('Add');    	
    	$('#appointmentModal').modal('show');
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

	
	$('#appointment_form').on('submit', function(event){
		event.preventDefault();
		if($('#appointment_form').parsley().isValid())
		{		
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
					$('#appointmentModal').modal('hide');
					result(data,dataTable);					
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){
		var appointment_id = $(this).data('id');
		$('#appointment_form').parsley().reset();
		view_data(appointment_id,url); 
	});	

  	$(document).on('click', '.view_button', function(){
  		var appointment_id = $(this).data('id');
		  view_data(appointment_id,url,'_detail','html');	      		      		
	      		
  	});

	  function view_data(appointment_id,url,detail='',method='val'){		  					
			$.ajax({
				url:url,
				method:"POST",
				data:{appointment_id:appointment_id, action:'fetch_single'},
				dataType:'JSON',
				success:function(data){
					$('#patient_name'+detail)[method](data.patient_name);
					$('#patient_email'+detail)[method](data.patient_email);
					$('#patient_mobile_no'+detail)[method](data.patient_mobile_no);
					$('#patient_address'+detail)[method](data.patient_address);
					$('#patient_department'+detail)[method](data['appointment_department_id'+detail]);
					$('#patient_visit_doctor_name'+detail).html(data['appointment_doctor_id'+detail]);	      		
					$('#patient_reason_to_visit'+detail)[method](data.patient_reason_to_visit);
					$('#patient_outing_remark').val(data.patient_outing_remark);
					$('#timepicker').val(data.appointment_time);
					$('#datepicker').val(data.appointment_date);
					$('#appointment'+detail+'Modal').modal('show');
					$('#modal_title').text('Edit Data');					
					$('#submit_button,#action').val('Edit');						
					$('#hidden_id'+detail).val(appointment_id);						
				}
			})					
		}

  	$('#appointment_details_form').on('submit', function(event){
  		event.preventDefault();
  		if($('#appointment_details_form').parsley().isValid())
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
					$('#appointmentdetailModal').modal('hide');
					result(data,dataTable);
				}
			});
		}
  	});

	  $(document).on('click', '.delete_button', function(){
		var id = $(this).data('id');
		data={id:id, action:'delete'};
		disable(url,dataTable,data,' delete the data ');		
  	});
  	$('#filter').click(function(){
  		var from_date = $('#from_date').val();
  		var to_date = $('#to_date').val();
  		$('#appointment_table').DataTable().destroy();
  		load_data(from_date, to_date);
  	});

  	$('#refresh').click(function(){
  		$('#from_date').val('');
  		$('#to_date').val('');
  		$('#appointment_table').DataTable().destroy();
  		load_data();
  	});


});

</script>
<?php include_once(INC.'footer.php');?>