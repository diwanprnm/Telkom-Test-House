<?php

namespace App\Services\PDF;

use Anouar\Fpdf\Fpdf as FPDF;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Session;

use App\Services\PDF\PDFMCTablesKonsumen;

use App\Services\PDF\CetakComplaints;


class PDFService
{
	public function cetakComplaint($data)
	{
		$cetakComplaint = new CetakComplaint();
		return $cetakComplaint->buatPDF($data, new PDFMCTablesKonsumen());
	}
}
