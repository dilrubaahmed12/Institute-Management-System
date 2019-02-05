<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <div class="container col-md-10">
        <div class="card">
            <!-- Exam Marks -->
            <div class="card-header">Exam Marks:</div>
            <div class="card-body">
                <?php
                    if(isset($_POST['showlist'])){
                        $program = $_POST['program'];
                        $class = $_POST['class'];
                        $bDay = $_POST['bDay'];
                        $bTime = $_POST['bTime'];
                        $session = $_POST['session'];

                        $_SESSION['exam_name'] = $_POST['exam_name'];
                        $_SESSION['exam_date'] = $_POST['exam_date'];
                        $_SESSION['subject'] = $_POST['subject']; 
                        $_SESSION['total_marks'] = $_POST['total_marks'];

                        $sql = "SELECT student_id, first_name, last_name, program_roll FROM students WHERE program='$program' AND class='$class' AND batch_day='$bDay' AND batch_time='$bTime' AND session='$session' AND status='Active' " ;
                        
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
                                            <th width="20%">Marks</th>
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
                                        <td><input type='text' class='form-control' name='marks[]'></td>
                                    </tr>
                                ";
                                $_SESSION['std_id'][]  = $row['student_id'];
                                if($row['first_name']==''){
                                    $_SESSION['std_name'][] = $row['last_name'];
                                }else{
                                    $_SESSION['std_name'][] = $row['first_name'].' '.$row['last_name'];
                                }
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
        $marks = $_POST['marks'];

        for($i=0; $i<count($marks) ; $i++){

            $stu_id = $_SESSION['std_id'][$i];
            $stu_name = $_SESSION['std_name'][$i];

            if($marks[$i] == 'a' || $marks[$i] == '' || !is_numeric($marks[$i])){
                $mark = 'A';
            }else{
                $mark = $marks[$i];
            }

            $sql = "INSERT INTO exams(student_id, student_name, exam_name, exam_date, subject, marks, total_marks) VALUES('$stu_id', '$stu_name', '$_SESSION[exam_name]', STR_TO_DATE('$_SESSION[exam_date]', '%d/%m/%Y'), '$_SESSION[subject]', '$mark', '$_SESSION[total_marks]') ";
            if($conn->query($sql) === TRUE){
                $msg = 'success';
            }
        }
        unset($_SESSION['exam_name']);
        unset($_SESSION['exam_date']);
        unset($_SESSION['subject']);
        unset($_SESSION['total_marks']);
        unset($_SESSION['std_id']);
        unset($_SESSION['std_name']);
        unset($_SESSION['program']);

        if($msg == 'success'){
        echo '
        <br>
        <div class="container">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Input Marks <strong>Successful !!</strong>
            </div>
        </div>
            ';
        }
    }
?>