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
                <div class="card-header">Transfer Bulk Students</div>
                <div class="card-body">
                    <?php
                        global $conn;  
                        if(isset($_POST['submit'])){
                            $pre_prog = $_POST['pre_prog'];
                            $pre_class = $_POST['pre_class'];
                            $pre_bDay = $_POST['pre_bDay'];
                            $pre_bTime = $_POST['pre_bTime'];
                            $pre_sess = $_POST['pre_sess'];
                            $new_prog = $_POST['new_prog'];
                            $new_class = $_POST['new_class'];
                            $new_bDay = $_POST['new_bDay'];
                            $new_bTime = $_POST['new_bTime'];
                            $new_sess = $_POST['new_sess'];

                            $sql = "SELECT program_code FROM programs WHERE class='$new_class' AND program_name='$new_prog' AND session='$new_sess' ";
                            $result = $conn->query($sql);
                            if($result->num_rows==1){
                                while($row = $result->fetch_assoc()){
                                    $new_code = $row['program_code'];
                                }
                            }

                            $sql = "UPDATE students SET program='$new_prog', class='$new_class', session='$new_sess', batch_day = '$new_bDay', batch_time = '$new_bTime'
                                    WHERE program='$pre_prog' AND class='$pre_class' AND session='$pre_sess' AND batch_day = '$pre_bDay' AND batch_time = '$pre_bTime' ";

                            if($conn->query($sql) === TRUE){
                                $sql = "SELECT student_id, program_roll FROM students WHERE program='$new_prog' AND class='$new_class' AND session='$new_sess' AND batch_day = '$new_bDay' AND batch_time = '$new_bTime' ";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()){
                                    $roll = $new_code.substr($row['program_roll'], -3); 
                                    $sql = "UPDATE students
                                            SET program_roll = '$roll'
                                            WHERE student_id = '$row[student_id]' ";
                                    $conn->query($sql);
                                }
                                if($conn->query($sql) === TRUE){
                                    echo '
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        Bulk Student Transfer <strong>Successful !!</strong>
                                    </div>
                                    ';
                                }
                            }else{
                                echo '
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    Bulk Student Transfer <strong>Failed !!</strong>
                                </div>
                                ';
                            }
                        }
                    ?>
                	<form method="POST">
	                	<div class="form-group">
                            <label for="pre_prog">Previous Program :</label>
                            <select name="pre_prog" class="form-control" id="pre_prog">
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
                            <label for="pre_class">Previous Class :</label>
                            <select name="pre_class" class="form-control" id="pre_class">
                                <option>Select</option>
                                <option>VIII</option>
                                <option>IX</option>
                                <option>X</option>
                                <option>XI</option>
                                <option>XII</option>
                                <option>Admission</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pre_batchday">Previous Batch Day :</label>
                            <select name="pre_bDay" class="form-control" id="pre_batchday">
                                <option>Select</option>
                                <option>SAT-MON-WED-FRI</option>
                                <option>SUN-TUE-THU-FRI</option>
                                <option>Everyday</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pre_batchtime">Previous Batch Time :</label>
                            <select name="pre_bTime" class="form-control" id="pre_batchtime">
                                <option>Select</option>
                                <option>8:00AM</option>
                                <option>9:00AM</option>
                                <option>10:00AM</option>
                                <option>11:00AM</option>
                                <option>12:00PM</option>
                                <option>12:30PM</option>
                                <option>1:30PM</option>
                                <option>2:30PM</option>
                                <option>3:30PM</option>
                                <option>4:30PM</option>
                                <option>5:30PM</option>
                                <option>6:30PM</option>
                                <option>7:30PM</option>
                            </select>
                        </div>
	                    <div class="form-group">
                            <label for="pre_sess">Previous Session :</label>
                            <input type="text" class="form-control" id="pre_sess" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('Y')-1; ?>" name="pre_sess" required>
                        </div>
                        <div class="form-group">
                            <label for="new_prog">New Program :</label>
                            <select name="new_prog" class="form-control" id="new_prog">
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
                            <label for="new_class">New Class :</label>
                            <select name="new_class" class="form-control" id="new_class">
                                <option>Select</option>
                                <option>VIII</option>
                                <option>IX</option>
                                <option>X</option>
                                <option>XI</option>
                                <option>XII</option>
                                <option>Admission</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="new_batchday">New Batch Day :</label>
                            <select name="new_bDay" class="form-control" id="new_batchday">
                                <option>Select</option>
                                <option>SAT-MON-WED-FRI</option>
                                <option>SUN-TUE-THU-FRI</option>
                                <option>Everyday</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="new_batchtime">New Batch Time :</label>
                            <select name="new_bTime" class="form-control" id="new_batchtime">
                                <option>Select</option>
                                <option>8:00AM</option>
                                <option>9:00AM</option>
                                <option>10:00AM</option>
                                <option>11:00AM</option>
                                <option>12:00PM</option>
                                <option>12:30PM</option>
                                <option>1:30PM</option>
                                <option>2:30PM</option>
                                <option>3:30PM</option>
                                <option>4:30PM</option>
                                <option>5:30PM</option>
                                <option>6:30PM</option>
                                <option>7:30PM</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="new_sess">New Session :</label>
                            <input type="text" class="form-control" id="new_sess" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('Y'); ?>" name="new_sess" required>
                        </div>
	                    <div class="form-group">
                            <input type="submit" value="Transfer" class="btn btn-danger btn-lg btn-block" name="submit"></input>
                        </div>
	                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>