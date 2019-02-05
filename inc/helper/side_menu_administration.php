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
				<a href="/RMS/app/Administration/Administration.php" class="list-group-item active list-group-item-action">Administration</a>
				
				<a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#acc">Accounts</a>
					<a id="acc" class="collapse" href="/RMS/app/Administration/Accounts/Account Summary/AccountSummary.php">Account Summary</a>
                    <a id="acc" class="collapse" href="/RMS/app/Administration/Accounts/Expense/Expense.php">Expense</a>
                    <a id="acc" class="collapse" href="/RMS/app/Administration/Accounts/Expense History/ExpenseHistory.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>Expense History</a>
				    <a id="acc" class="collapse" href="/RMS/app/Administration/Accounts/Income/Income.php">Income</a>
				    <a id="acc" class="collapse" href="/RMS/app/Administration/Accounts/Income History/IncomeHistory.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>Income History</a>
				    <a id="acc" class="collapse" href="/RMS/app/Administration/Accounts/Withdrawal History/WithdrawalHistory.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>Withdrawal History</a>
				
				<a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#reports">Reports</a>
					<a id="reports" class="collapse" href="/RMS/app/Administration/Reports/Today's Account Report/TodayAccountReport.php">Today's Account Report</a>
					<a id="reports" class="collapse" href="/RMS/app/Administration/Reports/Datewise Payment Report/DatewisePaymentReport.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>Date-wise Payment Report</a>
                    <a id="reports" class="collapse" href="/RMS/app/Administration/Reports/Monthwise Payment Report/MonthwisePaymentReport.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>Month-wise Payment Report</a>
                    <a id="reports" class="collapse" href="/RMS/app/Administration/Reports/Batchwise Payment Report/BatchwisePaymentReport.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>Batch-wise Payment Report</a>
                    <a id="reports" class="collapse" href="/RMS/app/Administration/Reports/Subjectwise Payment Report/SubjectwisePaymentReport.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>Subject-wise Payment Report</a>
                    
				<a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#manprg">Manage Programs</a>
                    <a id="manprg" class="collapse" href="/RMS/app/Administration/Manage Programs/Create New Program/CreateNewProgram.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>Create New Program</a>
				    <a id="manprg" class="collapse" href="/RMS/app/Administration/Manage Programs/View-Edit-Delete Program/ViewEditDeleteProgram.php" <?php if($_SESSION['user_type']!=md5('Admin')){echo 'hidden';} ?>>View/Edit/Delete Programs</a>
				    <a id="manprg" class="collapse" href="/RMS/app/Administration/Manage Programs/Transfer Bulk Students/transfer.php">Transfer Bulk Students</a>
			</div>
		</div>
	</div>
</div>
