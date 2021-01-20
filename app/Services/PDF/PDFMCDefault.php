<?php

namespace App\Services\PDF;
use Anouar\Fpdf\Fpdf as FPDF;

class PDFMCDefault extends FPDF{

	public $title = 'Title';
	private $subTitle = 'Sub-Title';
	private $kodeForm = 'Kode Form';

	public function setData($data)
	{
		$this->title = $data['title'];
		$this->subTitle = $data['subTitle'];
		$this->kodeForm = $data['kodeForm'];
	}

	function Header()
	{
		$this->SetFont('helvetica','BU',12);
		$this->Image(public_path().'/assets/images/Telkom-Indonesia-Corporate-Logo1.jpg',10,3,27);
		$this->Cell(190,5,$this->title,0,0,'R');
		$this->Ln();

		$this->SetFont('helvetica','I',10);
		$this->Cell(190,5,$this->subTitle,0,0,'R');
		$this->Ln();

		$this->Line(10,22.5,200,22.5);
		$this->Line(10,23,200,23);
		$this->Ln(5);
	}

	function Footer()
	{
		$this->SetY(-12);
		$this->SetFont('helvetica','',10);
		$this->Cell(0,0.1,$this->kodeForm,0,0,'L');
		$this->Cell(0,0.1,'Hal '.$this->PageNo().' dari {nb}',0,0,'R');
	}

}