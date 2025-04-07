<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="fixed {{ (Auth::user()->theme) ? 'dark' : '' }} {{ (Auth::user()->sidebar) ? 'sidebar-left-collapsed' : '' }}">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>{{ config('app.name') }} - @yield('title')</title>
        <link rel="icon" href="{{ url('img/fun_icon.png') }}">
        @livewireStyles
		<meta name="keywords" content="{{ config('app.name') }} - @yield('title')" />
		<meta name="description" content="{{ config('app.name') }} - @yield('title')">

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

		@include('parts.pagination-style')

		@yield('css')

	</head>
	<body>
		<section class="body">

            @include('parts.header')
			
			<div class="inner-wrapper">

                <livewire:lw-sidebar />

				<section role="main" class="content-body">

                    @include('parts.pageHeader')

					<!-- start: page -->
                    @yield('content')
					<!-- end: page -->
				</section>
			</div>

            @include('parts.rightbar')		
			

		</section>

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
		@yield('js')
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
