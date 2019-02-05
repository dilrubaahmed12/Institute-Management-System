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
                <div class="card-header">Expense History</div>
                <div class="card-body">
                	<?php view_expense_history(); ?>
                </div>
	        </div>
	    </div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php
    function view_expense_history(){

        global $conn;
        $total = 0;
        $total_common = 0;
        $total_riddha = 0;
        $total_xihad = 0;
        $total_shawon = 0;

        $from = DateTime::createFromFormat('d/m/Y', $_POST['from_date'])->format('Y-m-d');
        $to = DateTime::createFromFormat('d/m/Y', $_POST['to_date'])->format('Y-m-d');

        $_SESSION['msg'] = "<center><strong>Expense Report</strong></center>";
        $_SESSION['msg'] .= "<center><strong>From: $_POST[from_date]</strong></center>";
        $_SESSION['msg'] .= "<center><strong>To: $_POST[to_date]</strong></center>";

        $sql = "SELECT expense_type, expense, expense_date, expense_ref FROM accounts WHERE expense_date BETWEEN '$from' AND '$to' ORDER BY expense_date";

        $result = $conn->query($sql);
        
        if($result->num_rows > 0){
            echo '
                <table class="table table-hover">
                    <thead>
                        <tr>
                        	<th>No.</th>
                            <th>Expense Date</th>
                            <th>Expense Type</th>
                            <th>Expense Reference</th>
                            <th>Expense Amount</th>
                            <th>Common</th>
                            <th>Riddha</th>
                            <th>Xihad</th>
                            <th>Shawon</th>
                        </tr>
                    </thead>
                <tbody>
            ';
            $_SESSION['msg'] .= '
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Expense Date</th>
                            <th>Expense Type</th>
                            <th>Expense Reference</th>
                            <th>Expense Amount</th>
                            <th>Common</th>
                            <th>Riddha</th>
                            <th>Xihad</th>
                            <th>Shawon</th>
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
                        <td>"; if(stripos($row['expense_type'], "Common")) {echo $row['expense']; $total_common+=$row['expense'];} echo "</td>
                        <td>"; if(stripos($row['expense_type'], "Riddha")) {echo $row['expense']; $total_riddha+=$row['expense'];} echo "</td>
                        <td>"; if(stripos($row['expense_type'], "Xihad")) {echo $row['expense']; $total_xihad+=$row['expense'];} echo "</td>
                        <td>"; if(stripos($row['expense_type'], "Shawon")) {echo $row['expense']; $total_shawon+=$row['expense'];} echo "</td>
                    </tr>
                ";
                $_SESSION['msg'] .= "
                    <tr>
                        <td>$c</td>
                        <td>".date_format(date_create($row['expense_date']), 'd/m/Y')."</td>
                        <td>$row[expense_type]</td>
                        <td>$row[expense_ref]</td>
                        <td>$row[expense]</td>
                        <td>"; if(stripos($row['expense_type'], "Common")) {$_SESSION['msg'] .= $row['expense'];} $_SESSION['msg'] .= "</td>
                        <td>"; if(stripos($row['expense_type'], "Riddha")) {$_SESSION['msg'] .= $row['expense'];} $_SESSION['msg'] .= "</td>
                        <td>"; if(stripos($row['expense_type'], "Xihad")) {$_SESSION['msg'] .= $row['expense'];} $_SESSION['msg'] .= "</td>
                        <td>"; if(stripos($row['expense_type'], "Shawon")) {$_SESSION['msg'] .= $row['expense'];} $_SESSION['msg'] .= "</td>
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
                    <td>$total_common</td>
                    <td>$total_riddha</td>
                    <td>$total_xihad</td>
                    <td>$total_shawon</td>
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
                </div>
                ";
            $_SESSION['msg'] .= "
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><strong>Total</strong></td>
                    <td>$total</td>
                    <td>$total_common</td>
                    <td>$total_riddha</td>
                    <td>$total_xihad</td>
                    <td>$total_shawon</td>
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