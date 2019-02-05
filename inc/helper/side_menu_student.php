<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
?>

<div class="col-md-2">
	<div class="card">
		<div class="card-body">
			<div class="list-group">
				<a href="/RMS/app/Student/Student.php" class="list-group-item active list-group-item-action">Student</a>

				<a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#adm">Admission</a>
                    <a id="adm" class="collapse" href="/RMS/app/Student/Admission/New Admission/NewAdmission.php">New Admission</a>
                    <a id="adm" class="collapse" href="/RMS/app/Student/Admission/Cancel Admission/CancelAdmission.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>Cancel Admission</a>

				<a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#pmnt">Payment</a>
					<a id="pmnt" class="collapse" href="/RMS/app/Student/Payment/Make Payment/Payment.php">Make Payment</a>
					<a id="pmnt" class="collapse" href="/RMS/app/Student/Payment/Clear Previous Due/ClearPreviousDue.php">Clear Previous Due</a>
                    <a id="pmnt" class="collapse" href="/RMS/app/Student/Payment/Due Payment List/DuePaymentList.php">Due Payment List</a>
                    <a id="pmnt" class="collapse" href="/RMS/app/Student/Payment/View Payment History/ViewPaymentHistory.php">View Payment History</a>

				<a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#sms">SMS</a>
					<a id="sms" class="collapse" href="/RMS/app/Student/SMS/Class-Notice SMS/ClassNoticeSMS.php">Class Schedule SMS</a>
					<a id="sms" class="collapse" href="/RMS/app/Student/SMS/Due SMS/DueSMS.php">Due SMS</a>
					<a id="sms" class="collapse" href="/RMS/app/Student/SMS/Exam Result SMS/ExamSMS.php">Exam Result SMS</a>
					<a id="sms" class="collapse" href="/RMS/app/Student/SMS/Absent SMS/AbsentSMS.php">Absent SMS</a>
					<a id="sms" class="collapse" href="/RMS/app/Student/SMS/Notice SMS/NoticeSMS.php">Notice SMS</a>

				<a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#attendance">Attendance</a>
					<a id="attendance" class="collapse" href="/RMS/app/Student/Attendance/TakeAttendance.php">Take Attendance</a>
					<a id="attendance" class="collapse" href="/RMS/app/Student/Attendance/ViewAttendance.php">View Attendance</a>

				<a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#stddet">Student Details</a>
                    <a id="stddet" class="collapse" href="/RMS/app/Student/Student Details/Batch-wise Student List/BatchwiseStudentList.php">Batch-wise Student List</a>
                    <a id="stddet" class="collapse" href="/RMS/app/Student/Student Details/Search Students by Categories/SearchStudentsbyCategories.php">Search Students by Categories</a>
                    <a id="stddet" class="collapse" href="/RMS/app/Student/Student Details/Update-Delete Students/UpdateDeleteStudents.php">Update/Delete Students</a>
                    <a id="stddet" class="collapse" href="/RMS/app/Student/Student Details/Update Bulk Students/UpdateBulk.php">Update Bulk Students</a>
					
				<a href="#" class="list-group-item list-group-item-action" hidden>Visitor Entry</a>
			</div>
		</div>
	</div>
</div>