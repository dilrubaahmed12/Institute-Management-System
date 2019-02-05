<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<br>
<div class="container-fluid">
    <form method="POST">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Search Categories</div>
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
                                <option></option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="batchday">Batch Day :</label>
                            <select name="bDay" class="form-control" id="batchday">
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
                            <input type="submit" value="Search" name="search" class="btn btn-info btn-lg btn-block"></input>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
            	<div class="card">
            		<?php
            			if(isset($_POST['search'])){
            				search_result();
            			}
            		?>
            	</div>
            </div>
        </div>
    </form>
</div>

<?php
    function search_result(){

        $class = $_POST['class'];
        $program = $_POST['program'];
        if(isset($_POST['subjects'])){
            $subjects = implode(" ", $_POST['subjects']);
        }else{
            $subjects = '';
        }
        $status = $_POST['status'];
        $bDay = $_POST['bDay'];
        $bTime = $_POST['bTime'];
        $session = $_POST['session'];

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
            $sql .= "subjects='$subjects' AND ";
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
            echo '
            <div class="card-header">Search Result: Total '.$result->num_rows.' result(s) found</div>
                <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Program</th>
                            <th>Subjects</th>
                            <th>Batch Day</th>
                            <th>Batch Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                <tbody>
            ';
            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$row[first_name] $row[last_name]</td>
                        <td>$row[class]</td>
                        <td>$row[program]</td>
                        <td>$row[subjects]</td>
                        <td>$row[batch_day]</td>
                        <td>$row[batch_time]</td>
                        <td>$row[status]</td>
                    </tr>
                ";
            } 
            echo "</tbody>
                </table>
                </div>";
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