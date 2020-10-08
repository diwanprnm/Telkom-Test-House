<?php

namespace App\Services\PDF;

//PLUGIN
use Anouar\Fpdf\Fpdf as FPDF;

//PDFMC
use App\Services\PDF\PDFMCTablesKonsumen;
use App\Services\PDF\PDFMCTables;
use App\Services\PDF\PDFMCTandaTerima;

//CETAK PDF
use App\Services\PDF\CetakComplaint;
use App\Services\PDF\CetakKepuasanKonsumen;
use App\Services\PDF\CetakBuktiPenerimaanPerangkat;
use App\Services\PDF\CetakUjiFungsi;
use App\Services\PDF\CetakTandaTerima;


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


	public function cetakUjiFungsi($data)
	{
		$cetakUjiFungsi = new CetakUjiFungsi();
		return $cetakUjiFungsi->makePDF($data, new PDFMCTables());
	}


	public function cetakTandaTerima($data)
	{
		$cetakTandaTerima = new CetakTandaTerima();
		return $cetakTandaTerima->makePDF($data, new PDFMCTandaTerima);
	}
}
