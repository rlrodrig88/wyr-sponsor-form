<?php

class PDF extends FPDF {
	// Page header
	function Header()
	{
	    // Logo
	    $this->Image('./wp-content/plugins/wyr-sponsor-form/images/pdf_logo.png',20,6,30);
	    // Arial bold 15
	    $this->SetFont('Arial','B',20);
	    // Move to the right
	    $this->Cell(80);
	    // Title
	    $this->Cell(30,10,"Where Ya' Rack Sponsorship",0,0,'C');
	    // Logo
	    $this->Image('./wp-content/plugins/wyr-sponsor-form/images/pdf_picture.jpg',165,6,0,30);
	    // Line break
	    $this->Ln(20);
	}
	function Footer()
	{
	    // Arial bold 15
	    $this->SetFont('Arial','',12);
	    // Title
	    $date = date('Y/m/d H:i:s');
	    $this->Cell(0,10,$date,0,0,'C');
	    // Line break
	    $this->Ln(20);
	}
}

$pdf = new PDF();
$pdf->AddPage();
// Sponsorship Information
$pdf->Ln(10);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Sponsor Information:",0,1,'C');
$pdf->Cell(10,7,"",0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,7,"Name: ",0,0);	
$pdf->SetFont('Arial','',12);		
$pdf->Cell(30,7,$_SESSION['post-data']['nameFirst'],'B',0);
$pdf->Cell(50,7,$_SESSION['post-data']['nameLast'],'B',1);
$pdf->Cell(10,7,"",0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,7,"Business: ",0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,7,$_SESSION['post-data']['business'],'B',1);
$pdf->Cell(10,7,"",0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,7,"Email: ",0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,7,$_SESSION['post-data']['email'],'B',1);
$pdf->Cell(10,7,"",0,0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,7,"Phone: ",0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,7,$_SESSION['post-data']['phone'],'B',1);
$pdf->Ln(10);
// Rack Information
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Rack Information:",0,1,'C');	
$pdf->SetFont('Arial','B',12);	
$pdf->Cell(40,7,"Hitch Post Racks: ",0,0);	
$pdf->SetFont('Arial','',12);
$pdf->Cell(10,7,$_SESSION['post-data']['hitch-post-quantity'],'B',0,'C');
$pdf->Cell(10,7,"",0,0);		
$pdf->SetFont('Arial','B',12);	
$pdf->Cell(20,7,"Corrals: ",0,0);
$pdf->SetFont('Arial','',12);	
$pdf->Cell(10,7,$_SESSION['post-data']['corral-quantity'],'B',1,'C');	
$pdf->Ln(10);
// Rack Location	
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Rack Quantities:",0,1,'C');
$pdf->SetFont('Arial','B',12);	
$pdf->Cell(40,7,"Address: ",0,0);	
$pdf->SetFont('Arial','',12);
$pdf->Cell(70,7,$_SESSION['post-data']['location-address'],'B',0);	
$pdf->Cell(30,7,$_SESSION['post-data']['location-city'],'B',0);	
$pdf->Cell(20,7,$_SESSION['post-data']['location-state'],'B',0);
$pdf->Cell(30,7,$_SESSION['post-data']['location-zip'],'B',1);
$pdf->SetFont('Arial','B',12);	
$pdf->Cell(40,7,"Area Description: ",0,0);	
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,7,$_SESSION['post-data']['area-description'],'B',1);
$pdf->SetFont('Arial','B',12);	
$pdf->Cell(40,7,"Location Type: ",0,0);	
$pdf->SetFont('Arial','',12);
$pdf->Cell(20,7,$_SESSION['post-data']['public-private'],'B',1);
$pdf->SetFont('Arial','B',12);	
$pdf->Cell(40,7,'Property Owner: ',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(40,7,$_SESSION['post-data']['property-owner'],'B',1);		
$pdf->Ln(10);
// Plaque Information
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Plaque Information:",0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,7,"Description: ",0,0);	
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,7,$_SESSION['post-data']['plaque-description'],'B',1);
$pdf->Ln(10);
// Payment Information
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Payment: ",0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,7,"Payment Type: ",0,0);	
$pdf->SetFont('Arial','',12);
$pdf->Cell(20,7,$_SESSION['post-data']['payment-type'],'B',1);	
$pdf->Ln(30);
// Output File
$pdf->Output('F' ,'./wp-content/plugins/wyr-sponsor-form/temp/new-sponsorship.pdf');

// Email completed sponsor form
$to = "rlrodrig88@gmail.com";
$subject = "New WYR Sponsorship!";
$message = $_SESSION['post-data']['nameFirst'] . " " . $_SESSION['post-data']['nameLast'] . " has decided to sponsor a rack!";
// array of all uploaded files
$files = scandir('./wp-content/plugins/wyr-sponsor-form/temp/');
// create an array of attachments with pathnames 
$attachments = array();
foreach($files as $value) {
	array_push($attachments, './wp-content/plugins/wyr-sponsor-form/temp/' . $value);
}
wp_mail($to, $subject, $message, '', $attachments);

?>
