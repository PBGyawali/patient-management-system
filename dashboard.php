
<?php
include_once('config.php');
include_once(INC.'init.php');
include_once(CLASS_DIR.'stats.php');
$stats=new stats();
/*if(!$stats->is_login())
{	$_SESSION['error']="You must log in to view this page";
	header("location:".$stats->base_url."");}*/
include_once(INC.'header.php');
include_once(INC.'sidebar.php');
 $username=(isset($_SESSION['user_name']))?$_SESSION['user_name']:'';
 ?>	
	<body id="page-top">
	
	        <div class="col-sm-10 offset-sm-2 py-4">

			<div class="container container-fluid dashboard ">
				<a href="#"><h1 >Welcome	<strong style="color: green";><?php echo ucfirst($username); ?></strong></h1></a>
<h3 >Here is the status for <?php echo $stats->website_name()?> :</h3><br>

<div class="d-sm-flex justify-content-between align-items-center mb-4">
<?php   if($stats->is_master_user()): ?>
<a class="btn btn-primary btn-sm d-none d-sm-inline-block" role="button" href="http://localhost/us_opt1/sql.php?server=1" target="_blank"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Administrate Database</a>
<button id="downloadPdf" class="btn btn-primary btn-sm d-none d-sm-inline-block" role="button" href="#"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Generate Report</button></div>


	            <div class="row ">
				<div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-left-primary py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Average Patients (monthly)</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $stats->avg_month_patient()?></span></div>
                                    </div>
                                    <div class="col-auto"><i class="fa fa-medkit fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-left-success py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>Average Patients (annual)</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $stats->avg_yearly_patient()?></span></div>
                                    </div>
                                    <div class="col-auto"><i class="fa fa-heartbeat fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div><?php  endif?>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-left-info py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-info font-weight-bold text-xs mb-1"><span>Quarterly Patients Limit</span></div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="text-dark font-weight-bold h5 mb-0 mr-3"><span><?php echo number_format($stats->patient_limit())?>%</span></div>
                                            </div>
                                            <div class="col">                                          
	
                                         
                                                <div class="progress progress-sm ">
                                                    <div class="progress-bar  bg-info progress-bar-striped progress-bar-animated" aria-valuenow="<?php echo $stats->patient_limit()?>"  value="<?php echo $stats->patient_limit()?>" max="<?php echo $stats->get_data('facility_target','facility_table')?>" aria-valuemin="0" aria-valuemax="<?php echo $stats->get_data('facility_target','facility_table')?>" style="width:<?php echo $stats->patient_limit()?>%"><span class="sr-only"><?php echo $stats->patient_limit()?>%</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-diagnoses fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
						</div></div> 
                    <a href="appointment.php"><div class="col-md-6 col-xl-3 mb-4 ">
                        <div class="card shadow border-left-warning py-2 stats">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2 ">
                                        <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>Appointment Requests</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $stats->total_apppointments()?></span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-notes-medical fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div></a>
                    </div>
                </div>


                <?php   if($stats->is_admin()): ?>
	           
						<div class="row">
	            	<div class="col-sm-3">
	            		<div class="card text-white bg-info mb-3 stats">
						  	<div class="card-header text-center stats"><span><h4>Total Patients Today</h4></span></div>
						  	<div class="card-body stats">
						    	<h1 class="card-title text-center stats"><?php echo $stats->today_patient(); ?></h1>
						  	</div>
						</div>
	            	</div>
	            	<div class="col-sm-3">
	            		<div class="card text-white bg-warning mb-3 stats">
						  	<div class="card-header text-center stats"><span><h4>Total Patients Yesterday</h4></span></div>
						  	<div class="card-body stats">
						    	<h1 class="card-title text-center stats"><?php echo $stats->yesterday_patient(); ?></h1>
						  	</div>
						</div>
	            	</div>
	            	<div class="col-sm-3">
	            		<div class="card text-white bg-danger mb-3 stats">
						  	<div class="card-header text-center"><span><h4>Total Patients in Last 7 Days</h4></span></a></div>
						  	<div class="card-body">
						    	<h1 class="card-title text-center"><?php echo $stats->last_seven_day_patient(); ?></h1></a>
						  	</div>
						</div>
	            	</div>
	            	<div class="col-sm-3">
	            		<div class="card text-white bg-success mb-3 stats">
						  	<div class="card-header text-center"><span><h4>Total Patient Till Today</h4></span></div>
						  	<div class="card-body">
						    	<h1 class="card-title text-center"><?php echo $stats->total_patient(); ?></h1>
							  </div>							
						</div>
						<!--from here starts the new div--> 

                     


						
					</div>
					
				</div>
             
			</div>   <?php   endif ?>
            <div class="row">
            <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <div class="row">
                                        <div class="col">
                                            <h6 class="m-0 font-weight-bold text-primary">Live Patient Status</h6>
                                        </div>
                                        <div class="col text-right">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="patient_status"></div>
                                </div>
                            </div>
                        </div>
            </div>

            <?php   if($stats->is_admin()): ?>
			
			<div class="row">
                    <div class="col-lg-7 col-xl-8">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary font-weight-bold m-0">Patients Overview</h6>
                                <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fas fa-ellipsis-v text-gray-400"></i></button>
                                    <div class="dropdown-menu shadow dropdown-menu-right animated--fade-in"
                                        role="menu">
                                        <p class="text-center dropdown-header">Actiion:</p><a class="dropdown-item" role="presentation" href="#">&nbsp;Hide</a><a class="dropdown-item" role="presentation" href="#">&nbsp;Refresh</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" role="presentation" href="#">&nbsp;Ignore</a></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chart"class="chart-area"><canvas data-bs-chart="{&quot;type&quot;:&quot;line&quot;,&quot;data&quot;:{&quot;labels&quot;:[<?php echo $stats->getfullmonth()?>],&quot;datasets&quot;:[{&quot;label&quot;:&quot;Patients&quot;,&quot;fill&quot;:true,&quot;data&quot;:[<?php echo $stats->getfullmonthvalue()?>],&quot;backgroundColor&quot;:&quot;rgba(78, 115, 223, 0.05)&quot;,&quot;borderColor&quot;:&quot;rgba(78, 115, 223, 1)&quot;}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{},&quot;scales&quot;:{&quot;xAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;],&quot;drawOnChartArea&quot;:false},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}],&quot;yAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;]},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}]}}}"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-4">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary font-weight-bold m-0">Patient Sources</h6>
                                <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button"><i class="fas fa-ellipsis-v text-gray-400"></i></button>
                                    <div class="dropdown-menu shadow dropdown-menu-right animated--fade-in"
                                        role="menu">
                                        <p class="text-center dropdown-header">Action:</p><a class="dropdown-item" role="presentation" href="#">&nbsp;Hide</a><a class="dropdown-item" role="presentation" href="#">&nbsp;Refresh</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item" role="presentation" href="#">&nbsp;Ignore</a></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-area"><canvas data-bs-chart="{&quot;type&quot;:&quot;doughnut&quot;,&quot;data&quot;:{&quot;labels&quot;:[&quot;Regular&quot;,&quot;Outside&quot;,&quot;Referral&quot;],&quot;datasets&quot;:[{&quot;label&quot;:&quot;&quot;,&quot;backgroundColor&quot;:[&quot;#4e73df&quot;,&quot;#1cc88a&quot;,&quot;#36b9cc&quot;],&quot;borderColor&quot;:[&quot;#ffffff&quot;,&quot;#ffffff&quot;,&quot;#ffffff&quot;],&quot;data&quot;:[<?php echo $stats->get_patient_sources()?>]}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{}}}"></canvas></div>
                                <div
                                    class="text-center small mt-4"><span class="mr-2"><i class="fas fa-circle text-primary"></i>&nbsp;Regular</span><span class="mr-2"><i class="fas fa-circle text-success"></i>&nbsp;Outside</span><span class="mr-2"><i class="fas fa-circle text-info"></i>&nbsp;Refferal</span></div>
						</div>					
					</div><!--card siv ends-->					
				</div>			
			</div>
            <?php   endif ?>
			<footer class="bg-white sticky-footer">
        <div class="container my-auto">
		
			<div class="text-center my-auto copyright"><span>Copyright © <?php echo $stats->website_name()?> PMS <script>
                  document.write(new Date().getFullYear())
                </script></span>
		</div>
			<a class="no-border fixed-bottom text-right size-30 scroll-to-top" href="#page-top"><i class="fas  fa-4x fa-angle-up"></i></a>
        </div>
    </footer>
		</div>
	</div>	
</body>

</html>
<script src="<?php echo JS_URL?>Chart.min.js"></script>  
<script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.debug.js'></script>
<script type="text/javascript" src="<?php echo JS_URL?>bs-init.js"></script> 
<script>

$(document).ready(function(){

    reset_patient_status();

    setInterval(function(){
        reset_patient_status();
    }, 90000);

    function reset_patient_status()
    {
        $.ajax({
            url:"patient_action.php",
            method:"POST",
            data:{action:'dashboard_reset'},
            dataType:"json",
        error:function(request){                                        
                alert(request.responseText);        
            },          
            success:function(data){                
                $('#patient_status').html(data);
            }
        });
    }
    $('#downloadPdf').click(function(event) {
        event.preventDefault();  // get size of report page
  var reportPageHeight = $('#chart').innerHeight();
  var reportPageWidth = $('#chart').innerWidth();
  
  // create a new canvas object that we will populate with all other canvas objects
  var pdfCanvas = $('<canvas />').attr({
    id: "canvaspdf",
    width: reportPageWidth,
    height: reportPageHeight
  });
  
  // keep track canvas position
  var pdfctx = $(pdfCanvas)[0].getContext('2d');
  var pdfctxX = 0;
  var pdfctxY = 0;
  var buffer = 100;
  
  // for each chart.js chart
  $("canvas").each(function(index) {
    // get the chart height/width
    var canvasHeight = $(this).innerHeight();
    var canvasWidth = $(this).innerWidth();
    
    // draw the chart into the new canvas
    pdfctx.drawImage($(this)[0], pdfctxX, pdfctxY, canvasWidth, canvasHeight);
    pdfctxX += canvasWidth + buffer;
    
    // our report page is in a grid pattern so replicate that in the new canvas
    if (index % 2 === 1) {
      pdfctxX = 0;
      pdfctxY += canvasHeight + buffer;
    }
  });
  
  // create new pdf and add our new canvas as an image
  var pdf = new jsPDF('l', 'pt', [reportPageWidth, reportPageHeight]);
  pdf.addImage($(pdfCanvas)[0], 'PNG', 0, 0);
  
  // download the pdf
  pdf.save('filename.pdf');
});
});

</script>