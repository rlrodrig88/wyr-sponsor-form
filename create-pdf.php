<?php

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,$_SESSION['post-data']['nameFirst']);
// Output File
$pdf->Output('F' ,'./wp-content/plugins/wyr-sponsor-form/temp/new-sponsorship.pdf');

?>
