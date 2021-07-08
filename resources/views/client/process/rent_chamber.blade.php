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
		.pb-calendar .schedule-dot-item.blue{
			background-color: blue;
			width: 30px !important;
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
		.before-month, .after-month{
			/* font-size: 10px !important; */
		}
		.pb-calendar .before-today{
			opacity: 0.3;	
		}
		.center-div{
			position: absolute;
			
			margin: auto;
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
								<div class="content-wrap">
									<div class="container clearfix center-div">
										<div class="padding-y-md rounded border-danger">
											<div id="pb-calendar" class="pb-calendar">
											</div>
											* Silahkan klik tanggal yang diinginkan untuk rent chamber.<br>
											* Garis bawah merah menandakan chamber sudah di booking.<br>
											* Tidak bisa menyewa dihari Sabtu dan Minggu
										</div>
									</div>
								</div>
								<input type="hidden" id="dates" name="dates">
								<button id="buttonSubmitHelper">Sumbit helper</button> (deleted soon)
							</fieldset>
							<h2>Third Step</h2>
							<fieldset>
								<legend></legend>
								<h4 class="judulselesai">{{ trans('translate.service_thanks') }}</h4> 
								{{-- <a class="button button3d btn-green" href="@php echo url('/pengujian');@endphp">Finish</a> --}}
							</fieldset>
						</div>
					</form>
				</div>
			</div> 
		</div> 

		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
		</div>
	</section><!-- #content end -->
@endsection 

@section('content_js')

 <script type="text/javascript" src="{{url('vendor/jquerystep/jquery.steps.js')}}"></script>
 <script>
 	$(window).bind('beforeunload',function(){
	    return 'are you sure you want to leave and your data will be lost?';
	});  
  	var form = $("#form-permohonan");
	form.validate({
	    errorPlacement: function errorPlacement(error, element) { element.before(error); },
	    rules: { 
	        required: true,extension: "jpeg|jpg|png|pdf"
	    }
	});

	
	var formWizard = form.children("div").steps({
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
				// do nothing
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
	        window.location.href = '@php echo url("/pengujian");@endphp';
	    }
	});
  	$('ul[role="tablist"]').hide();  
	
</script>

<script type="text/javascript" src="{{url('assets/js/moment.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/pb.calendar.min.js')}}"></script>
<script>

$( document ).ready(function() {
	const currentDate = new Date();
	let current_yyyymm_ = moment().format("YYYYMM");
	let current_month = moment().format("MM");
	let bookedDates = [];
	let toBeBookedDates = [];
	const can_not_rent_chamber = "{{ trans('translate.can_not_rent_chamber') }}";
	const rent_chamber_confirmation = "{{ trans('translate.rent_chamber_confirmation') }}";
	const rent_chamber_duration = "{{ trans('translate.rent_chamber_duration') }}";

	let pbCalendarOption = {
		schedule_list :(callback_, yyyymm_) => {
			var temp_schedule_list_ = {};
			let bookedDateId = 1;
			bookedDates.forEach((bookedDate)=>{
				temp_schedule_list_[bookedDate] = [
					{'ID' : bookedDateId++, style :"red"}
				];
			})
			toBeBookedDates.forEach((toBeBookedDate)=>{
				temp_schedule_list_[toBeBookedDate] = [
					{'ID' : bookedDateId++, style :"blue"}
				];
			})
			callback_(temp_schedule_list_);
		},
		schedule_dot_item_render :(dot_item_el_, schedule_data_) => {
			dot_item_el_.addClass(schedule_data_['style'],true);
			return dot_item_el_;
		},
		'day_selectable' : true,
		'min_yyyymm' :moment(currentDate),
		//'max_yyyymm' :moment( new Date(currentDate.setMonth(currentDate.getMonth()+2)) ),
		'next_month_button' :'<img src="{{ URL::asset('assets/images/arrow-next.png') }}" class="icon">',
		'prev_month_button' :'<img src="{{ URL::asset('assets/images/arrow-prev.png') }}" class="icon">',
		'min_clickable_from_today' : 7,

		callback_selected_day : (serializeDate) => {
			// exception untuk tidak bisa diklik
			if ( moment(serializeDate).day() == 6 || moment(serializeDate).day() == 0  ){return false;}
			//modal messages
			if (bookedDates.includes(serializeDate) ){
				$('#modal-rent-button').hide();
				$('.modal-body.messages').html("{{ trans('translate.can_not_rent_chamber') }}" + moment(serializeDate).format('DD-MMM-YYYY'));

			}else{
				$('#modal-rent-button').show();
				$('.modal-body.messages').html(
					"<p>"+rent_chamber_confirmation + moment(serializeDate).format('DD-MMM-YYYY')+'</p>'+
					`<div class="form-group">
						<p class="text-normal" for="rent_duration"> ${rent_chamber_duration}:</p>
						<input type="hidden" id="rent_date" name="rent_date" value="${serializeDate}">
						<select class="form-control" id="rent_duration" name="rent_duration">
							<option>1</option>
							<option>2</option>
						</select>
					</div>`
				);
			}
			$('#myModal').modal('toggle');
		},

	}
	//initializebystatic
	let pbCalendar = $("#pb-calendar").pb_calendar(pbCalendarOption);
	//initcalendarbyajx
	const initCalendarByAjax = async () => {
		await getDateRentedChamber( resp => {
			bookedDates = resp.map( dateRecord =>{
				return dateRecord['date'].replace(/-/g, '');
			});
		});
        pbCalendar.update_view();
	}
	//invoke ajax call
	initCalendarByAjax();

	//onclick rent
	$('#modal-rent-button').click(() => {
		let rentDuration = parseInt($("#rent_duration").find(":selected").text());
		let rentDate = $('#rent_date').val();
		checkAvaliableAndBooked(rentDate, rentDuration);
		console.log(toBeBookedDates);
		pbCalendar.update_view();
	});

	const checkAvaliableAndBooked = (date, numDays) =>{
		const dates = [];
		let isAvailable = false;
		let holidayCount = 0;
		for (i=0; i<numDays; i++){
			willBeBookedDates = moment(date).add(i+holidayCount, 'days');
			dates.push(willBeBookedDates.format('YYYYMMDD'));
			if (willBeBookedDates.day() == 5){
				holidayCount += 2;
			}
		}
		const found = dates.some(r=> bookedDates.indexOf(r) >= 0)
		if (!found) {
			toBeBookedDates = dates;
			$('input[name=dates]').val(JSON.stringify(toBeBookedDates));
		}
		else {alert("The date you selected is not available!");}
		return !found;
	}


	$('#buttonSubmitHelper').click(()=> {
		$.ajax({
			url: "{{url('testForm')}}",
			type: 'POST',
			data: new FormData($("#form-permohonan")[0]),
			processData: false,
			contentType: false,
			success: function(msg) {
				alert('Email Sent');
			}               
		});
	});
});

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