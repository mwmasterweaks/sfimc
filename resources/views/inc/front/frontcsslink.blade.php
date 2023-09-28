  <link rel="stylesheet" href="{{ asset(config('app.src_name') . 'front/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset(config('app.src_name') . 'front/css/responsive.css') }}">
  <link rel="stylesheet" href="{{ asset(config('app.src_name') . 'front/fonts/flaticon.css') }}" />

  <script type="text/javascript">
      
      function showJSMessage(vHeader,vMessage,vButtonLabel){

          $("#spnMessageHeader").text(vHeader);
          $("#divMessage").text(vMessage);
          $("#spnMessageButtonLabel").text(vButtonLabel);

          $("#message-modal").modal();
      }

  </script>
