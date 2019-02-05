<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<?php
    function validate($data){
        global $conn;

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = $conn->real_escape_string($data);

        return $data;
    }
?>

<?php
    function change_pass(){
        global $conn;
        $user = $_SESSION['username'];
        $old_pass = md5(validate($_POST['oldpass']));
        $new_pass = md5(validate($_POST['newpass']));
        $conf_new_pass = md5(validate($_POST['confnewpass']));

        $sql = "SELECT * FROM users WHERE user_name='$user' AND password='$old_pass' ";
        $result = $conn->query($sql);

        if($result->num_rows == 1){
            while($row = $result->fetch_assoc()){
                $user_id = $row['user_id'];
                $sql = "UPDATE users SET password = '$new_pass' WHERE user_id = '$user_id' ";
                if($new_pass==$conf_new_pass){
                    if($conn->query($sql) === TRUE){
                        echo '
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Change Password <strong>Successful !!</strong>
                        </div>
                        ';
                    }else{
                        echo '
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Change Password <strong>Failed !!</strong>
                        </div>
                        ';
                    }
                }else{
                    echo '
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Password did not <strong>Match !!</strong>
                        </div>
                        ';
                }
            }
        }else{
            echo '
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Change Password <strong>Failed !!</strong>
            </div>
            ';
        }
    }
?>

<br>
<div class="container">
    <div class="container col-md-5">
        <div class="card">
            <div class="card-header">Change Password</div>
            <div class="card-body">
                <?php
                    if(isset($_POST['changepass'])){
                        change_pass();
                    }
                ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="pass">Old Password :</label>
                        <input type="password" class="form-control" id="pass" placeholder="Old Password" name="oldpass" required>
                    </div>
                    <div class="form-group">
                        <label for="newpass">New Password :</label>
                        <input type="password" class="form-control" id="newpass" placeholder="New Password" name="newpass" required>
                    </div>
                    <div class="form-group">
                        <label for="confnewpass">Confirm New Password :</label>
                        <input type="password" class="form-control" id="confnewpass" placeholder="Confirm New Password" name="confnewpass" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Change Password" name="changepass" class="btn btn-info btn-lg btn-block"></input>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
<!--Prevent form re-submission-->
<script>
    if( window.history.replaceState ){
    window.history.replaceState( null, null, window.location.href );
}
</script>
