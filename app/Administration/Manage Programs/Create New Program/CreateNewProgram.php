<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <form method="post">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
            <div class="col-md-5">
                <div class="card">
                    <!-- Create New Program -->
                    <div class="card-header">Create New Program</div>
                    <?php
                        if(isset($_POST['Create'])){
                            create_program();
                        }
                    ?>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="ProgramName">Program Name :</label>
                            <input type="text" class="form-control" id="ProgramName" placeholder="Program Name" name="ProgramName" required>
                        </div>
                        <div class="form-group">
                            <label for="ProgramType">Program Type :</label>
                            <select name="ProgramType" class="form-control" id="ProgramType">
                                <option>Select</option>
                                <option>Academic</option>
                                <option>Model Test</option>
                                <option>Admission</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ProgramCode">Program Code :</label>
                            <input type="text" class="form-control" id="ProgramCode" placeholder="Program Code" name="ProgramCode" required>
                        </div>
                        <div class="form-group">
                            <label for="class">Class :</label>
                            <select name="class" class="form-control" id="class">
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
                            <label for="subjects">Subjects :</label>
                            <input type="text" class="form-control" id="subjects" placeholder="Subjects" name="subjects" required>
                        </div>
                        <div class="form-group">
                            <label for="PaymentType">Payment Type :</label>
                            <select name="PaymentType" class="form-control" id="PaymentType">
                                <option>Select</option>
                                <option>Monthly</option>
                                <option>Course</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="CourseFee">Course Fee (Tk) :</label>
                            <input type="text" class="form-control" id="CourseFee" placeholder="Course Fee (Tk)" name="CourseFee" required>
                        </div>
                        <div class="form-group">
                            <label for="feepersub">Fee per Subject (Tk) :</label>
                            <input type="text" class="form-control" id="feepersub" placeholder="Fee per Subject (Tk)" name="feepersub" required>
                        </div>
                        <div class="form-group">
                            <label for="admfee">Admission Fee (Tk) :</label>
                            <input type="text" class="form-control" id="admfee" placeholder="Admission Fee (Tk)" name="admfee" required>
                        </div>
                        <div class="form-group">
                            <label for="extrafee">Extra Fee (Tk) :</label>
                            <input type="text" class="form-control" id="extrafee" placeholder="Extra Fee (Tk)" name="extrafee" required>
                        </div>
                        <div class="form-group">
                            <label for="session">Session :</label>
                            <input type="text" class="form-control" id="session" placeholder="Session" name="session" required>
                        </div>
                        <div class="form-group">
                            <label for="BatchDay">Batch Day :</label>
                            <input type="text" class="form-control" id="BatchDay" placeholder="Batch Day" name="BatchDay" required>
                        </div>
                        <div class="form-group">
                            <label for="BatchTime">Batch Time :</label>
                            <input type="text" class="form-control" id="BatchTime" placeholder="Batch Time" name="BatchTime" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Create New Program" name="Create" class="btn btn-info btn-lg btn-block"></input>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Running Program Block -->
            <div class="col-md-5">
                <div class="card">
                    <!-- Show Running Programs -->
                    <div class="card-header">Running Programs</div>
                    <div class="card-body">
                        <?php
                            show_programs();
                        ?>
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
    function show_programs(){
        global $conn;
        $sql = "SELECT program_name, class FROM programs";
        $result = $conn->query($sql);
        
        if($result->num_rows >0){
            echo '
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Program Name</th>
                        <th>Class</th>
                    </tr>
                </thead>
                <tbody>
            ';
            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$row[program_name]</td>
                        <td>$row[class]</td>
                    </tr>
                ";
            } 
            echo "</tbody>
                </table>";
        }else{
            echo "There is No Program Running Now";
        }
    }
?>

<?php
    function create_program(){
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
        
        $sql = "INSERT INTO programs(program_name, program_type, program_code, class, subjects, payment_type, course_fee, fee_per_subject, admission_fee, extra_fee, session, batch_day, batch_time) VALUES('$progName', '$progType', '$progCode', '$class', '$subs', '$paymentType', '$courseFee', '$feepersub', '$admfee', '$extrafee', '$sess', '$bDay', '$bTime')";
        
        if($conn->query($sql) === TRUE){
            echo '
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Program Creation <strong>Successful !!</strong>
            </div>
            ';
        }else{
            echo '
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Program Creation <strong>Failed !!</strong>
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