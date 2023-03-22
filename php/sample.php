<?php
require_once('../fpdf/fpdf.php');

class PDF extends FPDF
{
    protected $col = 0;

    function SetCol($col)
    {   

        // Move position to a column
        $this->col = $col;
        $x = 10 + $col*65;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    function AcceptPageBreak()
    {   
        if($this->PageNo() > 1){
            $this->Line(60,5,60,330);
            $this->Line(160,5,160,330);
        }
        
        if($this->col<2)
        {
            // Go to next column
            $this->SetCol($this->col+1);
            $this->SetY(10);
            return false;
        }
        else
        {
            // Go back to first column and issue page break
            $this->SetCol(0);
            return true;
        }
    }

}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
for($i=1;$i<=300;$i++)
    $pdf->Cell(0, 5, "Line $i", 0, 1);
$pdf->Output();

?>