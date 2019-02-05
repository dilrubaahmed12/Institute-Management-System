<?php
    include $_SERVER['DOCUMENT_ROOT']."/RMS/config/db.php";
    include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/header.php";
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
?>

<?php  
    function show_rcvbable_amount($std_id){
        global $conn;

        $sql = "SELECT program, subjects, date_of_admission FROM students WHERE student_id='$std_id' ";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        $prg = $data['program'];
        $taken_subs = explode(" ", $data['subjects']);
        $doa = $data['date_of_admission'];

        mysqli_free_result($result);
        $data = NULL;

        $sql = "SELECT subjects, payment_type, course_fee, fee_per_subject, admission_fee, extra_fee FROM programs WHERE program_name='$prg' ";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        $all_subs = explode(" ", $data['subjects']);
        $fee = explode(",", $data['fee_per_subject']);
        
        if($data['payment_type'] == 'Monthly'){
            $total_fee = 0;
            for($i=0; $i<sizeof($taken_subs); $i++){
                for($j=0; $j<sizeof($all_subs); $j++){
                    if($taken_subs[$i] == $all_subs[$j]){
                        $total_fee += $fee[$j];
                    }
                }
            }

            date_default_timezone_set('UTC');
            if($doa == date('Y-m-d')){
                $total_fee += $data['admission_fee'] + $data['extra_fee'];
            }else{
                $total_fee += $data['extra_fee'];
            }
        }else if($data['payment_type'] == 'Course'){
            $total_fee = $data['course_fee'];
            date_default_timezone_set('UTC');
            if($doa == date('Y-m-d')){
                $total_fee += $data['admission_fee'] + $data['extra_fee'];
            }else{
                $total_fee += $data['extra_fee'];
            }
        }

        echo $total_fee;
    }
?>

<?php 
    $sql = "SELECT * FROM payment WHERE payment_id='$_GET[pay_id]' ";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();

    $std_id = $row['student_id'];
    $pay_month = $row['payment_for_month'];
    $pay_year = $row['payment_year'];
    $pay_date = date_format(date_create($row['payment_date']), 'd/m/Y');
    $paid_amount = $row['paid_amount'];
    $discount = $row['discount_amount'];
    $due = $row['due_amount'];
    $next_payment_date = date_format(date_create($row['next_payment_date']), 'd/m/Y');
    $ref = $row['reference'];

    $sql = "SELECT * FROM students WHERE student_id='$std_id' ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
        
    $student_name = $row['first_name'].' '.$row['last_name'];
    $class = $row['class'];
    $sess = $row['session'];
    $program = $row['program'];
    $subs = $row['subjects'];
    $prog_roll = $row['program_roll'];
    $bDay = $row['batch_day'];
    $bTime = $row['batch_time'];
    $doa = $row['date_of_admission'];

    $sql = "SELECT payment_type FROM programs WHERE program_name='$program' ";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    $pay_type = $data['payment_type'];         
?>

<br>
<div class="container-fluid">
    <div class="row">
    <?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/helper/side_menu_student.php"; ?>
    <div class="card">
        <div class="card-header">Invoice</div>
        <div class="card-body">
            <div class="col text-center">
                <div class="btn btn-lg btn-success" id="print">Print</div>&nbsp;&nbsp;
            </div>
            <br>
            <div id="moneyReceipt">
            <div style="width: 800px; height: 450.24px; padding-left: 2px; padding-right: 2px; font-size: 11px; font-family: Verdana, Geneva, sans-serif; overflow: hidden; border: none; margin-top: 0px; padding-top: 0px;">
                <table style="width: 99%; height: 99%; border:none" cellpadding="0" cellspacing="0">
                    <tr style="height: 21%;">
                        <td>
                            <div style="text-align: center; font-size: 14px;">
                                <div style="float: left; width: 94%;">
                                    <div style="text-align: center; font-size: 25px; padding-bottom: 6px;font-weight: bold;">
                                        ROOT Science Care
                                    </div>
                                    <div style="text-align: center; font-size: 16px;">
                                        <div style="font-weight: bold;"> Invoice (Student Copy) #<?php echo $prog_roll.$_GET['pay_id']; ?></div>
                                    </div>
                                </div>
                                <div style="float: left; width: 16%;">   
                                    <div style="clear: both;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr style="height: 61%; border-top: 1px solid #000; ">
                        <td>
                            <div class="row" style="">
                                <div style="float: left; width: 59%; ">
                                    <div style="text-align: left; padding-left: 5.7%; padding-right: 2%; font-size: 12px; padding-top: 1px;">
                                        <table style="font-size: 12px; line-height: 15px; font-family: Verdana, Geneva, sans-serif; width:100%;">
                                            <tr>
                                                <td style="width: 32%; vertical-align: top;">
                                                    Student Name
                                                </td>
                                                <td style="width: 2%; vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="width: 66%; vertical-align: top;">
                                                    <?php echo $student_name; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Class
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $class; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Program
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $program; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Program Roll
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $prog_roll; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Subjects
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $subs; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Session
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $sess; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Batch Day
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $bDay; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Batch Time
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $bTime; ?>
                                                </td>
                                            </tr>
                                            <?php 
                                            date_default_timezone_set('UTC');
                                                if($doa == date('Y-m-d')){
                                                    echo '
                                                    <tr>
                                                        <td>Date of Admission</td>
                                                        <td>:</td>
                                                        <td>'
                                                            .date_format(date_create($doa), 'd/m/Y').'
                                                        </td>
                                                    </tr>';
                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                    <div style="float: left; width: 2%; ">
                        &nbsp;
                    </div>
                    <div style="float: left; width: 39%; ">
                        <div style="text-align: left;  padding-left: 5.7%; padding-right: 2%; font-size: 12px; padding-top: 1px;">
                            <table style="font-size: 12px; line-height: 15px; font-family: Verdana, Geneva, sans-serif; width:100%;">
                                <?php
                                    if($pay_type == 'Monthly'){
                                        echo '
                                        <tr>
                                            <td style="width: 58%; vertical-align: top;">
                                                Payment for Month
                                            </td>
                                            <td style="width: 2%; vertical-align: top;">
                                                :
                                            </td>
                                            <td style="width: 40%; vertical-align: top;">
                                                <span>'.$pay_month.', '.$pay_year.'</span>
                                            </td>
                                        </tr>';
                                    }
                                ?>
                                <tr>
                                    <td style="width: 58%; vertical-align: top;">
                                        Total Payable
                                    </td>
                                    <td style="width: 2%; vertical-align: top;">
                                        :
                                    </td>
                                    <td style="width: 40%; vertical-align: top;">
                                        <span></span>
                                        <span><?php show_rcvbable_amount($std_id); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 58%; vertical-align: top;">
                                        Discount
                                    </td>
                                    <td style="width: 2%; vertical-align: top;">
                                        :
                                    </td>
                                    <td style="width: 40%; vertical-align: top;">
                                        <span></span>
                                        <span><?php if($discount==0) echo '0'; else echo $discount; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top; font-weight:bold;">
                                        Paid Amount
                                    </td>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        :
                                    </td>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        <span><?php echo $paid_amount; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        Due Amount
                                    </td>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        :
                                    </td>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        <span><?php echo $due; ?></span>
                                    </td>
                                </tr>
                                <?php 
                                    if($ref != ''){
                                        echo '
                                            <tr>
                                                <td>
                                                    Reference
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <span>'.ucwords($ref).'</span>
                                                </td>
                                            </tr>
                                        ';
                                    }
                                ?>
                                <tr>
                                    <td>
                                        Payment Date
                                    </td>
                                    <td>
                                        :
                                    </td>
                                    <td>
                                        <span><?php echo $pay_date; ?></span>
                                    </td>
                                </tr>
                                <?php
                                    if($next_payment_date != '30/11/-0001'){
                                        echo '
                                            <tr>
                                                <td>
                                                    Next Payment Date
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <span>'.$next_payment_date.'</span>
                                                </td>
                                            </tr>
                                        ';
                                    } 
                                ?>
                        </table>

                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </td>
        </tr>
                <tr style="height: 8%; border-bottom: 1px solid #000;">
                    <td>
                        <div style="border-top: 1px solid #000000; padding-top: 1px; margin-left: 500px; width: 200px; margin-right: 25px; text-align: center; font-size: 12px; font-weight: bold;"><?php echo ucwords($_SESSION['username']); ?> </div>
                    </td>
                </tr>
                <tr style="height: 10%;">
                    <td style="padding-left: 1.5%; padding-right: 1.5%; font-size: 11px; text-align: center;">
                        <b>Address: </b>Sector # 6, Road # 3, House # 8 (1st Floor, Left Side), House Building, Uttara, Dhaka-1230 <br>
                        <b>Contact: </b>01789114390
                    </td>
                </tr>
            </table>
            </div>

            <div style="width: 800px; height: 450.24px; padding-left: 2px; padding-right: 2px; font-size: 11px; font-family: Verdana, Geneva, sans-serif; overflow: hidden; border: none; margin-top: 120px; padding-top: 0px;">
                <table style="width: 99%; height: 99%; border:none" cellpadding="0" cellspacing="0">
                    <tr style="height: 21%;">
                        <td>
                            <div style="text-align: center; font-size: 14px;">
                                <div style="float: left; width: 94%;">
                                    <div style="text-align: center; font-size: 25px; padding-bottom: 6px;font-weight: bold;">
                                        ROOT Science Care
                                    </div>
                                    <div style="text-align: center; font-size: 16px;">
                                        <div style="font-weight: bold;"> Invoice (Office Copy) #<?php echo $prog_roll.$_GET['pay_id']; ?></div>
                                    </div>
                                </div>
                                <div style="float: left; width: 16%;">   
                                    <div style="clear: both;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr style="height: 61%; border-top: 1px solid #000; ">
                        <td>
                            <div class="row" style="">
                                <div style="float: left; width: 59%; ">
                                    <div style="text-align: left; padding-left: 5.7%; padding-right: 2%; font-size: 12px; padding-top: 1px;">
                                        <table style="font-size: 12px; line-height: 15px; font-family: Verdana, Geneva, sans-serif; width:100%;">
                                            <tr>
                                                <td style="width: 32%; vertical-align: top;">
                                                    Student Name
                                                </td>
                                                <td style="width: 2%; vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="width: 66%; vertical-align: top;">
                                                    <?php echo $student_name; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Class
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $class; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Program
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $program; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Program Roll
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $prog_roll; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Subjects
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $subs; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Session
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $sess; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Batch Day
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $bDay; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;">
                                                    Batch Time
                                                </td>
                                                <td style="vertical-align: top;">
                                                    :
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <?php echo $bTime; ?>
                                                </td>
                                            </tr>
                                            <?php 
                                            date_default_timezone_set('UTC');
                                                if($doa == date('Y-m-d')){
                                                    echo '
                                                    <tr>
                                                        <td>Date of Admission</td>
                                                        <td>:</td>
                                                        <td>'
                                                            .date_format(date_create($doa), 'd/m/Y').'
                                                        </td>
                                                    </tr>';
                                                }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                    <div style="float: left; width: 2%; ">
                        &nbsp;
                    </div>
                    <div style="float: left; width: 39%; ">
                        <div style="text-align: left;  padding-left: 5.7%; padding-right: 2%; font-size: 12px; padding-top: 1px;">
                            <table style="font-size: 12px; line-height: 15px; font-family: Verdana, Geneva, sans-serif; width:100%;">
                                <?php
                                    if($pay_type == 'Monthly'){
                                        echo '
                                        <tr>
                                            <td style="width: 58%; vertical-align: top;">
                                                Payment for Month
                                            </td>
                                            <td style="width: 2%; vertical-align: top;">
                                                :
                                            </td>
                                            <td style="width: 40%; vertical-align: top;">
                                                <span>'.$pay_month.', '.$pay_year.'</span>
                                            </td>
                                        </tr>';
                                    }
                                ?>
                                <tr>
                                    <td style="width: 58%; vertical-align: top;">
                                        Total Payable
                                    </td>
                                    <td style="width: 2%; vertical-align: top;">
                                        :
                                    </td>
                                    <td style="width: 40%; vertical-align: top;">
                                        <span></span>
                                        <span><?php show_rcvbable_amount($std_id); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 58%; vertical-align: top;">
                                        Discount
                                    </td>
                                    <td style="width: 2%; vertical-align: top;">
                                        :
                                    </td>
                                    <td style="width: 40%; vertical-align: top;">
                                        <span></span>
                                        <span><?php if($discount==0) echo '0'; else echo $discount; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top; font-weight:bold;">
                                        Paid Amount
                                    </td>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        :
                                    </td>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        <span><?php echo $paid_amount; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        Due Amount
                                    </td>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        :
                                    </td>
                                    <td style="vertical-align: top; font-weight: bold;">
                                        <span><?php echo $due; ?></span>
                                    </td>
                                </tr>
                                <?php 
                                    if($ref != ''){
                                        echo '
                                            <tr>
                                                <td>
                                                    Reference
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <span>'.ucwords($ref).'</span>
                                                </td>
                                            </tr>
                                        ';
                                    }
                                ?>
                                <tr>
                                    <td>
                                        Payment Date
                                    </td>
                                    <td>
                                        :
                                    </td>
                                    <td>
                                        <span><?php echo $pay_date; ?></span>
                                    </td>
                                </tr>
                                <?php
                                    if($next_payment_date != '30/11/-0001'){
                                        echo '
                                            <tr>
                                                <td>
                                                    Next Payment Date
                                                </td>
                                                <td>
                                                    :
                                                </td>
                                                <td>
                                                    <span>'.$next_payment_date.'</span>
                                                </td>
                                            </tr>
                                        ';
                                    } 
                                ?>
                        </table>

                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </td>
        </tr>
                <tr style="height: 8%; border-bottom: 1px solid #000;">
                    <td>
                        <div style="border-top: 1px solid #000000; padding-top: 1px; margin-left: 500px; width: 200px; margin-right: 25px; text-align: center; font-size: 12px; font-weight: bold;"><?php echo ucwords($_SESSION['username']); ?> </div>
                    </td>
                </tr>
                <tr style="height: 10%;">
                    <td style="padding-left: 1.5%; padding-right: 1.5%; font-size: 11px; text-align: center;">
                        <b>Address: </b>Sector # 6, Road # 3, House # 8 (1st Floor, Left Side), House Building, Uttara, Dhaka-1230 <br>
                        <b>Contact: </b>01789114390
                    </td>
                </tr>
            </table>
        </div>
        </div>
        </div>
    </div>
</div>
    
<?php include $_SERVER['DOCUMENT_ROOT']."/RMS/inc/footer.php"; ?>

<script type="text/javascript" src="/RMS/inc/js/printThis.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#print").click(function () {
            $("#moneyReceipt").printThis({
                importCSS: true,             // import page CSS
                importStyle: true,           // import style tags
                header: null,                // prefix to html
                footer: null,                // postfix to html
            });
        });
    });
</script>
