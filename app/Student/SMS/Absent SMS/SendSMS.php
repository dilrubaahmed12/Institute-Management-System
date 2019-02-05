<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
		<?php
			if(is_file($_GET['nolist'])){
				$default = ini_get('max_execution_time');
            	set_time_limit(9999);

				$file_name = $_GET['nolist'];
			    
			    if(stristr($_SERVER['HTTP_USER_AGENT'], 'x64')){
                $cmd = "cd C:/Program Files (x86)/SMSCaster/ && smscaster.exe -ImportOutbox ".$file_name." -Long";
	            }else{
	                $cmd = "cd C:/Program Files/SMSCaster/ && smscaster.exe -ImportOutbox ".$file_name." -Long";
	            }
	            exec($cmd);
			    
			    set_time_limit($default);

			}else{
				echo '
				<div class="col-md-10">
		            <div class="alert alert-danger alert-dismissible">
		                <button type="button" class="close" data-dismiss="alert">&times;</button>
		                <strong>SMS Sending Failed !!</strong>
		            </div>
		        </div>';
			}
		?>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<script>    
    if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", "http://localhost/RMS/app/Student/Student.php");
    }
</script>