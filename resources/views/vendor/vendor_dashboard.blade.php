<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{asset('adminbackend/assets/images/favicon-32x32.png')}}" type="image/png" />
	<link href="{{ asset('adminbackend/assets/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet" />
	<!--plugins-->
	<link href="{{asset('adminbackend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/>
	<link href="{{asset('adminbackend/assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
	<link href="{{asset('adminbackend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
	<link href="{{asset('adminbackend/assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{asset('adminbackend/assets/css/pace.min.css')}}" rel="stylesheet" />
	<script src="{{asset('adminbackend/assets/js/pace.min.js')}}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{asset('adminbackend/assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('adminbackend/assets/css/app.css')}}" rel="stylesheet">
	<link href="{{asset('adminbackend/assets/css/icons.css')}}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{asset('adminbackend/assets/css/dark-theme.css')}}" />
	<link rel="stylesheet" href="{{asset('adminbackend/assets/css/semi-dark.css')}}" />
	<link rel="stylesheet" href="{{asset('adminbackend/assets/css/header-colors.css')}}" />
	<!-- toastr CSS -->
	 <!-- DataTable -->
	<link href="{{asset('adminbackend/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<title>Vendor Dashboard</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
           @include('vendor.body.sidebar')
		<!--end sidebar wrapper -->
		<!--start header -->
	        @include('vendor.body.header')
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
		  @yield('vendor')
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
	     @include('vendor.body.footer')
	</div>
	<!--end wrapper-->

	<!-- Bootstrap JS -->
	<script src="{{asset('adminbackend/assets/js/jquery.min.js')}}"></script>
	<!-- Bootstrap JS -->
	<script src="{{asset('adminbackend/assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	
	<script src="{{asset('adminbackend/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/chartjs/js/Chart.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('adminbackend/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/sparkline-charts/jquery.sparkline.min.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/jquery-knob/excanvas.js')}}"></script>
	<script src="{{asset('adminbackend/assets/plugins/jquery-knob/jquery.knob.js')}}"></script>
	
	  <script>
		  $(function() {
			  $(".knob").knob();
		  });
	  </script>
	  
	 
	  <script src="{{asset('adminbackend/assets/js/index.js')}}"></script> 
	  <script src="{{asset('adminbackend/assets/js/validate.min.js')}}"></script>
	  	
	 <!-- DataTable -->
	  <script src="{{asset('adminbackend/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	  <script>
		$(document).ready(function() {
			$('#example').DataTable();
		  } );
	 </script>

	  
     <!-- SweetAlert2 -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	 
	   <!-- Your delete confirmation JS -->
     <script src="{{asset('adminbackend/assets/js/code.js')}}"></script>

	  <script src="{{ asset('adminbackend/assets/plugins/input-tags/js/tagsinput.js') }}"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
	 
	  <!-- Toastr -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<!--app JS-->
	<script src="{{asset('adminbackend/assets/js/app.js')}}"></script>

	<script>
      @if(Session::has('message'))
      var type = "{{ Session::get('alert-type','info') }}"
      switch(type){
        case 'info':
        toastr.info(" {{ Session::get('message') }} ");
        break;
         
		case 'success':
        toastr.success(" {{ Session::get('message') }} ");
        break;


        case 'warning':
        toastr.warning(" {{ Session::get('message') }} ");
        break;

        case 'error':
        toastr.error(" {{ Session::get('message') }} ");
        break; 
     }
     @endif 
	</script>
    
	<script>
		document.addEventListener("DOMContentLoaded", function () {

			if(document.querySelector('#mytextarea')){

				tinymce.remove('#mytextarea');

				tinymce.init({
					selector: '#mytextarea',

					height: 300,

					license_key: 'gpl',

					branding: false,
					promotion: false,

					plugins: [
						'paste',
						'lists',
						'link',
						'image',
						'table',
						'code'
					],

					toolbar:
						'undo redo | bold italic underline | ' +
						'alignleft aligncenter alignright | ' +
						'bullist numlist | code',

					paste_as_text: false
				});

			}

		});
    </script>

</body>

</html>