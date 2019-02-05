<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
?>

<?php
    function validate($data){
        global $conn;

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = $conn->real_escape_string($data);

        return $data;
    }
?>

<?php 
    function withdraw(){
        global $conn;

        $person = $_POST['person'];
        $wd = validate($_POST['wd']);
        $wd_date = validate($_POST['wd_date']);
        $ref = validate($_POST['ref']);

        if($person == 'Tahir Hasan Riddha'){
            $sql = "INSERT INTO accounts(cash_wd_riddha, cash_wd_riddha_date, cash_wd_riddha_ref) VALUES('$wd', STR_TO_DATE('$wd_date', '%d/%m/%Y'), '$ref')";
        }else if($person == 'Jumshadur Rahman Xihad'){
            $sql = "INSERT INTO accounts(cash_wd_xihad, cash_wd_xihad_date, cash_wd_xihad_ref) VALUES('$wd', STR_TO_DATE('$wd_date', '%d/%m/%Y'), '$ref')";
        }else if($person == 'Bappa Ghosh Shawon'){
            $sql = "INSERT INTO accounts(cash_wd_teacher1, cash_wd_teacher1_date, cash_wd_teacher1_ref) VALUES('$wd', STR_TO_DATE('$wd_date', '%d/%m/%Y'), '$ref')";
        }

        if($conn->query($sql) === TRUE){
            echo '
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Withdrawal Save <strong>Successful !!</strong>
            </div>
            ';

            $sms = "Cash Withdraw: ".$wd."\r\nWithdrawer: ".$person."\r\nWithdraw Date: ".$wd_date."\r\nThanks, ROOT Science Care." ;
            
            $sms_text = $_SERVER["DOCUMENT_ROOT"].'/RMS/SMSTemplate/'.'WithdrawSMS.txt';
            $handle = fopen($sms_text, 'w');
            fwrite($handle, $sms);
            fclose($handle);

            $file_name = $_SERVER["DOCUMENT_ROOT"].'/RMS/RMSSMSFiles/'.'Withdraw.txt';
            $handle = fopen($file_name, 'w');
            if($person == 'Bappa Ghosh Shawon'){
                $data = "01793690456\r\n01776445409\r\n01789114390\r\n01711965150\r\n" ;
            }
            else{
                $data = "01793690456\r\n01776445409\r\n01789114390\r\n" ;
            }
            fwrite($handle, $data);
            fclose($handle);

            $default = ini_get('max_execution_time');
            set_time_limit(1000);
            
            if(stristr($_SERVER['HTTP_USER_AGENT'], 'x64')){
                $cmd = "cd C:/Program Files (x86)/SMSCaster/ && smscaster.exe -Compose ".$file_name." ".$sms_text." -Long -Start";
            }else{
                $cmd = "cd C:/Program Files/SMSCaster/ && smscaster.exe -Compose ".$file_name." ".$sms_text." -Long -Start";
            }
            exec($cmd);

            set_time_limit($default);
        }else{
            echo '
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Withdrawal Save <strong>Failed !!</strong>
            </div>
            ';
        }
    }
?>

<br>
<div class="container-fluid">
    <div class="row">
        <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_administration.php"; ?>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Withdraw Money</div>
                <div class="card-body">
                    <?php
                        if(isset($_POST['save'])){
                            withdraw();
                        } 
                    ?>
                    <form method="POST">
                        <div class="form-group">
                            <label for="person">Withdrawer Name :</label>
                            <select name="person" class="form-control" id="person">
                                <option>Select</option>
                                <option>Tahir Hasan Riddha</option>
                                <option>Jumshadur Rahman Xihad</option>
                                <option>Bappa Ghosh Shawon</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="wd">Withdrawal Amount :</label>
                            <input type="text" class="form-control" id="wd" placeholder="Withdrawal Amount" name="wd" required>
                        </div>
                        <div class="form-group">
                            <label for="wd_date">Date :</label>
                            <input type="text" class="form-control" id="wd_date" value="<?php 
                                date_default_timezone_set('UTC');
                                echo date('d/m/Y');
                             ?>" name="wd_date" required>
                        </div>
                        <div class="form-group">
                            <label for="ref">Reference :</label>
                            <input type="text" class="form-control" id="v" placeholder="Reference" name="ref" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Save" name="save" class="btn btn-success btn-lg btn-block"></input>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>
<!--Prevent form re-submission-->
<script>
    if( window.history.replaceState ){
    window.history.replaceState( null, null, window.location.href );
}
</script>
<!--Date Picker -->
<script>
    $(document).ready(function(){
        $("#wd_date").datepicker({
             dateFormat: "dd/mm/yy",
             changeMonth: true,
             changeYear: true,
             yearRange: "1995:2030"
        });
    })
</script>