<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<?php  
    function show_due(){
        global $conn;

        $sql = "SELECT due_amount FROM payment 
        WHERE student_id='$_GET[pay_id]' 
        AND payment_for_month='$_GET[month]'
        AND payment_year='$_GET[sess]'
        AND due_amount != 0 ";

        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        $due = $data['due_amount'];

        echo $due;
    }
?>

<br>
<div class="container-fluid">
    <form method="POST">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
            <div class="container col-md-5">
                <div class="card">
                    <div class="card-header">Due Payement Info</div>
                    <div class="card-body">
                    	<?php
                            $sql = "SELECT program FROM students WHERE student_id='$_GET[pay_id]' ";
                            $result = $conn->query($sql);
                            $data = $result->fetch_assoc();
                            $prg = $data['program'];

                            $sql = "SELECT payment_type FROM programs WHERE program_name='$prg' ";
                            $result = $conn->query($sql);
                            $data = $result->fetch_assoc();
                            $pay_type = $data['payment_type'];
                          
                    		if(isset($_POST['save'])){
                    			$sql = "SELECT due_amount FROM payment 
								        WHERE student_id='$_GET[pay_id]' 
								        AND payment_for_month='$_GET[month]'
                                        AND payment_year='$_GET[sess]'
								        AND due_amount != 0";
								$result = $conn->query($sql);
								$data = $result->fetch_assoc();

						        $due = $data['due_amount'];

								$sql1 = "UPDATE payment SET due_amount=0, next_payment_date='0000-00-00'
								        WHERE student_id='$_GET[pay_id]' 
								        AND payment_for_month='$_GET[month]'
                                        AND payment_year='$_GET[sess]'
								        AND due_amount != 0 ";

                                $sql2 = "INSERT INTO payment (payment_for_month, payment_year, payment_date, paid_amount, discount_amount, due_amount, next_payment_date, reference, student_id) VALUES ('$_GET[month]', '$_GET[sess]', STR_TO_DATE('$_POST[pay_date]', '%d/%m/%Y'), '$due', '0', '0', '0000-00-00', '', '$_GET[pay_id]')";

								if($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE){
                                    $last_pay_id = $conn->insert_id;
						            echo '
                                    <div class="text-center"><a href="Invoice.php?pay_id='.$last_pay_id.'&pre_due='.$due.'" class="btn btn-lg btn-info">Show Invoice</a></div><br>
						            <div class="alert alert-success alert-dismissible">
						                <button type="button" class="close" data-dismiss="alert">&times;</button>
						                Due Clear <strong>Successful !!</strong>
						            </div>
						            ';
                                    $sql = "SELECT cont_stdnt, cont_fath, cont_moth FROM students WHERE student_id='$_GET[pay_id]'";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();

                                    if($pay_type=='Course'){
                                        $sms = "Due Payment Received: ".$due."\r\nProgram: ".$prg."\r\nThanks, ROOT Science Care." ;
                                    }else if($pay_type=='Monthly'){
                                        $sms = "Due Payment Received: ".$due."\r\nFor Month: ".$_GET['month']."\r\nThanks, ROOT Science Care." ;
                                    }
                                    $sms_text = $_SERVER["DOCUMENT_ROOT"].'/RMS/SMSTemplate/'.'MakePaymentSMS.txt';
                                    $handle = fopen($sms_text, 'w');
                                    fwrite($handle, $sms);
                                    fclose($handle);

                                    $file_name = $_SERVER["DOCUMENT_ROOT"].'/RMS/RMSSMSFiles/'.'MakePayment.txt';
                                    $handle = fopen($file_name, 'w');
                                    $data = $row['cont_stdnt']."\r\n".$row['cont_fath']."\r\n".$row['cont_moth']."\r\n" ;
                                    fwrite($handle, $data);
                                    fclose($handle);

                                    $default = ini_get('max_execution_time');
                                    set_time_limit(1000);
                                    
                                    if(stristr($_SERVER['HTTP_USER_AGENT'], 'x64')){
                                        $cmd = "cd C:/Program Files (x86)/SMSCaster/ && smscaster.exe -Compose ".$file_name." ".$sms_text." -Long -Start";
                                    }else{
                                        $cmd = "cd C:/Program Files/SMSCaster/ && smscaster.exe -Compose ".$file_name." ".$sms_text." -Long -Start";
                                    }
                                    exec($cmd);

                                    set_time_limit($default);
						        }else{
						            echo '
						            <div class="alert alert-danger alert-dismissible">
						                <button type="button" class="close" data-dismiss="alert">&times;</button>
						                Due Clear <strong>Failed !!</strong>
						            </div>
						            ';
						        }
                    		}
                    	?>
         				<div class="form-group" <?php if($pay_type == 'Course'){echo 'hidden';} ?>>
                            <label for="month">Clear Due for Month :</label>
                            <input type="text" class="form-control" id="pay_for_month" name="pay_for_month" value="<?php echo $_GET['month']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="pre_due">Previous Due Amount :</label>
                            <input type="text" class="form-control" id="pre_due" name="pre_due" value="<?php show_due(); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="rcvd_amount">Received Amount :</label>
                            <input type="text" class="form-control" id="rcvd_amount" name="rcvd_amount" value="<?php show_due(); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="pay_date">Payment Date :</label>
                            <input type="text" class="form-control" id="pay_date" value="<?php date_default_timezone_set('UTC');
                                echo date('d/m/Y'); ?>" name="pay_date">
                        </div>
                     	<div class="form-group">
                            <input type="submit" value="Save" name="save" class="btn btn-success btn-lg btn-block"></input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
<!--Prevent form re-submission-->
<script>
    if( window.history.replaceState ){
    window.history.replaceState( null, null, window.location.href );
}
</script>
<!--Date Picker-->
<script>
    $(document).ready(function(){
        $("#pay_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>