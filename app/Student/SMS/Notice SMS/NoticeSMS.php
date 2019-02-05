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
                    <div class="card-header">Create Notice SMS</div>
                    <div class="card-body">
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
                            <label for="status">Status :</label>
                            <select name="status" class="form-control" id="status">
                                <option>Active</option>
                                <option>Inactive</option>
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
                            <input type="submit" value="Create Notice SMS" name="create" class="btn btn-dark btn-lg btn-block"></input>
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
        $status = $_POST['status'];
        $session = $_POST['session'];
        $sms = $_POST['sms'];

        global $conn;
        $sql = "SELECT * FROM students
        WHERE class='$class'
        AND program='$program'
        AND session='$session'
        AND status = '$status'
        ";
        
        $result = $conn->query($sql);
        
        if($result->num_rows > 0){
            $sms_text = $_SERVER["DOCUMENT_ROOT"].'/RMS/SMSTemplate/'.'NoticeSMS'.time().'.txt';
            $handle = fopen($sms_text, 'w');
            fwrite($handle, $sms);
            fclose($handle);

            $file_name = $_SERVER["DOCUMENT_ROOT"].'/RMS/RMSSMSFiles/'.'NoticeNumberList'.time().'.txt';
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
                        </tr>
                    </thead>
                <tbody>
            ';

            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$row[first_name] $row[last_name]</td>
                        <td>$row[cont_stdnt] $row[cont_fath] $row[cont_moth]</td>
                    </tr>
                ";
                $data = $row['cont_stdnt']."\r\n".$row['cont_fath']."\r\n".$row['cont_moth']."\r\n";
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