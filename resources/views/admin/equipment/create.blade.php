@extends('layouts.app')

@section('content')
<div class="main-content" >
	<div class="wrap-content container" id="container">
		<!-- start: PAGE TITLE -->
		<section id="page-title">
			<div class="row">
				<div class="col-sm-8">
					<h1 class="mainTitle">Tambah Barang Baru</h1>
				</div>
				<ol class="breadcrumb">
					<li>
						<span>Beranda</span>
					</li>
					<li>
						<span>Barang</span>
					</li>
					<li class="active">
						<span>Tambah</span>
					</li>
				</ol>
			</div>
		</section>
		<!-- end: PAGE TITLE -->
		<!-- start: RESPONSIVE TABLE -->
		<div class="container-fluid container-fullw bg-white">
			<div class="col-md-12">
				{!! Form::open(array('url' => 'admin/equipment', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}
					{!! csrf_field() !!}
    				
					<div class="row">
                        <div class="col-md-12" style="margin-bottom:10px">
							<div class="form-group">
								<label>
									Perangkat Pengujian *
								</label>
								<select class="form-control" id="examination_id" name="examination_id" required>
									<option value="" disabled selected>Select...</option>
									@foreach($examination as $item)
										<option value="{{$item->id}}">{{$item->name}}, model/type {{$item->model}}</option>
									@endforeach
								</select>
							</div>
						</div>

						<fieldset>
							<legend>
								Tambah Unit
							</legend>

							<div id="equip_fields">
							</div>

							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label>
											Jumlah *
										</label>
										<input type="number" name="qty[]" class="form-control" placeholder="Jumlah" required>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>
											Unit/Satuan *
										</label>
										<input type="text" name="unit[]" class="form-control" placeholder="mis: meter, dll ..." required>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>
											PIC *
										</label>
										<input type="text" name="pic[]" class="form-control" placeholder="Nama penanggung jawab ..." required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>
											Deskripsi
										</label>
										<textarea type="text" name="description[]" class="form-control" placeholder="Deskripsi"></textarea>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label>
											Keterangan
										</label>
										<textarea type="text" name="remarks[]" class="form-control" placeholder="Keterangan"></textarea>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label>
											Aksi
										</label>
										<button class="btn btn-success" type="button"  onclick="equip_fields();"> <span class="glyphicon glyphicon-plus" style="float:right"></span></button>
									</div>
								</div>
							</div>

						</fieldset>

						<div class="col-md-12">
                            <button type="submit" class="btn btn-wide btn-green btn-squared pull-left">
                                Submit
                            </button>
                                <a style=" color:white !important;" href="{{URL::to('/admin/equipment')}}">
                            		<button type="button" class="btn btn-wide btn-red btn-squared btn-marginleft pull-left">
                                		Cancel
                            		</button>
                                </a>
                        </div>
					</div>
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
	$('#examination_id').chosen();
	// $('#examination_id').val(0);
	$('#examination_id').trigger("chosen:updated");
	jQuery(document).ready(function() {
		FormElements.init();
	});

	var equip = 1;
	function equip_fields() {
	 
	    equip++;
	    var objTo = document.getElementById('equip_fields')
	    var divtest = document.createElement("div");
		divtest.setAttribute("class", "form-group removeclass"+equip);
		var rdiv = 'removeclass'+equip;
	    divtest.innerHTML = '<div class="row"><div class="col-md-1"><div class="form-group"><label>Jumlah *</label><input type="number" name="qty[]" class="form-control" placeholder="Jumlah" required></div></div><div class="col-md-2"><div class="form-group"><label>Unit/Satuan *</label><input type="text" name="unit[]" class="form-control" placeholder="mis: meter, dll ..." required></div></div><div class="col-md-2"><div class="form-group"><label>PIC *</label><input type="text" name="pic[]" class="form-control" placeholder="Nama penanggung jawab ..." required></div></div><div class="col-md-3"><div class="form-group"><label>Deskripsi</label><textarea type="text" name="description[]" class="form-control" placeholder="Deskripsi"></textarea></div></div><div class="col-md-3"><div class="form-group"><label>Keterangan</label><textarea type="text" name="remarks[]" class="form-control" placeholder="Keterangan"></textarea></div></div><div class="col-md-1"><div class="form-group"><label>Aksi</label><button class="btn btn-danger" type="button"  onclick="remove_equip_fields('+ equip +');"> <span class="glyphicon glyphicon-minus" style="float:right"></span></button></div></div></div>';
	    
	    objTo.appendChild(divtest)
	}
	   function remove_equip_fields(rid) {
		   $('.removeclass'+rid).remove();
	   }
</script>
@endsection