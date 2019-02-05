<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
?>

<div class="container">
    <?php show_user(); ?>
</div>

<?php
    function show_user(){

        global $conn;
        $sql = "SELECT user_id, user_name, user_type FROM users ORDER BY user_type";

        $result = $conn->query($sql);
        
        $_SESSION['user_num'] = $result->num_rows;
        $_SESSION['admin_count'] = 0;
        
        if($result->num_rows > 0){
            echo '
            <br>
            <div class="container col-md-5">
            <div class="card">
            <div class="card-header">Total '.$result->num_rows.' user(s) found</div>
                <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                <tbody>
            ';
            while($row = $result->fetch_assoc()){
                echo "
                    <tr>
                        <td>$row[user_name]</td>
                        <td><a href='dlt.php?del_id=$row[user_id]&usertyp=$row[user_type]' class='btn btn-danger'>Delete</a></td>
                    </tr>
                ";
                if($row['user_type']==md5('Admin')){
                    $_SESSION['admin_count']++;
                }
            } 
            echo "</tbody>
                </table>
                </div>
                </div>
                </div>";
        }else{
            echo '
            <br>
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>No User(s) Found !!</strong>
            </div>
            ';
        }
    }
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>