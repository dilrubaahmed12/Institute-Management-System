<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <form method="POST" action="ViewTodayAccountReport.php">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
            <div class="container col-md-5">
                <div class="card">
                    <div class="card-header">Account Report Search</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="date">Date :</label>
                            <input type="text" class="form-control" id="date" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('d/m/Y');
                             ?>" name="date" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Search" name="search" class="btn btn-info btn-lg btn-block"></input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
<!--Date Picker -->
<script>
    $(document).ready(function(){
        $("#date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>