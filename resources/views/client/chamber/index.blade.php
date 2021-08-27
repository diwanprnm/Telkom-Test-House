@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.profile') }} - Telkom DDB</title>
        <title>Laravel</title>
        <link href="{{url('assets/css/pb.calendar.min.css')}}" rel="stylesheet" type="text/css">
		<style>
			.padding-y-md{
				padding: 5rem 0;
			}
			.pb-calendar .schedule-dot-item.blue{
				/* top: -50px !important;
				position: absolute !important;
				width: 17px !important;
				height: 17px !important;
				background-color: blue;
				width: 25px !important; */
			}
			.pb-calendar .schedule-dot-item.red{
				background-color: red;
				width: 40px !important;
			}
			.pb-calendar .schedule-dot-item.green{
				background-color: green;
			}
			.before-month, .after-month{
				/* font-size: 10px !important; */
			}
			.pb-calendar .before-today{
				opacity: 0.3;	
			}
		</style>
@section('content')

<section id="page-title">

	<div class="container clearfix">
		<h1>{{ "RENT CHAMBER" }}</h1>			
			<ol class="breadcrumb">
				<li><a href="#">{{ trans('translate.menu_testing') }}</a></li>
				<li class="active">{{ "RENT CHAMBER" }}</li>
			</ol>
	</div>

</section>

<!-- Content
============================================= -->
<section id="content">

	<div class="content-wrap">
		<div class="container clearfix">
			<div class="padding-y-md rounded border-danger">
				<div id="pb-calendar" class="pb-calendar">
				</div>
			</div>
			* Silahkan klik tanggal yang diinginkan untuk rent chamber.<br>
			* Garis bawah merah menandakan chamber sudah di booking.<br>
			* Tidak bisa menyewa dihari Sabtu dan Minggu
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
			  <button type="button" class="btn btn-primary" id="modal-rent-button">Rent</button>
			</div>
		  </div>
		</div>
	  </div>

</section><!-- #content end --> 

@endsection

@section('content_js')
<script type="text/javascript" src="{{url('assets/js/moment.js')}}"></script>
<script type="text/javascript" src="{{url('assets/js/pb.calendar.min.js')}}"></script>
<script>

$( document ).ready(function() {
	const currentDate = new Date();
	let current_yyyymm_ = moment().format("YYYYMM");
	let current_month = moment().format("MM");
	let bookedDates = [];

	let pbCalendarOption = {
		schedule_list :(callback_, yyyymm_) => {
			var temp_schedule_list_ = {};
			let bookedDateId = 1;
			bookedDates.forEach((bookedDate)=>{
				temp_schedule_list_[bookedDate] = [
					{'ID' : bookedDateId++, style :"red"}
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
		'next_month_button' :'<img src="./assets/images/arrow-next.png" class="icon">',
		'prev_month_button' :'<img src="./assets/images/arrow-prev.png" class="icon">',
		'min_clickable_from_today' : 7,

		callback_selected_day : (serializeDate) => {
			// exception untuk tidak bisa diklik
			if ( moment(serializeDate).day() == 6 || moment(serializeDate).day() == 0  ){return false;}
			//modal messages
			if (bookedDates.includes(serializeDate) ){
				$('#modal-rent-button').hide();
				$('.modal-body.messages').html('Chamber the room has been rented this date(' + moment(serializeDate).format('DD-MMM-YYYY')+ ')');

			}else{
				$('#modal-rent-button').show();
				$('.modal-body.messages').html('Do you wanted to rent chamber at: '+ moment(serializeDate).format('DD-MMM-YYYY'));
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
});

const getDateRentedChamber = handleData => {
	return $.ajax({
		url:"./v1/getDateRentedChamber",  
		success:function(data) {
			handleData(data); 
		}
	});
}




</script>

@endsection