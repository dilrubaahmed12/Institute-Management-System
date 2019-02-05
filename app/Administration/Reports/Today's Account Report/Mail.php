<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
        <div class="container col-md-10">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                <?php
                    if(!isset($_SESSION)) 
                    { 
                        session_start(); 
                    }
                    if(isset($_POST['send_mail'])){
                        require $_SERVER['DOCUMENT_ROOT']."/RMS/inc/PHPMailer-5.2-stable/PHPMailerAutoload.php";

                        $mail = new PHPMailer;

                        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

                        $mail->isSMTP();                                      // Set mailer to use SMTP
                        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true;                               // Enable SMTP authentication
                        $mail->Username = 'rootlitu@gmail.com';                 // SMTP username
                        $mail->Password = 'Rootscience1@';                           // SMTP password
                        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                        $mail->Port = 587;                                    // TCP port to connect to

                        $mail->setFrom('rootlitu@gmail.com', 'ROOT Science Care');
                        
                        if(in_array('Riddha', $_POST['mail_to'])){
                            $mail->addAddress('th.riddha@gmail.com');
                        }
                        if(in_array('Xihad', $_POST['mail_to'])){
                            $mail->addAddress('jumsmc20@gmail.com'); 
                        }

                        $mail->addReplyTo('rootlitu@gmail.com', 'ROOT Science Care');

                        $mail->isHTML(true);                                  // Set email format to HTML

                        $mail->Subject = 'Account Report';
                        $mail->Body    = $_SESSION['msg'];
                        $mail->AltBody = 'This mail is auto-generated from ROOT Management System';

                        if(!$mail->send()) {
                            echo '<div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                Mail Sending <strong>Failed !!</strong>
                            </div>';
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                        } else {
                            echo '<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                Mail Sent <strong>Successfully !!</strong>
                            </div>';
                        }
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
