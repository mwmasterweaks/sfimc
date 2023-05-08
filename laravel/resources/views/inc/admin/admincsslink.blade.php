
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{URL::to('public/admin/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{URL::to('public/admin/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{URL::to('public/admin/bower_components/Ionicons/css/ionicons.min.css') }}">

  <!-- DataTables -->
  <link rel="stylesheet" href="{{URL::to('public/admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{URL::to('public/admin/dist/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
  folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{URL::to('public/admin/dist/css/skins/_all-skins.min.css') }}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{URL::to('public/admin/bower_components/morris.js/morris.css') }}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{URL::to('public/admin/bower_components/jvectormap/jquery-jvectormap.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{URL::to('public/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{URL::to('public/admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{URL::to('public/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{URL::to('public/admin/bower_components/select2/dist/css/select2.min.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link href="{{ URL::to('public/admin/css/getorgchart/css/getorgchart.css') }}" rel="stylesheet" />

  <link href="{{ URL::to('public/admin/css/base.css') }}" rel="stylesheet">
  <link href="{{ URL::to('public/admin/css/custom-style.css') }}" rel="stylesheet" />
  <link href="{{ URL::to('public/admin/bower_components/jquery-ui-1.12.1/jquery-ui.css') }}" rel="stylesheet" />

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style type="text/css">
    
    .ui-autocomplete {
      z-index: 999999;
    } 
   
  </style>

        <script type="text/javascript">
            
            function showJSMessage(vHeader,vMessage,vButtonLabel){

                $("#spnMessageHeader").text(vHeader);
                $("#divMessage").text(vMessage);
                $("#spnMessageButtonLabel").text(vButtonLabel);

                $("#message-modal").modal();
            }

        </script>
