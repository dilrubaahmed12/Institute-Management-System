<?php
	include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<br>

<div class="row">
	<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_users.php"; ?>
	<div class="container col-md-4">
		<form method="POST">
			<div class="card">
				<?php 
					if($_SESSION['admin_count']==1 && $_GET['usertyp']==md5('Admin')){
						echo '
					        <div class="alert alert-danger alert-dismissible">
					            <button type="button" class="close" data-dismiss="alert">&times;</button>
					            User Cannot be <strong>Deleted !!</strong>
					        </div>
					        ';
					}else{
						echo '
							<div class="card-header">Are You Sure ??</div>
							<div class="card-body">
								<input type="submit" name="yes" class="btn btn-danger btn-block btn-lg" value="Yes"></input>
								<input type="submit" name="no" class="btn btn-primary btn-block btn-lg" value="No"></input>
							</div>
						';
					}
				?>
			</div>
		</form>
	</div>
</div>

<?php 
	if(isset($_POST['yes']) && isset($_GET['del_id'])){

		$sql = "DELETE FROM users WHERE user_id = '$_GET[del_id]'";
        
        if($conn->query($sql) === TRUE){
            echo 
            '<script>
                window.location = "deleteSuccessful.php"
            </script>';
        }
	}else if(isset($_POST['no'])){
		echo 
		'<script>
            window.location = "deleteuser.php"
        </script>';
	}
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>


