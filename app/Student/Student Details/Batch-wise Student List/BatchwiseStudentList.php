<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";

    unset($_SESSION['std_id']);
?>

<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
        <div class="col-md-10">
            <div class="card">
               <?php
                    show_batches(); 
                ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php
    function show_batches(){
        global $conn;
        date_default_timezone_set('UTC');
        $sess = date('Y');
        $sql = "SELECT DISTINCT class, program, batch_day, batch_time FROM students WHERE status='Active' AND session='$sess' ORDER BY program";
        $result = $conn->query($sql);
        
        if($result->num_rows > 0){
            echo '
            <div class="card-header">Running Batches: Total '.$result->num_rows.' Batches: </div>
                <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Program Name</th>
                            <th>Class</th>
                            <th>Batch Day</th>
                            <th>Batch Time</th>
                            <th>Student List</th>
                            <th>Edit Batch</th>
                        </tr>
                    </thead>
                <tbody>
            ';
            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$row[program]</td>
                        <td>$row[class]</td>
                        <td>$row[batch_day]</td>
                        <td>$row[batch_time]</td>
                        <td><a href='ViewBatch.php?program_name=$row[program]&class=$row[class]&b_day=$row[batch_day]&b_time=$row[batch_time]' class='btn btn-success'>Student List</a></td>
                        <td><a href='EditBatch.php?program_name=$row[program]&class=$row[class]&b_day=$row[batch_day]&b_time=$row[batch_time]' class='btn btn-info'>Edit Batch</a></td>
                    </tr>
                ";
            } 
            echo "</tbody>
                </table>";
        }else{
            echo '
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                There is <strong>no batches running</strong> now !!
            </div>
            ';
        }
    }
?>
