<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <div class="container col-md-10">
        <div class="card">
            <!-- Exam Marks -->
            <div class="card-header"><?php echo $_POST['exam_name']; ?>:</div>
            <div class="card-body">
				<?php
				    if(isset($_POST['show'])){
				    	$program = $_POST['program'];
				    	$class = $_POST['class'];
				    	$sess = $_POST['session'];
				        $exam_name = $_POST['exam_name'];
				        $exam_date = DateTime::createFromFormat('d/m/Y', $_POST['exam_date'])->format('Y-m-d');
				        $subject = $_POST['subject'];
				        $total_marks = $_POST['total_marks'];

				        $_SESSION['program'] = $program;
				        $_SESSION['exam_name'] = $exam_name;
				        $_SESSION['exam_date'] = DateTime::createFromFormat('d/m/Y', $_POST['exam_date'])->format('d-m-Y');
				        $_SESSION['exam_sub'] = $subject;

				        global $conn;
				        $sql = "SELECT exams.student_name, exams.marks, students.program_roll FROM exams INNER JOIN students ON exams.student_id=students.student_id WHERE exams.exam_name='$exam_name' AND exams.exam_date='$exam_date' AND exams.subject='$subject' AND exams.total_marks='$total_marks' AND students.program='$program' AND students.class='$class' AND students.session='$sess' ORDER BY exams.marks='A', (0+exams.marks) DESC";
				        
				        $result = $conn->query($sql);
				        $_SESSION['output'] = '';
					    if($result->num_rows > 0){
					        echo '
					            <div>Total '.$result->num_rows.' Result(s)</div>
					            <br>
					            <table class="table table-hover">
					                <thead>
					                    <tr>
					                    	<th>Merit Position</th>
					                        <th>Student Name</th>
					                        <th>Program Roll</th>
					                        <th width="20%">Marks</th>
					                    </tr>
					                </thead>
					            <tbody>
					        ';
					        $merit = 1;
					        $real_merit = 0;
					        $last_mark = 0;
					        while($row = $result->fetch_assoc()){

					        	$real_merit++;
					        	if($row['marks'] == 'A'){
									$merit = 'N/A';
								}else if($row['marks'] < $last_mark){
					        		$merit = $real_merit;
					        	}
					        	
					            echo "
					                <tr>
					                	<td width='10%'>$merit</td>
					                    <td>$row[student_name]</td>
					                    <td>$row[program_roll]</td>
					                    <td>$row[marks]</td>
					                </tr>
					            ";
					            $_SESSION['output'] .= "
					                <tr>
					                	<td>$merit</td>
					                    <td>$row[student_name]</td>
					                    <td>$row[program_roll]</td>
					                    <td>$row[marks]</td>
					                </tr>
					            ";
					            $last_mark = $row['marks'];
					        }
					        echo "</tbody>
					            </table>
					            <form method='POST' action='CreatePDF.php'>
						            <div class='form-group text-center'>
						                <input type='submit' value='Create PDF File' name='create_pdf' class='btn btn-dark btn-lg'></input>
						            </div>
						        </form>
					            </div>
					            </div>";
					    }else{
					        echo '
					        <div class="alert alert-info alert-dismissible">
					            <button type="button" class="close" data-dismiss="alert">&times;</button>
					            <strong>No Result Found !!</strong>
					        </div>
					        ';
					    }
				    }
				?>
            </div>
        </div>
    </div>
</div>