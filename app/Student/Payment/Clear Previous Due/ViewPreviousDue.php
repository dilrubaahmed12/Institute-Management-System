<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
	
<div class="container">
	<?php search_due(); ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php
    function search_due(){

        $keywords = $_POST['kwd'];
        $month = $_POST['month'];
        $session = $_POST['session'];

        global $conn;

        $sql = "SELECT DISTINCT students.student_id, students.first_name, students.last_name, students.cont_stdnt, students.cont_fath, students.cont_moth, students.program_roll, students.program, students.subjects, students.status, students.batch_day, students.batch_time, payment.due_amount
        FROM students INNER JOIN payment ON students.student_id = payment.student_id
        WHERE payment.payment_for_month = '$month' AND payment.payment_year='$session' AND payment.due_amount !=0
        AND (students.first_name LIKE '%{$keywords}%'
            OR students.last_name LIKE '%{$keywords}%'
            OR students.cont_stdnt LIKE '%{$keywords}%'
            OR students.cont_fath LIKE '%{$keywords}%'
            OR students.cont_moth LIKE '%{$keywords}%'
            OR students.program_roll LIKE '%{$keywords}%' ) 
            ORDER BY students.program" ;
       

        $result = $conn->query($sql);

        if($result->num_rows > 0){
            echo '
            <br>
            <div class="card">
            <div class="card-header">Search Result: Total '.$result->num_rows.' result(s) found</div>
                <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Contact No.</th>
                            <th>Program Roll</th>
                            <th>Program</th>
                            <th>Subjects</th>
                            <th>Batch Day</th>
                            <th>Batch Time</th>
                            <th>Status</th>
                            <th>Due Amount</th>
                            <th>Clear Due</th>
                        </tr>
                    </thead>
                <tbody>
            ';
            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$row[first_name] $row[last_name]</td>
                        <td>$row[cont_stdnt] $row[cont_fath] $row[cont_moth]</td>
                        <td>$row[program_roll]</td>
                        <td>$row[program]</td>
                        <td>$row[subjects]</td>
                        <td>$row[batch_day]</td>
                        <td>$row[batch_time]</td>
                        <td>$row[status]</td>
                        <td>$row[due_amount]</td>
                        <td><a href='CollectPreviousDue.php?pay_id=$row[student_id]&month=$month&sess=$session' class='btn btn-success'>Clear Due</a></td>
                    </tr>
                ";
            } 
            echo "</tbody>
                </table>
                </div>
                <div>";
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