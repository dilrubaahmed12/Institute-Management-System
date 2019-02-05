<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<?php 

    $sql = "SELECT * FROM students WHERE student_id = '$_GET[edit_id]' ;";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()){ 
        $fname = $row['first_name'];
        $lname = $row['last_name'];
        $cntnostd = substr($row['cont_stdnt'], 2);
        $cntnofat = substr($row['cont_fath'], 2);
        $cntnomot = substr($row['cont_moth'], 2);
        $version = $row['version'];
        $inst = $row['institute'];
        $dob = date_format(date_create($row['dob']), 'd/m/Y');
        $gender = $row['gender'];
        $class = $row['class'];
        $sess = $row['session'];
        $subjects = explode(" ",$row['subjects']);
        $program = $row['program'];
        $bDay = $row['batch_day'];
        $bTime = $row['batch_time'];
        $doa = date_format(date_create($row['date_of_admission']), 'd/m/Y');
        $status = $row['status'];

        $_SESSION['old_roll'] = $row['program_roll'];
        $_SESSION['old_class'] = $row['class'];
        $_SESSION['old_session'] = $row['session'];
        $_SESSION['old_program'] = $row['program'];
    }
?>

<br>
<div class="container-fluid">
    <form method="POST">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
            <div class="col-md-5">
                <div class="card">
                    <!-- Student Information -->
                    <div class="card-header">Student Information</div>
                    <div class="card-body">
                        <?php  
                            if(isset($_POST['submit'])){
                                if($_POST['version']=='' || $_POST['gender']=='' || $_POST['class']=='' || $_POST['program']=='' || $_POST['bDay']=='' || $_POST['bTime']=='' || $_POST['session']=='' || $_POST['subjects']=='' || $_POST['dob']=='' || $_POST['doa']==''){
                                        echo '
                                            <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                Fill in <strong>All Fields !!</strong>
                                            </div>
                                        ';
                                }else {
                                    update_student();
                                }
                             }
                        ?>
                        <div class="form-group">
                            <label for="fname">First Name :</label>
                            <input type="text" class="form-control" id="fname" value="<?php echo $fname; ?>" name="fname">
                        </div>
                        <div class="form-group">
                            <label for="lname">Last Name :</label>
                            <input type="text" class="form-control" id="lname" value="<?php echo $lname; ?>" name="lname" required>
                        </div>
                        <div class="form-group">
                            <label for="cntnostd">Contact No. (Student) :</label>
                            <input type="text" minlength="11" maxlength="11" class="form-control" id="cntnostd" value="<?php echo $cntnostd; ?>" name="cntnostd" required>
                        </div>
                         <div class="form-group">
                            <label for="cntnofat">Contact No. (Father) :</label>
                            <input type="text" minlength="11" maxlength="11" class="form-control" id="cntnofat" value="<?php echo $cntnofat; ?>" name="cntnofat">
                        </div>
                        <div class="form-group">
                            <label for="cntnomot">Contact No. (Mother) :</label>
                            <input type="text" minlength="11" maxlength="11" class="form-control" id="cntnomot" value="<?php echo $cntnomot; ?>" name="cntnomot">
                        </div>
                        <div class="form-group">
                            <label for="version">Version of Study :</label>
                            <select name="version" class="form-control" id="version">
                                <option <?php if($version == 'Bangla'){ echo 'selected'; } ?>>Bangla</option>
                                <option <?php if($version == 'English'){ echo 'selected'; } ?>>English</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender :</label>
                            <select name="gender" class="form-control" id="gender">
                                <option <?php if($gender == 'Male'){ echo 'selected'; } ?>>Male</option>
                                <option <?php if($gender == 'Female'){ echo 'selected'; } ?>>Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inst">Institution :</label>
                            <input type="text" class="form-control" id="inst" value="<?php echo $inst; ?>" name="inst" required>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth :</label>
                            <input type="text" class="form-control" id="dob" value="<?php echo $dob; ?>" name="dob" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <!-- Office Information -->
                    <div class="card-header">Office Information</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="class">Class :</label>
                            <select name="class" class="form-control" id="class">
                                <option <?php if($class == 'VIII'){ echo 'selected'; } ?>>VIII</option>
                                <option <?php if($class == 'IX'){ echo 'selected'; } ?>>IX</option>
                                <option <?php if($class == 'X'){ echo 'selected'; } ?>>X</option>
                                <option <?php if($class == 'XI'){ echo 'selected'; } ?>>XI</option>
                                <option <?php if($class == 'XII'){ echo 'selected'; } ?>>XII</option>
                                <option <?php if($class == 'Admission'){ echo 'selected'; } ?>>Admission</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="session">Session :</label>
                            <input type="text" class="form-control" id="session" value="<?php echo $sess; ?>" name="session">
                        </div>
                        <div class="form-group">
                            <label for="program">Program :</label>
                            <select name="program" class="form-control" id="program">
                                <?php 
                                    $sql_prog = "SELECT program_name FROM programs";
                                    $result_prog = $conn->query($sql_prog);
                                ?>
                                <?php while($row_prog = $result_prog->fetch_assoc()): ?>
                                    <option value="<?php echo $row_prog['program_name']; ?>" <?php if($row_prog['program_name'] == $program){ echo 'selected'; } ?>><?php echo $row_prog['program_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <?php
                            $subs = " "; 
                            $sql = "SELECT subjects FROM programs";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
                                $subs .= " ";
                                $subs .= $row['subjects'];
                            }
                            $subs = array_unique(explode(" ",substr($subs, 2)));
                        ?>
                        <div class="form-group">
                            <label>Subjects :</label>
                            <?php 
                                foreach($subs as $sub){
                                    echo '
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input name="subjects[]" value="'.$sub.'" type="checkbox" class="form-check-input" '; if(in_array($sub, $subjects)){ echo 'checked';} echo '>'.$sub.'
                                            </label>
                                        </div>
                                    ';
                                }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="batchtime">Batch Day :</label>
                            <select name="bDay" class="form-control" id="batchtime">
                                <option <?php if($bDay == 'SAT-MON-WED-FRI'){ echo 'selected'; } ?>>SAT-MON-WED-FRI</option>
                                <option <?php if($bDay == 'SUN-TUE-THU-FRI'){ echo 'selected'; } ?>>SUN-TUE-THU-FRI</option>
                                <option <?php if($bDay == 'Everyday'){ echo 'selected'; } ?>>Everyday</option>
                                <option <?php if($bDay == 'Others'){ echo 'selected'; } ?>>Others</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="batchtime">Batch Time :</label>
                            <select name="bTime" class="form-control" id="batchtime">
                                <option <?php if($bTime == '8:00AM'){ echo 'selected'; } ?>>8:00AM</option>
                                <option <?php if($bTime == '9:00AM'){ echo 'selected'; } ?>>9:00AM</option>
                                <option <?php if($bTime == '10:00AM'){ echo 'selected'; } ?>>10:00AM</option>
                                <option <?php if($bTime == '11:00AM'){ echo 'selected'; } ?>>11:00AM</option>
                                <option <?php if($bTime == '12:00PM'){ echo 'selected'; } ?>>12:00PM</option>
                                <option <?php if($bTime == '12:30PM'){ echo 'selected'; } ?>>12:30PM</option>
                                <option <?php if($bTime == '1:30PM'){ echo 'selected'; } ?>>1:30PM</option>
                                <option <?php if($bTime == '2:30PM'){ echo 'selected'; } ?>>2:30PM</option>
                                <option <?php if($bTime == '3:30PM'){ echo 'selected'; } ?>>3:30PM</option>
                                <option <?php if($bTime == '4:30PM'){ echo 'selected'; } ?>>4:30PM</option>
                                <option <?php if($bTime == '5:30PM'){ echo 'selected'; } ?>>5:30PM</option>
                                <option <?php if($bTime == '6:30PM'){ echo 'selected'; } ?>>6:30PM</option>
                                <option <?php if($bTime == '7:30PM'){ echo 'selected'; } ?>>7:30PM</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="doa">Date of Admission :</label>
                            <input type="text" class="form-control" id="doa" value="<?php echo $doa; ?>" name="doa" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status :</label>
                            <select name="status" class="form-control" id="status">
                                <option <?php if($status == 'Active'){ echo 'selected'; } ?>>Active</option>
                                <option <?php if($status == 'Inactive'){ echo 'selected'; } ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Update" name="submit" class="btn btn-info btn-lg btn-block"></input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

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
    function update_student(){
        global $conn;

        $fname = ucwords(strtolower(validate($_POST['fname'])));
        $lname = ucwords(strtolower(validate($_POST['lname'])));

        $cntnostd = validate(filter_var($_POST['cntnostd'], FILTER_SANITIZE_NUMBER_INT));
        if($cntnostd != ''){
            $cntnostd = str_pad($cntnostd, 13, '88', STR_PAD_LEFT);
        }
        $cntnofat = validate(filter_var($_POST['cntnofat'], FILTER_SANITIZE_NUMBER_INT));
        if($cntnofat != ''){
            $cntnofat = str_pad($cntnofat, 13, '88', STR_PAD_LEFT);
        }
        $cntnomot = validate(filter_var($_POST['cntnomot'], FILTER_SANITIZE_NUMBER_INT));
        if($cntnomot != ''){
            $cntnomot = str_pad($cntnomot, 13, '88', STR_PAD_LEFT);
        }

        $version = $_POST['version'];
        $inst = strtoupper(validate($_POST['inst']));
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $class = $_POST['class'];
        $sess = validate(filter_var($_POST['session'], FILTER_SANITIZE_NUMBER_INT));
        $subjects = implode(" ", $_POST["subjects"]);
        $program = $_POST['program'];
        $bDay = $_POST['bDay'];
        $bTime = $_POST['bTime'];
        $doa = validate($_POST['doa']);
        $status = $_POST['status'];

        if($_SESSION['old_class'] != $class || $_SESSION['old_session'] != $sess || $_SESSION['old_program'] != $program){
            $sql = "SELECT program_code FROM programs WHERE class='$class' AND program_name='$program' AND session='$sess' ";
            $result = $conn->query($sql);
            if($result->num_rows==1){
                while($row = $result->fetch_assoc()){
                    $roll = $row['program_code'];
                }
            }
            $sql = "SELECT program_roll FROM students WHERE class='$class' AND program='$program' AND session='$sess' ORDER BY program_roll DESC LIMIT 1";
            $result = $conn->query($sql);
            if($result->num_rows==0){
                $roll .= "001";
            }else{
                $row = $result->fetch_assoc();
                $roll = str_pad($row['program_roll'] + 1, 3, '0', STR_PAD_LEFT);
            }
        }else{
            $roll = $_SESSION['old_roll'];
        }
        
        $sql = "UPDATE students SET
        first_name = '$fname',
        last_name = '$lname',
        cont_stdnt = '$cntnostd',
        cont_fath = '$cntnofat',
        cont_moth = '$cntnomot',
        version = '$version',
        institute = '$inst',
        dob = STR_TO_DATE('$dob', '%d/%m/%Y'),
        gender = '$gender',
        class= '$class',
        session = '$sess',
        program_roll = '$roll',
        subjects = '$subjects',
        program = '$program',
        batch_day = '$bDay',
        batch_time = '$bTime',
        date_of_admission = STR_TO_DATE('$doa', '%d/%m/%Y'),
        status = '$status' WHERE
        student_id = '$_GET[edit_id]'";
        
        if($conn->query($sql) === TRUE){
            echo '
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Student Update <strong>Successful !!</strong>
            </div>
            ';
            unset($_SESSION['old_roll']);
            unset($_SESSION['old_class']);
            unset($_SESSION['old_session']);
            unset($_SESSION['old_program']);
        }else{
            echo '
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Student Update <strong>Failed !!</strong>
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
        $("#dob").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
        $("#doa").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>