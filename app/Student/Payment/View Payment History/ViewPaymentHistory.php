<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php"; ?>

<br>
<div class="row">
    <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>

    <div class="col-md-10">
    <!-- Student Search -->
        <form action="/RMS/app/Student/Payment/View Payment History/SearchPaymentHistory.php" method="POST">
            <div class="container d-flex justify-content-center">
                <div class="input-group mb-3 input-group-lg" style="width:70%;">
                    <input type="text" name="search" class="form-control" placeholder="Search Students by Name/ Roll/ Mobile No."></input>
                        <div class="input-group-append">
                            <input value="Search" class="btn btn-info" type="submit"></input> 
                        </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>