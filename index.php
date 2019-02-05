<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    
    function validate($data){
        global $conn;

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = $conn->real_escape_string($data);

        return $data;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="inc/css/bootstrap.min.css">
        <script src="inc/js/jquery.js"></script>
        <script src="inc/js/bootstrap.min.js"></script>
		<script src="inc/js/popper.js"></script>
    </head>

    <body>
        <!-- Heading -->
        <div class="container">
            <div class="jumbotron text-center">
                <h1 class ="display-2">ROOT Management System</h1>
            </div>
        </div>
        <!-- Login Form -->
        <div class="container">
            <form method="post">
            	<div class="card">
	                <div class="card-header text-center">Login</div>
	                <div class="card-body">
                        <?php
                            session_start();
                            if(isset($_POST['login'])){ 
                                $user = validate($_POST['username']);
                                $pass = md5(validate($_POST['password']));
                                
                                $sql = "SELECT * FROM users WHERE user_name='$user' AND password='$pass'";
                                $result = $conn->query($sql);

                                if($result->num_rows == 1){
                                    $row = $result->fetch_assoc();
                                    $_SESSION['username'] = $user;
                                    $_SESSION['password'] = $pass;
                                    $_SESSION['user_type'] = $row['user_type'];
                                    header('Location: app/Home/Home.php');
                                }else{
                                    echo '
                                        <div class="container col-md-4 text-center alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            <strong>Incorrect Username or Password !!</strong>
                                        </div> ';
                                }
                            }
                        ?>
	                    <div class="row">
	                        <div class="col-md-4"></div>
		                        <div class="col-md-4">
	                                <div class="form-group">
	                                    <label for="usr">Username :</label>
	                                    <input type="text" class="form-control form-control-lg" id="usr" placeholder="Enter Username" name="username" required> 
	                                </div>
	                                <div class="form-group">
	                                    <label for="pwd">Password :</label>
	                                    <input type="password" class="form-control form-control-lg" id="pwd" placeholder="Enter Password" name="password" required>
	                                </div>
	                                <div class="form-group">
	                                    <input type="submit" value="Login" name="login" class="btn btn-dark btn-lg btn-block"></input>
	                                </div>
		                        </div>
	                        <div class="col-md-4"></div>
	                    </div>
	                </div>
            	</div>
            </form>
        </div>
    </body>
</html>

<script>
    if( window.history.replaceState ){
    window.history.replaceState( null, null, window.location.href );
}
</script>