<?php

namespace App\Services\PDF;
use Anouar\Fpdf\Fpdf as FPDF;

class PDFMCCetakSertifikatQA extends FPDF{

	public function setData($data)
	{

	}

	function Header()
	{
		$this->Image(app_path('Services/PDF/images/telkom-logo-text.jpg'),160,5,40); 
		$this->Image(app_path('Services/PDF/images/tth-logo-opacity.jpg'),50,100,120);
	}

	function Footer()
	{
		$this->SetY(-54);
		$this->SetFont('helvetica','',8);
		$this->Cell(0,5,"PT Telkom Indonesia (Persero) Tbk - Telkom        House",0,0,'C');
		$this->SetX(58);$this->SetTextColor(216,33,41);
		$this->Cell(0,5,"Test",0,0,'C');
		$this->Ln(4);$this->SetTextColor(0,0,0);
		$this->Cell(0,5,"Jl. Gegerkalong Hilir No. 47 Bandung 40152 INDONESIA | Customer Service: (+62) 812-2483-7500; E-Mail:                              ",0,0,'C');
		$this->SetX(147);$this->SetTextColor(51,102,204);$this->SetFont('','U',8);
		$this->Cell(0,5,"cstth@telkom.co.id",0,0,'C');$this->Ln(4);
		$this->Image(app_path('Services/PDF/images/tth-logo-text-moto.jpg'),20,263,52);
		$this->Image(app_path('Services/PDF/images/decorator-pattern-1.jpg'),80,260,85);
	}

	/**
	 * FPDF FUNCTION BELLOW
	 */

	var $widths;
	var $aligns;

	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}

	function Row($data, $border = true)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			if ($border){
				$this->Rect($x,$y,$w,$h);
			}
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}

}