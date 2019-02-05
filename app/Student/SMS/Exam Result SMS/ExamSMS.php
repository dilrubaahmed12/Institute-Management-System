<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";

    delete_sms_files();
?>

<br>
<div class="container-fluid">
    <form method="POST">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Create Exam Result SMS</div>
                    <div class="card-body">
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
                            <label for="session">Session :</label>
                            <input type="text" class="form-control" id="session" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('Y'); ?>" name="session" required>
                        </div>
                        <div class="form-group">
                            <label for="exam_name">Exam Name :</label>
                            <select name="exam_name" class="form-control" id="exam_name" required>
                                <option>Select</option>
                                <?php
                                    $sql = "SELECT DISTINCT exam_name FROM exams";
                                    $result = $conn->query($sql);
                                ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['exam_name']; ?>"><?php echo $row['exam_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exam_date">Exam Date :</label>
                            <input type="text" class="form-control" id="exam_date" name="exam_date"  placeholder="dd/mm/yyyy">
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <select name="subject" class="form-control" id="subject" required>
                                <option>Select</option>
                                <option>Physics</option>
                                <option>Chemistry</option>
                                <option>Math</option>
                                <option>Biology</option>
                                <option>G. Math</option>
                                <option>H. Math</option>
                                <option>Bangla 1st</option>
                                <option>Bangla 2nd</option>
                                <option>English 1st</option>
                                <option>English 2nd</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="total_marks">Total Marks :</label>
                            <input type="text" class="form-control" id="total_marks" name="total_marks"  placeholder="Total Marks" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Create Exam Result SMS" name="create" class="btn btn-dark btn-lg btn-block"></input>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="card">
            		<?php
            			if(isset($_POST['create'])){
            				create_sms_file();
            			}
            		?>
            	</div>
            </div>
        </div>
    </form>
</div>

<?php
    function create_sms_file(){

        $program = $_POST['program'];
        $class = $_POST['class'];
        $sess = $_POST['session'];
        $exam_name = $_POST['exam_name'];
        $exam_date = DateTime::createFromFormat('d/m/Y', $_POST['exam_date'])->format('Y-m-d');
        $subject = $_POST['subject'];
        $total_marks = $_POST['total_marks'];

        global $conn;
        $sql = "SELECT students.cont_stdnt, students.cont_fath, students.cont_moth, exams.student_name, exams.exam_name, exams.exam_date, exams.subject, exams.marks, exams.total_marks FROM students INNER JOIN exams ON students.student_id=exams.student_id WHERE exams.exam_name='$exam_name' AND exams.exam_date='$exam_date' AND exams.subject='$subject' AND exams.total_marks='$total_marks' AND students.program='$program' AND students.class='$class' AND students.session='$sess' ORDER BY exams.marks='A', (0+exams.marks) DESC ";
        
        $result = $conn->query($sql);
        
        if($result->num_rows > 0){

            $file_name = $_SERVER["DOCUMENT_ROOT"].'/RMS/RMSSMSFiles/'.'ExamResult'.time().'.csv';
            $handle = fopen($file_name, 'w');

            echo '
            <div class="card-header">Total '.$result->num_rows.' result(s) found</div>
                <div class="card-body">
                <div class="text-center"><a href="SendSMS.php?nolist='.$file_name.'" class="btn btn-lg btn-success">Send SMS</a></div><br>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Create SMS File for Result <strong>Successful !!</strong>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Merit Position</th>
                            <th>Student Name</th>
                            <th>Mobile No.</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                <tbody>
            ';

            $merit = 1;
            $real_merit = 0;
            $last_mark = 0;

            $highest_marks ="SELECT MAX(0+exams.marks) AS max_marks FROM exams INNER JOIN students ON students.student_id=exams.student_id WHERE exams.exam_name='$exam_name' AND exams.exam_date='$exam_date' AND exams.subject='$subject' AND exams.total_marks='$total_marks' AND students.program='$program' AND students.class='$class' AND students.session='$sess' AND marks!='A' ";
            $hm = $conn->query($highest_marks);
            $highest = $hm->fetch_array();

            $count = 0;
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
                        <td>$row[cont_stdnt] $row[cont_fath] $row[cont_moth]</td>
                        <td>$row[marks]</td>
                    </tr>
                ";
                $last_mark = $row['marks'];

                $total = $row['total_marks'];
                $date = DateTime::createFromFormat('Y-m-d', $row['exam_date'])->format('d/m/Y');

                if($row['marks'] == 'A'){
                    $sms = "Exam Result of ".$row['student_name']."\r\nExam: ".$row['subject']." (".$row['exam_name'].")\r\nDate: ".$date."\r\nObtained Marks: "."Absent"."/".$total."\r\nMerit Position: ".$merit."/".$result->num_rows."\r\nHighest Marks: ".$highest['max_marks']."/".$total."\r\nThanks, ROOT Science Care.";
                }else{
                    $sms = "Exam Result of ".$row['student_name']."\r\nExam: ".$row['subject']." (".$row['exam_name'].")\r\nDate: ".$date."\r\nObtained Marks: ".$row['marks']."/".$total."\r\nMerit Position: ".$merit."/".$result->num_rows."\r\nHighest Marks: ".$highest['max_marks']."/".$total."\r\nThanks, ROOT Science Care.";
                    $count++;
                }

                if($row['cont_stdnt']){
                    fputcsv($handle, array($row['cont_stdnt'], $sms));
                }
                if($row['cont_fath']){
                    fputcsv($handle, array($row['cont_fath'], $sms));
                }
                if($row['cont_moth']){
                    fputcsv($handle, array($row['cont_moth'], $sms));
                }
            }
            $sms = "Exam Result:\r\nExam: ".$subject." (".$exam_name.")\r\nDate: ".$date."\r\nTotal Marks: ".$total."\r\nHighest Marks: ".$highest['max_marks']."\r\nTotal Attended: ".$count."\r\nThanks, ROOT Science Care.";

            fputcsv($handle, array("8801793690456", $sms));
            fputcsv($handle, array("8801776445409", $sms));
            fputcsv($handle, array("8801789114390", $sms));
            fputcsv($handle, array("8801711965150", $sms));

            echo "</tbody>
                </table>
                </div>";
            fclose($handle);

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

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php 
    function delete_sms_files(){
        $files = glob($_SERVER["DOCUMENT_ROOT"].'/RMS/SMSTemplate/*');
        foreach($files as $file){
          if(is_file($file))
            unlink($file);
        }
        $files = glob($_SERVER["DOCUMENT_ROOT"].'/RMS/RMSSMSFiles/*');
        foreach($files as $file){
          if(is_file($file))
            unlink($file);
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
        $("#exam_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>