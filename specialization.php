<?php
include_once('config.php');
include_once(INC.'init.php');

if(!$pms->is_login())
    header("location:".$pms->login);

if(!$pms->is_admin())
    header("location:".$pms->dashboard);

include_once(INC.'header.php');
include_once(INC.'sidebar.php');
?>
<div class="col-sm-10 offset-sm-2 py-4">
<div class="d-flex flex-column " id="content-wrapper">
<div id="content">    
<div class="container-fluid ">   
 <div class="col-12 p-0">    
<div class="d-flex flex-column" >
		<span id="alert_action"></span>
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-secondary">
                    <div class="card-header">
                    	<div class="row">
                        	<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="card-title">Specialization List</h3>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 text-right">
                            	<button type="button" name="add" id="add_button" data-toggle="modal" data-target="#specializationModal" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add</button>
                        	</div>
                        </div>
                       
                        <div class="clear:both"></div>
                   	</div>
                <div class="card-body">
                    <div class="row">
                    	<div class="col-sm-12 table-responsive">
                    		<table id="specialization_data" class="table table-bordered table-striped">
                    			<thead><tr>
									<th>ID</th>
									<th>Specialization Name</th>
									<th>Status</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr></thead>
                    		</table>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    				
    		
	
	<div id="specializationModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="specialization_form"  action="<?php echo SERVER_URL?>specialization_action.php">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title"><i class="fa fa-plus"></i>Add specialization</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
		          		<div class="row">
			            	<label class="col-md-3 text-left">Enter data</label>
			            	<div class="col-md-9 px-0 pr-1">
			            		<input type="text" name="specialization_name" id="specialization_name" class="form-control"  data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-trigger="keyup" />
			            	</div>
			            </div>
		          	</div>
		          	
        		<div class="modal-footer">
				<input type="hidden" name="specialization_id" id="specialization_id"/>
    					<input type="hidden" name="btn_action" id="btn_action"/>
    					<input type="submit" name="action" id="action" class="btn btn-success"value="Add" />
    					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>          			
        		</div>
      		</div>
    	</form>
  	</div>
</div>
<script>
$(document).ready(function(){
	var url=$('#specialization_form').attr('action');	

	$('#add_button').click(function(){
		$('#specialization_form')[0].reset();
		$('.modal-title').html("<i class='fa fa-plus'></i> Add specialization");
		$('#action').val('Add');
		$('#btn_action').val('Add');
	});

	$(document).on('submit','#specialization_form', function(event){
		event.preventDefault();
		$('#action').attr('disabled','disabled');
		var form_data = $(this).serialize();
		$.ajax({
			url:url,
			method:"POST",
			data:form_data,
			dataType:"json",
			complete:function()
			{
				$('#action').attr('disabled', false);
				$('#action').val('Save');
			},
			success:function(data)
			{
				if(data.error != '')
					$('#form_message').html(data.error);
				else{
					$('#specialization_form')[0].reset();
					$('#specializationModal').modal('hide');
					$('#alert_action,#message').fadeIn().html(data.success);
					specializationdataTable.ajax.reload();						
				}
				timeout();
				
			}
		})
	});

	$(document).on('click', '.update', function(){
		var specialization_id = $(this).attr("id");
		var btn_action = 'fetch_single';
		$.ajax({
			url:url,
			method:"POST",
			data:{specialization_id:specialization_id, btn_action:btn_action},
			dataType:"json",
			success:function(data)
			{
				$('#specializationModal').modal('show');
				$('#specialization_name').val(data.specialization_name);
				$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit specialization");
				$('#specialization_id').val(specialization_id);
				$('#action').val('Edit');
				$('#btn_action').val("Edit");
			}
		})
	});

	var specializationdataTable = $('#specialization_data').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax" : {
			url:url,
			type:"POST",
			dataType:'json',
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[3, 4],
				"orderable":false,
			},
		],
		"pageLength": 25
	});
	
	
	$(document).on('click', '.delete', function(){
			var specialization_id = $(this).attr('id');
			var status = $(this).data("status");
			var btn_action = "delete";	
			var data={specialization_id:specialization_id, status:status, btn_action:btn_action};
			disable(url,specializationdataTable,data,'change the status');    
  	});


});
</script>

<?php include_once(INC.'footer.php');?>


				