<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
    
    unset($_SESSION['program']);
    unset($_SESSION['exam_name']);
    unset($_SESSION['exam_date']);
    unset($_SESSION['subject']);
    unset($_SESSION['total_marks']);
    unset($_SESSION['std_id']);
    unset($_SESSION['std_name']);
    unset($_SESSION['exam_sub']);
?>

<br>
<div class="container-fluid">
    <form method="POST" action="ResultData.php">
        <div class="row">
            <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_exam.php"; ?>
            <div class="container col-md-5">
                <div class="card">
                    <div class="card-header">View Exam Result</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="program">Program :</label>
                            <select name="program" class="form-control" id="program">
                                <option>Select</option>
                                <?php
                                    $sql = "SELECT program_name FROM programs";
                                    $result = $conn->query($sql);
                                ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['program_name']; ?>"><?php echo $row['program_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="class">Class :</label>
                            <select name="class" class="form-control" id="class">
                                <option>Select</option>
                                <option>VIII</option>
                                <option>IX</option>
                                <option>X</option>
                                <option>XI</option>
                                <option>XII</option>
                                <option>Admission</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="session">Session :</label>
                            <input type="text" class="form-control" id="session" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('Y'); ?>" name="session" required>
                        </div>
                        <div class="form-group">
                            <label for="exam_name">Exam Name :</label>
                            <select name="exam_name" class="form-control" id="exam_name">
                                <option>Select</option>
                                <option>Written</option>
                                <option>Creative</option>
                                <option>MCQ</option>
                                <option>Creative + MCQ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exam_date">Exam Date :</label>
                            <input type="text" class="form-control" id="exam_date" name="exam_date"  placeholder="dd/mm/yyyy">
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <select name="subject" class="form-control" id="subject" required>
                                <option>Select</option>
                                <option>Physics</option>
                                <option>Chemistry</option>
                                <option>Math</option>
                                <option>Biology</option>
                                <option>G. Math</option>
                                <option>H. Math</option>
                                <option>Bangla 1st</option>
                                <option>Bangla 2nd</option>
                                <option>English 1st</option>
                                <option>English 2nd</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="total_marks">Total Marks :</label>
                            <input type="text" class="form-control" id="total_marks" name="total_marks"  placeholder="Total Marks" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Show Marks" name="show" class="btn btn-info btn-lg btn-block"></input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<!--Prevent form re-submission-->
<script>
    if( window.history.replaceState ){
    window.history.replaceState( null, null, window.location.href );
}
</script>
<!--Date Picker-->
<script>
    $(document).ready(function(){
        $("#exam_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>