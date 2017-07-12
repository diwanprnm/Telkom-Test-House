<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class PaketController extends ControllerBase
{
    public function indexAction()
    {
		$sql="call sp_surat_dpb_load_all();";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		$this->view->data=$a;
	}
	

	
    public function loadDataDPBAction()
    {
    	$sql="call sp_paket_load();";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		unset($data);
		$this->dispatcher->forward(array("action" => "loadDPB","params"=> array('dataquery' => $a)));
		
    }
    public function loadDataSuratAction()
	{
		if($this->request->getPost('surat')==''){$vis_nosurat = 0;}else{$vis_nosurat = 1;}
		if($this->request->getPost('tanggal')==''){$vis_tanggal = 0;}else{$vis_tanggal = 1;}
		if($this->request->getPost('periode')==''){$vis_periode = 0;$periode = 0;}else{$vis_periode = 1;$periode=$this->request->getPost('periode');}
		if($this->request->getPost('status')==''){$vis_status = 0;$status = 0;}else{$vis_status = 1;$status=$this->request->getPost('status');}
		$surat=$this->request->getPost('surat');
		$tanggal=Parse::dateUSA($this->request->getPost('tanggal'));
		$sql="call sp_surat_dpb_cari($vis_nosurat,$vis_tanggal,$vis_periode,'$surat','$tanggal',$periode,$vis_status,$status);";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		$this->dispatcher->forward(array("action" => "loadDPB","params"=> array('dataquery' => $a)));
	}

    
	public function loadDPBAction()
	{
		$dataquery = $this->dispatcher->getParam("dataquery");
    	$this->view->data=$dataquery;
	}
	
	public function loadBarangAction()
	{
		$dataquery = $this->dispatcher->getParam("dataquery");
    	$this->view->data=$dataquery;
	}
	public function loadBarangDataAction()
	{
		$sql="call sp_master_barang_load();";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		
		$a=$data->toArray();
		unset($data);
		$this->dispatcher->forward(array("action" => "loadBarang","params"=> array('dataquery' => $a)));
	}

	public function databarangAction()
	{
		$param=$this->request->getPost('param'); 
		$sql="call sp_master_barang_pencarian_all('%$param%')";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		$this->dispatcher->forward(array("action" => "loadBarang","params"=> array('dataquery' => $a)));
	}

	
	public function loadBarangTerpilihFuncAction()
	{
		$this->session->remove('nama_paket_load_barang_terpilih');
		$this->session->remove("data_paket_load_barang_terpilih");
		$nama_paket = $this->request->getPost("id");
		$this->session->set('nama_paket_load_barang_terpilih',$nama_paket);
		$jns_insert = $this->request->getPost("jns_insert");
		if($jns_insert==0){
			$id_paket = $this->session->get('id_paket');
		}else{
			$id_paket = $this->request->getPost("id_paket");
		}
		$this->session->set('id_paket',$id_paket);
		
		$sql="call sp_paket_load_barang($id_paket);";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		$this->session->set('data_paket_load_barang_terpilih',$a);
		$this->view->data=$a;
	}
	
	public function loadBarangTerpilihAction()
	{
		$this->view->no_surat=$this->session->get('nama_paket_load_barang_terpilih');
		$this->view->data=$this->session->get('data_paket_load_barang_terpilih');
		$this->view->id_paket=$this->session->get('id_paket');
	}
	
	public function loadBarangPostingAction()
	{
		$id = $this->request->getPost("id");
		$sql="call sp_surat_dpb_load_barang('$id');";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		$this->view->data=$a;
	}
	
	public function addAction(){
		
	}
	public function getAutoNumbAction()
	{
		$jenis=$this->request->getPost('jenis');
		$ym=$this->request->getPost('ym');
		$sql="call sp_get_auto_numb_surat($ym,$jenis);";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		$last_numb = null;
		$text_numb = null;
		foreach($a as $row){
			$last_numb = $row['numb'];
			$text_numb = $row['text_numb'];
		}
		if($last_numb==NULL){
			$last_numb = "1";
		}
		if($text_numb==NULL){
			unset($data);
			$sql="call sp_pengaturan_surat_get($jenis);";
			$datas=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
			$a=$datas->toArray();
			foreach($a as $row){
				$text_numb = $row['text_nomor'];
			}
		}
		echo "".$last_numb."/".$text_numb."/".Parse::numToRomawi(substr($ym,4,2))."/".substr($ym,0,4)."";
	}
	public function InsertAction()
	{
		$nama_paket=$this->request->getPost('nama_paket');
		$keterangan=$this->request->getPost('keterangan');
		$sql="call sp_paket_insert('$nama_paket','$keterangan');";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		foreach($a as $row){
			echo $row['stts'];
			$id_paket = $row['id'];
		}
		$this->session->set('id_paket',$id_paket);
	}
	
	public function UpdateAction()
	{
		$id=$this->request->getPost('id');
		$nama_paket=$this->request->getPost('nama_paket');
		$keterangan=$this->request->getPost('keterangan');
		$sql="call sp_paket_update($id,'$nama_paket','$keterangan');";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		foreach($a as $row){
			echo $row['stts'];
		}
	}
	public function EditAction()
	{
		$id=$this->request->getPost('id');
		$sql="call sp_paket_get($id);";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		foreach($a as $row){
			echo $row['id']."|"; #0
			echo $row['nama']."|"; #1
			echo $row['keterangan']."|"; #2
		}
	}
	public function HapusAction()
	{
		$id=$this->request->getPost('id');
		$sql="call sp_paket_hapus($id);";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		foreach($a as $row){
			echo $row['stts'];
		}
	}
	public function cekPOAction()
	{
		$kode_surat=$this->request->getPost('kode_surat');
		$sql="call sp_surat_dpb_cek_po('$kode_surat');";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		foreach($a as $row){
			echo $row['count_data'];
		}
	}
	public function CekBarangAction()
	{
		$id=$this->request->getPost('id');
		$id_paket=$this->request->getPost('id_paket');
		$sql="call sp_paket_cek_barang($id_paket,'$id');";
		
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		foreach($a as $row){
			echo $row['count_data'];
		}
	}
	public function InsertBarangAction()
	{
		$id_user=0;
		if ($this->session->has("auth")) {
			$auth = $this->session->get("auth");
			$id_user=$auth['id'];
		}
		
		$kode_barang=$this->request->getPost('kode_barang');
		$nama_barang=$this->request->getPost('nama_barang');
		$id_paket=$this->request->getPost('id_paket');
		$qty=$this->request->getPost('qty');
		
		$sql="call sp_paket_insert_barang('$kode_barang','$nama_barang',$qty,$id_paket);";
		echo $sql ;exit;
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		foreach($a as $row){
			echo $row['stts'];
		}
	}
	public function DeleteBarangAction()
	{
		$id=$this->request->getPost('id');
		$sql="call sp_paket_delete_barang($id);";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		foreach($a as $row){
			echo $row['stts'];
		}
	}
	
	public function postingAction()
	{
		$id = $this->request->getPost("id");
		$no_dbm = $this->request->getPost("no_dbm");
		$no_dpb = $this->request->getPost("no_dpb");
		$tgl = $this->request->getPost("tgl");
		$periode = $this->request->getPost("periode");
		$total_qty = $this->request->getPost("total_qty");
		$total_harga = $this->request->getPost("total_harga");
		$sql="call sp_surat_dpb_posting($id,'$no_dbm','$no_dpb','$tgl',$periode,$total_qty,$total_harga,1,1);";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		$this->view->data=$a;
	}
	public function printPdfLapDPBAction()
	{
		// $sql="call sp_pengaturan_surat_get(1)";
		// $data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		// $a=$data->toArray();
		// $this->view->data=$a;
		// $nama =$this->session->get('nama_per1')
		// echo $nama;
		$this->view->kode_surat=$this->session->get('kode_surat');
	}
	
	public function laporanCreateSessionAction()
	{
		$this->session->remove("nama_per1");
		$this->session->remove("nama_per2");
		$this->session->remove("judul1");
		$this->session->remove("judul2");
		$this->session->remove("text");
		$this->session->remove("ttd_namakiri");
		$this->session->remove("ttd_jabkiri");
		$this->session->remove("ttd_nipkiri");
		$this->session->remove("ttd_namatengah");
		$this->session->remove("ttd_jabtengah");
		$this->session->remove("ttd_niptengah");
		$this->session->remove("ttd_namakanan");
		$this->session->remove("ttd_jabkanan");
		$this->session->remove("ttd_nipkanan");
		$this->session->remove("kode_surat");
		
		$sql="call sp_pengaturan_surat_get(1)";
		$data=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sql));
		$a=$data->toArray();
		
		$kode_surat=$this->request->getPost('id');
		unset($data);
		$this->session->remove("data_pengajuan_pembelian_barang");
		$sqla="call sp_surat_dpb_load_barang('$kode_surat');";
		$datas=new Phalcon\Mvc\Model\Resultset\Simple(null,null,$this->db->query($sqla));
		$aa=$datas->toArray();
		$this->session->set('data_pengajuan_pembelian_barang',$aa);
		$this->session->set('kode_surat',$kode_surat);
		
		foreach($a as $row){
		$nama_per1 = $row['nama_perusahaan1'];
		$nama_per2 = $row['nama_perusahaan2'];
		$judul1 = $row['judul1'];
		$judul2 = $row['judul2'];
		$text = $row['text_nomor'];
		$ttd_namakiri = $row['ttd_namakiri'];
		$ttd_jabkiri = $row['ttd_jabkiri'];
		$ttd_nipkiri = $row['ttd_nipkiri'];
		
		$ttd_namatengah = $row['ttd_namatengah'];
		$ttd_jabtengah = $row['ttd_jabtengah'];
		$ttd_niptengah = $row['ttd_niptengah'];
		
		$ttd_namakanan = $row['ttd_namakanan'];
		$ttd_jabkanan = $row['ttd_jabkanan'];
		$ttd_nipkanan = $row['ttd_nipkanan'];
		}
		
		$this->session->set('nama_per1',$nama_per1);
		$this->session->set('nama_per2',$nama_per2);
		$this->session->set('judul1',$judul1);
		$this->session->set('judul2',$judul2);
		$this->session->set('text',$text);
		$this->session->set('ttd_namakiri',$ttd_namakiri);
		$this->session->set('ttd_jabkiri',$ttd_jabkiri);
		$this->session->set('ttd_nipkiri',$ttd_nipkiri);
		$this->session->set('ttd_namatengah',$ttd_namatengah);
		$this->session->set('ttd_jabtengah',$ttd_jabtengah);
		$this->session->set('ttd_niptengah',$ttd_niptengah);
		$this->session->set('ttd_namakanan',$ttd_namakanan);
		$this->session->set('ttd_jabkanan',$ttd_jabkanan);
		$this->session->set('ttd_nipkanan',$ttd_nipkanan);
	}
	
}