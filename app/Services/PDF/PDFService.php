<?php

namespace App\Services\PDF;

//PDFMC
use App\Services\PDF\PDFMCTablesKonsumen;
use App\Services\PDF\PDFMCTable;
use App\Services\PDF\PDFMCTables;
use App\Services\PDF\PDFMCTandaTerima;
use App\Services\PDF\PDFMCTableKuitansi;
use App\Services\PDF\PDFMCTablePermohonan;
use App\Services\PDF\WatermarkSTEL;

//CETAK PDF
use App\Services\PDF\CetakComplaint;
use App\Services\PDF\CetakKepuasanKonsumen;
use App\Services\PDF\CetakBuktiPenerimaanPerangkat;
use App\Services\PDF\CetakUjiFungsi;
use App\Services\PDF\CetakTandaTerima;
use App\Services\PDF\CetakHasilKuitansi;
use App\Services\PDF\CetakPengujian;
use App\Services\PDF\CetakSPB;
use App\Services\PDF\CetakSTEL;


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


	public function cetakHasilKuitansi($data)
	{
		$cetakHasilKuitansi = new CetakHasilKuitansi();
		return $cetakHasilKuitansi->makePDF($data, new PDFMCTableKuitansi('L','mm','A5'));
	}


	public function cetakPengujian($data)
	{
		$cetakPengujian = new CetakPengujian();
		return $cetakPengujian->makePDF($data, new PDFMCTablePermohonan());
	}


	public function cetakSPB($data)
	{
		$cetakSPB = new CetakSPB();
		$cetakSPB->makePDF($data, new PDFMCTables());
	}


	public function cetakKontrak($data)
	{
		$cetakKontrak = new CetakKontrak();
		$cetakKontrak->makePDF($data, new PDFMCTable());
	}


	public function cetakSTEL($data)
	{
		$cetakSTEL = new CetakSTEL();
		$cetakSTEL->makePDF($data, new WatermarkSTEL());
	}


	public function cetakPermohonan($data)
	{
		$cetakPermohonan = new CetakPermohonan();
		$cetakPermohonan->makePDF($data, new PDFMCTablePermohonan());
	}
}
