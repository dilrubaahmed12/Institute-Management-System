<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<br>
<div class="container-fluid">
    <form method="POST">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">Cancel Admission</div>
                    <div class="card-body">
         				<?php
         					if(isset($_POST['cancel'])){
         						cancel_admission();
         					}
         				?>
                        <div class="form-group">
                            <label for="refund_amount">Refund Amount :</label>
                            <input type="text" class="form-control" id="refund_amount" name="refund_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="cancel_date">Cancel Date :</label>
                            <input type="text" class="form-control" id="cancel_date" placeholder="dd/mm/yyyy" name="cancel_date" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('d/m/Y');
                             ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="ref">Reference :</label>
                            <input type="text" class="form-control" id="v" placeholder="Cancel Reference" name="ref" required>
                        </div>
                     	<div class="form-group">
                            <input type="submit" value="Cancel Admission" name="cancel" class="btn btn-danger btn-lg btn-block"></input>
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
    function cancel_admission(){
        global $conn;
        $sql = "UPDATE students SET status='Inactive' WHERE student_id='$_GET[cancel_id]' ";
        if ($conn->query($sql) === TRUE) {
            echo '
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Cancel Student <strong>Successful !!</strong>
            </div>
                ';
        } else {
            echo '
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Cancel Student <strong>Failed !!</strong>
            </div>
                ';
        }
        $refund = filter_var($_POST['refund_amount'], FILTER_SANITIZE_NUMBER_INT);
        $ref = $_POST['ref'];
        $cancel_date = $_POST['cancel_date'];
        
        $sql = "INSERT INTO accounts (cancel_fee, cancel_date, cancel_ref) VALUES ('$refund', STR_TO_DATE('$cancel_date', '%d/%m/%Y'), '$ref')";
        $conn->query($sql);
    }
?>

<?php 
	function show_std_info(){
		global $conn;
		$sql = "SELECT * FROM students WHERE student_id='$_GET[cancel_id]'";
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

<!--Prevent form re-submission-->
<script>
    if( window.history.replaceState ){
    window.history.replaceState( null, null, window.location.href );
}
</script>
<!--Date Picker-->
<script>
    $(document).ready(function(){
        $("#cancel_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>
