<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="fixed dark sidebar-left-collapsed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>{{ config('app.name') }} - {{ $title }}</title>
        <link rel="icon" href="{{ url('img/icon.png') }}">
        @livewireStyles
		<meta name="keywords" content="{{ config('app.name') }} - {{ $title }}" />
		<meta name="description" content="{{ config('app.name') }} - {{ $title }}">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="{{ url('panel/vendor/bootstrap/css/bootstrap.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/animate/animate.compat.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/font-awesome/css/all.min.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/boxicons/css/boxicons.min.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/magnific-popup/magnific-popup.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/jquery-ui/jquery-ui.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/jquery-ui/jquery-ui.theme.css') }}" />

        <!-- Specific CSS -->
		<link rel="stylesheet" href="{{ url('panel/vendor/select2/css/select2.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/bootstrap-multiselect/css/bootstrap-multiselect.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/morris/morris.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/dropzone/basic.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/dropzone/dropzone.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/summernote/summernote-bs4.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/codemirror/lib/codemirror.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/codemirror/theme/monokai.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/datatables/media/css/dataTables.bootstrap5.css') }}" />
		<link rel="stylesheet" href="{{ url('panel/vendor/pnotify/pnotify.custom.css') }}" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="{{ url('panel/css/theme.css') }}" />
		<!-- Skin CSS -->
		<link rel="stylesheet" href="{{ url('panel/css/skins/default.css') }}" />
		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="{{ url('panel/css/custom.css') }}">
		<!-- Head Libs -->
		<script src="{{ url('panel/vendor/modernizr/modernizr.js') }}"></script>
                
        <!--toastr-->
		<script src="{{ url('panel/toastr/jquery-3.6.0.js') }}"></script>
		<link href="{{ url('panel/toastr/toastr.min.css') }}" rel="stylesheet">
		<script src="{{ url('panel/toastr/toastr.min.js') }}"></script>        
        <!--toastr end-->
        
        
        <!--alertify-->        
		<script src="{{ url('panel/alertify/alertify.min.js') }}"></script>
		<link rel="stylesheet" href="{{ url('panel/alertify/alertify.min.css') }}"/>
		<link rel="stylesheet" href="{{ url('panel/alertify/default.min.css') }}"/>
		<link rel="stylesheet" href="{{ url('panel/alertify/semantic.min.css') }}"/>
		<link rel="stylesheet" href="{{ url('panel/alertify/bootstrap.min.css') }}"/>
        <!--alertify end-->


	</head>
	<body>
		<hr>
		<div class="container center mt-3">
			<img src="{{ url('img/logo-1.png') }}" height="40" alt="CustomERP" />
		</div>
		<hr>
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="card-body">
						<h4>CustomERP | device record system</h4>
						<div class="table-responsive" style="min-height: 160px;">
							<table class="table table-bordered table-striped mb-0">
								<tbody>
									@foreach ($requestInfo as $key=>$req)
									<tr>
										<td>{{ $key }}</td>
										<td>{{ $req }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>

						<hr>
						<form action="{{ route('save.macinfo') }}" method="post">
							@csrf
							@if($requestInfo['mac'] == 'not detected')
							<input class="form-control" type="text" name="mac" placeholder="mac adresi giriniz... (cihazınızda mac adresi tespit edilemedi)" required>
							@endif
							
							<input class="form-control mt-2" type="text" name="user" placeholder="kullanıcı bilgisi giriniz... (ad/soyad)" required>
							<input class="form-control mt-2" type="text" name="location" placeholder="konum bilgisi ve özelliğini giriniz... (oda/kat - sabit/taşınabilir)" required>
							<select name="type" id="" class="form-control mt-2" required>
								<option value="0">CustomERP</option>
								<option value="1">şahsi mülk</option>
							</select>
							<div class="col mt-2">
								<div class="row mx-auto">
									<button type="submit" class="btn btn-block btn-primary btn-sm">
									<i class="fa fa-save"></i>
										KAYDET
									</button>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>


		@if(Session::has('success'))
			<script>

				alertify.set('notifier','position','top-right',10);
				alertify.success("{{ Session::get('success') }}",10);

			</script>
		@endif





		<!-- Vendor -->
		<script src="{{ url('panel/vendor/jquery/jquery.js') }}"></script>
		<script src="{{ url('panel/vendor/jquery-browser-mobile/jquery.browser.mobile.js') }}"></script>
		<script src="{{ url('panel/vendor/popper/umd/popper.min.js') }}"></script>
		<script src="{{ url('panel/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
		<script src="{{ url('panel/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
		<script src="{{ url('panel/vendor/common/common.js') }}"></script>
		<script src="{{ url('panel/vendor/nanoscroller/nanoscroller.js') }}"></script>
		<script src="{{ url('panel/vendor/magnific-popup/jquery.magnific-popup.js') }}"></script>
		<script src="{{ url('panel/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>

		<!-- Specific Page Vendor -->
		<script src="{{ url('panel/vendor/jquery-ui/jquery-ui.js') }}"></script>
		<script src="{{ url('panel/vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js') }}"></script>
		<script src="{{ url('panel/vendor/select2/js/select2.js') }}"></script>
		<script src="{{ url('panel/vendor/jquery-appear/jquery.appear.js') }}"></script>
		<script src="{{ url('panel/vendor/bootstrapv5-multiselect/js/bootstrap-multiselect.js') }}"></script>
		<script src="{{ url('panel/vendor/ios7-switch/ios7-switch.js') }}"></script>
		<script src="{{ url('panel/vendor/pnotify/pnotify.custom.js') }}"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="{{ url('panel/js/theme.js') }}"></script>

		<!-- Theme Custom -->
		<script src="{{ url('panel/js/custom.js') }}"></script>

		<!-- Theme Initialization Files -->
		<script src="{{ url('panel/js/theme.init.js') }}"></script>

        @livewireScripts

        <script>
            window.livewire.on('alert', param => {
                toastr[param['type']](param['message']);
            });
        </script>


	</body>
</html>
