<?php
namespace App\Services\PDF;

use Anouar\Fpdf\Fpdf as FPDF;

class PDFMCTablesKonsumen extends FPDF{
	var $widths;
	var $aligns;
	var $kodeForm = '(Kode Form)';
	
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

	function setData($data)
	{
		$this->kodeForm = $data['kodeForm'];
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
			if($i==0){
				$x = 10.00125;
			}else{
				$x=$this->GetX();
			}
			$y=$this->GetY();
			//Print the text
			// $this->SetFont('Arial','',10);
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
	function judul_kop($judul,$title) {
		$this->judul = $judul;
		$this->title = $title;
	}
	
	function Header()
	{
		 //this is header
	}
	//Page footer
	function Footer()
	{
		$this->SetXY(10,-20);
		$this->SetFont('helvetica','',10);
		$this->Cell(95,11,$this->kodeForm,0,0,'L');
		$this->Cell(95,11,'Hal '.$this->PageNo().' dari {nb}',0,0,'R');
		
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
	
	/* function terbilang($satuan)
	{    
		$huruf = array ("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh","Sebelas"); 
			if ($satuan < 12)   
				return " ".$huruf[$satuan];
			elseif ($satuan < 20)   
				return $this->terbilang($satuan - 10)." Belas ";  
			elseif ($satuan < 100)    
				return $this->terbilang($satuan / 10)." Puluh ".$this->terbilang($satuan % 10);  
			elseif ($satuan < 200)    
				return " Seratus".$this->terbilang($satuan - 100);
			elseif ($satuan < 1000)    
				return $this->terbilang($satuan / 100)." Ratus ".$this->terbilang($satuan % 100);   
			elseif ($satuan < 2000)    
				return "Seribu ".$this->terbilang($satuan - 1000);  
			elseif ($satuan < 1000000)  
				return $this->terbilang($satuan / 1000)." Ribu ".$this->terbilang($satuan % 1000); 
			elseif ($satuan < 1000000000)    
				return $this->terbilang($satuan / 1000000)." Juta ".$this->terbilang($satuan % 1000000);  
			//elseif ($satuan >= 1000000000)   
	} */
	function kekata($x) {
		$x = abs($x);
		$angka = array("", "satu", "dua", "tiga", "empat", "lima",
		"enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($x <12) {
		// $temp = " ". $angka&#91;$x&#93;;
		$temp = " ". $angka[$x];
		} else if ($x <20) {
		$temp = $this->kekata($x - 10). " belas";
		} else if ($x <100) {
		$temp = $this->kekata($x/10)." puluh". $this->kekata($x % 10);
		} else if ($x <200) {
		$temp = " seratus" . $this->kekata($x - 100);
		} else if ($x <1000) {
		$temp = $this->kekata($x/100) . " ratus" . $this->kekata($x % 100);
		} else if ($x <2000) {
		$temp = " seribu" . $this->kekata($x - 1000);
		} else if ($x <1000000) {
		$temp = $this->kekata($x/1000) . " ribu" . $this->kekata($x % 1000);
		} else if ($x <1000000000) {
		$temp = $this->kekata($x/1000000) . " juta" . $this->kekata($x % 1000000);
		} else if ($x <1000000000000) {
		$temp = $this->kekata($x/1000000000) . " milyar" . $this->kekata(fmod($x,1000000000));
		} else if ($x <1000000000000000) {
		$temp = $this->kekata($x/1000000000000) . " trilyun" . $this->kekata(fmod($x,1000000000000));
		}
		return $temp;
	}
	
	function terbilang($x, $style=4) {
		if($x<0) {
		$hasil = "minus ". trim($this->kekata($x));
		} else {
		$hasil = trim($this->kekata($x));
		}
		switch ($style) {
			case 1:
			$hasil = strtoupper($hasil);
			break;
			case 2:
			$hasil = strtolower($hasil);
			break;
			case 3:
			$hasil = ucwords($hasil);
			break;
			default:
			$hasil = ucfirst($hasil);
			break;
		}
		return $hasil;
	}
}