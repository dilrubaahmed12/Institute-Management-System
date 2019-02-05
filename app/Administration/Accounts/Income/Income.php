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
	function save_income(){
		global $conn;

		$income = validate($_POST['income']);
		$income_date = validate($_POST['income_date']);
		$ref = validate($_POST['ref']);

		$sql = "INSERT INTO accounts(income, income_date, income_ref) VALUES('$income', STR_TO_DATE('$income_date', '%d/%m/%Y'), '$ref')";

    	if($conn->query($sql) === TRUE){
            echo '
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Income Save <strong>Successful !!</strong>
            </div>
            ';
        }else{
            echo '
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Income Save <strong>Failed !!</strong>
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
                <div class="card-header">Income Info</div>
                <div class="card-body">
                	<?php
                		if(isset($_POST['save'])){
                			save_income();
                		} 
                	?>
                    <form method="POST">
                    	<div class="form-group">
                            <label for="income">Income Amount :</label>
                            <input type="text" class="form-control" id="income" placeholder="Income Amount" name="income" required>
                        </div>
                        <div class="form-group">
                            <label for="income_date">Date :</label>
                            <input type="text" class="form-control" id="income_date" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('d/m/Y');
                             ?>" name="income_date" required>
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
        $("#income_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>