<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<br>
<div class="container-fluid">
    <div class="container col-md-8">
        <div class="card">
            <!-- Student List -->
            <div class="card-header">Student List:</div>
            <div class="card-body">
                <?php
                    date_default_timezone_set('UTC');
                    $sess = date('Y');
                    $program = $_GET['program_name'];
                    $class = $_GET['class'];
                    $bDay = $_GET['b_day'];
                    $bTime = $_GET['b_time'];

                    $sql = "SELECT student_id, first_name, last_name, program_roll, program, class, batch_day, batch_time FROM students WHERE program='$program' AND class='$class' AND batch_day='$bDay' AND batch_time='$bTime' AND status='Active' AND session='$sess' ";
                
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
                                        <th>Program</th>
                                        <th>Class</th>
                                        <th>Batch Day</th>
                                        <th>Batch Time</th>
                                    </tr>
                                </thead>
                            <tbody>
                        ';
                        while($row = $result->fetch_assoc()){
                            echo "
                                <form method='POST' class='form-inline'>
                                <tr>
                                    <td>$row[first_name] $row[last_name]</td>
                                    <td>$row[program_roll]</td>
                                    <td>$row[program]</td>
                                    <td>$row[class]</td>
                                    <td>$row[batch_day]</td>
                                    <td>$row[batch_time]</td>
                                </tr>
                            ";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
