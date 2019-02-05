<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
        <div class="container col-md-8">
            <div class="card">
                <div class="card-header">Withdrawal History</div>
                <div class="card-body">
                	<?php view_withdrawal_history(); ?>
                </div>
	        </div>
	    </div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php
    function view_withdrawal_history(){

        global $conn;
        $total = 0;

        $person = $_POST['person'];
        $from = DateTime::createFromFormat('d/m/Y', $_POST['from_date'])->format('Y-m-d');
        $to = DateTime::createFromFormat('d/m/Y', $_POST['to_date'])->format('Y-m-d');

        $_SESSION['msg'] = "<center><strong>Withdrawal Report for $person</strong></center>";
        $_SESSION['msg'] .= "<center><strong>From: $_POST[from_date]</strong></center>";
        $_SESSION['msg'] .= "<center><strong>To: $_POST[to_date]</strong></center>";

        if($person == 'Tahir Hasan Riddha'){
            $sql = "SELECT cash_wd_riddha, cash_wd_riddha_date, cash_wd_riddha_ref FROM accounts WHERE cash_wd_riddha_date BETWEEN '$from' AND '$to' ORDER BY cash_wd_riddha_date";
    
            $result = $conn->query($sql);
            
            if($result->num_rows > 0){
                echo '
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
                $_SESSION['msg'] .= "
                <tr>
                    <td></td>
                    <td></td>
                    <td><strong>Total</strong></td>
                    <td>$total</td>
                </tr>
                    </tbody>
                </table>
                    </div>
                </div>
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
        else if($person == 'Jumshadur Rahman Xihad'){
            $sql = "SELECT cash_wd_xihad, cash_wd_xihad_date, cash_wd_xihad_ref FROM accounts WHERE cash_wd_xihad_date BETWEEN '$from' AND '$to' ORDER BY cash_wd_xihad_date";
    
            $result = $conn->query($sql);
            
            if($result->num_rows > 0){
                echo '
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
                $_SESSION['msg'] .= "
                    <tr>
                        <td></td>
                        <td></td>
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
        else if($person == 'Bappa Ghosh Shawon'){
            $sql = "SELECT cash_wd_teacher1, cash_wd_teacher1_date, cash_wd_teacher1_ref FROM accounts WHERE cash_wd_teacher1_date BETWEEN '$from' AND '$to' ORDER BY cash_wd_teacher1_date";
    
            $result = $conn->query($sql);
            
            if($result->num_rows > 0){
                echo '
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
                    $_SESSION['msg'] .= "
                    <tr>
                        <td></td>
                        <td></td>
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
    }
?>