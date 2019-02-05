<?php 
	session_start();
	function fetch_data(){ 
		$output = $_SESSION['output'];     
	    return $output;  
	} 

	if(isset($_POST["create_pdf"])){  
		require_once($_SERVER['DOCUMENT_ROOT'].'/RMS/inc/tcpdf/tcpdf.php');  
		
		$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
		$obj_pdf->SetDefaultMonospacedFont('courier');  
		$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
		$obj_pdf->setPrintHeader(false);  
		$obj_pdf->setPrintFooter(false);  
		$obj_pdf->SetAutoPageBreak(TRUE, 10);  
		$obj_pdf->SetFont('courier', '', 10);  
		$obj_pdf->AddPage(); 

		$content = '';  
		$content .= '
		<h3 align="center">ROOT Science Care</h3> 
		<h4 align="center">Exam Result</h4>
		<h5 align="center">'.$_SESSION['program'].'</h5>
		<h5 align="center">Subject: '.$_SESSION['exam_sub'].' ('.$_SESSION['exam_name'].')</h5>
		<h5 align="center">Date: '.$_SESSION['exam_date'].'</h5>
		<br><br>
		<table border="1" cellspacing="0" cellpadding="5">  
		   <tr>  
		        <th width="15%">Merit Position</th>  
		        <th width="50%">Student Name</th>  
		        <th width="20%">Program Roll</th>  
		        <th width="15%">Marks</th>  
		   </tr>  
		';  
		$content .= fetch_data();  
		$content .= '</table>';  
		$obj_pdf->writeHTML($content);  
		$obj_pdf->Output($_SESSION['program'].'-'.$_SESSION['exam_sub'].'-'.$_SESSION['exam_name'].'-'.$_SESSION['exam_date'].'.pdf', 'I');  
	}  
?>
