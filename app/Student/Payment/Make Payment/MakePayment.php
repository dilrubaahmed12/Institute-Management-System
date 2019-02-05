<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<?php  
    function show_rcvbable_amount(){
        global $conn;

        $sql = "SELECT program, subjects, date_of_admission FROM students WHERE student_id='$_GET[pay_id]' ";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        $prg = $data['program'];
        $taken_subs = explode(" ", $data['subjects']);
        $doa = $data['date_of_admission'];

        mysqli_free_result($result);
        $data = NULL;

        $sql = "SELECT subjects, payment_type, course_fee, fee_per_subject, admission_fee, extra_fee FROM programs WHERE program_name='$prg' ";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        $all_subs = explode(" ", $data['subjects']);
        $fee = explode(",", $data['fee_per_subject']);
        
        if($data['payment_type'] == 'Monthly'){
            $total_fee = 0;
            for($i=0; $i<sizeof($taken_subs); $i++){
                for($j=0; $j<sizeof($all_subs); $j++){
                    if($taken_subs[$i] == $all_subs[$j]){
                        $total_fee += $fee[$j];
                    }
                }
            }

            date_default_timezone_set('UTC');
            if($doa == date('Y-m-d')){
                $total_fee += $data['admission_fee'] + $data['extra_fee'];
            }else{
                $total_fee += $data['extra_fee'];
            }
        }else if($data['payment_type'] == 'Course'){
            $total_fee = $data['course_fee'];
            date_default_timezone_set('UTC');
            if($doa == date('Y-m-d')){
                $total_fee += $data['admission_fee'] + $data['extra_fee'];
            }else{
                $total_fee += $data['extra_fee'];
            }
        }

        echo $total_fee;
    }
?>

<br>
<div class="container-fluid">
    <form method="POST">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
            <div class="col-md-5">
                <div class="card">
                    <!-- Payment Information -->
                    <div class="card-header">Payment Info</div>
                    <div class="card-body">
         				<?php
                            date_default_timezone_set('UTC');
                            $month_name = date('F');

                            $sql = "SELECT program FROM students WHERE student_id='$_GET[pay_id]' ";
                            $result = $conn->query($sql);
                            $data = $result->fetch_assoc();
                            $prg = $data['program'];

                            $sql = "SELECT payment_type FROM programs WHERE program_name='$prg' ";
                            $result = $conn->query($sql);
                            $data = $result->fetch_assoc();
                            $pay_type = $data['payment_type'];

         					if(isset($_POST['save']) && $pay_type=='Monthly'){
                                make_payment(); 
         					}else if(isset($_POST['save']) && $pay_type=='Course'){
                                make_course_payment(); 
                            }
         				?>
         				<div class="form-group" <?php if($pay_type == 'Course'){echo 'hidden';} ?>>
                            <label for="month">Payment for Month :</label>
                            <select name="month" class="form-control" id="month">
                                <option <?php if($month_name=='January'){ echo 'selected'; } ?>>January</option>
                                <option <?php if($month_name=='February'){ echo 'selected'; } ?>>February</option>
                                <option <?php if($month_name=='March'){ echo 'selected'; } ?>>March</option>
                                <option <?php if($month_name=='April'){ echo 'selected'; } ?>>April</option>
                                <option <?php if($month_name=='May'){ echo 'selected'; } ?>>May</option>
                                <option <?php if($month_name=='June'){ echo 'selected'; } ?>>June</option>
                                <option <?php if($month_name=='July'){ echo 'selected'; } ?>>July</option>
                                <option <?php if($month_name=='August'){ echo 'selected'; } ?>>August</option>
                                <option <?php if($month_name=='September'){ echo 'selected'; } ?>>September</option>
                                <option <?php if($month_name=='October'){ echo 'selected'; } ?>>October</option>
                                <option <?php if($month_name=='November'){ echo 'selected'; } ?>>November</option>
                                <option <?php if($month_name=='December'){ echo 'selected'; } ?>>December</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="session">Session :</label>
                            <input type="text" class="form-control" id="session" value="<?php
                                date_default_timezone_set('UTC');
                                echo date('Y'); ?>" name="session" required>
                        </div>
                        <div class="form-group">
                            <label for="rcvable_amount">Receivable Amount :</label>
                            <input type="text" class="form-control" id="rcvable_amount" name="rcvable_amount" value="<?php show_rcvbable_amount(); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="rcvd_amount"><strong>Received Amount :</strong></label>
                            <input type="text" class="form-control" id="rcvd_amount" name="rcvd_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="due_amount">Due Amount :</label>
                            <input type="text" class="form-control" id="due_amount" name="due_amount" readonly>
                        </div>
                        <div class="form-group">
                            <label for="discount">Discount :</label>
                            <input type="text" class="form-control" id="discount" name="discount">
                        </div>
                        <div class="form-group">
                            <label for="nxt_pay_date">Next Payment :</label>
                            <input type="text" class="form-control" id="nxt_pay_date" placeholder="dd/mm/yyyy" name="nxt_pay_date">
                        </div>
                        <div class="form-group">
                            <label for="ref">Reference :</label>
                            <input type="text" class="form-control" id="v" placeholder="Add Referrer Name" name="ref">
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
            <div class="col-md-5">
                <div class="card">
                	<div class="card-header">Student Info</div>
                	<div class="card-body">
                		<?php show_std_info(); ?>
                	</div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php 
	function show_std_info(){
		global $conn;
		$sql = "SELECT * FROM students WHERE student_id='$_GET[pay_id]'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        echo "
            <strong>Student Name: </strong>$row[first_name] $row[last_name]<br><br>
            <strong>Class: </strong>$row[class]<br><br>
            <strong>Session: </strong>$row[session]<br><br>
            <strong>Program: </strong>$row[program]<br><br>
            <strong>Subjects: </strong>$row[subjects]<br><br>
            <strong>Program Roll: </strong>$row[program_roll]<br><br>
            <strong>Batch Day: </strong>$row[batch_day]<br><br>
            <strong>Batch Time: </strong>$row[batch_time]<br><br>
        ";  
	}
?>
<?php  
    function make_course_payment(){
        global $conn;

        $sess = $_POST['session'];
        $paid_amount = filter_var($_POST['rcvd_amount'], FILTER_SANITIZE_NUMBER_INT);
        $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_INT);
        $due_amount = filter_var($_POST['due_amount'], FILTER_SANITIZE_NUMBER_INT);
        $nxt_pay_date = $_POST['nxt_pay_date'];
        $pay_date = $_POST['pay_date'];
        $ref = $_POST['ref'];
        $stdent_id = $_GET['pay_id'];
        
        $sql = "INSERT INTO payment (payment_year, payment_date, paid_amount, discount_amount, due_amount, next_payment_date, reference, student_id) VALUES ('$sess', STR_TO_DATE('$pay_date', '%d/%m/%Y'), '$paid_amount', '$discount', '$due_amount', STR_TO_DATE('$nxt_pay_date', '%d/%m/%Y'), '$ref', '$stdent_id')";

        if($conn->query($sql) === TRUE){
            $last_pay_id = $conn->insert_id;
            echo '
            <div class="text-center"><a href="Invoice.php?pay_id='.$last_pay_id.'" class="btn btn-lg btn-info">Show Invoice</a></div><br>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Payment <strong>Successful !!</strong>
            </div>
            ';
            $sql = "SELECT cont_stdnt, cont_fath, cont_moth, program FROM students WHERE student_id='$_GET[pay_id]'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if($due_amount){
                $sms = "Payment Received: ".$paid_amount."\r\nProgram: ".$row['program']."\r\nDue Amount: ".$due_amount."\r\nNext Payment Date: ".$nxt_pay_date."\r\nThanks, ROOT Science Care." ;
            }else{
                $sms = "Payment Received: ".$paid_amount."\r\nProgram: ".$row['program']."\r\nThanks, ROOT Science Care." ;
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
                Payment <strong>Failed !!</strong>
            </div>
            ';
        } 
    }
?>
<?php 
	function make_payment(){
		global $conn;

        $pay_month = $_POST['month'];
        $sess = $_POST['session'];
        $paid_amount = filter_var($_POST['rcvd_amount'], FILTER_SANITIZE_NUMBER_INT);
        $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_INT);
        $due_amount = filter_var($_POST['due_amount'], FILTER_SANITIZE_NUMBER_INT);
        $nxt_pay_date = $_POST['nxt_pay_date'];
        $pay_date = $_POST['pay_date'];
        $ref = $_POST['ref'];
        $stdent_id = $_GET['pay_id'];
        
        $sql = "INSERT INTO payment (payment_for_month, payment_year, payment_date, paid_amount, discount_amount, due_amount, next_payment_date, reference, student_id) VALUES ('$pay_month', '$sess', STR_TO_DATE('$pay_date', '%d/%m/%Y'), '$paid_amount', '$discount', '$due_amount', STR_TO_DATE('$nxt_pay_date', '%d/%m/%Y'), '$ref', '$stdent_id')";
        
        if($conn->query($sql) === TRUE){
            $last_pay_id = $conn->insert_id;
            echo '
            <div class="text-center"><a href="Invoice.php?pay_id='.$last_pay_id.'" class="btn btn-lg btn-info">Show Invoice</a></div><br>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Payment <strong>Successful !!</strong>
            </div>
            ';
            $sql = "SELECT cont_stdnt, cont_fath, cont_moth FROM students WHERE student_id='$_GET[pay_id]'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if($due_amount){
                $sms = "Payment Received: ".$paid_amount."\r\nFor Month: ".$pay_month."\r\nDue Amount: ".$due_amount."\r\nNext Payment Date: ".$nxt_pay_date."\r\nThanks, ROOT Science Care." ;
            }else{
                $sms = "Payment Received: ".$paid_amount."\r\nFor Month: ".$pay_month."\r\nThanks, ROOT Science Care." ;
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
                Payment <strong>Failed !!</strong>
            </div>
            ';
        } 
	}
?>
<!--Prevent form re-submission-->
<script>
    if( window.history.replaceState ){
    window.history.replaceState( null, null, window.location.href );
}
</script>
<!-- Auto Calc -->
<script>
    $(document).ready(function(){
        $('#rcvd_amount').val($('#rcvable_amount').val());
        $('#due_amount').val($('#rcvable_amount').val()-$('#rcvd_amount').val());
        $('#rcvd_amount').keyup(function(){
        $('#due_amount').val($('#rcvable_amount').val()-$('#rcvd_amount').val());
        })
        $('#discount').keyup(function(){
        $('#due_amount').val($('#rcvable_amount').val()-$('#discount').val()-$('#rcvd_amount').val());
        }) 
    })
</script>
<!--Date Picker-->
<script>
    $(document).ready(function(){
        $("#nxt_pay_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
        $("#pay_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>