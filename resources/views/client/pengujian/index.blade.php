<?php
	$currentUser = Auth::user();
?>
@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.examination') }} - Telkom DDS</title>
    <!-- Bootstrap Css -->
	<!-- <link href="{{ asset('template-assets/bootstrap-assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/> -->

    <!-- Style -->
	<!--
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
		<link href="{{ asset('template-assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{ asset('template-assets/css/form-elements.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{ asset('template-assets/css/popup.css')}}" rel="stylesheet" type="text/css"/>

		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">  
	-->
	<input type="hidden" name="link" id="link">	
   <div id="modal_kuisioner" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Survey Kepuasan Kastamer Eksternal</h4>
          </div>
          <div class="modal-body pre-scrollable">
            <form id="form-kuisioner1">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Tanggal</label>
							<!--<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">-->
								<input type="text" id="tanggal" name="tanggal" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo date('d-m-Y');?>" readonly required>
								<!--<span class="input-group-btn">
									<button type="button" class="btn btn-default">
										<i class="glyphicon glyphicon-calendar"></i>
									</button>
								</span>-->
							<!--</p>-->
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" id="user_name" name="user_name" placeholder="-" class="form-control" value="{{ $currentUser->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <input type="text" id="company_name" name="company_name" placeholder="-" class="form-control" value="{{ $currentUser->company->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" id="company_phone" name="company_phone" placeholder="-" value="{{ $currentUser->company->phone }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Jenis Pengujian</label>
                            <input type="text" id="exam_type" name="exam_type" placeholder="-" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" id="user_email" name="user_email" placeholder="-" value="{{ $currentUser->email }}" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" id="user_phone" name="user_phone" placeholder="-" value="{{ $currentUser->phone }}" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h2>Harap isikan penilaian anda terhadap Layanan QA/TA/VT</h2>
                    <p>Kustomer diharapkan dapat untuk beberapa kriteria yang diajukan. Nilai tersebut merupakan nilai kustomer  berikan mengenai ekspetasi setuju dan PT. Telkom.

                    Skala pemberian nilai adalah 1 - 7 dengan nilai 7 adalah penilaian Sangat Tidak Baik atau Sangat Tidak Setuju. Kastemer diharapkan dapat memberikan dengan angka bulat.
                    </p>
                </div>
                <div class="row">
                    <table id="table_kuisioner" style="width:100%; padding: 2px;" border="1">
                      <tr>
                        <th>No</th>
                        <th>Kriteria</th> 
                        <th>Nilai Ekspetasi</th>
                        <th>NIlai Performasi</th>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>Pihak UREL (User Relation) mampu menjadi jembatan antara Kastamer dan Yesy Engineer Telkom.</td> 
                        <td><input type="number" min="1" max="7" name="quest1_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest1_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>Proses pelayanan pengujian secara keseluruhan (sejak pengajuan hingga pelaporan) mudah dimengerti oleh Kastemer.</td> 
                        <td><input type="number" min="1" max="7" name="quest2_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest2_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>Pihak UREL memberika informasi serta melakukan pengecekan kelengkapan mengenai berkas-berkas yang harus disiapkan.</td> 
                        <td><input type="number" min="1" max="7" name="quest3_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest3_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>4</td>
                        <td>Setiap lini proses (sejak pengajuan hingga pelaporan) dilakukan dengan cepat.</td> 
                        <td><input type="number" min="1" max="7" name="quest4_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest4_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>5</td>
                        <td>Pihak UREL memberikan informasi yang dibutuhkan oleh Kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest5_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest5_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                    </table>
                </div>
                <div class="row">
                    <p>Menurut Anda, dalam proses pengajuan hingga pelaporan, tahap apa yang sebaiknya ditingkatkan oleh PT. Telkom? Dan mengapa harus ditingkatkan?</p>
                    <textarea name="quest6" class="form-control" placeholder="Jawaban"></textarea>
                </div>
                <div class="row">
                    <p>Pada tahap ini, silahkan mengisi nilai dengan sekala 1-7 untuk nilai ekspetasi awal dan nilai performansi. Kastemer diharapkan mengisi kolom nilai dan setiap kriteria, serta nilai performansi/kenyataan dari setiap kriteria</p>
                    <p>Nilai 7 adalah penilaian Sangat Baik atau Sangat Setuju dan nilai 1 adalah penilaian Sangat Tidak Baik atau Sangat Tidak Setuju. Kastemer diharapkan dapat memberikan nilai dengan angka bulat.</p>
                    <table id="table_kuisioner" style="width:100%; padding: 2px;" border="1">
                      <tr>
                        <th>No</th>
                        <th>Kriteria</th> 
                        <th>Nilai Ekspetasi</th>
                        <th>NIlai Performasi</th>
                      </tr>
                      <tr>
                        <td>7</td>
                        <td>Kastemer percaya pada kualitas pengujian yang dilakukan oleh Telkom.</td> 
                        <td><input type="number" min="1" max="7" name="quest7_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest7_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>8</td>
                        <td>Kastemer merasa pihak UREL faham dan terpercaya.</td> 
                        <td><input type="number" min="1" max="7" name="quest8_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest8_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>9</td>
                        <td>Kastemer merasa pihak UREL sudah melakukan pemeriksaan kelengkapan administrasi dengan kinerja yang baik.</td> 
                        <td><input type="number" min="1" max="7" name="quest9_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest9_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>10</td>
                        <td>Kastemer measa aman sewaktu melakukan transaksi dengan pihak Telkom terutama pihak UREL.</td> 
                        <td><input type="number" min="1" max="7" name="quest10_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest10_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>11</td>
                        <td>Kastemer merasa Engineer Telkom sudah berpengalaman.</td> 
                        <td><input type="number" min="1" max="7" name="quest11_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest11_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>12</td>
                        <td>Alat ukur yang digunakan oleh pihak Telkom berkualitas, baik, dan akurat.</td> 
                        <td><input type="number" min="1" max="7" name="quest12_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest12_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>13</td>
                        <td>Laboratorium yang digunakan oleh pihak Telkom dalam keadaan bersih dan memenuhi Standar Laboratorium.</td> 
                        <td><input type="number" min="1" max="7" name="quest13_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest13_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>14</td>
                        <td>Tarif Pengujian yang ditetapkan oleh pihak PT. Telkom sesuai dan bersaing dengan harga pasar.</td> 
                        <td><input type="number" min="1" max="7" name="quest14_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest14_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>15</td>
                        <td>Pihak UREL yang melayani kastamer berpakaian rapih dan sopan.</td> 
                        <td><input type="number" min="1" max="7" name="quest15_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest15_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>16</td>
                        <td>Kontor Telkom DDS dalam kondisi nyaman, bersih dan sudah sesuai kondisi keseluruhannya.</td> 
                        <td><input type="number" min="1" max="7" name="quest16_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest16_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>17</td>
                        <td>Pihak Telkom mengembalikan barang/perangkat yang diujikan dalam keadaan baik seperti awal.</td> 
                        <td><input type="number" min="1" max="7" name="quest17_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest17_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>18</td>
                        <td>Sertifikat yang diterima oleh kastemer tidak mengalami kesalahan informasi.</td> 
                        <td><input type="number" min="1" max="7" name="quest18_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest18_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>19</td>
                        <td>Pihak Telkom DDS terutama pihak UREL yang melayani proses pengajuan hingga pelaporan sudah memahami kebutuhan kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest19_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest19_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>20</td>
                        <td>Proses pengujian secara keseluruhan tidak memakan durasi waktu yang lama.</td> 
                        <td><input type="number" min="1" max="7" name="quest20_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest20_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>21</td>
                        <td>Pihak UREL cepat dan tepat dalam merespon keluhan yang diberikan kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest21_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest21_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>22</td>
                        <td>Pihak UREL tanggapan dalam membantu permasalahan kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest22_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest22_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>23</td>
                        <td>Engineer tanggapan pada permasalahan yang dihadapi kastamer selama proses pengajuan hingga pelaporan.</td> 
                        <td><input type="number" min="1" max="7" name="quest23_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest23_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>24</td>
                        <td>Pihak UREL mudah dihubungi dan tanggap pada segala pertanyaan yang diajukan kastamer terkait pengujian perangkat.</td> 
                        <td><input type="number" min="1" max="7" name="quest24_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest24_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>25</td>
                        <td>Pihak UREL bersikap ramah dan profesional terhadap kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest25_eks" class="form-control" value="1" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest25_perf" class="form-control" value="1" placeholder="1-7" required></td>
                      </tr>
                    </table>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <!-- <button type="submit" class="button button3d btn-sky" data-dismiss="modal">Simpan</button> -->
			<button type="button" id="submit-kuisioner1" class="button button3d btn-sky">Simpan</button>
          </div>
        </div>

      </div>
    </div>
	
	<div id="modal_kuisioner2" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Kuisioner Kepuasan Customer</h4>
          </div>
          <div class="modal-body pre-scrollable">
            <form id="form-kuisioner">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
							<input type="hidden" id="exam_id" name="exam_id">
                            <label>Nama Responden</label>
							<input type="text" id="user_name" name="user_name" placeholder="-" class="form-control" value="{{ $currentUser->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Perusahaan</label>
                            <input type="text" id="company_name" name="company_name" placeholder="-" class="form-control" value="{{ $currentUser->company->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>No. Tlp / HP</label>
                            <input type="text" id="company_phone" name="company_phone" placeholder="-" value="{{ $currentUser->company->phone }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Jenis Pengujian</label>
                            <input type="text" id="exam_type" name="exam_type" placeholder="-" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Perangkat</label>
                            <input type="text" placeholder="Smartphone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" id="tanggal" name="tanggal" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo date('d-m-Y');?>" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <p>Survey ini terdiri dari dua bagian, yaitu tingkat kepentingan dan tingkat kepuasan Anda. Tingkat kepentingan menunjukan seberapa penting sebuah pernyataan bagi Anda. Sedangkan, tingkat kepuasan menunjukkan seberapa puas pengalaman Anda setelah melakukan pengujian di Infrasutructure Assurance (IAS) Divisi Digital Service (DDS) PT. Telekomuniasi Indonesia, Tbk.
                    </p>
                    <p>Besar pengharapan kami agar pengisian survey ini dapat dikerjakan dengan sebaik-baiknya. Atas kerja samanya, kami ucapkan terimakasih.</p>
                    <p>
                    Skala pemberian nilai adalah 1 - 10 dengan nilai 1 adalah penilaian Sangat Tidak Baik atau Sangat Tidak Setuju. Kastemer diharapkan dapat memberikan dengan angka bulat.
                    </p>
                </div>
                <div class="row">
                    <table id="table_kuisioner" style="width:100%; padding: 2px;" border="1">
                      <tr>
                        <th>NO</th>
                        <th>PERTANYAAN</th>
                        <th>TINGKAT KEPENTINGAN</th>
                        <th>TINGKAT KEPUASAN</th>
                      </tr>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td>Pengajuan <strong>pendaftaran</strong> pengujian dapat dengan mudah dilakukan.</td>
                          <td>
                            <input type="number" name="quest1_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest1_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td>Pelaksanaan <strong>uji fungsi</strong> sebelum barang diterima terlaksana dengan <strong>baik</strong>.</td>
                          <td>
                            <input type="number" name="quest2_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest2_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td>Biaya/<strong>tarif</strong> pengujian perangkat sudah sesuai.</td>
                          <td>
                            <input type="number" name="quest3_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest3_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td><strong>Prosedur</strong> pembayaran dilakukan dengan <strong>mudah</strong>.</td>
                         <td>
                            <input type="number" name="quest4_eks" value="10" min="1" max="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest4_perf" value="10" min="1" max="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td>Perangkat uji <strong>diterima</strong> dengan <strong>baik</strong> oleh petugas.</td>
                          <td>
                            <input type="number" name="quest5_eks" value="10" min="1" max="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest5_perf" value="10" min="1" max="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td><strong>Pelaksanaan</strong> pengujian sesuai dengan <strong>jadwal</strong> yang sudah disepakati.</td>
                          <td>
                            <input type="number" name="quest7_eks" value="10" min="1" max="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest7_perf" value="10" min="1" max="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>7</td>
                          <td>Perangkat uji setelah pengujian <strong>selesai</strong> ditangani dengan <strong>baik</strong>.</td>
                          <td>
                            <input type="number" name="quest8_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest8_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>8</td>
                          <td><strong>Lama pengujian</strong> diselesaikan dengan informasi/kesepakatan yang telah ditentukan.</td>
                          <td>
                            <input type="number" name="quest9_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest9_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>9</td>
                          <td><strong>Komunikasi</strong> antara test engineer Lab. QA DDS Telkom dengan test engineer kami terjalin dengan baik untuk kelancaran pengujian.</td>
                          <td>
                            <input type="number" name="quest10_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest10_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>10</td>
                          <td><strong>Alat ukur</strong> yang digunakan sudah terjamin kualitas dan akurasinya.</td>
                          <td>
                            <input type="number" name="quest11_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest11_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>11</td>
                          <td><strong>Ruang</strong> laboratorium terkondisi dengan baik.</td>
                          <td>
                            <input type="number" name="quest12_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest12_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>12</td>
                          <td><strong>Kapabilitas dan pengalaman</strong> test engineer Lab. QA DDS Telkom sudah sesuai dengan kompetensinya.</td>
                          <td>
                            <input type="number" name="quest13_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest13_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>13</td>
                          <td>Test engineer Lab. QA DDS Telkom memiliki <strong>pemahaman</strong> terhadap materi item uji.</td>
                          <td>
                            <input type="number" name="quest14_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest14_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>14</td>
                          <td>Petugas memberikan <strong>pelayanan</strong> dengan <strong>ramah</strong> dan profesional.</td>
                          <td>
                            <input type="number" name="quest15_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest15_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>15</td>
                          <td>Petugas memberikan <strong>informasi</strong> tentang <strong>tarif</strong> yang jelas kepada kastamer.</td>
                          <td>
                            <input type="number" name="quest16_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest16_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>16</td>
                          <td>Petugas memberikan <strong>informasi</strong> tentang <strong>prosedur</strong> pengujian dengan jelas.</td>
                          <td>
                            <input type="number" name="quest17_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest17_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>17</td>
                          <td>Petugas selalu <strong>tanggap</strong> dengan apa yang diinginkan kastamer.</td>
                          <td>
                            <input type="number" name="quest18_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest18_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>18</td>
                          <td>Petugas memberikan <strong>perlakuan</strong> yang sama kepada semua kastamer.</td>
                          <td>
                            <input type="number" name="quest19_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest19_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                        <tr>
                          <td>19</td>
                          <td>Petugas memberikan <strong>laporan</strong> hasil pengujian dengan cepat dan tepat.</td>
                          <td>
                            <input type="number" name="quest20_eks" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                          <td>
                            <input type="number" name="quest20_perf" min="1" max="10" value="10" placeholder="1-10" class="form-control">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <div class="row">
                    <p>Kritik dan Saran Anda untuk meningkatkan kualitas pelayanan kami:
                    </p>
                    <div class="form-group">
                      <textarea name="quest6" class="form-control" placeholder="Komentar disini"></textarea>
                    </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <!-- <button type="submit" class="button button3d btn-sky" data-dismiss="modal">Simpan</button> -->
			<button type="button" id="submit-kuisioner" class="button button3d btn-sky">Simpan</button>
          </div>
        </div>

      </div>
    </div>

    <div id="modal_complain" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Customer Complaint</h4>
          </div>
          <div class="modal-body pre-scrollable">
                <form id="form-complain">
                    <table id="table_kuisioner" style="width:100%; padding: 2px;" border="1">
                        <tr>
                            <th colspan="2">No</th>
                            <td colspan="2"><input type="text" name="no" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th>Sheet</th>
                            <td><input type="text" name="no" class="form-control" readonly></td>
                            <th>of</th>
                            <td><input type="text" name="no" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <label>Customer Name and Address</label>
                                <textarea class="form-control" placeholder="-" readonly>{{ $currentUser->name }} / {{ $currentUser->address }}</textarea>
                            </th>
                            <td colspan="2">
                                <select class="form-control">
                                    <option>Walk In</option>
                                    <option>Call In</option>
                                    <option>Web In</option>        
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <label>Customer Contact</label>
                                <input type="text" name="no" class="form-control" placeholder="-" value="{{ $currentUser->phone }}" readonly>
                            </th>
                            <td colspan="2">
								<input type="hidden" id="my_exam_id" name="my_exam_id">
								<label>Date</label>
								<!--<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">-->
									<input type="text" id="tanggal_complaint" name="tanggal_complaint" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo date('d-m-Y');?>" readonly required>
									<!--<span class="input-group-btn">
										<button type="button" class="btn btn-default">
											<i class="glyphicon glyphicon-calendar"></i>
										</button>
									</span>-->
								<!--</p>-->
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <label>Customer Complaint</label>
                                <textarea name="complaint" class="form-control" placeholder="Your Complaint"></textarea>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <label>Name of Recipient</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <label>Corrective Action Taken</label>
                                <textarea class="form-control" placeholder="-" readonly></textarea>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <label>Completed Date</label>
                                <input type="text" name="no" class="form-control" placeholder="DD/MM/YYYY" readonly>
                            </th>
                            <td colspan="2">
                                <label>CPAR No</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <label>Name of Actiones</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <label>USer Relation Manager Signature</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </th>
                            <td colspan="2">
                                <label>Date</label>
                                <input type="text" name="no" class="form-control" placeholder="DD/MM/YYYY">
                            </td>
                        </tr>
                    </table>
                </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="submit-complain" class="button button3d btn-sky submit-complain">Lewati</button>
            <button type="button" id="submit-complain2" class="button button3d btn-sky submit-complain">Simpan</button>
          </div>
        </div>

      </div>
    </div>

    <div id="modal_status_barang" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Status Barang</h4>
          </div>
          <div class="modal-body pre-scrollable">
               <div class="row">
                    <h2>Silakan Ambil Barang di Gudang DDS Telkom, Sebelum mengunduh Sertifikat. Terima Kasih</h2>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
          </div>
        </div>

      </div>
    </div> 
	
	<div id="modal_status_download" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Status Download</h4>
          </div>
          <div class="modal-body pre-scrollable">
               <div class="row">
                    <h2>Batas Maksimal Unduh Sertifikat adalah 3 kali. Silakan Hubungi Petugas URel untuk Informasi Lebih Lanjut.</h2>
					<div id="historiDownload">
					</div>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="button button3d btn-sky" data-dismiss="modal">OK</button>
          </div>
        </div>

      </div>
    </div> 
@section('content')
 		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.examination') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">{{ trans('translate.menu_testing') }}</a></li>
					<li class="active">{{ trans('translate.examination') }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content"> 
			<div class="content-wrap"> 
				<div class="container clearfix">
 					<div class="container-fluid container-fullw bg-white">
					 	<div class="row">
					<div class="col-md-3 form-group">
						<select onchange="filter()" class="form-control" id="cmb-jns-pengujian">
							<option value="">{{ trans('translate.examination_choose_type') }}</option>
							@foreach($data_exam_type as $item)
								<option value="{{ $item->id }}">{{ $item->name }} ({{ $item->description }})</option>
							@endforeach
						</select>
					</div>
					
					<div class="col-md-3 form-group">
						<select onchange="filter()" class="form-control" id="cmb-jns-status">
							<option value="">{{ trans('translate.examination_choose_status') }}</option>
							<option value="1">{{ trans('translate.examination_reg') }}</option>
							<option value="2">{{ trans('translate.examination_function') }}</option>
							<option value="3">{{ trans('translate.examination_contract') }}</option>
							<option value="4">{{ trans('translate.examination_spb') }}</option>
							<option value="5">{{ trans('translate.examination_payment') }}</option>
							<option value="6">{{ trans('translate.examination_spk') }}</option>
							<option value="7">{{ trans('translate.examination_exam') }}</option>
							<option value="8">{{ trans('translate.examination_report') }}</option>
							<option value="9">{{ trans('translate.examination_qa') }}</option>
							<option value="10">{{ trans('translate.examination_certificate') }}</option>
						</select>
					</div>
					<!--  
					<div class="col-md-6 col-xs-12">
						<span class="input-icon input-icon-right search-table"> 
							<input id="search_value" type="text" placeholder="{{ trans('translate.search_exam') }}" id="form-field-17" class="form-control " value="{{ $search }}">
							<i class="ti-search"></i>
						</span>
					</div>
					-->
				</div>
			
				@if (Session::get('error'))
					<div class="alert alert-error alert-danger">
						{{ Session::get('error') }}
					</div>
				@endif
				
				@if (Session::get('message'))
					<div class="alert alert-info">
						{{ Session::get('message') }}
					</div>
				@endif
				<div id="html-filter">
				<div class="row">
					<div class="col-md-12">
						<?php if(count($data)>0){ ?>
						@foreach($data as $item)
						<div class="col-md-12 list-border-progress">
							<div class="step list-progress">
								<div class="garis garis-progress" style="{{($item->examination_type_id == '1')?'width:83%':'width:70%'}}"></div>
								<ul class="number" style="width:100%;">
									<li>
									@if($item->registration_status == '1')
										<button class="step-fill done">1</button>
									@else
										<button class="step-fill active">1</button>
									@endif
										<p>{{ trans('translate.examination_reg')}}</p>
									</li>
									<li>
									@if($item->registration_status == '1' && $item->function_status != '1')
										<button class="step-fill active">2</button>
									@elseif($item->function_status == '1')
										<button class="step-fill done">2</button>
									@else
										<button class="step-fill">2</button>
									@endif
										<p> {{ trans('translate.examination_function') }}</p>
									</li>
									<li>
									@if($item->function_status == '1' && $item->contract_status != '1')
										<button class="step-fill active">3</button>
									@elseif($item->contract_status == '1')
										<button class="step-fill done">3</button>
									@else
										<button class="step-fill">3</button>
									@endif
										<p> {{ trans('translate.examination_contract') }}</p>
									</li>
									<li>
									@if($item->contract_status == '1' && $item->spb_status != '1')
										<button class="step-fill active">4</button>
									@elseif($item->spb_status == '1')
										<button class="step-fill done">4</button>
									@else
										<button class="step-fill">4</button>
									@endif
										<p>{{ trans('translate.examination_spb') }}</p>
									</li>
									<li>
									@if($item->spb_status == '1' && $item->payment_status != '1')
										<button class="step-fill active">5</button>
									@elseif($item->payment_status == '1')
										<button class="step-fill done">5</button>
									@else
										<button class="step-fill">5</button>
									@endif
										<p>{{ trans('translate.examination_payment') }} </p>
									</li>
									<li>
									@if($item->payment_status == '1' && $item->spk_status != '1')
										<button class="step-fill active">6</button>
									@elseif($item->spk_status == '1')
										<button class="step-fill done">6</button>
									@else
										<button class="step-fill">6</button>
									@endif
										<p> {{ trans('translate.examination_spk') }}</p>
									</li>
									<li>
									@if($item->spk_status == '1' && $item->examination_status != '1')
										<button class="step-fill active">7</button>
									@elseif($item->examination_status == '1')
										<button class="step-fill done">7</button>
									@else
										<button class="step-fill">7</button>
									@endif
										<p>{{ trans('translate.examination_exam') }} </p>
									</li>
									<li>
									@if($item->examination_status == '1' && $item->resume_status != '1')
										<button class="step-fill active">8</button>
									@elseif($item->resume_status == '1')
										<button class="step-fill done">8</button>
									@else
										<button class="step-fill">8</button>
									@endif
										<p>{{ trans('translate.examination_report') }} </p>
									</li>
									@if($item->examination_type_id == '1')
									<li>
									@if($item->resume_status == '1' && $item->qa_status != '1')
										<button class="step-fill active">9</button>
									@elseif($item->qa_status == '1')
										<button class="step-fill done">9</button>
									@else
										<button class="step-fill">9</button>
									@endif
										<p>{{ trans('translate.examination_qa') }} </p>
									</li>
									<li>
									@if($item->qa_status == '1' && $item->certificate_status != '1')
										<button class="step-fill active">10</button>
									@elseif($item->certificate_status == '1')
										<button class="step-fill done">10</button>
									@else
										<button class="step-fill">10</button>
									@endif
										<p>{{ trans('translate.examination_certificate') }} </p>
									</li> 
									@endif
								</ul>
							</div>
							<div class="data-status">
								<table class="table table-striped">
									<tr>
										<th colspan="3">{{ trans('translate.examination_status') }}</th>
									</tr>
									@if($item->registration_status != '0' && $item->function_status != '1')
									<tr>
										<td>{{ trans('translate.examination_function_test_date') }}</td>
										<td colspan="2"> :
											@if($item->function_date != null)
												{{ $item->function_date }} (FIX) {{ $item->function_test_reason }}
											@elseif($item->function_date == null && $item->urel_test_date != null)
												{{ $item->urel_test_date }} ({{ trans('translate.from_customer') }}) {{ $item->function_test_reason }}
											@elseif($item->urel_test_date == null && $item->deal_test_date != null)
												{{ $item->deal_test_date }} ({{ trans('translate.from_te') }}) {{ $item->function_test_reason }}
											@else
												{{ $item->cust_test_date }} {{ trans('translate.from_customer') }}
											@endif
										</td>
									</tr>
									@endif
									<tr>
										<td>{{ trans('translate.examination_type') }}</td>
										<td colspan="2">: {{ $item->jns_pengujian }} ( {{ $item->desc_pengujian }} )</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_user') }}</td>
										<td colspan="2">: {{ $item->userName }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_company') }}</td>
										<td colspan="2">: {{ $item->companiesName }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_date_regist') }}</td>
										<td colspan="2">: {{ $item->created_at }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_equipment') }}</td>
										<td colspan="2">: {{ $item->nama_perangkat }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_capacity') }}</td>
										<td colspan="2">: {{ $item->kapasitas_perangkat }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_model') }}</td>
										<td colspan="2">: {{ $item->model_perangkat }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_number_exam_form') }}</td>
										<td colspan="2">: {{ $item->function_test_NO }}</td>
									</tr>
									<tr>
										<td>{{ trans('translate.examination_labs_name') }}</td>
										<td colspan="2">: {{ $item->labs_name }}</td>
									</tr>
								</table>
							</div>
							<div class="option-progress right"> 

								<a class="button button-3d nomargin btn-blue" href="{{URL::to('cetakPengujian/'.$item->id.'')}}" target="_blank">{{ trans('translate.examination_print') }}</a>
								<a class="button button-3d nomargin btn-blue " href="{{URL::to('pengujian/'.$item->id.'/detail')}}">{{ trans('translate.examination_detail') }}</a>
								
								@if($item->registration_status != '0' && $item->function_status != '1')
									@if($item->deal_test_date == NULL)
									<a class="button button-3d edit_btn nomargin btn-blue" onclick="reSchedule('<?php echo $item->id ?>','<?php echo $item->cust_test_date ?>','1','<?php echo $item->deal_test_date ?>','<?php echo $item->urel_test_date ?>')">{{ trans('translate.examination_reschedule_test_date') }}</a>
									@elseif($item->deal_test_date != NULL && $item->function_date == NULL)
									<a class="button button-3d edit_btn nomargin btn-blue" onclick="reSchedule('<?php echo $item->id ?>','<?php echo $item->cust_test_date ?>','2','<?php echo $item->deal_test_date ?>','<?php echo $item->urel_test_date ?>')">{{ trans('translate.examination_reschedule_test_date') }}</a>
									@endif
								@endif
								
								<?php if($item->spb_status == 1 and $item->payment_status != 1){ ?>
									<a class="button edit_btn button-3d nomargin btn-blue" href="{{URL::to('pengujian/'.$item->id.'/pembayaran')}}">{{ trans('translate.examination_payment') }}</a>
									<a class="button edit_btn button-3d nomargin btn-blue" href="{{URL::to('pengujian/'.$item->id.'/downloadSPB')}}">{{ trans('translate.download') }} SPB</a>
								<?php } ?>
								
								<?php if($item->registration_status != 1){ ?>
									<a class="button edit_btn button-3d nomargin btn-blue" href="{{url('editprocess/'.$item->jns_pengujian.'/'.$item->id)}}">{{ trans('translate.examination_edit') }}</a>
								<?php } ?>
								
								<?php if(
				  $item->registration_status == 1 &&
                  $item->function_status == 1 &&
                  $item->contract_status == 1 &&
                  $item->spb_status == 1 &&
                  $item->payment_status == 1 &&
                  $item->spk_status == 1 &&
                  $item->examination_status == 1 &&
                  $item->resume_status == 1 &&
					date('Y-m-d') >= $item->resume_date
					){ ?>
									<a class="button button-3d edit_btn download_progress_btn nomargin btn-blue" href="javascript:void(0)" onclick="return isTestimonial('{{ $item->device_id }}','{{ URL::to('pengujian/'.$item->id.'/downloadLaporanPengujian') }}','device', '{{$item->jns_pengujian}} ({{$item->desc_pengujian}})', '{{$item->id}}');">{{ trans('translate.download') }} {{ trans('translate.examination_report') }}</a>
								<?php } ?>
								
								<?php if(
                  $item->registration_status == 1 &&
                  $item->function_status == 1 &&
                  $item->contract_status == 1 &&
                  $item->spb_status == 1 &&
                  $item->payment_status == 1 &&
                  $item->spk_status == 1 &&
                  $item->examination_status == 1 &&
                  $item->resume_status == 1 &&
                  $item->qa_status == 1 &&
                  $item->certificate_status == 1
                ){ ?>
									<a class="button button-3d edit_btn download_progress_btn nomargin btn-blue" href="javascript:void(0)" onclick="return isTestimonial('{{ $item->device_id }}','{{ $item->sistem_mutuPerangkat }}','device', '{{$item->jns_pengujian}} ({{$item->desc_pengujian}})', '{{$item->id}}');">{{ trans('translate.download') }} {{ trans('translate.certificate') }}</a>
								<?php } ?>
							</div>
						</div>
						@endforeach
						<?php }else{?>
						<div class="table-responsive font-table">
							<table class="table table-striped table-bordered table-hover table-full-width dataTable no-footer" id="sample-table-1">
								<thead>
									<tr align="center">
										<th colspan="3" style="text-align: center;">{{ trans('translate.data_not_found') }}</th>
									</tr>
								</thead>
							</table>
						</div>
						<?php }?>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap_full_number pull-right" >
									<?php echo $data->appends(array('search' => $search,'jns' => $jns,'status' => $status))->links(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
					</div> 
				</div>
			</div> 
		</section><!-- #content end -->
		

<div class="modal fade bs-example-modal-lg" id="myModaledit" aria-labelledby="myModalLabel" style="display: none;" data-backdrop="static" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="row">
			<div id="contact">
				<form role="form" action="#" method="post" class="f1" id="form-permohonan-edit" enctype="multipart/form-data">
					{!! csrf_field() !!}
					<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
					<input type="hidden" name="hide_exam_id_edit" id="hide_exam_id_edit"/>
					<input type="hidden" name="hide_device_id_edit" id="hide_device_id_edit"/>
					<input type="hidden" name="hide_id_user" id="hide_id_user">
					<input type="hidden" name="hide_company_id" id="hide_company_id">
					<div class="row" style="padding-right: 10px;margin-top: 20px;">
					<a data-dismiss="modal" style="cursor:pointer;"><img src="{{asset('template-assets/img/close (2).png')}}" style=" margin-top:-27px; float:right;" width="20"></a>
					</div>
					<h5>{{ trans('translate.service_title') }}</h5>
					<div class="f1-steps">
						<div class="f1-progress">
							<div class="f1-progress-line" data-now-value="52.22" data-number-of-steps="8" style="width: 52.22%;"></div>
						</div>
						<div class="f1-step">
							<div class="f1-step-icon"><i class="fa fa-user"></i></div>
							<p>{{ trans('translate.service_application') }}</p>
						</div>
						<div class="f1-step active">
							<div class="f1-step-icon"><i class="fa fa-user"></i></div>
							<p>{{ trans('translate.service_company') }}</p>
						</div>
						<div class="f1-step">
							<div class="f1-step-icon"><i class="fa fa-user"></i></div>
							<p>{{ trans('translate.service_device') }}</p>
						</div>
						<div class="f1-step">
							<div class="f1-step-icon"><i class="fa fa-user"></i></div>
							<p>{{ trans('translate.service_upload') }}</p>
						</div>
						<div class="f1-step">
							<div class="f1-step-icon"><i class="fa fa-user"></i></div>
							<p>{{ trans('translate.service_preview') }}</p>
						</div>
						  <div class="f1-step">
							<div class="f1-step-icon"><i class="fa fa-user"></i></div>
							<p>{{ trans('translate.service_upload_form') }}</p>
						</div>
						  <div class="f1-step">
							<div class="f1-step-icon"><i class="fa fa-user"></i></div>
							<p>{{ trans('translate.service_finished') }}</p>
						</div>
					</div>
						
						<input type="hidden" name="f1-nama-pemohon-edit" placeholder="Nama pemohon" class="data-pemohon f1-nama-pemohon form-control input-submit" id="f1-nama-pemohon" readonly>
						<input type="hidden" name="f1-alamat-pemohon-edit" placeholder="Alamat..." class="data-pemohon f1-alamat-pemohon form-control input-submit" id="f1-alamat-pemohon" readonly>
						<input type="hidden" name="f1-telepon-pemohon-edit" placeholder="Telepon..." class="data-pemohon f1-telepon-pemohon form-control input-submit" id="f1-telepon-pemohon" readonly>
						<input type="hidden" name="f1-faksimile-pemohon-edit" placeholder="Faksimile..." class="data-pemohon f1-faksimile-pemohon form-control input-submit" id="f1-faksimile-pemohon" readonly>
						<input type="hidden" name="f1-email-pemohon-edit" placeholder="E-mail..." class="data-pemohon f1-email-pemohon form-control input-submit" id="f1-email-pemohon" readonly>
						<!--
							<input type="radio" name="jns-perusahaan" class="jns-perusahaan" id="edit-rad-pabrikan" value="Pabrikan">Pabrikan
							<input type="radio" name="jns-perusahaan" class="jns-perusahaan" id="edit-rad-agen" value="Agen/Perwakilan">Agen/Perwakilan
							<input type="radio" name="jns-perusahaan" class="jns-perusahaan" id="edit-rad-pengguna" value="Pengguna/Perorangan">Pengguna/Perorangan
						-->
					
					<!-- Data Perusahaan-->
					<fieldset>
						<h4>{{ trans('translate.service_company') }}</h4>
						<div class="form-group">
							<label for="f1-jns-perusahaan">{{ trans('translate.service_company_type') }} : </label>
							<input type="radio" name="jns_perusahaan" class="rad-jns_perusahaan-edit" id="rad-jns_perusahaan1-edit" value="Agen">{{ trans('translate.service_company_agent') }}
							<input type="radio" name="jns_perusahaan" class="rad-jns_perusahaan-edit" id="rad-jns_perusahaan2-edit" value="Pabrikan">{{ trans('translate.service_company_branch') }}
							<input type="radio" name="jns_perusahaan" class="rad-jns_perusahaan-edit" id="rad-jns_perusahaan3-edit" value="Perorangan">{{ trans('translate.service_company_individual') }}
						</div>
						<div class="form-group">
							<label for="f1-nama-perusahaan">{{ trans('translate.service_company_name') }}</label>
							<input type="text" name="f1-nama-perusahaan-edit" placeholder="{{ trans('translate.service_company_name') }}" class="data-perusahaan f1-nama-perusahaan form-control input-submit" id="f1-nama-perusahaan" readonly>
						</div>
						<div class="form-group">
							<label for="f1-alamat-perusahaan">{{ trans('translate.service_company_address') }}</label>
							<input type="text" name="f1-alamat-perusahaan-edit" placeholder="{{ trans('translate.service_company_address') }}" class="data-perusahaan f1-alamat-perusahaan form-control input-submit" id="f1-alamat-perusahaan" readonly>
						</div>
						<div class="form-group">
							<label for="f1-telepon-perusahaan">{{ trans('translate.service_company_phone') }}</label>
							 <input type="text" name="f1-telepon-perusahaan-edit" placeholder="{{ trans('translate.service_company_phone') }}" class="data-perusahaan f1-telepon-perusahaan form-control input-submit" id="f1-telepon-perusahaan" readonly>
						</div>
						<div class="form-group">
							<label for="f1-faksimile-perusahaan">{{ trans('translate.service_company_fax') }}</label>
							 <input type="text" name="f1-faksimile-perusahaan-edit" placeholder="{{ trans('translate.service_company_fax') }}" class="data-perusahaan f1-faksimile-perusahaan form-control input-submit" id="f1-faksimile-perusahaan" readonly>
						</div>
						<div class="form-group">
							<label for="f1-email-perusahaan">{{ trans('translate.service_company_email') }}</label>
							 <input type="text" name="f1-email-perusahaan-edit" placeholder="{{ trans('translate.service_company_email') }}" class="data-perusahaan f1-email-perusahaan form-control input-submit" id="f1-email-perusahaan" readonly>
						</div>
						<div class="f1-buttons">
							<button type="button" class="btn btn-next">{{ trans('translate.service_next') }}</button>
						</div>
					</fieldset>	
					<!-- Data Perangkat-->
					<fieldset>
						<input type="hidden" name="hide_jns_pengujian_edit" id="hide_jns_pengujian" class="hide_jns_pengujian_edit"/>
						<input type="hidden" name="hide_serial_number_edit" id="hide_serial_number_edit"/>
						<input type="hidden" name="hide_name_edit" id="hide_name_edit"/>
						<input type="hidden" name="hide_model_edit" id="hide_model_edit"/>
						<h4>{{ trans('translate.service_device') }}</h4>
						<div class="form-group">
							<label for="f1-nama-perangkat">{{ trans('translate.service_device_equipment') }}</label>
							<input type="text" name="f1-nama-perangkat-edit" placeholder="{{ trans('translate.service_device_equipment') }}" class="data-perangkat f1-nama-perangkat form-control input-submit" id="f1-nama-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-merek-perangkat">{{ trans('translate.service_device_mark') }}</label>
							<input type="text" name="f1-merek-perangkat-edit" placeholder="{{ trans('translate.service_device_mark') }}" class="data-perangkat f1-merek-perangkat form-control input-submit" id="f1-merek-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-kapasitas-perangkat">{{ trans('translate.service_device_capacity') }}</label>
							<input type="text" name="f1-kapasitas-perangkat-edit" placeholder="{{ trans('translate.service_device_capacity') }}" class="data-perangkat f1-kapasitas-perangkat form-control input-submit" id="f1-kapasitas-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-pembuat-perangkat">{{ trans('translate.service_device_manufactured_by') }}</label>
							<input type="text" name="f1-pembuat-perangkat-edit" placeholder="{{ trans('translate.service_device_manufactured_by') }}" class="data-perangkat f1-pembuat-perangkat form-control input-submit" id="f1-pembuat-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-serialNumber-perangkat">{{ trans('translate.service_device_serial_number') }}</label>
							<input type="text" name="f1-serialNumber-perangkat-edit" placeholder="{{ trans('translate.service_device_serial_number') }}" class="data-perangkat f1-serialNumber-perangkat form-control input-submit" id="f1-serialNumber-perangkat">
						</div>
						<div class="form-group">
							<label for="f1-model-perangkat">{{ trans('translate.service_device_model') }}</label>
							<input type="text" name="f1-model-perangkat-edit" placeholder="{{ trans('translate.service_device_model') }}" class="data-perangkat f1-model-perangkat form-control input-submit" id="f1-model-perangkat">
						</div>
						<input type="hidden" class="data-perangkat f1-jns-referensi-perangkat" id="f1-jns-referensi-perangkat" name="f1-jns-referensi-perangkat">
						<div class="form-group cmb-ref-perangkat">
							<label for="f1-referensi-perangkat">{{ trans('translate.service_device_test_reference') }}</label>
							<select class="form-control" id="f1-cmb-ref-perangkat" name="f1-cmb-ref-perangkat">
									<option value="">{{ trans('translate.service_device_test_reference') }}</option>
								@foreach($data_stels as $item)
									<option value="{{ $item->code }}">{{ $item->code }} || {{ $item->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group txt-ref-perangkat">
							<label for="f1-referensi-perangkat">{{ trans('translate.service_device_test_reference') }}</label>
							<input type="text" name="f1-referensi-perangkat-edit" placeholder="{{ trans('translate.service_device_test_reference') }}" class="data-perangkat f1-referensi-perangkat form-control input-submit" id="f1-referensi-perangkat">
						</div>
						<div class="f1-buttons">
							<button type="button" class="btn btn-previous">{{ trans('translate.service_previous') }}</button>
							<button type="button" class="btn btn-next cek-SN-jnsPengujian">{{ trans('translate.service_next') }}</button>
						</div>
					</fieldset>
					<!-- upload berkas-->
					<fieldset>
						<h4>{{ trans('translate.service_upload') }}</h4>
						<div class="form-group">
							<label>{{ trans('translate.service_upload_siupp') }}<span class="text-danger">*</span></label>
							<input class="data-upload-berkas f1-file-siupp" id="fileInput-SIUPP" name="fuploadsiupp_edit" type="file" accept="application/pdf">
							<input type="hidden" name="hide_siupp_file_edit" id="hide_siupp_file" value=""/>
							<a id="siupp-file" class="btn btn-link siupp-file-edit" style="color:black !important;" ></a>
						</div>
						<div class="form-group" style="margin-bottom:0.01%">
							<label class="sr-only" for="f1-no-siupp">{{ trans('translate.service_upload_siupp_no') }}</label>
							<input type="text" name="f1-no-siupp-edit" placeholder="{{ trans('translate.service_upload_siupp_no') }}" class="data-upload-berkas f1-no-siupp form-control input-submit" id="f1-no-siupp">
						</div>
						<div class="form-group">
							<label class="sr-only" for="f1-tgl-siupp">{{ trans('translate.service_upload_siupp_date') }}</label>
							<input type="hidden" name="f1-tgl-siupp-edit" placeholder="{{ trans('translate.service_upload_siupp_date') }}" class="date data-upload-berkas f1-tgl-siupp form-control input-submit" id="f1-tgl-siupp" readonly>
							<div class="col-xs-1 selectContainer">
								D: <select name="daySIUPP" id="daySIUPP" class="form-control" style="width:auto;" onchange="setDays(monthSIUPP,this,yearSIUPP,1)">
									@for($i = 1;$i <= 31; $i++)
										<?php
											if($i < 10){
												$i = '0'.$i;
											}
										?>
										<option value="{{$i}}">{{$i}}</option>
									@endfor
								</select>
							</div>
							<div class="col-xs-2 selectContainer">
								M: <select name="monthSIUPP" id="monthSIUPP" class="form-control" style="width:auto;" onchange="setDays(this,daySIUPP,yearSIUPP,1)">
									<option value="01">January</option>
									<option value="02">February</option>
									<option value="03">March</option>
									<option value="04">April</option>
									<option value="05">May</option>
									<option value="06">June</option>
									<option value="07">July</option>
									<option value="08">August</option>
									<option value="09">September</option>
									<option value="10">October</option>
									<option value="11">November</option>
									<option value="12">December</option>
								</select>
							</div>
							<div class="col-xs-2 selectContainer">
								Y: <select name="yearSIUPP" id="yearSIUPP" class="form-control" style="width:auto;" onchange="setDays(monthSIUPP,daySIUPP,this,1)">
									@for($i = date('Y')+100;$i >= 1900; $i--)
										@if($i == date('Y'))
											<option value="{{$i}}" selected>{{$i}}</option>
										@else
											<option value="{{$i}}">{{$i}}</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="form-group col-xs-12" style="margin-top:35px">
							<label>{{ trans('translate.service_upload_certificate') }}<span class="text-danger">*</span></label>
							<input type="text" name="f1-sertifikat-sistem-mutu-edit" placeholder="{{ trans('translate.service_upload_certificate') }}" class="data-upload-berkas f1-sertifikat-sistem-mutu form-control input-submit" id="f1-sertifikat-sistem-mutu">
						</div>
						<div class="form-group col-xs-12" style="margin-bottom:0.01%">
							<label>{{ trans('translate.service_upload_certificate_file') }}<span class="text-danger">*</span></label>
							<input class="data-upload-berkas f1-file-lampiran" id="fileInput-lampiran" name="fuploadlampiran_edit" type="file" accept="application/pdf">
							<input type="hidden" name="hide_sertifikat_file_edit" id="hide_sertifikat_file" value=""/>
							<a id="sertifikat-file" class="btn btn-link sertifikat-file-edit" style="color:black !important;" ></a>
						</div>
						<div class="form-group">
							<label class="sr-only" for="f1-batas-waktu">{{ trans('translate.service_upload_certificate_date') }}</label>
							<input type="hidden" name="f1-batas-waktu-edit" placeholder="{{ trans('translate.service_upload_certificate_date') }}" class="date data-upload-berkas f1-batas-waktu form-control input-submit" id="f1-batas-waktu" readonly>
							<div class="col-xs-1 selectContainer">
								D: <select name="daySerti" id="daySerti" class="form-control" style="width:auto;" onchange="setDays(monthSerti,this,yearSerti,0)">
									@for($i = 1;$i <= 31; $i++)
										<?php
											if($i < 10){
												$i = '0'.$i;
											}
										?>
										<option value="{{$i}}">{{$i}}</option>
									@endfor
								</select>
							</div>
							<div class="col-xs-2 selectContainer">
								M: <select name="monthSerti" id="monthSerti" class="form-control" style="width:auto;" onchange="setDays(this,daySerti,yearSerti,0)">
									<option value="01">January</option>
									<option value="02">February</option>
									<option value="03">March</option>
									<option value="04">April</option>
									<option value="05">May</option>
									<option value="06">June</option>
									<option value="07">July</option>
									<option value="08">August</option>
									<option value="09">September</option>
									<option value="10">October</option>
									<option value="11">November</option>
									<option value="12">December</option>
								</select>
							</div>
							<div class="col-xs-2 selectContainer">
								Y: <select name="yearSerti" id="yearSerti" class="form-control" style="width:auto;" onchange="setDays(monthSerti,daySerti,this,0)">
									@for($i = date('Y')+100;$i >= 1900; $i--)
										@if($i == date('Y'))
											<option value="{{$i}}" selected>{{$i}}</option>
										@else
											<option value="{{$i}}">{{$i}}</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="form-group col-xs-12" style="margin-top:35px">
							<label>{{ trans('translate.service_upload_npwp') }}<span class="text-danger">*</span></label>
							<input class="data-upload-berkas f1-file-NPWP" id="fileInput-NPWP" name="fuploadnpwp_edit" type="file" accept="application/pdf">
							<input type="hidden" name="hide_npwp_file_edit" id="hide_npwp_file" value=""/>
							<a id="npwp-file" class="btn btn-link npwp-file-edit" style="color:black !important;" ></a>
						</div>
						<div class="form-group col-xs-12">
							<label>{{ trans('translate.service_upload_reference_test') }}<span class="text-danger">*</span></label>
							<input class="data-upload-berkas f1-file-ref-uji" id="fileInput-ref-uji" name="fuploadrefuji_edit" type="file" accept="application/pdf">
							<input type="hidden" name="hide_ref_uji_file_edit" id="hide_ref_uji_file" value=""/>
							<a id="ref-uji-file" class="btn btn-link ref-uji-file-edit" style="color:black !important;" ></a>
						</div>
						<div class="dv-srt-dukungan-prinsipal">
							<div class="form-group col-xs-12">
								<label>{{ trans('translate.service_upload_support_principals') }}<span class="text-danger">*</span></label>
								<input class="data-upload-berkas f1-file-prinsipal" id="fileInput-prinsipal" name="fuploadprinsipal_edit" type="file" accept="application/pdf">
								<input type="hidden" name="hide_prinsipal_file_edit" id="hide_prinsipal_file" value=""/>
								<a id="prinsipal-file" class="btn btn-link prinsipal-file-edit" style="color:black !important;" ></a>
							</div>
						</div>
						<div class="dv-srt-sp3">
							<div class="form-group col-xs-12">
								<label>{{ trans('translate.service_upload_sp3') }}<span class="text-danger">*</span></label>
								<input class="data-upload-berkas f1-file-sp3" id="fileInput-sp3" name="fuploadsp3_edit" type="file" accept="application/pdf">
								<input type="hidden" name="hide_sp3_file_edit" id="hide_sp3_file" value=""/>
								<a id="sp3-file" class="btn btn-link sp3-file-edit" style="color:black !important;" ></a>
							</div>
						</div>
						<div class="f1-buttons col-xs-12">
							<button type="button" class="btn btn-previous">{{ trans('translate.service_previous') }}</button>
							<button type="button" class="btn btn-next">{{ trans('translate.service_next') }}</button>
						</div>
					</fieldset>
					<!-- Preview-->
					<fieldset>
						<input type="hidden" name="hide_cekSNjnsPengujian_edit" id="hide_cekSNjnsPengujian_edit">
						<h4>{{ trans('translate.service_preview') }}</h4>
						<h3>{{ trans('translate.service_application') }}</h3>
						<table class="table table-striped">
							<tr>
								<td>{{ trans('translate.service_application_name') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-1" class="f1-preview-1-edit"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_application_address') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-2" class="f1-preview-2-edit"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_application_phone') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-3" class="f1-preview-3-edit"></div></td>
								<td colspan=2></td>
								<td>{{ trans('translate.service_application_fax') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-4" class="f1-preview-4-edit"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_application_email') }}</td>
								<td> : </td>
								<td> <div id="f1-preview-5" class="f1-preview-5-edit"></div></td>
							</tr>
						</table>
						<h3>{{ trans('translate.service_company') }}</h3>
						<div id="f2-preview-6"></div>
						<table class="table table-striped">
							<tr>
								<td>{{ trans('translate.service_company_name') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-1" class="f2-preview-1-edit"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_company_address') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-2" class="f2-preview-2-edit"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_company_phone') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-3" class="f2-preview-3-edit"></div></td>
								<td colspan=2></td>
								<td>{{ trans('translate.service_company_fax') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-4" class="f2-preview-4-edit"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_company_email') }}</td>
								<td> : </td>
								<td> <div id="f2-preview-5" class="f2-preview-5-edit"></div></td>
							</tr>
						</table>
						<h3 id="f5-jns-pengujian" class="f5-jns-pengujian-edit">{{ trans('translate.service_preview_exam_type') }} : </h3>
						<br>
						<h3>{{ trans('translate.service_device') }}</h3>
						<table class="table table-striped">
							<tr>
								<td>{{ trans('translate.service_device_equipment') }}</td>
								<td> : </td>
								<td> <div id="f3-preview-1" class="f3-preview-1"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_device_mark') }}</td>
								<td> : </td>
								<td> <div id="f3-preview-2"></div></td>
								<td colspan=2></td>
								<td>{{ trans('translate.service_device_model') }}</td>
								<td> : </td>
								<td> <div id="f3-preview-3"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_device_capacity') }}</td>
								<td> : </td>
								<td> <div id="f3-preview-4"></div></td>
								<td colspan=2></td>
								<td>{{ trans('translate.service_device_test_reference') }}</td>
								<td> : </td>
								<td> <div id="f3-preview-5"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_device_serial_number') }}</td>
								<td> : </td>
								<td> <div id="f3-preview-7"></div></td>
								<td colspan=2></td>
								<td>{{ trans('translate.service_device_manufactured_by') }}</td>
								<td> : </td>
								<td> <div id="f3-preview-6"></div></td>
							</tr>
						</table>
						<h3>{{ trans('translate.service_upload') }}</h3>
						<table class="table table-striped">
							<tr>
								<td>{{ trans('translate.service_upload_siupp') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-1" class="f4-preview-1-edit"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_upload_siupp_no') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-2"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_upload_siupp_date') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-3"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_upload_certificate') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-5"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_upload_certificate_file') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-6" class="f4-preview-6-edit"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_upload_certificate_date') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-7"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_upload_npwp') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-11" class="f4-preview-11-edit"></div></td>
							</tr>
							<tr>
								<td>{{ trans('translate.service_upload_reference_test') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-file-ref-uji" class="f4-preview-file-ref-uji"></div></td>
							</tr>
							<tr class="dv-srt-dukungan-prinsipal">
								<td>{{ trans('translate.service_upload_support_principals') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-8" class="f4-preview-8-edit"></div></td>
							</tr>
							<tr class="dv-srt-sp3">
								<td>{{ trans('translate.service_upload_sp3') }}</td>
								<td> : </td>
								<td> <div id="f4-preview-file-sp3" class="f4-preview-file-sp3-edit"></div></td>
							</tr>
						</table>
						<div class="f1-buttons">
							<button type="button" class="btn btn-previous">{{ trans('translate.service_previous') }}</button>
							<button type="button" class="btn btn-next update-permohonan">{{ trans('translate.service_save') }}</button>
						</div>
					</fieldset>
					<!-- upload detail pengujian-->
					<fieldset>
						<h4>{{ trans('translate.service_upload_form') }}</h4>
						<div class="form-group">
							<label>{{ trans('translate.service_upload_now') }}<span class="text-danger">*</span></label>
							<input class="data-upload-detail-pengujian" id="fileInput-detail-pengujian" name="fuploaddetailpengujian_edit" type="file" accept="application/pdf">
							<input type="hidden" name="hide_attachment_file_edit" id="hide_attachment_file" value=""/>
							<a id="attachment-file" class="btn btn-link attachment-file-edit" style="color:black !important;" ></a>
							<button type="button" class="btn btn-next upload-form">{{ trans('translate.service_upload_now') }}</button>
							<div id="attachment-file">
								{{ trans('translate.service_upload_if_form') }}
								<a class="btn btn-link" style="margin-left:-10px; height:37px; color:black !important; font-size: 100%;" href="{{ url('/cetakPermohonan') }}" target="_blank">{{ trans('translate.service_upload_click') }}</a>
							</div>
						</div>
						<div class="f1-buttons">
							<button type="button" class="btn btn-next">{{ trans('translate.service_upload_later') }}</button>
						</div>
					</fieldset>
					<!-- submit-->
					<fieldset>
						<h4 class="judulselesai">{{ trans('translate.service_thanks') }}</h4>
						<div class="f1-buttons">
							<button type="button" class="btn btn-submit">OK</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>	  
	</div>
</div>

<form id="form" role="form" method="POST" action="{{ url('/pengujian/tanggaluji') }}">
{!! csrf_field() !!}
<input type="hidden" name="hide_id_exam" id="hide_id_exam"/>
<input type="hidden" name="hide_date_type" id="hide_date_type"/>
<div class="modal fade" id="reschedule-modal-content" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-eyes-open"></i> {{ trans('translate.reschedule_message') }}</h4>
			</div>
			
			<div class="modal-body">
				<table width=100%>
					<tr>
						<td>
							<div class="form-group">
									<label>
										{{ trans('translate.reschedule_date') }} *
									</label>
									<!-- <p class="input-group input-append"> -->
										<input type="text" id="cust_test_date" class="form-control datepicker" name="cust_test_date" placeholder="Tanggal ..." readonly>
										<span class="input-group-btn">
											<!-- <button type="button" class="btn btn-default"> -->
												<i class="glyphicon glyphicon-calendar"></i>
											<!-- </button> -->
										</span>
									<!-- </p> -->
								</div>
						</td>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table width=100%>
					<tr>
						<td>
							<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
						</td>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
</form>

<form id="form" role="form" method="POST" action="{{ url('/pengujian/tanggaluji') }}">
{!! csrf_field() !!}
<input type="hidden" name="hide_id_exam2" id="hide_id_exam2"/>
<input type="hidden" name="hide_date_type2" id="hide_date_type2"/>
<div class="modal fade" id="reschedule-modal-content2" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><i class="fa fa-eyes-open"></i> {{ trans('translate.reschedule_message') }}</h4>
			</div>
			
			<div class="modal-body">
				<table width=100%>
					<tr>
						<td>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_date_te1') }} *
										</label>
											<input type="text" id="deal_test_date2" class="form-control" placeholder="Tanggal ..." readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_date_cust1') }} *
										</label>
											<input type="text" id="cust_test_date2" class="form-control" placeholder="Tanggal ..." readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_date') }} *
										</label>
										<!-- <p class="input-group input-append"> -->
											<input type="text" id="urel_test_date2" class="form-control datepicker" name="urel_test_date" placeholder="Tanggal ..." readonly>
											<span class="input-group-btn">
												<!-- <button type="button" class="btn btn-default"> -->
													<i class="glyphicon glyphicon-calendar"></i>
												<!-- </button> -->
											</span>
										<!-- </p> -->
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>
											{{ trans('translate.reschedule_reason') }} *
										</label>
										<textarea name="alasan" class="form-control" placeholder="{{ trans('translate.reschedule_reason') }} ..."></textarea>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div><!-- /.modal-content -->
			<div class="modal-footer">
				<table width=100%>
					<tr>
						<td>
							<button type="submit" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
						</td>
					</tr>
				</table>
			</div>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
</form>
@endsection
 
@section('content_js')
 
<script type="text/javascript" src="{{ asset('template-assets/bootstrap-assets/js/bootstrap.min.js')}}"></script>

<script type="text/javascript" src="{{ asset('template-assets/plugins/owl-carousel/owl.carousel.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/js/jquery.easing.min.js')}}"></script> 
<script type="text/javascript" src="{{ asset('template-assets/plugins/countTo/jquery.countTo.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/plugins/inview/jquery.inview.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/plugins/Lightbox/dist/js/lightbox.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/plugins/WOW/dist/wow.min.js')}}"></script>

  
<script type="text/javascript" src="{{ asset('template-assets/js/jquery.backstretch.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/js/retina-1.1.0.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/js/scripts.js')}}"></script>

<script type="text/javascript" src="{{ asset('template-assets/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/vendor/jquery-smart-wizard/jquery.smartWizard.js')}}"></script>
<script type="text/javascript" src="{{ asset('template-assets/assets/js/form-wizard.js')}}"></script>
<script>
	jQuery(document).ready(function() {
		// Main.init();
		// FormWizard.init();
		$(".rad-jns_perusahaan-edit").change(function(){
			var jns_perusahaan = $('input[name="jns_perusahaan"]:checked').val();
			var jns_pengujian = $('.hide_jns_pengujian_edit').val();
			if(jns_pengujian == 1){					
				if(jns_perusahaan == 'Pabrikan'){
					$(".dv-srt-dukungan-prinsipal").hide();
				}
				else{
					$(".dv-srt-dukungan-prinsipal").show();
				}
			}
		});
	});
</script>
<script type="text/javascript" >
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$('#cmb-jns-pengujian').val('<?php echo $jns; ?>');
	$('#cmb-jns-status').val('<?php echo $status; ?>');
	
	$('.update-permohonan').click(function(){
		if($('#hide_cekSNjnsPengujian_edit').val() > 0){
			return false;
		}
		var form = $('#form-permohonan-edit')[0]; // You need to use standart javascript object here
		var formData = new FormData(form);
	 
		$.ajax({
			type: "POST",
			url : "updatePermohonan",
			// data: {'_token':"{{ csrf_token() }}", 'nama_pemohon':nama_pemohon, 'nama_pemohons':nama_pemohon},
			// data:new FormData($("#form-permohonan")[0]),
			data:formData,
			// dataType:'json',
			async:false,
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";		
			},
			success: function(data){
				// document.getElementById("overlay").style.display="none";
				console.log(data);
				window.open("cetakPermohonan");
			},
			error:function(){
				alert("Gagal mengambil data");
			}
		});
	});
	
	function edit(a,b){
		if(b==1){
			alert("Pengujian sudah teregistrasi!");return false;
		}else{
			$.ajax({
				type: "POST",
				url : "editPengujian",
				data: {'_token':"{{ csrf_token() }}", 'id':a},
				type:'post',
				beforeSend: function(){
					$('#myModaledit').modal('hide');
				},
				success: function(data){
					console.log(data);
					
					var nama_pemohon = document.getElementsByName("f1-nama-pemohon-edit");
					var alamat_pemohon = document.getElementsByName("f1-alamat-pemohon-edit");
					var telepon_pemohon = document.getElementsByName("f1-telepon-pemohon-edit");
					var faksimile_pemohon = document.getElementsByName("f1-faksimile-pemohon-edit");
					var email_pemohon = document.getElementsByName("f1-email-pemohon-edit");
					
					var nama_perusahaan = document.getElementsByName("f1-nama-perusahaan-edit");
					var alamat_perusahaan = document.getElementsByName("f1-alamat-perusahaan-edit");
					var telepon_perusahaan = document.getElementsByName("f1-telepon-perusahaan-edit");
					var faksimile_perusahaan = document.getElementsByName("f1-faksimile-perusahaan-edit");
					var email_perusahaan = document.getElementsByName("f1-email-perusahaan-edit");
					
					var nama_perangkat = document.getElementsByName("f1-nama-perangkat-edit");
					var merek_perangkat = document.getElementsByName("f1-merek-perangkat-edit");
					var kapasitas_perangkat = document.getElementsByName("f1-kapasitas-perangkat-edit");
					var pembuat_perangkat = document.getElementsByName("f1-pembuat-perangkat-edit");
					var serialNumber_perangkat = document.getElementsByName("f1-serialNumber-perangkat-edit");
					var model_perangkat = document.getElementsByName("f1-model-perangkat-edit");
					var referensi_perangkat = document.getElementsByName("f1-referensi-perangkat-edit");
					
					var hide_siupp_file = document.getElementsByName("hide_siupp_file_edit");
					var no_siupp = document.getElementsByName("f1-no-siupp-edit");
					var tgl_siupp = document.getElementsByName("f1-tgl-siupp-edit");
					var hide_sertifikat_file = document.getElementsByName("hide_sertifikat_file_edit");
					var no_sertifikat = document.getElementsByName("f1-sertifikat-sistem-mutu-edit");
					var batas_waktu = document.getElementsByName("f1-batas-waktu-edit");
					var hide_npwp_file = document.getElementsByName("hide_npwp_file_edit");
					var hide_ref_uji_file = document.getElementsByName("hide_ref_uji_file_edit");
					var no_ref_uji = document.getElementsByName("f1-no-surat-ref-uji-edit");
					var tgl_ref_uji = document.getElementsByName("f1-tgl-surat-ref-uji-edit");
					var hide_prinsipal_file = document.getElementsByName("hide_prinsipal_file_edit");
					var no_prinsipal = document.getElementsByName("f1-no-surat-prinsipal-edit");
					var tgl_prinsipal = document.getElementsByName("f1-tgl-surat-prinsipal-edit");
					var hide_sp3_file = document.getElementsByName("hide_sp3_file_edit");
					var no_sp3 = document.getElementsByName("f1-no-surat-sp3-edit");
					var tgl_sp3 = document.getElementsByName("f1-tgl-surat-sp3-edit");
					
					var hide_attachment_file = document.getElementsByName("hide_attachment_file_edit");
					
					str = data.split("|token|");
					$('#hide_id_user').val(str[14]);
					for (var i=0;i<nama_pemohon.length;i++) {nama_pemohon[i].value = str[15];}
					for (var i=0;i<alamat_pemohon.length;i++) {alamat_pemohon[i].value = str[37];}
					for (var i=0;i<telepon_pemohon.length;i++) {telepon_pemohon[i].value = str[38];}
					for (var i=0;i<faksimile_pemohon.length;i++) {faksimile_pemohon[i].value = str[39];}
					for (var i=0;i<email_pemohon.length;i++) {email_pemohon[i].value = str[16];}
					
					for (var i=0;i<nama_perusahaan.length;i++) {nama_perusahaan[i].value = str[17];}
					for (var i=0;i<alamat_perusahaan.length;i++) {alamat_perusahaan[i].value = str[18];}
					for (var i=0;i<telepon_perusahaan.length;i++) {telepon_perusahaan[i].value = str[19];}
					for (var i=0;i<faksimile_perusahaan.length;i++) {faksimile_perusahaan[i].value = str[20];}
					for (var i=0;i<email_perusahaan.length;i++) {email_perusahaan[i].value = str[21];}
					
					for (var i=0;i<nama_perangkat.length;i++) {nama_perangkat[i].value = str[0];}
					$('#hide_name_edit').val(str[0]);
					for (var i=0;i<merek_perangkat.length;i++) {merek_perangkat[i].value = str[1];}
					for (var i=0;i<kapasitas_perangkat.length;i++) {kapasitas_perangkat[i].value = str[2];}
					for (var i=0;i<pembuat_perangkat.length;i++) {pembuat_perangkat[i].value = str[3];}
					for (var i=0;i<serialNumber_perangkat.length;i++) {serialNumber_perangkat[i].value = str[4];}
					$('#hide_serial_number_edit').val(str[4]);
					for (var i=0;i<model_perangkat.length;i++) {model_perangkat[i].value = str[5];}
					$('#hide_model_edit').val(str[5]);
					
					for (var i=0;i<hide_siupp_file.length;i++) {hide_siupp_file[i].value = str[11];}
					$('.siupp-file-edit').html(str[11]);
					$('.f4-preview-1-edit').html(str[11]);
					for (var i=0;i<no_siupp.length;i++) {no_siupp[i].value = str[10];}
					for (var i=0;i<tgl_siupp.length;i++) {tgl_siupp[i].value = str[12];}
						date_format_siupp = str[12].split("-");
						$('#daySIUPP').val(date_format_siupp[0]);
						$('#monthSIUPP').val(date_format_siupp[1]);
						$('#yearSIUPP').val(date_format_siupp[2]);
					for (var i=0;i<hide_sertifikat_file.length;i++) {hide_sertifikat_file[i].value = str[8];}
					$('.sertifikat-file-edit').html(str[8]);
					$('.f4-preview-6-edit').html(str[8]);
					for (var i=0;i<no_sertifikat.length;i++) {no_sertifikat[i].value = str[7];}
					for (var i=0;i<batas_waktu.length;i++) {batas_waktu[i].value = str[9];}
						date_format_serti = str[9].split("-");
						$('#daySerti').val(date_format_serti[0]);
						$('#monthSerti').val(date_format_serti[1]);
						$('#yearSerti').val(date_format_serti[2]);
					for (var i=0;i<hide_npwp_file.length;i++) {hide_npwp_file[i].value = str[13];}
					$('.npwp-file-edit').html(str[13]);
					$('.f4-preview-11-edit').html(str[13]);
					
					for (var i=0;i<hide_attachment_file.length;i++) {hide_attachment_file[i].value = str[25];}
					$('.attachment-file-edit').html(str[25]);
					
					for (var i=0;i<hide_ref_uji_file.length;i++) {hide_ref_uji_file[i].value = str[26];}
					$('.ref-uji-file-edit').html(str[26]);
					$('.f4-preview-file-ref-uji').html(str[26]);
					for (var i=0;i<no_ref_uji.length;i++) {no_ref_uji[i].value = str[27];}
					for (var i=0;i<tgl_ref_uji.length;i++) {tgl_ref_uji[i].value = str[28];}
					
					for (var i=0;i<hide_prinsipal_file.length;i++) {hide_prinsipal_file[i].value = str[29];}
					$('.prinsipal-file-edit').html(str[29]);
					$('.f4-preview-8-edit').html(str[29]);
					for (var i=0;i<no_prinsipal.length;i++) {no_prinsipal[i].value = str[30];}
					for (var i=0;i<tgl_prinsipal.length;i++) {tgl_prinsipal[i].value = str[31];}
					
					for (var i=0;i<hide_sp3_file.length;i++) {hide_sp3_file[i].value = str[32];}
					$('.sp3-file-edit').html(str[32]);
					$('.f4-preview-file-sp3-edit').html(str[32]);
					for (var i=0;i<no_sp3.length;i++) {no_sp3[i].value = str[33];}
					for (var i=0;i<tgl_sp3.length;i++) {tgl_sp3[i].value = str[34];}
					
					$('#f1-preview-1').html(str[15]);
					$('#f1-preview-2').html(str[37]);
					$('#f1-preview-3').html(str[38]);
					$('#f1-preview-4').html(str[39]);
					$('#f1-preview-5').html(str[16]);
					$('#f2-preview-1').html(str[17]);
					$('#f2-preview-2').html(str[18]);
					$('#f2-preview-3').html(str[19]);
					$('#f2-preview-4').html(str[20]);
					$('#f2-preview-5').html(str[21]);
					$('#hide_device_id_edit').val(str[22]);
					$('.hide_jns_pengujian_edit').val(str[23]);
						if(str[23]==1){
							$(".dv-srt-dukungan-prinsipal").show();
							$(".cmb-ref-perangkat").show();
							$("#f1-cmb-ref-perangkat").val(''+str[6]+'');
							$(".txt-ref-perangkat").hide();
							$("#f1-jns-referensi-perangkat").val(1);
							$(".dv-srt-sp3").hide();
							// $('.f5-jns-pengujian-edit').html('Jenis Pengujian : QA');
							var divs = document.getElementsByClassName('f5-jns-pengujian-edit');
							for (var i = 0; i < divs.length; i++) {
								divs[i].innerHTML += " QA";
							}
						}else if(str[23]==2){
							$(".dv-srt-dukungan-prinsipal").hide();
							$(".cmb-ref-perangkat").hide();
							$(".txt-ref-perangkat").show();
							for (var i=0;i<referensi_perangkat.length;i++) {referensi_perangkat[i].value = str[6];}
							$("#f1-jns-referensi-perangkat").val(0);
							$(".dv-srt-sp3").show();
							// $('.f5-jns-pengujian-edit').html('Jenis Pengujian : TA');
							var divs = document.getElementsByClassName('f5-jns-pengujian-edit');
							for (var i = 0; i < divs.length; i++) {
								divs[i].innerHTML += " TA";
							}
						}else if(str[23]==3){
							$(".dv-srt-dukungan-prinsipal").hide();
							$(".cmb-ref-perangkat").hide();
							$(".txt-ref-perangkat").show();
							for (var i=0;i<referensi_perangkat.length;i++) {referensi_perangkat[i].value = str[6];}
							$("#f1-jns-referensi-perangkat").val(0);
							$(".dv-srt-sp3").hide();
							// $('.f5-jns-pengujian-edit').html('Jenis Pengujian : VT');
							var divs = document.getElementsByClassName('f5-jns-pengujian-edit');
							for (var i = 0; i < divs.length; i++) {
								divs[i].innerHTML += " VT";
							}
						}else{
							$(".dv-srt-dukungan-prinsipal").hide();
							$(".cmb-ref-perangkat").hide();
							$(".txt-ref-perangkat").show();
							for (var i=0;i<referensi_perangkat.length;i++) {referensi_perangkat[i].value = str[6];}
							$("#f1-jns-referensi-perangkat").val(0);
							$(".dv-srt-sp3").hide();
							// $('.f5-jns-pengujian-edit').html('Jenis Pengujian : CAL');
							var divs = document.getElementsByClassName('f5-jns-pengujian-edit');
							for (var i = 0; i < divs.length; i++) {
								divs[i].innerHTML += " CAL";
							}
						}
					$('input[name="jns_perusahaan"][value="' + str[24] + '"]').prop('checked', true);
					if(str[23] == 1){					
						if(str[24] == 'Pabrikan'){
							$(".dv-srt-dukungan-prinsipal").hide();
						}
						else{
							$(".dv-srt-dukungan-prinsipal").show();
						}
					}
					$('#hide_exam_id_edit').val(str[35]);
					$('#hide_company_id').val(str[36]);
					
					$('#myModaledit').modal('show');
				},
				error:function(){
					alert("Gagal mengambil data");
					$('#myModaledit').modal('hide');
				}
			});
		}
	}
	
	$('.cek-SN-jnsPengujian').click(function(){
		var jnsPelanggan = $('.hide_jns_pengujian_edit').val();
		var true_serialNumber_perangkat = $('#hide_serial_number_edit').val();
		var true_nama_perangkat = $('#hide_name_edit').val();
		var true_model_perangkat = $('#hide_model_edit').val();
		var serialNumber_perangkat = $('#f1-serialNumber-perangkat').val();
		var nama_perangkat = $('#f1-nama-perangkat').val();
		var model_perangkat = $('#f1-model-perangkat').val();
		if((true_nama_perangkat != nama_perangkat) && (true_model_perangkat != model_perangkat)){
		// if(true_serialNumber_perangkat != serialNumber_perangkat){
			//var serialNumber_perangkat = document.getElementsByName("f1-serialNumber-perangkat-edit");
			$.ajax({
				type: "POST",
				url : "cekPermohonan",
				data: {'_token':"{{ csrf_token() }}", 'jnsPelanggan':jnsPelanggan, 'serialNumber_perangkat':serialNumber_perangkat, 'nama_perangkat':nama_perangkat, 'model_perangkat':model_perangkat},
				// dataType:'json',
				type:'post',
				success: function(data){
					console.log(data);
					$('#hide_cekSNjnsPengujian_edit').val(data);
				}
			});
		}else{
			$('#hide_cekSNjnsPengujian_edit').val('0');
		}
	});
	
	
	$("#siupp-file").click(function() {
		var file = $('#hide_siupp_file').val();
		downloadFileCompany(file);
	});
	
	$("#sertifikat-file").click(function() {
		var file = $('#hide_sertifikat_file').val();
		downloadFileCompany(file);
	});
	
	$("#npwp-file").click(function() {
		var file = $('#hide_npwp_file').val();
		downloadFileCompany(file);
	});
	
	$("#ref-uji-file").click(function() {
		var file = $('#hide_ref_uji_file').val();
		downloadFile(file);
	});
	
	$("#prinsipal-file").click(function() {
		var file = $('#hide_prinsipal_file').val();
		downloadFile(file);
	});
	
	$("#sp3-file").click(function() {
		var file = $('#hide_sp3_file').val();
		downloadFile(file);
	});
	
	$("#attachment-file").click(function() {
		var file = $('#hide_attachment_file').val();
		downloadFile(file);
	});
	
	function downloadFile(file){
		var path = "{{ URL::asset('media/examination') }}";
		var id_exam = $('#hide_exam_id_edit').val();
		//Get file name from url.
		var url = path+'/'+id_exam+'/'+file;
		var filename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
		var xhr = new XMLHttpRequest();
		xhr.responseType = 'blob';
		xhr.onload = function() {
			if (this.status === 404) {
			   // not found, add some error handling
			   alert("File Tidak Ada!");
			   return false;
			}
			var a = document.createElement('a');
			a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
			a.download = filename; // Set the file name.
			a.style.display = 'none';
			document.body.appendChild(a);
			a.click();
			delete a;
		};
		xhr.open('GET', url);
		xhr.send();
	}
	
	function downloadFileCompany(file){
		var path = "{{ URL::asset('media/company') }}";
		var company_id = $('#hide_company_id').val();
		//Get file name from url.
		var url = path+'/'+company_id+'/'+file;
		var filename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
		var xhr = new XMLHttpRequest();
		xhr.responseType = 'blob';
		xhr.onload = function() {
			if (this.status === 404) {
			   // not found, add some error handling
			   alert("File Tidak Ada!");
			   return false;
			}
			var a = document.createElement('a');
			a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
			a.download = filename; // Set the file name.
			a.style.display = 'none';
			document.body.appendChild(a);
			a.click();
			delete a;
		};
		xhr.open('GET', url);
		xhr.send();
	}
	
	$('.upload-form').click(function(){
		$.ajax({
			url : "uploadPermohonanEdit",
			data:new FormData($("#form-permohonan-edit")[0]),
			// dataType:'json',
			async:false,
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				// document.getElementById("overlay").style.display="none";
				console.log(response);
			}
		});
	});

	$('.datepicker').datepicker({
      dateFormat: 'yy-mm-dd', 
      autoclose: true,
      numberOfMonths: 2 ,
      showButtonPanel: true,
       beforeShowDay: $.datepicker.noWeekends,

  });
	function reSchedule(a,b,c,d,e){
		if(c==1){
			$('#reschedule-modal-content').modal('show');
			$('#hide_id_exam').val(a);
			$('#hide_date_type').val(c);
			$('#reschedule-modal-content').on('shown.bs.modal', function() {
				$('#cust_test_date').val(b);
				$("#cust_test_date").focus();
			});
		}else if(c==2){
			$('#reschedule-modal-content2').modal('show');
			$('#hide_id_exam2').val(a);
			$('#hide_date_type2').val(c);
			$('#reschedule-modal-content2').on('shown.bs.modal', function() {
				$('#cust_test_date2').val(b);
				$('#deal_test_date2').val(d);
				$('#urel_test_date2').val(e);
				$("#urel_test_date2").focus();
			});
		}
	}
	$('.btn-submit').click(function(){
		var APP_URL = {!! json_encode(url('/pengujian')) !!};
		location.reload(APP_URL);
		// $('#myModal').modal('hide');
		// document.getElementById("form-permohonan").reset();
	});
</script>
<script type="text/javascript" src="{{ asset('assets/js/search/pengujian.js')}}"></script>
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
<!-- <script src={{ asset("assets/js/chosen.jquery.min.js") }}></script> -->
<script type="text/javascript">
	$('#myModaledit').on('shown.bs.modal', function () 
	{
		$('#f1-cmb-ref-perangkat').chosen();
		// $('#f1-cmb-ref-perangkat').trigger("chosen:updated");
		$('#f1_cmb_ref_perangkat_chosen').css({
			"width": "100%"
		});
		$('#f1_cmb_ref_perangkat_chosen .chosen-single span').css({
			"color": "black"
		});
	}); 
	
	function isTestimonial(a,b,c,d,e){
		var link = document.getElementById('link');
			// link.value = '/pengujian/download/'+a+'/'+b+'/'+c;
			link.value = b;
		
		$.ajax({
			type: "POST",
			url : "checkKuisioner",
			data: {'_token':"{{ csrf_token() }}", 'id':e},
			type:'post',
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				// document.getElementById("overlay").style.display="none";
				console.log(response);
				if(response=='0'){
					$('#modal_kuisioner2').modal('show');
					$('#modal_kuisioner2').on('shown.bs.modal', function() {
						$("#exam_type").val(d);
						$("#exam_id").val(e);
						$("#tanggal").focus();
					});
				}else if(response=='' || response==undefined || response==undefined){
					$("#my_exam_id").val(e);
					$('#modal_kuisioner2').modal('hide');
					$('#modal_complain').modal('show');	
					$('#modal_complain').on('shown.bs.modal', function() {
						$("#tanggal_complaint").focus();
					});	
				}else{
					checkAmbilBarang(e);
				}
			}
		});
	}
	
	$('#submit-kuisioner').click(function () {
		$("#my_exam_id").val(document.getElementById('exam_id').value);
		$.ajax({
			url : "insertKuisioner",
			data:new FormData($("#form-kuisioner")[0]),
			// dataType:'json',
			async:false,
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				// document.getElementById("overlay").style.display="none";
				console.log(response);
				// if(response==1){
					$('#modal_kuisioner2').modal('hide');
					$('#modal_complain').modal('show');	
					$('#modal_complain').on('shown.bs.modal', function() {
						$("#tanggal_complaint").focus();
					});					
				// }
			}
		});
	});

	$('.submit-complain').click(function () {
		$.ajax({
			url : "insertComplaint",
			data:new FormData($("#form-complain")[0]),
			// dataType:'json',
			async:false,
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				// document.getElementById("overlay").style.display="none";
				console.log(response);
				checkAmbilBarang(document.getElementById('my_exam_id').value);
			}
		});
	});
	
	function checkAmbilBarang(a){
		var link = document.getElementById('link').value;
		$.ajax({
			type: "POST",
			url : "cekAmbilBarang",
			data: {'_token':"{{ csrf_token() }}", 'my_exam_id':a},
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				// document.getElementById("overlay").style.display="none";
				console.log(response);
				$('#modal_complain').modal('hide');
				if(response==1){
					// window.location.href = '/telkomdds/public'+link;
					window.open(link);
				}else{
					$('#modal_status_barang').modal('show');
				}
			}
		});
	}
</script>

@endsection