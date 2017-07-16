<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// use Anouar\Fpdf\Fpdf as FPDF;


class PDF_MC_Table_Kuitansi extends FPDF{
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
		
		$pageWidth = 148;
		$pageHeight = 210;
		$lineWidth = 10;
		$lineHeight = 10;
		$margin = 10;
		$this->Rect( $margin, $margin , ($pageHeight - $lineHeight) - $margin , ($pageWidth - $lineWidth) - $margin);
		$this->SetFont('helvetica','B',14);
		$this->Rotate(90); 
		$this->Image('./assets/images/Telkom-Indonesia-Corporate-Logo1.jpg',-108,12,40);
		$this->Cell(-75);
		$this->Cell(0,17,'Divisi Digital Service (DDS)',0,0,'L');
		$this->Ln(7);
		$this->Cell(-75);
		$this->SetFont('helvetica','B',10);
		$this->Cell(0,17,'PT. Telekomunikasi Indonesia, Tbk.',0,0,'L');
		$this->Ln(7);
		$this->Cell(-75);
		$this->SetFont('helvetica','',10);
		$this->Cell(0,17,'Jl. Gegerkalong Hilir No. 47',0,0,'L');
		$this->Ln(6);
		$this->Cell(-75);
		$this->Cell(0,17,'Bandung 40152, Indonesia',0,0,'L');
		$this->Line(-118,43,10,43);
		$this->Rotate(0);
	}
	//Page footer
	function Footer()
	{
		//Position at 1.5 cm from bottom
		// $this->SetY(-6);
		//Arial italic 8
		// $this->SetFont('helvetica','I',11);
		//Page number
		// $this->Cell(0,0.1,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		
		// $this->Cell(130,0.1,'Bandung',0,0,'R');
		
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
	
	public $angle = 0; 

    function Rotate($angle,$x=-1,$y=-1) { 

        if($x==-1) 
            $x=$this->x; 
        if($y==-1) 
            $y=$this->y; 
        if($this->angle!=0) 
            $this->_out('Q'); 
        $this->angle=$angle; 
        if($angle!=0) 

        { 
            $angle*=M_PI/180; 
            $c=cos($angle); 
            $s=sin($angle); 
            $cx=$x*$this->k; 
            $cy=($this->h-$y)*$this->k; 
             
            $this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy)); 
        } 
    } 
}

class PDF_MC_Tables extends FPDF{
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
		$this->Image('./assets/images/Telkom-Indonesia-Corporate-Logo1.jpg',165,5,40);
		$this->Ln(5);
	}
	//Page footer
	function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-6);
		//Arial italic 8
		$this->SetFont('helvetica','I',11);
		//Page number
		$this->Cell(0,0.1,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		
		// $this->Cell(130,0.1,'Bandung',0,0,'R');
		
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

class PDF_MC_Table extends FPDF{
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
	function judul_kop($judul,$title) {
		$this->judul = $judul;
		$this->title = $title;
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
		$this->Image('./assets/images/Telkom-Indonesia-Corporate-Logo1.jpg',10,3,27);
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
		//Position at 1.5 cm from bottom
		$this->SetY(-6);
		//Arial italic 8
		$this->SetFont('helvetica','I',11);
		//Page number
		$this->Cell(0,0.1,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		
		// $this->Cell(130,0.1,'Bandung',0,0,'R');
		
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

/**
* 
*/
class WatermakStel extends FPDI
{
	
		var $extgstates = array();

	    // alpha: real value from 0 (transparent) to 1 (opaque)
	    // bm:    blend mode, one of the following:
	    //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
	    //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
	    function SetAlpha($alpha, $bm='Normal')
	    {
	        // set alpha for stroking (CA) and non-stroking (ca) operations
	        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
	        $this->SetExtGState($gs);
	    }

	    function AddExtGState($parms)
	    {
	        $n = count($this->extgstates)+1;
	        $this->extgstates[$n]['parms'] = $parms;
	        return $n;
	    }

	    function SetExtGState($gs)
	    {
	        $this->_out(sprintf('/GS%d gs', $gs));
	    } 

	    function _putextgstates()
	    {
	        for ($i = 1; $i <= count($this->extgstates); $i++)
	        {
	            $this->_newobj();
	            $this->extgstates[$i]['n'] = $this->n;
	            $this->_out('<</Type /ExtGState');
	            $parms = $this->extgstates[$i]['parms'];
	            $this->_out(sprintf('/ca %.3F', $parms['ca']));
	            $this->_out(sprintf('/CA %.3F', $parms['CA']));
	            $this->_out('/BM '.$parms['BM']);
	            $this->_out('>>');
	            $this->_out('endobj');
	        }
	    }

	    function _putresourcedict()
	    {
	        parent::_putresourcedict();
	        $this->_out('/ExtGState <<');
	        foreach($this->extgstates as $k=>$extgstate)
	            $this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
	        $this->_out('>>');
	    }

	    function _putresources()
	    {
	        $this->_putextgstates();
	        parent::_putresources();
	    }
}
Route::get('cetakstel', function(Illuminate\Http\Request $request){
	$attach = $request->attach;
	$invoice_id = $request->invoice_id;
	$company_name = $request->company_name;
	// $pdf = new Fpdf('P','in',array(8.5,11)); 
	$pdf = new WatermakStel();
 	$path = public_path('media/stel/'.$attach); 
	$pagecount = $pdf->setSourceFile($path);
	for ($i=1; $i <= $pagecount ; $i++) { 
		 $pdf->AddPage();
		//Import the first page of the file
		$tppl = $pdf->importPage($i); 
		// use the imported page and place it at point 20,30 with a width of 170 mm
		$pdf->useTemplate($tppl, 0, 0); 
		 $pdf->SetAlpha(0.4);
		$image_path = public_path('assets/images/Telkom-Indonesia-Corporate-Logo1.jpg');
		$pdf->Image($image_path,170,3,27);   
		$pdf->SetY(260); 
		$pdf->SetTextColor(255,0,0);
		$pdf->SetFont('helvetica','I',18);
		//Page number
		$pdf->Cell(0,0.1,$company_name,0,0,'C'); 
		$pdf->SetY(266);
		$pdf->SetTextColor(255,0,0);
		//Arial italic 8
		$pdf->SetFont('helvetica','I',18);
		//Page number
		$pdf->Cell(0,0.1,'STEL '.$invoice_id,0,0,'C');	
		$pdf->SetFillColor(217,217,217);
		$pdf->Rect(70, 0, 75, 297, 'F');
	}
	  $pdf->Output();
 	exit;
 
});
Route::get('cetakPermohonan', function(Illuminate\Http\Request $request){
	// Instanciation of inherited class
		$data = $request->session()->get('key');
	$pdf = new PDF_MC_Table();
	$pdf->judul_kop(
	'PERMOHONAN UJI MUTU ('.$data[0]['initPengujian'].')',
	'Applicant Form '.$data[0]['initPengujian'].' ('.$data[0]['descPengujian'].')');
	$pdf->AliasNbPages();
	$pdf->AddPage();
/*Data Pemohon*/
	$pdf->SetFont('helvetica','B',11);
	$pdf->Cell(27,5,"Data Pemohon ",0,0,'L');
	$pdf->SetFont('helvetica','',11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5," / Applicant's Data",0,0,'L');
	/*Nama Pemohon*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Nama Pemohon",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['nama_pemohon']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Applicant's Name",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(10.00125,$yNow);
	/*Alamat Pemohon*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Alamat",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['alamat_pemohon']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Address",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(10.00125,$yNow);
	/*Telepon dan Faksimile Pemohon*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(55.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Telepon",0,0,'L');
	$pdf->SetWidths(array(0.00125,70,75,35));
	$pdf->Row(array("","",":",$data[0]['telepon_pemohon']));
	$y2 = $pdf->getY();
	$pdf->setXY(125.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Faksimile",0,0,'L');
	$pdf->SetWidths(array(0.00125,140,145,45));
	$pdf->Row(array("","",":",$data[0]['faksimile_pemohon']));
	$y3 = $pdf->getY();
	$pdf->setXY(55.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(70,5,"Telephone",0,0,'L');
	$pdf->Cell(10,5,"Facsimile",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		// $yNow;
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);
	/*Email Pemohon*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(55.00125,$y + 6);
	$pdf->Cell(10,5,"E-mail",0,0,'L');
	$pdf->SetWidths(array(0.00125,70,75,115));
	$pdf->Row(array("","",":",$data[0]['email_pemohon']));
	$pdf->Ln(2);
	$pdf->setX(10.00125);
/*End Data Pemohon*/

/*Data Perusahaan*/
	$pdf->SetFont('helvetica','B',11);
	$pdf->Cell(31,5,"Data Perusahaan ",0,0,'L');
	$pdf->SetFont('helvetica','',11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5," / Company's Data",0,0,'L');
	/*Jenis Perusahaan*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	// $pdf->Cell(190,5,"[ Pabrikan (Manufacture) ]",0,0,'C');
		// if($data[0]['jns_perusahaan'] == 1){
			// $jns_perusahaan = 'Pabrikan';
		// }else if($data[0]['jns_perusahaan'] == 2){
			// $jns_perusahaan = 'Agen/Perwakilan';
		// }else if($data[0]['jns_perusahaan'] == 3){
			// $jns_perusahaan = 'Pengguna/Perorangan';
		// }
	$pdf->Cell(190,5,"[ ".$data[0]['jns_perusahaan']." ]",0,0,'C');
	/*Nama Perusahaan*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Nama Perusahaan",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['nama_perusahaan']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Company's Name",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(10.00125,$yNow);
	/*Alamat Perusahaan*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Alamat",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['alamat_perusahaan']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Address",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(10.00125,$yNow);
	/*Telepon dan Faksimile Perusahaan*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(55.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Telepon",0,0,'L');
	$pdf->SetWidths(array(0.00125,70,75,35));
	$pdf->Row(array("","",":",$data[0]['telepon_perusahaan']));
	$y2 = $pdf->getY();
	$pdf->setXY(125.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Faksimile",0,0,'L');
	$pdf->SetWidths(array(0.00125,140,145,45));
	$pdf->Row(array("","",":",$data[0]['faksimile_perusahaan']));
	$y3 = $pdf->getY();
	$pdf->setXY(55.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(70,5,"Telephone",0,0,'L');
	$pdf->Cell(10,5,"Facsimile",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		// $yNow;
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);
	/*Email Perusahaan*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(55.00125,$y + 6);
	$pdf->Cell(10,5,"E-mail",0,0,'L');
	$pdf->SetWidths(array(0.00125,70,75,115));
	$pdf->Row(array("","",":",$data[0]['email_perusahaan']));
	$pdf->Ln(2);
	$pdf->setX(10.00125);
/*End Data Perusahaan*/

/*Data Perangkat*/
	$pdf->SetFont('helvetica','B',11);
	$pdf->Cell(28,5,"Data Perangkat ",0,0,'L');
	$pdf->SetFont('helvetica','',11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5," / Equipment's Data",0,0,'L');
	/*Nama Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Perangkat",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['nama_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Equipment",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(10.00125,$yNow);
	/*Merek dan Model Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Merek/Pabrik",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['merek_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Model/Tipe",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['model_perangkat']));
	$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Merk",0,0,'L');
	$pdf->Cell(10,5,"Model/Type",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		/* // $yNow; */
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);
	/*Kapasitas dan Referensi Uji Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Kapasitas/Kecepatan",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['kapasitas_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Referensi Uji",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['referensi_perangkat']));
	$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Capacity/Speed",0,0,'L');
	$pdf->Cell(10,5,"Test Reference",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		/* // $yNow; */
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);
	/*Negara Pembuat Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Negara Pembuat",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['pembuat_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Made In",0,0,'L');
	$pdf->Ln(8);
	$pdf->setX(10.00125);
/*End Data Perangkat*/

/*Pernyataan*/
	$pdf->SetFont('helvetica','B',11);
	$pdf->Cell(21,5,"Pernyataan ",0,0,'L');
	$pdf->SetFont('helvetica','',11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5," / Aggrement",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(15.00125,$y + 6);
	$pdf->Cell(5,5,"1. ",0,0,'L');
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Kami menyatakan bahwa permohonan ini telah diisi dengan keadaan yang sebenarnya.",0,0,'L');
	$pdf->Ln(4);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"     Ensuring that we have filled this application form with eligible data.",0,0,'L');
	$pdf->Ln(6);
	$pdf->Cell(5,5,"2. ",0,0,'L');
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Kami telah mengetahui dan menyetujui spesifikasi uji tersebut yang digunakan sebagai acuan pengujian.",0,0,'L');
	$pdf->Ln(4);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"     We had fully agreed and understand to the specification as stated above for testing reference.",0,0,'L');
	$pdf->Ln(6);
	$pdf->Cell(5,5,"3. ",0,0,'L');
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Kami menjamin bahwa merek, model, dan tipe barang yang Kami produksi/pasarkan sama dengan yang diujikan.",0,0,'L');
	$pdf->Ln(4);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"     Ensuring that we reproduce/distribute the similar mark model and type with the tested item.",0,0,'L');
	$pdf->Ln(8);
	$pdf->setX(10.00125);
/*End Data Pemohon*/

/*Footer Manual*/
	$pdf->SetFont('helvetica','',10);
	$pdf->Cell(150,5,"     Bandung,",0,0,'R');
	$pdf->Ln(18);
	$pdf->SetFont('','U');
	$pdf->Cell(185,5,"                                        ",0,0,'R');
	$pdf->SetFont('helvetica','',8);
	$pdf->Ln(6);
	$pdf->SetFont('','U');
	$pdf->Cell(185,5,"NAMA PEMOHON & CAP PERUSAHAAN",0,0,'R');
	$pdf->Ln(4);
	$pdf->SetFont('','I');
	$pdf->Cell(185,5,"APPLICANT'S NAME & COMPANY STAMP",0,0,'R');
	$pdf->Ln(6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"User Relation, Divisi Digital Service, Telp. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	$pdf->Ln(4);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Divisi Digital Service, User Relation, Phone. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',8);
	$pdf->Cell(185,5,"IASO2/F/002 Versi 01",0,0,'R');
/*End Footer Manual*/
	$pdf->Output();
	exit;
});

Route::get('cetakKontrak', function(Illuminate\Http\Request $request){
	// Instanciation of inherited class
		$data = $request->session()->get('key_contract');
	$pdf = new PDF_MC_Table();
	$pdf->judul_kop('FORM TINJAUAN KONTRAK','Contract Review Form');
	$pdf->AliasNbPages();
	$pdf->AddPage();
/*Data Pemohon*/
	$pdf->SetFont('helvetica','B',11);
	$pdf->Cell(27,5,"Data Pemohon ",0,0,'L');
	$pdf->SetFont('helvetica','',11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5," / Applicant's Data",0,0,'L');
	/*Nama Pemohon*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Nama Pemohon",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['nama_pemohon']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Applicant's Name",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(10.00125,$yNow);
	/*Alamat Pemohon*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Alamat",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['alamat_pemohon']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Address",0,0,'L');
	$pdf->Ln(6);
	$pdf->setX(10.00125);
/*End Data Pemohon*/

/*Data Perangkat*/
	$pdf->SetFont('helvetica','B',11);
	$pdf->Cell(28,5,"Data Perangkat ",0,0,'L');
	$pdf->SetFont('helvetica','',11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5," / Equipment's Data",0,0,'L');
	/*Nama Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Perangkat",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['nama_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Equipment",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(10.00125,$yNow);
	/*Merek dan Model Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Merek/Pabrik",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['merek_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Model/Tipe",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['model_perangkat']));
	$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Merk",0,0,'L');
	$pdf->Cell(10,5,"Model/Type",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		/* // $yNow; */
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);
	/*Kapasitas dan Referensi Uji Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Kapasitas/Kecepatan",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['kapasitas_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Referensi Uji",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['referensi_perangkat']));
	$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Capacity/Speed",0,0,'L');
	$pdf->Cell(10,5,"Test Reference",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		/* // $yNow; */
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);
	/*Negara Pembuat Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Negara Pembuat",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['pembuat_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Made In",0,0,'L');
	$pdf->Ln(8);
	$pdf->setX(10.00125);
/*End Data Perangkat*/

/*Hal-hal yang disepakati*/
	$pdf->SetFont('helvetica','B',11);
	$pdf->Cell(43,5,"Hal-hal yang disepakati ",0,0,'L');
	$pdf->SetFont('helvetica','',11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5," / Aggrements",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(4,4,"",0,0,'L');
	$pdf->Cell(10,4,"Biaya pengujian sesuai SPB yang telah diterbitkan oleh DDS TELKOM.",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(4,4,"",0,0,'L');
	$pdf->Cell(10,4,"Kastamer memahami konfigurasi dan item pengujian.",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(4,4,"",0,0,'L');
	$pdf->Cell(10,4,"Kastamer menerima laporan pengujian *) dan menyetujui bahwa data perangkat yang tercatat dalam Laporan Hasil",0,0,'L');
	$pdf->Ln(4);
	$pdf->Cell(5,4,"",0,0,'L');
	$pdf->Cell(10,4,"Uji perangkat adalah sesuai sampel uji.",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(4,4,"",0,0,'L');
	$pdf->Cell(10,4,"Kastamer menerima Surat Keterangan Quality Assurance. **).",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(4,4,"",0,0,'L');
	$pdf->Cell(10,4,"Pengambilan kembali barang uji maksimal 10 (sepuluh) hari kerja setelah proses pengujian selesai.",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(4,4,"",0,0,'L');
	$pdf->Cell(10,4,"Waktu penyelesaian pekerjaan: ".$data[0]['testing_start']." sampai dengan ".$data[0]['testing_end']." (dd-mm-yyyy).",0,0,'L');
	$pdf->Ln(4);
	$pdf->Cell(5,4,"",0,0,'L');
	$pdf->Cell(10,4,"Jika pekerjaan belum selesai melebihi batas waktu yang telah disepakati yang diakibatkan oleh oleh kelalaian kastamer,",0,0,'L');
	$pdf->Ln(4);
	$pdf->Cell(5,4,"",0,0,'L');
	$pdf->Cell(10,4,"maka DDS TELKOM dapat menghentikan (closed) pengujian. DDS TELKOM hanya memberikan hasil rekaman sesuai",0,0,'L');
	$pdf->Ln(4);
	$pdf->Cell(5,4,"",0,0,'L');
	$pdf->Cell(10,4,"item yang telah dilaksanakan.",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(4,4,"",0,0,'L');
	$pdf->Cell(10,4,"Ketika kastamer memerlukan penambahan waktu pelaksanaan pengujian karena sesuatu hal, maka kastamer dapat",0,0,'L');
	$pdf->Ln(4);
	$pdf->Cell(5,4,"",0,0,'L');
	$pdf->Cell(10,4,"menyampaikan secara tertulis dan perlu disepakati oleh kedua belah pihak.",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(4,4,"",0,0,'L');
	$pdf->Cell(10,4,"Pembayaran biaya uji sesuai SPB, dilakukan oleh kastamer secara giral melalu rekening atas nama TELKOM paling",0,0,'L');
	$pdf->Ln(4);
	$pdf->Cell(5,4,"",0,0,'L');
	$pdf->Cell(10,4,"lambat 7 hari kerja setelah penerbitan SPB. Apabila pada tenggang waktu tersebut kastamer tidak melakukan",0,0,'L');
	$pdf->Ln(4);
	$pdf->Cell(5,4,"",0,0,'L');
	$pdf->Cell(10,4,"pembayaran, kontrak ini tidak berlaku.",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4,4,"*)   Kecuali TA Test",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(4,4,"**)  Kecuali TA Test dan Voluntary Test",0,0,'L');
	
	$pdf->Ln(4);
	$pdf->setX(10.00125);
/*End Data Pemohon*/

/*Footer Manual*/
	$pdf->SetFont('helvetica','',10);
	$pdf->Cell(190,5,"Bandung, ".$data[0]['contract_date'],0,0,'R');
	$pdf->Ln(5);
	$pdf->setX(10.00125);
	$pdf->Cell(63, 4, 'User Relation', 1, 0, 'C');
	$pdf->Cell(63, 4, 'Laboratorium', 1, 0, 'C');
	$pdf->Cell(63, 4, 'Kastamer', 1, 1, 'C');
	$pdf->setX(10.00125);
	$pdf->drawTextBox('(...............................)', 63, 18, 'C', 'B', 1);
	$pdf->setXY(73.00125,$pdf->getY()-18);
	$pdf->drawTextBox('(...............................)', 63, 18, 'C', 'B', 1);
	$pdf->setXY(136.00125,$pdf->getY()-18);
	$pdf->drawTextBox('(...............................)', 63, 18, 'C', 'B', 1);
	$pdf->Ln(2);
	$pdf->setX(10.00125);
	$pdf->Cell(10,4,"Catatan Kelengkapan Administrasi:",0,0,'L');
	$pdf->Cell(53, 4,"",0,0,'L');
	$pdf->Cell(10,4,"Catatan Kelengkapan Teknis:",0,0,'L');
	$pdf->Cell(66, 4,"",0,0,'L');
	$pdf->Cell(10,4,"Catatan Lain:",0,0,'L');
	$pdf->Ln(4);
	$pdf->setX(12.00125);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(1, 4,"",0,0,'L');
	$pdf->Cell(10,4,"Sistem Mutu",0,0,'L');
	$pdf->Cell(48, 4,"",0,0,'L');
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(1, 4,"",0,0,'L');
	$pdf->Cell(10,4,"Fungsi perangkat memenuhi untuk diuji",0,0,'L');
	$pdf->Ln(5);
	$pdf->setX(12.00125);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(1, 4,"",0,0,'L');
	$pdf->Cell(10,4,"SIUPP",0,0,'L');
	$pdf->Cell(48, 4,"",0,0,'L');
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(1, 4,"",0,0,'L');
	$pdf->Cell(10,4,"Kelengkapan perangkat uji",0,0,'L');
	$pdf->Ln(5);
	$pdf->setX(12.00125);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(1, 4,"",0,0,'L');
	$pdf->Cell(10,4,"NPWP",0,0,'L');
	$pdf->Cell(48, 4,"",0,0,'L');
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(1, 4,"",0,0,'L');
	$pdf->Cell(10,4,"Kesesuaian sampel perangkat uji",0,0,'L');
	$pdf->Ln(5);
	$pdf->setX(12.00125);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(1, 4,"",0,0,'L');
	$pdf->Cell(10,4,"Surat Penunjukkan Prinsipal",0,0,'L');
	$pdf->Ln(5);
	$pdf->setX(12.00125);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(1, 4,"",0,0,'L');
	$pdf->Cell(10,4,"Sertifikat ISO Prinsipal",0,0,'L');
	$pdf->Ln(5);
	$pdf->setX(12.00125);
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(1, 4,"",0,0,'L');
	$pdf->Cell(10,4,"Manual/Spesifikasi Perangkat",0,0,'L');
	$pdf->Ln(6);
	$pdf->setX(10.00125);
	$pdf->Cell(12,4,"Kolom",0,0,'L');
	$pdf->Cell(4, 4, "", 1, 0);
	$pdf->Cell(18,4,'harus diisi. Jika "Ya" tulis',0,0,'L');
	$pdf->Cell(22,4,'',0,0,'L');
	$pdf->SetFont('ZapfDingbats','', 10);
	$pdf->Cell(4, 4, "4", 0, 0);
	$pdf->SetFont('helvetica','',10);
	$pdf->Cell(35,4,', dan jika "Tidak" tulis',0,0,'L');
	$pdf->SetFont('ZapfDingbats','', 10);
	$pdf->Cell(4, 4, "6", 0, 0);
	$pdf->SetFont('helvetica','',10);
	$pdf->Cell(0,4,".",0,0,'L');
	$pdf->SetFont('helvetica','',7);
	$pdf->Ln(5);
	$pdf->setX(10.00125);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"User Relation, Divisi Digital Service, Telp. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	$pdf->Ln(4);
	$pdf->setX(10.00125);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Divisi Digital Service, User Relation, Phone. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	$pdf->Ln();
	$pdf->Cell(185,1,"IASO2/F/006 Versi 01",0,0,'R');
/*End Footer Manual*/
	$pdf->Output();
	exit;
});

Route::get('cetakSPB', function(Illuminate\Http\Request $request){
	// Instanciation of inherited class
		$data = $request->session()->pull('key_exam_for_spb');
		// echo"<pre>";print_r($data);exit;
	$pdf = new PDF_MC_Tables();
		$spb_number = $data[0]['spb_number'];
		$company_name = $data[0]['exam']['company']['name'];
		$user_name = $data[0]['exam']['user']['name'];
		if($data[0]['exam']['company']['address'] != null){
			if($data[0]['exam']['company']['postal_code'] != null){
				$company_address = $data[0]['exam']['company']['address'].", ".$data[0]['exam']['company']['city'].", ".$data[0]['exam']['company']['postal_code'].".";
			}else{
				$company_address = $data[0]['exam']['company']['address'].", ".$data[0]['exam']['company']['city'].".";
			}
		}else{
			$company_address = "-";
		}
		if($data[0]['exam']['company']['fax'] != null){
			$company_contact = $data[0]['exam']['company']['phone_number']." - ".$data[0]['exam']['company']['fax'];
		}else{
			$company_contact = $data[0]['exam']['company']['phone_number'];
		}
		setlocale(LC_ALL, 'IND');
		$contract_date = date('j', strtotime($data[0]['exam']['contract_date']))." ".strftime('%B %Y', strtotime($data[0]['exam']['contract_date']));
		$exam_type = $data[0]['exam']['examinationType']['name'];
		$biaya = 0;
		for($i=0;$i<count($data[0]['arr_nama_perangkat']);$i++){
			$biaya = $biaya + $data[0]['arr_biaya'][$i];
		}
		$ppn = 0.1*$biaya;
		$total_biaya = $biaya + $ppn;
		$terbilang = $pdf->terbilang($total_biaya, 3);
		$spb_date = date('j', strtotime($data[0]['spb_date']))." ".strftime('%B %Y', strtotime($data[0]['spb_date']));
	// $pdf->judul_kop('FORM TINJAUAN KONTRAK','Contract Review Form');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->Ln(25);
	$pdf->SetFont('helvetica','B',10);
	// $pdf->SetFont('','BU');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(60);
	$pdf->Cell(70,5,'DIVISI DIGITAL SERVICE - PT TELKOM',0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('helvetica','B',10);
	$pdf->Cell(60);
	$pdf->SetFont('','BU');
	$pdf->Cell(70,5,'SURAT PEMBERITAHUAN BIAYA (SPB)',0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('helvetica','B',10);
	$pdf->Cell(60);
	// $pdf->SetFont('','I');
	$pdf->Cell(70,5,'No. '.$spb_number,0,0,'C');
	
	$pdf->Ln(10);
	$pdf->SetFont('helvetica','',10);
	$pdf->SetWidths(array(43,45,5,82));
	$pdf->SetAligns(array('C','L','L','L'));
	$pdf->Row(array('','Nama Perusahaan',':',''));
		$pdf->SetFont('helvetica','B',10);
		$pdf->SetXY(103.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,$company_name,0,0,'L');
		$pdf->Ln();
	$pdf->SetFont('helvetica','',10);
	$pdf->Row(array('','','','Up. '.$user_name));	
	$pdf->Row(array('','Alamat',':',$company_address));	
	$pdf->Row(array('','Telepon / Fax',':',$company_contact));	
	
	$pdf->Ln(4);
	$pdf->SetFont('helvetica','',10);
	$pdf->SetWidths(array(13,7,160));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','1. ','Menunjuk Contract Review Saudara tanggal '.$contract_date.' perihal permohonan uji mutu ('.$exam_type.'), dengan ini kami beritahukan bahwa biaya pengujian yang harus dibayar adalah :'));	
	
	$pdf->Ln(1);
	$pdf->SetFont('helvetica','B',10);
	$pdf->SetWidths(array(17,8,125,27));
	$pdf->SetAligns(array('L','C','C','C'));
	$pdf->RowRect(array('','No','Nama Perangkat','Biaya (Rp.)'));	
	$pdf->SetFont('helvetica','',10);
	$pdf->SetAligns(array('L','L','L','R'));
	for($i=0;$i<count($data[0]['arr_nama_perangkat']);$i++){
		$pdf->RowRect(array('',($i+1).'.',$data[0]['arr_nama_perangkat'][$i],number_format($data[0]['arr_biaya'][$i],0,",",".").",-"));	
	}
	$pdf->RowRect(array('','','PPN 10 %',number_format($ppn,0,",",".").",-"));	
	$pdf->SetFont('helvetica','B',10);
	$pdf->RowRect(array('','','Total Biaya Pengujian',number_format($total_biaya,0,",",".").",-"));	
	$pdf->SetWidths(array(17,160));
	$pdf->SetAligns(array('L','C'));
	$pdf->SetFont('','BI');
	$pdf->RowRect(array('','Terbilang : '.$terbilang.' Rupiah'));	
	
	$pdf->Ln(3);
	$pdf->SetFont('helvetica','',10);
	$pdf->SetWidths(array(13,7,160));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','2. ','Ketentuan dan tata cara pembayaran diatur sebagai berikut :'));	
	
	$pdf->Ln(4);
	$pdf->SetFont('helvetica','',10);
	$pdf->SetWidths(array(20,7,153));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','a. ','Pembayaran dilakukan ke rekening nomor'));	
		$pdf->SetFont('helvetica','B',10);
		$pdf->SetXY(104.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,'131-0096022712 an. Divisi RisTI TELKOM, Bank ',0,0,'L');
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'Mandiri KCP KAMPUS TELKOM BANDUNG.',0,0,'L');
		$pdf->Ln();
		$pdf->SetFont('helvetica','',10);
	$pdf->Row(array('','b. ','Transfer Rekening'));	
		$pdf->SetFont('helvetica','BU',10);
		$pdf->SetXY(67.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,'harus mencantumkan Nomor surat SPB',0,0,'L');
		$pdf->SetFont('helvetica','',10);
		$pdf->SetX(135.00125);
		$pdf->Cell(0,5,'yang dibayarkan (untuk',0,0,'L');
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'memudahkan penerbitan faktur pajak).',0,0,'L');
		$pdf->Ln();
	$pdf->Row(array('','c. ','Untuk memudahkan proses Administrasi keuangan dan penerbitan faktur pajak, mohon dapat dikirimkan'));	
		$pdf->SetFont('helvetica','B',10);
		$pdf->SetXY(54.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,'copy Bukti Transfer dari Bank yang mencantumkan nomor SPB yang dibayar',0,0,'L');
		$pdf->SetFont('helvetica','',10);
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'serta copy NPWP melalui web',0,0,'L');
		$pdf->SetX(85.00125);
		$pdf->SetFont('helvetica','U',10);
		$pdf->Cell(0,5,'www.telkomtesthouse.co.id',0,0,'L');
		$pdf->SetFont('helvetica','',10);
		$pdf->Ln();
	$pdf->Row(array('','d. ','Apabila dalam waktu 1 (satu) minggu setelah pembayaran, Saudara belum melengkapi nomor NPWP maka kami anggap Saudara tidak membutuhkan Faktur Pajak Standar.'));	
	$pdf->Row(array('','e. ','Perangkat sampel Uji harus sudah diambil paling lama 2(dua) minggu setelah selesai pengujian, apabila sampai batas waktu yang ditetapkan perangkat uji belum diambil, maka penyimpanan perangkat & segala akibatnya menjadi tanggung jawab Saudara.'));	
	$pdf->Row(array('','f. ','Estimasi pengujian dilaksanakan paling lambat bulan Juli 2017'));	
	
	$pdf->Ln(3);
	$pdf->SetFont('helvetica','',10);
	$pdf->SetWidths(array(13,7,160));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','3. ','Atas perhatian dan kerjasama Saudara kami ucapkan terimakasih.'));	
	
/*Footer Manual*/
	
/*End Footer Manual*/

	$pdf->Ln(3);
	$pdf->Cell(9);
	$pdf->Cell(150,5,"Bandung, ".$spb_date,0,0,'L');
	$pdf->Ln(20);
	$pdf->SetFont('helvetica','B',10);
	$pdf->SetFont('','BU');
	$pdf->Cell(9);
	$pdf->Cell(185,5,"SONTANG HUTAPEA",0,0,'L');
	$pdf->Ln(6);
	$pdf->SetFont('','B');
	$pdf->Cell(9);
	$pdf->Cell(185,5,"MANAGER USER RELATION",0,0,'L');
	
	$pdf->Output();
	exit;
});

Route::post('/editPengujian', 'PengujianController@edit');
// Route::get('/pengujian/{id}/edit', 'PengujianController@edit');
Route::get('/pengujian/{id}/detail', 'PengujianController@detail');
Route::get('/pengujian/{id}/pembayaran', 'PengujianController@pembayaran');
Route::get('/pengujian/download/{id}/{attach}/{jns}', 'PengujianController@download');
Route::get('/pengujian/{id}/downloadSPB', 'PengujianController@downloadSPB');
Route::get('/products/{id}/stel', 'ProductsController@downloadStel');
Route::post('/pengujian/pembayaran', 'PengujianController@uploadPembayaran');
Route::post('/pengujian/tanggaluji', 'PengujianController@updateTanggalUji');
Route::get('/cetakPengujian/{id}', 'PengujianController@details');
Route::get('/cetak/{namaPemohon}/{alamatPemohon}/{telpPemohon}/{faxPemohon}/{emailPemohon}/{jnsPerusahaan}/{namaPerusahaan}/{alamatPerusahaan}/{telpPerusahaan}/{faxPerusahaan}/{emailPerusahaan}/{nama_perangkat}/{merk_perangkat}/{kapasitas_perangkat}/{pembuat_perangkat}/{model_perangkat}/{referensi_perangkat}/{serialNumber}/{jnsPengujian}/{initPengujian}/{descPengujian}/{namaFile}', 
array('as' => 'cetak', function(
	$namaPemohon = null, $alamatPemohon = null, $telpPemohon = null, $faxPemohon = null, $emailPemohon = null, 
	$jnsPerusahaan = null, $namaPerusahaan = null, $alamatPerusahaan = null,
	$telpPerusahaan = null, $faxPerusahaan = null,$emailPerusahaan = null,$nama_perangkat = null,
	$merk_perangkat = null,$kapasitas_perangkat = null,$pembuat_perangkat = null,$model_perangkat = null,
	$referensi_perangkat = null,$serialNumber = null,$jnsPengujian = null,$initPengujian = null,$descPengujian = null,
	$namaFile = null ) {
		$pdf = new PDF_MC_Table();
		$pdf->jns_pengujian(urldecode($initPengujian),urldecode($descPengujian));
		$pdf->AliasNbPages();
		$pdf->AddPage();
	/*Data Pemohon*/
		$pdf->SetFont('helvetica','B',11);
		$pdf->Cell(27,5,"Data Pemohon ",0,0,'L');
		$pdf->SetFont('helvetica','',11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5," / Applicant's Data",0,0,'L');
		/*Nama Pemohon*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Nama Pemohon",0,0,'L');
		$pdf->SetWidths(array(0.00125,40,45,145));
		$pdf->Row(array("","",":",urldecode($namaPemohon)));
		$y2 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"Applicant's Name",0,0,'L');
			if(($y2 - $y) > 11){
				$yNow = $y2 - 6;
			}else{
				$yNow = $y2;
			}
		$pdf->setXY(10.00125,$yNow);
		/*Alamat Pemohon*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Alamat",0,0,'L');
		$pdf->SetWidths(array(0.00125,40,45,145));
		$pdf->Row(array("","",":",urldecode($alamatPemohon)));
		$pdf->Row(array("","",":",""));
		$y2 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"Address",0,0,'L');
			if(($y2 - $y) > 11){
				$yNow = $y2 - 6;
			}else{
				$yNow = $y2;
			}
		$pdf->setXY(10.00125,$yNow);
		/*Telepon dan Faksimile Pemohon*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(55.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Telepon",0,0,'L');
		$pdf->SetWidths(array(0.00125,70,75,35));
		$pdf->Row(array("","",":",urldecode($telpPemohon)));
		$pdf->Row(array("","",":",""));
		$y2 = $pdf->getY();
		$pdf->setXY(125.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Faksimile",0,0,'L');
		$pdf->SetWidths(array(0.00125,140,145,45));
		$pdf->Row(array("","",":",urldecode($faxPemohon)));
		$pdf->Row(array("","",":",""));
		$y3 = $pdf->getY();
		$pdf->setXY(55.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(70,5,"Telephone",0,0,'L');
		$pdf->Cell(10,5,"Facsimile",0,0,'L');
		$yNow = max($y,$y2,$y3);
		if($y2 == $y3){
			// $yNow;
		}else{
			$yNow = $yNow - 6;
		}
		$pdf->setXY(10.00125,$yNow);
		/*Email Pemohon*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(55.00125,$y + 6);
		$pdf->Cell(10,5,"E-mail",0,0,'L');
		$pdf->SetWidths(array(0.00125,70,75,115));
		$pdf->Row(array("","",":",urldecode($emailPemohon)));
		$pdf->Ln(2);
		$pdf->setX(10.00125);
	/*End Data Pemohon*/

	/*Data Perusahaan*/
		$pdf->SetFont('helvetica','B',11);
		$pdf->Cell(31,5,"Data Perusahaan ",0,0,'L');
		$pdf->SetFont('helvetica','',11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5," / Company's Data",0,0,'L');
		/*Jenis Perusahaan*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetFont('','U');
		// $pdf->Cell(190,5,"[ Pabrikan (Manufacture) ]",0,0,'C');
		$pdf->Cell(190,5,"[ ".urldecode($jnsPerusahaan)." ]",0,0,'C');
		/*Nama Perusahaan*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Nama Perusahaan",0,0,'L');
		$pdf->SetWidths(array(0.00125,40,45,145));
		$pdf->Row(array("","",":",urldecode($namaPerusahaan)));
		$y2 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"Company's Name",0,0,'L');
			if(($y2 - $y) > 11){
				$yNow = $y2 - 6;
			}else{
				$yNow = $y2;
			}
		$pdf->setXY(10.00125,$yNow);
		/*Alamat Perusahaan*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Alamat",0,0,'L');
		$pdf->SetWidths(array(0.00125,40,45,145));
		$pdf->Row(array("","",":",urldecode($alamatPerusahaan)));
		$y2 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"Address",0,0,'L');
			if(($y2 - $y) > 11){
				$yNow = $y2 - 6;
			}else{
				$yNow = $y2;
			}
		$pdf->setXY(10.00125,$yNow);
		/*Telepon dan Faksimile Perusahaan*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(55.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Telepon",0,0,'L');
		$pdf->SetWidths(array(0.00125,70,75,35));
		$pdf->Row(array("","",":",urldecode($telpPerusahaan)));
		$y2 = $pdf->getY();
		$pdf->setXY(125.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Faksimile",0,0,'L');
		$pdf->SetWidths(array(0.00125,140,145,45));
		$pdf->Row(array("","",":",urldecode($faxPerusahaan)));
		$y3 = $pdf->getY();
		$pdf->setXY(55.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(70,5,"Telephone",0,0,'L');
		$pdf->Cell(10,5,"Facsimile",0,0,'L');
		$yNow = max($y,$y2,$y3);
		if($y2 == $y3){
			// $yNow;
		}else{
			$yNow = $yNow - 6;
		}
		$pdf->setXY(10.00125,$yNow);
		/*Email Perusahaan*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(55.00125,$y + 6);
		$pdf->Cell(10,5,"E-mail",0,0,'L');
		$pdf->SetWidths(array(0.00125,70,75,115));
		$pdf->Row(array("","",":",urldecode($emailPerusahaan)));
		$pdf->Ln(2);
		$pdf->setX(10.00125);
	/*End Data Perusahaan*/

	/*Data Perangkat*/
		$pdf->SetFont('helvetica','B',11);
		$pdf->Cell(28,5,"Data Perangkat ",0,0,'L');
		$pdf->SetFont('helvetica','',11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5," / Equipment's Data",0,0,'L');
		/*Nama Perangkat*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Perangkat",0,0,'L');
		$pdf->SetWidths(array(0.00125,40,45,145));
		$pdf->Row(array("","",":",urldecode($nama_perangkat)));
		$y2 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"Equipment",0,0,'L');
			if(($y2 - $y) > 11){
				$yNow = $y2 - 6;
			}else{
				$yNow = $y2;
			}
		$pdf->setXY(10.00125,$yNow);
		/*Merek dan Model Perangkat*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Merek/Pabrik",0,0,'L');
		$pdf->SetWidths(array(0.00125,40,45,50));
		$pdf->Row(array("","",":",urldecode($merk_perangkat)));
		$y2 = $pdf->getY();
		$pdf->setXY(110.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Model/Tipe",0,0,'L');
		$pdf->SetWidths(array(0.00125,135,140,50));
		$pdf->Row(array("","",":",urldecode($model_perangkat)));
		$y3 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(100,5,"Merk",0,0,'L');
		$pdf->Cell(10,5,"Model/Type",0,0,'L');
		$yNow = max($y,$y2,$y3);
		if($y2 == $y3){
			/* // $yNow; */
		}else{
			$yNow = $yNow - 6;
		}
		$pdf->setXY(10.00125,$yNow);
		/*Kapasitas dan Referensi Uji Perangkat*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Kapasitas/Kecepatan",0,0,'L');
		$pdf->SetWidths(array(0.00125,40,45,50));
		$pdf->Row(array("","",":",urldecode($kapasitas_perangkat)));
		$y2 = $pdf->getY();
		$pdf->setXY(110.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Referensi Uji",0,0,'L');
		$pdf->SetWidths(array(0.00125,135,140,50));
		$pdf->Row(array("","",":",urldecode($referensi_perangkat)));
		$y3 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(100,5,"Capacity/Speed",0,0,'L');
		$pdf->Cell(10,5,"Test Reference",0,0,'L');
		$yNow = max($y,$y2,$y3);
		if($y2 == $y3){
			/* // $yNow; */
		}else{
			$yNow = $yNow - 6;
		}
		$pdf->setXY(10.00125,$yNow);
		/*Negara Pembuat Perangkat*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Negara Pembuat",0,0,'L');
		$pdf->SetWidths(array(0.00125,40,45,145));
		$pdf->Row(array("","",":",urldecode($pembuat_perangkat)));
		$y2 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"Made In",0,0,'L');
		$pdf->Ln(8);
		$pdf->setX(10.00125);
	/*End Data Perangkat*/

	/*Pernyataan*/
		$pdf->SetFont('helvetica','B',11);
		$pdf->Cell(21,5,"Pernyataan ",0,0,'L');
		$pdf->SetFont('helvetica','',11);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5," / Aggrement",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(15.00125,$y + 6);
		$pdf->Cell(5,5,"1. ",0,0,'L');
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Kami menyatakan bahwa permohonan ini telah diisi dengan keadaan yang sebenarnya.",0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"     Ensuring that we have filled this application form with eligible data.",0,0,'L');
		$pdf->Ln(6);
		$pdf->Cell(5,5,"2. ",0,0,'L');
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Kami telah mengetahui dan menyetujui spesifikasi uji tersebut yang digunakan sebagai acuan pengujian.",0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"     We had fully agreed and understand to the specification as stated above for testing reference.",0,0,'L');
		$pdf->Ln(6);
		$pdf->Cell(5,5,"3. ",0,0,'L');
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Kami menjamin bahwa merek, model, dan tipe barang yang Kami produksi/pasarkan sama dengan yang diujikan.",0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"     Ensuring that we reproduce/distribute the similar mark model and type with the tested item.",0,0,'L');
		$pdf->Ln(8);
		$pdf->setX(10.00125);
	/*End Data Pemohon*/

	/*Footer Manual*/
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(150,5,"     Bandung,",0,0,'R');
		$pdf->Ln(18);
		$pdf->SetFont('','U');
		$pdf->Cell(185,5,"                                        ",0,0,'R');
		$pdf->SetFont('helvetica','',8);
		$pdf->Ln(6);
		$pdf->SetFont('','U');
		$pdf->Cell(185,5,"NAMA PEMOHON & CAP PERUSAHAAN",0,0,'R');
		$pdf->Ln(4);
		$pdf->SetFont('','I');
		$pdf->Cell(185,5,"APPLICANT'S NAME & COMPANY STAMP",0,0,'R');
		$pdf->Ln(6);
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"User Relation, Divisi Digital Service, Telp. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"Divisi Digital Service, User Relation, Phone. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',8);
		$pdf->Cell(185,5,"IASO2/F/002 Versi 01",0,0,'R');
	/*End Footer Manual*/
		$pdf->Output();
		exit;
	}
));

Route::get('/cetakKuitansi/{id}', 'IncomeController@cetakKuitansi');
Route::get('/cetakHasilKuitansi/{nomor}/{dari}/{jumlah}/{untuk}', 
array('as' => 'cetakHasilKuitansi', function(
	$nomor = null, $dari = null, $jumlah = null, $untuk = null ) {
	$pdf = new PDF_MC_Table_Kuitansi('L','mm','A5'); 
	$terbilang = $pdf->terbilang($jumlah, 3);
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$pdf->SetFont('helvetica','B',11);
	
	$y = $pdf->getY()-20;
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(45.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(45,5,"Nomor",0,0,'L');
	$pdf->SetWidths(array(45.00125,25,5,95));
	$pdf->SetFont('','');
	$pdf->Row(array("","",":",$nomor));
	$y2 = $pdf->getY();
	$pdf->setXY(45.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(45,5,"Nr.",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(45.00125,$yNow);
	
	$pdf->Ln(2);
	$y = $pdf->getY();
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(45.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(45,5,"Sudah diterima dari",0,0,'L');
	$pdf->SetWidths(array(45.00125,25,5,95));
	$pdf->SetFont('','');
	$pdf->Row(array("","",":",$dari));
	$y2 = $pdf->getY();
	$pdf->setXY(45.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(45,5,"Receipt From",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(45.00125,$yNow);
	
	$pdf->Ln(2);
	$y = $pdf->getY();
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(45.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(45,5,"Banyak Uang",0,0,'L');
	$pdf->SetWidths(array(45.00125,25,5,95));
	$pdf->SetFont('','');
	$pdf->Row(array("","",":",$terbilang.' Rupiah'));
	$y2 = $pdf->getY();
	$pdf->setXY(45.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(45,5,"Amount",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(45.00125,$yNow);
	
	$pdf->Ln(2);
	$y = $pdf->getY();
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(45.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(45,5,"Untuk Pembayaran",0,0,'L');
	$pdf->SetWidths(array(45.00125,25,5,95));
	$pdf->SetFont('','');
	$pdf->Row(array("","",":",$untuk));
	$y2 = $pdf->getY();
	$pdf->setXY(45.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(45,5,"For Payment",0,0,'L');
		if(($y2 - $y) > 11){
			$yNow = $y2 - 6;
		}else{
			$yNow = $y2;
		}
	$pdf->setXY(45.00125,$yNow);

/*Footer Manual*/
	$pdf->Ln(22);
	$pdf->SetFont('helvetica','',10);
	$now = date('Y-m-d');
	setlocale(LC_ALL, 'IND');
	$date = date('j', strtotime($now))." ".strftime('%B %Y', strtotime($now));
	$pdf->Cell(280,5,"Bandung, ".$date,0,0,'C');
	$pdf->Ln();
	$pdf->Cell(280,5,"DIVISI DIGITAL SERVICE",0,0,'C');
	$pdf->Ln(25);
	$pdf->Cell(280,5,"                                        ",0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('','BU');
	$pdf->Cell(280,5,"SONTANG HUTAPEA",0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('helvetica','BU',12);
	$pdf->Cell(30);
	$pdf->Cell(0,5,"Rp. ".number_format($jumlah, 0, '.', ','),0,0,'L');
	$pdf->SetFont('helvetica','',9);
	$pdf->setXY(110,$pdf->getY());
	$pdf->Cell(0,5,"Sub Coord Product & Infrastructure User Relation",0,0,'C');
	// $pdf->SetFont('','U');
	// $pdf->Cell(185,5,"NAMA PEMOHON & CAP PERUSAHAAN",0,0,'R');
	// $pdf->Ln(4);
	// $pdf->SetFont('','I');
	// $pdf->Cell(185,5,"APPLICANT'S NAME & COMPANY STAMP",0,0,'R');
	// $pdf->Ln(6);
	// $pdf->SetFont('','U');
	// $pdf->Cell(10,5,"User Relation, Divisi Digital Service, Telp. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	// $pdf->Ln(4);
	// $pdf->SetFont('','I');
	// $pdf->Cell(10,5,"Divisi Digital Service, User Relation, Phone. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	// $pdf->Ln(6);
	// $pdf->SetFont('helvetica','',8);
	// $pdf->Cell(185,5,"IASO2/F/002 Versi 01",0,0,'R');
/*End Footer Manual*/
	$pdf->Output();
	exit;
	}
));

Route::get('/cetakUjiFungsi/{id}', 'ExaminationController@cetakUjiFungsi');
Route::get('/cetakHasilUjiFungsi/{company_name}/{company_address}/{company_phone}/{company_fax}/{device_name}/{device_mark}/{device_manufactured_by}/{device_model}/{device_serial_number}/{status}/{catatan}', 
array('as' => 'cetakHasilUjiFungsi', function(
	$company_name = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd", $company_address = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd", $company_phone = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd", $company_fax = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd", 
	$device_name = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd", $device_mark = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd", $device_manufactured_by = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd", $device_model = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd" , $device_serial_number = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd", 
	$status = null, $catatan = "asdasdasdashdhgasdghasghdcaghscdhgascghdcasghcdhagsd" ) {
	$pdf = new PDF_MC_Table(); 
	$pdf->judul_kop('FORM UJI FUNGSI','');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$pdf->Ln(10);
	$pdf->SetFont('helvetica','',11);
	$pdf->SetWidths(array(0.00125,50,140));
	$pdf->SetAligns(array('L','R','L'));
	// $pdf->SetFont('','BI');
	$pdf->RowRect(array('','Nama Perusahaan',$company_name));	
	$pdf->RowRect(array('','Alamat',$company_address));	
	$pdf->RowRect(array('','Telepon / Fax',$company_phone.' / '.$company_fax));	
	$pdf->RowRect(array('','Nama Perangkat',$device_name));	
	$pdf->RowRect(array('','Merek / Buatan',$device_mark.' / '.$device_manufactured_by));	
	$pdf->RowRect(array('','Tipe / Serial Number',$device_model.' / '.$device_serial_number));	
	$pdf->Ln(1);
	$pdf->Rect(10,$pdf->getY(),190,55);	
	$pdf->SetFont('','B');
	$pdf->Cell(180,10,'Hasil Uji Fungsi',0,0,'C');
	$pdf->Ln(1);
	if($status == 1){
		$pdf->SetFont('ZapfDingbats','', 10);
		$pdf->Cell(20);
		$pdf->Cell(4, 100, "4", 0, 0);
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(18,100,'Memenuhi',0,0,'C');
		$pdf->SetFont('ZapfDingbats','', 10);
		$pdf->Cell(30);
		$pdf->Cell(4, 100, "m", 0, 0);
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(8);
		$pdf->Cell(18,100,'Tidak Memenuhi',0,0,'C');
		$pdf->SetFont('ZapfDingbats','', 10);
		$pdf->Cell(40);
		$pdf->Cell(4, 100, "m", 0, 0);
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(4);
		$pdf->Cell(18,100,'Lain-lain',0,0,'C');
	}
	else if($status == 2){
		$pdf->SetFont('ZapfDingbats','', 10);
		$pdf->Cell(20);
		$pdf->Cell(4, 100, "m", 0, 0);
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(18,100,'Memenuhi',0,0,'C');
		$pdf->SetFont('ZapfDingbats','', 10);
		$pdf->Cell(30);
		$pdf->Cell(4, 100, "4", 0, 0);
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(8);
		$pdf->Cell(18,100,'Tidak Memenuhi',0,0,'C');
		$pdf->SetFont('ZapfDingbats','', 10);
		$pdf->Cell(40);
		$pdf->Cell(4, 100, "m", 0, 0);
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(4);
		$pdf->Cell(18,100,'Lain-lain',0,0,'C');
	}
	else if($status == 3){
		$pdf->SetFont('ZapfDingbats','', 10);
		$pdf->Cell(20);
		$pdf->Cell(4, 100, "m", 0, 0);
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(18,100,'Memenuhi',0,0,'C');
		$pdf->SetFont('ZapfDingbats','', 10);
		$pdf->Cell(30);
		$pdf->Cell(4, 100, "m", 0, 0);
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(8);
		$pdf->Cell(18,100,'Tidak Memenuhi',0,0,'C');
		$pdf->SetFont('ZapfDingbats','', 10);
		$pdf->Cell(40);
		$pdf->Cell(4, 100, "4", 0, 0);
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(4);
		$pdf->Cell(18,100,'Lain-lain',0,0,'C');
	}
	$pdf->Rect(10,$pdf->getY()+55,190,40);	
	$pdf->Ln(1);
	$pdf->Cell(180,100+15,'Diketahui oleh:',0,0,'C');
	$pdf->Rect(10,$pdf->getY()+55+40,190,40);	
	$pdf->Ln(1);
	$pdf->Cell(15);
	$pdf->Cell(18,100+25,'Officer Customer Relationship',0,0,'C');
	$pdf->Cell(50);
	$pdf->Cell(18,100+25,'Test Engineer Laboratorium',0,0,'C');
	$pdf->Cell(45);
	$pdf->Cell(18,100+25,'Customer',0,0,'C');
	$pdf->Ln(1);
	$pdf->Cell(15);
	$pdf->Cell(18,100+25+40,'____________________________',0,0,'C');
	$pdf->Cell(50);
	$pdf->Cell(18,100+25+40,'____________________________',0,0,'C');
	$pdf->Cell(45);
	$pdf->Cell(18,100+25+40,'____________________________',0,0,'C');
	$pdf->Ln(1);
	$pdf->Cell(18,100+25+40+10,'NIK.',0,0,'L');
	$pdf->Cell(50);
	$pdf->Cell(18,100+25+40+10,'NIK.',0,0,'L');
	$pdf->Ln($pdf->getY()+25);
	$pdf->SetWidths(array(5.00125,20,160));
	$pdf->SetAligns(array('L','L','L'));
	$pdf->Row(array('','Catatan:',$catatan));
	$pdf->Cell(18,50,'Beri tanda',0,0,'L');
	$pdf->SetFont('ZapfDingbats','', 10);
	$pdf->Cell(4, 50, "4", 0, 0);
	$pdf->SetFont('helvetica','',10);
	$pdf->Cell(20,50,'pada kolom',0,0,'L');
	$pdf->SetFont('','B');
	$pdf->Cell(35,50,'HASIL UJI FUNGSI',0,0,'L');

/*Footer Manual*/
	
/*End Footer Manual*/
	$pdf->Output();
	exit;
	}
));

Route::get('/', 'PermohonanController@createPermohonan');
Route::get('/health', function (){
	return 'ok';
}); 
Route::post('/client/login', 'ClientController@authenticate');
Route::get('/client/logout', 'ClientController@logout');
Route::get('/language/{lang}', 'HomeController@language');
Route::get('/about', 'HomeController@about');
Route::get('/sertifikasi', 'HomeController@sertifikasi');
Route::get('/contact', 'HomeController@contact');
Route::get('/procedure', 'HomeController@procedure');
Route::get('/process', 'HomeController@process');
Route::get('/detailprocess/{id}', 'HomeController@detail_process');
Route::get('/faq', 'HomeController@faq');

Route::group(['prefix' => '/admin', 'middlewareGroups' => 'web'], function () {
	Route::auth();
	Route::get('/logout', 'UserController@logout');
	Route::get('/', 'DashboardController@index');
	Route::get('/examination/download/{id}', 'ExaminationController@downloadForm');
	Route::get('/examination/media/download/{id}/{name}', 'ExaminationController@downloadMedia');
	Route::get('/examination/print/{id}', 'ExaminationController@printForm');
	Route::get('/examination/media/print/{id}/{name}', 'ExaminationController@printMedia');
	Route::get('/stel/media/{id}', 'STELController@viewMedia');
	Route::get('/company/media/{id}/{name}', 'CompanyController@viewMedia');
	Route::get('/device', 'DeviceController@index');
	Route::get('/devicenc', 'DevicencController@index');
	Route::get('/examination/revisi/{id}', 'ExaminationController@revisi');
	Route::get('/examination/harddelete/{id}', 'ExaminationController@destroy');
	Route::post('/examination/revisi', 'ExaminationController@updaterevisi');
	Route::post('/examination/{id}/tanggalkontrak', 'ExaminationController@tanggalkontrak');
	Route::post('/examination/{id}/generateSPBParam', 'ExaminationController@generateSPBParam');
	Route::get('/examination/generateSPB', 'ExaminationController@generateSPB');
	Route::post('/examination/generateSPB', 'ExaminationController@generateSPBData');
	Route::put('/user/profile/{id}', 'UserController@updateProfile');
	Route::resource('/article', 'ArticleController');
	Route::resource('/examination', 'ExaminationController');
	Route::resource('/stel', 'STELController');
	Route::resource('/charge', 'ExaminationChargeController');
	Route::resource('/calibration', 'CalibrationChargeController');
	Route::resource('/company', 'CompanyController');
	Route::resource('/user', 'UserController');
	Route::resource('/slideshow', 'SlideshowController');
	Route::resource('/footer', 'FooterController');
	Route::resource('/labs', 'ExaminationLabController');
	Route::resource('/myexam', 'MyExaminationController');
	Route::get('/feedback/{id}/reply', 'FeedbackController@reply');
	Route::post('/feedback/reply', 'FeedbackController@sendEmailReplyFeedback');
	Route::get('/feedback', 'FeedbackController@index');
	Route::get('/downloadUsman', 'DashboardController@downloadUsman');
	Route::post('/user/{id}/softDelete', 'UserController@softDelete');
	Route::get('/analytic', 'AnalyticController@index');
	Route::resource('/role', 'RoleController');
	Route::get('/downloadbukti/{id}', 'SalesController@viewMedia');
	// Route::get('/analytic', function(){
		// $visitor = Tracker::currentSession();
		// echo"<pre>";print_r($visitor);
	// });
	Route::resource('/privilege', 'PrivilegeController');
	Route::get('/topdashboard', 'TopDashboardController@index');
	Route::post('/topdashboard/searchGrafik', 'TopDashboardController@searchGrafik');
	Route::resource('/testimonial', 'TestimonialController');
	Route::resource('/tempcompany', 'TempCompanyController');
	Route::get('/tempcompany/media/{id}/{name}', 'TempCompanyController@viewMedia');
	
	Route::get('/adm_exam_autocomplete/{query}', 'ExaminationController@autocomplete')->name('adm_exam_autocomplete');
	Route::get('/adm_exam_done_autocomplete/{query}', 'ExaminationDoneController@autocomplete')->name('adm_exam_done_autocomplete');
	Route::get('/adm_dev_autocomplete/{query}', 'DevClientController@autocomplete')->name('dev_client_autocomplete');
	Route::get('/adm_feedback_autocomplete/{query}', 'FeedbackController@autocomplete')->name('adm_feedback_autocomplete');
	Route::get('/adm_article_autocomplete/{query}', 'ArticleController@autocomplete')->name('adm_article_autocomplete');
	Route::get('/adm_stel_autocomplete/{query}', 'STELController@autocomplete')->name('adm_stel_autocomplete');
	Route::get('/adm_charge_autocomplete/{query}', 'ExaminationChargeController@autocomplete')->name('adm_charge_autocomplete');
	Route::get('/adm_calibration_autocomplete/{query}', 'CalibrationChargeController@autocomplete')->name('adm_calibration_autocomplete');
	Route::get('/adm_slideshow_autocomplete/{query}', 'SlideshowController@autocomplete')->name('adm_slideshow_autocomplete');
	Route::get('/adm_labs_autocomplete/{query}', 'ExaminationLabController@autocomplete')->name('adm_labs_autocomplete');
	Route::get('/adm_company_autocomplete/{query}', 'CompanyController@autocomplete')->name('adm_company_autocomplete');
	Route::get('/adm_temp_company_autocomplete/{query}', 'TempCompanyController@autocomplete')->name('adm_temp_company_autocomplete');
	Route::get('/adm_user_autocomplete/{query}', 'UserController@autocomplete')->name('adm_user_autocomplete');
	Route::get('/adm_footer_autocomplete/{query}', 'FooterController@autocomplete')->name('adm_footer_autocomplete');
	Route::get('/adm_inc_autocomplete/{query}', 'IncomeController@autocomplete')->name('adm_inc_autocomplete');
	
	Route::post('/examination/{id}/generateSPKCode', 'ExaminationController@generateSPKCodeManual');
	Route::resource('/log', 'LogController');
	Route::get('/backup', 'BackupController@index');
	Route::get('/delete/{id}', 'BackupController@destroy');
	Route::post('/restore', 'BackupController@restore');
	
	Route::resource('/examinationdone', 'ExaminationDoneController');
	
	Route::resource('/income', 'IncomeController@index');
	
	Route::post('/myexam/{id}/tanggalkontrak', 'MyExaminationController@tanggalkontrak');
	Route::post('/myexam/{id}/generateSPBParam', 'MyExaminationController@generateSPBParam');
	Route::get('/myexam/generateSPB', 'MyExaminationController@generateSPB');
	Route::post('/myexam/generateSPB', 'MyExaminationController@generateSPBData');
	Route::post('/myexam/{id}/generateSPKCode', 'MyExaminationController@generateSPKCodeManual');
	Route::get('/history', 'HistoryController@index');
	Route::resource('/equipment', 'EquipmentController');
	Route::resource('/sales', 'SalesController');
	Route::resource('/question', 'QuestionController');
	Route::resource('/questionpriv', 'QuestionprivController');
	Route::get('/kuitansi', 'IncomeController@kuitansi');
	Route::get('/kuitansi/create', 'IncomeController@create');
	Route::post('/kuitansi/generateKuitansi', 'IncomeController@generateKuitansiManual');
	Route::post('/kuitansi', 'IncomeController@store');
	Route::get('/kuitansi/{id}/detail', 'IncomeController@detail');

});
	Route::get('/adm_dashboard_autocomplete/{query}', 'DashboardController@autocomplete')->name('adm_dashboard_autocomplete');
	
	Route::get('/examination/excel', 'ExaminationController@excel');
	Route::get('/device/excel', 'DeviceController@excel');
	Route::get('/company/excel', 'CompanyController@excel');
	Route::post('/company/importExcel', 'CompanyController@importExcel');
	Route::get('/income/excel', 'IncomeController@excel');
	Route::get('/log/excel', 'LogController@excel');
	Route::get('/examinationdone/excel', 'ExaminationDoneController@excel');
Route::get('/sales/excel', 'SalesController@excel');

Route::post('/submitPermohonan', 'PermohonanController@submit');
Route::post('/uploadPermohonan', 'PermohonanController@upload');
Route::post('/uploadPermohonanEdit', 'PermohonanController@uploadEdit');
Route::post('/cekPermohonan', 'PermohonanController@cekSNjnsPengujian');
Route::post('/getPemohon', 'PermohonanController@getInfo');
Route::post('/downloadFile', 'PermohonanController@downloadFile');
Route::post('/updatePermohonan', 'PermohonanController@update');
// Route::get('/cetakPermohonan', 'PermohonanController@cetak');
Route::post('/cekLogin', 'ClientController@cekLogin');
Route::resource('/pengujian', 'PengujianController');
Route::get('/pengujian', 'PengujianController@index');
Route::get('/pengujian/{id}/detail', 'PengujianController@detail');
Route::post('/testimonial', 'PengujianController@testimonial');
Route::post('/cekAmbilBarang', 'PengujianController@cekAmbilBarang');
Route::resource('/STELclient', 'STELClientController');
Route::get('/STELclient', 'STELClientController@index');
Route::resource('/STSELclient', 'STSELClientController');
Route::get('/STSELclient', 'STSELClientController@index');
Route::resource('/Chargeclient', 'ExaminationChargeClientController');
Route::get('/Chargeclient', 'ExaminationChargeClientController@index');
Route::resource('/Devclient', 'DevClientController');
Route::get('/Devclient', 'DevClientController@index');
Route::get('/client/profile', 'ProfileController@index');
Route::post('/client/profile', 'ProfileController@update');
Route::post('/client/company', 'ProfileController@updateCompany');
Route::get('/client/password/resetPass', function () {
   return view('client.passwords.email');
});
Route::post('/client/password/email', 'ResetPasswordController@postEmail');
Route::get('/client/password/reset/{token}', 'ResetPasswordController@getReset');
Route::post('/client/password/reset', 'ResetPasswordController@postReset');

Route::post('/filterPengujian', 'PengujianController@filter');
Route::post('/filterSTEL', 'STELClientController@filter');
Route::post('/filterCharge', 'ExaminationChargeClientController@filter');
Route::get('/register', 'ProfileController@register');
Route::post('/client/register', 'ProfileController@insert');

Route::post('/global/search', 'HomeController@search');
Route::post('/client/feedback', 'PermohonanController@feedback');

Route::get('/client/downloadUsman', 'HomeController@downloadUsman');
Route::get('mylogsbl', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

 
Route::get('/stel_autocomplete/{query}', 'STELClientController@autocomplete')->name('stel_autocomplete');
Route::get('/stsel_autocomplete/{query}', 'STSELClientController@autocomplete')->name('stsel_autocomplete');
Route::get('/charge_client_autocomplete/{query}', 'ExaminationChargeClientController@autocomplete')->name('charge_client_autocomplete');
Route::get('/dev_client_autocomplete/{query}', 'DevClientController@autocomplete')->name('dev_client_autocomplete');
Route::get('/pengujian_autocomplete/{query}', 'PengujianController@autocomplete')->name('pengujian_autocomplete');
 

Route::group(['prefix' => '/v1', 'middlewareGroups' => 'api'], function () {
	Route::get('/companies', 'v1\CompanyAPIController@getCompanies');
	Route::get('/customer', 'v1\CustomerAPIController@getCustomer');
	Route::get('/stel', 'v1\StelAPIController@getStelData');
	Route::get('/device', 'v1\DeviceAPIController@getDeviceData');
	Route::get('/examination', 'v1\ExaminationAPIController@getExaminationData');
	Route::get('/examination/applicants', 'v1\ExaminationAPIController@getExaminationByApplicants');
	Route::get('/examination/companies', 'v1\ExaminationAPIController@getExaminationByCompany');
	Route::get('/examination/devices', 'v1\ExaminationAPIController@getExaminationByDevice');
	Route::get('/spk', 'v1\ExaminationAPIController@getSpk');
	Route::get('/function_test', 'v1\ExaminationAPIController@getFunctionTest');
	Route::get('/examination_histories', 'v1\ExaminationAPIController@getExaminationHistory');
	Route::post('/updateFunctionDate', 'v1\ExaminationAPIController@updateFunctionDate');
	Route::post('/updateEquipLoc', 'v1\ExaminationAPIController@updateEquipLoc');
	Route::post('/updateDeviceTE', 'v1\ExaminationAPIController@updateDeviceTE');
	Route::post('/updateFunctionStat', 'v1\ExaminationAPIController@updateFunctionStat');
	Route::post('/updateSpkStat', 'v1\ExaminationAPIController@updateSpkStat');
	Route::post('/sendLapUji', 'v1\ExaminationAPIController@sendLapUji');
	Route::post('/sendSertifikat', 'v1\ExaminationAPIController@sendSertifikat');
});

Route::get('/do_backup', 'BackupController@backup'); 

Route::get('/login', 'ProfileController@login');
 
Route::get('/products', 'ProductsController@index'); 
Route::resource('/products', 'ProductsController');

Route::get('/payment_status', 'ProductsController@payment_status');
Route::post('/checkout', 'ProductsController@checkout');
Route::post('/doCheckout', 'ProductsController@doCheckout');
Route::get('/payment_detail/{id}', 'ProductsController@payment_detail');
Route::get('/test_notifitcation', 'ProductsController@test_notifitcation');
Route::get('/upload_payment/{id}', 'ProductsController@upload_payment');
Route::post('/pembayaranstel', 'ProductsController@pembayaranstel');

Route::post('/insertKuisioner', 'PengujianController@insertKuisioner');
