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
						<span>Edit</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		<!-- start: RESPONSIVE TABLE -->
		@if(!empty(Session::get('error_name')) && (Session::get('error_name') == 1))
			<div class="alert alert-error alert-danger">
				Nama Perangkat sudah ada!
			</div>
		@endif
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/newcharge/'.$id.'/updateDetail/'.$exam_id, 'method' => 'POST')) !!}
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
										<option value="{{$item->id}}" data-name="{{$item->device_name}}" data-stel="{{$item->stel}}" data-category="{{$item->category}}" data-duration="{{$item->duration}}" data-price="{{$item->price}}" data-vt_price="{{$item->vt_price}}" data-ta_price="{{$item->ta_price}}" @if ($data->examination_charges_id == $item->id) {{'selected'}}	@endif>{{$item->device_name}} || {{$item->stel}}</option>
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
									<input type="text" name="old_device_name" class="txt-name form-control" value="{{ $data->old_device_name }}" placeholder="Nama Perangkat" readonly>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Nama Perangkat *
									</label>
									<input type="text" name="device_name" class="txt-name form-control" value="{{ $data->device_name }}" placeholder="Nama Perangkat" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Referensi Uji
									</label>
									<input type="text" name="old_stel" class="txt-stel form-control" value="{{ $data->old_stel }}" placeholder="Referensi Uji" readonly>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Referensi Uji *
									</label>
									<input type="text" name="stel" class="txt-stel form-control" value="{{ $data->stel }}" placeholder="Referensi Uji" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
										Kategori
									</label>
									<select name="old_category" class="cmb-category">
										@foreach ($labs as $lab)
											<option value="{{$lab->id}}" @if ($data->old_category == $lab->id) selected @endif >{{$lab->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Kategori *
									</label>
									<select name="category" class="cmb-category" required>
										@foreach ($labs as $lab)
											<option value="{{$lab->id}}" @if ($data->category == $lab->id) selected @endif >{{$lab->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Durasi (Hari)
									</label>
									<input type="text" id="txt-old-duration" name="old_duration" class="txt-duration form-control" value="{{ $data->old_duration }}" placeholder="Durasi (Hari)" readonly>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Durasi (Hari) *
									</label>
									<input type="text" id="txt-duration" name="duration" class="txt-duration form-control" value="{{ $data->duration }}" placeholder="Durasi (Hari)" required>
								</div>
							</div>
	                        <div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya QA (Rp.)
									</label>
									<input type="text" id="txt-price" name="price" class="txt-price form-control" value="{{ $data->price }}" placeholder="Biaya QA (Rp.)" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya QA (Rp.) *
									</label>
									<input type="text" id="txt-new_price" name="new_price" class="txt-price form-control" value="{{ $data->new_price }}" placeholder="Biaya QA (Rp.)" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya VT (Rp.)
									</label>
									<input type="text" id="txt-vt_price" name="vt_price" class="txt-vt_price form-control" value="{{ $data->vt_price }}" placeholder="Biaya VT (Rp.)" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya VT (Rp.) *
									</label>
									<input type="text" id="txt-new_vt_price" name="new_vt_price" class="txt-vt_price form-control" value="{{ $data->new_vt_price }}" placeholder="Biaya VT (Rp.)" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya TA (Rp.)
									</label>
									<input type="text" id="txt-ta_price" name="ta_price" class="txt-ta_price form-control" value="{{ $data->ta_price }}" placeholder="Biaya TA (Rp.)" readonly="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
											Biaya TA (Rp.) *
									</label>
									<input type="text" id="txt-new_ta_price" name="new_ta_price" class="txt-ta_price form-control" value="{{ $data->new_ta_price }}" placeholder="Biaya TA (Rp.)" required>
								</div>
							</div>
	                        <div class="col-md-12">
	                        	@if($is_implement == 0)
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left" name="submit" value="submit">
	                                Submit
	                            </button>
	                            @endif
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