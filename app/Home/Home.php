<?php 
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>
        
    <br><br>
    <!-- Student Search -->
    <form action="/RMS/app/Student/SearchStudent.php" method="POST">
        <div class="container d-flex justify-content-center">
            <div class="input-group mb-3 input-group-lg" style="width:50%;">
                <input type="text" name="search" class="form-control" placeholder="Search Students by Name/ Roll/ Mobile No."></input>
                    <div class="input-group-append">
                        <input value="Search" class="btn btn-info" type="submit"></input> 
                    </div>
            </div>
        </div>
    </form>

    <br><br>

    <div class="container">
        <div class="jumbotron text-center">
            <h1 class="display-3">Welcome to<br>ROOT Science Care</h1>
        </div>
    </div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>