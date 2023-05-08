<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Success Formula International | Home</title> 
  
  <!-- mobile responsive meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  @include('inc.front.frontcsslink')
  @include('inc.front.frontjslink')

  <!--favicon-->
  <link rel="shortcut icon" href="{{URL::to('img/logo.png')}}">
</head>
<body>

  <!-- Load Facebook SDK for JavaScript -->
      <div id="fb-root"></div>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            xfbml            : true,
            version          : 'v7.0'
          });
        };

        (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

      <!-- Your Chat Plugin code -->
      <div class="fb-customerchat"
        attribution=setup_tool
        page_id="1231368980240947"
  theme_color="#ffc300">
      </div>


  <div class="boxed_wrapper">
    <div class="crypto-top-strip crypto-bgcolor">
      <div class="container">
        @include('inc.front.frontheader')
      </div>
    </div>

    <!-- Menu -->
    <div class="mainmenu-area stricky">
        @include('inc.front.frontnav')
    </div>

    @yield('content')


    <footer class="footer footer-classic">
      @include('inc.front.frontfooter')
      @include('inc.front.frontmodal')
    </footer>
 
    <!-- Scroll Top Button -->
    <button class="scroll-top tran3s color2_bg">
      <span class="fa fa-angle-up"></span>
    </button>

    <!-- pre loader  -->
    <div class="preloader"></div>

  </div>
  
<div id="lab-slide-bottom-popup" style="position: fixed;bottom: 0;left:10%;width: 80%;background-color:#444;z-index: 1040;display:none;border:1px solid #BEBEBE;">
  <br>
  <div style="position: relative; margin: 0 auto; padding: 0 20px;">         
    <button type="button" class="close"><span id="closediv"  aria-hidden="true">×</span></button>
    <h3 style="font-size:20px;color: #e8b918;">Important Cookie Information</h3>
  <p style="font-size:14px;color:#fff;">
    {{ config('app.COMPANY_NAME') }} uses cookies to give you the best experience on our website. By continuing to view our site, you consent to our use of cookies. For more information and details, please read our updated <a href="https://www.sfimc.org/privacy-policy#CookiePolicy" style=" color:lightblue;">privacy and cookie policy</a>. 
  </p>
  <div class="pull-right" style="margin-top:-15px;">
    <button id="acceptcookie" class="btn-primary btn-plain btn btn-lg"><i class="fa fa-check" aria-hidden="true"></i> I Agree </button>
  </div>
  <br>
  <br>
  </div>  
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

<script type="text/javascript">
jQuery(document).ready(function($) {  

  var sitevisited = getCookie("agreepboy");
  if (sitevisited!="true") {
    setTimeout(function() {
      $('#lab-slide-bottom-popup').show();
    }, 2000);     
  }    

  $(document).ready(function() {
    checkCookie();  
  });
  
  function checkCookie(){ 

    var page='{{$Page}}';    
    if (page=='home') {      
      var sitevisited = getCookie("agreepboy");     
      if (sitevisited!="true") {
          showmodalCookie();
      }     
    } 
  }

  // Get the Cookie
  function getCookie(cname) {
      var name = cname + "=";
      var ca   = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
          var  c = ca[i];
          while (c.charAt(0) == ' ') {
              c = c.substring(1);
          }
          if ( c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
          }
      }
      return "";
  }

  function setCookie(cname, cvalue, exdays) {

      var d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      var expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + "; " + expires;
  }

  function showmodalCookie(){         
     $('.lab-slide-up').find('a').attr('data-target', '#lab-slide-bottom-popup');
  }

  $("#acceptcookie").click(function(){        
      setCookie("agreepboy", "true", 30);
      $('#lab-slide-bottom-popup').hide();      
  });

  $("#closediv").click(function(){            
    $('#lab-slide-bottom-popup').hide();
  });

});

</script>


</html>

