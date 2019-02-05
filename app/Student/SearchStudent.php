<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
?>
	
<div class="container">
	<?php search_student(); ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<?php
    function search_student(){

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
                            <th width="5%">Contact No.</th>
                            <th>Program Roll</th>
                            <th width="15%">Program</th>
                            <th width="10%">Subjects</th>
                            <th width="15%">Batch Day</th>
                            <th>Batch Time</th>
                            <th width="5%">Status</th>
                            <th>Edit</th>
                            <th '; if($_SESSION['user_type']!=md5('Admin')){echo "hidden";} echo'>Delete</th>
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
                        <td><a href='Student Details/Update-Delete Students/UpdateStudent.php?edit_id=$row[student_id]' class='btn btn-info'>Edit</a></td>
                        <td "; if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} echo"><a href='Student Details/Update-Delete Students/DeleteStudent.php?del_id=$row[student_id]' class='btn btn-danger'>Delete</a></td>
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