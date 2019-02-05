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
                <div class="card-header">Datewise Payment List</div>
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
        $from = DateTime::createFromFormat('d/m/Y', $_POST['from_date'])->format('Y-m-d');
        $to = DateTime::createFromFormat('d/m/Y', $_POST['to_date'])->format('Y-m-d');
        $total = 0;
        global $conn;

        $sql = "SELECT students.first_name, students.last_name, students.program_roll, students.program, students.subjects, students.batch_day, students.batch_time, payment.payment_for_month, payment.payment_date, payment.paid_amount 
            FROM students INNER JOIN payment ON students.student_id = payment.student_id
            WHERE (payment.payment_date BETWEEN '$from' AND '$to') AND students.program='$program' ORDER BY payment.payment_date";
        
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