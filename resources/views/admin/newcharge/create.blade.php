@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Tarif Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Tarif</span>
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
				{!! Form::open(array('url' => 'admin/newcharge', 'method' => 'POST')) !!}
					{{ csrf_field() }}
    				<fieldset>
						<legend>
							Tambah Tarif Baru
						</legend>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Nama *
									</label>
									<input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nama" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Tanggal Penerapan *
									</label>
									<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
										<input type="text" name="valid_from" class="form-control" required="">
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">
												<i class="glyphicon glyphicon-calendar"></i>
											</button>
										</span>
									</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Deskripsi
									</label>
									<textarea type="text" name="description" class="form-control" placeholder="Deskripsi ..."></textarea>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>
							Daftar Tarif
						</legend>
						<!--  Buat PO Detail disini -->

						<a class="btn btn-sm btn-primary " id="btn-add-data"><i class="fa fa-plus"></i> Tambah Data</a>

						<table class="table table-striped" id="table-data"> 
							<thead>
							  <th class="text-center" width="40%">Data</th>
							  <th class="text-center">Biaya QA (Rp.)</th>
							  <th class="text-center">Biaya VT (Rp.)</th>
							  <th class="text-center">Biaya TA (Rp.)</th>
							  <th class="text-center">Biaya Baru QA (Rp.)</th>
							  <th class="text-center">Biaya Baru VT (Rp.)</th>
							  <th class="text-center">Biaya Baru TA (Rp.)</th>
							  <th class="text-center">Action</th>
							</thead> 
							<tbody id="container-data">
							  <tr id="data_1">
							    <td>
							      <select name="examination_charges_id[]" class="examination_charges_id form-control" required>
							        <option value="">Choose Data</option>
							        @foreach($data as $item)
										<option value="{{$item->id}}" data-price="{{$item->price}}" data-vt_price="{{$item->vt_price}}" data-ta_price="{{$item->ta_price}}">{{$item->device_name}} || {{$item->stel}}</option>
									@endforeach
							      </select>
							    </td>
							    <td><input type="text" name="price[]" class="form-control txt-price" value="{{ old('price') }}" placeholder="Biaya QA (Rp.)" readonly></td>
							    <td><input type="text" name="vt_price[]" class="form-control txt-vt_price" value="{{ old('vt_price') }}" placeholder="Biaya VT (Rp.)" readonly></td>
							    <td><input type="text" name="ta_price[]" class="form-control txt-ta_price" value="{{ old('ta_price') }}" placeholder="Biaya TA (Rp.)" readonly></td>
							    <td><input type="text" name="new_price[]" class="form-control txt-price" value="{{ old('new_price') }}" placeholder="Biaya Baru QA (Rp.)"></td>
							    <td><input type="text" name="new_vt_price[]" class="form-control txt-vt_price" value="{{ old('new_vt_price') }}" placeholder="Biaya Baru VT (Rp.)"></td>
							    <td><input type="text" name="new_ta_price[]" class="form-control txt-ta_price" value="{{ old('new_ta_price') }}" placeholder="Biaya Baru TA (Rp.)"></td>
							    <td><div class="btn btn-sm btn-primary btn-danger btn-delete-data"><i class="fa fa-trash"></i> Delete</div></td>
							  </tr>
							</tbody>
						</table>

						<!--  End PO Detail -->

						<div class="row">
							<div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/newcharge')}}">
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
	var examinationCharge = {!! json_encode($data) !!};
	var examinationCharge_value = '';
	for(i=0;i<examinationCharge.length;i++){
        examinationCharge_value+= "<option value="+examinationCharge[i].id+" data-price="+examinationCharge[i].price+" data-vt_price="+examinationCharge[i].vt_price+" data-ta_price="+examinationCharge[i].ta_price+" >"+examinationCharge[i].device_name+" || "+examinationCharge[i].stel+"</option>";
    }
	var rowCount = ($('#table-data tr').length)-1;
	jQuery(document).ready(function() {
		FormElements.init();
		formatPrice();
	});

	function formatPrice() {
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
        $(this).parent().parent().find('.txt-price').val($(this).find(':selected').data('price'));
        $(this).parent().parent().find('.txt-vt_price').val($(this).find(':selected').data('vt_price'));
        $(this).parent().parent().find('.txt-ta_price').val($(this).find(':selected').data('ta_price'));
        formatPrice();
    });

    $('#btn-add-data').on('click',function(){
    	rowCount++;
    	html = '<tr id="data_'+rowCount+'">\
		    <td>\
		      <select name="examination_charges_id[]" class="examination_charges_id form-control" required>\
		        <option value="">Choose Data</option>\
		        	'+examinationCharge_value+'\
		      </select>\
		    </td>\
		    <td><input type="text" name="price[]" class="form-control txt-price" value="{{ old("price") }}" placeholder="Biaya QA (Rp.)" readonly></td>\
		    <td><input type="text" name="vt_price[]" class="form-control txt-vt_price" value="{{ old("vt_price") }}" placeholder="Biaya VT (Rp.)" readonly></td>\
		    <td><input type="text" name="ta_price[]" class="form-control txt-ta_price" value="{{ old("ta_price") }}" placeholder="Biaya TA (Rp.)" readonly></td>\
		    <td><input type="text" name="new_price[]" class="form-control txt-price" value="{{ old("new_price") }}" placeholder="Biaya Baru QA (Rp.)"></td>\
		    <td><input type="text" name="new_vt_price[]" class="form-control txt-vt_price" value="{{ old("new_vt_price") }}" placeholder="Biaya Baru VT (Rp.)"></td>\
		    <td><input type="text" name="new_ta_price[]" class="form-control txt-ta_price" value="{{ old("new_ta_price") }}" placeholder="Biaya Baru TA (Rp.)"></td>\
		    <td><div class="btn btn-sm btn-primary btn-danger btn-delete-data"><i class="fa fa-trash"></i> Delete</div></td>\
		</tr>';

		$('#container-data').append(html);

		formatPrice();
		$('.examination_charges_id').chosen();
		$('.examination_charges_id').trigger("chosen:updated");
		$('.examination_charges_id').on('change',function(){
	        $(this).parent().parent().find('.txt-price').val($(this).find(':selected').data('price'));
	        $(this).parent().parent().find('.txt-vt_price').val($(this).find(':selected').data('vt_price'));
	        $(this).parent().parent().find('.txt-ta_price').val($(this).find(':selected').data('ta_price'));
	        formatPrice();
	    });

		$('.btn-delete-data').on('click',function(){
	        $(this).parent().parent().remove();
	    });
    });

    $('.btn-delete-data').on('click',function(){
        $(this).parent().parent().remove();
    });
</script>
@endsection