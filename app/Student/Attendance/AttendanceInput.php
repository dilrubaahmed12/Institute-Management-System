<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <div class="container col-md-10">
        <div class="card">
            <!-- Attendance List -->
            <div class="card-header">Attendance List:</div>
            <div class="card-body">
                <?php
                    if(isset($_POST['showlist'])){
                        $program = $_POST['program'];
                        $class = $_POST['class'];
                        $bDay = $_POST['bDay'];
                        $bTime = $_POST['bTime'];

                        $_SESSION['attendance_date'] = $_POST['attendance_date'];

                        $sql = "SELECT student_id, first_name, last_name, program_roll FROM students WHERE program='$program' AND class='$class' AND batch_day='$bDay' AND batch_time='$bTime' AND status='Active' " ;
                        
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            echo '
                                <div>Total '.$result->num_rows.' Student(s)</div>
                                <br>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Program Roll</th>
                                            <th>Select Absent</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <form method='POST'>
                                    <tr>
                                        <td>$row[first_name] $row[last_name]</td>
                                        <td>$row[program_roll]</td>
                                        <td><input name='att[]' value='$row[student_id]' type='checkbox' class='form-check-input'></td>
                                    </tr>
                                ";
                            }
                            echo "</tbody>
                                </table>
                                <div class='form-group text-center'>
                                    <input type='submit' value='Submit' name='submit' class='btn btn-success btn-lg'></input>
                                </div>
                                </div>
                                </div>
                                </form>";
                        }else{
                            echo '
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>No Students Found !!</strong>
                            </div>
                            ';
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php 
    if(isset($_POST['submit'])){
        $att = $_POST['att'];

        for($i=0; $i<count($att) ; $i++){

            $stu_id = $att[$i];

            $sql = "INSERT INTO attendance(student_id, attendance_date, status) VALUES('$stu_id', STR_TO_DATE('$_SESSION[attendance_date]', '%d/%m/%Y'), 'Absent') ";
            if($conn->query($sql) === TRUE){
                $msg = 'success';
            }
        }

        unset($_SESSION['attendance_date']);

        if($msg == 'success'){
        echo '
        <br>
        <div class="container">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Input Attendance <strong>Successful !!</strong>
            </div>
        </div>
            ';
        }
    }
?>