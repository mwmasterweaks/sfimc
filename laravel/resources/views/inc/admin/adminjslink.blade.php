  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="{{URL::to('admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

  <!-- Select2 -->
  <script src="{{URL::to('admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

  <!-- Morris.js charts -->
  <script src="{{URL::to('admin/bower_components/raphael/raphael.min.js') }}"></script>
  <!--
  <script src="{{URL::to('admin/bower_components/morris.js/morris.min.js') }}"></script>
  -->

  <!-- Sparkline -->
  <script src="{{URL::to('admin/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
  <!-- jvectormap -->
  <script src="{{URL::to('admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
  <script src="{{URL::to('admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{URL::to('admin/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="{{URL::to('admin/bower_components/moment/min/moment.min.js') }}"></script>
  <script src="{{URL::to('admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
  <!-- datepicker -->
  <script src="{{URL::to('admin/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="{{URL::to('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
  <!-- DataTables -->
  <script src="{{URL::to('admin/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{URL::to('admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
  <!-- Slimscroll -->
  <script src="{{URL::to('admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
  <!-- FastClick -->
  <script src="{{URL::to('admin/bower_components/fastclick/lib/fastclick.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{URL::to('admin/bower_components/chart.js/Chart.js') }}"></script>

  <!-- getorgchart -->
  <script src="{{ URL::to('admin/css/getorgchart/js/getorgchart.js') }}" type="text/javascript"></script>

  <!-- AdminLTE App -->
  <script src="{{URL::to('admin/dist/js/adminlte.min.js') }}"></script>

  <!-- Tiny MCE -->
  <script src="https://cdn.tiny.cloud/1/1tkg4r3githfe96sp1bdcc33hlgy1574gd53cbul5b0yalth/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

  <style type="text/css">
    .borderRequired{
      border-color: #a94442;
      border-width:1px;
      border-style: solid;
    }    
  </style>
  
  <script type="text/javascript">

    function formatDateTimeAMPM(date){

      var month = date.getMonth() + 1;
      var day = date.getDate() + 0;
      var year = date.getFullYear();

      var hours = date.getHours() + 0;
      var minutes = date.getMinutes() + 0;
      var ampm = hours >= 12 ? 'PM' : 'AM';
      hours = hours % 12;
      hours = hours ? hours : 12; // the hour '0' should be '12'
      minutes = minutes < 10 ? '0'+minutes : minutes;

      var strDateTime = year + "-" + (month > 9 ? "" + month: "0" + month) + "-" + (day > 9 ? "" + day: "0" + day) + " " + (hours > 9 ? "" + hours: "0" + hours) + ':' + minutes + ' ' + ampm;

      return strDateTime;
    }

    function FormatDecimal(vValue, vDecimal){
      var vReturn
      try {
        vReturn = vValue.toString().replace(",","");
        vReturn = parseFloat(vValue);
        vReturn = vReturn.toFixed(vDecimal);
      }
      catch(err) {
        vReturn = 0;
      }

      return vReturn;
    }

    function showMessage(vType,vMessage){
      if(vType == "Error"){
        $("#divErrorMsg").show();
        $("#spnErrorMsg").text(vMessage);
      }else if(vType == "Info"){
        $("#divInfoMsg").show();
        $("#spnInfoMsg").text(vMessage);
      }else if(vType == "Warning"){
        $("#divWarningMsg").show();
        $("#spnWarningMsg").text(vMessage);
      }else if(vType == "Success"){
        $("#divSuccessMsg").show();
        $("#spnSuccessMsg").text(vMessage);
      }
    }

    function hideMessage(){
      $("#divErrorMsg").hide();
      $("#divInfoMsg").hide();
      $("#divWarningMsg").hide();
      $("#divSuccessMsg").hide();
    }

    function showJSModalMessageJS(vHeader,vMessage,vButtonLabel){
        $("#spnMessageHeader").text(vHeader);
        $("#divMessage").text(vMessage);
        $("#spnMessageButtonLabel").text(vButtonLabel);

        $("#message-modal").modal();
    }

    function CheckRequiredField(vID){
      if($.trim($("#"+vID).val()) == ""){
        $("#"+vID).addClass("borderRequired");
        return true;
      }else{
        $("#"+vID).removeClass("borderRequired");
      }
      return false;
    }

    function buttonOneClick(vID, vLabel, vIsDisabled){
      var btn = $("#"+vID);
      if(vIsDisabled){
        btn.html('<img src="{{ URL::to('img/button-loader.gif') }}" style="max-height:15px;">');
        btn.attr("disabled", true);
      }else{
        btn.html(vLabel);
        btn.removeAttr("disabled");
      }
    }

    $(function() {
      $("form").submit(function() {
                var btn = $(".one-click");
                btn.html('<img src="{{ URL::to('img/button-loader.gif') }}" style="max-height:15px;">');
                btn.attr("disabled", true);
      })

      //Initialize Select2 Elements
      $('.select2').select2();

    })

    tinymce.init({
        selector: ".wysiwyg",
        plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        tinycomments_mode: 'embedded',
        tinycomments_author: 'F and A IT Services',
        code_dialog_width: 800,
        code_dialog_height: 500,
    });

    $(function () {

          $(".DecimalOnly").on("keypress keyup blur",function (event) {
              $(this).val($(this).val().replace(/[^0-9\.]/g,''));
              if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                  event.preventDefault();
              }
          });

          $(".numberonly").on("keypress keyup blur",function (event) {
             $(this).val($(this).val().replace(/[^\d].+/, ""));
              if ((event.which < 48 || event.which > 57)) {
                  event.preventDefault();
              }
          });

          $(".moneyonly").on("keypress keyup blur",function (event) {
            if(event.which >= 37 && event.which <= 40) return;
            $(this).val(function(index, value) {
              return value
              .replace(/\D/g, "")
              .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
              ;
            });
          });

          $('.alphamumericonly').keypress(function (e) {
              var regex = new RegExp("^[a-zA-Z0-9]+$");
              var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
              if (regex.test(str)) {
                  return true;
              }

              e.preventDefault();
              return false;
          });

          function GetUrlParameters(vUrl) {

              vUrl = vUrl || window.location.search.substring(1);
              var urlParams = {};
              var match,
                  pl     = /\+/g,  // Regex for replacing addition symbol with a space
                  search = /([^&=]+)=?([^&]*)/g,
                  decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
                  query  = vUrl;

              while (match = search.exec(query))
                 urlParams[decode(match[1])] = decode(match[2]);

              return urlParams;

          };

    })

  </script>

  <script type="text/javascript">

    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function(){
        var output = document.getElementById('output');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };

  </script>
  <!-- FB -->
 {{--  <div id="fb-root"></div>
  <script>
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
  </script>

  <script>
  !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
  </script>
  --}} 
