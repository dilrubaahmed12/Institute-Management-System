<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
        <div class="container col-md-10">
            <div class="card">
                <div class="card-header">Due Payment List</div>
                <div class="card-body">
                    <?php view_due_list(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php
    function view_due_list(){
        $program = $_POST['program'];
        $month = $_POST['month'];
        $session = $_POST['session'];
        $total = 0;
        $count = 0;
        
        global $conn;

        $sql = "SELECT payment_type FROM programs WHERE program_name='$program' ";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        $pay_type = $data['payment_type'];

        if($pay_type == 'Monthly'){
            $sql = "SELECT DISTINCT students.student_id, students.first_name, students.last_name, students.cont_stdnt, students.cont_fath, students.cont_moth, students.program_roll, students.program, students.subjects, students.batch_day, students.batch_time
            FROM students INNER JOIN payment ON students.student_id = payment.student_id
            WHERE (payment.payment_for_month != '$month' AND students.status='Active' AND students.program='$program') OR (students.program='$program' AND payment.due_amount !=0 AND students.status='Active') OR (payment.payment_for_month = '$month' AND payment.payment_year != '$session' AND students.status='Active' AND students.program='$program')";
        }else if($pay_type == 'Course'){
            $sql = "SELECT DISTINCT students.student_id, students.first_name, students.last_name, students.cont_stdnt, students.cont_fath, students.cont_moth, students.program_roll, students.program, students.subjects, students.batch_day, students.batch_time
            FROM students INNER JOIN payment ON students.student_id = payment.student_id
            WHERE payment.payment_for_month = '' AND students.status='Active' AND students.program='$program' AND payment.due_amount !=0 AND payment.payment_year = '$session' " ;
        }
        
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            echo '
                <div>Due(s) List</div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th width="10%">Contact No.</th>
                            <th>Program Roll</th>
                            <th>Program</th>
                            <th>Subjects</th>
                            <th>Batch Day</th>
                            <th>Batch Time</th>
                            <th style="'; if($pay_type=='Course'){ echo 'display:none;';} echo'">Due Month</th>
                            <th>Due Amount</th>
                        </tr>
                    </thead>
                <tbody>
            ';
            while($row = $result->fetch_assoc()){
                if(due($row['student_id'], $month, $session))
                {
                    echo "
                    <tr>
                        <td>$row[first_name] $row[last_name]</td>
                        <td>$row[cont_stdnt] $row[cont_fath] $row[cont_moth]</td>
                        <td>$row[program_roll]</td>
                        <td>$row[program]</td>
                        <td>$row[subjects]</td>
                        <td>$row[batch_day]</td>
                        <td>$row[batch_time]</td>
                        <td"; if($pay_type=='Course'){ echo 'hidden';} echo">".$month."</td>
                        <td>"; due_amount($row['student_id'], $month, $session); echo "</td>
                    </tr>
                    ";
                    $total += due($row['student_id'], $month, $session);
                    $count++;
                }
            } 
            echo "
                <tr>
                    <td>Total Due</td>
                    <td><strong>$count</strong></td>
                    <td>Students</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td"; if($pay_type=='Course'){ echo 'hidden';} echo"></td>
                    <td><strong>Total</strong></td>
                    <td>$total</td>
                </tr>
                </tbody>
            </table>
            </div>
            </div>";
        }else{
            echo '
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>No Records Found !!</strong>
            </div>
            ';
        }
    }
?>

<?php  
    function due_amount($id, $mon, $sess){
        global $conn;

        if($mon==''){
            $sql = "SELECT due_amount FROM payment WHERE student_id='$id' AND due_amount != 0 ";
            $result = $conn->query($sql);
            $data = $result->fetch_assoc();
            $total_fee = $data['due_amount'];
        }else{
            $total_fee = 0;

            $sql = "SELECT payment_for_month FROM payment WHERE payment_for_month='$mon' AND payment_year='$sess' AND student_id='$id' ";
            $result = $conn->query($sql);

            if($result->num_rows > 0){
                $sql3 = "SELECT due_amount FROM payment WHERE student_id='$id' AND due_amount != 0 ";
                $result3 = $conn->query($sql3);
                
                while($data3 = $result3->fetch_assoc()){
                    $total_fee += $data3['due_amount'];
                }
            }else{
                $sql1 = "SELECT program, subjects FROM students WHERE student_id='$id' ";
                $result1 = $conn->query($sql1);
                $data1 = $result1->fetch_assoc();
                $prg = $data1['program'];
                $taken_subs = explode(" ", $data1['subjects']);

                mysqli_free_result($result1);
                $data1 = NULL;

                $sql2 = "SELECT subjects, fee_per_subject, extra_fee FROM programs WHERE program_name='$prg' ";
                $result2 = $conn->query($sql2);
                $data2 = $result2->fetch_assoc();
                $all_subs = explode(" ", $data2['subjects']);
                $fee = explode(",", $data2['fee_per_subject']);

                $total_fee = 0;
                for($i=0; $i<sizeof($taken_subs); $i++){
                    for($j=0; $j<sizeof($all_subs); $j++){
                        if($taken_subs[$i] == $all_subs[$j]){
                            $total_fee += $fee[$j];
                        }
                    }
                }
                $total_fee += $data2['extra_fee'];

                $sql3 = "SELECT due_amount FROM payment WHERE student_id='$id' AND due_amount != 0 ";
                $result3 = $conn->query($sql3);
                
                while($data3 = $result3->fetch_assoc()){
                    $total_fee += $data3['due_amount'];
                }
            }
        }
        
        echo $total_fee;
    }
?>

<?php
    function due($id, $mon, $sess){
        global $conn;

        if($mon==''){
            $sql = "SELECT due_amount FROM payment WHERE student_id='$id' AND due_amount != 0 ";
            $result = $conn->query($sql);
            $data = $result->fetch_assoc();
            $total_fee = $data['due_amount'];
        }else{
            $total_fee = 0;

            $sql = "SELECT payment_for_month FROM payment WHERE payment_for_month='$mon' AND payment_year='$sess' AND student_id='$id' ";
            $result = $conn->query($sql);

            if($result->num_rows > 0){
                $sql3 = "SELECT due_amount FROM payment WHERE student_id='$id' AND due_amount != 0 ";
                $result3 = $conn->query($sql3);
                
                while($data3 = $result3->fetch_assoc()){
                    $total_fee += $data3['due_amount'];
                }
            }else{
                $sql1 = "SELECT program, subjects FROM students WHERE student_id='$id' ";
                $result1 = $conn->query($sql1);
                $data1 = $result1->fetch_assoc();
                $prg = $data1['program'];
                $taken_subs = explode(" ", $data1['subjects']);

                mysqli_free_result($result1);
                $data1 = NULL;

                $sql2 = "SELECT subjects, fee_per_subject, extra_fee FROM programs WHERE program_name='$prg' ";
                $result2 = $conn->query($sql2);
                $data2 = $result2->fetch_assoc();
                $all_subs = explode(" ", $data2['subjects']);
                $fee = explode(",", $data2['fee_per_subject']);

                $total_fee = 0;
                for($i=0; $i<sizeof($taken_subs); $i++){
                    for($j=0; $j<sizeof($all_subs); $j++){
                        if($taken_subs[$i] == $all_subs[$j]){
                            $total_fee += $fee[$j];
                        }
                    }
                }
                $total_fee += $data2['extra_fee'];

                $sql3 = "SELECT due_amount FROM payment WHERE student_id='$id' AND due_amount != 0 ";
                $result3 = $conn->query($sql3);
                
                while($data3 = $result3->fetch_assoc()){
                    $total_fee += $data3['due_amount'];
                }
            }
        }

        return $total_fee;
    }
?>