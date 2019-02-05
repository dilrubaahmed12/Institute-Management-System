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
                <div class="card-header">Account Report</div>
                <div class="card-body">
                    <?php
                        $date = DateTime::createFromFormat('d/m/Y', $_POST['date'])->format('Y-m-d');
                        $total = 0;
                        global $conn;

                        $_SESSION['msg'] = "<center><strong>Account Report</strong></center>";
                        $_SESSION['msg'] .= "<center><strong>Date: $_POST[date]</strong></center>";

                        $sql = "SELECT students.first_name, students.last_name, students.program_roll, students.program, students.subjects, students.batch_day, students.batch_time, payment.payment_for_month, payment.payment_date, payment.paid_amount 
                            FROM students INNER JOIN payment ON students.student_id = payment.student_id
                            WHERE payment.payment_date='$date' ORDER BY students.program";
                        
                        $result = $conn->query($sql);
                        if($result->num_rows > 0){
                            echo '
                                <div><center>Payment</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Program Roll</th>
                                            <th>Program</th>
                                            <th>Subjects</th>
                                            <th>Batch Day</th>
                                            <th>Batch Time</th>
                                            <th>Payment Month</th>
                                            <th>Payment Date</th>
                                            <th>Paid Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $_SESSION['msg'] .= '
                                <div><center>Payment</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Program Roll</th>
                                            <th>Program</th>
                                            <th>Subjects</th>
                                            <th>Batch Day</th>
                                            <th>Batch Time</th>
                                            <th>Payment Month</th>
                                            <th>Payment Date</th>
                                            <th>Paid Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <tr>
                                        <td>$row[first_name] $row[last_name]</td>
                                        <td>$row[program_roll]</td>
                                        <td>$row[program]</td>
                                        <td>$row[subjects]</td>
                                        <td>$row[batch_day]</td>
                                        <td>$row[batch_time]</td>
                                        <td>$row[payment_for_month]</td>
                                        <td>".date_format(date_create($row['payment_date']), 'd/m/Y')."</td>
                                        <td>$row[paid_amount]</td>
                                    </tr>
                                ";
                                $total += $row['paid_amount'];
                                $_SESSION['msg'] .= "
                                    <tr>
                                        <td>$row[first_name] $row[last_name]</td>
                                        <td>$row[program_roll]</td>
                                        <td>$row[program]</td>
                                        <td>$row[subjects]</td>
                                        <td>$row[batch_day]</td>
                                        <td>$row[batch_time]</td>
                                        <td>$row[payment_for_month]</td>
                                        <td>".date_format(date_create($row['payment_date']), 'd/m/Y')."</td>
                                        <td>$row[paid_amount]</td>
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
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                </tbody>
                            </table>
                            ";
                            $_SESSION['msg'] .= "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                </tbody>
                            </table>
                            ";
                        }

                        $total = 0;
                        $sql = "SELECT expense_type, expense, expense_date, expense_ref FROM accounts WHERE expense_date='$date' ";

                        $result = $conn->query($sql);
                        
                        if($result->num_rows > 0){
                            echo '
                                <div><center>Expense</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Expense Date</th>
                                            <th>Expense Type</th>
                                            <th>Expense Reference</th>
                                            <th>Expense Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';

                            $_SESSION['msg'] .= '
                            <div><center>Expense</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Expense Date</th>
                                            <th>Expense Type</th>
                                            <th>Expense Reference</th>
                                            <th>Expense Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $c = 1;
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['expense_date']), 'd/m/Y')."</td>
                                        <td>$row[expense_type]</td>
                                        <td>$row[expense_ref]</td>
                                        <td>$row[expense]</td>
                                    </tr>
                                ";
                                $_SESSION['msg'] .= "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['expense_date']), 'd/m/Y')."</td>
                                        <td>$row[expense_type]</td>
                                        <td>$row[expense_ref]</td>
                                        <td>$row[expense]</td>
                                    </tr>
                                ";
                                $total += $row['expense'];
                                $c++;
                            } 
                            echo "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                            $_SESSION['msg'] .= "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>
                            ";
                        }

                        $total = 0;
                        $sql = "SELECT income, income_date, income_ref FROM accounts WHERE income_date='$date' ";

                        $result = $conn->query($sql);
                        
                        if($result->num_rows > 0){
                            echo '
                            <div><center>Income</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Income Date</th>
                                            <th>Income Reference</th>
                                            <th>Income Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $_SESSION['msg'] .= '
                            <div><center>Income</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Income Date</th>
                                            <th>Income Reference</th>
                                            <th>Income Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $c = 1;
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['income_date']), 'd/m/Y')."</td>
                                        <td>$row[income_ref]</td>
                                        <td>$row[income]</td>
                                    </tr>
                                ";
                                $_SESSION['msg'] .= "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['income_date']), 'd/m/Y')."</td>
                                        <td>$row[income_ref]</td>
                                        <td>$row[income]</td>
                                    </tr>
                                ";
                                $total += $row['income'];
                                $c++;
                            } 
                            echo "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                            $_SESSION['msg'] .= "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                        }

                        $total = 0;
                        $sql = "SELECT cash_wd_riddha, cash_wd_riddha_date, cash_wd_riddha_ref FROM accounts WHERE cash_wd_riddha_date='$date' ";
    
                        $result = $conn->query($sql);
                        
                        if($result->num_rows > 0){
                            echo '
                            <div><center>Cash Withdraw Tahir Hasan Riddha</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Withdrawal Date</th>
                                            <th>Reference</th>
                                            <th>Cash Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $_SESSION['msg'] .= '
                            <div><center>Cash Withdraw Tahir Hasan Riddha</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Withdrawal Date</th>
                                            <th>Reference</th>
                                            <th>Cash Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $c = 1;
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['cash_wd_riddha_date']), 'd/m/Y')."</td>
                                        <td>$row[cash_wd_riddha_ref]</td>
                                        <td>$row[cash_wd_riddha]</td>
                                    </tr>
                                ";
                                $_SESSION['msg'] .= "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['cash_wd_riddha_date']), 'd/m/Y')."</td>
                                        <td>$row[cash_wd_riddha_ref]</td>
                                        <td>$row[cash_wd_riddha]</td>
                                    </tr>
                                ";
                                $c++;
                                $total += $row['cash_wd_riddha'];
                            } 
                            echo "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                            $_SESSION['msg'] .= "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                        }

                        $total = 0;
                        $sql = "SELECT cash_wd_xihad, cash_wd_xihad_date, cash_wd_xihad_ref FROM accounts WHERE cash_wd_xihad_date='$date' ";
    
                        $result = $conn->query($sql);
                        
                        if($result->num_rows > 0){
                            echo '
                            <div><center>Cash Withdraw Jumshadur Rahman Xihad</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Withdrawal Date</th>
                                            <th>Reference</th>
                                            <th>Cash Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $_SESSION['msg'] .= '
                            <div><center>Cash Withdraw Jumshadur Rahman Xihad</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Withdrawal Date</th>
                                            <th>Reference</th>
                                            <th>Cash Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $c = 1;
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['cash_wd_xihad_date']), 'd/m/Y')."</td>
                                        <td>$row[cash_wd_xihad_ref]</td>
                                        <td>$row[cash_wd_xihad]</td>
                                    </tr>
                                ";
                                $_SESSION['msg'] .= "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['cash_wd_xihad_date']), 'd/m/Y')."</td>
                                        <td>$row[cash_wd_xihad_ref]</td>
                                        <td>$row[cash_wd_xihad]</td>
                                    </tr>
                                ";
                                $c++;
                                $total += $row['cash_wd_xihad'];
                            } 
                            echo "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                            $_SESSION['msg'] .= "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                        }

                        $total = 0;
                        $sql = "SELECT cash_wd_teacher1, cash_wd_teacher1_date, cash_wd_teacher1_ref FROM accounts WHERE cash_wd_teacher1_date='$date' ";
    
                        $result = $conn->query($sql);
                        
                        if($result->num_rows > 0){
                            echo '
                            <div><center>Cash Withdraw Bappa Ghosh Shawon</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Withdrawal Date</th>
                                            <th>Reference</th>
                                            <th>Cash Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $_SESSION['msg'] .= '
                            <div><center>Cash Withdraw Bappa Ghosh Shawon</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Withdrawal Date</th>
                                            <th>Reference</th>
                                            <th>Cash Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $c = 1;
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['cash_wd_teacher1_date']), 'd/m/Y')."</td>
                                        <td>$row[cash_wd_teacher1_ref]</td>
                                        <td>$row[cash_wd_teacher1]</td>
                                    </tr>
                                ";
                                $_SESSION['msg'] .= "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['cash_wd_teacher1_date']), 'd/m/Y')."</td>
                                        <td>$row[cash_wd_teacher1_ref]</td>
                                        <td>$row[cash_wd_teacher1]</td>
                                    </tr>
                                ";
                                $c++;
                                $total += $row['cash_wd_teacher1'];
                            } 
                            echo "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                            $_SESSION['msg'] .= "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                        }

                        $total = 0;
                        $sql = "SELECT cancel_fee, cancel_date, cancel_ref FROM accounts WHERE cancel_date='$date' ";
    
                        $result = $conn->query($sql);
                        
                        if($result->num_rows > 0){
                            echo '
                            <div><center>Cancel</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Cancel Date</th>
                                            <th>Reference</th>
                                            <th>Refund Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $_SESSION['msg'] .= '
                            <div><center>Cancel</center></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Cancel Date</th>
                                            <th>Reference</th>
                                            <th>Refund Amount</th>
                                        </tr>
                                    </thead>
                                <tbody>
                            ';
                            $c = 1;
                            while($row = $result->fetch_assoc()){
                                echo "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['cancel_date']), 'd/m/Y')."</td>
                                        <td>$row[cancel_ref]</td>
                                        <td>$row[cancel_fee]</td>
                                    </tr>
                                ";
                                $_SESSION['msg'] .= "
                                    <tr>
                                        <td>$c</td>
                                        <td>".date_format(date_create($row['cancel_date']), 'd/m/Y')."</td>
                                        <td>$row[cancel_ref]</td>
                                        <td>$row[cancel_fee]</td>
                                    </tr>
                                ";
                                $c++;
                                $total += $row['cancel_fee'];
                            } 
                            echo "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                            $_SESSION['msg'] .= "
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td>$total</td>
                                </tr>
                                    </tbody>
                                </table>";
                        }

                        echo "
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
                            </br>
                            <div class='form-group text-center'>
                                <input type='submit' value='Send Mail' name='send_mail' class='btn btn-dark btn-lg'></input>
                            </div>
                        </form>
                        </div>
                        </div>
                        ";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>