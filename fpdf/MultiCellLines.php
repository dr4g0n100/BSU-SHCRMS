<?PHP
include "fpdf.php";
/** Extensions to FPDF */

$ActMultiform=null;		// Bad bad bad bad Global variable to our fpdf thingy

class ModifiedPDF extends FPDF
	{
	/*function Header()
		{
		Global $ActMultiform;
		$ActMultiform->Header();	// Your favorite function to create header stuff
		}

	function Footer()
		{
		Global $ActMultiform;
		$ActMultiform->Footer();	// Your favorite header to create the bottom lines
		}*/

	/**
	 * Pretty much copy of original MultiCell. But won't output anything, just return number of lines.
	 * return number of lines
	 */
	function MultiCellLines($w, $h, $txt, $border=0, $align='J', $fill=false)
		{
		//Output text with automatic or explicit line breaks
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 && $s[$nb-1]=="\n")
			$nb--;
		$NumLines=0;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$ns=0;
		$nl=1;
		while($i<$nb)
		{
			//Get next character
			$c=$s[$i];
			if($c=="\n")
			{
				//Explicit line break
				if($this->ws>0)	$this->ws=0;
				$NumLines++;
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				continue;
			}
			if($c==' ')
			{
				$sep=$i;
				$ls=$l;
				$ns++;
			}
			$l+=$cw[$c];
			if($l>$wmax)
			{
				//Automatic line break
				if($sep==-1)
				{
					if($i==$j)
						$i++;
					if($this->ws>0)	$this->ws=0;
					$NumLines++;
				}
				else
				{
					if($align=='J')	$this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
					$NumLines++;
					$i=$sep+1;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
			}
			else
				$i++;
		}
		//Last chunk
		$NumLines++;
		return $NumLines;
		}

	}
?>