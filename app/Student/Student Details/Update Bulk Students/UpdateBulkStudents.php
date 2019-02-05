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
                    if(isset($_POST['showlist'])){
                        $program = $_POST['program'];
                        $class = $_POST['class'];
                        $bDay = $_POST['bDay'];
                        $bTime = $_POST['bTime'];
                        $sess = $_POST['session'];

                        $sql = "SELECT subjects FROM programs WHERE program_name='$program' ";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $show_subs = explode(" ", $row['subjects']); 

                        $sql = "SELECT student_id, first_name, last_name, program_roll, subjects, date_of_admission, status FROM students WHERE program='$program' AND class='$class' AND batch_day='$bDay' AND batch_time='$bTime' AND session = $sess ORDER BY status" ;
                        
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
                                            <th width="12%">Date of Admission</th>
                                            <th width="40%">Subjects</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $c = 0;
                            while($row = $result->fetch_assoc()){
                                $_SESSION['std_id'][] = $row['student_id'];
                                $doa = date_format(date_create($row['date_of_admission']), 'd/m/Y');
                                $subjects = explode(" ",$row['subjects']);
                                $status = $row['status'];

                                echo "
                                    <form method='POST' class='form-inline'>
                                    <tr>
                                        <td>$row[first_name] $row[last_name]</td>
                                        <td>$row[program_roll]</td>
                                        <td><input type='text' class='form-control doa' value='"; echo $doa."'"; echo " name='doa[]' required></td>
                                        <td>";
                                foreach($show_subs as $show_sub){
                                    echo "
                                    <div class='form-check-inline'>
                                        <label class='form-check-label'>
                                        <input name='subjects[$c][]' value='".$show_sub."' type='checkbox' class='form-check-input' "; if(in_array($show_sub, $subjects)){ echo 'checked';} echo ">".$show_sub."
                                        </label>
                                    </div>
                                    ";
                                }
                                echo "        
                                    </td>
                                    <td>
                                    <div class='form-group'>
                                        <select name='status[]' class='form-control' id='status'>
                                            <option "; if($status == 'Active'){ echo 'selected'; } echo ">Active</option>
                                            <option "; if($status == 'Inactive'){ echo 'selected'; } echo ">Inactive</option>
                                        </select>
                                    </div>
                                    </td>
                                </tr>
                                ";
                                $c++;
                            }
                            echo "</tbody>
                                </table>
                                <div class='form-group text-center'>
                                    <input type='submit' value='Update' name='update' class='btn btn-success btn-block btn-lg'></input>
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
    if(isset($_POST['update'])){
        $doas = $_POST['doa'];
        $subjectss = $_POST['subjects'];
        $statuss = $_POST['status'];

        for($i=0; $i<count($statuss) ; $i++){
            $stu_id = $_SESSION['std_id'][$i];
            $doa = $doas[$i];
            $subjects = implode(" ", $subjectss[$i]);
            $status = $statuss[$i];
            
            $sql = "UPDATE students SET date_of_admission=STR_TO_DATE('$doa', '%d/%m/%Y'), subjects = '$subjects', status = '$status'  WHERE student_id='$stu_id' ";
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
                Bulk Student Update <strong>Successful !!</strong>
            </div>
        </div>
            ';
        }
    }
?>

</script>
<!--Date Picker-->
<script>
    $(document).ready(function(){
        $(".doa").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>