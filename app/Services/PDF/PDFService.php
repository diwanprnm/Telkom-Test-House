<?php

namespace App\Services\PDF;

//PLUGIN
use Anouar\Fpdf\Fpdf as FPDF;


//PDFMC
use App\Services\PDF\PDFMCTablesKonsumen;
use App\Services\PDF\PDFMCTables;

//CETAK PDF
use App\Services\PDF\CetakComplaint;
use App\Services\PDF\CetakKepuasanKonsumen;
use App\Services\PDF\CetakBuktiPenerimaanPerangkat;


class PDFService
{
	public function cetakComplaint($data)
	{
		$cetakComplaint = new CetakComplaint();
		return $cetakComplaint->makePDF($data, new PDFMCTablesKonsumen());
	}

	public function cetakKepuasanKonsumen($data)
	{
		$cetakKepuasanKonsumen = new CetakKepuasanKonsumen();
		return $cetakKepuasanKonsumen->makePDF($data, new PDFMCTablesKonsumen());
	}

	public function cetakBuktiPenerimaanPerangkat($data)
	{
		$cetakBuktiPenerimaanPerangkat = new CetakBuktiPenerimaanPerangkat();
		return $cetakBuktiPenerimaanPerangkat->makePDF($data, new PDFMCTables());
	}
}
