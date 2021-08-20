@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>Rent Chamber - Telkom DDB</title>
@section('content')
 	<link rel="stylesheet" href="{{url('vendor/jquerystep/main.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{url('vendor/jquerystep/jquery.steps.css')}}" type="text/css" /> 
	<link rel="stylesheet" href="{{url('assets/css/pb.calendar.min.css')}}" type="text/css">
  	<style type="text/css">
	  	ul[role="tablist"] {
	    	display: none;
		}
		.wizard .content {
		    min-height: 100px;
		}
		.wizard .content > .body {
		    width: 100%;
		    height: auto;
		    padding: 15px;
		    position: relative;
		}
        .text-normal{
			top: 4px;
            font-weight: normal !important;
        }
        .text-bold{
            font-weight: bold !important;
        }
		.padding-y-md{
			padding: 5rem 0;
		}
		.pb-calendar{
			width: 60%;
			transform: translate(20%, 0%);
		}
		.pb-calendar .row-day .red{
			background-color: red;
			color: white !important;
		}
		.pb-calendar .row-day .blue{
			background-color: blue;
			color: white !important;
		}
		.red{
			background-color: red !important;
			color: white !important;
		}
		.pb-calendar .schedule-dot-item.red{
			background-color: red;
			width: 30px !important;
		}
		.pb-calendar .schedule-dot-item.green{
			background-color: green;
		}
		.pb-calendar .before-month .schedule-dot-list,
		.pb-calendar .after-month .schedule-dot-list{
			display: none;
			width: 0;
			height: 0;
		}
		.pb-calendar .calendar-body-frame{
			margin-bottom: 2rem;
		}
		.before-month, .after-month{
			background: transparent !important;
			/* font-size: 10px !important; */
		}
		.pb-calendar .before-today{
			opacity: 0.3;	
		}
		.rounded-corner {
			border-radius: 15px;
			border: 5px solid white; 
		}
		.center-div{
			position: absolute;
			margin: auto;
		}
		.css-class-to-highlight a,
		.css-class-to-highlight span{
			background-color: red !important;
			color: white !important;
		}
		.material .material-input{
			padding-top: 0px; 
		}
		#numberOfDaysSelect{
			width: 100% !important;
		}
		#list-of-rent-date{
			font-size: 18px;
		}
		label{
			color: gray;
			padding: 1rem 0 0 0;
		}
		.date-tobe-booked a{
			background-color: blue !important;
			color: white !important;
		}
	</style>
  <div class="overlay"></div>
<!-- Page Title ============================================= -->
	<section id="page-title">
		<div class="container clearfix">
			<h1>RENT CHAMBER</h1>
			<ol class="breadcrumb">
				<li><a href="{{ url('/') }}">Home</a></li>
				<li>Testing</li>
				<li class="active">Process</li>
			</ol>
		</div>
	</section><!-- #page-title end -->

	<!-- Content
	============================================= -->
	<section id="content">

		<div class="content-wrap">  
			<div class="container">  
				<div class="content">
					<div class="col-md-12">
						<div class="step-process step ">
							<div class="garis"></div>
							<ul class="number">
								<li>
									<button class="step-fill active">1</button>
									<p>Form</p>
								</li>
								<li>
									<button class="step-fill">2</button>
									<p>Data Pendukung</p>
								</li>
								<li>
									<button class="step-fill">3</button>
									<p>Selesai</p>
								</li>
							</ul>
						</div>
					</div>
					<form role="form" action="{{url('testForm')}}" method="post" class="material" id="form-permohonan" enctype="multipart/form-data" onsubmit="return false">
						{{ csrf_field() }}
						<div id="wizard">
							<h2>First Step</h2>
							<fieldset>
								<legend></legend>
								<p>{{ trans('translate.description_rent_chamber') }}<p>
							</fieldset>

							<h2>Second Step</h2>
							<fieldset>
								<legend></legend>

									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="exampleFormControlInput1">{{ trans('translate.rent_chamber_client_label_choose_date') }}</label>
												<input type="text" class="form-control date" id="exampleFormControlInput1" placeholder="YYYY-MM-DD">
											</div>
										</div>

										<div class="col-md-4">
											<div class="form-group">
												<label for="exampleFormControlSelect1">{{ trans('translate.rent_chamber_client_label_choose_duration') }}</label>
												<select class="form-control" id="duratonOfRent">
													<option value="1">1 {{ trans('translate.chamber_days') }}</option>
													<option value="2">2 {{ trans('translate.chamber_days') }}</option>
												</select>
												</div>
										</div>

										<div class="col-md-4">
											<div class="form-group">
												<label>
													{{ trans('translate.rent_chamber_client_label_rent_date') }}
												</label>
												<p id="list-of-rent-date">...</p>
											</div>
										</div>

										<div class="col-md-12">
											<div id="rent-chamber-notes">
											</div>
										</div>

									</div>
									<br/>
									<br/>
									
									<div id="pb-calendar" class="pb-calendar">
									</div>
								<input type="hidden" id="dates" name="dates">
								{{-- <button id="buttonSubmitHelper">Sumbit helper</button> (deleted soon) --}}
							</fieldset>
							<h2>Third Step</h2>
							<fieldset>
								<legend></legend>
								<h4 class="judulselesai">{{ trans('translate.service_thanks') }}</h4> 
								{{-- <a class="button button3d btn-green" href="@php echo url('/chamber_history');@endphp">Finish</a> --}}
							</fieldset>
						</div>
					</form>
				</div>
			</div> 
		</div> 

		{{-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <h4 class="modal-title" id="myModalLabel">Rent Chamber</h4>
				</div>
				<div class="modal-body messages">
				  Booking Tanggal x
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  <button type="button" class="btn btn-primary" data-dismiss="modal" id="modal-rent-button">Rent</button>
				</div>
			  </div>
			</div>
		</div> --}}
	</section><!-- #content end -->
@endsection 

@section('content_js')

<script type="text/javascript" src="{{url('vendor/jquerystep/jquery.steps.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/moment.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/pb.calendar.min.js')}}"></script>
<script>

const indonesiaDayName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const indonesiaMonthName = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const can_not_rent_chamber = "{{ trans('translate.can_not_rent_chamber') }}";
const rent_chamber_confirmation = "{{ trans('translate.rent_chamber_confirmation') }}";
const rent_chamber_duration = "{{ trans('translate.rent_chamber_duration') }}";
const chamberNotes = "{!! trans('translate.rent_chamber_client_notes') !!}";;
const locale = "{{ trans('translate.locale') }}";
const form = $("#form-permohonan");
const currentDate = new Date();
let sendFormRentChamber;
let bookedDates = [];
let toBeBookedDates = [];
let messagesServerError = "Server error when submiting data.";
let messagesChamberHasBeenRented = "The date you requested is already rented by other customer.";
if (locale == 'Indonesia'){
	messagesServerError = "Kesalahan pada server saat pengiriman data.";
	messagesChamberHasBeenRented = "Tanggal yang anda minta baru saja di sewa pelanggan lain.";
}

$(window).bind('beforeunload',function(){
	return 'are you sure you want to leave and your data will be lost?';
});  
form.validate({
	errorPlacement: function errorPlacement(error, element) { element.before(error); },
	rules: { 
		required: true,extension: "jpeg|jpg|png|pdf"
	}
});

const formWizard = form.children("div").steps({
	headerTag: "h2",
	bodyTag: "fieldset",
	autoFocus: true,
	transitionEffect: "slideLeft",
	onStepChanging: function (event, currentIndex, newIndex){  
		if(!form.valid() && (newIndex > currentIndex)){ 

		}
		//UI
		form.trigger("focus"); 
		form.validate().settings.ignore = ":disabled,:hidden";

		if (currentIndex == 0){
			// do nothing this is text
		}

		if (currentIndex == 1 && newIndex == 2){
			let succedd = sendFormRentChamber();
			console.log(succedd);
			if (!succedd){
				return false;
			}
		}

		if(newIndex < currentIndex ){ 
			if(newIndex > 0) $( ".number li:eq("+(newIndex-1)+") button" ).removeClass("active").addClass("done");
			$( ".number li:eq("+(newIndex)+" ) button" ).removeClass("done").addClass("active");
			$( ".number li:eq("+(newIndex+1)+" ) button" ).removeClass("active");

			$(".form-group input").removeClass("error");
			$(".form-group span").removeClass("material-bar");
			$('body').scrollTop(10);
			return true;
		}else{
			if(form.valid()){
				$('body').scrollTop(10);
				if(newIndex > 0) $( ".number li:eq("+(newIndex-1)+") button" ).removeClass("active").addClass("done");
				$( ".number li:eq("+(newIndex)+" ) button" ).removeClass("done").addClass("active");
				$( ".number li:eq("+(newIndex+1)+" ) button" ).removeClass("active");
				if (newIndex == 2) {
					$( ".number li:eq("+(newIndex)+" ) button" ).removeClass("active").addClass("done");
					$( ".number li:eq("+(newIndex+1)+" ) button" ).removeClass("active").addClass("done");
				}
			}
			return form.valid();	
		} 
	},
	onStepChanged: (event, currentIndex, priorIndex) =>{
		if (currentIndex == 0 ){
			$( '#formBTNprevious' ).hide(); $( '#formBTNnext' ).show(); $( '#formBTNfinish' ).hide();
			$( '#formBTNnext' ).html("Next");
		} else if (currentIndex == 1 ){
			$( '#formBTNprevious' ).show(); $( '#formBTNnext' ).show(); $( '#formBTNfinish' ).hide();
			$( '#formBTNnext' ).html("Save");
		} else if (currentIndex == 2 ){
			$( '#formBTNprevious' ).hide(); $( '#formBTNnext' ).hide(); $( '#formBTNfinish' ).show();
		}
	},
	onFinishing: function (event, currentIndex){
		form.validate().settings.ignore = ":disabled";
		return form.valid();
	},
	onFinished: function (event, currentIndex){
		window.location.href = '@php echo url("/chamber_history");@endphp';
	}
});

$('ul[role="tablist"]').hide();

$( document ).ready(function() {
	const myDatePicker = $('.date');
	const minDate = moment(new Date()).add(7, 'days').format('YYYY-MM-DD');
	let current_yyyymm_ = moment().format("YYYYMM");
	let current_month = moment().format("MM");

	let pbCalendarOption = {
		'day_selectable' : false,
		'min_yyyymm' :moment(currentDate),
		'next_month_button' :'<img src="{{ URL::asset('assets/images/arrow-next.png') }}" class="icon">',
		'prev_month_button' :'<img src="{{ URL::asset('assets/images/arrow-prev.png') }}" class="icon">',
		'min_clickable_from_today' : 7,
		'callback_changed_month': (event) => {
			setDayLabelWithClass(bookedDates,'red');
			setDayLabelWithClass(toBeBookedDates,'blue');
		}
	}
	//let pbCalendar = $("#pb-calendar").pb_calendar(pbCalendarOption);

	const initCalendarByAjax = async () => {
		await getDateRentedChamber( resp => {
			bookedDates = resp.map( dateRecord =>{
				return dateRecord['date'].replace(/-/g, '');
			});
		});
		setDayLabelWithClass(bookedDates,'red');
		initDatePicker();
		$("#rent-chamber-notes").html(chamberNotes);
		nearestAvailableDate = getNearestAvailableDate( moment(minDate, 'YYYY-MM-DD') );
		toBeBookedDates.push(nearestAvailableDate.format('YYYYMMDD'))
		myDatePicker.val(nearestAvailableDate.format('YYYY-MM-DD'));
		calculateEndDate();
	}

	const initDatePicker = () => {
		myDatePicker.datepicker({
			minDate: minDate,
			dateFormat: 'yy-mm-dd', 
			autoclose: true,
			numberOfMonths: 2 ,
			showButtonPanel: true,
			firstDay: 1,
			beforeShowDay: function(d) {
				date = moment(d).format('YYYYMMDD');
				dates = bookedDates;
				if(dates.includes(date)){
					return [false, "css-class-to-highlight"];
				} else if(d.getDay() == 6 || d.getDay() == 0){
					return [false, "no"];
				}
				return [true, toBeBookedDates.includes(date) ? "date-tobe-booked" : ""];
			},
		});
	}


	const checkAvaliableAndBooked = (date, numDays) =>{
		const dates = [];
		let isAvailable = false;
		let willBeBookedDates = moment(date, 'YYYY-MM-DD');
		for (i=0; i<numDays; i++){
			willBeBookedDates = getNearestAvailableDate(willBeBookedDates);
			dates.push(willBeBookedDates.format('YYYYMMDD'));
			willBeBookedDates.add(1, 'days');
		}
		toBeBookedDates = dates;
		$('input[name=dates]').val(JSON.stringify(toBeBookedDates));
		return true;
	}

	// //onclick rent
	// $('#modal-rent-button').click(() => {
	// 	let rentDuration = parseInt($("#rent_duration").find(":selected").val());
	// 	let rentDate = $('#rent_date').val();
	// 	checkAvaliableAndBooked(rentDate, rentDuration);
	// 	pbCalendar.update_view();
	// });

	// 	for (i=0; i<numDays; i++){
	// 		willBeBookedDates = moment(date).add(i+holidayCount, 'days');
	// 		dates.push(willBeBookedDates.format('YYYYMMDD'));
	// 		if (willBeBookedDates.day() == 5){
	// 			holidayCount += 2;
	// 		}
	// 	}
	// 	const found = dates.some(r=> bookedDates.indexOf(r) >= 0)
	// 	if (!found) {
	// 		toBeBookedDates = dates;
	// 		$('input[name=dates]').val(JSON.stringify(toBeBookedDates));
	// 	}
	// 	else {
	// 		myDatePicker.val(moment(toBeBookedDates[0]).format('YYYY-MM-DD'));
	// 		$("#duratonOfRent").val(toBeBookedDates.length);
	// 		alert("The date you selected is not available!");
	// 	}
	// 	return !found;
	// }

	const calculateEndDate = () => {
		numberOfDay = parseInt($("#duratonOfRent").find(":selected").text());
		startRentDate = myDatePicker.val();
		let isAvailable = checkAvaliableAndBooked(startRentDate, numberOfDay)
		if(isAvailable){
			//pbCalendar.update_view();
			setMonthCalendarView(startRentDate);
			setDayLabelWithClass(toBeBookedDates,'blue');
			setDayLabelWithClass(bookedDates,'red');
			
			// Create list date
			listOfDate = toBeBookedDates.map((date)=>{
				let dateMoment = moment(date, 'YYYYMMDD');
				if (locale == 'Indonesia'){
					textDate = dateMoment.format('DD')+' '+indonesiaMonthName[ parseInt(dateMoment.format('MM'))-1 ]+' '+dateMoment.format('YYYY');
				}else{
					textDate = moment(dateMoment, 'YYYYMMDD').format('DD MMMM YYYY');
				}
				return textDate;
			});

			// format list date then set into html
			textListOfDate = listOfDate.reduce((text, current)=> `${text}, ${current}`).replace(/,(?=[^,]*$)/, ' &');
			$('#list-of-rent-date').html(textListOfDate);
		}
	}

	const setMonthCalendarView = (startRentDate = new Date) => {
		//trigger("click");
		const nextButton = $('#pb-calendar .control-btn.next-btn');
		const prevButton = $('#pb-calendar .control-btn.prev-btn');
		let firstDayOfMonth = $('#pb-calendar .row-day .col:not(.before-month):first').data('day-yyyymmdd');
		let yearMonthRentDate = parseInt(moment(startRentDate, 'YYYY-MM-DD').format('YYYYMM'))
		let yearMonthCalendarView = parseInt(moment(firstDayOfMonth, 'YYYYMMDD').format('YYYYMM'));
		
		if ( yearMonthRentDate < yearMonthCalendarView){
			prevButton.trigger("click");
			setMonthCalendarView(startRentDate);
		} else if ( yearMonthRentDate > yearMonthCalendarView ){
			nextButton.trigger("click");
			setMonthCalendarView(startRentDate);
		}
	}

	const getNearestAvailableDate = (lastCheckedDate) => {
		if (!lastCheckedDate instanceof moment){
			console.error(`The parameter passed to getNearestAvailableDate isn't instace of moment.js`)
		}
		let nearestAvailableDate = false;
		while (!nearestAvailableDate){
			if( bookedDates.includes(lastCheckedDate.format('YYYYMMDD')) || lastCheckedDate.day() == '6' || lastCheckedDate.day() == '0'){
				lastCheckedDate.add(1, 'days');
			}else{
				nearestAvailableDate = lastCheckedDate;
			}
		}
		return nearestAvailableDate;
	}
	
	initCalendarByAjax();
	
	
	myDatePicker.change(calculateEndDate);
	$('#duratonOfRent').change(calculateEndDate);
	// $('#buttonSubmitHelper').click(()=> {
	// 	sendFormRentChamber();
	// });

	sendFormRentChamber = () => {
		let isSuccedd = false;
		$.ajax({
			url: "{{url('chamber')}}",
			type: 'POST',
			async: false,
			data: new FormData($("#form-permohonan")[0]),
			processData: false,
			contentType: false,
			beforeSend: function(){
				$("body").addClass("loading");  
			},
			success: (res) => {
				$("body").removeClass("loading"); 
				isSuccedd = res.success; 
				console.log(res, res['success']);
				if (!res.success){
					alert(messagesChamberHasBeenRented);
					initCalendarByAjax();
				}
			},
			error: (err) => {
				$("body").removeClass("loading");  
				alert(messagesServerError);
				console.error(messagesServerError, err);
				initCalendarByAjax();
				isSuccedd = false;
			}
		});
		return isSuccedd;
	}

});

const setDayLabelWithClass = (list, color) => list.forEach( item => $(`.row-day .col[data-day-yyyymmdd='${item}']`).addClass(`${color} rounded-corner`) );
const getDateRentedChamber = handleData => {
	return $.ajax({
		url:"{{URL::to('/v1/getDateRentedChamber')}}",  
		success:function(data) {
			handleData(data); 
		}
	});
}
</script>
@endsection