<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<?php
    
?>
<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_settings.php"; ?>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Take Backup</div>
                <div class="card-body">
                    <div class="text-center">
                    	<a href="backup.php" class="btn btn-success btn-lg">Take Backup</a>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
