<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <form method="POST" action="ViewMonthwisePaymentReport.php">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
            <div class="container col-md-5">
                <div class="card">
                    <div class="card-header">Monthwise Payment Search</div>
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
                            <label for="month">Payment List Month :</label>
                            <select name="month" class="form-control" id="month">
                                <option>Select</option>
                                <option>January</option>
                                <option>February</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="session">Session :</label>
                            <input type="text" class="form-control" id="session" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('Y'); ?>" name="session" required>
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