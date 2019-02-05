<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
	
<div class="container">
	<?php search_cancel_admission(); ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php
    function search_cancel_admission(){

        $keywords = $_POST['search'];
        
        global $conn;
        $sql = "SELECT * FROM students
        WHERE first_name LIKE '%{$keywords}%'
        OR last_name LIKE '%{$keywords}%'
        OR cont_stdnt LIKE '%{$keywords}%'
		OR cont_fath LIKE '%{$keywords}%'
		OR cont_moth LIKE '%{$keywords}%'
		OR program_roll LIKE '%{$keywords}%'
        ";

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
                            <th>Status</th>
                            <th>Payment</th>
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
                        <td>$row[status]</td>
                        <td><a href='Cancel.php?cancel_id=$row[student_id]' class='btn btn-danger'>Cancel Admission</a></td>
                    </tr>
                ";
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
                <strong>No Students Found !!</strong>
            </div>
            ';
        }
    }
?>