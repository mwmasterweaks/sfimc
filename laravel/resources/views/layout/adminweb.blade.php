<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Admin Dashboard | Success Formula Intenational</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!--favicon-->
    <link rel="shortcut icon" href="{{URL::to('img/logo.png')}}">

      <!-- jQuery 3 -->
      <script src="{{URL::to('admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="{{URL::to('admin/bower_components/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
    @include('inc.admin.admincsslink')

    @include('inc.admin.adminjslink')

</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

    	@include('inc.admin.adminheader')

	      <div id="divLoader" style="display: none;">
	      </div>
	          
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			@yield('content')
		</div>
  
  		@include('inc.admin.adminfooter')
    	@include('inc.admin.adminmodal')

	</div>

</body>

<style type="text/css">

  #divLoader{
    position:fixed;
    top: 50%;
    left: 50%;
    margin-top: -100px;
    margin-left: -100px;
    width:200px;
    height:200px;
    background-color:#fff;
    background-image:url('{{ URL::to('img/loader.gif') }}');
    background-size: 200px 200px;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    filter: alpha(opacity=40); /* For IE8 and earlier */
  }  

</style>

</html>

