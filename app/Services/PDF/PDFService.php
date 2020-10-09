<?php

namespace App\Services\PDF;

//PLUGIN
use Anouar\Fpdf\Fpdf as FPDF;

//PDFMC
use App\Services\PDF\PDFMCTablesKonsumen;
use App\Services\PDF\PDFMCTable;
use App\Services\PDF\PDFMCTables;
use App\Services\PDF\PDFMCTandaTerima;
use App\Services\PDF\PDFMCTableKuitansi;
use App\Services\PDF\PDFMCTablePermohonan;

//CETAK PDF
use App\Services\PDF\CetakComplaint;
use App\Services\PDF\CetakKepuasanKonsumen;
use App\Services\PDF\CetakBuktiPenerimaanPerangkat;
use App\Services\PDF\CetakUjiFungsi;
use App\Services\PDF\CetakTandaTerima;
use App\Services\PDF\CetakHasilKuitansi;
use App\Services\PDF\CetakPengujian;


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
		return $cetakBuktiPenerimaanPerangkat->makePDF($data, new PDFMCTable());
	}


	public function cetakUjiFungsi($data)
	{
		$cetakUjiFungsi = new CetakUjiFungsi();
		return $cetakUjiFungsi->makePDF($data, new PDFMCTable());
	}


	public function cetakTandaTerima($data)
	{
		$cetakTandaTerima = new CetakTandaTerima();
		return $cetakTandaTerima->makePDF($data, new PDFMCTandaTerima());
	}


	public function CetakHasilKuitansi($data)
	{
		$cetakHasilKuitansi = new CetakHasilKuitansi();
		return $cetakHasilKuitansi->makePDF($data, new PDFMCTableKuitansi('L','mm','A5'));
	}


	public function cetakPengujian($data)
	{
		$cetakPengujian = new CetakPengujian();
		return $cetakPengujian->makePDF($data, new PDFMCTablePermohonan());
	}
}
