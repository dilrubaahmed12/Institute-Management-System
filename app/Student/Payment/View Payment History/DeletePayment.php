<?php
	include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<br>

<div class="row">
	<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
	<div class="container col-md-4">
		<div class="card">
			<?php 
				$sql = "DELETE FROM payment WHERE payment_id = '$_GET[del_pay_id]'";
				if($conn->query($sql) === TRUE){
					echo '
				        <div class="alert alert-danger alert-dismissible">
				            <button type="button" class="close" data-dismiss="alert">&times;</button>
				            Payment <strong>Deleted !!</strong>
				        </div>
				        ';
				}
			?>
		</div>
	</div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>


