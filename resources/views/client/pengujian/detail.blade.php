@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.examination_detail') }} - Telkom DDS</title>
	<div id="modal_kuisioner" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
            <h4 class="modal-title">Survey Kepuasan Kastamer Eksternal</h4>
          </div>
          <div class="modal-body pre-scrollable">
            <form>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <input type="hidden" name="exam_id" name="exam_id">
                            <label>Tanggal</label>
                            <input type="text" name="tanggal" placeholder="DD/MM/YYYY" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" id="user_name" name="user_name" placeholder="John Doe" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <input type="text" id="company_name" name="company_name" placeholder="PT. ABCD" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" id="company_phone" name="company_phone" placeholder="0812345678" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Jenis Pengujian</label>
                            <input type="text" id="exam_type" name="exam_type" placeholder="Nama Pengujian" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" id="user_email" name="user_email" placeholder="user@mail.com" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" id="user_phone" name="user_phone" placeholder="0812345678" class="form-control" readonly>
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
                        <td><input type="number" min="1" max="7" name="quest1_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest1_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>Proses pelayanan pengujian secara keseluruhan (sejak pengajuan hingga pelaporan) mudah dimengerti oleh Kastemer.</td> 
                        <td><input type="number" min="1" max="7" name="quest2_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest2_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>Pihak UREL memberika informasi serta melakukan pengecekan kelengkapan mengenai berkas-berkas yang harus disiapkan.</td> 
                        <td><input type="number" min="1" max="7" name="quest3_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest3_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>4</td>
                        <td>Setiap lini proses (sejak pengajuan hingga pelaporan) dilakukan dengan cepat.</td> 
                        <td><input type="number" min="1" max="7" name="quest4_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest4_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>5</td>
                        <td>Pihak UREL memberikan informasi yang dibutuhkan oleh Kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest5_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest5_perf" class="form-control" placeholder="1-7" required></td>
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
                        <td><input type="number" min="1" max="7" name="quest7_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest7_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>8</td>
                        <td>Kastemer merasa pihak UREL faham dan terpercaya.</td> 
                        <td><input type="number" min="1" max="7" name="quest8_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest8_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>9</td>
                        <td>Kastemer merasa pihak UREL sudah melakukan pemeriksaan kelengkapan administrasi dengan kinerja yang baik.</td> 
                        <td><input type="number" min="1" max="7" name="quest9_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest9_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>10</td>
                        <td>Kastemer measa aman sewaktu melakukan transaksi dengan pihak Telkom terutama pihak UREL.</td> 
                        <td><input type="number" min="1" max="7" name="quest10_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest10_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>11</td>
                        <td>Kastemer merasa Engineer Telkom sudah berpengalaman.</td> 
                        <td><input type="number" min="1" max="7" name="quest11_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest11_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>12</td>
                        <td>Alat ukur yang digunakan oleh pihak Telkom berkualitas, baik, dan akurat.</td> 
                        <td><input type="number" min="1" max="7" name="quest12_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest12_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>13</td>
                        <td>Laboratorium yang digunakan oleh pihak Telkom dalam keadaan bersih dan memenuhi Standar Laboratorium.</td> 
                        <td><input type="number" min="1" max="7" name="quest13_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest13_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>14</td>
                        <td>Tarif Pengujian yang ditetapkan oleh pihak PT. Telkom sesuai dan bersaing dengan harga pasar.</td> 
                        <td><input type="number" min="1" max="7" name="quest14_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest14_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>15</td>
                        <td>Pihak UREL yang melayani kastamer berpakaian rapih dan sopan.</td> 
                        <td><input type="number" min="1" max="7" name="quest15_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest15_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>16</td>
                        <td>Kontor Telkom DDS dalam kondisi nyaman, bersih dan sudah sesuai kondisi keseluruhannya.</td> 
                        <td><input type="number" min="1" max="7" name="quest16_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest16_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>17</td>
                        <td>Pihak Telkom mengembalikan barang/perangkat yang diujikan dalam keadaan baik seperti awal.</td> 
                        <td><input type="number" min="1" max="7" name="quest17_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest17_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>18</td>
                        <td>Sertifikat yang diterima oleh kastemer tidak mengalami kesalahan informasi.</td> 
                        <td><input type="number" min="1" max="7" name="quest18_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest18_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>19</td>
                        <td>Pihak Telkom DDS terutama pihak UREL yang melayani proses pengajuan hingga pelaporan sudah memahami kebutuhan kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest19_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest19_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>20</td>
                        <td>Proses pengujian secara keseluruhan tidak memakan durasi waktu yang lama.</td> 
                        <td><input type="number" min="1" max="7" name="quest20_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest20_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>21</td>
                        <td>Pihak UREL cepat dan tepat dalam merespon keluhan yang diberikan kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest21_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest21_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>22</td>
                        <td>Pihak UREL tanggapan dalam membantu permasalahan kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest22_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest22_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>23</td>
                        <td>Engineer tanggapan pada permasalahan yang dihadapi kastamer selama proses pengajuan hingga pelaporan.</td> 
                        <td><input type="number" min="1" max="7" name="quest23_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest23_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>24</td>
                        <td>Pihak UREL mudah dihubungi dan tanggap pada segala pertanyaan yang diajukan kastamer terkait pengujian perangkat.</td> 
                        <td><input type="number" min="1" max="7" name="quest24_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest24_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                      <tr>
                        <td>25</td>
                        <td>Pihak UREL bersikap ramah dan profesional terhadap kastamer.</td> 
                        <td><input type="number" min="1" max="7" name="quest25_eks" class="form-control" placeholder="1-7" required></td>
                        <td><input type="number" min="1" max="7" name="quest25_perf" class="form-control" placeholder="1-7" required></td>
                      </tr>
                    </table>
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
                <form>
                    <table id="table_kuisioner" style="width:100%; padding: 2px;" border="1">
                        <tr>
                            <th colspan="2">No</th>
                            <td colspan="2"><input type="text" name="no" class="form-control"></td>
                        </tr>
                        <tr>
                            <th>Sheet</th>
                            <td><input type="text" name="no" class="form-control"></td>
                            <th>of</th>
                            <td><input type="text" name="no" class="form-control"></td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <label>Customer Name and Address</label>
                                <textarea class="form-control" placeholder="John Doe/ Bandung"></textarea>
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
                                <input type="text" name="no" class="form-control" placeholder="0812345678">
                            </th>
                            <td colspan="2">
                                <label>Date</label>
                                <input type="text" name="no" class="form-control" placeholder="DD/MM/YYYY">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <label>Customer Complaint</label>
                                <textarea class="form-control" placeholder="Your Complaint"></textarea>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <label>Name of Recipient</label>
                                <input type="text" name="no" class="form-control" placeholder="John Doe">
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <label>Corrective Action Taken</label>
                                <textarea class="form-control" placeholder="Your Complaint"></textarea>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <label>Completed Date</label>
                                <input type="text" name="no" class="form-control" placeholder="DD/MM/YYYY">
                            </th>
                            <td colspan="2">
                                <label>CPAR No</label>
                                <input type="text" name="no" class="form-control" placeholder="12356">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <label>Name of Actiones</label>
                                <input type="text" name="no" class="form-control" placeholder="NAme">
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <label>USer Relation Manager Signature</label>
                                <input type="text" name="no" class="form-control" placeholder="John Doe">
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
            <button type="button" id="submit-complain" class="button button3d btn-sky">Simpan</button>
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
	
@section('content')
 	<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>Detail {{ trans('translate.examination') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="{{ url('/') }}">{{ trans('translate.home') }}</a></li>
					<li>{{ trans('translate.menu_testing') }}</li>
					<li><a href="{{ url('/pengujian') }}"></a>{{ trans('translate.examination') }}</li>
					<li class="active">Detail</li>
				</ol>
			</div>

		</section><!-- #page-title end -->



	<input type="hidden" name="exam_id" id="exam_id" value="<?php echo $data[0]->id ?>">
	<input type="hidden" name="link" id="link">
	<div class="modal fade" id="myModal_testimonial" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="fa fa-eyes-open"></i> {{ trans('translate.download_certificate_message') }} </h4>
				</div>
				
				<div class="modal-body">
					<table width=100%>
						<tr>
							<td>
								<div class="form-group">
									<label for="message">{{ trans('translate.contact_message') }}:</label>
									<textarea class="form-control" rows="5" name="message" id="message"></textarea>
								</div>
							</td>
						</tr>
					</table>
				</div><!-- /.modal-content -->
				<div class="modal-footer">
					<table width=100%>
						<tr>
							<td>
								<button type="button" id="submit-testimonial" class="btn btn-danger" style="width:100%"><i class="fa fa-check-square-o"></i> Submit</button>
							</td>
						</tr>
					</table>
				</div>
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
		
		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap"> 
				<div class="container clearfix">
					<div class="container-fluid container-fullw bg-white">
						@if(count($exam_schedule->data)>0)
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-white" id="panel1">
									<div class="panel-body">
										<div class="col-md-12">
										<!-- start: WIZARD FORM -->
											<div id="wizard" class="swMain">
												<div class="form-group">
													<table class="table table-condensed">
														<thead>
															<tr>
																<th colspan="2">{{ trans('translate.examination_date') }}</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="center">{{ trans('translate.examination_date_begin') }} : {{ $exam_schedule->data[0]->testing_type }}</td>
																<td class="center">{{ trans('translate.examination_date_end') }} : {{ $exam_schedule->data[0]->testing_type }}</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										<!-- end: WIZARD FORM -->
										</div>
									</div>
								</div>
							</div>
						</div>
						@endif
						 <!-- start: WIZARD FORM -->
						<?php $no=1; //print_r($data);exit;
						//foreach($data as $item){
						?>
						<form action="#" role="form" class="smart-wizard" id="form">
							{!! csrf_field() !!}
							<div id="wizard" class="swMain">
							@foreach($data as $item)
								<div class="form-group">
									<table class="table table-condensed">
										<thead>
											<tr>
												<th colspan="3">{{ trans('translate.service_application') }}</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{{ trans('translate.service_application_name') }}</td>
												<td> : {{ $item->namaPemohon }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_address') }}</td>
												<td> : {{ $item->alamatPemohon }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_phone') }}</td>
												<td> : {{ $item->telpPemohon }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_fax') }}</td>
												<td> : {{ $item->faxPemohon }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_application_email') }}</td>
												<td> : {{ $item->emailPemohon }}</td>
											</tr>
										</tbody>
										<thead>
											<tr>
												<th colspan="3">{{ trans('translate.service_company') }} [{{ $item->jnsPerusahaan }}]</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{{ trans('translate.service_company_name') }}</td>
												<td> : {{ $item->namaPerusahaan }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_address') }}</td>
												<td> : {{ $item->alamatPerusahaan }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_phone') }}</td>
												<td> : {{ $item->telpPerusahaan }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_fax') }}</td>
												<td> : {{ $item->faxPerusahaan }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_email') }}</td>
												<td> : {{ $item->emailPerusahaan }}</td>
											</tr>
										</tbody>
										<thead>
											<tr>
												<th colspan="3">{{ trans('translate.service_device') }}</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>{{ trans('translate.service_device_serial_number') }}</td>
												<td> : {{ $item->serialNumber }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_equipment') }}</td>
												<td> : {{ $item->nama_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_mark') }}</td>
												<td> : {{ $item->merk_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_capacity') }}</td>
												<td> : {{ $item->kapasitas_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_manufactured_by') }}</td>
												<td> : {{ $item->pembuat_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_model') }}</td>
												<td> : {{ $item->model_perangkat }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_device_test_reference') }}</td>
												<td> : {{ $item->referensi_perangkat }}</td>
											</tr>
										</tbody>
										<thead>
											<tr>
												<th colspan="3">{{ trans('translate.service_device_document') }}</th>
											</tr>
										</thead>
										<tbody>
											@foreach($data_attach as $item_attach)
											<tr>
												<td> 
													@if($item_attach->name == 'Sertifikat' && $item_attach->attachment != '')
														<a class="btn btn-link" href="javascript:void(0)" style="color:black !important;" onclick="return isTestimonial('{{ $item_attach->id_attach }}','{{ $item_attach->attachment }}','{{ $item_attach->jns }}');">{{ $item_attach->name }} </a>
													@elseif($item_attach->name == 'Laporan Uji' && $item_attach->attachment != '')
														<a class="btn btn-link" href="#" style="color:black !important;">{{ $item_attach->name }} </a>
													@else	
														<a class="btn btn-link" href="{{URL::to('/pengujian/download/'.$item_attach->id_attach.'/'.$item_attach->attachment.'/'.$item_attach->jns)}}" style="color:black !important;">{{ $item_attach->name }} </a>
													@endif
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="row">
									<div class=" pull-right col-xs-12">
										<a class="button button-3d btn-sky nomargin" href="{{url('/pengujian')}}">{{ trans('translate.back') }}</a>
										<a class="button button-3d btn-sky nomargin pull-right" style="margin-bottom:10px;" 
											href="{{URL::to('cetakPengujian/'.$item->id.'')}}" target="_blank">
											{{ trans('translate.examination_print') }}
										</a>
									</div>
								</div>										
							</div>
							<?php 
							$no++;
							//}
							?>
							@endforeach
						</form>
						<!-- end: WIZARD FORM -->
						<div id="wizard" class="swMain">
							<div class="form-group">
								<table class="table table-condensed">
									<thead>
										<tr>
											<th colspan="4">{{ trans('translate.examination_history') }}</th>
										</tr>
										<tr>
											<td class="center">{{ trans('translate.examination_history_step') }}</td>
											<td class="center">{{ trans('translate.examination_history_status') }}</td>
											<td class="center">{{ trans('translate.examination_history_notes') }}</td>
											<td class="center">{{ trans('translate.examination_history_date') }}</td>
										</tr>
									</thead>
									<tbody>
									@foreach($exam_history as $item)
										<tr>
											<td class="center">{{ $item->tahap }}</td>
											@if($item->status == 1)
												<td class="center">Completed</td>
											@else
												<td class="center">Not Completed</td>
											@endif
											<td>{{ $item->keterangan }}</td>
											<td class="center">{{ $item->date_action }}</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section><!-- #content end -->
@endsection

@section('content_js')
<script type="text/javascript">
	// jQuery(document).ready(function() {
		// Main.init();
		// FormWizard.init();
	// });
	
	// $.ajaxSetup({
	// 	headers: {
	// 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 	}
	// });
	function isTestimonial(a,b,c){
		var link = document.getElementById('link');
			link.value = '/pengujian/download/'+a+'/'+b+'/'+c;
		// var message = document.getElementById('message');
		$('#modal_kuisioner').modal('show');
		// message.focus();
	}
	
	$('#submit-kuisioner').click(function () {
		$('#modal_kuisioner').modal('hide');
		$('#modal_complain').modal('show');
	});

	$('#submit-complain').click(function () {
		var link = document.getElementById('link').value;
		var exam_id = document.getElementById('exam_id').value;
		$('#modal_complain').modal('hide');
		$.ajax({
			type: "POST",
			url : "{{URL::to('/cekAmbilBarang')}}",
			data: {'_token':"{{ csrf_token() }}", 'exam_id':exam_id},
			// data:new FormData($("#form-permohonan")[0]),
			// data:formData,
			dataType:'json',
			// async:false,
			// type:'post',
			// processData: false,
			// contentType: false,
			beforeSend: function(){
				// document.getElementById("overlay").style.display="inherit";		
			},
			success: function(response){
				// return false;
				// document.getElementById("overlay").style.display="none";
				$('#modal_complain').modal('hide');
				if(response==0){
					$('#modal_status_barang').modal('show');
				}else{
					window.location.href = '/telkomdds/public'+link;
				}
			},
			error:function(){
				alert("Gagal mengambil data");
			}
		});
	});

	
	$('#submit-testimonial').click(function () {
		var link = document.getElementById('link').value;
		// var message = document.getElementById('message').value;
		var exam_id = document.getElementById('exam_id').value;
		window.location.href = '/telkomtesthouse/public'+link;
		/* if(message == ''){
			$('#modal_kuisioner').modal('show');
			return false;
		}else{
			$.ajax({
				type: "POST",
				url : "{{URL::to('/testimonial')}}",
				data: {'_token':"{{ csrf_token() }}", 'message':message, 'exam_id':exam_id},
				// data:new FormData($("#form-permohonan")[0]),
				// data:formData,
				dataType:'json',
				// async:false,
				// type:'post',
				// processData: false,
				// contentType: false,
				beforeSend: function(){
					// document.getElementById("overlay").style.display="inherit";		
				},
				success: function(response){
					// return false;
					// document.getElementById("overlay").style.display="none";
					$('#myModal_testimonial').modal('hide');
					window.location.href = '/telkomtesthouse/public'+link;
				},
				error:function(){
					alert("Gagal mengambil data");
				}
			});
		} */
	});
</script>
@endsection