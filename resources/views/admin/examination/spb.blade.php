<!DOCTYPE html>
<html lang="en" translate="no">
<head>
	<script type="text/javascript">
		try {
			if (top.location.hostname != self.location.hostname) throw 1;
		} 
		catch (e) {
			top.location.href = self.location.href;
		}
	</script>
    <!-- META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TELKOM DIGITAL SERVICE</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    <link href={{ asset("vendor/bootstrap/css/bootstrap.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/fontawesome/css/font-awesome.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/themify-icons/themify-icons.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/animate.css/animate.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("vendor/perfect-scrollbar/perfect-scrollbar.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("vendor/switchery/switchery.min.css") }} rel="stylesheet" media="screen" type="text/css">
    <link href={{ asset("assets/css/styles.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/bootstrap-colorpicker.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/plugins.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/themes/theme-1.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("vendor/bootstrap-timepicker/bootstrap-timepicker.min.css") }} rel="stylesheet" type="text/css">
    <link href={{ asset("assets/css/chosen.min.css") }} rel="stylesheet" type="text/css">
	
    <link href={{ asset("assets/css/jquery-ui-1_12_1.css") }} rel="stylesheet" type="text/css">
	<script src={{ asset("assets/js/jquery-1.12.4.js") }}></script>
	<script src={{ asset("assets/js/jquery-ui-1_12_1.js") }}></script>
	<style type="text/css">
        #overlay {
          z-index: 1000;
          display:none;
          position: fixed;
          top: 0px;
          left: 0px;
          width: 100%;
          height: 100%;
          background: rgba(4, 10, 30, 0.8);
        }
        #tengah{
            width: 250px;
            height: 30px;
            position: absolute;
            top:0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
			display: block;
    		text-align: -webkit-center;
        }
    </style>
</head>

<body>
	<div id="overlay">
        <div id="tengah"> 
            <br>
            <span style="color:#ffffff">Please wait ....</span>
            <img src='{{ asset("images/loading.gif") }}' alt=""/> 
        </div>
    </div>

	<input type="hidden" name="exam_id" id="exam_id" class="form-control" value="{{ $exam_id }}"/>
	<fieldset>
		<legend>
			Pembuatan SPB
		</legend>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>
						Nomor SPB *
					</label>
						<input type="text" name="spb_number" id="spb_number" class="form-control" value="{{ $spb_number }}" required/>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>
						Tanggal SPB *
					</label>
					<p class="input-group input-append datepicker date" data-date-format="yyyy-mm-dd">
					@php
						if($spb_date == '' or $spb_date == '0000-00-00' or $spb_date == NULL){
							$timestamp = date('Y-m-d');
						}else{
							$timestamp = date('Y-m-d', strtotime($spb_date));
						}
					@endphp
						<input type="text" name="spb_date" id="spb_date" class="form-control" value="{{ $timestamp }}" required readonly/>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default">
								<em class="glyphicon glyphicon-calendar"></em>
							</button>
						</span>
					</p>
				</div>
			</div>
			@if($data->examination_type_id != '1')
				<div class="col-md-12">
					<div class="form-group">
						<label>
							Pilih referensi uji
						</label>
						<select class="form-control" id="cmb-ref-perangkat" name="cmb-ref-perangkat" onchange="changeTotal();">
								<option value="">- Pilih Referensi Uji -</option>
							@foreach($data_stels as $item)
								<option value="{{ $item->price }}">{{ $item->stel }} || {{ $item->price }}</option>
							@endforeach
						</select>
					</div>
				</div>
			@endif
		</div>
		<table style="width: 100%;">
			<caption></caption>
			<tbody>
				<tr>
					<th class="center" scope="col">
						<label for="nama_perangkat">Nama Perangkat *</label>
					</th>
					<td class="center">
						<label for="biaya">Biaya *</label>
					</td>
					<td style="width:40px;"><a  style="width:40px;" value='Add More' class='del btn btn-success btn-flat' onclick='addAppend()'><em id='icon_add' class='fa fa-plus'></em></a></td>
				</tr>
				<tr>
					<td>
						<div class="form-group">
						<input type="text" class="form-control" name="nama_perangkat[]" value="{{ $data->device->name }}, merek {{ $data->device->mark }}, tipe {{ $data->device->model }}, kapasitas {{ $data->device->capacity }}" required>
						</div>
					</td>
					<td>
						<div class="form-group">
							<input type="number" class="form-control biaya" name="biaya[]" id="biaya1" value="{{ $price }}" required>
						</div>
					</td>
				</tr>
				@if($data->is_loc_test == 1)
					<tr>
						<td>
							<div class="form-group">
								<input type="text" class="form-control" name="nama_perangkat[]" value="Biaya Akomodasi & Transportasi" required>
							</div>
						</td>
						<td>
							<div class="form-group">
								<input type="number" class="form-control biaya" name="biaya[]" id="biaya1" required>
							</div>
						</td>
					</tr>
				@endif
			</tbody>
			<tbody class="tes_append"></tbody>
		</table>
		<p style="margin-top: -2%;font-size: 80%;">Note : Biaya belum termasuk ppn 10%</p>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>
						Total Biaya (Rp.) *
					</label>
						<input type="text" name="total_biaya" id="total_biaya" class="form-control" value="{{ $price + ($price*0.1) }}" readonly="" />
				</div>
			</div>
		</div>
	</fieldset>
		<div class="row">
			<div class="col-md-12">
				<button type="button" class="btn btn-wide btn-green btn-squared pull-right generate-button">
					Generate
				</button>
			</div>
		</div>
	
	<!-- start: MAIN JAVASCRIPTS -->
    <script src={{ asset("vendor/bootstrap/js/bootstrap.min.js") }}></script>
    <script src={{ asset("vendor/modernizr/modernizr.js") }}></script>
    <script src={{ asset("vendor/jquery-cookie/jquery.cookie.js") }}></script>
    <script src={{ asset("vendor/perfect-scrollbar/perfect-scrollbar.min.js") }}></script>
    <script src={{ asset("vendor/switchery/switchery.min.js") }}></script>
    <script src={{ asset("assets/js/main.js") }}></script>
    <script src={{ asset("assets/js/chosen.jquery.min.js") }}></script>
	<script src={{ asset("assets/js/accounting.min.js") }}></script>
	<script src={{ asset("assets/js/jquery.price_format.min.js") }}></script>
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
	$('.date').datepicker({  
			"format": "yyyy-mm-dd",
			"setDate": new Date(),
			"autoclose": true
		});
	jQuery(document).ready(function() {
		FormElements.init();
		
		$('#cmb-ref-perangkat').chosen();
		$("#cmb-ref-perangkat").change(function(){
			var price = $('#cmb-ref-perangkat').val();
			if(price == null){
				$('#biaya1').val(0);
			}else{
				$('#biaya1').val(price);
			}
		});
		
		/* $('.biaya').priceFormat({
			prefix: '',
			clearPrefix: true,
			centsLimit: 0
		}); */
		
		/* $(document).ready(function() {
			$(".biaya").keydown(function (e) {
				// Allow: backspace, delete, tab, escape, enter and .
				if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
					 // Allow: Ctrl/cmd+A
					(e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
					 // Allow: Ctrl/cmd+C
					(e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
					 // Allow: Ctrl/cmd+X
					(e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
					 // Allow: home, end, left, right
					(e.keyCode >= 35 && e.keyCode <= 39)) {
						 // let it happen, don't do anything
						 return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});
		});
		
		jQuery('.tabs .tab-links a').on('click', function(e)  {
			var currentAttrValue = jQuery(this).attr('href');
	 
			// Show/Hide Tabs
			jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
	 
			// Change/remove current tab to active
			jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
	 
			e.preventDefault();
		}); */
	});
	
    </script>
    <script>
		var hitung = 0;
		function addAppend(){
			hitung += 1;
			var bahan_append = '<tr id="hapus_'+hitung+'">';
					bahan_append += '<td>'
					bahan_append += '<div class="form-group">'
						bahan_append += '<input type="text" class="form-control" name="nama_perangkat[]" required>'
					bahan_append += '</div>'
					bahan_append += '</td>'
					bahan_append += '<td>'
					bahan_append += '<div class="form-group">'
						bahan_append += '<input type="number" class="form-control biaya" name="biaya[]" required>'
					bahan_append += '</div>'
					bahan_append += '</td>'
					bahan_append += '<td style="width:40px;"><a  style="width:40px;" value="Delete" class="del btn btn-danger btn-flat" onclick="destroy('+hitung+')"><i id="icon_add" class="fa-cross fa fa-remove"/></a></td>'
			bahan_append += '</tr>'

			$('.tes_append').append(bahan_append);
			$('.biaya').on('input',function(){
            	changeTotal();
	        });
		}
		function destroy(a)
		{
			$("#hapus_"+a+"").remove();
			changeTotal();
		}
		
		$('.generate-button').click(function () {
			arr_nama_perangkat = [];
			arr_biaya = [];
			total_biaya = 0;
			ppn = 0;
			var exam_id = document.getElementsByName("exam_id")[0].value;
			var spb_number = document.getElementsByName("spb_number")[0].value;
			var spb_date = document.getElementsByName("spb_date")[0].value;
			var nama_perangkat = document.getElementsByName("nama_perangkat[]");
				var i;
				for (i = 0; i < nama_perangkat.length; i++) {
					arr_nama_perangkat[i] = nama_perangkat[i].value;
				}
			var biaya = document.getElementsByName("biaya[]");
				for (i = 0; i < biaya.length; i++) {
					arr_biaya[i] = biaya[i].value ? biaya[i].value : 0;
					total_biaya += Number(arr_biaya[i]);
				}
				ppn = 0.1*total_biaya;
				total_biaya += ppn;
			
			var APP_URL = {!! json_encode(url('/cetakSPB')) !!};
			
			$.ajax({
				type: "POST",
				url : "generateSPB",
				data: {'_token':"{{ csrf_token() }}", 'exam_id':exam_id, 'spb_number':spb_number, 'spb_date':spb_date, 'arr_nama_perangkat':arr_nama_perangkat, 'arr_biaya':arr_biaya},
				beforeSend: function(){
					document.getElementById("overlay").style.display="inherit";
				},
				success: function(response){
					console.log(response);
					if(response == 2){
						alert("Nomor SPB sudah ada!");
						document.getElementById("overlay").style.display="none";
					}else if(response.includes("myToken")){
						var res = response.split("myToken");
						window.open(APP_URL);
						pops(res[0],res[1],spb_number,spb_date);
						window.close();
						// document.getElementById("overlay").style.display="none";
					}else{
						alert("Gagal mengambil data");
						document.getElementById("overlay").style.display="none";
					}
				},
				error:function(){
					alert("Gagal mengambil data");
					document.getElementById("overlay").style.display="none";
				}
			});
		});
		
        jQuery(document).ready(function() {
            Main.init();
            $('.biaya').on('input',function(){
            	changeTotal();
	        });
        });

        function changeTotal(){
        	arr_biaya = [];
			total_biaya = 0;
			ppn = 0;

    		var biaya = document.getElementsByName("biaya[]");
            for (i = 0; i < biaya.length; i++) {
				arr_biaya[i] = biaya[i].value ? biaya[i].value : 0;
				total_biaya += Number(arr_biaya[i]);
			}
			/*ppn = 0.1*total_biaya;
			total_biaya += ppn;*/

            $('#total_biaya').val(total_biaya);
            var total_biaya = document.getElementById('total_biaya');
			$('#total_biaya').val(formatPrice(total_biaya.value));
        }
		
		/* Fungsi */
		function formatPrice(angka, prefix)
		{
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
				split	= number_string.split(','),
				sisa 	= split[0].length % 3,
				rupiah 	= split[0].substr(0, sisa),
				ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);
				
			if (ribuan) {
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
			
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}

		function pops(PO_ID,jumlah,spb_number,spb_date){
			textcontent=opener.document.getElementById("PO_ID").value;
			opener.document.getElementById("PO_ID").value = PO_ID;
			
			textcontent=opener.document.getElementById("exam_price").value;
			opener.document.getElementById("exam_price").value = jumlah.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
			
			textcontent=opener.document.getElementById("spb_number").value;
			opener.document.getElementById("spb_number").value = spb_number;
			
			textcontent=opener.document.getElementById("spb_date").value;
			opener.document.getElementById("spb_date").value = spb_date;
		}
    </script>
    <!-- end: MAIN JAVASCRIPTS -->
</body>
</html>