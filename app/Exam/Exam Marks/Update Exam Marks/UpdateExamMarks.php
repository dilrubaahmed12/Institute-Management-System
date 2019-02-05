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
				    if(isset($_POST['show'])){

				    	$program = $_POST['program'];
				    	$class = $_POST['class'];
				    	$bday = $_POST['bDay'];
				    	$btime = $_POST['bTime'];
				    	$sess = $_POST['session'];
				        $exam_name = $_POST['exam_name'];
				        $exam_date = DateTime::createFromFormat('d/m/Y', $_POST['exam_date'])->format('Y-m-d');
				        $subject = $_POST['subject'];
				        $total_marks = $_POST['total_marks'];

				        global $conn;
				        $sql = "SELECT exams.exam_id, exams.student_name, exams.marks, students.program_roll FROM exams INNER JOIN students ON exams.student_id=students.student_id WHERE exams.exam_name='$exam_name' AND exams.exam_date='$exam_date' AND exams.subject='$subject' AND exams.total_marks='$total_marks' AND students.program='$program' AND students.class='$class' AND students.batch_day='$bday' AND students.batch_time='$btime' AND students.session='$sess' ";
				        
				        $result = $conn->query($sql);

					    if($result->num_rows > 0){
					        echo '
					            <div>Total '.$result->num_rows.' Result(s)</div>
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
					                    <td>$row[student_name]</td>
					                    <td>$row[program_roll]</td>
					                    <td><input type='text' class='form-control' name='marks[]' value='$row[marks]'></td>
					                </tr>
					            ";
					            $_SESSION['examid'][] = $row['exam_id'];
					        }
					        echo "</tbody>
					            </table>
					            <div class='form-group text-center'>
					                <input type='submit' value='Update Marks' name='update' class='btn btn-success btn-lg'></input>
					            </div>
					            </div>
					            </div>
					            </form>";
					    }else{
					        echo '
					        <div class="alert alert-info alert-dismissible">
					            <button type="button" class="close" data-dismiss="alert">&times;</button>
					            <strong>No Exam Marks Found !!</strong>
					        </div>
					        ';
					    }
				    }
				?>
            </div>
        </div>
    </div>
</div>

<?php 
    if(isset($_POST['update'])){
        $marks = $_POST['marks'];

        for($i=0; $i<count($marks) ; $i++){
            if($marks[$i] == 'a' || $marks[$i] == '' || !is_numeric($marks[$i])){
                $mark = 'A';
            }else{
                $mark = $marks[$i];
            }
            
            $exam_id = $_SESSION['examid'][$i];

            $sql = "UPDATE exams SET marks='$mark' WHERE exam_id='$exam_id' ";
            if($conn->query($sql) === TRUE){
                $msg = 'success';
            }
        }

        unset($_SESSION['examid']);

        if($msg == 'success'){
        echo '
        <br>
        <div class="container">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Update Marks <strong>Successful !!</strong>
            </div>
        </div>
            ';
        }
    }
?>