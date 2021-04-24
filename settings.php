<?php

include_once('config.php');
include_once(INC.'init.php');
include_once (CLASS_DIR.'select.php');
$select= new select();
$setup=false;
if(isset($_SESSION['setup'])&&!empty($_SESSION['setup']))
{
    $setup=true;
}
elseif(!$pms->is_login())
{
    header("location:".$pms->login);
}
elseif(!$pms->is_admin())
{
    header("location:".$pms->dashboard);
}
else
{
    $rows = $pms->getArray('facility_table');
}
include_once(INC.'header.php');
include_once(INC.'sidebar.php');

?>
<div class="col-sm-10 offset-sm-2 py-4">
<div class="d-flex flex-column " id="content-wrapper">
<div id="content">    
<div class="container-fluid ">   
 <div class="col-12 p-0">    
<div class="d-flex flex-column" ><!-- Never added anything in the bottom -->
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?php echo ($setup)?'System Configuration':'Setting'?></h1>
                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <form method="post" id="setting_form" enctype="multipart/form-data" action="<?php echo SERVER_URL?>setting_action.php">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="m-0 font-weight-bold text-primary"><?php echo ($setup)?'Set up Account':'Setting'?></h6>
                                    </div>
                                    <div clas="col text-right" >
                                        <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm"> <?php echo ($setup)?'<i class="fas fa-save"></i>  Set Up':'<i class="fas fa-edit"></i> Edit'?></button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>facility Name</label>
                                            <input type="text" name="facility_name" value="<?php echo ($setup)?'':$rows['facility_name']?>"id="facility_name" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label>facility Email</label>
                                            <input type="text" name="facility_email" value="<?php echo ($setup)?'':$rows['facility_email']?>"id="facility_email" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label>facility Contact No.</label>
                                            <input type="text" name="facility_contact_no" value="<?php echo ($setup)?'':$rows['facility_contact_no']?>"id="facility_contact_no" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label>facility Address</label>
                                            <input type="text" name="facility_address" value="<?php echo ($setup)?'':$rows['facility_address']?>"id="facility_address" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        
                                            <div class="form-group">
                                                <label>Patient Target</label>
                                                <input type="number" name="facility_target" value="<?php echo ($setup)?'':$rows['facility_target']?>"id="facility_target" class="form-control" />
                                            </div>                                    
                                        <div class="form-group">
                                            <label>Currency</label>
                                            <?php  echo $select->Currency_list(($setup)?'':$rows['facility_currency']
                                        ); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Timezone</label>
                                            <?php  echo $select->Timezone_list(($setup)?'':$rows['facility_timezone']); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Select Logo</label><br />
                                            <input type="file" name="facility_logo" class="file_upload" id="facility_logo" data-allowed_file='[<?php echo '"' . implode('","', ALLOWED_IMAGES) . '"'?>]' data-upload_time="later" accept="<?php echo "image/" . implode(", image/", ALLOWED_IMAGES);?>"  />
                                            <br />
                                            <span class="text-muted">Only <?php  echo join(' and ', array_filter(array_merge(array(join(', ', array_slice(ALLOWED_IMAGES, 0, -1))), array_slice(ALLOWED_IMAGES, -1)), 'strlen'));?> extensions are supported</span><br />
                                            <span id="uploaded_logo"></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if($setup):?>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Admin Email Address</label>
                                            <input type="text" name="admin_email" id="admin_email" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Admin Username</label>
                                            <input type="text" name="admin_username" id="admin_name" class="form-control" required data-parsley-trigger="keyup" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Admin Password</label>
                                            <input type="password" name="admin_password" id="admin_password" class="form-control" required data-parsley-trigger="keyup" />
                                        </div>
                                    </div>
                                </div>
                                <?php endif?>
                            </div>
                        </div>
                    </form>
              
<script>
$(document).ready(function(){

    $('#setting_form').parsley();
    var url=$('#setting_form').attr('action');

	$('#setting_form').on('submit', function(event){
		event.preventDefault();
		if($('#setting_form').parsley().isValid())
		{		
            var button_value=$('#edit_button').html();           
			$.ajax({
				url:url,
				method:"POST",
				data:new FormData(this),
                dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#edit_button').attr('disabled', 'disabled');
					$('#edit_button').html('wait...');
				},
                complete:function()
				{
					$('#edit_button').attr('disabled', false);
                    $('#edit_button').html(button_value); 
				},
				success:function(data)
				{ 
                    result(data.success);					
				}
			})
		}
	});

});
</script>
<?php include_once(INC."footer.php");?>