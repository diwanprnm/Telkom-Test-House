<?php

namespace App\Services\PDF;

use Anouar\Fpdf\Fpdf as FPDF;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Session;

use App\Services\PDF\PDFMCTablesKonsumen;

use App\Services\PDF\CetakComplaints;
use App\Services\PDF\CetakKepuasanKonsumen;


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
}
