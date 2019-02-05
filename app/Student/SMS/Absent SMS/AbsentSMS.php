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
                    <div class="card-header">Create Absent SMS</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="att_date">Attendance Date :</label>
                            <input type="text" class="form-control" id="att_date" name="attendance_date" placeholder="dd/mm/yyyy">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Create Absent SMS" name="create" class="btn btn-dark btn-lg btn-block"></input>
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

        $attendance_date = DateTime::createFromFormat('d/m/Y', $_POST['attendance_date'])->format('Y-m-d');

        global $conn;
        $sql = "SELECT students.first_name, students.last_name, students.cont_stdnt, students.cont_fath, students.cont_moth, attendance.attendance_date FROM students INNER JOIN attendance ON students.student_id=attendance.student_id WHERE attendance.attendance_date='$attendance_date' AND attendance.status='Absent' ";
        
        $result = $conn->query($sql);
        
        if($result->num_rows > 0){

            $file_name = $_SERVER["DOCUMENT_ROOT"].'/RMS/RMSSMSFiles/AbsentList'.time().'.csv';
            $handle = fopen($file_name, 'w');

            echo '
            <div class="card-header">Total '.$result->num_rows.' Absent Student(s) found</div>
                <div class="card-body">
                <div class="text-center"><a href="SendSMS.php?nolist='.$file_name.'" class="btn btn-lg btn-success">Send SMS</a></div><br>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Create SMS File for Absent Students <strong>Successful !!</strong>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Contact No.</th>
                        </tr>
                    </thead>
                <tbody>
            ';

            $count = 0;

            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$row[first_name] $row[last_name]</td>
                        <td>$row[cont_stdnt] $row[cont_fath] $row[cont_moth]</td>
                    </tr>
                ";
                $date = DateTime::createFromFormat('Y-m-d', $attendance_date)->format('d/m/Y');

                $sms = $row['last_name']." was absent on ".$date.".\r\nThanks, ROOT Science Care.";

                if($row['cont_stdnt']){
                    fputcsv($handle, array($row['cont_stdnt'], $sms));
                }
                if($row['cont_fath']){
                    fputcsv($handle, array($row['cont_fath'], $sms));
                }
                if($row['cont_moth']){
                    fputcsv($handle, array($row['cont_moth'], $sms));
                }
                $count++;
            }

            $sms = "Date: ".$date."\r\nTotal Absent: ".$count."\r\nThanks, ROOT Science Care.";

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
                <strong>No Absent Student Found !!</strong>
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
        $("#att_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>