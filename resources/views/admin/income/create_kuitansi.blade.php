@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Kuitansi Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Kuitansi</span>
					</li>
					<li class="active">
						<span>Tambah</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		@if(!empty(Session::get('error')))
			<div class="alert alert-error alert-danger">
				{{ (Session::get('error')) }}
			</div>
		@endif
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/kuitansi', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{{ csrf_field() }}
    				<fieldset>
						<legend>
							Tambah Kuitansi Baru
						</legend>
						<div class="row"> 
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Nomor *
									</label>
									<input type="text" id="number" name="number" class="form-control" value="{{ old('number') }}" placeholder="Nomor" required>
									<button type="button" class="btn btn-wide btn-green btn-squared pull-right" onclick="generateKuitansi()">
										Generate
									</button>
								</div>
							</div> 
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Sudah diterima dari *
									</label>
									<input type="text" name="from" class="form-control" value="{{ old('from') }}" placeholder="Sudah diterima dari" required>
								</div>
							</div> 
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Banyak Uang *
									</label>
									<input type="number" name="price" class="form-control" value="{{ old('price') }}" placeholder="Banyak Uang" required>
								</div>
							</div> 
	                        <div class="col-md-12">
								<div class="form-group">
									<label>
										Untuk Pembayaran *
									</label>
									<input type="text" name="for" class="form-control" value="{{ old('for') }}" placeholder="Untuk Pembayaran" required>
								</div>
							</div> 
	                        <div class="col-md-12">
	                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
	                                Submit
	                            </button>
								<a style=" color:white !important;" href="{{URL::to('/admin/kuitansi')}}">
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
@endsection

<script type="text/javascript">
	function generateKuitansi(){
		$.ajax({
			type: "POST",
			url : "generateKuitansi",
			data: {'_token':"{{ csrf_token() }}"},
			beforeSend: function(){
				document.getElementById("number").disabled = true;
			},
			success: function(response){
				document.getElementById("number").disabled = false;
				document.getElementById("number").value = response;
				$('#number').val(response);
			},
			error:function(){
				alert("Gagal mengambil data");
				document.getElementById("number").disabled = false;
			}
		});
	}
</script>