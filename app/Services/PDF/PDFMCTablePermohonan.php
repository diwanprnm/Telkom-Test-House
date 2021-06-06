<?php

namespace App\Services\PDF;

use Anouar\Fpdf\Fpdf as FPDF;

class PDFMCTablePermohonan extends FPDF{
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

	function Row($data)
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
			// $x=$this->GetX();
			$x=10.00125;
			$y=$this->GetY();
			//Draw the border
			// $this->Rect($x,$y,$w,$h);
			//Print the text
			$this->SetFont('Arial','',10);
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function RowRect($data)
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
			if($i==0){
				$x = 10.00125;
			}else{
				$x=$this->GetX();
			}
			$y=$this->GetY();
			//Draw the border
			if($i>0){
				$this->Rect($x,$y,$w,$h);
			}
			//Print the text
			// $this->SetFont('Arial','',10);
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
			$this->setLeftMargin(15);
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
	
	public $param1;
	public $param2;
	function jns_pengujian($param1,$param2) {
		$this->param1 = $param1;
		$this->param2 = $param2;
	}
	
	public $judul;
	public $title;
	public $kotaPerusahaan;
	public $date;
	public $nama_pemohon;
	function data_param($judul, $title, $kotaPerusahaan, $date, $nama_pemohon) {
		$this->judul = $judul;
		$this->title = $title;
		$this->kotaPerusahaan = ucwords(strtolower($kotaPerusahaan));
		$this->date = $date;
		$this->nama_pemohon = $nama_pemohon;
	}
	
	function Header()
	{
		// switch ($this->param) {
			// case 1:
				// $uji_init = 'QA';
				// $uji_name = 'Quality Assurance';
				// break;
			// case 2:
				// $uji_init = 'TA';
				// $uji_name = 'Type Approval';
				// break;
			// case 3:
				// $uji_init = 'UP';
				// $uji_name = 'Uji Pesanan';
				// break;
			// case 4:
				// $uji_init = 'CAL';
				// $uji_name = 'Calibration';
				// break;
			// default:
				// $uji_init = 'N/A';
				// $uji_name = 'Not Applicable';
				// break;
		// }
		$this->Image(public_path().'/assets/images/Telkom-Indonesia-Corporate-Logo1.jpg',10,3,27);
		$this->SetFont('helvetica','B',12);
		$this->SetFont('','BU');
		$this->SetTextColor(0, 0, 0);
		$this->Cell(120);
		$this->Cell(70,5,$this->judul,0,0,'R');
		$this->Ln();
		$this->SetFont('helvetica','',10);
		$this->Cell(120);
		$this->SetFont('','I');
		$this->Cell(70,5,$this->title,0,0,'R');
		$this->Ln();
		$this->Line(10,22.5,200,22.5);
		$this->Line(10,23,200,23);
		// $this->Ln();
		// $this->SetFont('helvetica','',6);
		// $this->Cell(120);
		// $this->Cell(70,5,'Hal '.$this->PageNo().' of {nb}',0,0,'R');
		$this->Ln(5);
	}
	//Page footer
	function Footer()
	{
		$this->SetY(-48);
		$this->SetFont('helvetica','',10);
		$this->Cell(0,5,"$this->kotaPerusahaan, $this->date",0,0,'R');
		$this->Ln(18);
		$this->SetFont('','U');
		$this->Cell(185,5,"$this->nama_pemohon",0,0,'R');
		$this->SetFont('helvetica','',8);
		$this->Ln(4);
		$this->SetFont('','U');
		$this->Cell(185,5,"NAMA PEMOHON & CAP PERUSAHAAN",0,0,'R');
		$this->Ln(4);
		$this->SetFont('','I');
		$this->Cell(185,5,"Applicant's Name & Company Stamp",0,0,'R');
		$this->Ln(1);
		$this->SetFont('','U');
		$this->Cell(10,5,"Telkom Test House, Telp. (+62) 812-2483-7500",0,0,'L');
		$this->Ln(4);
		$this->SetFont('','I');
		$this->Cell(10,5,"Telkom Test House, Phone. (+62) 812-2483-7500",0,0,'L');
		$this->Ln(4);
		$this->SetFont('helvetica','',8);
		if($this->param1 == 'QA'){
			$this->Cell(185,5,"TLKM02/F/001 Versi 03",0,0,'L');		
		}
		else if($this->param1 == 'TA'){
			$this->Cell(185,5,"TLKM02/F/002 Versi 03",0,0,'L');		
		}
		else if($this->param1 == 'VT'){
			$this->Cell(185,5,"TLKM02/F/003 Versi 03",0,0,'L');		
		}
		else if($this->param1 == 'KAL'){
			$this->Cell(185,5,"TLKM02/F/004 Versi 03",0,0,'L');		
		}
		//Position at 1.5 cm from bottom
		$this->SetXY(-10,-11);
		$this->Cell(4,0.1,'Hal '.$this->PageNo().' dari {nb}',0,0,'R');
		
	}
	
	/**
	 * Draws text within a box defined by width = w, height = h, and aligns
	 * the text vertically within the box ($valign = M/B/T for middle, bottom, or top)
	 * Also, aligns the text horizontally ($align = L/C/R/J for left, centered, right or justified)
	 * drawTextBox uses drawRows
	 *
	 * This function is provided by TUFaT.com
	 */
	function drawTextBox($strText, $w, $h, $align='L', $valign='T', $border=true)
	{
		$xi=$this->GetX();
		$yi=$this->GetY();
		
		$hrow=$this->FontSize;
		$textrows=$this->drawRows($w, $hrow, $strText, 0, $align, 0, 0, 0);
		$maxrows=floor($h/$this->FontSize);
		$rows=min($textrows, $maxrows);

		$dy=0;
		if (strtoupper($valign)=='M')
			$dy=($h-$rows*$this->FontSize)/2;
		if (strtoupper($valign)=='B')
			$dy=$h-$rows*$this->FontSize;

		$this->SetY($yi+$dy);
		$this->SetX($xi);

		$this->drawRows($w, $hrow, $strText, 0, $align, false, $rows, 1);

		if ($border)
			$this->Rect($xi, $yi, $w, $h);
	}

	function drawRows($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0, $prn=0)
	{
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r", '', $txt);
		$nb=strlen($s);
		if($nb>0 && $s[$nb-1]=="\n")
			$nb--;
		$b=0;
		if($border)
		{
			if($border==1)
			{
				$border='LTRB';
				$b='LRT';
				$b2='LR';
			}
			else
			{
				$b2='';
				if(is_int(strpos($border, 'L')))
					$b2.='L';
				if(is_int(strpos($border, 'R')))
					$b2.='R';
				$b=is_int(strpos($border, 'T')) ? $b2.'T' : $b2;
			}
		}
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
				if($this->ws>0)
				{
					$this->ws=0;
					if ($prn==1) $this->_out('0 Tw');
				}
				if ($prn==1) {
					$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
				}
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border && $nl==2)
					$b=$b2;
				if ( $maxline && $nl > $maxline )
					return substr($s, $i);
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
					if($this->ws>0)
					{
						$this->ws=0;
						if ($prn==1) $this->_out('0 Tw');
					}
					if ($prn==1) {
						$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
					}
				}
				else
				{
					if($align=='J')
					{
						$this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
						if ($prn==1) $this->_out(sprintf('%.3F Tw', $this->ws*$this->k));
					}
					if ($prn==1){
						$this->Cell($w, $h, substr($s, $j, $sep-$j), $b, 2, $align, $fill);
					}
					$i=$sep+1;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border && $nl==2)
					$b=$b2;
				if ( $maxline && $nl > $maxline )
					return substr($s, $i);
			}
			else
				$i++;
		}
		//Last chunk
		if($this->ws>0)
		{
			$this->ws=0;
			if ($prn==1) $this->_out('0 Tw');
		}
		if($border && is_int(strpos($border, 'B')))
			$b.='B';
		if ($prn==1) {
			$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
		}
		$this->x=$this->lMargin;
		return $nl;
	}

}