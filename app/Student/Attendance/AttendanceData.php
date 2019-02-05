<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <div class="container col-md-10">
        <div class="card">
            <!-- Attendance -->
            <div class="card-header">Absent List</div>
            <div class="card-body">
				<?php
				    if(isset($_POST['show'])){
				    	$program = $_POST['program'];
                        $class = $_POST['class'];
                        $bDay = $_POST['bDay'];
                        $bTime = $_POST['bTime'];
				        $attendance_date = DateTime::createFromFormat('d/m/Y', $_POST['attendance_date'])->format('Y-m-d');

				        global $conn;
				        $sql = "SELECT students.first_name, students.last_name, students.cont_stdnt, students.cont_fath, students.cont_moth, students.program_roll FROM students INNER JOIN attendance ON students.student_id=attendance.student_id WHERE students.program='$program' AND students.class='$class' AND students.batch_day='$bDay' AND students.batch_time='$bTime' AND attendance.attendance_date='$attendance_date' AND attendance.status='Absent' ORDER BY students.program_roll";
				        
				        $result = $conn->query($sql);

					    if($result->num_rows > 0){
					        echo '
					            <div>Total '.$result->num_rows.' Absent Student(s)</div>
					            <br>
					            <table class="table table-hover">
					                <thead>
					                    <tr>
					                    	<th width="30%">Student Name</th>
					                        <th>Contact No.</th>
					                        <th>Program Roll</th>
					                        <th width="20%">Status</th>
					                    </tr>
					                </thead>
					            <tbody>
					        ';
					        while($row = $result->fetch_assoc()){
					            echo "
					                <tr>
					                	<td>$row[first_name] $row[last_name]</td>
					                    <td>$row[cont_stdnt] $row[cont_fath] $row[cont_moth]</td>
					                    <td>$row[program_roll]</td>
					                    <td>Absent</td>
					                </tr>
					            ";
					        }
					    }else{
					        echo '
					        <div class="alert alert-info alert-dismissible">
					            <button type="button" class="close" data-dismiss="alert">&times;</button>
					            <strong>No Absent Student Found !!</strong>
					        </div>
					        ';
					    }
				    }
				?>
            </div>
        </div>
    </div>
</div>