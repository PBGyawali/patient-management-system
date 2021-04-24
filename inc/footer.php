		</div>
	</body>
</html>
<script src="<?php echo JS_URL.'confirmdefaults.js'?>"></script>
<script src="<?php echo JS_URL.'confirm.js'?>"></script>
<script>	

	function disable(url,datatable,data,message="change the status"){	
		//var data2 = {"btn_action":"delete"};
		//var main_data = Object.assign({}, data, data2);
        $.confirm
        ({
            title: 'Confirmation please!',
            content: "This will "+ message+". Are you sure?", 
			type: 'blue',   
            buttons:{
						Yes: { 
							btnClass: 'btn-blue',           
							action: function() {   
								$.ajax({
									url:url,
									method:"POST",
									data:data,                              
									dataType:"JSON",
									success:function(response){          
										result(response,datatable);										                               
									}
								});
							}
						},                      
					}
        });
    }
	function result(data,dataTable=''){	       
		$('#alert_action,#message').fadeIn().html(data);
		timeout(dataTable);				
    }

	function timeout(datatable='')
	{		
		setTimeout(function(){
            $('.error, .message, .alert').slideUp();
		}, 3000);
		
		setTimeout(function(){
		$('#message,#alert_action,#form_message').html('');
		}, 5000);
		if(datatable)
		datatable.ajax.reload();   
	}

</script>
