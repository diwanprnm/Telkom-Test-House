<?php

namespace App\Services\PDF;

use Illuminate\Support\Facades\App;

//PDFMC
use App\Services\PDF\PDFMCTablesKonsumen;
use App\Services\PDF\PDFMCTable;
use App\Services\PDF\PDFMCTables;
use App\Services\PDF\PDFMCTandaTerima;
use App\Services\PDF\PDFMCTableKuitansi;
use App\Services\PDF\PDFMCTablePermohonan;
use App\Services\PDF\PDFMCDefault;
use App\Services\PDF\WatermarkSTEL;

//CETAK PDF
use App\Services\PDF\CetakComplaint;
use App\Services\PDF\CetakKepuasanKonsumen;
use App\Services\PDF\CetakBuktiPenerimaanPerangkat;
use App\Services\PDF\CetakUjiFungsi;
use App\Services\PDF\CetakTandaTerima;
use App\Services\PDF\CetakHasilKuitansi;
use App\Services\PDF\CetakSPB;
use App\Services\PDF\CetakSTEL;


class PDFService
{

	public function cetakComplaint($data)
	{
		$cetakComplaint = new CetakComplaint();
		if( App::environment() == 'testing'){ return ''; }
		return $cetakComplaint->makePDF($data, new PDFMCTablesKonsumen());
	}


	public function cetakKepuasanKonsumen($data)
	{
		$cetakKepuasanKonsumen = new CetakKepuasanKonsumen();
		if( App::environment() == 'testing'){ return ''; }
		return $cetakKepuasanKonsumen->makePDF($data, new PDFMCTablesKonsumen());
	}


	public function cetakBuktiPenerimaanPerangkat($data)
	{
		$cetakBuktiPenerimaanPerangkat = new CetakBuktiPenerimaanPerangkat();
		if( App::environment() == 'testing'){ return ''; }
		return $cetakBuktiPenerimaanPerangkat->makePDF($data, new PDFMCCetakBuktiPenerimaanPerangkat());
	}


	public function cetakUjiFungsi($data)
	{
		$cetakUjiFungsi = new CetakUjiFungsi();
		if( App::environment() == 'testing'){ return ''; }
		return $cetakUjiFungsi->makePDF($data, new PDFMCCetakUjiFungsi());
	}


	public function cetakTandaTerima($data)
	{
		//Dipanggil dari routes
		$cetakTandaTerima = new CetakTandaTerima();
		return $cetakTandaTerima->makePDF($data, new PDFMCTandaTerima());
	}


	public function cetakHasilKuitansi($data)
	{
		$cetakHasilKuitansi = new CetakHasilKuitansi();
		if( App::environment() == 'testing'){ return ''; }
		return $cetakHasilKuitansi->makePDF($data, new PDFMCTableKuitansi('L','mm','A5'));
	}


	public function cetakPengujian($data)
	{
		$cetakPermohonan = new CetakPermohonan();
		if( App::environment() == 'testing'){ return ''; }
		return $cetakPermohonan->makePDF($this->convertDataPermohonan($data), new PDFMCTablePermohonan());
	}


	public function cetakSPB($data)
	{
		//dipanggil dari routes
		$cetakSPB = new CetakSPB();
		$cetakSPB->makePDF($data, new PDFMCTables());
	}


	public function cetakKontrak($data)
	{
		//dipanggil dari routes
		$cetakKontrak = new CetakKontrak();
		$cetakKontrak->makePDF($data, new PDFMCCetakKontrak());
	}


	public function cetakSTEL($data)
	{
		//dipanggil dari routes
		$cetakSTEL = new CetakSTEL();
		$cetakSTEL->makePDF($data, new WatermarkSTEL());
	}


	public function cetakPermohonan($data)
	{
		//dipanggil dari routes
		$cetakPermohonan = new CetakPermohonan();
		$cetakPermohonan->makePDF($data, new PDFMCTablePermohonan());
	}

	public function cetakTechnicalMeetingUjiLokasi($data)
	{
		$cetakTechnicalMeetingUjiLokasi = new CetakTechnicalMeetingUjiLokasi();
		$cetakTechnicalMeetingUjiLokasi->makePDF($data, new PDFMCDefault());
	}

	public function cetakTiketChamber($data)
	{
		$cetakTiketChamber = new CetakTiketChamber();
		return $cetakTiketChamber->makePDF($data, new PDFMCCetakTiketChamber('L','mm',['80', '210']));
	}

	private function convertDataPermohonan($data){
		return array([
			'nama_pemohon' => $data['namaPemohon'],
			'initPengujian' => $data['initPengujian'],
			'kotaPerusahaan' => $data['kotaPerusahaan'],
			'no_reg' => $data['no_reg'],
			'alamat_pemohon' => $data['alamatPemohon'],
			'telepon_pemohon' => $data['telpPemohon'],
			'email_pemohon' => $data['emailPemohon'],
			'jns_perusahaan' => $data['jnsPerusahaan'],
			'nama_perusahaan' => $data['namaPerusahaan'],
			'alamat_perusahaan' => $data['alamatPerusahaan'],
			'jnsPengujian' => $data['jnsPengujian'],
			'telepon_perusahaan' => $data['telpPerusahaan'],
			'email_perusahaan' => $data['emailPerusahaan'],
			'npwp_perusahaan' => $data['npwpPerusahaan'],
			'nama_perangkat' => $data['nama_perangkat'],
			'merek_perangkat' => $data['merk_perangkat'],
			'model_perangkat' => $data['model_perangkat'],
			'kapasitas_perangkat' => $data['kapasitas_perangkat'],
			'referensi_perangkat' => $data['referensi_perangkat'],
			'pembuat_perangkat' => $data['pembuat_perangkat'],
			'plg_id_perusahaan' => $data['plg_idPerusahaan'],
			'nib_perusahaan' => $data['nibPerusahaan'],
			'serial_number' => $data['serialNumber'],
			'date' => $data['date']
		]);
	}
}
