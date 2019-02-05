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
                    <!-- Student Information -->
                    <div class="card-header">Student Information</div>
                    <div class="card-body">
                        <?php  
                            if(isset($_POST['submit'])){
                                if($_POST['version']=='Select' || $_POST['gender']=='Select' || $_POST['class']=='Select' || $_POST['program']=='Select' || $_POST['bDay']=='Select' || $_POST['bTime']=='Select' || $_POST['session']=='' || $_POST['subjects']=='' || $_POST['dob']=='' || $_POST['doa']==''){
                                        echo '
                                            <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                Fill in <strong>All Fields !!</strong>
                                            </div>
                                        ';
                                }else {
                                    new_admission();
                                }
                            }
                        ?>
                        <div class="form-group">
                            <label for="fname">First Name :</label>
                            <input type="text" class="form-control" id="fname" placeholder="First Name" name="fname" value="<?php
                            if(isset($_POST['fname'])){
                                echo htmlspecialchars($_POST['fname']); 
                            }
                            ?>">
                        </div>
                        <div class="form-group">
                            <label for="lname">Last Name :</label>
                            <input type="text" class="form-control" id="lname" placeholder="Last Name" name="lname" value="<?php
                            if(isset($_POST['lname'])){
                                echo htmlspecialchars($_POST['lname']); 
                            }
                            ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="cntnostd">Contact No. (Student) :</label>
                            <input type="text" minlength="11" maxlength="11" class="form-control" id="cntnostd" placeholder="Contact No. (Student)" name="cntnostd" value="<?php
                            if(isset($_POST['cntnostd'])){
                                echo htmlspecialchars($_POST['cntnostd']); 
                            }
                            ?>" required>
                        </div>
                         <div class="form-group">
                            <label for="cntnofat">Contact No. (Father) :</label>
                            <input type="text" minlength="11" maxlength="11" class="form-control" id="cntnofat" placeholder="Contact No. (Father)" name="cntnofat" value="<?php
                            if(isset($_POST['cntnofat'])){
                                echo htmlspecialchars($_POST['cntnofat']); 
                            }
                            ?>">
                        </div>
                        <div class="form-group">
                            <label for="cntnomot">Contact No. (Mother) :</label>
                            <input type="text" minlength="11" maxlength="11" class="form-control" id="cntnomot" placeholder="Contact No. (Mother)" name="cntnomot" value="<?php
                            if(isset($_POST['cntnomot'])){
                                echo htmlspecialchars($_POST['cntnomot']); 
                            }
                            ?>">
                        </div>
                        <div class="form-group">
                            <label for="version">Version of Study :</label>
                            <select name="version" class="form-control" id="version">
                                <option>Select</option>
                                <option <?php if(isset($_POST['version']) && $_POST['version'] == 'Bangla'){ echo 'selected'; } ?>>Bangla</option>
                                <option <?php if(isset($_POST['version']) && $_POST['version'] == 'English'){ echo 'selected'; } ?>>English</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender :</label>
                            <select name="gender" class="form-control" id="gender">
                                <option>Select</option>
                                <option <?php if(isset($_POST['gender']) && $_POST['gender'] == 'Male'){ echo 'selected'; } ?>>Male</option>
                                <option <?php if(isset($_POST['gender']) && $_POST['gender'] == 'Female'){ echo 'selected'; } ?>>Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inst">Institution :</label>
                            <input type="text" class="form-control" id="inst" placeholder="Institution" name="inst" value="<?php
                            if(isset($_POST['inst'])){
                                echo htmlspecialchars($_POST['inst']); 
                            }
                            ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth :</label>
                            <input type="text" class="form-control" id="dob" placeholder="dd/mm/yyyy" name="dob" value="<?php
                            if(isset($_POST['dob'])){
                                echo htmlspecialchars($_POST['dob']); 
                            }
                            ?>" required>
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
                                <option>Select</option>
                                <option <?php if(isset($_POST['class']) && $_POST['class'] == 'VIII'){ echo 'selected'; } ?>>VIII</option>
                                <option <?php if(isset($_POST['class']) && $_POST['class'] == 'IX'){ echo 'selected'; } ?>>IX</option>
                                <option <?php if(isset($_POST['class']) && $_POST['class'] == 'X'){ echo 'selected'; } ?>>X</option>
                                <option <?php if(isset($_POST['class']) && $_POST['class'] == 'XI'){ echo 'selected'; } ?>>XI</option>
                                <option <?php if(isset($_POST['class']) && $_POST['class'] == 'XII'){ echo 'selected'; } ?>>XII</option>
                                <option <?php if(isset($_POST['class']) && $_POST['class'] == 'Admission'){ echo 'selected'; } ?>>Admission</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="session">Session :</label>
                            <input type="text" class="form-control" id="session" value="<?php 
                                if(isset($_POST['session'])){
                                echo htmlspecialchars($_POST['session']); 
                                }else{
                                    date_default_timezone_set('UTC');
                                    echo date('Y'); } ?>" name="session" required>
                        </div>
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
                                                <input name="subjects[]" value="'.$sub.'" type="checkbox" class="form-check-input">'.$sub.'
                                            </label>
                                        </div>
                                    ';
                                }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="batchday">Batch Day :</label>
                            <select name="bDay" class="form-control" id="batchday">
                                <option>Select</option>
                                <option <?php if(isset($_POST['bDay']) && $_POST['bDay'] == 'SAT-MON-WED-FRI'){ echo 'selected'; } ?>>SAT-MON-WED-FRI</option>
                                <option <?php if(isset($_POST['bDay']) && $_POST['bDay'] == 'SUN-TUE-THU-FRI'){ echo 'selected'; } ?>>SUN-TUE-THU-FRI</option>
                                <option <?php if(isset($_POST['bDay']) && $_POST['bDay'] == 'Everyday'){ echo 'selected'; } ?>>Everyday</option>
                                <option <?php if(isset($_POST['bDay']) && $_POST['bDay'] == 'Others'){ echo 'selected'; } ?>>Others</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="batchtime">Batch Time :</label>
                            <select name="bTime" class="form-control" id="batchtime">
                                <option>Select</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '8:00AM'){ echo 'selected'; } ?>>8:00AM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '9:00AM'){ echo 'selected'; } ?>>9:00AM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '10:00AM'){ echo 'selected'; } ?>>10:00AM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '11:00AM'){ echo 'selected'; } ?>>11:00AM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '12:00PM'){ echo 'selected'; } ?>>12:00PM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '12:30PM'){ echo 'selected'; } ?>>12:30PM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '1:30PM'){ echo 'selected'; } ?>>1:30PM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '2:30PM'){ echo 'selected'; } ?>>2:30PM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '3:30PM'){ echo 'selected'; } ?>>3:30PM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '4:30PM'){ echo 'selected'; } ?>>4:30PM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '5:30PM'){ echo 'selected'; } ?>>5:30PM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '6:30PM'){ echo 'selected'; } ?>>6:30PM</option>
                                <option <?php if(isset($_POST['bTime']) && $_POST['bTime'] == '7:30PM'){ echo 'selected'; } ?>>7:30PM</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="doa">Date of Admission :</label>
                            <input type="text" class="form-control" id="doa" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('d/m/Y');
                             ?>" name="doa" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Save" name="submit" class="btn btn-success btn-lg btn-block"></input>
                        </div>
                        <div class="form-group">
                            <input type="reset" value="Reset" class="btn btn-danger btn-lg btn-block"></input>
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
    function new_admission(){
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
        $subjects = implode(" ",$_POST['subjects']);
        $program = $_POST['program'];
        $bDay = $_POST['bDay'];
        $bTime = $_POST['bTime'];
        $doa = validate($_POST['doa']);
        $status = 'Active';

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
        
        if(check_admission($fname,$lname,$cntnostd,$cntnofat,$cntnomot)){
            $sql = "INSERT INTO students(first_name, last_name, cont_stdnt, cont_fath, cont_moth, version, institute, dob, gender, class, session, program_roll, subjects, program, batch_day, batch_time, date_of_admission, status) VALUES('$fname', '$lname', '$cntnostd', '$cntnofat', '$cntnomot', '$version', '$inst', STR_TO_DATE('$dob', '%d/%m/%Y') , '$gender', '$class', '$sess', '$roll', '$subjects', '$program', '$bDay', '$bTime', STR_TO_DATE('$doa', '%d/%m/%Y'), '$status')";
        
            if($conn->query($sql) === TRUE){
                echo '
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Student Admission <strong>Successful !!</strong>
                </div>
                ';
                if($fname==''){
                    $sms = "Name: ".$lname."\r\nProgram: ".$program."\r\nSubjects: ".$subjects."\r\nRoll: ".$roll."\r\nBatch Day: ".$bDay."\r\nWelcome to ROOT Science Care.";
                }else{
                    $sms = "Name: ".$fname." ".$lname."\r\nProgram: ".$program."\r\nSubjects: ".$subjects."\r\nRoll: ".$roll."\r\nBatch Day: ".$bDay."\r\nWelcome to ROOT Science Care.";
                }
                $sms_text = $_SERVER["DOCUMENT_ROOT"].'/RMS/SMSTemplate/'.'NewAdmissionSMS.txt';
                $handle = fopen($sms_text, 'w');
                fwrite($handle, $sms);
                fclose($handle);

                $file_name = $_SERVER["DOCUMENT_ROOT"].'/RMS/RMSSMSFiles/'.'NewAdmission.txt';
                $handle = fopen($file_name, 'w');
                $data = $cntnostd."\r\n".$cntnofat."\r\n".$cntnomot."\r\n" ;
                fwrite($handle, $data);
                fclose($handle);

                $default = ini_get('max_execution_time');
                set_time_limit(1000);
                
                if(stristr($_SERVER['HTTP_USER_AGENT'], 'x64')){
                    $cmd = "cd C:/Program Files (x86)/SMSCaster/ && smscaster.exe -Compose ".$file_name." ".$sms_text." -Long -Start";
                }else{
                    $cmd = "cd C:/Program Files/SMSCaster/ && smscaster.exe -Compose ".$file_name." ".$sms_text." -Long -Start";
                }
                exec($cmd);

                set_time_limit($default);
            }else{
                echo '
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Student Admission <strong>Failed !!</strong>
                </div>
                ';
            }
        }else{
            echo '
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Student <strong>Already Admitted !!</strong>
                </div>
                ';
        }
    }
?>

<?php  
    function check_admission($f_name, $l_name, $cont_std, $cont_fat, $cont_mot){
        global $conn;
        $sql = "SELECT * FROM students WHERE
        (first_name='$f_name'
        AND last_name='$l_name')
        AND (cont_stdnt='$cont_std'
        OR cont_fath='$cont_fat'
        OR cont_moth='$cont_mot') LIMIT 1";

        $result = $conn->query($sql);

        if($result->fetch_array() != false){
            return false;
        }else{
            return true;
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