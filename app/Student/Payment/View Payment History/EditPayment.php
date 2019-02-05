<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<?php
	$sql = "SELECT * FROM payment WHERE payment_id='$_GET[edit_pay_id]' ";
	$result = $conn->query($sql);

    while($row = $result->fetch_assoc()){
    	$std_id = $row['student_id'];
        $pay_month = $row['payment_for_month'];
        $pay_year = $row['payment_year'];
        $pay_date = date_format(date_create($row['payment_date']), 'd/m/Y');
        $paid_amount = $row['paid_amount'];
        $discount = $row['discount_amount'];
        $due = $row['due_amount'];
        $next_payment_date = date_format(date_create($row['next_payment_date']), 'd/m/Y');
        $ref = $row['reference'];
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
         					if(isset($_POST['update'])){
         						update_payment();    
         					}
         				?>
         				<div class="form-group" <?php if($pay_month==NULL){ echo 'hidden'; } ?>>
                            <label for="month">Payment for Month :</label>
                            <select name="month" class="form-control" id="month">
                                <option <?php if($pay_month==NULL){ echo 'selected'; }else{echo 'hidden';} ?>></option>
                                <option <?php if($pay_month=='January'){ echo 'selected'; } ?>>January</option>
                                <option <?php if($pay_month=='February'){ echo 'selected'; } ?>>February</option>
                                <option <?php if($pay_month=='March'){ echo 'selected'; } ?>>March</option>
                                <option <?php if($pay_month=='April'){ echo 'selected'; } ?>>April</option>
                                <option <?php if($pay_month=='May'){ echo 'selected'; } ?>>May</option>
                                <option <?php if($pay_month=='June'){ echo 'selected'; } ?>>June</option>
                                <option <?php if($pay_month=='July'){ echo 'selected'; } ?>>July</option>
                                <option <?php if($pay_month=='August'){ echo 'selected'; } ?>>August</option>
                                <option <?php if($pay_month=='September'){ echo 'selected'; } ?>>September</option>
                                <option <?php if($pay_month=='October'){ echo 'selected'; } ?>>October</option>
                                <option <?php if($pay_month=='November'){ echo 'selected'; } ?>>November</option>
                                <option <?php if($pay_month=='December'){ echo 'selected'; } ?>>December</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="session">Session :</label>
                            <input type="text" class="form-control" id="session" value="<?php
                                echo $pay_year; ?>" name="session" required>
                        </div>
                        <div class="form-group">
                            <label for="rcvd_amount">Received Amount :</label>
                            <input type="text" class="form-control" id="rcvd_amount" name="rcvd_amount" value="<?php echo $paid_amount; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="discount">Discount :</label>
                            <input type="text" class="form-control" id="discount" name="discount" value="<?php echo $discount; ?>">
                        </div>
                        <div class="form-group">
                            <label for="due_amount">Due Amount :</label>
                            <input type="text" class="form-control" id="due_amount" name="due_amount" value="<?php echo $due; ?>">
                        </div>
                        <div class="form-group">
                            <label for="nxt_pay_date">Next Payment :</label>
                            <input type="text" class="form-control" id="nxt_pay_date" name="nxt_pay_date" value="<?php 
                            if($next_payment_date=='30/11/-0001'){
                            	echo 'dd/mm/yyyy';
                            }else{
                            	echo $next_payment_date;
                            }
                             ?>">
                        </div>
                        <div class="form-group">
                            <label for="ref">Reference :</label>
                            <input type="text" class="form-control" id="v" name="ref" value="<?php echo $ref; ?>">
                        </div>
                        <div class="form-group">
                            <label for="pay_date">Payment Date :</label>
                            <input type="text" class="form-control" id="pay_date" value="<?php echo $pay_date; ?>" name="pay_date">
                        </div>
                     	<div class="form-group">
                            <input type="submit" value="Update" name="update" class="btn btn-info btn-lg btn-block"></input>
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
		global $std_id;

		$sql = "SELECT * FROM students WHERE student_id='$std_id'";
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
	function update_payment(){
		global $conn;

        $pay_month = $_POST['month'];
        $pay_year = $_POST['session'];
        $paid_amount = filter_var($_POST['rcvd_amount'], FILTER_SANITIZE_NUMBER_INT);
        $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_INT);
        $due_amount = filter_var($_POST['due_amount'], FILTER_SANITIZE_NUMBER_INT);
        $nxt_pay_date = $_POST['nxt_pay_date'];
        $pay_date = $_POST['pay_date'];
        $ref = $_POST['ref'];
        
        $sql = "UPDATE payment SET payment_for_month='$pay_month', payment_year='$pay_year', payment_date=STR_TO_DATE('$pay_date', '%d/%m/%Y'), paid_amount='$paid_amount', discount_amount='$discount', due_amount='$due_amount', next_payment_date=STR_TO_DATE('$nxt_pay_date', '%d/%m/%Y'), reference='$ref' WHERE payment_id='$_GET[edit_pay_id]'";
        
        if($conn->query($sql) === TRUE){
            echo '
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Payment Update <strong>Successful !!</strong>
            </div>
            ';
        }else{
            echo '
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Payment Update <strong>Failed !!</strong>
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