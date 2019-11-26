@extends('layouts.client')
<!-- Document Title
    ============================================= -->
    <title>{{ trans('translate.contact') }} - Telkom DDS</title>
@section('content')
		<link rel="stylesheet" href="{{url('vendor/chosen/chosen.css')}}">
		<!-- Page Title
		============================================= -->
		<section id="page-title">

			<div class="container clearfix">
				<h1>{{ trans('translate.contact') }}</h1>
				
				<ol class="breadcrumb">
					<li><a href="#">{{ trans('translate.menu_company') }}</a></li>
					<li class="active">{{ trans('translate.contact') }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap">


				<div class="container clearfix">

					<!-- <div class="col_two_third">

						<div class="contact-widget">

							<div class="contact-form-result">
								@if (Session::get('error_feedback'))
									<div class="alert alert-error alert-danger">
										{{ Session::get('error_feedback') }}
									</div>
								@endif
								
								@if (Session::get('message_feedback'))
									<div class="alert alert-info">
										{{ Session::get('message_feedback') }}
									</div>
								@endif 
							</div>

							<form id="form-send-feedback" class="nobottommargin"  role="form" method="POST" action="{{ url('/client/feedback') }}">
						 	{{ csrf_field() }}
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-process"></div>
							
							<div class="col_full">
								<label for="template-contactform-name">{{ trans('translate.contact_question') }} <small>*</small></label>
								<select class="required chosen-select" id="question" name="question"> 
										<option value="0">-</option>
									@foreach($data_question as $item)
										<option value="{{ $item->id }}">{{ $item->name }}</option>
									@endforeach
								</select>
							</div>  
							
							<div class="clear"></div>

							<div class="col_full">
								<label for="template-contactform-name">{{ trans('translate.contact_email') }} <small>*</small></label>
								<input type="text" name="email" id="Email" placeholder="john@mail.com" class="sm-form-control required" />
							</div>  

							<div class="clear"></div>

							<div class="col_full">
								<label for="template-contactform-subject">{{ trans('translate.contact_subject') }} <small>*</small></label>
								<input type="text" name="subject" id="Subject" placeholder="{{ trans('translate.contact_subject_example') }}" class="required sm-form-control" />
							</div> 

							<div class="clear"></div>

							<div class="col_full">
								<label for="template-contactform-message">{{ trans('translate.contact_message') }} <small>*</small></label>
								<textarea class="required sm-form-control" name="message" rows="3" placeholder="{{ trans('translate.contact_message') }}" rows="6" cols="30"></textarea>
							</div>

							<div class="col_full">
								<div class="g-recaptcha" data-sitekey="6Le87jIUAAAAADQF_jaDT4vnN0yiKM8kFoLJdICO" ></div>
								<input type="hidden" class="hiddenRecaptcha required sm-form-control" name="hiddenRecaptcha" id="hiddenRecaptcha">
							</div>

							<div class="col_full hidden">
								<input type="text" id="template-contactform-botcheck" name="template-contactform-botcheck" value="" class="sm-form-control" />
							</div> 
							<div class="col_full">
								<button type="submit" class="button button-3d nomargin full btn-blue" type="submit" name="template-contactform-submit" value="submit">{{ trans('translate.contact_send') }}</button>
							</div>

						</form>

						</div>
						
					</div> -->

					<div class="col_one_third col_last contact-address">
							<div class="row">
										<address>
											<strong>{{ trans('translate.service_company_address') }}:</strong><br>
											Jl. Gegerkalong Hilir, Sukarasa, Sukasari<br>
											Kota Bandung, Jawa Barat 40152<br>
										</address>
										<abbr title="Customer Service"><strong>{{ trans('translate.service_company_cs') }}:</strong></abbr> (+62) 812-2483-7500<br>
										<abbr title="Email Address"><strong>{{ trans('translate.service_company_email') }}:</strong></abbr> urelddstelkom@gmail.com
									</div>
					</div>

				</div>



				</div>

			</div>

		</section><!-- #content end -->


@endsection
 

@section('content_js')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
	$(".chosen-select").chosen(); 
</script>
<script src="{{url('vendor/chosen/chosen.jquery.js')}}" type="text/javascript"></script> 
@endsection