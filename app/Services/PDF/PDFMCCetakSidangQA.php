<?php

namespace App\Services\PDF;
use Anouar\Fpdf\Fpdf as FPDF;

class PDFMCCetakSidangQA extends FPDF{

	private $data;

	public function setData($data)
	{
		$this->data = $data;
	}

	function Header()
	{
        $this->SetY(10);$this->SetFont('helvetica','B',14);
		$this->Cell(0,5,$this->data['title'],0,0,'C');$this->Ln(8);
        $this->SetY(15);$this->SetFont('helvetica','B',12);
		$this->Cell(0,5,$this->data['subTitle'],0,0,'C');$this->Ln(8);
	}

	function Footer()
	{
		$signees = $this->data['signees'];
		$this->SetXY(67, -30);
		$this->SetFont('helvetica','B',8);
		$this->Cell(55,5,"Bandung, ".$this->data['date'],0,0,'C');
		$this->SetXY(67, -26.5);
		$this->Cell(55,5,"Komite Validasi QA",0,0,'C');
		$this->SetXY(67, -13.5);
		$this->SetFont('helvetica','UB',8);
		$this->Cell(55,5,$signees[1]['name'] ??"SONTANG HOTAPEA",0,0,'C');
		$this->SetXY(67, -10);
		$this->SetFont('helvetica','B',8);
		$this->Cell(55,5,$signees[1]['title'] ??"Sekretaris",0,0,'C');


		$this->SetXY(177, -26.5);
		$this->Cell(55,5,"Menyetujui",0,0,'C');
		$this->SetXY(177, -13.5);
		$this->SetFont('helvetica','UB',8);
		$this->Cell(55,5,$signees[0]['name'] ??"I GEDE ASTAWA",0,0,'C');
		$this->SetXY(177, -10);
		$this->SetFont('helvetica','B',8);
		$this->Cell(55,5,$signees[0]['title'] ??"SM INFRASTRUCTURE ASSURANCE",0,0,'C');

		$this->ImageStream($this->data['qrCode'], 245, 180, 30);
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

	function ImageStream($stream, $x, $y, $w, $h=null)
	{
        $imageSize = getimagesizefromstring($stream);
        $extension = image_type_to_extension($imageSize[2]);		
        $tempImageLocation = tempnam(sys_get_temp_dir(),'').$extension;
        file_put_contents($tempImageLocation, $stream);
        $this->Image($tempImageLocation, $x, $y, $w, $h);
        unlink($tempImageLocation);
	}

}