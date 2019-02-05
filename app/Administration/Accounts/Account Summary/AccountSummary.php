<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<?php
    $sql = "SELECT SUM(paid_amount) FROM payment";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_paid = $row['SUM(paid_amount)'];
    
    $sql = "SELECT SUM(expense) FROM accounts";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_expense = $row['SUM(expense)'];

    $sql = "SELECT SUM(income) FROM accounts";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_income = $row['SUM(income)'];

    $sql = "SELECT SUM(cash_wd_riddha) FROM accounts";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $cash_wd_riddha = $row['SUM(cash_wd_riddha)'];

    $sql = "SELECT SUM(cash_wd_xihad) FROM accounts";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $cash_wd_xihad = $row['SUM(cash_wd_xihad)'];

    $sql = "SELECT SUM(cash_wd_teacher1) FROM accounts";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $cash_wd_teacher1 = $row['SUM(cash_wd_teacher1)'];

    $sql = "SELECT SUM(cancel_fee) FROM accounts";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $cancel_fee = $row['SUM(cancel_fee)'];
    
    $cash_in_hand = $total_paid - $total_expense + $total_income - $cash_wd_riddha - $cash_wd_xihad - $cash_wd_teacher1 - $cancel_fee;
?>

<?php
    function cash_in_hand(){

        global $cash_in_hand;

        if($cash_in_hand >=0 ){
            echo'<span class="text-success"> '.$cash_in_hand.'</span>
            ';
        }else{
            echo'<span class="text-danger"> '.$cash_in_hand.'</span>
            '; 
        }
    } 
?>

<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Account Summary</div>
                <div class="card-body">
                    <span class="display-4">Cash in Account:</span><span class="display-1"><?php cash_in_hand(); ?>Taka</span>
                    <br><br><br><br>
                    <div class="text-center">
                    	<a href="Withdraw.php" class="btn btn-primary btn-lg">Withdraw</a>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
