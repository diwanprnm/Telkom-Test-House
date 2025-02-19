@php
	$currentUser = Auth::user();
	$date_dmY = date('d-m-Y');
@endphp
@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.examination_detail') }} - Telkom Test House</title>
	
@section('content')
<style type="text/css">
	.radio-toolbar input[type="radio"] {
	  opacity: 0;
	  position: fixed;
	  width: 0;
	}
	.radio-toolbar label {
	    display: inline-block;
	    background-color: #ddd;
	    padding: 6%;
	    font-family: sans-serif, Arial;
	    border-radius: 100%;
	}
	.radio-toolbar input[type="radio"]:checked + label {
	    background-color:#bfb;
	    border-color: #4c4;
	}
	.radio-toolbar input[type="radio"]:focus + label {
	    border: 2px dashed #444;
	}
	.radio-toolbar label:hover {
	  background-color: #dfd;
	}
</style>
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



	<input type="hidden" name="exam_id" id="exam_id" value="@php echo $data[0]->id @endphp">
	<input type="hidden" name="link" id="link">
	<div class="modal fade" id="myModal_testimonial" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><em class="fa fa-eyes-open"></em> {{ trans('translate.download_certificate_message') }} </h4>
				</div>
				
				<div class="modal-body">
					<table style="width: 100%;">
						<caption></caption>
						<tr>
							<th scope="col">
								<div class="form-group">
									<label for="message">{{ trans('translate.contact_message') }}:</label>
									<textarea class="form-control" rows="5" name="message" id="message"></textarea>
								</div>
							</th>
						</tr>
					</table>
				</div><!-- /.modal-content -->
				<div class="modal-footer">
					<table style="width: 100%;">
						<caption></caption>
						<tr>
							<th scope="col">
								<button type="button" id="submit-testimonial" class="btn btn-danger" style="width:100%"><em class="fa fa-check-square-o"></em> Submit</button>
							</th>
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
						@if($exam_schedule->code != 'MSTD0059AERR' && $exam_schedule->code != 'MSTD0000AERR')
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-white" id="panel1">
									<div class="panel-body">
										<div class="col-md-12">
										<!-- start: WIZARD FORM -->
											<div id="wizard" class="swMain">
												<div class="form-group">
													<table class="table table-condensed">
														<caption></caption>
														<thead>
															<tr>
																<th colspan="2" scope="colgroup">{{ trans('translate.examination_date') }}</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="center">{{ trans('translate.examination_date_begin') }} : {{ $exam_schedule->data[0]->startTestDt }}</td>
																<td class="center">{{ trans('translate.examination_date_end') }} : {{ $exam_schedule->data[0]->targetDt }}</td>
															</tr>
															<tr>
																<td class="left">{{ trans('translate.examination_spk_code') }} : {{ $data[0]->spk_code }}</td>
																<td class="left">Test Engineer : {{ $data[0]->function_test_PIC }}</td>
															</tr>
															<tr>
																<td class="left">{{ trans('translate.examination_labs_name') }} : {{ $data[0]->labs_name }}</td>
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
						@else
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-white" id="panel1">
									<div class="panel-body">
										<div class="col-md-12">
										<!-- start: WIZARD FORM -->
											<div id="wizard" class="swMain">
												<div class="form-group">
													<table class="table table-condensed">
														<caption></caption>
														<thead class="hidden">
															<tr>
																<th scope="col">-</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="left">{{ trans('translate.examination_spk_code') }} : {{ $data[0]->spk_code }}</td>
																<td class="left">Test Engineer : {{ $data[0]->function_test_PIC }}</td>
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
						@php $no=1;@endphp
						<form action="#" role="form" class="smart-wizard" id="form">
							{!! csrf_field() !!}
							<div id="wizard" class="swMain">
							@foreach($data as $item)
								<div class="form-group">
									<table class="table table-condensed">
										<caption></caption>
										<thead>
											<tr>
												<th colspan="3" scope="colgroup">{{ trans('translate.service_application') }}</th>
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
												<th colspan="3" scope="colgroup">{{ trans('translate.service_company') }} [{{ $item->jnsPerusahaan }}]</th>
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
											@if($item->id_jns_pengujian == 2)
											<tr>
												<td>{{ trans('translate.service_company_plg_id') }}</td>
												<td> : {{ $item->plg_idPerusahaan }}</td>
											</tr>
											<tr>
												<td>{{ trans('translate.service_company_nib') }}</td>
												<td> : {{ $item->nibPerusahaan }}</td>
											</tr>
											@endif
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
												<th colspan="3" scope="colgroup">{{ trans('translate.service_device') }}</th>
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
												<td>{{ trans('translate.service_device_model') }}</td>
												<td> : {{ $item->model_perangkat }}</td>
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
												<td>{{ trans('translate.service_device_test_reference') }}</td>
												<td> : {{ $item->referensi_perangkat }}</td>
											</tr>
										</tbody>
										<thead>
											<tr>
												<th colspan="3" scope="colgroup">{{ trans('translate.service_device_document') }}</th>
											</tr>
										</thead>
										<tbody>
											@if(
												$item->registration_status == 1 &&
												$item->function_status == 1 &&
												$item->contract_status == 1 &&
												$item->spb_status == 1 &&
												$item->payment_status == 1 &&
												$item->spk_status == 1 &&
												$item->examination_status == 1 &&
												$item->resume_status == 1 &&
												date('Y-m-d') >= $item->resume_date
											)
											<tr>
												<td>
													<a class="btn btn-link" href="javascript:void(0)" onclick="return isTestimonial('{{ $item->nama_perangkat }}','{{ URL::to('pengujian/'.$item->id.'/downloadLaporanPengujian') }}','{{ $item->id }}', '{{$item->jns_pengujian}} ({{$item->desc_pengujian}})','{{ $item->id }}');">Laporan Uji </a>
												</td>
											</tr>
											@endif
											@if(
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
											)
											<tr>
												<td> 
													<a class="btn btn-link" href="javascript:void(0)" onclick="return isTestimonial('{{ $item->nama_perangkat }}','{{ URL::to('pengujian/'.$item->id.'/downloadSertifikat') }}','{{ $item->id }}', '{{$item->jns_pengujian}} ({{$item->desc_pengujian}})','{{ $item->id }}');">Sertifikat </a>
												</td> 
											</tr>
											@endif
											@foreach($data_attach as $item_attach)
												@if($item_attach->attachment != '' && $item_attach->name != 'Laporan Uji' && $item_attach->name != 'Revisi Laporan Uji' && $item_attach->name != 'Sertifikat' && $item_attach->name != 'Revisi Sertifikat')
												<tr>
													<td> 
														<a class="btn btn-link" href="{{URL::to('/pengujian/download/'.$item_attach->id_attach.'/'.$item_attach->attachment.'/'.$item_attach->jns)}}">{{ $item_attach->name }} </a>
													</td>
												</tr>
												@endif
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
							@php 
							$no++;
							@endphp
							@endforeach
						</form>
						<!-- end: WIZARD FORM -->
						<div id="wizard" class="swMain">
							<div class="form-group">
								<table class="table table-condensed">
									<caption></caption>
									<thead>
										<tr>
											<th colspan="4" scope="colgroup">{{ trans('translate.examination_history') }}</th>
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
											<td>{{ $item->keterangan }} {{ trans('translate.by') }} {{ $item->user->name }}</td>
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
		
		<div id="modal_kuisioner" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Survey Kepuasan Kastamer Eksternal</h4>
          </div>
          <div class="modal-body pre-scrollable">
            <form id="form-kuisioner1">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label>Tanggal</label>
								<input type="text" id="tanggal" name="tanggal" placeholder="DD/MM/YYYY" class="form-control" value="@php echo $date_dmY;@endphp" readonly required>
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
                            <input type="text" id="exam_type1" name="exam_type" placeholder="-" class="form-control" readonly>
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
					<table id="table_kuisioner" style="width:100%; padding: 2px; border: 1px">
					  <caption></caption>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kriteria</th> 
                        <th scope="col">Nilai Ekspetasi</th>
                        <th scope="col">NIlai Performasi</th>
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
					<table id="table_kuisioner" style="width:100%; padding: 2px; border: 1px" border="1">
					  <caption></caption>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kriteria</th> 
                        <th scope="col">Nilai Ekspetasi</th>
                        <th scope="col">NIlai Performasi</th>
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
                        <td>Kontor Telkom Test House dalam kondisi nyaman, bersih dan sudah sesuai kondisi keseluruhannya.</td> 
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
                        <td>Pihak Telkom Test House terutama pihak UREL yang melayani proses pengajuan hingga pelaporan sudah memahami kebutuhan kastamer.</td> 
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
                            <input type="text" id="device_name" placeholder="Smartphone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" id="tanggal" name="tanggal" placeholder="DD/MM/YYYY" class="form-control" value="@php echo $date_dmY;@endphp" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <p>Survey ini terdiri dari dua bagian, yaitu tingkat kepentingan dan tingkat kepuasan Anda. Tingkat kepentingan menunjukan seberapa penting sebuah pernyataan bagi Anda. Sedangkan, tingkat kepuasan menunjukkan seberapa puas pengalaman Anda setelah melakukan pengujian di Infrasutructure Assurance (IAS) Divisi Digital Business (DDB) PT. Telekomuniasi Indonesia, Tbk.
                    </p>
                    <p>Besar pengharapan kami agar pengisian survey ini dapat dikerjakan dengan sebaik-baiknya. Atas kerja samanya, kami ucapkan terimakasih.</p>
                    <p>
                    Skala pemberian nilai adalah 1 - 10 dengan nilai 1 adalah penilaian Sangat Tidak Baik atau Sangat Tidak Setuju. Kastemer diharapkan dapat memberikan dengan angka bulat.
                    </p>
                </div>
                <div class="row">
					<table id="table_kuisioner" style="width:100%; padding: 2px; border:1px">
					  <caption></caption>
                      <tr>
                        <th scope="col">NO</th>
                        <th scope="col">PERTANYAAN</th>
                        <th style="width: 25%;" scope="col">TINGKAT KEPENTINGAN</th>
                        <th style="width: 25%;" scope="col">TINGKAT KEPUASAN</th>
                      </tr>
                      <tbody>
            @php $no = 0; @endphp
            @foreach($data_kuisioner as $item)
              <input type="hidden" name="question_id[]" value="{{ $item->id }}">
              <input type="hidden" name="is_essay[]" value="{{ $item->is_essay }}">
              @php $no++; @endphp
              <tr>
                @if($item->is_essay)
                <td colspan = 2>{{ $item->question }}</td>
                <td colspan = 2>
                  <textarea name="eks{{$no-1}}" class="form-control" placeholder="..."></textarea>
                </td>
                @else
                <td>{{ $no }}</td>
                <td>{{ $item->question }}</td>
                <td>
                	<div class="radio-toolbar">
                		@php
                			for ($i=0; $i<10 ; $i++) { 
                		@endphp
                				<input type="radio" id="eks{{$no.$i}}" name="eks{{$no-1}}" value="{{$i+1}}" @php echo $i == 9 ? "checked" : "";@endphp><label for="eks{{$no.$i}}">{{$i+1}}</label>
                		@php
                			}
                		@endphp
                	</div>
                </td>
                <td>
                	<div class="radio-toolbar">
                		@php
                			for ($i=0; $i<10 ; $i++) { 
                		@endphp
                				<input type="radio" id="pref{{$no.$i}}" name="pref{{$no-1}}" value="{{$i+1}}" @php echo $i == 9 ? "checked" : "";@endphp><label for="pref{{$no.$i}}">{{$i+1}}</label>
                		@php
                			}
                		@endphp
                	</div>
                </td>
                @endif
              </tr>
            @endforeach
                      </tbody>
                    </table>
                </div>
            </form>
          </div>
          <div class="modal-footer">
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
					<table id="table_kuisioner" style="width:100%; padding: 2px; border=1px">
						<caption></caption>
                        <tr>
                            <th colspan="2" scope="colgroup">No</th>
                            <td colspan="2"><input type="text" name="no" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th scope="col">Sheet</th>
                            <td><input type="text" name="no" class="form-control" readonly></td>
                            <th scope="col">of</th>
                            <td><input type="text" name="no" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th colspan="2" scope="colgroup">
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
                            <th colspan="2" scope="colgroup">
                                <label>Customer Contact</label>
                                <input type="text" name="no" class="form-control" placeholder="-" value="{{ $currentUser->phone }}" readonly>
                            </th>
                            <td colspan="2">
								<input type="hidden" id="my_exam_id" name="my_exam_id">
								<label>Date</label>
									<input type="text" id="tanggal_complaint" name="tanggal_complaint" placeholder="DD/MM/YYYY" class="form-control" value="@php echo $date_dmY;@endphp" readonly required>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4" scope="colgroup">
                                <label>Customer Complaint</label>
                                <textarea name="complaint" class="form-control" placeholder="Your Complaint"></textarea>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" scope="colgroup">
                                <label>Name of Recipient</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" scope="colgroup">
                                <label>Corrective Action Taken</label>
                                <textarea class="form-control" placeholder="-" readonly></textarea>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" scope="colgroup">
                                <label>Completed Date</label>
                                <input type="text" name="no" class="form-control" placeholder="DD/MM/YYYY" readonly>
                            </th>
                            <th colspan="2" scope="colgroup">
                                <label>CPAR No</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" scope="colgroup">
                                <label>Name of Actiones</label>
                                <input type="text" name="no" class="form-control" placeholder="-" readonly>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" scope="colgroup">
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
                    <h2>Silakan Ambil Barang di Gudang Telkom Test House, Sebelum mengunduh Sertifikat. Terima Kasih</h2>
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
@endsection

@section('content_js')
<script type="text/javascript">
	$('.date').datepicker({  
		"format": "dd-mm-yyyy",
		"setDate": new Date(),
		"autoclose": true
	});
	// jQuery(document).ready(function() {
		// Main.init();
		// FormWizard.init();
	// });
	
	// $.ajaxSetup({
	// 	headers: {
	// 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 	}
	// });
	function isTestimonial(a,b,c,d,e){
		var link = document.getElementById('link');
			link.value = b;
		
		$.ajax({
			type: "POST",
			url : "{{URL::to('checkKuisioner')}}",
			data: {'_token':"{{ csrf_token() }}", 'id':e},
			type:'post',
			beforeSend: function(){
				document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				document.getElementById("overlay").style.display="none";
				console.log(response);
				if(response=='0'){
					$('#modal_kuisioner2').modal('show');
					$('#modal_kuisioner2').on('shown.bs.modal', function() {
						$("#device_name").val(a);
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
		$('#modal_kuisioner2').modal('hide');
		$("#my_exam_id").val(document.getElementById('exam_id').value);
		$.ajax({
			url : "{{URL::to('insertKuisioner')}}",
			data:new FormData($("#form-kuisioner")[0]),
			// dataType:'json',
			async:false,
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				document.getElementById("overlay").style.display="none";
				console.log(response);
				// if(response==1){
					$('#modal_complain').modal('show');	
					$('#modal_complain').on('shown.bs.modal', function() {
						$("#tanggal_complaint").focus();
					});					
				// }
			}
		});
	});

	$('.submit-complain').click(function () {
		$('#modal_complain').modal('hide');
		$.ajax({
			url : "{{URL::to('insertComplaint')}}",
			data:new FormData($("#form-complain")[0]),
			// dataType:'json',
			async:false,
			type:'post',
			processData: false,
			contentType: false,
			beforeSend: function(){
				document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				document.getElementById("overlay").style.display="none";
				console.log(response);
				checkAmbilBarang(document.getElementById('my_exam_id').value);
			}
		});
	});
	
	function checkAmbilBarang(a){
		$('#modal_complain').modal('hide');
		var link = document.getElementById('link').value;
		$.ajax({
			type: "POST",
			url : "{{URL::to('cekAmbilBarang')}}",
			data: {'_token':"{{ csrf_token() }}", 'my_exam_id':a},
			beforeSend: function(){
				document.getElementById("overlay").style.display="inherit";
			},
			success:function(response){
				document.getElementById("overlay").style.display="none";
				console.log(response);
				if(response==1){
					// window.location.href = '/telkomdds/public'+link;
					window.open(link);
				}else{
					$('#modal_status_barang').modal('show');
				}
			}
		});
	}
	
	$('#submit-testimonial').click(function () {
		var link = document.getElementById('link').value;
		// var message = document.getElementById('message').value;
		var exam_id = document.getElementById('exam_id').value;
		// window.location.href = '/telkomtesthouse/public'+link;
		window.location.href = link;
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