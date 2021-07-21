@extends('layouts.app')

@section('content')

<style type="text/css">
	ul.tabs{
		margin: 0px;
		padding: 0px;
		list-style: none;
		margin-bottom: 10px;
	}
	ul.tabs li{
		background: none;
		color: #222;
		display: inline-block;
		padding: 10px 15px;
		cursor: pointer;
	}

	ul.tabs li.current{
		background: #FF3E41;
		color: #ffffff;
	}

	.tab-content{
		display: none;
		/*background: #FF3E41;
		padding: 15px;*/
	}

	.tab-content.current{
		display: inherit;
	}
</style>

<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Rekap Pembelian STEL</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li class="active">
						<span>Rekap Pembelian STEL</span>
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

			<div class="row">
				<div class="col-md-6 pull-right" style="margin-bottom:10px;margin-top:20px">
					<a style=" color:white !important;" href="{{URL::to('/admin/sales/create')}}">
		            <button type="button" class="btn btn-wide btn-green btn-squared pull-right" >
						Tambah
		            </button>         
					</a>
		        </div>
		    </div>

			

			<ul class="tabs">
				<li class="btn tab-unpaid" data-tab="tab-unpaid">Unpaid</li>
				<li class="btn tab-paid" data-tab="tab-paid">Paid</li>
				<li class="btn tab-delivered" data-tab="tab-delivered">Delivered</li>
			</ul>

			<div class="row">
		        <div class="col-md-6">
	    			<a class="btn btn-wide btn-primary pull-left" data-toggle="collapse" href="#collapse1" style="margin-right: 10px;"><em class="ti-filter"></em>
						Filter
					</a>
					<button id="excel" type="submit" class="btn btn-info pull-left">
                        Export to Excel
                    </button>
				</div>
				<div class="col-md-6">
	                <span class="input-icon input-icon-right search-table">
	                    <input id="filter_search_input" type="text" placeholder="Search" id="form-field-17" class="form-control " value="">
	                    <em class="ti-search"></em>
	                </span>
	            </div>
	            <div class="col-md-12 panel panel-info">
			    	<div id="collapse1" class="panel-collapse collapse">
			     		<fieldset>
							<legend>
								Filter
							</legend>
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>
											Tanggal
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Dari Tanggal" value="" name="after_date" id="filter_after_date_input" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
									</div>
		                        </div>
		                        <div class="col-md-6">
									<div class="form-group">
										<label>
											&nbsp;
										</label>
										<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
											<input type="text" placeholder="Sampai Tanggal" value="" name="before_date" id="filter_before_date_input" class="form-control"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default">
													<em class="glyphicon glyphicon-calendar"></em>
												</button>
											</span>
										</p>
									</div>
		                        </div>

								<div class="col-md-12">
		                            <button id="filter" type="submit" class="btn btn-wide btn-green btn-squared pull-right">
		                                Filter
		                            </button>
		                        </div>
							</div>
							
						</fieldset>
			    	</div>
			    </div>
	        </div>


			{{-- <input type="hidden" name="hidden_tab" id="hidden_tab" value="{{ $tab }}"> --}}
			
			
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
	

$(document).ready(() => {
	// Initialization
	const baseUrl = "{{URL::to('/')}}";
	FormElements.init();
	setFilterByParam();

	// Setting view current tab list
	$('ul.tabs li').click((event) => {
		var tab_id = $(this).attr('data-tab');
		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');
		event.currentTarget.className += " current";
		$("#"+tab_id).addClass('current');
	})

	// Set destination when button filter(submit) click
	$('#filter').click(()=>{
		let param = getFilterParam();
		document.location.href = baseUrl+'/admin/chamber?'+$.param(param);
	});
	
	// Set destination when button excel click
	$('#excel').click(()=>{
		let param = getFilterParam();
		document.location.href = baseUrl+'/admin/chamber/excel?'+$.param(param);
	});
	
});

// Get url parameter from filter
const getFilterParam = () =>  {
	return {
		search:	$('#filter_search_input').val(),
		after: $('#filter_after_date_input').val(),
		before:	$('#filter_before_date_input').val(),
		tab: $('.tabs .current').attr('data-tab'),
	}
};

// Set filter from url parameter
const setFilterByParam = () =>  {
	const url = new URL(window.location.href);
	const currentTab = url.searchParams.get("tab") ?? 'tab-unpaid';
	$('#filter_search_input').val(url.searchParams.get("search"));
	$('#filter_after_date_input').val(url.searchParams.get("after"));
	$('#filter_before_date_input').val(url.searchParams.get("before"));
	$(`.${currentTab}`).addClass('current');
};



	
</script>>
@endsection