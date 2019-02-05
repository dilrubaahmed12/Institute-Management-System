<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
        <div class="container col-md-10">
            <div class="card">
                <div class="card-header"><?php echo $_POST['subject']; ?> Payment List</div>
                <div class="card-body">
                    <?php view_list(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php
    function view_list(){
        $program = $_POST['program'];
        $subject = $_POST['subject'];
        $from = DateTime::createFromFormat('d/m/Y', $_POST['from_date'])->format('Y-m-d');
        $to = DateTime::createFromFormat('d/m/Y', $_POST['to_date'])->format('Y-m-d');
        $total = 0;
        $total_sub_amount = 0;
        global $conn;

        $_SESSION['msg'] = "<center><strong>Program: $program</strong></center>";
        $_SESSION['msg'] .= "<center><strong>Subject: $subject</strong></center>";
        $_SESSION['msg'] .= "<center><strong>From: $_POST[from_date]</strong></center>";
        $_SESSION['msg'] .= "<center><strong>To: $_POST[to_date]</strong></center>";

        $sql = "SELECT subjects, fee_per_subject, admission_fee, extra_fee, payment_type FROM programs WHERE program_name='$program' ";
        $res = $conn->query($sql);
        $data = $res->fetch_assoc();
        $all_subs = explode(" ", $data['subjects']);
        $fee = explode(",", $data['fee_per_subject']);
        $admission_fee = $data['admission_fee'];
        $extra_fee = $data['extra_fee'];
        $pay_type = $data['payment_type'];

        $sql = "SELECT students.first_name, students.last_name, students.program_roll, students.program, students.subjects, students.batch_day, students.batch_time, students.date_of_admission, payment.payment_for_month, payment.payment_date, payment.paid_amount, payment.discount_amount, payment.due_amount
            FROM students INNER JOIN payment ON students.student_id = payment.student_id
            WHERE (payment.payment_date BETWEEN '$from' AND '$to') AND students.subjects LIKE '%$subject%' AND students.program='$program' ORDER BY payment.payment_date";
        
        $result = $conn->query($sql);
        
        if($result->num_rows > 0){
            echo '
                <div>Total '.$result->num_rows.' result(s) found</div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Program Roll</th>
                            <th>Program</th>
                            <th>Subjects</th>
                            <th>Batch Day</th>
                            <th>Batch Time</th>
                            <th '; if($pay_type == 'Course') echo "hidden"; echo '>Payment Month</th>
                            <th>Payment Date</th>
                            <th>Paid Amount</th>
                            <th '; if($pay_type == 'Course') echo "hidden"; echo '>'.$subject.' Amount</th>
                        </tr>
                    </thead>
                <tbody>
            ';
            $_SESSION['msg'] .= '
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Program Roll</th>
                            <th>Program</th>
                            <th>Subjects</th>
                            <th>Batch Day</th>
                            <th>Batch Time</th>
                            <th '; if($pay_type == 'Course') $_SESSION['msg'] .= "hidden"; $_SESSION['msg'] .= '>Payment Month</th>
                            <th>Payment Date</th>
                            <th>Paid Amount</th>
                            <th '; if($pay_type == 'Course') $_SESSION['msg'] .= "hidden"; $_SESSION['msg'] .= '>'.$subject.' Amount</th>
                        </tr>
                    </thead>
                <tbody>';
            while($row = $result->fetch_assoc()){
                $sub_amount = $row['paid_amount'];
                $taken_subs = explode(" ", $row['subjects']);

                $rcvable = 0;
                for($i=0; $i<sizeof($taken_subs); $i++){
                    for($j=0; $j<sizeof($all_subs); $j++){
                        if($taken_subs[$i] == $all_subs[$j]){
                            $rcvable += $fee[$j];
                        }
                    }
                }

                if($row['date_of_admission'] == $row['payment_date']){
                    $rcvable += $admission_fee + $extra_fee;
                }else{
                    $rcvable += $extra_fee;
                }

                if($sub_amount < ($rcvable-(float)$row['discount_amount']) && (float)$row['due_amount'] == 0){
                    $fake_due = ($rcvable-(float)$row['discount_amount']) - $sub_amount;
                }else{
                    $fake_due = (float)$row['due_amount'];
                }
 
                if($row['payment_date'] == $row['date_of_admission']){
                $dis_due = ((float)$row['discount_amount'] + $fake_due - $admission_fee)/sizeof($taken_subs);
                }else{
                    $dis_due = ((float)$row['discount_amount'] + $fake_due)/sizeof($taken_subs);
                }
                               
                for($i=0; $i<sizeof($taken_subs); $i++){
                    for($j=0; $j<sizeof($all_subs); $j++){
                        if($taken_subs[$i] == $all_subs[$j] && $taken_subs[$i] != $subject){
                            $sub_amount -= ($fee[$j]-$dis_due);
                        }
                    }
                }
                $sub_amount -= $extra_fee/sizeof($taken_subs)*(sizeof($taken_subs) - 1);
                
                echo "
                    <tr>
                        <td>$row[first_name] $row[last_name]</td>
                        <td>$row[program_roll]</td>
                        <td>$row[program]</td>
                        <td>$row[subjects]</td>
                        <td>$row[batch_day]</td>
                        <td>$row[batch_time]</td>
                        <td "; if($pay_type == 'Course') echo "hidden"; echo ">$row[payment_for_month]</td>
                        <td>".date_format(date_create($row['payment_date']), 'd/m/Y')."</td>
                        <td>$row[paid_amount]</td>
                        <td "; if($pay_type == 'Course') echo "hidden"; echo ">".$sub_amount."</td>
                    </tr>
                ";
                $total += $row['paid_amount'];
                $total_sub_amount += $sub_amount;

                $_SESSION['msg'] .= "
                    <tr>
                        <td>$row[first_name] $row[last_name]</td>
                        <td>$row[program_roll]</td>
                        <td>$row[program]</td>
                        <td>$row[subjects]</td>
                        <td>$row[batch_day]</td>
                        <td>$row[batch_time]</td>
                        <td "; if($pay_type == 'Course') $_SESSION['msg'] .= "hidden"; $_SESSION['msg'] .= ">$row[payment_for_month]</td>
                        <td>".date_format(date_create($row['payment_date']), 'd/m/Y')."</td>
                        <td>$row[paid_amount]</td>
                        <td "; if($pay_type == 'Course') $_SESSION['msg'] .= "hidden"; $_SESSION['msg'] .= ">".$sub_amount."</td>
                    </tr>
                ";
            } 
            echo "
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td "; if($pay_type == 'Course') echo "hidden"; echo "></td>
                    <td><strong>Total</strong></td>
                    <td>$total</td>
                    <td "; if($pay_type == 'Course') echo "hidden"; echo ">$total_sub_amount</td>
                </tr>
                </tbody>
            </table>
                <form method='POST' action='Mail.php'>
                    <center><strong>Mail to: </strong></center>
                    <div class='form-check text-center'>
                        <label class='form-check-label'>
                            <input name='mail_to[]' value='Riddha' type='checkbox' class='form-check-input'> Tahir Hasan Riddha
                        </label>
                    </div>
                    <div class='form-check text-center'>
                        <label class='form-check-label'>
                            <input name='mail_to[]' value='Xihad' type='checkbox' class='form-check-input'> Jumshadur Rahman Xihad
                        </label>
                    </div>
                    <div class='form-check text-center'>
                        <label class='form-check-label'>
                            <input name='mail_to[]' value='Shawon' type='checkbox' class='form-check-input'> Bappa Ghosh Shawon
                        </label>
                    </div>
                    </br>
                    <div class='form-group text-center'>
                        <input type='submit' value='Send Mail' name='send_mail' class='btn btn-dark btn-lg'></input>
                    </div>
                </form>
            </div>
            </div>";

            $_SESSION['msg'] .= "<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td "; if($pay_type == 'Course') $_SESSION['msg'] .= "hidden"; $_SESSION['msg'] .= "></td>
                    <td><strong>Total</strong></td>
                    <td>$total</td>
                    <td "; if($pay_type == 'Course') $_SESSION['msg'] .= "hidden"; $_SESSION['msg'] .= ">$total_sub_amount</td>
                </tr>
                </tbody>
            </table>
            ";
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