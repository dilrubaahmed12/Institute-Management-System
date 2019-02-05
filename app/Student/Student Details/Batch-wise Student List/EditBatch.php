<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<br>
<div class="container-fluid">
    <div class="container col-md-12">
        <div class="card">
            <!-- Student List -->
            <div class="card-header">Student List:</div>
            <div class="card-body">
                <?php 
                    if(isset($_POST['update'])){
                        $programs = $_POST['program'];
                        $classes = $_POST['class'];
                        $b_days = $_POST['bDay'];
                        $b_times = $_POST['bTime'];

                        for($i=0; $i<count($programs) ; $i++){
                            $stu_id = $_SESSION['std_id'][$i];
                            $program = $programs[$i];
                            $class = $classes[$i];
                            $b_day = $b_days[$i];
                            $b_time = $b_times[$i];
                            
                            $sql = "UPDATE students SET program = '$program', class = '$class', batch_day = '$b_day', batch_time = '$b_time' WHERE student_id='$stu_id' ";
                            if($conn->query($sql) === TRUE){
                                $msg = 'success';
                            }
                        }

                        unset($_SESSION['std_id']);

                        if($msg == 'success'){
                        echo '
                        <br>
                        <div class="container">
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                Batch Student Update <strong>Successful !!</strong>
                            </div>
                        </div>
                            ';
                        }
                    }
                ?>

                <?php
                    date_default_timezone_set('UTC');
                    $sess = date('Y');
                    $program = $_GET['program_name'];
                    $class = $_GET['class'];
                    $bDay = $_GET['b_day'];
                    $bTime = $_GET['b_time'];

                    $sql = "SELECT student_id, first_name, last_name, program_roll, program, class, batch_day, batch_time FROM students WHERE program='$program' AND class='$class' AND batch_day='$bDay' AND batch_time='$bTime' AND status='Active' AND session='$sess' ";
                
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
                                        <th>Program</th>
                                        <th>Class</th>
                                        <th>Batch Day</th>
                                        <th>Batch Time</th>
                                    </tr>
                                </thead>
                            <tbody>
                        ';
                        while($row = $result->fetch_assoc()){
                            $_SESSION['std_id'][] = $row['student_id'];
                            $old_program = $row['program'];
                            $old_class = $row['class'];
                            $old_bDay = $row['batch_day'];
                            $old_bTime = $row['batch_time'];

                            echo "
                                <form method='POST' class='form-inline'>
                                <tr>
                                    <td>$row[first_name] $row[last_name]</td>
                                    <td>$row[program_roll]</td>
                                    <td><select name='program[]' class='form-control' id='program'>";

                                $sql_prog = "SELECT program_name FROM programs";
                                $result_prog = $conn->query($sql_prog);

                                while($row_prog = $result_prog->fetch_assoc()):
                            echo "<option value='$row_prog[program_name]'"; if($row_prog['program_name'] == $old_program){ echo 'selected'; } echo '>'; echo $row_prog['program_name'];
                            echo "</option>";
                                endwhile;
                            echo "</select>";
                            echo "        
                                </td>
                                <td>
                                <div class='form-group'>
                                    <select name='class[]' class='form-control' id='status'>
                                        <option "; if($old_class == 'VIII'){ echo 'selected'; } echo ">VIII</option>
                                        <option "; if($old_class == 'IX'){ echo 'selected'; } echo ">IX</option>
                                        <option "; if($old_class == 'X'){ echo 'selected'; } echo ">X</option>
                                        <option "; if($old_class == 'XI'){ echo 'selected'; } echo ">XI</option>
                                        <option "; if($old_class == 'XII'){ echo 'selected'; } echo ">XII</option>
                                        <option "; if($old_class == 'Admission'){ echo 'selected'; } echo ">Admission</option>
                                    </select>
                                </div>
                                </td>
                                <td>
                                <div class='form-group'>
                                    <select name='bDay[]' class='form-control' id='bDay'>
                                        <option "; if($old_bDay == 'SAT-MON-WED-FRI'){ echo 'selected'; } echo ">SAT-MON-WED-FRI</option>
                                        <option "; if($old_bDay == 'SUN-TUE-THU-FRI'){ echo 'selected'; } echo ">SUN-TUE-THU-FRI</option>
                                        <option "; if($old_bDay == 'Everyday'){ echo 'selected'; } echo ">Everyday</option>
                                        <option "; if($old_bDay == 'Others'){ echo 'selected'; } echo ">Others</option>
                                    </select>
                                </div>
                                </td>
                                <td>
                                <div class='form-group'>
                                    <select name='bTime[]' class='form-control' id='bTime'>
                                        <option "; if($old_bTime == '8:00AM'){ echo 'selected'; } echo ">8:00AM</option>
                                        <option "; if($old_bTime == '9:00AM'){ echo 'selected'; } echo ">9:00AM</option>
                                        <option "; if($old_bTime == '10:00AM'){ echo 'selected'; } echo ">10:00AM</option>
                                        <option "; if($old_bTime == '11:00AM'){ echo 'selected'; } echo ">11:00AM</option>
                                        <option "; if($old_bTime == '12:00PM'){ echo 'selected'; } echo ">12:00PM</option>
                                        <option "; if($old_bTime == '12:30PM'){ echo 'selected'; } echo ">12:30PM</option>
                                        <option "; if($old_bTime == '1:30PM'){ echo 'selected'; } echo ">1:30PM</option>
                                        <option "; if($old_bTime == '2:30PM'){ echo 'selected'; } echo ">2:30PM</option>
                                        <option "; if($old_bTime == '3:30PM'){ echo 'selected'; } echo ">3:30PM</option>
                                        <option "; if($old_bTime == '4:30PM'){ echo 'selected'; } echo ">4:30PM</option>
                                        <option "; if($old_bTime == '5:30PM'){ echo 'selected'; } echo ">5:30PM</option>
                                        <option "; if($old_bTime == '6:30PM'){ echo 'selected'; } echo ">6:30PM</option>
                                        <option "; if($old_bTime == '7:30PM'){ echo 'selected'; } echo ">7:30PM</option>
                                    </select>
                                </div>
                                </td>
                                </tr>
                            ";
                        }
                        echo "</tbody>
                            </table>
                            <div class='form-group text-center'>
                                <input type='submit' value='Update' name='update' class='btn btn-success btn-block btn-lg'></input>
                            </div>
                            </div>
                            </div>
                            </form>";
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
