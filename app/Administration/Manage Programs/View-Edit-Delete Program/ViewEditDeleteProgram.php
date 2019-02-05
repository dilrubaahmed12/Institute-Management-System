<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
        <div class="col-md-10">
            <div class="card">
               <?php
                    if(isset($_GET['edit_id'])){
                        show_edit_form();
                    }else{
                        show_programs();
                    }
                    if(isset($_POST['Update'])){
                        edit_program();
                    } 
                ?>
                </div>
            </div>
        </div>
    </div>
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
    function show_edit_form(){
        global $conn;

        $sql = "SELECT * FROM programs WHERE program_id = '$_GET[edit_id]'";
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()){
            $progName = $row['program_name'];
            $progType = $row['program_type'];
            $progCode = $row['program_code'];
            $class = $row['class']; 
            $subs = $row['subjects'];
            $paymentType = $row['payment_type'];
            $courseFee = $row['course_fee'];
            $feepersub = $row['fee_per_subject'];
            $admfee = $row['admission_fee'];
            $extrafee = $row['extra_fee'];
            $sess = $row['session']; 
            $bDay = $row['batch_day']; 
            $bTime = $row['batch_time']; 
        }
        echo '
        <div class="card-header">Edit Program</div>
        <form method="post">
            <div class="card-body">
                <div class="form-group">
                    <label for="ProgramName">Program Name :</label>
                    <input type="text" class="form-control" id="ProgramName" placeholder="Program Name" name="ProgramName" value="'.$progName.'" required>
                </div>
                <div class="form-group">
                    <label for="ProgramType">Program Type :</label>
                    <select name="ProgramType" class="form-control" id="ProgramType">
                        <option '; if($progType == 'Academic'){ echo 'selected'; } echo '>Academic</option>
                        <option '; if($progType == 'Model Test'){ echo 'selected'; } echo '>Model Test</option>
                        <option '; if($progType == 'Admission'){ echo 'selected'; } echo '>Admission</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ProgramCode">Program Code :</label>
                    <input type="text" class="form-control" id="ProgramCode" placeholder="Program Code" name="ProgramCode" value="'.$progCode.'" required>
                </div>
                <div class="form-group">
                    <label for="class">Class :</label>
                    <select name="class" class="form-control" id="class">
                        <option '; if($class == 'VIII'){ echo 'selected'; } echo '>VIII</option>
                        <option '; if($class == 'IX'){ echo 'selected'; } echo '>IX</option>
                        <option '; if($class == 'X'){ echo 'selected'; } echo '>X</option>
                        <option '; if($class == 'XI'){ echo 'selected'; } echo '>XI</option>
                        <option '; if($class == 'XII'){ echo 'selected'; } echo '>XII</option>
                        <option '; if($class == 'Admission'){ echo 'selected'; } echo '>Admission</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="subjects">Subjects :</label>
                    <input type="text" class="form-control" id="subjects" placeholder="Subjects" name="subjects" value="'.$subs.'" required>
                </div>
                <div class="form-group">
                    <label for="PaymentType">Payment Type :</label>
                    <select name="PaymentType" class="form-control" id="PaymentType">
                        <option '; if($paymentType == 'Monthly'){ echo 'selected'; } echo '>Monthly</option>
                        <option '; if($paymentType == 'Course'){ echo 'selected'; } echo '>Course</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="CourseFee">Course Fee (Tk) :</label>
                    <input type="text" class="form-control" id="CourseFee" placeholder="Course Fee (Tk)" name="CourseFee" value="'.$courseFee.'" required>
                </div>
                <div class="form-group">
                    <label for="feepersub">Fee per Subject (Tk) :</label>
                    <input type="text" class="form-control" id="feepersub" placeholder="Fee per Subject (Tk)" name="feepersub" value="'.$feepersub.'" required>
                </div>
                <div class="form-group">
                    <label for="admfee">Admission Fee (Tk) :</label>
                    <input type="text" class="form-control" id="admfee" placeholder="Admission Fee (Tk)" name="admfee" value="'.$admfee.'">
                </div>
                <div class="form-group">
                    <label for="extrafee">Extra Fee (Tk) :</label>
                    <input type="text" class="form-control" id="extrafee" placeholder="Extra Fee (Tk)" name="extrafee" value="'.$extrafee.'">
                </div>
                <div class="form-group">
                    <label for="session">Session :</label>
                    <input type="text" class="form-control" id="session" placeholder="Session" name="session" value="'.$sess.'">
                </div>
                <div class="form-group">
                    <label for="BatchDay">Batch Day :</label>
                    <input type="text" class="form-control" id="BatchDay" placeholder="Batch Day" name="BatchDay" value="'.$bDay.'" required="1">
                </div>
                <div class="form-group">
                    <label for="BatchTime">Batch Time :</label>
                    <input type="text" class="form-control" id="BatchTime" placeholder="Batch Time" name="BatchTime" value="'.$bTime.'" required="1">
                </div>
                <div class="form-group">
                    <input type="submit" value="Update" name="Update" class="btn btn-info btn-lg btn-block"></input>
                </div>
            </div>
        </form>
        ';
    }
?>

<?php
    function show_programs(){
        global $conn;
        $sql = "SELECT * FROM programs";
        $result = $conn->query($sql);
        
        if($result->num_rows > 0){
            echo '
            <div class="card-header">Running Programs</div>
                <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Program Name</th>
                            <th>Program Code</th>
                            <th>Class</th>
                            <th>Subjects</th>
                            <th>Batch Day</th>
                            <th>Batch Time</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                <tbody>
            ';
            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$row[program_name]</td>
                        <td>$row[program_code]</td>
                        <td>$row[class]</td>
                        <td>$row[subjects]</td>
                        <td>$row[batch_day]</td>
                        <td>$row[batch_time]</td>
                        <td><a href='ViewEditDeleteProgram.php?edit_id=$row[program_id]' class='btn btn-info'>Edit</a></td>
                        <td><a href='deleteProgram.php?del_id=$row[program_id]' class='btn btn-danger'>Delete</a></td>
                    </tr>
                ";
            } 
            echo "</tbody>
                </table>";
        }else{
            echo '
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                There is <strong>no program running</strong> now !!
            </div>
            ';
        }
    }
?>

<?php
    function edit_program(){
        global $conn;
        
        $progName = validate($_POST['ProgramName']);
        $progType = validate($_POST['ProgramType']);
        $progCode = validate(filter_var($_POST['ProgramCode'], FILTER_SANITIZE_NUMBER_INT));
        $class = validate($_POST['class']);
        $subs = validate(implode(" ", explode(",", $_POST['subjects'])));
        $paymentType = validate($_POST['PaymentType']);
        $courseFee = validate($_POST['CourseFee']);
        $feepersub = validate($_POST['feepersub']);
        $admfee = validate(filter_var($_POST['admfee'], FILTER_SANITIZE_NUMBER_INT));
        $extrafee = validate(filter_var($_POST['extrafee'], FILTER_SANITIZE_NUMBER_INT));
        $sess = validate($_POST['session']);
        $bDay = validate($_POST['BatchDay']);
        $bTime = validate($_POST['BatchTime']);
        
        $sql = "UPDATE programs SET program_name='$progName', program_type='$progType', program_code='$progCode', class='$class', subjects='$subs', payment_type='$paymentType', course_fee='$courseFee', fee_per_subject='$feepersub', admission_fee='$admfee', extra_fee='$extrafee', session='$sess', batch_day='$bDay', batch_time='$bTime' WHERE program_id = $_GET[edit_id]";
        
        if($conn->query($sql) === TRUE){
            echo 
            '<script>
                window.location = "editSuccessful.php"
            </script>';
        }
    }
?>

<!--Prevent form re-submission-->
<script>
    if( window.history.replaceState ){
    window.history.replaceState( null, null, window.location.href );
}
</script>
