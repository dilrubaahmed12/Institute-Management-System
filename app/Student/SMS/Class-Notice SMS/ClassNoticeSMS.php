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
                    <div class="card-header">Create Class Schedule SMS</div>
                    <div class="card-body">
                    	<div class="form-group">
                            <label for="class">Class :</label>
                            <select name="class" class="form-control" id="class">
                                <option></option>
                                <option>VIII</option>
                                <option>IX</option>
                                <option>X</option>
                                <option>XI</option>
                                <option>XII</option>
                                <option>Admission</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="program">Program :</label>
                            <select name="program" class="form-control" id="program">
                                <option></option>
                                <?php 
                                    $sql = "SELECT program_name FROM programs";
                                    $result = $conn->query($sql);
                                ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['program_name']; ?>"><?php echo $row['program_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <?php
                            $subs = " "; 
                            $sql = "SELECT subjects FROM programs";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
                                $subs .= " ";
                                $subs .= $row['subjects'];
                            }
                            $subs = array_unique(explode(" ",substr($subs, 2)));
                        ?>
                        <div class="form-group">
                            <label>Subjects :</label>
                            <?php 
                                foreach($subs as $sub){
                                    echo '
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input name="subjects[]" value="'.$sub.'" type="checkbox" class="form-check-input">'.$sub.'
                                            </label>
                                        </div>
                                    ';
                                }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="status">Status :</label>
                            <select name="status" class="form-control" id="status">
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="batchtime">Batch Day :</label>
                            <select name="bDay" class="form-control" id="batchtime">
                                <option></option>
                                <option>SAT-MON-WED-FRI</option>
                                <option>SUN-TUE-THU-FRI</option>
                                <option>Everyday</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="batchtime">Batch Time :</label>
                            <select name="bTime" class="form-control" id="batchtime">
                                <option></option>
                                <option>8:00AM</option>
                                <option>9:00AM</option>
                                <option>10:00AM</option>
                                <option>11:00AM</option>
                                <option>12:00PM</option>
                                <option>12:30PM</option>
                                <option>1:30PM</option>
                                <option>2:30PM</option>
                                <option>3:30PM</option>
                                <option>4:30PM</option>
                                <option>5:30PM</option>
                                <option>6:30PM</option>
                                <option>7:30PM</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="session">Session :</label>
                            <input type="text" class="form-control" id="session" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('Y'); ?>" name="session">
                        </div>
                        <div class="form-group">
                            <label for="sms">SMS :</label>
                            <textarea class="form-control" rows="20" id="sms" name="sms"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Create Class Schedule SMS" name="create" class="btn btn-dark btn-lg btn-block"></input>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="card">
            		<?php
            			if(isset($_POST['create'])){
            				create_number_list();
            			}
            		?>
            	</div>
            </div>
        </div>
    </form>
</div>

<?php
    function create_number_list(){

        $class = $_POST['class'];
        $program = $_POST['program'];
        if(isset($_POST['subjects'])){
            $subjects = $_POST['subjects'];
        }else{
            $subjects = '';
        }
        $status = $_POST['status'];
        $bDay = $_POST['bDay'];
        $bTime = $_POST['bTime'];
        $session = $_POST['session'];
        $sms = $_POST['sms'];

        global $conn;
        $sql = "SELECT * FROM students
        WHERE ";
        if($class != ''){
            $sql .= "class='$class' AND ";
        }
        if($program != ''){
            $sql .= "program='$program' AND ";
        }
        if($subjects != ''){
            $sql .= "(";
            foreach($subjects as $value){
                $sql .= "subjects LIKE '%".$value."%' OR ";
            }
            $sql = substr($sql, 0, -3);
            $sql .= ") AND ";
        }
		if($status != ''){
            $sql .= "status='$status' AND ";
        }
        if($bDay != ''){
            $sql .= "batch_day='$bDay' AND ";
        }
        if($bTime != ''){
            $sql .= "batch_time='$bTime' AND ";
        }
        if($session != ''){
           $sql .= "session='$session' "; 
        }

        $result = $conn->query($sql);
        
        if($result->num_rows > 0){
            $sms_text = $_SERVER["DOCUMENT_ROOT"].'/RMS/SMSTemplate/'.'ClassScheduleSMS'.time().'.txt';
            $handle = fopen($sms_text, 'w');
            fwrite($handle, $sms);
            fclose($handle);

            $file_name = $_SERVER["DOCUMENT_ROOT"].'/RMS/RMSSMSFiles/'.'ClassScheduleNumberList'.time().'.txt';
            $handle = fopen($file_name, 'w');

            echo '
            <div class="card-header">Search Result: Total '.$result->num_rows.' result(s) found</div>
                <div class="card-body">
                <div class="text-center"><a href="SendSMS.php?text='.$sms_text.'&nolist='.$file_name.'" class="btn btn-lg btn-success">Send SMS</a></div><br>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Create SMS File <strong>Successful !!</strong>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Mobile No.</th>
                            <th>Subjects</th>
                        </tr>
                    </thead>
                <tbody>
            ';

            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$row[first_name] $row[last_name]</td>
                        <td>$row[cont_stdnt] $row[cont_fath] $row[cont_moth]</td>
                        <td>$row[subjects]</td>
                    </tr>
                ";
                $data = $row['cont_stdnt']."\r\n".$row['cont_fath']."\r\n".$row['cont_moth']."\r\n" ;
                fwrite($handle, $data);
            }
            $data = '8801793690456'."\r\n".'8801776445409'."\r\n".'8801789114390'."\r\n".'8801711965150'."\r\n";
            fwrite($handle, $data);

            echo "</tbody>
                </table>
                </div>";
            fclose($handle);

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