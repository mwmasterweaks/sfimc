

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Member Login | SUCCESS FORMULA INTL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link rel="stylesheet" type="text/css" href="{{ asset(config('app.src_name') . 'admin/css/admin-login.css') }}">
    <!--favicon-->
    <link rel="shortcut icon" href="{{ asset(config('app.src_name') . 'img/logo.png')}}">
    <link type="text/css" rel="stylesheet" href="{{ asset(config('app.src_name') . 'admin/css/assets/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset(config('app.src_name') . 'admin/css/assets/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset(config('app.src_name') . 'admin/css/assets/fonts/flaticon/font/flaticon.css') }}">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset(config('app.src_name') . 'img/logo.png')}}">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset(config('app.src_name') . 'admin/css/assets/css/style.css') }}">

</head>
<body id="top">
<div class="page_loader"></div>

<!-- Login 6 start -->
<div class="login-6">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 col-lg-7 col-md-12 bg-img">
                <div class="info">
                    <div class="waviy">
                        <span style="--i:1;font-size:25px">W</span>
                        <span style="--i:2;font-size:25px">e</span>
                        <span style="--i:3;font-size:25px">l</span>
                        <span style="--i:4;font-size:25px">c</span>
                        <span style="--i:5;font-size:25px">o</span>
                        <span style="--i:6;font-size:25px">m</span>
                        <span style="--i:7;font-size:25px">e</span>
                        <span class="color-yellow" style="--i:8;font-size:28px">t</span>
                        <span class="color-yellow" style="--i:9;font-size:28px">o</span>

                        
             <br>
                        <span style="--i:1;font-size:28px;color:green">S</span>
                        <span style="--i:2;font-size:25px">U</span>
                        <span style="--i:3;font-size:25px">C</span>
                        <span style="--i:4;font-size:25px">C</span>
                        <span style="--i:5;font-size:25px">E</span>
                        <span style="--i:6;font-size:25px">S</span>
                        <span style="--i:7;font-size:25px">S</span>
                         <span style="--i:1;font-size:28px;color:green">F</span>
                        <span style="--i:2;font-size:25px">O</span>
                        <span style="--i:3;font-size:25px">R</span>
                        <span style="--i:4;font-size:25px">M</span>
                        <span style="--i:5;font-size:25px">U</span>
                        <span style="--i:6;font-size:25px">L</span>
                        <span style="--i:7;font-size:25px">A</span>
                        
                         <span style="--i:1;font-size:28px;color:green">I</span>
                        <span style="--i:2;font-size:25px">N</span>
                        <span style="--i:3;font-size:25px">T</span>
                        <span style="--i:4;font-size:25px">L</span>
                       
                    </div>
                    <p>Please click on login to continue, username & password required.</p>
                      <div class="admin-sidebar-info">
                                  <ul>
                                      <li><a href="{{ URL::route('home') }}" style="color:green;">Go To Main Site</a></li>
                                  </ul>
                                </div>
                </div>
                <div class="bg-photo">
                    <img src="{{ asset(config('app.src_name') . 'admin/css/assets/img/img-6.png') }}" alt="bg" class="img-fluid">
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-12 bg-color-6" style = "background: linear-gradient(110.1deg, rgb(34, 126, 34) 2.9%, rgb(168, 251, 60) 90.3%);">
                <div class="form-section" id="divLogin" >
                    <div class="logo">
                        <a href="/">
                            <img src="{{ asset(config('app.src_name') . 'admin/css/assets/img/logos/logo.png') }}" alt="logo" style = "width:120px;height:120px">
                        </a>
                    </div>
                    <h3 style = "color:#fff" >Member Login</h3>
                    <div class="login-inner-form">
                        <form action="{{ route('do-member-login') }}" method="post">
                             @if(session('Success_Msg'))
               <div class="alert alert-success alert-dismissible" style="border-left:3px solid #3c763d;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{session('Success_Msg')}}
                </div>
              @endif

              @if(session('Error_Msg'))
               <div class="alert alert-danger alert-dismissible" style="border-left:3px solid #a94442;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      {{session('Error_Msg')}}
                </div>
              @endif
                            <div class="form-group clearfix">
                                <label for="first_field" class="form-label" style = "color:#fff"></label>
                                <div class="form-box">
                                    <input name="EntryCode" type="text" class="form-control" id="" placeholder="IBO No." aria-label="Email Address">
                                    <i class="flaticon-user"></i>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="second_field" class="form-label"style = "color:#fff"></label>
                                <div class="form-box">
                                    <input name="UserPassword" type="password" style = "color:#121212" id="inputStylePassword" class="form-control" autocomplete="off" id="second_field" placeholder="Password" aria-label="Password">
                                    <i class="flaticon-password"></i>
                                </div>
                            </div>
                            <div class="checkbox form-group clearfix">
                                <div class="form-check float-start">
                                    <input class="form-check-input" type="checkbox" id="rememberme" onclick="myFunction()">
                                    <label class="form-check-label" for="rememberme">
                                       Show Password
                                    </label>
                                </div>
                                <div class="form-group clearfix mb-0" style="float: right;">
                                    <a href="{{ URL::route('forgot-password') }}">Forgot Password?</a>
                                </div>
                            </div>
                            <div class="form-group clearfix mb-0">
                                <button type="submit" class="btn btn-primary btn-lg btn-theme">Login</button>
                            </div>
                        </form>
                    
                     
                    </div>
                   <p style = "font-size:12px;text-align:center;color:#121212">
                         <br>
                         <br>
                          <br>
                           <br>
                            <br>
                            © 2021 Success Formula International | All rights reserved.
                        </p>
                </div>
            </div>
        </div>
    </div>
</div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script type="text/javascript">


$(document).ready(function(){

  $("#divLogin").show();

    $("#btnLogin").click(function(){
        $("#divLogin").slideToggle();
        $("#divLogin").show();
    });

});

function myFunction() {
    var x = document.getElementById("inputStylePassword");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}


</script>
<!-- Login 6 end -->

<!-- External JS libraries -->
<script src="resources/views/admin/assets/js/jquery-3.6.0.min.js"></script>
<script src="resources/views/admin/assets/js/bootstrap.bundle.min.js"></script>
<script src="resources/views/admin/assets/js/jquery.validate.min.js"></script>
<script src="resources/views/admin/assets/js/app.js"></script>
<!-- Custom JS Script -->

</body>

</html>

