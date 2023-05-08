<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Member Dashboard | Success Formula Intenational</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!--favicon-->
    <link rel="shortcut icon" href="{{URL::to('public/img/logo.png')}}">

      <!-- jQuery 3 -->
      <script src="{{URL::to('public/admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="{{URL::to('public/admin/bower_components/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
    @include('inc.admin.admincsslink')

    @include('inc.admin.adminjslink')

</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
  	@include('inc.member.memberheader')
    
	          
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			@yield('content')
		</div>
  
  		@include('inc.admin.adminfooter')
    	@include('inc.admin.adminmodal')

	</div>

</body>



</html>

