@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Tarif Pengujian Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Data Master</span>
					</li>
					<li>
						<span>Tarif Pengujian Baru</span>
					</li>
					<li>
						<span>Detail</span>
					</li>
					<li class="active">
						<span>Tambah</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		@if(!empty(Session::get('error_name')) && (Session::get('error_name') == 1))
			<div class="alert alert-error alert-danger">
				Nama Perangkat sudah ada!
			</div>
		@endif
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/newcharge/'.$id.'/postDetail', 'method' => 'POST')) !!}
					{{ csrf_field() }}
    				<fieldset>
						<legend>
							Tambah Tarif Pengujian Baru
						</legend>
						<div class="row">
							<div class="col-md-12">
								<select name="examination_charges_id" class="examination_charges_id form-control">
							        <option value="">Choose Data</option>
							        @foreach($examinationCharge as $item)
										<option value="{{$item->id}}" data-name="{{$item->device_name}}" data-stel="{{$item->stel}}" data-category="{{$item->category}}" data-duration="{{$item->duration}}" data-price="{{$item->price}}" data-vt_price="{{$item->vt_price}}" data-ta_price="{{$item->ta_price}}">{{$item->device_name}} || {{$item->stel}}</option>
									@endforeach
							    </select>
							</div>

							<div class="col-md-12"><label></label></div>

							<div class="col-md-6"><label><strong>SEBELUM</strong></label></div>
							<div class="col-md-6"><label><strong>SESUDAH</strong></label></div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nama Perangkat
									</label>
									<input type="text" name="old_device_name" class="txt-name form-control" value="{{ old('old_device_name') }}" placeholder="Nama Perangkat" readonly>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Nama Perangkat *
									</label>
									<input type="text" name="device_name" class="txt-name form-control" value="{{ old('device_name') }}" placeholder="Nama Perangkat" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Referensi Uji
									</label>
									<input type="text" name="old_stel" class="txt-stel form-control" value="{{ old('old_stel') }}" placeholder="Referensi Uji" readonly>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Referensi Uji *
									</label>
									<input type="text" name="stel" class="txt-stel form-control" value="{{ old('stel') }}" placeholder="Referensi Uji" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Kategori
									</label>
									<select name="old_category" class="cmb-category">
										@if( old('old_category') == 'Lab CPE' )
											<option value="Lab CPE" selected>Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('old_category') == 'Lab Device' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device" selected>Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('old_category') == 'Lab Energi' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi" selected>Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('old_category') == 'Lab Kabel' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel" selected>Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('old_category') == 'Lab Transmisi' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi" selected>Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('old_category') == 'Lab EMC' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC" selected>Lab EMC</option>
										@else
											<option value="" disabled selected>Select...</option>
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Kategori *
									</label>
									<select name="category" class="cmb-category" required>
										@if( old('category') == 'Lab CPE' )
											<option value="Lab CPE" selected>Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('category') == 'Lab Device' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device" selected>Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('category') == 'Lab Energi' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi" selected>Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('category') == 'Lab Kabel' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel" selected>Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('category') == 'Lab Transmisi' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi" selected>Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@elseif( old('category') == 'Lab EMC' )
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC" selected>Lab EMC</option>
										@else
											<option value="" disabled selected>Select...</option>
											<option value="Lab CPE">Lab CPE</option>
											<option value="Lab Device">Lab Device</option>
											<option value="Lab Energi">Lab Energi</option>
											<option value="Lab Kabel">Lab Kabel</option>
											<option value="Lab Transmisi">Lab Transmisi</option>
											<option value="Lab EMC">Lab EMC</option>
										@endif
									</select>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Durasi (Hari)
									</label>
									<input type="text" id="txt-old-duration" name="old_duration" class="txt-duration form-control" value="{{ old('old_duration') }}" placeholder="Durasi (Hari)" readonly>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Durasi (Hari) *
									</label>
									<input type="text" id="txt-duration" name="duration" class="txt-duration form-control" value="{{ old('duration') }}" placeholder="Durasi (Hari)" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya QA (Rp.)
									</label>
									<input type="text" id="txt-price" name="price" class="txt-price form-control" value="{{ old('price') }}" placeholder="Biaya QA (Rp.)" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya QA (Rp.) *
									</label>
									<input type="text" id="txt-new_price" name="new_price" class="txt-price form-control" value="{{ old('new_price') }}" placeholder="Biaya QA (Rp.)" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya VT (Rp.)
									</label>
									<input type="text" id="txt-vt_price" name="vt_price" class="txt-vt_price form-control" value="{{ old('vt_price') }}" placeholder="Biaya VT (Rp.)" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya VT (Rp.) *
									</label>
									<input type="text" id="txt-new_vt_price" name="new_vt_price" class="txt-vt_price form-control" value="{{ old('new_vt_price') }}" placeholder="Biaya VT (Rp.)" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya TA (Rp.)
									</label>
									<input type="text" id="txt-ta_price" name="ta_price" class="txt-ta_price form-control" value="{{ old('ta_price') }}" placeholder="Biaya TA (Rp.)" readonly="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya TA (Rp.) *
									</label>
									<input type="text" id="txt-new_ta_price" name="new_ta_price" class="txt-ta_price form-control" value="{{ old('new_ta_price') }}" placeholder="Biaya TA (Rp.)" required>
								</div>
							</div>
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left" name="submit" value="submit">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/newcharge/'.$id)}}">
									<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
									Cancel
									</button>
								</a>
	                        </div>
						</div>
					</fieldset>
				{!! Form::close() !!}
			</div>
		</div>
		<!-- end: RESPONSIVE TABLE -->
	</div>
</div>
@endsection

@section('content_js')
<script src={{ asset("vendor/maskedinput/jquery.maskedinput.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js") }}></script>
<script src={{ asset("vendor/autosize/autosize.min.js") }}></script>
<script src={{ asset("vendor/selectFx/classie.js") }}></script>
<script src={{ asset("vendor/selectFx/selectFx.js") }}></script>
<script src={{ asset("vendor/select2/select2.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker.min.js") }}></script>
<script src={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.js") }}></script>
<script src={{ asset("vendor/jquery-validation/jquery.validate.min.js") }}></script>
<script src={{ asset("assets/js/form-elements.js") }}></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		FormElements.init();
		formatPrice();
	});

	function formatPrice() {
		$('.txt-duration').priceFormat({
			prefix: '',
			clearPrefix: true,
			centsLimit: 0
		}); 
		
		$('.txt-price').priceFormat({
			prefix: '',
			clearPrefix: true,
			centsLimit: 0
		}); 
		
		$('.txt-vt_price').priceFormat({
			prefix: '',
			clearPrefix: true,
			centsLimit: 0
		}); 
		
		$('.txt-ta_price').priceFormat({
			prefix: '',
			clearPrefix: true,
			centsLimit: 0
		}); 
	}

	$('.examination_charges_id').chosen();
	$('.examination_charges_id').trigger("chosen:updated");

	$('.examination_charges_id').on('change',function(){
		$(this).parent().parent().find('.txt-name').val($(this).find(':selected').data('name'));
        $(this).parent().parent().find('.txt-stel').val($(this).find(':selected').data('stel'));
        $(this).parent().parent().find('.cmb-category').val($(this).find(':selected').data('category'));
        $(this).parent().parent().find('.txt-duration').val($(this).find(':selected').data('duration'));
        $(this).parent().parent().find('.txt-price').val($(this).find(':selected').data('price'));
        $(this).parent().parent().find('.txt-vt_price').val($(this).find(':selected').data('vt_price'));
        $(this).parent().parent().find('.txt-ta_price').val($(this).find(':selected').data('ta_price'));
        formatPrice();
    });
</script>
@endsection