<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<?php
    function validate($data){
        global $conn;

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = $conn->real_escape_string($data);

        return $data;
    }
?>

<?php
	function save_exp(){
		global $conn;
		$exp_typ = $_POST['exp_typ']." ".$_POST['exp_cat'];
		$exp = validate($_POST['exp']);
		$exp_date = validate($_POST['exp_date']);
		$ref = validate($_POST['ref']);

		$sql = "INSERT INTO accounts(expense_type, expense, expense_date, expense_ref) VALUES('$exp_typ', '$exp', STR_TO_DATE('$exp_date', '%d/%m/%Y'), '$ref')";

    	if($conn->query($sql) === TRUE){
            echo '
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Expense Save <strong>Successful !!</strong>
            </div>
            ';
        }else{
            echo '
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Expense Save <strong>Failed !!</strong>
            </div>
            ';
        }
	}
?>

<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Expense Info</div>
                <div class="card-body">
                	<?php
                		if(isset($_POST['save'])){
                			save_exp();
                		} 
                	?>
                    <form method="POST">
                    	<div class="form-group">
                            <label for="exp_typ">Expense Type :</label>
                            <select name="exp_typ" class="form-control" id="exp_typ">
                                <option></option>
                                <option>Advertisement</option>
                                <option>Stationary</option>
                                <option>House Rent</option>
                                <option>Staff Salary</option>
                                <option>Teacher Payment</option>
                                <option>Refreshment</option>
                                <option>Conveyance</option>
                                <option>Mobile Bill</option>
                                <option>SMS Bill</option>
                                <option>Internet Bill</option>
                                <option>Repair</option>
                                <option>Miscelleneous</option>
                            </select>
                        </div>
                        <div>
                            Expense Category :
                        </div>
                        <div>
                            <br>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="exp_cat" value="Common" checked>Common
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="exp_cat" value="Riddha">Riddha
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="exp_cat" value="Xihad">Xihad
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="exp_cat" value="Shawon">Shawon
                            </label>
                        </div>
                        <div>
                            <br>
                        </div>
                    	<div class="form-group">
                            <label for="exp">Expense Amount :</label>
                            <input type="text" class="form-control" id="exp" placeholder="Expense Amount" name="exp" required>
                        </div>
                        <div class="form-group">
                            <label for="exp_date">Date :</label>
                            <input type="text" class="form-control" id="exp_date" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('d/m/Y');
                             ?>" name="exp_date" required>
                        </div>
                        <div class="form-group">
                            <label for="ref">Reference :</label>
                            <input type="text" class="form-control" id="v" placeholder="Reference" name="ref" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Save" name="save" class="btn btn-info btn-lg btn-block"></input>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
<!--Prevent form re-submission-->
<script>
    if( window.history.replaceState ){
    window.history.replaceState( null, null, window.location.href );
}
</script>
<!--Date Picker -->
<script>
    $(document).ready(function(){
        $("#exp_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>