<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    if(empty($_SESSION)){
        header('Location: /RMS/index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RMS</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/RMS/inc/css/bootstrap.min.css">
        <link rel="stylesheet" href="/RMS/inc/css/jquery-ui.css">
        <script src="/RMS/inc/js/jquery.js"></script>
        <script src="/RMS/inc/js/bootstrap.min.js"></script>
        <script src="/RMS/inc/js/popper.js"></script>
        <script src="/RMS/inc/js/jquery-ui.js"></script>
    </head>

    <body>

        <nav class="navbar navbar-expand-md bg-dark navbar-dark justify-content-center">
            <!--Brand Logo-->
            <a class="navbar-brand" href="#">
                <img src="/RMS/inc/image/rsc.jpg" alt="logo" style="width:50px;">
            </a>
            <!--Links-->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/RMS/app/Home/Home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/RMS/app/Student/Student.php">Student</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/RMS/app/Exam/Exam.php">Exam</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" hidden>Teacher</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" hidden>Staff</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/RMS/app/Administration/Administration.php">Administration</a>
                </li>
                <li class="nav-item" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>
                    <a class="nav-link" href="/RMS/app/Users/users.php">Users</a>
                </li>
                <li class="nav-item" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>
                    <a class="nav-link" href="/RMS/app/Settings/Settings.php">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/RMS/app/Users/ChangePassword.php">Change Password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success" href="#"><strong><?php echo ucwords($_SESSION['username']); ?></strong></a>
                </li>
            </ul>
            <!--Logout-->
            <form method="POST">
                <input type="submit" class="btn btn-danger" name="logout" value="Logout"></input>
            </form>
        </nav>

        <div class="card bg-light text-dark">
            <div class="card-body"><?php echo substr(getcwd(), 23); ?></div>
        </div>

<?php
    if(isset($_POST['logout'])){
        session_destroy();
        header('Location: /RMS/index.php');
    }
?>