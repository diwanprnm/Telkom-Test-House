@extends('layouts.app')

@section('content')
<link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" >
<style type="text/css">
	.css-class-to-highlight a,
	.css-class-to-highlight span{
		background-color: red !important;
		color: white !important;
	}
	.date-tobe-booked a,
	.date-tobe-booked span{
		background-color: blue !important;
		color: white !important;
	}
	.btn{
		margin-right: 1rem;
	}
</style>
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Manage Chamber</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Keuangan</span>
					</li>
					<li>
						<span>Chamber</span>
					</li>
					<li class="active">
						<span>Manage Chamber</span>
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

		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/chamber/'.$data->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
					<fieldset>
						<legend>
							Manage Chamber
						</legend>
						<div class="row"> 
							<input type="hidden" name="PO_ID" id="PO_ID">
							<div class="col-md-12">
								<div class="form-group">
									<label>Perusahaan</label>
									<input type="text" name="company_name" class="form-control" value="{{$data->company_name}}" readonly>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Tanggal 1</label>
									<input type="text" name="start_date" class="form-control datepicker-chamber" value="{{$dataDetail[0]->date ?? ''}}">
								</div>
							</div>
							@if ($data->duration > 1)
							<div class="col-md-12">
								<div class="form-group">
									<label>Tanggal 2</label>
									<input type="text" name="end_date" class="form-control datepicker-chamber" value="{{$dataDetail[1]->date ?? ''}}" >
								</div>
							</div>
							@endif

							<div class="col-md-12">
								<div class="form-group">
									<label>Biaya</label>
									<input type="text" name="price" class="form-control text-price" value="{{$data->price}}" @if ($data->spb_date) readonly @endif>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Pajak</label>
									<input type="text" name="tax" class="form-control text-price" value="{{$data->tax}}" readonly>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Total</label>
									<input type="text" name="total" class="form-control text-price" value="{{$data->total}}" readonly>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Tanggal Tagihan</label>
									<input type="text" name="spb_date" class="form-control datepicker" value="{{$data->spb_date ?? Carbon\Carbon::now()->format('Y-m-d')}}" required @if ($data->spb_date) readonly disable @endif >
								</div>
							</div>
						</div>

						<div class="row"> 
							<div class="col-md-12">
								<button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
									Submit
								</button>
								<a style=" color:white !important;" href="{{URL::to('/admin/chamber')}}">
									<button type="button" class="btn btn-wide btn-red btn-squared pull-left">
									Kembali
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
<script src={{ asset("vendor/jquery-validation/jquery.validate.min.js") }}></script>
<script type="text/javascript" src="{{url('assets/js/moment.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript">
$( document ).ready(() => {

// INITIALIZE
	const id = "{{$data->id}}";
	const datePicker = $('.datepicker');
	const myDatePicker = $('.datepicker-chamber');
	let bookedDates = [];
	let detailRentDate = {!! json_encode($dataDetail) !!}.map((date)=>{
		return date.date;
	});
	const spbDate = "{{$data->spb_date ?? ''}}";

// EVENT
	$('input[name="price"]').on("keyup change",()=>{
		price = parseInt($('input[name="price"]').val().replace(/[^\d.-]/g, ''));
		$('input[name="tax"]').val( Math.round(price * 0.1) );
		$('input[name="total"]').val( Math.round(price * 1.1) );
		priceFormat($('input[name="tax"]'));
		priceFormat($('input[name="total"]'));

		console.log(price);
	});

// FUNCTION
	const priceFormat = (elem) => {
		elem.priceFormat({
			prefix: '',
			clearPrefix: true,
			centsLimit: 0
		}); 
	}

	const initDatePicker = () => {
		detailRentDate = detailRentDate.sort();
		myDatePicker.datepicker({
			dateFormat: 'yy-mm-dd', 
			autoclose: true,
			numberOfMonths: 2 ,
			showButtonPanel: true,
			firstDay: 1,
			beforeShowDay: function(d) {
				addedClass = '';
				date = moment(d).format('YYYY-MM-DD');
				clickAble = d >= new Date() && d <= moment(detailRentDate[detailRentDate.length-1]).add(7, 'days');
				if(bookedDates.includes(date)){ addedClass = 'css-class-to-highlight'; clickAble = false;}
				if(detailRentDate.includes(date)){ addedClass = 'date-tobe-booked'; clickAble = true;}				
				return [clickAble, addedClass];
			},
		});
	}

	const initDatePickerInput = () => {
		datePicker.datepicker({
			dateFormat: 'yy-mm-dd', 
			autoclose: true,
			numberOfMonths: 1,
			showButtonPanel: true,
			firstDay: 1,
			minDate:0,
		});
	}	

	const getDateRentedChamber = handleData => {
		return $.ajax({
			url:"{{URL::to('/v1/getDateRentedChamber')}}",  
			success:function(data) {
				handleData(data); 
			}
		});
	}

	const initCalendarByAjax = async () => {
		await getDateRentedChamber( resp => {
			bookedDates = resp.map( dateRecord =>{
				return dateRecord['date'];
			});
		});
		initDatePicker();
	}

// CONTRUCT
	priceFormat($('.text-price'));
	initCalendarByAjax();
	spbDate ? datePicker.datepicker('disable') : initDatePickerInput();
});
</script>
@endsection