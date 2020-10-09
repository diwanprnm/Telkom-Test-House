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
use Anouar\Fpdf\Fpdf as FPDF;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Illuminate\Http\Request;
// use Session;

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

// class PDF_MC_TablesKonsumen extends FPDF{
// 	var $widths;
// 	var $aligns;
	
// 	function SetWidths($w)
// 	{
// 		//Set the array of column widths
// 		$this->widths=$w;
// 	}

// 	function SetAligns($a)
// 	{
// 		//Set the array of column alignments
// 		$this->aligns=$a;
// 	}

// 	function Row($data)
// 	{
// 		//Calculate the height of the row
// 		$nb=0;
// 		for($i=0;$i<count($data);$i++)
// 			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
// 		$h=5*$nb;
// 		//Issue a page break first if needed
// 		$this->CheckPageBreak($h);
// 		//Draw the cells of the row
// 		for($i=0;$i<count($data);$i++)
// 		{
// 			$w=$this->widths[$i];
// 			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
// 			//Save the current position
// 			if($i==0){
// 				$x = 10.00125;
// 			}else{
// 				$x=$this->GetX();
// 			}
// 			$y=$this->GetY();
// 			//Print the text
// 			// $this->SetFont('Arial','',10);
// 			$this->MultiCell($w,5,$data[$i],0,$a);
// 			//Put the position to the right of the cell
// 			$this->SetXY($x+$w,$y);
// 		}
// 		//Go to the next line
// 		$this->Ln($h);
// 	}
	
// 	function RowRect($data)
// 	{
// 		//Calculate the height of the row
// 		$nb=0;
// 		for($i=0;$i<count($data);$i++)
// 			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
// 		$h=5*$nb;
// 		//Issue a page break first if needed
// 		$this->CheckPageBreak($h);
// 		//Draw the cells of the row
// 		for($i=0;$i<count($data);$i++)
// 		{
// 			$w=$this->widths[$i];
// 			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
// 			//Save the current position
// 			if($i==0){
// 				$x = 10.00125;
// 			}else{
// 				$x=$this->GetX();
// 			}
// 			$y=$this->GetY();
// 			//Draw the border
// 			if($i>0){
// 				$this->Rect($x,$y,$w,$h);
// 			}
// 			//Print the text
// 			// $this->SetFont('Arial','',10);
// 			$this->MultiCell($w,5,$data[$i],0,$a);
// 			//Put the position to the right of the cell
// 			$this->SetXY($x+$w,$y);
// 		}
// 		//Go to the next line
// 		$this->Ln($h);
// 	}

// 	function CheckPageBreak($h)
// 	{
// 		//If the height h would cause an overflow, add a new page immediately
// 		if($this->GetY()+$h>$this->PageBreakTrigger)
// 			$this->AddPage($this->CurOrientation);
// 			$this->setLeftMargin(15);
// 	}

// 	function NbLines($w,$txt)
// 	{
// 		//Computes the number of lines a MultiCell of width w will take
// 		$cw=&$this->CurrentFont['cw'];
// 		if($w==0)
// 			$w=$this->w-$this->rMargin-$this->x;
// 		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
// 		$s=str_replace("\r",'',$txt);
// 		$nb=strlen($s);
// 		if($nb>0 and $s[$nb-1]=="\n")
// 			$nb--;
// 		$sep=-1;
// 		$i=0;
// 		$j=0;
// 		$l=0;
// 		$nl=1;
// 		while($i<$nb)
// 		{
// 			$c=$s[$i];
// 			if($c=="\n")
// 			{
// 				$i++;
// 				$sep=-1;
// 				$j=$i;
// 				$l=0;
// 				$nl++;
// 				continue;
// 			}
// 			if($c==' ')
// 				$sep=$i;
// 			$l+=$cw[$c];
// 			if($l>$wmax)
// 			{
// 				if($sep==-1)
// 				{
// 					if($i==$j)
// 						$i++;
// 				}
// 				else
// 					$i=$sep+1;
// 				$sep=-1;
// 				$j=$i;
// 				$l=0;
// 				$nl++;
// 			}
// 			else
// 				$i++;
// 		}
// 		return $nl;
// 	}
	
// 	public $param1;
// 	public $param2;
// 	function jns_pengujian($param1,$param2) {
// 		$this->param1 = $param1;
// 		$this->param2 = $param2;
// 	}
	
// 	public $judul;
// 	public $title;
// 	function judul_kop($judul,$title) {
// 		$this->judul = $judul;
// 		$this->title = $title;
// 	}
	
// 	function Header()
// 	{
		 
// 	}
// 	//Page footer
// 	function Footer()
// 	{
// 		//Position at 1.5 cm from bottom
// 		$this->SetY(-6);
// 		//Arial italic 8
// 		$this->SetFont('helvetica','I',11);
// 		//Page number
// 		$this->Cell(0,0.1,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		
// 		// $this->Cell(130,0.1,'Bandung',0,0,'R');
		
// 	}
	
// 	/**
// 	 * Draws text within a box defined by width = w, height = h, and aligns
// 	 * the text vertically within the box ($valign = M/B/T for middle, bottom, or top)
// 	 * Also, aligns the text horizontally ($align = L/C/R/J for left, centered, right or justified)
// 	 * drawTextBox uses drawRows
// 	 *
// 	 * This function is provided by TUFaT.com
// 	 */
// 	function drawTextBox($strText, $w, $h, $align='L', $valign='T', $border=true)
// 	{
// 		$xi=$this->GetX();
// 		$yi=$this->GetY();
		
// 		$hrow=$this->FontSize;
// 		$textrows=$this->drawRows($w, $hrow, $strText, 0, $align, 0, 0, 0);
// 		$maxrows=floor($h/$this->FontSize);
// 		$rows=min($textrows, $maxrows);

// 		$dy=0;
// 		if (strtoupper($valign)=='M')
// 			$dy=($h-$rows*$this->FontSize)/2;
// 		if (strtoupper($valign)=='B')
// 			$dy=$h-$rows*$this->FontSize;

// 		$this->SetY($yi+$dy);
// 		$this->SetX($xi);

// 		$this->drawRows($w, $hrow, $strText, 0, $align, false, $rows, 1);

// 		if ($border)
// 			$this->Rect($xi, $yi, $w, $h);
// 	}

// 	function drawRows($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0, $prn=0)
// 	{
// 		$cw=&$this->CurrentFont['cw'];
// 		if($w==0)
// 			$w=$this->w-$this->rMargin-$this->x;
// 		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
// 		$s=str_replace("\r", '', $txt);
// 		$nb=strlen($s);
// 		if($nb>0 && $s[$nb-1]=="\n")
// 			$nb--;
// 		$b=0;
// 		if($border)
// 		{
// 			if($border==1)
// 			{
// 				$border='LTRB';
// 				$b='LRT';
// 				$b2='LR';
// 			}
// 			else
// 			{
// 				$b2='';
// 				if(is_int(strpos($border, 'L')))
// 					$b2.='L';
// 				if(is_int(strpos($border, 'R')))
// 					$b2.='R';
// 				$b=is_int(strpos($border, 'T')) ? $b2.'T' : $b2;
// 			}
// 		}
// 		$sep=-1;
// 		$i=0;
// 		$j=0;
// 		$l=0;
// 		$ns=0;
// 		$nl=1;
// 		while($i<$nb)
// 		{
// 			//Get next character
// 			$c=$s[$i];
// 			if($c=="\n")
// 			{
// 				//Explicit line break
// 				if($this->ws>0)
// 				{
// 					$this->ws=0;
// 					if ($prn==1) $this->_out('0 Tw');
// 				}
// 				if ($prn==1) {
// 					$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
// 				}
// 				$i++;
// 				$sep=-1;
// 				$j=$i;
// 				$l=0;
// 				$ns=0;
// 				$nl++;
// 				if($border && $nl==2)
// 					$b=$b2;
// 				if ( $maxline && $nl > $maxline )
// 					return substr($s, $i);
// 				continue;
// 			}
// 			if($c==' ')
// 			{
// 				$sep=$i;
// 				$ls=$l;
// 				$ns++;
// 			}
// 			$l+=$cw[$c];
// 			if($l>$wmax)
// 			{
// 				//Automatic line break
// 				if($sep==-1)
// 				{
// 					if($i==$j)
// 						$i++;
// 					if($this->ws>0)
// 					{
// 						$this->ws=0;
// 						if ($prn==1) $this->_out('0 Tw');
// 					}
// 					if ($prn==1) {
// 						$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
// 					}
// 				}
// 				else
// 				{
// 					if($align=='J')
// 					{
// 						$this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
// 						if ($prn==1) $this->_out(sprintf('%.3F Tw', $this->ws*$this->k));
// 					}
// 					if ($prn==1){
// 						$this->Cell($w, $h, substr($s, $j, $sep-$j), $b, 2, $align, $fill);
// 					}
// 					$i=$sep+1;
// 				}
// 				$sep=-1;
// 				$j=$i;
// 				$l=0;
// 				$ns=0;
// 				$nl++;
// 				if($border && $nl==2)
// 					$b=$b2;
// 				if ( $maxline && $nl > $maxline )
// 					return substr($s, $i);
// 			}
// 			else
// 				$i++;
// 		}
// 		//Last chunk
// 		if($this->ws>0)
// 		{
// 			$this->ws=0;
// 			if ($prn==1) $this->_out('0 Tw');
// 		}
// 		if($border && is_int(strpos($border, 'B')))
// 			$b.='B';
// 		if ($prn==1) {
// 			$this->Cell($w, $h, substr($s, $j, $i-$j), $b, 2, $align, $fill);
// 		}
// 		$this->x=$this->lMargin;
// 		return $nl;
// 	}
	
// 	/* function terbilang($satuan)
// 	{    
// 		$huruf = array ("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh","Sebelas"); 
// 			if ($satuan < 12)   
// 				return " ".$huruf[$satuan];
// 			elseif ($satuan < 20)   
// 				return $this->terbilang($satuan - 10)." Belas ";  
// 			elseif ($satuan < 100)    
// 				return $this->terbilang($satuan / 10)." Puluh ".$this->terbilang($satuan % 10);  
// 			elseif ($satuan < 200)    
// 				return " Seratus".$this->terbilang($satuan - 100);
// 			elseif ($satuan < 1000)    
// 				return $this->terbilang($satuan / 100)." Ratus ".$this->terbilang($satuan % 100);   
// 			elseif ($satuan < 2000)    
// 				return "Seribu ".$this->terbilang($satuan - 1000);  
// 			elseif ($satuan < 1000000)  
// 				return $this->terbilang($satuan / 1000)." Ribu ".$this->terbilang($satuan % 1000); 
// 			elseif ($satuan < 1000000000)    
// 				return $this->terbilang($satuan / 1000000)." Juta ".$this->terbilang($satuan % 1000000);  
// 			//elseif ($satuan >= 1000000000)   
// 	} */
// 	function kekata($x) {
// 		$x = abs($x);
// 		$angka = array("", "satu", "dua", "tiga", "empat", "lima",
// 		"enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
// 		$temp = "";
// 		if ($x <12) {
// 		// $temp = " ". $angka&#91;$x&#93;;
// 		$temp = " ". $angka[$x];
// 		} else if ($x <20) {
// 		$temp = $this->kekata($x - 10). " belas";
// 		} else if ($x <100) {
// 		$temp = $this->kekata($x/10)." puluh". $this->kekata($x % 10);
// 		} else if ($x <200) {
// 		$temp = " seratus" . $this->kekata($x - 100);
// 		} else if ($x <1000) {
// 		$temp = $this->kekata($x/100) . " ratus" . $this->kekata($x % 100);
// 		} else if ($x <2000) {
// 		$temp = " seribu" . $this->kekata($x - 1000);
// 		} else if ($x <1000000) {
// 		$temp = $this->kekata($x/1000) . " ribu" . $this->kekata($x % 1000);
// 		} else if ($x <1000000000) {
// 		$temp = $this->kekata($x/1000000) . " juta" . $this->kekata($x % 1000000);
// 		} else if ($x <1000000000000) {
// 		$temp = $this->kekata($x/1000000000) . " milyar" . $this->kekata(fmod($x,1000000000));
// 		} else if ($x <1000000000000000) {
// 		$temp = $this->kekata($x/1000000000000) . " trilyun" . $this->kekata(fmod($x,1000000000000));
// 		}
// 		return $temp;
// 	}
	
// 	function terbilang($x, $style=4) {
// 		if($x<0) {
// 		$hasil = "minus ". trim($this->kekata($x));
// 		} else {
// 		$hasil = trim($this->kekata($x));
// 		}
// 		switch ($style) {
// 			case 1:
// 			$hasil = strtoupper($hasil);
// 			break;
// 			case 2:
// 			$hasil = strtolower($hasil);
// 			break;
// 			case 3:
// 			$hasil = ucwords($hasil);
// 			break;
// 			default:
// 			$hasil = ucfirst($hasil);
// 			break;
// 		}
// 		return $hasil;
// 	}
// }



/**
* 
*/
// class WatermakStel extends FPDI{
// 	var $extgstates = array();

// 	// alpha: real value from 0 (transparent) to 1 (opaque)
// 	// bm:    blend mode, one of the following:
// 	//          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
// 	//          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
// 	function SetAlpha($alpha, $bm='Normal')
// 	{
// 		// set alpha for stroking (CA) and non-stroking (ca) operations
// 		$gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
// 		$this->SetExtGState($gs);
// 	}

// 	function AddExtGState($parms)
// 	{
// 		$n = count($this->extgstates)+1;
// 		$this->extgstates[$n]['parms'] = $parms;
// 		return $n;
// 	}

// 	function SetExtGState($gs)
// 	{
// 		$this->_out(sprintf('/GS%d gs', $gs));
// 	} 

// 	function _putextgstates()
// 	{
// 		for ($i = 1; $i <= count($this->extgstates); $i++)
// 		{
// 			$this->_newobj();
// 			$this->extgstates[$i]['n'] = $this->n;
// 			$this->_out('<</Type /ExtGState');
// 			$parms = $this->extgstates[$i]['parms'];
// 			$this->_out(sprintf('/ca %.3F', $parms['ca']));
// 			$this->_out(sprintf('/CA %.3F', $parms['CA']));
// 			$this->_out('/BM '.$parms['BM']);
// 			$this->_out('>>');
// 			$this->_out('endobj');
// 		}
// 	}

// 	function _putresourcedict()
// 	{
// 		parent::_putresourcedict();
// 		$this->_out('/ExtGState <<');
// 		foreach($this->extgstates as $k=>$extgstate)
// 			$this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
// 		$this->_out('>>');
// 	}

// 	function _putresources()
// 	{
// 		$this->_putextgstates();
// 		parent::_putresources();
// 	}
// }

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
	$pdf = new PDF_MC_Table_Permohonan();
	$pdf->jns_pengujian($data[0]['initPengujian'],$data[0]['initPengujian']);
	$kop = '';
	if($data[0]['initPengujian'] == 'QA'){
		$kop = 'MUTU';
	}
	else if($data[0]['initPengujian'] == 'TA'){
		$kop = 'TIPE';
	}
	else if($data[0]['initPengujian'] == 'VT'){
		$kop = 'PESAN';
	}
	else if($data[0]['initPengujian'] == 'CAL'){
		$kop = 'KALIBRASI';
	}
	$pdf->judul_kop(
	// 'PERMOHONAN UJI MUTU ('.$data[0]['initPengujian'].')', //IAS
	'PERMOHONAN UJI '.$kop.' - '.strtoupper(urldecode($data[0]['descPengujian'])),
	$data[0]['descPengujian'].' Testing Application');
	$pdf->AliasNbPages();
	$pdf->AddPage();
/*Data Pemohon*/
	$pdf->SetFont('helvetica','B',9);
	$pdf->Cell(190,1,"No. Reg ".$data[0]['no_reg'],0,0,'R');
	$pdf->Ln(1);
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
	$pdf->setXY(10.00125,$y + 6);
	// $pdf->SetFont('','U');
	$pdf->Cell(10,5,"Nomor HP",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['telepon_pemohon']));
	$y2 = $pdf->getY();
	$pdf->setXY(100.00125,$y + 6);
	// $pdf->SetFont('','U');
	$pdf->Cell(10,5,"E-Mail",0,0,'L');
	$pdf->SetWidths(array(0.00125,110,120,70));
	$pdf->Row(array("","",":",$data[0]['email_pemohon']));
	/*$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Telephone",0,0,'L');
	$pdf->Cell(10,5,"Facsimile",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		// $yNow;
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);*/
	/*Email Pemohon*/
	/*$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->Cell(10,5,"E-mail",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",$data[0]['email_pemohon']));*/
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
	switch ($data[0]['jns_perusahaan']) {
		case 'Agen':
			$jnsPerusahaan_in = 'Agen/Perwakilan';
			$jnsPerusahaan_en = 'Agent/Distributor';
			break;
		
		case 'Pabrikan':
			$jnsPerusahaan_in = 'Pabrikan';
			$jnsPerusahaan_en = 'Manufacture';
			break;
		
		case 'Perorangan':
			$jnsPerusahaan_in = 'Pengguna/Perorangan';
			$jnsPerusahaan_en = 'User/Private';
			break;
		
		default:
			$jnsPerusahaan_in = 'Tidak Diketahui';
			$jnsPerusahaan_en = 'Unknown';

			break;
	}
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',12);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','BU');
	$pdf->Cell(190,5,$jnsPerusahaan_in,0,0,'C');
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',12);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','I');
	$pdf->Cell(190,5,$jnsPerusahaan_en,0,0,'C');
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
	if($data[0]['jnsPengujian'] == 2){
		/*PLG_ID dan NIB*/
		$y = $pdf->getY();
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->Cell(10,5,"PLG_ID",0,0,'L');
		$pdf->SetWidths(array(0.00125,40,45,50));
		$pdf->Row(array("","",":",$data[0]['plg_id_perusahaan']));
		$y2 = $pdf->getY();
		$pdf->setXY(120.00125,$y + 6);
		$pdf->Cell(10,5,"NIB",0,0,'L');
		$pdf->SetWidths(array(0.00125,135,140,50));
		$pdf->Row(array("","",":",$data[0]['nib_perusahaan']));
		$yNow = max($y,$y2);
		$yNow = $yNow - 4;
		$pdf->setXY(10.00125,$yNow);
	}
	/*Telepon dan Faksimile Perusahaan*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	// $pdf->SetFont('','U');
	$pdf->Cell(10,5,"Telepon",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['telepon_perusahaan']));
	$y2 = $pdf->getY();
	$pdf->setXY(120.00125,$y + 6);
	// $pdf->SetFont('','U');
	$pdf->Cell(10,5,"Faksimile",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['faksimile_perusahaan']));
	$pdf->setX(10.00125);
	/*$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Telephone",0,0,'L');
	$pdf->Cell(10,5,"Facsimile",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		// $yNow;
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);*/
	/*Email Perusahaan*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 3);
	$pdf->Cell(10,5,"E-Mail",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,65));
	$pdf->Row(array("","",":",$data[0]['email_perusahaan']));
	$y2 = $pdf->getY();
	$pdf->setXY(120.00125,$y + 3);
	// $pdf->setXY(110.00125,$y + 6);
	// $pdf->SetFont('','U');
	$pdf->Cell(10,5,"NPWP",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['npwp_perusahaan']));
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
	// $pdf->SetFont('','U');
	$pdf->Cell(10,5,"Merek/Pabrik",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['merek_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y + 6);
	// $pdf->SetFont('','U');
	$pdf->Cell(10,5,"Model/Tipe",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['model_perangkat']));
	$y3 = $pdf->getY();
	$yNow = max($y,$y2,$y3);
	/*$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Merk",0,0,'L');
	$pdf->Cell(10,5,"Model/Type",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		
	}else{
		$yNow = $yNow - 6;
	}*/
	$pdf->setXY(10.00125,$yNow);
	/*Kapasitas dan Referensi Uji Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 3);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Kapasitas/Kecepatan",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['kapasitas_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y + 3);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Referensi Uji",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['referensi_perangkat']));
	$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 7);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Capacity/Speed",0,0,'L');
	$pdf->Cell(10,5,"Test Reference",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		/* // $yNow; */
	}else{
		$yNow = $yNow - 3;
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
if($data[0]['jnsPengujian'] == 4){
/*Metoda Kalibrasi*/
	$pdf->SetFont('helvetica','B',11);
	$pdf->Cell(37,5,"Metoda Kalibrasi WI",0,0,'L');
	$pdf->SetFont('helvetica','',11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5," / Calibration Method (WI)",0,0,'L');
	$y = $pdf->getY();
	$pdf->Ln(8);
	$pdf->setX(10.00125);
/*End Metoda Kalibrasi*/
}
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
	$pdf->Cell(10,5,"     We had fully informed and agreed to the spesification as stated above for testing reference.",0,0,'L');
	$pdf->Ln(6);
	$pdf->Cell(5,5,"3. ",0,0,'L');
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Kami menjamin bahwa merek, model, dan tipe barang yang Kami produksi/pasarkan sama dengan yang diujikan.",0,0,'L');
	$pdf->Ln(4);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"     Ensuring that we reproduce/distribute the similar brand, model and type with the tested item.",0,0,'L');
	$pdf->Ln(6);
	$pdf->Cell(5,5,"4. ",0,0,'L');
	if($data[0]['initPengujian'] == 'TA'){
		$pdf->SetFont('','U');
		$pdf->Cell(10,5,"Untuk uji EMC, Kami telah menyatakan bahwa perangkat yang diuji bebas dari modifikasi.",0,0,'L');
		
		$pdf->Ln(4);
		$pdf->SetFont('','I');
		$pdf->Cell(10,5,"     For EMC Test, We certified the tested item is modification-free device.",0,0,'L');

		$pdf->Ln(6);
		$pdf->SetFont('','');
		$pdf->Cell(5,5,"5. ",0,0,'L');
	}
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Kami menyatakan bahwa perangkat yang akan diuji sesuai dengan dokumen perangkat. Apabila perangkat",0,0,'L');
	$pdf->Ln(4);
	$pdf->SetFont('','');
	$pdf->Cell(5,5,"     ",0,0,'L');
	$pdf->SetFont('','U');
	$pdf->Cell(5,5,"terbukti tidak benar/tidak sah, maka permohonan dinyatakan batal dan dikenakan sanksi penundaan",0,0,'L');
	$pdf->Ln(4);
	$pdf->SetFont('','');
	$pdf->Cell(5,5,"     ",0,0,'L');
	$pdf->SetFont('','U');
	$pdf->Cell(5,5,"permohonan registrasi uji berikutnya.",0,0,'L');
	$pdf->Ln(4);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"     We certified the tested item is in accordance with device's document/data sheet. If the tested item is proven to be",0,0,'L');
	$pdf->Ln(4);
	$pdf->Cell(10,5,"     incompatible/invalid, it shall thereupon be canceled, in addition we wil be subjected to a postponement of the",0,0,'L');
	$pdf->Ln(4);
	$pdf->Cell(10,5,"     application for the next testing registration.",0,0,'L');
	$pdf->Ln(8);
	$pdf->setX(10.00125);
/*End Data Pemohon*/

/*Footer Manual*/
	/*$pdf->SetFont('helvetica','',10);
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
	$pdf->Cell(10,5,"User Relation, Divisi Digital Business, Telp. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	$pdf->Ln(4);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Divisi Digital Business, User Relation, Phone. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',8);
	if($data[0]['initPengujian'] == 'QA'){
		$pdf->Cell(185,5,"IAS02/F/001 Versi 01",0,0,'R');		
	}
	else if($data[0]['initPengujian'] == 'TA'){
		$pdf->Cell(185,5,"IAS02/F/002 Versi 01",0,0,'R');		
	}
	else if($data[0]['initPengujian'] == 'VT'){
		$pdf->Cell(185,5,"IAS02/F/003 Versi 01",0,0,'R');		
	}
	else if($data[0]['initPengujian'] == 'CAL'){
		$pdf->Cell(185,5,"IAS02/F/004 Versi 01",0,0,'R');		
	}*/
/*End Footer Manual*/
	$pdf->Output();
	exit;
});

Route::get('cetakKontrak', function(Illuminate\Http\Request $request){
	/*$client = new Client([
		'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
		// Base URI is used with relative requests
		'base_uri' => config("app.url_api_bsp"),
		// You can set any number of default request options.
		'timeout'  => 60.0,
	]);
		$res_user_info = $client->get('user/userInfo')->getBody();
		$user_info = json_decode($res_user_info);*/

	// Instanciation of inherited class
		$data = $request->session()->get('key_contract');
	$pdf = new PDF_MC_Table();
	if($data[0]['is_loc_test'] == 1){
		$pdf->judul_kop('KONTRAK UJI LOKASI DALAM NEGERI','On-Site Testing Contract');
	}else{
		$pdf->judul_kop('KONTRAK PENGUJIAN','Testing Contract');
	}
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('helvetica','B',9);
	$pdf->Cell(190,1,"No. Reg ".$data[0]['no_reg'],0,0,'R');
	$pdf->Ln(1);
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
	/*Alamat Pemohon*/
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
	$pdf->Ln(6);
	$pdf->setX(10.00125);
	/*Merek dan Model Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y);
	$pdf->Cell(10,5,"PLG_ID *)",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$plg_id = $data[0]['jns_pengujian'] == 2 ? $data[0]['plg_id'] : '-';
	$pdf->Row(array("","",":",$plg_id));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y);
	$pdf->Cell(10,5,"NIB *)",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$nib = $data[0]['jns_pengujian'] == 2 ? $data[0]['nib'] : '-';
	$pdf->Row(array("","",":",$nib));
	$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 5);
	/*$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Merk",0,0,'L');
	$pdf->Cell(10,5,"Model/Type",0,0,'L');*/
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		/* // $yNow; */
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);
	$pdf->Ln(4);
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
	// $pdf->SetFont('','U');
	$pdf->Cell(10,5,"Merk/Pabrik",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['merek_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y + 6);
	// $pdf->SetFont('','U');
	$pdf->Cell(10,5,"Model/Type",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['model_perangkat']));
	$y3 = $pdf->getY();
	/*$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Merk",0,0,'L');
	$pdf->Cell(10,5,"Model/Type",0,0,'L');*/
	$yNow = max($y,$y2,$y3);
	/*if($y2 == $y3){
	
	}else{
		$yNow = $yNow - 6;
	}*/
	$pdf->setXY(10.00125,$yNow);
	/*Kapasitas dan Referensi Uji Perangkat*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 3);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Kapasitas/Kecepatan",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['kapasitas_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y + 3);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Referensi Uji",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",$data[0]['referensi_perangkat']));
	$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 7);
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
	$pdf->SetWidths(array(0.00125,40,45,50));
	$pdf->Row(array("","",":",$data[0]['pembuat_perangkat']));
	$y2 = $pdf->getY();
	$pdf->setXY(110.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Pilihan Item Uji *)",0,0,'L');
	$pdf->SetWidths(array(0.00125,135,140,50));
	$pdf->Row(array("","",":",'ALL / PARTIAL **)'));
	$y3 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(100,5,"Made In",0,0,'L');
	$pdf->Cell(10,5,"Test Item Choice",0,0,'L');
	$yNow = max($y,$y2,$y3);
	if($y2 == $y3){
		/* // $yNow; */
	}else{
		$yNow = $yNow - 6;
	}
	$pdf->setXY(10.00125,$yNow);
	/*Keterangan*/
	$y = $pdf->getY();
	$pdf->Ln(6);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(10.00125,$y + 6);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"Keterangan",0,0,'L');
	$pdf->SetWidths(array(0.00125,40,45,145));
	$pdf->Row(array("","",":",''));
	$y2 = $pdf->getY();
	$pdf->setXY(10.00125,$y + 11);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Note",0,0,'L');
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
	// $pdf->Cell(4, 4, "", 1, 0);
	if($data[0]['is_loc_test'] == 1){
		$pdf->Cell(4,4,"1.",0,0,'L');
		$pdf->Cell(10,4,"Kesepakatan yang tertuang dalam Technical Meeting adalah benar.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		$pdf->Cell(4,4,"2.",0,0,'L');
		$pdf->Cell(10,4,"Biaya uji lokasi (biaya pengujian, transportasi, dan akomodasi) sesuai dengan SPB yang telah diterbitkan oleh",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"TELKOM.",0,0,'L');
		// $pdf->Cell(4, 4, "", 1, 0);
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		// $pdf->Cell(4, 4, "", 1, 0);
		$pdf->Cell(4,4,"3.",0,0,'L');
		$pdf->Cell(10,4,"Pelanggan memahami dan menentukan Referensi Uji yang akan digunakan, memahami item uji, dan konfigurasi uji.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		// $pdf->Cell(4, 4, "", 1, 0);
		$pdf->Cell(4,4,"4.",0,0,'L');
		$pdf->Cell(10,4,"Pelanggan akan menerima Laporan Hasil Uji dan/atau Sertifikat.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		// $pdf->Cell(4, 4, "", 1, 0);
		$pdf->Cell(4,4,"5.",0,0,'L');
		$pdf->Cell(10,4,"Pembayaran biaya uji lokasi sesuai SPB, dilakukan oleh pelanggan melalui rekening Bank atas nama TELKOM",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"paling lambat 3 (tiga) hari kerja sebelum pelaksanaan uji lokasi. Apabila pada tenggang waktu tersebut,",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"pelanggan tidak melakukan pembayaran, kontrak ini dinyatakan Tidak Berlaku.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		$pdf->Cell(4,4,"6.",0,0,'L');
		$pdf->Cell(10,4,"Pelanggan menyatakan bahwa perangkat yang didaftarkan dalam kontrak ini adalah sama dengan sampel uji.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		$pdf->Cell(4,4,"7.",0,0,'L');
		$pdf->Cell(10,4,"Pelanggan menyatakan bahwa lingkungan (laboratorium, teknisi, sampel uji, dan alat ukur) uji lokasi sudah siap.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		$pdf->Cell(4,4,"8.",0,0,'L');
		$pdf->Cell(10,4,"Kekeliruan pada penamaan perangkat dan acuan uji yang digunakan pada Laporan Hasil uji bukan tanggung jawab",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"TELKOM.*",0,0,'L');
	}else{		
		$pdf->Cell(4,4,"1.",0,0,'L');
		$pdf->Cell(10,4,"Biaya pengujian sesuai SPB yang telah diterbitkan oleh TELKOM.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		// $pdf->Cell(4, 4, "", 1, 0);
		$pdf->Cell(4,4,"2.",0,0,'L');
		$pdf->Cell(10,4,"Pelanggan memahami dan menentukan Referensi Uji yang akan digunakan, memahami item uji, dan konfigurasi uji.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		// $pdf->Cell(4, 4, "", 1, 0);
		$pdf->Cell(4,4,"3.",0,0,'L');
		$pdf->Cell(10,4,"Pelanggan akan menerima Laporan Hasil Uji dan/atau Sertifikat.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		// $pdf->Cell(4, 4, "", 1, 0);
		$pdf->Cell(4,4,"4.",0,0,'L');
		$pdf->Cell(10,4,"Pelanggan harus mengambil kembali sampel uji, paling lama 30 (tiga puluh) hari kalender setelah proses pengujian",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"selesai.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		// $pdf->Cell(4, 4, "", 1, 0);
		$pdf->Cell(4,4,"5.",0,0,'L');
		$pdf->Cell(10,4,"Laporan Pengujian dan/atau Sertifikat Quality Assurance Test diberikan apabila Sampel Uji sudah diambil oleh",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"pelanggan. Setelah menerima Laporan dan/atau Sertifikat Quality Assurance Test, pelanggan telah memahami",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"hasil uji.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		// $pdf->Cell(4, 4, "", 1, 0);
		$pdf->Cell(4,4,"6.",0,0,'L');
		$pdf->Cell(10,4,"Pembayaran biaya uji sesuai SPB, dilakukan oleh pelanggan melalui rekening Bank atas nama TELKOM paling",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"lambat 14 (empat belas) hari kerja setelah penerbitan SPB. Apabila pada tenggang waktu tersebut, pelanggan",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"tidak melakukan pembayaran, kontrak ini dinyatakan Tidak Berlaku.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		$pdf->Cell(4,4,"7.",0,0,'L');
		$pdf->Cell(10,4,"Pelanggan menyatakan bahwa perangkat yang didaftarkan dalam kontrak ini adalah sama dengan sampel uji.",0,0,'L');
		$y = $pdf->getY();
		$pdf->Ln(5);
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(12.00125,$y + 6);
		$pdf->Cell(4,4,"8.",0,0,'L');
		$pdf->Cell(10,4,"Kekeliruan pada Penamaan perangkat dan acuan uji yang digunakan pada Laporan Hasil uji bukan tanggung jawab",0,0,'L');
		$pdf->Ln(4);
		$pdf->Cell(1,4,"",0,0,'L');
		$pdf->Cell(10,4,"TELKOM.",0,0,'L');
	}
	$y = $pdf->getY();
	$pdf->Ln(5);
	$pdf->SetFont('helvetica','',10);
	$pdf->setXY(12.00125,$y + 6);
	$pdf->Cell(50,4,"*)  Untuk Pengujian TA",0,0,'L');
	$pdf->Cell(4,4,"**)  Coret salah satu",0,0,'L');
	
	$pdf->Ln(6);
	$pdf->setX(10.00125);
/*End Data Pemohon*/

/*Footer Manual*/
	$pdf->SetFont('helvetica','',10);
	$pdf->Cell(190,5,"Bandung, ".$data[0]['contract_date'],0,0,'R');
	$pdf->Ln(5);
	$pdf->setX(10.00125);
	$pdf->Cell(63, 4, 'Manager User Relation', 1, 0, 'C');
	$pdf->Cell(63, 4, 'Manager Laboratorium', 1, 0, 'C');
	$pdf->Cell(63, 4, 'Pelanggan', 1, 1, 'C');
	$pdf->setX(10.00125);
	if($data[0]['is_poh'] == '1'){
		$pdf->drawTextBox('POH ('.$data[0]['manager_urel'].')', 63, 23, 'C', 'B', 1);
	}else{
		$pdf->drawTextBox('('.$data[0]['manager_urel'].')', 63, 23, 'C', 'B', 1);
	}
	$pdf->setXY(73.00125,$pdf->getY()-23);
	$pdf->drawTextBox('('.$data[0]['manager_lab'].')', 63, 23, 'C', 'B', 1);
	$pdf->setXY(136.00125,$pdf->getY()-23);
	$pdf->drawTextBox('('.$data[0]['pic'].')', 63, 23, 'C', 'B', 1);
	/*$pdf->Ln(2);
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
	$pdf->SetFont('helvetica','',7);*/
	$pdf->Ln(5);
	$pdf->setX(10.00125);
	$pdf->SetFont('','U');
	$pdf->Cell(10,5,"User Relation, Divisi Digital Business, Telp. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	$pdf->Ln(4);
	$pdf->setX(10.00125);
	$pdf->SetFont('','I');
	$pdf->Cell(10,5,"Divisi Digital Business, User Relation, Phone. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
	$pdf->Ln();
	if($data[0]['is_loc_test'] == 1){
		$pdf->Cell(185,1,"TLKM02/F/007 Versi 01",0,0,'R');
	}else{
		$pdf->Cell(185,1,"TLKM02/F/006 Versi 02",0,0,'R');
	}
/*End Footer Manual*/
	$pdf->Output();
	exit;
});

Route::get('cetakSPB', function(Illuminate\Http\Request $request){
	// Instanciation of inherited class
		$data = $request->session()->get('key_exam_for_spb');
		// echo"<pre>";print_r($data);exit;
	$pdf = new PDF_MC_Tables();
		$is_poh = $data[0]['is_poh'];
		$manager_urel = $data[0]['manager_urel'];
		$spb_number = $data[0]['spb_number'];
		$company_name = $data[0]['exam']['company']['name'];
		$no_reg = $data[0]['exam']['function_test_NO'];
		$test_reference = $data[0]['exam']['device']['test_reference'];
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
		$ppn = floor(0.1*$biaya);
		$total_biaya = $biaya + $ppn;
		$terbilang = $pdf->terbilang($total_biaya, 3);
		$spb_date = date('j', strtotime($data[0]['spb_date']))." ".strftime('%B %Y', strtotime($data[0]['spb_date']));
		$payment_method = $data[0]['payment_method']->data->VA;
	// $pdf->judul_kop('FORM TINJAUAN KONTRAK','Contract Review Form');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->Ln(20);
	$pdf->SetFont('helvetica','B',9);
	// $pdf->SetFont('','BU');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(60);
	$pdf->Cell(70,5,'DIVISI DIGITAL SERVICE - PT TELKOM',0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('helvetica','B',9);
	$pdf->Cell(60);
	$pdf->SetFont('','BU');
	$pdf->Cell(70,5,'SURAT PEMBERITAHUAN BIAYA (SPB)',0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('helvetica','B',9);
	$pdf->Cell(60);
	// $pdf->SetFont('','I');
	$pdf->Cell(70,5,'No. '.$spb_number,0,0,'C');
	
	$pdf->Ln(10);
	$pdf->SetFont('helvetica','',9);
	$pdf->SetWidths(array(48,30,5,82));
	$pdf->SetAligns(array('C','L','L','L'));
	$pdf->Row(array('','Nama Perusahaan',':',''));
		$pdf->SetFont('helvetica','B',9);
		$pdf->SetXY(93.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,$company_name,0,0,'L');
		$pdf->Ln();
	$pdf->SetFont('helvetica','',9);
	$pdf->Row(array('','','','Up. '.$user_name));	
	$pdf->Row(array('','Alamat',':',$company_address));	
	$pdf->Row(array('','Telepon / Fax',':',$company_contact));	
	
	$pdf->Ln(4);
	$pdf->SetFont('helvetica','',9);
	$pdf->SetWidths(array(13,7,160));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','I. ','Merujuk Kontrak Pengujian Saudara tanggal '.$contract_date.' perihal permohonan uji mutu ('.$no_reg.'), dengan ini kami beritahukan bahwa biaya pengujian yang harus dibayar adalah :'));
	
	$pdf->Ln(1);
	$pdf->SetFont('helvetica','B',9);
	$pdf->SetWidths(array(17,8,125,27));
	$pdf->SetAligns(array('L','C','C','C'));
	$pdf->RowRect(array('','No','Nama','Biaya (Rp.)'));
	$pdf->SetFont('helvetica','',9);
	$pdf->SetAligns(array('L','L','L','R'));
	for($i=0;$i<count($data[0]['arr_nama_perangkat']);$i++){
		$item = $i == 0 ? $data[0]['arr_nama_perangkat'][$i].' ('.$test_reference.')' : $data[0]['arr_nama_perangkat'][$i];
		$no = $data[0]['arr_nama_perangkat'][$i] == 'Kode Unik' ? '' : ($i+1).'.';
		$pdf->RowRect(array('',$no,$item,number_format($data[0]['arr_biaya'][$i],0,",",".").",-"));
	}
	$pdf->RowRect(array('','','PPN 10 %',number_format($ppn,0,",",".").",-"));
	$pdf->SetFont('helvetica','B',9);
	$pdf->RowRect(array('','','Total Biaya Pengujian',number_format($total_biaya,0,",",".").",-"));	
	$pdf->SetWidths(array(17,160));
	$pdf->SetAligns(array('L','C'));
	$pdf->SetFont('','BI');
	$pdf->RowRect(array('','Terbilang : '.$terbilang.' Rupiah'));	
	
	$pdf->Ln(3);
	$pdf->SetFont('helvetica','',9);
	$pdf->SetWidths(array(13,7,160));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','II. ','Ketentuan dan tata cara pembayaran diatur sebagai berikut :'));	
	
	$pdf->Ln(1);
	$pdf->SetFont('helvetica','',9);
	$pdf->SetWidths(array(20,7,153));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','1. ','Pembayaran dilakukan melalui Virtual Account dengan pilihan bank sebagai berikut :'));
	for($i=0;$i<count($payment_method);$i++){
		$pdf->SetFont('ZapfDingbats','', 5);
		$pdf->SetX(40.00125);
		$pdf->Cell(5, 5, "l", 0, 0);
		$pdf->SetFont('helvetica','',9);
		$pdf->Cell(8,5,$payment_method[$i]->productName,0,0,'L');
		$pdf->Ln();
	}
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'Saudara wajib mengikuti petunjuk yang ada di website Telkom Test House atau e-mail petunjuk',0,0,'L');
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'pembayaran.',0,0,'L');
		$pdf->Ln();
	$pdf->Row(array('','2. ','Pembayaran dilakukan'));
		$pdf->SetFont('helvetica','B',9);
		$pdf->SetXY(70.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,'paling lambat 14 (empat belas) hari kalender setelah penerbitan SPB.',0,0,'L');
		$pdf->SetFont('helvetica','',9);
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'Apabila pada tenggang waktu tersebut Saudara tidak melakukan pembayaran, SPB ini tidak berlaku.',0,0,'L');
		$pdf->Ln();
	$pdf->Row(array('','3. ','Perangkat sampel uji harus sudah diambil'));
		$pdf->SetFont('helvetica','B',9);
		$pdf->SetXY(98.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,'paling lambat 14 (empat belas) hari kalender setelah',0,0,'L');
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'pemberitahuan selesai uji,',0,0,'L');
		$pdf->SetFont('helvetica','',9);
		$pdf->SetX(78.00125);
		$pdf->Cell(0,5,'apabila sampai batas waktu yang ditetapkan perangkat uji belum diambil',0,0,'L');
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'maka penyimpanan perangkat & segala akibatnya menjadi tanggung jawab Saudara.',0,0,'L');
	
/*Footer Manual*/
	
/*End Footer Manual*/

	$pdf->Ln(20);
	$pdf->Cell(9);
	$pdf->Cell(150,5,"Bandung, ".$spb_date,0,0,'L');
	$pdf->Ln(20);
	$pdf->SetFont('helvetica','B',9);
	$pdf->SetFont('','BU');
	$pdf->Cell(9);
	$pdf->Cell(185,5,$manager_urel,0,0,'L');
	$pdf->Ln(6);
	$pdf->SetFont('','B');
	$pdf->Cell(9);
	if($is_poh == '1'){
		$pdf->Cell(185,5,"POH. MANAGER USER RELATION",0,0,'L');
	}else{
		$pdf->Cell(185,5,"MANAGER USER RELATION",0,0,'L');
	}
	$pdf->Ln(10);
	$pdf->SetFont('','BI');
	$pdf->Cell(9);
	$pdf->Cell(185,5,"Tembusan: Sdr. OM Finance Service Center",0,0,'L');
	
	$pdf->Output();
	exit;
});

Route::post('/editPengujian', 'PengujianController@edit');
// Route::get('/pengujian/{id}/edit', 'PengujianController@edit');
Route::get('/pengujian/{id}/detail', 'PengujianController@detail');
Route::get('/pengujian/{id}/pembayaran', 'PengujianController@pembayaran');
Route::get('/pengujian/download/{id}/{attach}/{jns}', 'PengujianController@download');
Route::get('/pengujian/{id}/downloadSPB', 'PengujianController@downloadSPB');
Route::get('/pengujian/{id}/downloadLaporanPengujian', 'PengujianController@downloadLaporanPengujian');
Route::get('/pengujian/{id}/downloadSertifikat', 'PengujianController@downloadSertifikat');
Route::get('/products/{id}/stel', 'ProductsController@downloadStel');
Route::post('/pengujian/pembayaran', 'PengujianController@uploadPembayaran');
Route::post('/pengujian/tanggaluji', 'PengujianController@updateTanggalUji');
Route::get('/cetakPengujian/{id}', 'PengujianController@details');

Route::get('/cetakKuitansi/{id}', 'IncomeController@cetakKuitansi');
Route::get('/cetakUjiFungsi/{id}', 'ExaminationController@cetakUjiFungsi');
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
Route::get('/editprocess/{jenis_uji}/{id}', 'HomeController@edit_process');
Route::get('/faq', 'HomeController@faq');

Route::group(['prefix' => '/admin', 'middlewareGroups' => 'web'], function () {
	Route::auth();
	Route::get('/logout', 'UserController@logout');
	Route::get('/', 'DashboardController@index');
	Route::get('/examination/download/{id}', 'ExaminationController@downloadForm');
	Route::get('/examination/media/download/{id}', 'ExaminationController@downloadRefUjiFile');
	Route::get('/examination/media/download/{id}/{name}', 'ExaminationController@downloadMedia');
	Route::get('/stel/media/{id}', 'STELController@viewMedia');
	Route::get('/company/media/{id}/{name}', 'CompanyController@viewMedia');
	Route::resource('/device', 'DeviceController');
	Route::get('/devicenc', 'DevicencController@index');
	Route::get('/devicenc/{id}/{reason}/moveData', 'DevicencController@moveData');
	Route::get('/examination/revisi/{id}', 'ExaminationController@revisi');
	Route::get('/examination/harddelete/{id}/{page}/{reason}', 'ExaminationController@destroy');
	Route::get('/examination/resetUjiFungsi/{id}/{reason}', 'ExaminationController@resetUjiFungsi');
	Route::post('/examination/revisi', 'ExaminationController@updaterevisi');
	Route::post('/examination/{id}/tanggalkontrak', 'ExaminationController@tanggalkontrak');
	Route::post('/examination/{id}/generateSPBParam', 'ExaminationController@generateSPBParam');
	Route::post('/examination/{id}/generateEquipParam', 'ExaminationController@generateEquipParam');
	Route::post('/examination/{id}/tandaterima', 'ExaminationController@tandaterima');
	Route::get('/examination/generateEquip', 'ExaminationController@generateEquip');
	Route::get('/examination/generateSPB', 'ExaminationController@generateSPB');
	Route::post('/examination/{id}/generateFromTPN', 'ExaminationController@generateFromTPN');
	Route::post('/examination/{id}/generateTaxInvoiceSPB', 'ExaminationController@generateTaxInvoice');
	Route::get('/examination/{id}/deleteRevLapUji', 'ExaminationController@deleteRevLapUji');
	Route::post('/examination/generateSPB', 'ExaminationController@generateSPBData');
	Route::put('/user/profile/{id}', 'UserController@updateProfile');
	Route::resource('/article', 'ArticleController');
	Route::resource('/examination', 'ExaminationController');
	Route::resource('/stel', 'STELController');
	Route::resource('/charge', 'ExaminationChargeController');
	Route::resource('/newcharge', 'NewExaminationChargeController');
	Route::get('/newcharge/{id}/createDetail', 'NewExaminationChargeController@createDetail');
	Route::post('/newcharge/{id}/postDetail', 'NewExaminationChargeController@postDetail');
	Route::get('/newcharge/{id}/editDetail/{exam_id}', 'NewExaminationChargeController@editDetail');
	Route::post('/newcharge/{id}/updateDetail/{exam_id}', 'NewExaminationChargeController@updateDetail');
	Route::post('/newcharge/{id}/deleteDetail/{exam_id}', 'NewExaminationChargeController@deleteDetail');
	Route::resource('/calibration', 'CalibrationChargeController');
	Route::resource('/company', 'CompanyController');
	Route::resource('/user', 'UserController');
	Route::resource('/userin', 'UserinController');
	Route::resource('/usereks', 'UsereksController');
	Route::resource('/slideshow', 'SlideshowController');
	Route::resource('/certification', 'CertificationController');
	Route::resource('/popupinformation', 'PopUpInformationController');
	Route::resource('/footer', 'FooterController');
	Route::resource('/labs', 'ExaminationLabController');
	Route::resource('/myexam', 'MyExaminationController');
	Route::get('/feedback/{id}/reply', 'FeedbackController@reply');
	Route::post('/feedback/{id}/destroy', 'FeedbackController@destroy');
	Route::post('/feedback/reply', 'FeedbackController@sendEmailReplyFeedback');
	Route::get('/feedback', 'FeedbackController@index');
	Route::get('/downloadUsman', 'DashboardController@downloadUsman');
	Route::post('/user/{id}/softDelete', 'UserController@softDelete');
	Route::post('/userin/{id}/softDelete', 'UserinController@softDelete');
	Route::post('/usereks/{id}/softDelete', 'UsereksController@softDelete');
	Route::get('/analytic', 'AnalyticController@index');
	Route::resource('/role', 'RoleController');
	Route::get('/downloadbukti/{id}', 'SalesController@viewMedia');
	Route::get('/downloadstelwatermark/{id}', 'SalesController@viewWatermark');
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
	Route::resource('/log_administrator', 'LogController');
	Route::get('/backup', 'BackupController@index');
	Route::get('/backup/{id}/delete', 'BackupController@destroy');
	Route::get('/backup/{id}/media', 'BackupController@viewmedia');
	Route::get('/backup/{id}/restore', 'BackupController@restore');
	
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
	Route::get('/sales/{id}/upload', 'SalesController@upload');
	Route::get('/sales/{id}/{reason}/deleteProduct', 'SalesController@deleteProduct');
	Route::post('/sales/{id}/generateKuitansi', 'SalesController@generateKuitansi');
	Route::post('/sales/{id}/generateTaxInvoice', 'SalesController@generateTaxInvoice');
	Route::resource('/question', 'QuestionController');
	Route::resource('/questionerquestion', 'QuestionerQuestionController');
	Route::resource('/questionpriv', 'QuestionprivController');
	Route::get('/kuitansi', 'IncomeController@kuitansi');
	Route::get('/kuitansi/create', 'IncomeController@create');
	Route::post('/kuitansi/generateKuitansi', 'IncomeController@generateKuitansiManual');
	Route::post('/kuitansi', 'IncomeController@store');
	Route::get('/kuitansi/{id}/detail', 'IncomeController@detail');
	Route::get('/downloadkuitansistel/{id}', 'SalesController@downloadkuitansistel');
	Route::get('/downloadfakturstel/{id}', 'SalesController@downloadfakturstel');

	Route::resource('/spk', 'SPKController');
	Route::resource('/faq', 'FaqController');
	
	Route::get('/all_notifications', 'NotificationController@indexAdmin');

	Route::resource('/functiontest', 'FunctionTestController');
	Route::resource('/generalSetting', 'GeneralSettingController');
	Route::resource('/spb', 'SPBController');
	Route::resource('/nogudang', 'NoGudangController');
	Route::resource('/feedbackncomplaint', 'FeedbackComplaintController');
	Route::resource('/fakturpajak', 'FakturPajakController');
	Route::resource('/videoTutorial', 'VideoTutorialController');
	Route::post('/orderSlideshow', 'SlideshowController@orderSlideshow');

});
	Route::get('/adm_dashboard_autocomplete/{query}', 'DashboardController@autocomplete')->name('adm_dashboard_autocomplete');
	
	Route::get('/examination/excel', 'ExaminationController@excel');
	Route::get('/device/excel', 'DeviceController@excel');
	Route::get('/devicenc/excel', 'DevicencController@excel');
	Route::get('/company/excel', 'CompanyController@excel');
	Route::post('/company/importExcel', 'CompanyController@importExcel');
	Route::get('/income/excel', 'IncomeController@excel');
	Route::get('/log/excel', 'LogController@excel');
	Route::get('/log_administrator/excel', 'LogController@excel');
	Route::get('/examinationdone/excel', 'ExaminationDoneController@excel');
	Route::get('/sales/excel', 'SalesController@excel');

	Route::get('/spb/excel', 'SPBController@excel');
	Route::get('/nogudang/excel', 'NoGudangController@excel');
	Route::get('/stel/excel', 'STELController@excel');
	Route::get('/functiontest/excel', 'FunctionTestController@excel');
	Route::get('/charge/excel', 'ExaminationChargeController@excel');
	Route::get('/calibration/excel', 'CalibrationChargeController@excel');
	Route::get('/spk/excel', 'SPKController@excel');
	Route::get('/feedbackncomplaint/excel', 'FeedbackComplaintController@excel');

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
Route::get('/STELclient', 'STELClientController@index');
Route::get('/STSELclient', 'STELClientController@index');
Route::resource('/Chargeclient', 'ExaminationChargeClientController');
Route::get('/Chargeclient', 'ExaminationChargeClientController@index');
Route::resource('/NewChargeclient', 'ExaminationNewChargeClientController');
Route::get('/NewChargeclient', 'ExaminationNewChargeClientController@index');
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
Route::post('/filterNewCharge', 'ExaminationNewChargeClientController@filter');
Route::get('/register', 'ProfileController@register');
Route::post('/client/register', 'ProfileController@insert');
Route::post('/checkRegisterEmail', 'ProfileController@checkRegisterEmail');

Route::post('/global/search', 'HomeController@search');
Route::post('/client/feedback', 'PermohonanController@feedback');

Route::get('/client/downloadUsman', 'HomeController@downloadUsman');
Route::get('mylogsbl', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

 
Route::get('/stel_autocomplete/{query}/{type}', 'STELClientController@autocomplete')->name('stel_autocomplete');
Route::get('/stsel_autocomplete/{query}/{type}', 'STELClientController@autocomplete')->name('stsel_autocomplete');
Route::get('/charge_client_autocomplete/{query}', 'ExaminationChargeClientController@autocomplete')->name('charge_client_autocomplete');
Route::get('/new_charge_client_autocomplete/{query}', 'ExaminationNewChargeClientController@autocomplete')->name('new_charge_client_autocomplete');
Route::get('/dev_client_autocomplete/{query}', 'DevClientController@autocomplete')->name('dev_client_autocomplete');
Route::get('/pengujian_autocomplete/{query}', 'PengujianController@autocomplete')->name('pengujian_autocomplete');
 

Route::group(['prefix' => '/v1', 'middlewareGroups' => 'api'], function () {
	Route::get('/companies', 'v1\CompanyAPIController@getCompanies');
	Route::get('/customer', 'v1\CustomerAPIController@getCustomer');
	Route::get('/stel', 'v1\StelAPIController@getStelData');
	Route::get('/checkBillingTPN', 'v1\StelAPIController@checkBillingTPN');
	Route::get('/checkTaxInvoiceTPN', 'v1\StelAPIController@checkTaxInvoiceTPN');
	Route::get('/checkKuitansiTPN', 'v1\StelAPIController@checkKuitansiTPN');
	Route::get('/checkReturnedTPN', 'v1\StelAPIController@checkReturnedTPN');
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
	Route::post('/updateSpk', 'v1\ExaminationAPIController@updateSpk');
	Route::post('/sendLapUji', 'v1\ExaminationAPIController@sendLapUji');
	Route::post('/updateSidangQa', 'v1\ExaminationAPIController@updateSidangQa');
	Route::post('/sendSertifikat', 'v1\ExaminationAPIController@sendSertifikat');
	Route::post('/sendSPK', 'v1\ExaminationAPIController@sendSPK');
	Route::post('/sendSPKHistory', 'v1\ExaminationAPIController@sendSPKHistory');
	Route::get('/checkSPKCreatedOTR', 'v1\ExaminationAPIController@checkSPKCreatedOTR');
	Route::get('/checkBillingSPBTPN', 'v1\ExaminationAPIController@checkBillingTPN');
	Route::get('/checkTaxInvoiceSPBTPN', 'v1\ExaminationAPIController@checkTaxInvoiceTPN');
	Route::get('/checkKuitansiSPBTPN', 'v1\ExaminationAPIController@checkKuitansiTPN');
	Route::get('/checkReturnedSPBTPN', 'v1\ExaminationAPIController@checkReturnedTPN');
});

Route::get('/do_backup', 'BackupController@backup'); 

Route::get('/login', 'ProfileController@login');
 
Route::get('/products', 'ProductsController@index'); 
Route::resource('/products', 'ProductsController');

Route::get('/purchase_history', 'ProductsController@purchase_history');
Route::get('/payment_confirmation/{id}', 'ProductsController@payment_confirmation');
Route::get('/payment_confirmation_spb/{id}', 'PengujianController@payment_confirmation');
Route::get('/resend_va/{id}', 'ProductsController@api_resend_va');
Route::get('/resend_va_spb/{id}', 'PengujianController@api_resend_va');
Route::get('/cancel_va/{id}', 'ProductsController@api_cancel_va');
Route::get('/cancel_va_spb/{id}', 'PengujianController@api_cancel_va');
Route::post('/doCancel', 'ProductsController@doCancel');
Route::post('/doCancelSPB', 'PengujianController@doCancel');
Route::get('/payment_status', 'ProductsController@payment_status');
Route::get('/checkout', 'ProductsController@checkout');
Route::post('/doCheckout', 'ProductsController@doCheckout');
Route::post('/doCheckoutSPB', 'PengujianController@doCheckout');
Route::get('/payment_detail/{id}', 'ProductsController@payment_detail');
Route::get('/test_notification', 'ProductsController@test_notification');
Route::get('/upload_payment/{id}', 'ProductsController@upload_payment');
Route::post('/pembayaranstel', 'ProductsController@pembayaranstel');

Route::post('/checkKuisioner', 'PengujianController@checkKuisioner');
Route::post('/insertKuisioner', 'PengujianController@insertKuisioner');
Route::post('/insertComplaint', 'PengujianController@insertComplaint');

Route::get('/client/downloadkuitansistel/{id}', 'ProductsController@downloadkuitansistel');
Route::get('/client/downloadfakturstel/{id}', 'ProductsController@downloadfakturstel');
Route::get('/client/downloadstelwatermark/{id}', 'ProductsController@viewWatermark');

Route::get('/cetakFormBarang/{id}', 'ExaminationController@cetakFormBarang');
Route::get('cetakTandaTerima', function(Illuminate\Http\Request $request){
	$PDF = new \App\Services\PDF\PDFService();
	return $PDF->cetakTandaTerima($request->session()->get('key_tanda_terima'));
});
Route::get('/cetakKepuasanKonsumen/{id}', 'ExaminationDoneController@cetakKepuasanKonsumen');
Route::get('/cetakComplaint/{id}', 'ExaminationDoneController@cetakComplaint');
Route::post('/updateNotif', 'NotificationController@updateNotif');
Route::get('/all_notifications', 'NotificationController@index');