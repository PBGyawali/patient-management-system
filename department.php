<?php

//department.php

include_once('config.php');
include_once(INC.'init.php');

$pms = new pms();

if(!$pms->is_login())
{
	header("location:".$pms->base_url."");
}

if(!$pms->is_admin())
{
	header("location:".$pms->base_url."dashboard.php");
}

include_once(INC.'header.php');
include_once(INC.'sidebar.php');
?>
	
	
	        <div class="col-sm-10 offset-sm-2 py-4">
	        	<span id="message"></span>
	            <div class="card">
	            	<div class="card-header">
	            		<div class="row">
	            			<div class="col">
	            				<h2>Department Area</h2>
	            			</div>
	            			<div class="col text-right">
	            				<button type="button" name="add_department" id="add_department" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add</button>
	            			</div>
	            		</div>
	            	</div>
	            	<div class="card-body">
	            		<div class="table-responsive">
	            			<table class="table table-striped table-bordered" id="departments">
	            				<thead>
	            					<tr>
										<th>Department Name</th>
										<th>Capacity</th>
										<th>Department Doctor List</th>							
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

<div id="departmentModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="deparment_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add Data</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-5 text-right">Department Name</label>
			            	<div class="col-md-6">
			            		<input type="text" name="department_name" id="department_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s/()-]+$/" data-parsley-trigger="keyup" />
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
url="department_action.php";
	var dataTable = $('#departments').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:url,
			type:"POST",
			dataType:'json',
			data:{action:'fetch'},		
		},
		"columnDefs":[
			{
				"targets":[2],
				"orderable":false,
			},
		],
	});

	$('#add_department').click(function(){		
		$('#deparment_form')[0].reset();
		$('#deparment_form').parsley().reset();
    	$('#modal_title').text('Add Data');
    	$('#action').val('Add');
    	$('#submit_button').val('Add');
    	$('#departmentModal').modal('show');    	
		$('#form_message').html('');
		$('#submit_button').attr('disabled', false);
	});

	$('#deparment_form').parsley();

	$('#deparment_form').on('submit', function(event){
		event.preventDefault();
		if($('#deparment_form').parsley().isValid())
		{		
			$.ajax({
				url:url,
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
				beforeSend:function(){
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data)	{
					$('#submit_button').attr('disabled', false);
					if(data.error != ''){
						$('#form_message').html(data.error);
						$('#submit_button').val('Add');
					}
					else{
						$('#departmentModal').modal('hide');
						result(data.success,dataTable);						
					}
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){
		var department_id = $(this).data('id');
		$('#deparment_form').parsley().reset();
		$('#form_message').html('');
		$.ajax({
	      	url:url,
	      	method:"POST",
	      	data:{department_id:department_id, action:'fetch_single'},
	      	dataType:'JSON',
	      	success:function(data)
	      	{
				$('#department_name').val(data.department_name);  				
	        	$('#modal_title').text('Edit Data');
	        	$('#action').val('Edit');
	        	$('#submit_button').val('Edit');
	        	$('#departmentModal').modal('show');
				$('#hidden_id').val(department_id);
				$('#submit_button').attr('disabled', false);
	      	}
	    })
	});

	$(document).on('click', '.delete_button', function(){
		var id = $(this).data('id');				
		data={id:id, action:'delete'};
		disable(url,dataTable,data,'delete the data');    
  });
});
</script>
<?php include_once(INC."footer.php");?>