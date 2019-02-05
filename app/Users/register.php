<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
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
    function regi(){
        global $conn;
        $user = validate($_POST['user']);
        $pass = md5(validate($_POST['pass']));
        $conf_pass = md5(validate($_POST['conf_pass']));
        $user_type = md5($_POST['usr_typ']);

        $sql = "SELECT * FROM users WHERE user_name='$user' ";
        $result = $conn->query($sql);
        if($result->num_rows>0){
            echo '
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    User Name <strong>Already Taken !!</strong>
                </div>
                ';
        }else{
            $sql = "INSERT INTO users(user_name, password, user_type) VALUES('$user', '$pass', '$user_type')";

            if($pass==$conf_pass){
                if($conn->query($sql) === TRUE){
                echo '
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    User Registration <strong>Successful !!</strong>
                </div>
                ';
                }else{
                    echo '
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        User Registration <strong>Failed !!</strong>
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
    }
?>

<br>
<div class="container">
        <div class="container col-md-5">
            <div class="card">
                <div class="card-header">User Registration</div>
                <div class="card-body">
                    <?php
                        if(isset($_POST['register'])){
                            regi();
                        }
                    ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="user">User Name :</label>
                            <input type="text" class="form-control" id="user" placeholder="User Name" name="user" required>
                        </div>
                        <div class="form-group">
                            <label for="pass">Password :</label>
                            <input type="password" class="form-control" id="pass" placeholder="Password" name="pass" required>
                        </div>
                        <div class="form-group">
                            <label for="conpass">Confirm Password :</label>
                            <input type="password" class="form-control" id="conpass" placeholder="Confirm Password" name="conf_pass" required>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="usr_typ" value="Admin">Admin
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="usr_typ" value="Manager">Manager
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="usr_typ" value="Staff">Staff
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="usr_typ" value="Teacher">Teacher
                            </label>
                        </div>
                        <div>
                            <br>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Register" name="register" class="btn btn-info btn-lg btn-block"></input>
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
