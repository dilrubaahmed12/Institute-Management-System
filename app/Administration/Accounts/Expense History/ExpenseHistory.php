<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
        <div class="container col-md-4">
            <div class="card">
                <div class="card-header">Expense Search</div>
                <div class="card-body">
                	<form method="POST" action="ViewExpenseHistory.php">
	                	<div class="form-group">
	                        <label for="from_date">From Date :</label>
	                        <input type="text" class="form-control" id="from_date" placeholder="dd/mm/yyyy" name="from_date" required>
	                    </div>
	                    <div class="form-group">
	                        <label for="to_date">To Date :</label>
	                        <input type="text" class="form-control" id="to_date" placeholder="dd/mm/yyyy" name="to_date" required>
	                    </div>
	                    <div class="form-group">
                            <input type="submit" value="Search" class="btn btn-info btn-lg btn-block"></input>
                        </div>
	                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
<!--Date Picker -->
<script>
    $(document).ready(function(){
        $("#from_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
        $("#to_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>