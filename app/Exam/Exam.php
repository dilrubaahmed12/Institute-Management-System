<?php 
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
    echo '<br>';
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_exam.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php";
    unset($_SESSION['exam_name']);
    unset($_SESSION['exam_date']);
    unset($_SESSION['subject']);
    unset($_SESSION['total_marks']);
    unset($_SESSION['std_id']);
    unset($_SESSION['std_name']);
    unset($_SESSION['exam_sub']);
    unset($_SESSION['program']);
?>