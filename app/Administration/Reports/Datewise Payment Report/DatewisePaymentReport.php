<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <form method="POST" action="ViewDatewisePaymentReport.php">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
            <div class="container col-md-5">
                <div class="card">
                    <div class="card-header">Datewise Payment Search</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="program">Program :</label>
                            <select name="program" class="form-control" id="program">
                                <option>Select</option>
                                <?php 
                                    $sql = "SELECT program_name FROM programs";
                                    $result = $conn->query($sql);
                                ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['program_name']; ?>"><?php echo $row['program_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="from_date">From Date :</label>
                            <input type="text" class="form-control" id="from_date" placeholder="dd/mm/yyyy" name="from_date" required>
                        </div>
                        <div class="form-group">
                            <label for="to_date">To Date :</label>
                            <input type="text" class="form-control" id="to_date" placeholder="dd/mm/yyyy" name="to_date" required>
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