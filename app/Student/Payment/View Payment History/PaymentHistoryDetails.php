<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
?>
	
<div class="container">
	<?php view_payment_history_details(); ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php
    function view_payment_history_details(){

        global $conn;

        $sql = "SELECT payment_type FROM programs WHERE program_name='$_GET[prog]' ";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        $pay_type = $data['payment_type'];

        $sql = "SELECT * FROM payment WHERE student_id = '$_GET[std_id]' AND payment_date != '0000-00-00' ORDER BY payment_date DESC";

        $result = $conn->query($sql);
        
        if($result->num_rows > 0){
            echo '
            <br>
            <div class="card">
            <div class="card-header">Payment History Details:</div>
                <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Payment Date</th>
                            <th style="'; if($pay_type=='Course'){ echo 'display:none;';} echo'">For Month</th>
                            <th>For Year</th>
                            <th>Paid Amount</th>
                            <th>Discount</th>
                            <th>Due Amount</th>
                            <th>Next Payment Date</th>
                            <th>Reference</th>
                            <th>Show Invoice</th>
                            <th '; if($_SESSION['user_type']!=md5('Admin')){echo "hidden";} echo'>Edit Payment</th>
                            <th '; if($_SESSION['user_type']!=md5('Admin')){echo "hidden";} echo'>Delete Payment</th>
                        </tr>
                    </thead>
                <tbody>
            ';
            $c = 1;
            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$c</td>
                        <td>".date_format(date_create($row['payment_date']), 'd/m/Y')."</td>
                        <td"; if($pay_type=='Course'){ echo 'hidden';} echo">$row[payment_for_month]</td>
                        <td>$row[payment_year]</td>
                        <td>$row[paid_amount]</td>
                        <td>$row[discount_amount]</td>
                        <td>$row[due_amount]</td>
                        <td>";
                        if($row['next_payment_date'] == '0000-00-00'){
                            echo '';
                        }else{
                            echo date_format(date_create($row['next_payment_date']), 'd/m/Y');
                        }
                        echo "</td>
                        <td>$row[reference]</td>
                        <td><a href='Invoice.php?pay_id=$row[payment_id]' class='btn btn-success'>Show Invoice</a></td>
                        <td "; if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} echo"><a href='EditPayment.php?edit_pay_id=$row[payment_id]' class='btn btn-info'>Edit</a></td>
                        <td "; if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} echo"><a href='DeletePayment.php?del_pay_id=$row[payment_id]' class='btn btn-danger'>Delete</a></td>
                    </tr>
                ";
                $c++;
            } 
            echo "</tbody>
                </table>
                </div>
                </div>";
        }else{
            echo '
            <br>
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>No Records Found !!</strong>
            </div>
            ';
        }
    }
?>